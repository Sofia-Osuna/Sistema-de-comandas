<?php
// includes/acciones-metodos-pago.php
// Comprobaciones COMUNES de las acciones de métodos de pago. Cada archivo de
// public/acciones/metodo-pago-*.php lo incluye en su primera línea, así ninguna
// acción puede olvidarse de revisar sesión, rol, método HTTP y CSRF.
//
// Al terminar deja disponibles: $modelo (MetodoPago), $idUsuario, $ip y redirigir().

require_once __DIR__ . '/csrf.php';          // inicia la sesión

const PAGINA_METODOS_PAGO = '../paginas/metodos-pagos.php';

function redirigir(array $flash = []): void
{
    if ($flash) {
        $_SESSION['flash_metodos'] = $flash;
    }
    header('Location: ' . PAGINA_METODOS_PAGO);
    exit;
}

// 1) Sesión y rol.
// TODO auth: cuando exista includes/auth.php, cambiar este bloque por su función.
if (empty($_SESSION['pk_usuario']) || ($_SESSION['rol'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado.');
}
$idUsuario = (int) $_SESSION['pk_usuario'];

// 2) Solo POST (nunca un GET que modifique datos).
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método no permitido.');
}

// 3) CSRF.
if (!csrf_validar($_POST['csrf_token'] ?? null)) {
    redirigir(['ok' => false, 'mensaje' => 'La sesión del formulario expiró. Intenta de nuevo.']);
}

// 4) Conexión y modelo. Un error de conexión no debe mostrar rutas ni claves.
try {
    require_once __DIR__ . '/../modelos/Conexion.php';
    require_once __DIR__ . '/../modelos/MetodoPago.php';
    $modelo = new MetodoPago((new Conexion())->conectar());
} catch (Throwable $e) {
    error_log('[metodos-pago] ' . $e->getMessage());
    redirigir(['ok' => false, 'mensaje' => 'No se pudo conectar con la base de datos.']);
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '';

// Lee un campo del POST como texto; si llega un arreglo (metodo[]=x) lo ignora.
function post_texto(string $campo): string
{
    $v = $_POST[$campo] ?? '';
    return is_string($v) ? $v : '';
}
