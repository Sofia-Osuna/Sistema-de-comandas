<?php
/**
 * Beanora - Formulario de Mesa (nueva / editar)
 * Ubicación: public/paginas/mesa_form.php
 *
 *   mesa_form.php          -> nueva mesa
 *   mesa_form.php?id=4     -> editar la mesa con pk_mesa = 4
 *
 * Campos según el diccionario de datos (tabla `mesas`):
 *   cod_mesa              VARCHAR(10)  NOT NULL, UNIQUE  (ej. M-01)
 *   fk_usuario_encargado  INT NULL     -> usuarios.pk_usuario (rol mesero). NULL = sin asignar
 *   estatus               CHAR(1)      '1' = disponible, '0' = fuera de servicio
 *   estado                (libre | ocupada | esperando_cuenta) NO se captura aquí:
 *                         nace como 'libre' y lo cambia la operación del salón.
 *
 * Los arreglos de abajo son de ejemplo. Sustitúyelos por consultas a la BD
 * (ver los TODO) cuando tengas el modelo de mesas y usuarios.
 */

$paginaActual = 'mesas'; // por si el sidebar marca el ítem activo con esta variable

/* ---------- Datos de ejemplo ---------- */

// TODO BD: SELECT pk_usuario, nombres, apellido_paterno FROM usuarios u
//          JOIN roles r ON r.pk_rol = u.fk_rol WHERE r.rol = 'mesero' AND u.estatus = '1'
$meseros = [
    2 => 'Carlos Morales',
    3 => 'Ana Ruiz',
    6 => 'Luis Gómez',
];

// TODO BD: SELECT pk_mesa, cod_mesa, fk_usuario_encargado, estado, estatus FROM mesas
$mesasEjemplo = [
    1 => ['cod_mesa' => 'M-01', 'fk_usuario_encargado' => 2,    'estado' => 'libre',            'estatus' => '1'],
    2 => ['cod_mesa' => 'M-02', 'fk_usuario_encargado' => 3,    'estado' => 'ocupada',          'estatus' => '1'],
    3 => ['cod_mesa' => 'M-03', 'fk_usuario_encargado' => null, 'estado' => 'libre',            'estatus' => '1'],
    4 => ['cod_mesa' => 'M-04', 'fk_usuario_encargado' => 2,    'estado' => 'ocupada',          'estatus' => '1'],
    5 => ['cod_mesa' => 'M-05', 'fk_usuario_encargado' => 6,    'estado' => 'esperando_cuenta', 'estatus' => '1'],
    6 => ['cod_mesa' => 'M-06', 'fk_usuario_encargado' => 3,    'estado' => 'ocupada',          'estatus' => '1'],
    7 => ['cod_mesa' => 'M-07', 'fk_usuario_encargado' => null, 'estado' => 'libre',            'estatus' => '0'],
    8 => ['cod_mesa' => 'M-08', 'fk_usuario_encargado' => 6,    'estado' => 'esperando_cuenta', 'estatus' => '1'],
];

$estadosMesa = [
    'libre'            => ['Libre',          'libre'],
    'ocupada'          => ['Ocupada',        'ocupada'],
    'esperando_cuenta' => ['Esperando Cta.', 'cuenta'],
];

/* ---------- Modo: nueva o editar ---------- */
$id           = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$modoEdicion  = $id > 0;
$mesaActual   = null;

if ($modoEdicion) {
    $mesaActual = $mesasEjemplo[$id] ?? null;        // TODO BD: buscar por pk_mesa
    if ($mesaActual === null) {
        header('Location: mesas.php');
        exit;
    }
}

$datos = [
    'cod_mesa'             => $mesaActual['cod_mesa'] ?? '',
    'fk_usuario_encargado' => $mesaActual['fk_usuario_encargado'] ?? '',
    'estatus'              => $mesaActual['estatus'] ?? '1',
];

/* ---------- Guardado + validaciones (el servidor es la autoridad) ---------- */
$errores = [];
$exito   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod     = strtoupper(trim($_POST['cod_mesa'] ?? ''));
    $mesero  = trim($_POST['fk_usuario_encargado'] ?? '');
    $estatus = isset($_POST['estatus']) ? '1' : '0';

    // cod_mesa: obligatorio, máx. 10 caracteres, único
    if ($cod === '') {
        $errores['cod_mesa'] = 'El código de la mesa es obligatorio.';
    } elseif (mb_strlen($cod) > 10) {
        $errores['cod_mesa'] = 'El código no puede pasar de 10 caracteres.';
    } else {
        foreach ($mesasEjemplo as $pk => $m) {   // TODO BD: SELECT 1 FROM mesas WHERE cod_mesa = ? AND pk_mesa <> ?
            if ($pk !== $id && strcasecmp($m['cod_mesa'], $cod) === 0) {
                $errores['cod_mesa'] = "El código $cod ya está registrado en otra mesa.";
                break;
            }
        }
    }

    // fk_usuario_encargado: vacío = NULL (sin asignar); si viene, debe ser un usuario con rol mesero
    $mesero = ($mesero === '') ? null : (int)$mesero;
    if ($mesero !== null && !isset($meseros[$mesero])) {
        $errores['fk_usuario_encargado'] = 'Selecciona un mesero válido.';
    }

    $datos = ['cod_mesa' => $cod, 'fk_usuario_encargado' => $mesero ?? '', 'estatus' => $estatus];

    if (!$errores) {
        /* TODO BD:
         *   nueva  -> INSERT INTO mesas (cod_mesa, fk_usuario_encargado, estatus) VALUES (?, ?, ?)   -- estado = 'libre' por defecto
         *   editar -> UPDATE mesas SET cod_mesa = ?, fk_usuario_encargado = ?, estatus = ? WHERE pk_mesa = ?
         * Después: header('Location: mesas.php'); exit;
         */
        $exito = $modoEdicion ? "Mesa $cod actualizada correctamente." : "Mesa $cod registrada correctamente.";
        if (!$modoEdicion) {
            $datos = ['cod_mesa' => '', 'fk_usuario_encargado' => '', 'estatus' => '1'];
        }
    }
}

$titulo = $modoEdicion ? 'Editar Mesa' : 'Nueva Mesa';
$estadoActual = $mesaActual['estado'] ?? 'libre';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beanora - <?= htmlspecialchars($titulo) ?></title>

    <!-- Bootstrap 5 + Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">

    <!-- Estilos del proyecto (esta página está en public/paginas/) -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/modulos-admin.css">
</head>
<body>

<div class="d-flex flex-column flex-lg-row">

    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="contenido-panel flex-grow-1 p-3 p-md-4 p-lg-5">

        <!-- ================= Encabezado ================= -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <a href="mesas.php" class="enlace-acento d-inline-flex align-items-center gap-1 mb-2">
                    <i class="bi bi-arrow-left"></i> Volver al Tablero de Mesas
                </a>
                <h1 class="bn-titulo">
                    <?= htmlspecialchars($titulo) ?><?= $modoEdicion ? ' <span class="bn-form-cod">' . htmlspecialchars($mesaActual['cod_mesa']) . '</span>' : '' ?>
                </h1>
                <p class="bn-subtitulo">
                    <?= $modoEdicion
                        ? 'Modifica el código, el mesero asignado o el estatus de la mesa.'
                        : 'Registra una mesa nueva en el salón. Quedará como Libre al crearse.' ?>
                </p>
            </div>
        </div>

        <!-- ================= Formulario ================= -->
        <form id="formMesa" method="post" novalidate class="bn-card bn-form-card">
            <div class="p-3 p-md-4">
                <div class="row g-3 g-md-4">

                    <!-- Código de mesa -->
                    <div class="col-12 col-md-6">
                        <label for="codMesa" class="bn-label">Código de mesa</label>
                        <input type="text" id="codMesa" name="cod_mesa"
                               class="bn-input w-100 <?= isset($errores['cod_mesa']) ? 'is-invalid' : '' ?>"
                               value="<?= htmlspecialchars($datos['cod_mesa']) ?>"
                               maxlength="10" required placeholder="Ej. M-01" autocomplete="off">
                        <div class="invalid-feedback" id="codMesaError"><?= htmlspecialchars($errores['cod_mesa'] ?? 'El código de la mesa es obligatorio.') ?></div>
                        <div class="bn-ayuda">Código visible de la mesa, único. Máximo 10 caracteres.</div>
                    </div>

                    <!-- Mesero asignado -->
                    <div class="col-12 col-md-6">
                        <label for="mesero" class="bn-label">Mesero asignado</label>
                        <select id="mesero" name="fk_usuario_encargado"
                                class="bn-select form-select <?= isset($errores['fk_usuario_encargado']) ? 'is-invalid' : '' ?>">
                            <option value="">Sin asignar</option>
                            <?php foreach ($meseros as $pk => $nombre): ?>
                                <option value="<?= (int)$pk ?>" <?= (string)$datos['fk_usuario_encargado'] === (string)$pk ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($nombre) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= htmlspecialchars($errores['fk_usuario_encargado'] ?? '') ?></div>
                        <div class="bn-ayuda">Solo aparecen usuarios con rol mesero.</div>
                    </div>

                    <!-- Estatus -->
                    <div class="col-12">
                        <span class="bn-label">Estatus de la mesa</span>
                        <div class="bn-estatus-box d-flex align-items-center justify-content-between gap-3">
                            <div class="form-check form-switch bn-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="estatus" name="estatus" value="1"
                                       <?= $datos['estatus'] === '1' ? 'checked' : '' ?>>
                                <label class="form-check-label bn-switch-texto <?= $datos['estatus'] === '1' ? 'ok' : 'off' ?>" for="estatus" id="estatusTexto">
                                    <?= $datos['estatus'] === '1' ? 'Disponible' : 'Fuera de servicio' ?>
                                </label>
                            </div>
                            <?php if ($modoEdicion): ?>
                                <span class="d-inline-flex align-items-center gap-2 small" style="color: var(--texto-suave);">
                                    Estado actual:
                                    <span class="bn-estado bn-estado-mesa-<?= $estadosMesa[$estadoActual][1] ?>">
                                        <?= htmlspecialchars($estadosMesa[$estadoActual][0]) ?>
                                    </span>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Notas informativas -->
                    <div class="col-12">
                        <div class="bn-nota d-none" id="notaFuera">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            Una mesa fuera de servicio no se puede ocupar ni recibir pedidos.
                            <?php if ($modoEdicion && $estadoActual !== 'libre'): ?>
                                Esta mesa está <strong><?= htmlspecialchars(strtolower($estadosMesa[$estadoActual][0])) ?></strong> ahora mismo: verifica que ya no tenga clientes antes de sacarla de servicio.
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Pie del formulario -->
            <div class="bn-form-pie d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 p-3 p-md-4">
                <a href="mesas.php" class="bn-btn bn-btn-suave justify-content-center text-decoration-none">Cancelar</a>
                <button type="submit" class="bn-btn bn-btn-verde justify-content-center">
                    <i class="bi bi-check-lg"></i> <?= $modoEdicion ? 'Guardar cambios' : 'Registrar mesa' ?>
                </button>
            </div>
        </form>

    </main>
</div>

<!-- Avisos -->
<div class="bn-toasts" id="toasts" aria-live="polite"></div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const formEl   = document.getElementById('formMesa');
    const codEl    = document.getElementById('codMesa');
    const codErr   = document.getElementById('codMesaError');
    const switchEl = document.getElementById('estatus');
    const textoEl  = document.getElementById('estatusTexto');
    const notaEl   = document.getElementById('notaFuera');

    const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    /* ---------- Código: siempre en mayúsculas y sin espacios ---------- */
    codEl.addEventListener('input', () => {
        codEl.value = codEl.value.toUpperCase().replace(/\s+/g, '');
        codEl.classList.remove('is-invalid');
    });

    /* ---------- Interruptor de estatus ---------- */
    function actualizarEstatus() {
        const activo = switchEl.checked;
        textoEl.textContent = activo ? 'Disponible' : 'Fuera de servicio';
        textoEl.classList.toggle('ok', activo);
        textoEl.classList.toggle('off', !activo);
        notaEl.classList.toggle('d-none', activo);
    }
    switchEl.addEventListener('change', actualizarEstatus);
    actualizarEstatus();

    /* ---------- Validación en el navegador (el servidor vuelve a validar) ---------- */
    formEl.addEventListener('submit', e => {
        const cod = codEl.value.trim();
        let error = '';
        if (cod === '')          error = 'El código de la mesa es obligatorio.';
        else if (cod.length > 10) error = 'El código no puede pasar de 10 caracteres.';

        codEl.classList.toggle('is-invalid', !!error);
        if (error) {
            e.preventDefault();
            codErr.textContent = error;
            codEl.focus();
        }
    });

    /* ---------- Aviso de éxito ---------- */
    function avisar(texto) {
        const t = document.createElement('div');
        t.className = 'bn-toast';
        t.innerHTML = `<i class="bi bi-check-circle-fill"></i><span>${esc(texto)}</span>`;
        document.getElementById('toasts').appendChild(t);
        setTimeout(() => {
            t.classList.add('saliendo');
            setTimeout(() => t.remove(), 300);
        }, 3500);
    }
    <?php if ($exito): ?>
    avisar(<?= json_encode($exito, JSON_UNESCAPED_UNICODE) ?>);
    <?php endif; ?>
</script>
</body>
</html>
