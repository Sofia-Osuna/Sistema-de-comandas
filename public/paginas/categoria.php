<?php
// Cuando exista auth.php, descomentar:
// require_once __DIR__ . '/../../includes/auth.php';
// requerirRol(['admin']);

// Datos de ejemplo (después vendrán de modelos/Categoria.php con PDO).
// 'productos' = COUNT de productos con esa fk_categoria.
$categorias = [
    ['id' => 1, 'categoria' => 'Entradas',          'productos' => 6],
    ['id' => 2, 'categoria' => 'Bebidas calientes', 'productos' => 12],
    ['id' => 3, 'categoria' => 'Bebidas frías',     'productos' => 9],
    ['id' => 4, 'categoria' => 'Postres',           'productos' => 7],
    ['id' => 5, 'categoria' => 'Panadería',         'productos' => 0],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categorías | Beanora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/categorias.css">
</head>
<body>
<div class="d-flex flex-column flex-lg-row">

    <?php include '../../includes/sidebar.php'; ?>

    <main class="contenido-panel flex-grow-1 p-4 p-lg-5">

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <h1 class="titulo-pagina mb-1">Categorías</h1>
                <p class="fs-5 text-secondary mb-0">Agrupa los productos del menú por tipo.</p>
            </div>
            <a href="formulario-categoria.php" class="btn btn-principal btn-lg rounded-3 d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-plus-lg"></i> Nueva Categoría
            </a>
        </div>

        <?php if (empty($categorias)): ?>

            <!-- Estado vacío -->
            <div class="bg-white rounded-4 shadow-sm text-center p-5 text-secondary">
                <i class="bi bi-tags fs-1 d-block mb-2"></i>
                <h2 class="h5 mb-1">Aún no hay categorías</h2>
                <p class="mb-0">Crea la primera para empezar a organizar el menú.</p>
            </div>

        <?php else: ?>

        <!-- ================= BUSCADOR ================= -->
        <div class="barra-busqueda bg-white rounded-4 shadow-sm p-3 p-md-4 mb-4">
            <div class="buscador">
                <i class="bi bi-search"></i>
                <input type="search" id="buscador" class="form-control buscador-input"
                       placeholder="Buscar categoría por nombre..." autocomplete="off"
                       maxlength="30" aria-label="Buscar categoría">
            </div>
            <small class="text-secondary d-block mt-3" id="resumen"></small>
        </div>

        <!-- Sin resultados -->
        <div id="sinResultados" class="bg-white rounded-4 shadow-sm text-center p-5 text-secondary d-none">
            <i class="bi bi-search fs-1 d-block mb-2"></i>
            <h2 class="h5 mb-1" id="sinTitulo">Sin resultados</h2>
            <p class="mb-0" id="sinTexto">Prueba con otro nombre.</p>
        </div>

        <!-- ================= TARJETAS (celular) ================= -->
        <div class="d-md-none" id="listaTarjetas">
            <?php foreach ($categorias as $c): ?>
                <div class="card border-0 shadow-sm rounded-4 mb-3 item-categoria"
                     data-id="<?= (int) $c['id'] ?>"
                     data-nombre="<?= htmlspecialchars($c['categoria'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="icono-categoria"><i class="bi bi-tag-fill"></i></span>
                            <div class="overflow-hidden">
                                <div class="fw-semibold fs-5 text-truncate"><?= htmlspecialchars($c['categoria']) ?></div>
                                <div class="text-secondary small">
                                    <?= (int) $c['productos'] ?> <?= $c['productos'] === 1 ? 'producto' : 'productos' ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 border-top pt-3">
                            <a href="categoria-form.php?id=<?= (int) $c['id'] ?>" class="btn btn-light flex-fill">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <button type="button" class="btn btn-light flex-fill btn-eliminar"
                                    data-id="<?= (int) $c['id'] ?>"
                                    data-nombre="<?= htmlspecialchars($c['categoria'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?= $c['productos'] > 0 ? 'disabled title="Tiene productos asignados"' : '' ?>>
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ================= TABLA (tableta y escritorio) ================= -->
        <div class="d-none d-md-block bg-white rounded-4 shadow-sm overflow-hidden" id="contenedorTabla">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tabla-categorias">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th>Categoría</th>
                            <th>Productos</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categorias as $c): ?>
                        <tr class="item-categoria"
                            data-id="<?= (int) $c['id'] ?>"
                            data-productos="<?= (int) $c['productos'] ?>"
                            data-nombre="<?= htmlspecialchars($c['categoria'], ENT_QUOTES, 'UTF-8') ?>">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="icono-categoria"><i class="bi bi-tag-fill"></i></span>
                                    <span class="fw-semibold"><?= htmlspecialchars($c['categoria']) ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if ($c['productos'] > 0): ?>
                                    <span class="badge productos-con"><?= (int) $c['productos'] ?></span>
                                <?php else: ?>
                                    <span class="badge productos-sin">Sin productos</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="categoria-form.php?id=<?= (int) $c['id'] ?>" class="btn btn-light" title="Editar categoría">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-light btn-eliminar"
                                            data-id="<?= (int) $c['id'] ?>"
                                            data-nombre="<?= htmlspecialchars($c['categoria'], ENT_QUOTES, 'UTF-8') ?>"
                                            title="<?= $c['productos'] > 0 ? 'Tiene productos asignados' : 'Eliminar categoría' ?>"
                                            <?= $c['productos'] > 0 ? 'disabled' : '' ?>>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php endif; ?>

    </main>
</div>

<!-- ================= MODAL: CONFIRMAR ELIMINACIÓN ================= -->
<div class="modal fade cat-modal" id="modalEliminar" tabindex="-1" aria-labelledby="eliminarTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-trash3-fill cat-modal-icono"></i>
                <h5 class="cat-modal-titulo mt-2 mb-2" id="eliminarTitulo">¿Eliminar categoría?</h5>
                <p class="text-secondary mb-4">
                    Se quitará <strong id="eliminarNombre"></strong> del menú. Esta acción no se puede deshacer.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light cat-btn" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-peligro cat-btn" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const buscador   = document.getElementById('buscador');
        const resumen    = document.getElementById('resumen');
        const sinRes     = document.getElementById('sinResultados');
        const sinTitulo  = document.getElementById('sinTitulo');
        const sinTexto   = document.getElementById('sinTexto');
        const modalEl    = document.getElementById('modalEliminar');
        const nombreEl   = document.getElementById('eliminarNombre');
        const btnConfirm = document.getElementById('btnConfirmarEliminar');

        if (!buscador) return; // no hay categorías: no hay nada que filtrar

        let idPorEliminar = null;

        // Sin acentos ni mayúsculas: "cafe" encuentra "Café"
        const normalizar = s => s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();

        /* ---------- Buscador + resumen ---------- */
        function actualizar() {
            const q = normalizar(buscador.value);
            const items = document.querySelectorAll('.item-categoria');

            items.forEach(function (el) {
                el.classList.toggle('d-none', !normalizar(el.dataset.nombre).includes(q));
            });

            // El conteo se hace solo con las filas de la tabla (las tarjetas son las mismas categorías)
            const filas     = document.querySelectorAll('tr.item-categoria');
            const total     = filas.length;
            const visibles  = Array.from(filas).filter(f => !f.classList.contains('d-none')).length;
            const conProd   = Array.from(filas).filter(f => Number(f.dataset.productos) > 0).length;

            if (total === 0) {
                resumen.textContent = 'No hay categorías.';
            } else if (q) {
                resumen.textContent = visibles + ' de ' + total + (total === 1 ? ' categoría' : ' categorías');
            } else {
                resumen.textContent = total + (total === 1 ? ' categoría' : ' categorías') +
                                      ' · ' + conProd + ' con productos · ' + (total - conProd) + ' sin productos';
            }

            // Mensaje cuando no hay nada que mostrar
            const vacio = visibles === 0;
            sinRes.classList.toggle('d-none', !vacio);
            document.getElementById('listaTarjetas').classList.toggle('d-none', vacio);
            document.getElementById('listaTarjetas').classList.toggle('d-md-none', !vacio);
            document.getElementById('contenedorTabla').classList.toggle('d-none', vacio);
            document.getElementById('contenedorTabla').classList.toggle('d-md-block', !vacio);

            if (total === 0) {
                sinTitulo.textContent = 'Ya no hay categorías';
                sinTexto.textContent  = 'Crea una nueva para organizar el menú.';
            } else {
                sinTitulo.textContent = 'Sin resultados';
                sinTexto.textContent  = 'No hay categorías que coincidan con "' + buscador.value.trim() + '".';
            }
        }

        buscador.addEventListener('input', actualizar);

        /* ---------- Abrir modal de eliminación ---------- */
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-eliminar');
            if (!btn || btn.disabled) return;

            idPorEliminar = btn.dataset.id;
            nombreEl.textContent = btn.dataset.nombre;
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

        /* ---------- Confirmar (solo diseño: quita la fila de la pantalla) ----------
           Cuando haya backend: aquí va un fetch POST a public/acciones/eliminar-categoria.php
           y solo se quita del DOM si la respuesta es correcta. */
        btnConfirm.addEventListener('click', function () {
            document.querySelectorAll('.item-categoria[data-id="' + idPorEliminar + '"]')
                .forEach(el => el.remove());

            bootstrap.Modal.getInstance(modalEl).hide();
            idPorEliminar = null;
            actualizar();
        });

        actualizar();
    })();
</script>
</body>
</html>