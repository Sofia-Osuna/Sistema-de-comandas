<?php
/**
 * Beanora - Configuración
 * Ubicación: public/paginas/configuracion.php
 *
 * Sección actual: Métodos de pago (listado + formulario nuevo/editar en modal).
 * Más adelante se pueden agregar otras secciones debajo con el mismo patrón
 * (<section class="bn-seccion"> ... </section>).
 *
 * Campos según el diccionario de datos (tabla `metodos_pago`):
 *   pk_metodo_pago  INT AUTO_INCREMENT (PK)
 *   metodo          VARCHAR(30)  NOT NULL, UNIQUE   (efectivo, tarjeta... se pueden agregar más)
 *   estatus         CHAR(1)      NOT NULL, default '1'   ('1' = activo, '0' = inactivo)
 *
 * No hay opción de eliminar: ventas.fk_metodo_pago usa RESTRICT, así que los
 * métodos con historial se DESACTIVAN (estatus = '0'), no se borran.
 *
 * Mientras no exista el modelo, los datos de ejemplo viven en $_SESSION para que
 * puedas probar agregar / editar / activar / desactivar. Los puntos a reemplazar
 * por consultas a la BD están marcados con "TODO BD".
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$paginaActual = 'configuracion'; // por si el sidebar marca el ítem activo con esta variable

/* ---------- Datos de ejemplo (TODO BD: SELECT pk_metodo_pago, metodo, estatus FROM metodos_pago) ---------- */
if (!isset($_SESSION['metodos_pago'])) {
    $_SESSION['metodos_pago'] = [
        1 => ['metodo' => 'efectivo',      'estatus' => '1'],
        2 => ['metodo' => 'tarjeta',       'estatus' => '1'],
        3 => ['metodo' => 'transferencia', 'estatus' => '0'],
    ];
}
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$metodos = &$_SESSION['metodos_pago'];

/* ---------- Mensaje de una operación anterior (se muestra una sola vez) ---------- */
$exito = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

/* ---------- Procesar formularios (el servidor es la autoridad) ---------- */
$errores    = [];
$formDatos  = ['pk' => 0, 'metodo' => '', 'estatus' => '1'];
$abrirModal = false;
$errorGlobal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errorGlobal = 'La sesión del formulario expiró. Intenta de nuevo.';
    } else {
        $accion = $_POST['accion'] ?? '';
        $pk     = (int)($_POST['pk'] ?? 0);

        /* --- Guardar (nuevo / editar) --- */
        if ($accion === 'guardar') {
            $metodo  = mb_strtolower(trim($_POST['metodo'] ?? ''));
            $estatus = isset($_POST['estatus']) ? '1' : '0';

            if ($pk > 0 && !isset($metodos[$pk])) {
                $errorGlobal = 'El método de pago que intentas editar no existe.';
            } else {
                if ($metodo === '') {
                    $errores['metodo'] = 'El nombre del método de pago es obligatorio.';
                } elseif (mb_strlen($metodo) > 30) {
                    $errores['metodo'] = 'El nombre no puede pasar de 30 caracteres.';
                } else {
                    foreach ($metodos as $id => $m) {   // TODO BD: SELECT 1 FROM metodos_pago WHERE metodo = ? AND pk_metodo_pago <> ?
                        if ($id !== $pk && $m['metodo'] === $metodo) {
                            $errores['metodo'] = 'Ya existe un método de pago con ese nombre.';
                            break;
                        }
                    }
                }

                if (!$errores) {
                    if ($pk > 0) {
                        // TODO BD: UPDATE metodos_pago SET metodo = ?, estatus = ? WHERE pk_metodo_pago = ?
                        $metodos[$pk] = ['metodo' => $metodo, 'estatus' => $estatus];
                        $_SESSION['flash'] = 'Método de pago actualizado correctamente.';
                    } else {
                        // TODO BD: INSERT INTO metodos_pago (metodo, estatus) VALUES (?, ?)
                        $metodos[] = ['metodo' => $metodo, 'estatus' => $estatus];
                        $_SESSION['flash'] = 'Método de pago registrado correctamente.';
                    }
                    header('Location: configuracion.php');
                    exit;
                }

                // Hubo errores: se vuelve a abrir el modal con lo que escribió la persona
                $formDatos  = ['pk' => $pk, 'metodo' => $metodo, 'estatus' => $estatus];
                $abrirModal = true;
            }
        }

        /* --- Activar / desactivar (baja lógica) --- */
        if ($accion === 'estatus') {
            if (isset($metodos[$pk])) {
                // TODO BD: UPDATE metodos_pago SET estatus = ? WHERE pk_metodo_pago = ?
                $metodos[$pk]['estatus'] = $metodos[$pk]['estatus'] === '1' ? '0' : '1';
                $_SESSION['flash'] = $metodos[$pk]['estatus'] === '1'
                    ? 'Método de pago activado.'
                    : 'Método de pago desactivado. Ya no se podrá usar al cobrar.';
            }
            header('Location: configuracion.php');
            exit;
        }
    }
}

/* ---------- Conteos ---------- */
$total     = count($metodos);
$activos   = count(array_filter($metodos, fn($m) => $m['estatus'] === '1'));
$inactivos = $total - $activos;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beanora - Configuración</title>

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

        <!-- ================= Encabezado de la página ================= -->
        <div class="mb-4">
            <h1 class="bn-titulo">Configuración</h1>
            <p class="bn-subtitulo">Ajustes generales del sistema del restaurante.</p>
        </div>

        <?php if ($errorGlobal): ?>
            <div class="bn-nota mb-4"><i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($errorGlobal) ?></div>
        <?php endif; ?>

        <!-- ================= Sección: Métodos de pago ================= -->
        <section class="bn-seccion" id="metodos-pago" aria-labelledby="tituloMetodos">

            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bn-seccion-icono"><i class="bi bi-credit-card"></i></div>
                    <div>
                        <h2 class="bn-card-titulo" id="tituloMetodos">Métodos de pago</h2>
                        <p class="bn-subtitulo mb-0">Formas de pago disponibles al cobrar una cuenta.</p>
                    </div>
                </div>
                <button type="button" class="bn-btn bn-btn-verde align-self-start" id="btnNuevoMetodo">
                    <i class="bi bi-plus-lg"></i> Agregar método
                </button>
            </div>

            <!-- Filtros -->
            <div class="bn-card bn-filtros mb-3">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div class="d-flex flex-wrap align-items-center gap-2" id="filtrosMetodos">
                        <button type="button" class="bn-chip activo" data-filtro="todos">Todos <span class="n"><?= $total ?></span></button>
                        <button type="button" class="bn-chip" data-filtro="1">Activos <span class="n"><?= $activos ?></span></button>
                        <button type="button" class="bn-chip" data-filtro="0">Inactivos <span class="n"><?= $inactivos ?></span></button>
                    </div>
                    <span class="bn-contador align-self-start">
                        <strong><?= $activos ?></strong> métodos activos
                    </span>
                </div>
            </div>

            <!-- Listado -->
            <div class="bn-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table bn-tabla align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 90px;">#</th>
                                <th>Método de pago</th>
                                <th>Estatus</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoMetodos">
                            <?php foreach ($metodos as $pk => $m): $activo = $m['estatus'] === '1'; ?>
                                <tr data-estatus="<?= $m['estatus'] ?>" class="<?= $activo ? '' : 'bn-fila-inactiva' ?>">
                                    <td><span class="bn-num-pedido"><?= (int)$pk ?></span></td>
                                    <td><span class="bn-metodo-nombre"><?= htmlspecialchars($m['metodo']) ?></span></td>
                                    <td>
                                        <span class="bn-estado <?= $activo ? 'bn-estado-activo' : 'bn-estado-inactivo' ?>">
                                            <?= $activo ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="bn-accion btn-editar-metodo"
                                                    data-pk="<?= (int)$pk ?>"
                                                    data-metodo="<?= htmlspecialchars($m['metodo']) ?>"
                                                    data-estatus="<?= $m['estatus'] ?>">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                                <input type="hidden" name="accion" value="estatus">
                                                <input type="hidden" name="pk" value="<?= (int)$pk ?>">
                                                <?php if ($activo): ?>
                                                    <button type="submit" class="bn-accion peligro"><i class="bi bi-slash-circle"></i> Desactivar</button>
                                                <?php else: ?>
                                                    <button type="submit" class="bn-accion"><i class="bi bi-check-circle"></i> Activar</button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Estado vacío -->
                <div id="metodosVacio" class="bn-vacio <?= $total ? 'd-none' : '' ?>">
                    <i class="bi bi-credit-card"></i>
                    <h4>No hay métodos de pago en este estado</h4>
                    <p class="mb-0">Prueba con otro filtro o agrega un método nuevo.</p>
                </div>
            </div>

            <p class="bn-ayuda mt-3">
                <i class="bi bi-info-circle"></i>
                Los métodos de pago no se eliminan para conservar el historial de ventas; se desactivan y dejan de aparecer al cobrar.
            </p>
        </section>

    </main>
</div>

<!-- ================= Modal: Método de pago (nuevo / editar) ================= -->
<div class="modal fade bn-modal" id="modalMetodo" tabindex="-1" aria-labelledby="modalMetodoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalMetodoLabel">Agregar método de pago</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formMetodo" method="post" novalidate>
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="pk" id="metodoPk" value="0">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="metodoNombre" class="bn-label">Método de pago</label>
                        <input type="text" id="metodoNombre" name="metodo" class="bn-input w-100"
                               maxlength="30" required placeholder="Ej. efectivo, tarjeta, transferencia" autocomplete="off">
                        <div class="invalid-feedback" id="metodoError">El nombre del método de pago es obligatorio.</div>
                        <div class="bn-ayuda">Nombre único. Máximo 30 caracteres.</div>
                    </div>

                    <div>
                        <span class="bn-label">Estatus</span>
                        <div class="bn-estatus-box">
                            <div class="form-check form-switch bn-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="metodoEstatus" name="estatus" value="1" checked>
                                <label class="form-check-label bn-switch-texto ok" for="metodoEstatus" id="metodoEstatusTexto">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="bn-btn bn-btn-suave" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="bn-btn bn-btn-verde" id="btnGuardarMetodo">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Avisos -->
<div class="bn-toasts" id="toasts" aria-live="polite"></div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalEl  = document.getElementById('modalMetodo');
    const modal    = new bootstrap.Modal(modalEl);
    const formEl   = document.getElementById('formMetodo');
    const pkEl     = document.getElementById('metodoPk');
    const nombreEl = document.getElementById('metodoNombre');
    const errorEl  = document.getElementById('metodoError');
    const switchEl = document.getElementById('metodoEstatus');
    const textoEl  = document.getElementById('metodoEstatusTexto');

    const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    /* ---------- Abrir el modal (nuevo / editar) ---------- */
    function abrirFormulario({ pk = 0, metodo = '', estatus = '1', error = '' } = {}) {
        pkEl.value = pk;
        nombreEl.value = metodo;
        switchEl.checked = estatus === '1';
        actualizarEstatus();
        document.getElementById('modalMetodoLabel').textContent = pk > 0 ? 'Editar método de pago' : 'Agregar método de pago';
        document.getElementById('btnGuardarMetodo').textContent = pk > 0 ? 'Guardar cambios' : 'Agregar';
        nombreEl.classList.toggle('is-invalid', !!error);
        if (error) errorEl.textContent = error;
        modal.show();
    }

    document.getElementById('btnNuevoMetodo').addEventListener('click', () => abrirFormulario());

    document.getElementById('cuerpoMetodos').addEventListener('click', e => {
        const btn = e.target.closest('.btn-editar-metodo');
        if (!btn) return;
        abrirFormulario({ pk: Number(btn.dataset.pk), metodo: btn.dataset.metodo, estatus: btn.dataset.estatus });
    });

    modalEl.addEventListener('shown.bs.modal', () => nombreEl.focus());

    /* ---------- Interruptor de estatus ---------- */
    function actualizarEstatus() {
        textoEl.textContent = switchEl.checked ? 'Activo' : 'Inactivo';
        textoEl.classList.toggle('ok', switchEl.checked);
        textoEl.classList.toggle('off', !switchEl.checked);
    }
    switchEl.addEventListener('change', actualizarEstatus);

    /* ---------- Validación en el navegador (el servidor vuelve a validar) ---------- */
    nombreEl.addEventListener('input', () => nombreEl.classList.remove('is-invalid'));

    formEl.addEventListener('submit', e => {
        const valor = nombreEl.value.trim();
        let error = '';
        if (valor === '')          error = 'El nombre del método de pago es obligatorio.';
        else if (valor.length > 30) error = 'El nombre no puede pasar de 30 caracteres.';

        nombreEl.classList.toggle('is-invalid', !!error);
        if (error) {
            e.preventDefault();
            errorEl.textContent = error;
            nombreEl.focus();
        }
    });

    /* ---------- Filtros por estatus ---------- */
    document.getElementById('filtrosMetodos').addEventListener('click', e => {
        const chip = e.target.closest('[data-filtro]');
        if (!chip) return;
        document.querySelectorAll('#filtrosMetodos .bn-chip').forEach(c => c.classList.toggle('activo', c === chip));

        let visibles = 0;
        document.querySelectorAll('#cuerpoMetodos tr').forEach(tr => {
            const ver = chip.dataset.filtro === 'todos' || tr.dataset.estatus === chip.dataset.filtro;
            tr.classList.toggle('d-none', !ver);
            if (ver) visibles++;
        });
        document.getElementById('metodosVacio').classList.toggle('d-none', visibles > 0);
    });

    /* ---------- Avisos ---------- */
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

    <?php if ($abrirModal): ?>
    abrirFormulario(<?= json_encode([
        'pk'      => $formDatos['pk'],
        'metodo'  => $formDatos['metodo'],
        'estatus' => $formDatos['estatus'],
        'error'   => $errores['metodo'] ?? '',
    ], JSON_UNESCAPED_UNICODE) ?>);
    <?php endif; ?>
</script>
</body>
</html>
