<?php
// Cuando exista auth.php, descomentar:
// require_once __DIR__ . '/../../includes/auth.php';
// requerirRol(['admin']);

$id        = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$esEdicion = $id > 0;

// Categorías (después vendrán de modelos/Categoria.php: SELECT pk_categoria, categoria ...)
$categorias = [
    1 => 'Entradas',
    2 => 'Bebidas calientes',
    3 => 'Bebidas frías',
    4 => 'Postres',
    5 => 'Panadería',
];

// Datos de ejemplo (después vendrán de modelos/Producto.php con PDO).
// Los nombres de los campos son los de la tabla `productos` del diccionario de datos.
$ejemplos = [
    1 => ['producto' => 'Latte de Avellana',   'fk_categoria' => 2, 'descripcion' => 'Espresso con leche cremada y jarabe artesanal de avellana.',
          'precio' => 75.00,  'imagen' => '', 'stock' => 24, 'estado' => 'disponible'],
    2 => ['producto' => 'Croissant de Almendras', 'fk_categoria' => 5, 'descripcion' => 'Hojaldre de mantequilla relleno de crema frangipane.',
          'precio' => 85.00,  'imagen' => '', 'stock' => 12, 'estado' => 'disponible'],
    3 => ['producto' => 'Cold Brew Vainilla Coconut', 'fk_categoria' => 3, 'descripcion' => 'Extracción en frío por 16 horas con leche de coco.',
          'precio' => 90.00,  'imagen' => '', 'stock' => 0,  'estado' => 'agotado'],
];

// Valores por defecto para "Nuevo producto"
$producto = [
    'producto' => '', 'fk_categoria' => 0, 'descripcion' => '',
    'precio' => '', 'imagen' => '', 'stock' => 0, 'estado' => 'disponible',
];
if ($esEdicion && isset($ejemplos[$id])) {
    $producto = $ejemplos[$id];
}

// Las fotos se guardan en public/assets/img/platillos/ (campo productos.imagen = solo el nombre del archivo)
$rutaImagenes = '../assets/img/platillos/';
$imagenActual = $producto['imagen'] !== '' ? $rutaImagenes . basename($producto['imagen']) : '';

$titulo = $esEdicion ? 'Editar Producto' : 'Nuevo Producto';
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $titulo ?> | Beanora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/usuario-form.css">
    <link rel="stylesheet" href="../assets/css/producto-perfil.css">
</head>
<body>
<div class="d-flex flex-column flex-lg-row">

    <?php include '../../includes/sidebar.php'; ?>

    <main class="contenido-panel flex-grow-1 p-4 d-flex">

        <!-- Contenedor centrado -->
        <div class="form-centrado">

            <!-- Encabezado -->
            <a href="menu.php" class="volver d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left"></i> Volver al menú
            </a>
            <h1 class="titulo-pagina mb-1"><?= $titulo ?></h1>
            <p class="form-subtitulo mb-4">
                <?= $esEdicion ? 'Actualiza la información, el precio y la disponibilidad de este producto.' : 'Registra un platillo o bebida para el catálogo del menú.' ?>
            </p>

            <!-- Formulario (solo diseño: todavía no envía nada) -->
            <form id="formProducto" class="form-card needs-validation" action="#" method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="quitar_imagen" id="quitarImagen" value="0">

                <p class="form-aviso" id="avisoOk" role="status">
                    <i class="bi bi-check-circle-fill"></i>
                    Formulario válido. (Modo diseño: todavía no se guarda en la base de datos.)
                </p>

                <div class="form-cuerpo">

                    <!-- ===== Columna izquierda ===== -->
                    <div class="form-col">

                        <section class="form-seccion">
                            <h2 class="form-seccion-titulo">Datos del producto</h2>

                            <div class="mb-3">
                                <label for="producto" class="form-etiqueta">Nombre</label>
                                <input type="text" class="form-control" id="producto" name="producto"
                                       value="<?= $e($producto['producto']) ?>"
                                       placeholder="Ej. Cappuccino de vainilla" maxlength="50"
                                       autocomplete="off" required>
                                <div class="invalid-feedback">Escribe el nombre del producto.</div>
                            </div>

                            <div class="mb-3">
                                <label for="fk_categoria" class="form-etiqueta">Categoría</label>
                                <select class="form-select" id="fk_categoria" name="fk_categoria" required>
                                    <option value="">Selecciona una categoría...</option>
                                    <?php foreach ($categorias as $pk => $nombre): ?>
                                        <option value="<?= $pk ?>" <?= (int) $producto['fk_categoria'] === $pk ? 'selected' : '' ?>><?= $e($nombre) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Selecciona una categoría.</div>
                                <div class="form-ayuda">¿No está la que necesitas? <a href="formulario-categoria.php">Crear categoría</a></div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-etiqueta">Descripción <span class="text-lowercase fw-normal text-muted">(opcional)</span></label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                          maxlength="255" placeholder="Describe brevemente el platillo o la bebida..."><?= $e($producto['descripcion']) ?></textarea>
                                <div class="d-flex justify-content-end">
                                    <div class="form-ayuda text-nowrap"><span id="contadorDesc">0</span>/255</div>
                                </div>
                            </div>

                            <div>
                                <label for="precio" class="form-etiqueta">Precio de venta (MXN)</label>
                                <div class="campo-prefijo">
                                    <span>$</span>
                                    <input type="number" class="form-control" id="precio" name="precio"
                                           value="<?= $producto['precio'] === '' ? '' : $e(number_format((float) $producto['precio'], 2, '.', '')) ?>"
                                           min="0.01" max="9999.99" step="0.01" inputmode="decimal"
                                           placeholder="0.00" required>
                                    <div class="invalid-feedback">Ingresa un precio entre $0.01 y $9,999.99.</div>
                                </div>
                            </div>
                        </section>

                    </div>

                    <!-- ===== Columna derecha ===== -->
                    <div class="form-col">

                        <section class="form-seccion">
                            <h2 class="form-seccion-titulo">Imagen</h2>

                            <div class="img-preview<?= $imagenActual ? ' con-imagen' : '' ?>" id="imgPreview">
                                <?php if ($imagenActual): ?>
                                    <img src="<?= $e($imagenActual) ?>" alt="Imagen actual de <?= $e($producto['producto']) ?>">
                                <?php endif; ?>
                                <i class="bi bi-image"></i>
                                <small>Aún no hay imagen</small>
                            </div>

                            <input type="file" id="imagen" name="imagen" class="d-none" accept="image/jpeg,image/png,image/webp">
                            <div class="d-flex flex-wrap gap-2 align-items-center mt-3">
                                <label for="imagen" class="btn btn-suave d-inline-flex align-items-center gap-2 mb-0">
                                    <i class="bi bi-upload"></i> <span id="textoBtnImg"><?= $imagenActual ? 'Cambiar imagen' : 'Elegir imagen' ?></span>
                                </label>
                                <button type="button" class="btn btn-peligro-suave <?= $imagenActual ? '' : 'd-none' ?>" id="btnQuitarImg">
                                    <i class="bi bi-trash3"></i> Quitar
                                </button>
                            </div>
                            <div class="form-ayuda">JPG, PNG o WEBP. Máximo 2 MB.</div>
                            <div class="form-error" id="errorImagen" role="alert"></div>
                        </section>

                        <section class="form-seccion">
                            <h2 class="form-seccion-titulo">Inventario y disponibilidad</h2>

                            <div class="mb-3">
                                <label for="stock" class="form-etiqueta">Stock (existencias)</label>
                                <input type="number" class="form-control" id="stock" name="stock"
                                       value="<?= (int) $producto['stock'] ?>" min="0" max="9999" step="1"
                                       inputmode="numeric" required>
                                <div class="invalid-feedback">Ingresa un número entero de 0 a 9,999.</div>
                                <div class="form-ayuda">Con stock en 0 el producto se marca como agotado.</div>
                            </div>

                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="estado" name="estado"
                                       value="disponible" <?= $producto['estado'] === 'disponible' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="estado">
                                    Estado: <span id="estadoTexto" class="estado-texto"></span>
                                </label>
                            </div>
                        </section>

                    </div>
                </div>

                <!-- Acciones -->
                <div class="form-acciones">
                    <a href="menu.php" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-principal d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-lg"></i> <?= $esEdicion ? 'Guardar cambios' : 'Crear producto' ?>
                    </button>
                </div>
            </form>

        </div>
    </main>
</div>

<script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        'use strict';
        const $ = id => document.getElementById(id);
        const form     = $('formProducto');
        const nombre   = $('producto');
        const desc     = $('descripcion');
        const stock    = $('stock');
        const estado   = $('estado');
        const archivo  = $('imagen');
        const preview  = $('imgPreview');
        const errorImg = $('errorImagen');
        const btnQuitar = $('btnQuitarImg');
        const TIENE_IMG_INICIAL = <?= $imagenActual ? 'true' : 'false' ?>;
        const TIPOS = ['image/jpeg', 'image/png', 'image/webp'];
        const MAX_BYTES = 2 * 1024 * 1024;
        let urlTemporal = null;

        // ----- Contador de la descripción (VARCHAR(255)) -----
        function contarDesc() { $('contadorDesc').textContent = desc.value.length; }
        desc.addEventListener('input', contarDesc);
        contarDesc();

        // ----- Estado: texto + sincronía con el stock -----
        function pintarEstado() {
            const on = estado.checked;
            const t = $('estadoTexto');
            t.textContent = on ? 'Disponible' : 'Agotado';
            t.className = 'estado-texto ' + (on ? 'ok' : 'off');
        }
        estado.addEventListener('change', pintarEstado);
        stock.addEventListener('input', function () {
            if (stock.value === '') return;
            estado.checked = parseInt(stock.value, 10) > 0;
            pintarEstado();
        });
        pintarEstado();

        // ----- Imagen: validar tipo/tamaño y mostrar vista previa -----
        function mostrarError(msg) {
            errorImg.textContent = msg || '';
            errorImg.classList.toggle('visible', !!msg);
        }
        function ponerPreview(src) {
            let img = preview.querySelector('img');
            if (!img) { img = document.createElement('img'); preview.prepend(img); }
            img.src = src;
            img.alt = 'Vista previa de la imagen';
            preview.classList.add('con-imagen');
        }
        function quitarPreview() {
            const img = preview.querySelector('img');
            if (img) img.remove();
            preview.classList.remove('con-imagen');
            if (urlTemporal) { URL.revokeObjectURL(urlTemporal); urlTemporal = null; }
        }

        archivo.addEventListener('change', function () {
            mostrarError('');
            const f = archivo.files[0];
            if (!f) return;
            if (!TIPOS.includes(f.type)) {
                mostrarError('Formato no permitido. Usa JPG, PNG o WEBP.');
                archivo.value = ''; return;
            }
            if (f.size > MAX_BYTES) {
                mostrarError('La imagen pesa más de 2 MB. Elige una más ligera.');
                archivo.value = ''; return;
            }
            if (urlTemporal) URL.revokeObjectURL(urlTemporal);
            urlTemporal = URL.createObjectURL(f);
            ponerPreview(urlTemporal);
            $('quitarImagen').value = '0';
            $('textoBtnImg').textContent = 'Cambiar imagen';
            btnQuitar.classList.remove('d-none');
        });

        btnQuitar.addEventListener('click', function () {
            archivo.value = '';
            quitarPreview();
            mostrarError('');
            // En edición, avisa al servidor que debe borrar la imagen guardada
            $('quitarImagen').value = TIENE_IMG_INICIAL ? '1' : '0';
            $('textoBtnImg').textContent = 'Elegir imagen';
            btnQuitar.classList.add('d-none');
        });

        // ----- Envío (solo diseño) -----
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Solo diseño: no envía nada todavía

            // Evita nombres hechos solo de espacios
            nombre.value = nombre.value.trim();
            nombre.setCustomValidity(nombre.value === '' ? 'Vacío' : '');
            desc.value = desc.value.trim();
            contarDesc();

            form.classList.add('was-validated');
            $('avisoOk').classList.toggle('visible', form.checkValidity() && !errorImg.classList.contains('visible'));
        });
    })();
</script>
</body>
</html>
