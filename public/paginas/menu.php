<?php
// ==========================================
// PANTALLA: Gestión del Menú (Admin)
// ==========================================
// Datos de ejemplo (mismo enfoque que cuentas_ventas.php) mientras no exista la BD.
// Las altas, bajas y cambios de disponibilidad se guardan en el navegador (localStorage).
// 'img' vacío = se muestra un marcador con el ícono de la categoría.

$categorias = ['Café', 'Repostería', 'Desayunos', 'Bebidas Frías'];

$menu_semilla = [
    ['id' => 1,  'nombre' => 'Latte de Avellana',          'categoria' => 'Café',          'precio' => 75.00,  'disponible' => true,
     'descripcion' => 'Espresso con leche cremada y jarabe artesanal de avellana.',
     'ingredientes' => 'espresso, leche, jarabe de avellana',
     'img' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=400&auto=format&fit=crop&q=80'],
    ['id' => 2,  'nombre' => 'Cappuccino',                 'categoria' => 'Café',          'precio' => 65.00,  'disponible' => true,
     'descripcion' => 'Doble shot de espresso con leche vaporizada y una capa generosa de espuma.',
     'ingredientes' => 'espresso, leche vaporizada, espuma', 'img' => ''],
    ['id' => 3,  'nombre' => 'Espresso Doble',             'categoria' => 'Café',          'precio' => 55.00,  'disponible' => true,
     'descripcion' => 'Café de especialidad recién molido, intenso y con cuerpo.',
     'ingredientes' => 'café molido, agua', 'img' => ''],
    ['id' => 4,  'nombre' => 'Flat White',                 'categoria' => 'Café',          'precio' => 70.00,  'disponible' => true,
     'descripcion' => 'Espresso doble con leche de microespuma, suave y aterciopelado.',
     'ingredientes' => 'espresso, leche', 'img' => ''],
    ['id' => 5,  'nombre' => 'Croissant de Almendras',     'categoria' => 'Repostería',    'precio' => 85.00,  'disponible' => true,
     'descripcion' => 'Hojaldre de mantequilla relleno de crema frangipane y almendras tostadas.',
     'ingredientes' => 'hojaldre, mantequilla, crema frangipane, almendras',
     'img' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=400&auto=format&fit=crop&q=80'],
    ['id' => 6,  'nombre' => 'Cheesecake de Frutos Rojos', 'categoria' => 'Repostería',    'precio' => 110.00, 'disponible' => true,
     'descripcion' => 'Cremoso cheesecake horneado con base de galleta y salsa de frutos rojos.',
     'ingredientes' => 'queso crema, galleta, fresa, zarzamora', 'img' => ''],
    ['id' => 7,  'nombre' => 'Panini Pavo & Queso Brie',   'categoria' => 'Desayunos',     'precio' => 190.00, 'disponible' => true,
     'descripcion' => 'Pan ciabatta artesanal con pechuga de pavo, brie y mermelada de higo.',
     'ingredientes' => 'ciabatta, pavo, queso brie, mermelada de higo',
     'img' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=400&auto=format&fit=crop&q=80'],
    ['id' => 8,  'nombre' => 'Omelette de Champiñones',    'categoria' => 'Desayunos',     'precio' => 140.00, 'disponible' => true,
     'descripcion' => 'Omelette esponjoso con champiñones salteados, queso y cebollín.',
     'ingredientes' => 'huevo, champiñones, queso, cebollín', 'img' => ''],
    ['id' => 9,  'nombre' => 'Cold Brew Vainilla Coconut', 'categoria' => 'Bebidas Frías', 'precio' => 90.00,  'disponible' => false,
     'descripcion' => 'Extracción en frío por 16 horas con leche de coco y vainilla en rama.',
     'ingredientes' => 'café cold brew, leche de coco, vainilla',
     'img' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=400&auto=format&fit=crop&q=80'],
    ['id' => 10, 'nombre' => 'Jugo de Naranja',            'categoria' => 'Bebidas Frías', 'precio' => 60.00,  'disponible' => true,
     'descripcion' => 'Jugo de naranja natural exprimido al momento.',
     'ingredientes' => 'naranja', 'img' => ''],
];

// Flags seguros para incrustar JSON dentro de <script>
$json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
?>
<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beanora - Gestión del Menú</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/modulos-admin.css">
</head>
<body class="d-flex flex-column flex-lg-row h-100" style="overflow-x: hidden;">

    <!-- 1. SIDEBAR / NAVBAR GLOBAL (marca "Gestión del Menú" como activo según el archivo actual) -->
    <?php include '../../includes/sidebar.php'; ?>

    <!-- 2. CONTENIDO PRINCIPAL -->
    <main class="w-100 flex-grow-1 p-3 p-lg-4 overflow-y-auto" style="background-color: var(--fondo); min-height: 100vh;">

        <!-- Cabecera -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-4">
            <div>
                <h2 class="bn-titulo">Gestión del Menú</h2>
                <p class="bn-subtitulo">Catálogo de productos disponibles para comensales y meseros</p>
            </div>
            <button type="button" class="bn-btn bn-btn-verde align-self-start align-self-sm-auto" id="btnNuevo">
                <i class="bi bi-plus-lg"></i> Nuevo Platillo
            </button>
        </div>

        <!-- Búsqueda + filtros por categoría -->
        <div class="bn-card bn-filtros mb-4">
            <div class="d-flex flex-column flex-xl-row gap-3 justify-content-between align-items-xl-center">
                <div class="bn-buscador flex-grow-1" style="max-width: 420px;">
                    <i class="bi bi-search"></i>
                    <input type="search" id="buscador" class="bn-input" placeholder="Buscar por nombre o ingrediente..." autocomplete="off" maxlength="60" aria-label="Buscar platillo">
                </div>
                <div class="d-flex flex-wrap gap-2" id="chipsCategoria">
                    <button type="button" class="bn-chip activo" data-cat="todos">Todos<span class="n"></span></button>
                    <?php foreach ($categorias as $c): ?>
                        <button type="button" class="bn-chip" data-cat="<?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?><span class="n"></span></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <small class="text-muted d-block mt-3" id="resumenMenu"></small>
        </div>

        <!-- Cuadrícula de productos -->
        <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-xl-3 row-cols-xxl-4 mb-5" id="gridMenu"></div>

        <div id="vacioMenu" class="bn-card bn-vacio d-none mb-5">
            <i class="bi bi-cup-hot"></i>
            <h4>No encontramos platillos</h4>
            <p class="mb-0">Prueba con otra búsqueda o cambia la categoría.</p>
        </div>
    </main>

    <!-- MODAL: NUEVO PLATILLO -->
    <div class="modal fade bn-modal" id="modalPlatillo" tabindex="-1" aria-labelledby="platilloTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" id="formPlatillo" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="platilloTitulo">Nuevo platillo</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fNombre" class="bn-label">Nombre del platillo</label>
                        <input type="text" id="fNombre" class="form-control bn-input" maxlength="60" required placeholder="Ej. Cappuccino Vainilla">
                        <div class="invalid-feedback" id="errNombre">Escribe el nombre del platillo.</div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-7">
                            <label for="fCategoria" class="bn-label">Categoría</label>
                            <select id="fCategoria" class="form-select bn-select" required>
                                <?php foreach ($categorias as $c): ?>
                                    <option><?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="fPrecio" class="bn-label">Precio (MXN)</label>
                            <input type="number" id="fPrecio" class="form-control bn-input" min="1" max="9999" step="0.01" required placeholder="0.00">
                            <div class="invalid-feedback">Precio de 1 a 9,999.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fDescripcion" class="bn-label">Descripción</label>
                        <textarea id="fDescripcion" class="form-control bn-input" rows="3" maxlength="160" required placeholder="Describe brevemente el platillo..."></textarea>
                        <div class="invalid-feedback">Agrega una breve descripción.</div>
                    </div>
                    <div class="mb-3">
                        <label for="fIngredientes" class="bn-label">Ingredientes <span class="text-muted text-lowercase fw-normal">(opcional, separados por coma)</span></label>
                        <input type="text" id="fIngredientes" class="form-control bn-input" maxlength="120" placeholder="Ej. espresso, leche, vainilla">
                    </div>
                    <div>
                        <label class="bn-label">Imagen <span class="text-muted text-lowercase fw-normal">(opcional · JPG, PNG o WEBP)</span></label>
                        <div class="bn-dropzone">
                            <div class="bn-preview" id="fPreview"><i class="bi bi-image"></i></div>
                            <div class="flex-grow-1 min-w-0">
                                <input type="file" id="fImagen" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
                                <div class="small text-danger mt-1 d-none" id="errImagen"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bn-btn bn-btn-suave" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="bn-btn bn-btn-verde"><i class="bi bi-check-lg"></i> Guardar platillo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: CONFIRMAR ELIMINACIÓN -->
    <div class="modal fade bn-modal" id="modalEliminar" tabindex="-1" aria-labelledby="eliminarTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <i class="bi bi-trash3-fill" style="font-size: 2.2rem; color: #c0392b;"></i>
                    <h5 class="modal-title mt-2 mb-2" id="eliminarTitulo">¿Eliminar platillo?</h5>
                    <p class="text-muted mb-4">Se quitará <strong id="eliminarNombre"></strong> del menú. Esta acción no se puede deshacer.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="bn-btn bn-btn-suave" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="bn-btn bn-btn-peligro" id="btnConfirmarEliminar">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bn-toasts" id="avisos" aria-live="polite"></div>

    <script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        'use strict';

        // ---------- Datos ----------
        // El menú se guarda en el navegador (localStorage) para que los cambios
        // sobrevivan a recargar. Cuando exista la BD, basta con reemplazar
        // cargar()/guardar() por llamadas a /acciones/*.php.
        const SEMILLA = <?= json_encode($menu_semilla, $json_flags); ?>;
        const CATEGORIAS = <?= json_encode($categorias, $json_flags); ?>;
        const CLAVE = 'beanora_menu_v1';
        const ICONOS = { 'Café': 'bi-cup-hot', 'Repostería': 'bi-cake2', 'Desayunos': 'bi-egg-fried', 'Bebidas Frías': 'bi-cup-straw' };
        const MAX_ARCHIVO = 5 * 1024 * 1024; // 5 MB antes de redimensionar

        let menu = cargar();
        let categoriaActiva = 'todos';
        let idParaEliminar = null;
        let imagenNueva = '';

        // Solo se aceptan imágenes https o data:image (evita esquemas peligrosos como javascript:)
        const imgSegura = u => typeof u === 'string' && (/^https:\/\//i.test(u) || /^data:image\/(jpeg|png|webp);base64,/i.test(u));
        const valido = p => p && Number.isFinite(p.id) && typeof p.nombre === 'string' && CATEGORIAS.includes(p.categoria) &&
                            Number.isFinite(p.precio) && typeof p.disponible === 'boolean';

        function cargar() {
            try {
                const crudo = localStorage.getItem(CLAVE);
                if (crudo) {
                    const datos = JSON.parse(crudo);
                    if (Array.isArray(datos)) return datos.filter(valido).map(p => Object.assign({}, p, { img: imgSegura(p.img) ? p.img : '' }));
                }
            } catch (e) { /* se usa la semilla */ }
            const inicial = SEMILLA.map(p => Object.assign({}, p));
            guardar(inicial);
            return inicial;
        }
        function guardar(lista) {
            try { localStorage.setItem(CLAVE, JSON.stringify(lista || menu)); return true; }
            catch (e) { aviso('No se pudo guardar en el navegador (almacenamiento lleno).', 'aviso'); return false; }
        }

        // ---------- Utilidades ----------
        const $ = id => document.getElementById(id);
        const MAPA_HTML = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
        const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => MAPA_HTML[c]);
        const dinero = n => n.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
        const normaliza = s => String(s || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();

        function aviso(mensaje, tipo) {
            const t = document.createElement('div');
            t.className = 'bn-toast' + (tipo === 'aviso' ? ' aviso' : '');
            const i = document.createElement('i');
            i.className = 'bi ' + (tipo === 'aviso' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill');
            const s = document.createElement('span');
            s.textContent = mensaje;
            t.append(i, s);
            $('avisos').appendChild(t);
            setTimeout(() => { t.classList.add('saliendo'); setTimeout(() => t.remove(), 300); }, 3200);
        }

        function marcador(cat) {
            return '<div class="bn-img-vacia"><i class="bi ' + (ICONOS[cat] || 'bi-cup-hot') + '"></i></div>';
        }

        // ---------- Render ----------
        function render() {
            const q = normaliza($('buscador').value);

            // Contadores de los chips (por categoría, sin considerar la búsqueda)
            document.querySelectorAll('#chipsCategoria .bn-chip').forEach(ch => {
                const cat = ch.dataset.cat;
                const n = cat === 'todos' ? menu.length : menu.filter(p => p.categoria === cat).length;
                ch.querySelector('.n').textContent = n;
                ch.classList.toggle('activo', cat === categoriaActiva);
            });

            const lista = menu.filter(p => {
                const okCat = categoriaActiva === 'todos' || p.categoria === categoriaActiva;
                const okBusqueda = !q || normaliza(p.nombre).includes(q) || normaliza(p.ingredientes).includes(q);
                return okCat && okBusqueda;
            });

            const disp = menu.filter(p => p.disponible).length;
            $('resumenMenu').textContent = menu.length + ' platillos en el menú · ' + disp + ' disponibles · ' + (menu.length - disp) + ' agotados';

            $('vacioMenu').classList.toggle('d-none', lista.length > 0);
            $('gridMenu').innerHTML = lista.map(p => {
                const agotado = !p.disponible;
                return '<div class="col"><article class="bn-card bn-producto' + (agotado ? ' is-agotado' : '') + '">' +
                    '<div class="bn-producto-img">' +
                        (p.img ? '<img src="' + esc(p.img) + '" alt="' + esc(p.nombre) + '" loading="lazy" data-cat="' + esc(p.categoria) + '">' : marcador(p.categoria)) +
                        '<span class="bn-cat-pill">' + esc(p.categoria) + '</span>' +
                        (agotado ? '<span class="bn-agotado-tag">Agotado</span>' : '') +
                    '</div>' +
                    '<div class="p-3 d-flex flex-column gap-2">' +
                        '<div class="d-flex justify-content-between align-items-start gap-2">' +
                            '<h3 class="bn-producto-nombre">' + esc(p.nombre) + '</h3>' +
                            '<span class="bn-precio">' + dinero(p.precio) + '</span>' +
                        '</div>' +
                        '<p class="bn-producto-desc">' + esc(p.descripcion) + '</p>' +
                    '</div>' +
                    '<div class="bn-producto-pie">' +
                        '<label class="form-check form-switch bn-switch">' +
                            '<input class="form-check-input" type="checkbox" role="switch" data-id="' + esc(p.id) + '"' + (p.disponible ? ' checked' : '') + ' aria-label="Disponibilidad de ' + esc(p.nombre) + '">' +
                            '<span class="bn-switch-texto ' + (p.disponible ? 'ok' : 'off') + '">' + (p.disponible ? 'Disponible' : 'Agotado') + '</span>' +
                        '</label>' +
                        '<button type="button" class="bn-btn-icono" data-eliminar="' + esc(p.id) + '" title="Eliminar platillo" aria-label="Eliminar ' + esc(p.nombre) + '"><i class="bi bi-trash3"></i></button>' +
                    '</div></article></div>';
            }).join('');

            // Si una imagen remota falla (sin internet, enlace roto), se muestra el marcador
            $('gridMenu').querySelectorAll('img[data-cat]').forEach(img => {
                img.addEventListener('error', function () {
                    this.replaceWith(Object.assign(document.createElement('div'), { className: 'bn-img-vacia', innerHTML: '<i class="bi ' + (ICONOS[this.dataset.cat] || 'bi-cup-hot') + '"></i>' }));
                }, { once: true });
            });
        }

        // ---------- Filtros y búsqueda ----------
        $('chipsCategoria').addEventListener('click', e => {
            const ch = e.target.closest('.bn-chip');
            if (!ch) return;
            categoriaActiva = ch.dataset.cat;
            render();
        });
        $('buscador').addEventListener('input', render);

        // ---------- Disponibilidad y eliminación ----------
        $('gridMenu').addEventListener('change', e => {
            const sw = e.target.closest('input[data-id]');
            if (!sw) return;
            const p = menu.find(x => String(x.id) === sw.dataset.id);
            if (!p) return;
            p.disponible = sw.checked;
            guardar();
            render();
            aviso(p.nombre + ' ahora está ' + (p.disponible ? 'Disponible' : 'Agotado') + '.', p.disponible ? 'ok' : 'aviso');
        });

        const modalEliminar = new bootstrap.Modal($('modalEliminar'));
        $('gridMenu').addEventListener('click', e => {
            const btn = e.target.closest('button[data-eliminar]');
            if (!btn) return;
            const p = menu.find(x => String(x.id) === btn.dataset.eliminar);
            if (!p) return;
            idParaEliminar = p.id;
            $('eliminarNombre').textContent = p.nombre;
            modalEliminar.show();
        });
        $('btnConfirmarEliminar').addEventListener('click', () => {
            const p = menu.find(x => x.id === idParaEliminar);
            menu = menu.filter(x => x.id !== idParaEliminar);
            guardar();
            modalEliminar.hide();
            render();
            if (p) aviso('"' + p.nombre + '" eliminado del menú.', 'aviso');
            idParaEliminar = null;
        });

        // ---------- Nuevo platillo ----------
        const modalPlatillo = new bootstrap.Modal($('modalPlatillo'));
        const form = $('formPlatillo');

        function limpiarForm() {
            form.reset();
            form.classList.remove('was-validated');
            ['fNombre', 'fPrecio', 'fDescripcion'].forEach(id => $(id).classList.remove('is-invalid'));
            imagenNueva = '';
            $('fPreview').innerHTML = '<i class="bi bi-image"></i>';
            $('errImagen').classList.add('d-none');
        }
        $('btnNuevo').addEventListener('click', () => { limpiarForm(); modalPlatillo.show(); });
        $('modalPlatillo').addEventListener('shown.bs.modal', () => $('fNombre').focus());

        // Redimensiona la imagen a máx. 640 px y la convierte a JPEG para que ocupe poco espacio
        function procesarImagen(archivo) {
            return new Promise((resolve, reject) => {
                const url = URL.createObjectURL(archivo);
                const img = new Image();
                img.onload = () => {
                    const max = 640;
                    const r = Math.min(1, max / Math.max(img.width, img.height));
                    const c = document.createElement('canvas');
                    c.width = Math.round(img.width * r);
                    c.height = Math.round(img.height * r);
                    const ctx = c.getContext('2d');
                    ctx.fillStyle = '#fff';
                    ctx.fillRect(0, 0, c.width, c.height);
                    ctx.drawImage(img, 0, 0, c.width, c.height);
                    URL.revokeObjectURL(url);
                    resolve(c.toDataURL('image/jpeg', 0.82));
                };
                img.onerror = () => { URL.revokeObjectURL(url); reject(new Error('imagen inválida')); };
                img.src = url;
            });
        }

        $('fImagen').addEventListener('change', async function () {
            const err = $('errImagen');
            err.classList.add('d-none');
            imagenNueva = '';
            $('fPreview').innerHTML = '<i class="bi bi-image"></i>';
            const f = this.files[0];
            if (!f) return;
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(f.type)) {
                err.textContent = 'Formato no permitido. Usa JPG, PNG o WEBP.';
                err.classList.remove('d-none'); this.value = ''; return;
            }
            if (f.size > MAX_ARCHIVO) {
                err.textContent = 'La imagen pesa más de 5 MB.';
                err.classList.remove('d-none'); this.value = ''; return;
            }
            try {
                imagenNueva = await procesarImagen(f);
                const im = document.createElement('img');
                im.src = imagenNueva; im.alt = 'Vista previa';
                $('fPreview').replaceChildren(im);
            } catch (e) {
                err.textContent = 'No se pudo leer la imagen. Prueba con otra.';
                err.classList.remove('d-none'); this.value = '';
            }
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const nombre = $('fNombre').value.trim();
            const categoria = $('fCategoria').value;
            const precio = parseFloat($('fPrecio').value);
            const descripcion = $('fDescripcion').value.trim();
            const ingredientes = $('fIngredientes').value.trim();

            let ok = true;
            const marca = (id, cond, msg) => {
                const el = $(id);
                el.classList.toggle('is-invalid', !cond);
                if (!cond) ok = false;
                if (msg && !cond) { const f = $('errNombre'); if (id === 'fNombre') f.textContent = msg; }
            };
            const repetido = menu.some(p => normaliza(p.nombre) === normaliza(nombre) && p.categoria === categoria);
            marca('fNombre', nombre.length > 0 && !repetido,
                  repetido ? 'Ya existe un platillo con ese nombre en esta categoría.' : 'Escribe el nombre del platillo.');
            marca('fPrecio', Number.isFinite(precio) && precio >= 1 && precio <= 9999);
            marca('fDescripcion', descripcion.length > 0);
            if (!CATEGORIAS.includes(categoria)) ok = false;
            if (!ok) return;

            const nuevoId = menu.reduce((m, p) => Math.max(m, p.id), 0) + 1;
            menu.unshift({
                id: nuevoId,
                nombre: nombre,
                categoria: categoria,
                precio: Math.round(precio * 100) / 100,
                disponible: true,
                descripcion: descripcion,
                ingredientes: ingredientes,
                img: imagenNueva
            });
            if (!guardar()) { menu.shift(); return; } // si no cabe en el navegador, se revierte

            // Se limpian filtros para que el nuevo platillo se vea de inmediato
            categoriaActiva = 'todos';
            $('buscador').value = '';
            modalPlatillo.hide();
            render();
            aviso('Platillo "' + nombre + '" registrado correctamente.');
        });
        ['fNombre', 'fPrecio', 'fDescripcion'].forEach(id => $(id).addEventListener('input', e => e.target.classList.remove('is-invalid')));

        render();
    })();
    </script>
</body>
</html>
