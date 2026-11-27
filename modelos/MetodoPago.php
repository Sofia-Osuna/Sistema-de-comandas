<?php
// modelos/MetodoPago.php
// Toda la lógica de la tabla metodos_pago (PDO). Las páginas y acciones no escriben SQL.

class MetodoPago
{
    public const LONGITUD_MIN = 2;
    public const LONGITUD_MAX = 30;

    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /** Lista los métodos. Con $soloActivos = true solo trae estatus '1' (para cobrar ventas). */
    public function listar(bool $soloActivos = false): array
    {
        $sql = "SELECT pk_metodo_pago, metodo, estatus FROM metodos_pago";
        if ($soloActivos) {
            $sql .= " WHERE estatus = '1'";
        }
        $sql .= " ORDER BY metodo ASC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT pk_metodo_pago, metodo, estatus FROM metodos_pago WHERE pk_metodo_pago = :id"
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $fila ?: null;
    }

    /** Limpia y valida el nombre. Devuelve [nombre_limpio, error]. */
    public function validarNombre(string $metodo): array
    {
        // quita espacios al inicio/final, colapsa espacios repetidos y guarda en minúsculas
        $metodo = trim((string) preg_replace('/\s+/u', ' ', $metodo));
        $metodo = mb_strtolower($metodo, 'UTF-8');

        if ($metodo === '') {
            return [null, 'El nombre del método de pago es obligatorio.'];
        }

        $largo = mb_strlen($metodo, 'UTF-8');
        if ($largo < self::LONGITUD_MIN || $largo > self::LONGITUD_MAX) {
            return [null, 'El nombre debe tener entre ' . self::LONGITUD_MIN . ' y ' . self::LONGITUD_MAX . ' caracteres.'];
        }

        // letras (con acentos), números, espacios y . - /
        if (!preg_match('/^[\p{L}\p{N}][\p{L}\p{N} .\-\/]*$/u', $metodo)) {
            return [null, 'El nombre contiene caracteres no permitidos.'];
        }

        return [$metodo, null];
    }

    public function crear(string $metodo, string $estatus, int $idUsuario, string $ip): array
    {
        [$metodo, $error] = $this->validarNombre($metodo);
        if ($error !== null) {
            return ['ok' => false, 'mensaje' => $error, 'campo' => 'metodo'];
        }
        if ($estatus !== '0' && $estatus !== '1') {
            return ['ok' => false, 'mensaje' => 'Estatus no válido.'];
        }

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                "INSERT INTO metodos_pago (metodo, estatus) VALUES (:metodo, :estatus)"
            );
            $stmt->bindValue(':metodo', $metodo, PDO::PARAM_STR);
            $stmt->bindValue(':estatus', $estatus, PDO::PARAM_STR);
            $stmt->execute();
            $nuevoId = (int) $this->conn->lastInsertId();

            $this->registrarBitacora($idUsuario, 'metodo_pago_crear', "Alta: $metodo (id $nuevoId)", $ip);

            $this->conn->commit();
            return ['ok' => true, 'mensaje' => 'Método de pago registrado correctamente.'];
        } catch (PDOException $e) {
            return $this->manejarError($e);
        }
    }

    public function actualizar(int $id, string $metodo, string $estatus, int $idUsuario, string $ip): array
    {
        [$metodo, $error] = $this->validarNombre($metodo);
        if ($error !== null) {
            return ['ok' => false, 'mensaje' => $error, 'campo' => 'metodo'];
        }
        if ($estatus !== '0' && $estatus !== '1') {
            return ['ok' => false, 'mensaje' => 'Estatus no válido.'];
        }
        if ($this->obtener($id) === null) {
            return ['ok' => false, 'mensaje' => 'El método de pago que intentas editar no existe.'];
        }

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                "UPDATE metodos_pago SET metodo = :metodo, estatus = :estatus WHERE pk_metodo_pago = :id"
            );
            $stmt->bindValue(':metodo', $metodo, PDO::PARAM_STR);
            $stmt->bindValue(':estatus', $estatus, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->registrarBitacora($idUsuario, 'metodo_pago_editar', "Edición: id $id -> $metodo (estatus $estatus)", $ip);

            $this->conn->commit();
            return ['ok' => true, 'mensaje' => 'Método de pago actualizado correctamente.'];
        } catch (PDOException $e) {
            return $this->manejarError($e);
        }
    }

    /**
     * Baja lógica ('0') o reactivación ('1'). No hay DELETE porque
     * ventas.fk_metodo_pago tiene ON DELETE RESTRICT.
     * Recibe el estatus al que se quiere llegar (no lo invierte), así una pestaña
     * desactualizada no deshace el cambio de otra persona.
     */
    public function cambiarEstatus(int $id, string $estatus, int $idUsuario, string $ip): array
    {
        if ($estatus !== '0' && $estatus !== '1') {
            return ['ok' => false, 'mensaje' => 'Estatus no válido.'];
        }

        $actual = $this->obtener($id);
        if ($actual === null) {
            return ['ok' => false, 'mensaje' => 'El método de pago no existe.'];
        }

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                "UPDATE metodos_pago SET estatus = :estatus WHERE pk_metodo_pago = :id"
            );
            $stmt->bindValue(':estatus', $estatus, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $accion  = $estatus === '1' ? 'metodo_pago_activar' : 'metodo_pago_baja';
            $detalle = ($estatus === '1' ? 'Reactivado' : 'Dado de baja') . ": {$actual['metodo']} (id $id)";
            $this->registrarBitacora($idUsuario, $accion, $detalle, $ip);

            $this->conn->commit();
            return [
                'ok' => true,
                'mensaje' => $estatus === '1'
                    ? 'Método de pago activado.'
                    : 'Método de pago desactivado. Ya no se podrá usar al cobrar.',
            ];
        } catch (PDOException $e) {
            return $this->manejarError($e);
        }
    }

    // ---------------------------------------------------------------

    private function registrarBitacora(int $idUsuario, string $accion, string $detalle, string $ip): void
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO bitacora (fk_usuario, accion, detalle, ip, fecha, hora)
             VALUES (:usuario, :accion, :detalle, :ip, CURDATE(), CURTIME())"
        );
        $stmt->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':accion', $accion, PDO::PARAM_STR);
        $stmt->bindValue(':detalle', $detalle, PDO::PARAM_STR);
        $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
        $stmt->execute();
    }

    private function manejarError(PDOException $e): array
    {
        if ($this->conn->inTransaction()) {
            $this->conn->rollBack();
        }

        // 1062 = entrada duplicada (UNIQUE de metodo)
        if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
            return ['ok' => false, 'mensaje' => 'Ya existe un método de pago con ese nombre.', 'campo' => 'metodo'];
        }

        // el detalle técnico va al log del servidor, no al usuario
        error_log('[MetodoPago] ' . $e->getMessage());
        return ['ok' => false, 'mensaje' => 'Ocurrió un error inesperado. Intenta de nuevo.'];
    }
}
