<?php
// Cuando exista auth.php, descomentar:
// require_once __DIR__ . '/../../includes/auth.php';
// requerirRol(['admin', 'mesero']);

// ---------- Datos de ejemplo (después vendrán de los modelos con PDO) ----------
// mesas: solo 'libre' y 'ocupada' con estatus '1' pueden recibir pedidos
$mesas = [
    ['id' => 1, 'cod' => 'M-01', 'estado' => 'libre',            'estatus' => '1'],
    ['id' => 2, 'cod' => 'M-02', 'estado' => 'ocupada',          'estatus' => '1'],
    ['id' => 3, 'cod' => 'M-03', 'estado' => 'libre',            'estatus' => '1'],
    ['id' => 4, 'cod' => 'M-04', 'estado' => 'ocupada',          'estatus' => '1'],
    ['id' => 5, 'cod' => 'M-05', 'estado' => 'esperando_cuenta', 'estatus' => '1'],
    ['id' => 6, 'cod' => 'M-06', 'estado' => 'libre',            'estatus' => '0'], // fuera de servicio
];

// usuarios con rol mesero
$meseros = [
    ['id' => 2, 'nombre' => 'Carlos Morales'],
    ['id' => 3, 'nombre' => 'Ana Ruiz'],
    ['id' => 6, 'nombre' => 'Luis Gómez'],
];

$categorias = ['Café', 'Repostería', 'Desayunos', 'Bebidas Frías'];

// productos activos; 'estado' = disponible | agotado
$productos = [
    ['id' => 1,  'nombre' => 'Latte de Avellana',          'categoria' => 'Café',          'precio' => 75.00,  'estado' => 'disponible'],
    ['id' => 2,  'nombre' => 'Cappuccino',                 'categoria' => 'Café',          'precio' => 65.00,  'estado' => 'disponible'],
    ['id' => 3,  'nombre' => 'Espresso Doble',             'categoria' => 'Café',          'precio' => 55.00,  'estado' => 'disponible'],
    ['id' => 4,  'nombre' => 'Flat White',                 'categoria' => 'Café',          'precio' => 70.00,  'estado' => 'disponible'],
    ['id' => 5,  'nombre' => 'Croissant de Almendras',     'categoria' => 'Repostería',    'precio' => 85.00,  'estado' => 'disponible'],
    ['id' => 6,  'nombre' => 'Cheesecake de Frutos Rojos', 'categoria' => 'Repostería',    'precio' => 110.00, 'estado' => 'disponible'],
    ['id' => 7,  'nombre' => 'Panini Pavo & Queso Brie',   'categoria' => 'Desayunos',     'precio' => 190.00, 'estado' => 'disponible'],
    ['id' => 8,  'nombre' => 'Omelette de Champiñones',    'categoria' => 'Desayunos',     'precio' => 140.00, 'estado' => 'disponible'],
    ['id' => 9,  'nombre' => 'Cold Brew Vainilla Coconut', 'categoria' => 'Bebidas Frías', 'precio' => 90.00,  'estado' => 'agotado'],
    ['id' => 10, 'nombre' => 'Jugo de Naranja',            'categoria' => 'Bebidas Frías', 'precio' => 60.00,  'estado' => 'disponible'],
];

$codSiguiente = 'P-0013'; // solo de referencia: lo genera el backend
$mesaInicial  = isset($_GET['mesa']) ? (int) $_GET['mesa'] : 0; // pk_mesa, por si vienes desde el tablero de mesas

$json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;

$etiquetaEstado = ['libre' => 'Libre', 'ocupada' => 'Ocupada', 'esperando_cuenta' => 'Esperando cuenta'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo Pedido | Beanora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/usuario-form.css">
    <link rel="stylesheet" href="../assets/css/pedido-form.css">
</head>
<body>
<div class="d-flex flex-column flex-lg-row">

    <?php include '../../includes/sidebar.php'; ?>

    <main class="contenido-panel flex-grow-1 p-3 p-md-4 p-lg-5">
        <div class="pedido-contenedor">

            <!-- Encabezado -->
            <a href="pedidos.php" class="volver d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left"></i> Volver a pedidos
            </a>
            <h1 class="titulo-pagina mb-1">Nuevo Pedido</h1>
            <p class="form-subtitulo mb-4">Elige la mesa, agrega los productos y envía la comanda a cocina.</p>

            <form id="formPedido" novalidate>
                <div class="pedido-layout">

                    <!-- ================= IZQUIERDA: mesa + productos ================= -->
                    <div class="d-flex flex-column gap-4 min-w-0">

                        <!-- Mesa y mesero -->
                        <section class="form-card">
                            <div class="form-seccion">
                                <h2 class="form-seccion-titulo">Mesa y mesero</h2>
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <label for="mesa" class="form-etiqueta">Mesa</label>
                                        <select id="mesa" class="form-select form-control" required>
                                            <option value="">Selecciona una mesa</option>
                                            <?php foreach ($mesas as $m):
                                                $libre = $m['estatus'] === '1' && in_array($m['estado'], ['libre', 'ocupada'], true);
                                                $motivo = $m['estatus'] === '0' ? 'Fuera de servicio' : $etiquetaEstado[$m['estado']];
                                            ?>
                                                <option value="<?= (int) $m['id'] ?>"
                                                        <?= $libre ? '' : 'disabled' ?>
                                                        <?= $mesaInicial === $m['id'] && $libre ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($m['cod']) ?> · <?= $motivo ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Selecciona la mesa.</div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="mesero" class="form-etiqueta">Mesero</label>
                                        <select id="mesero" class="form-select form-control" required>
                                            <option value="">Selecciona un mesero</option>
                                            <?php foreach ($meseros as $u): ?>
                                                <option value="<?= (int) $u['id'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Selecciona quién toma el pedido.</div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Productos -->
                        <section class="form-card">
                            <div class="form-seccion">
                                <h2 class="form-seccion-titulo">Productos</h2>

                                <div class="buscador mb-3">
                                    <i class="bi bi-search"></i>
                                    <input type="search" id="buscador" class="form-control buscador-input"
                                           placeholder="Buscar producto..." autocomplete="off" maxlength="50"
                                           aria-label="Buscar producto">
                                </div>

                                <div class="d-flex flex-wrap gap-2 mb-3" id="chipsCategoria">
                                    <button type="button" class="ped-chip activo" data-cat="todos">Todos</button>
                                    <?php foreach ($categorias as $c): ?>
                                        <button type="button" class="ped-chip"
                                                data-cat="<?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($c) ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>

                                <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-xl-3" id="gridProductos"></div>

                                <div id="sinProductos" class="text-center text-secondary py-4 d-none">
                                    <i class="bi bi-search fs-2 d-block mb-1"></i>
                                    No hay productos que coincidan.
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- ================= DERECHA: resumen del pedido ================= -->
                    <aside class="form-card ticket" id="ticket">
                        <div class="form-seccion">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="form-seccion-titulo mb-0">Pedido</h2>
                                <span class="ticket-cod"><?= htmlspecialchars($codSiguiente) ?></span>
                            </div>

                            <div id="ticketVacio" class="text-center text-secondary py-4">
                                <i class="bi bi-receipt fs-2 d-block mb-1"></i>
                                Aún no agregas productos.
                            </div>
                            <div id="ticketLineas"></div>
                            <div class="small text-danger mt-2 d-none" id="errLineas">Agrega al menos un producto.</div>
                        </div>

                        <div class="form-seccion">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary">Total</span>
                                <span class="ticket-total" id="total">$0.00</span>
                            </div>
                            <div class="form-ayuda">El total final lo calcula el sistema al guardar.</div>
                        </div>

                        <div class="form-acciones">
                            <a href="pedidos.php" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-principal d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-send-fill"></i> Enviar a cocina
                            </button>
                        </div>
                    </aside>

                </div>
            </form>

        </div>
    </main>
</div>

<!-- Barra inferior (solo celular): total y salto al resumen -->
<div class="barra-movil d-lg-none" id="barraMovil">
    <div>
        <div class="small text-secondary" id="barraCantidad">0 productos</div>
        <div class="fw-semibold" id="barraTotal">$0.00</div>
    </div>
    <a href="#ticket" class="btn btn-principal">Ver pedido <i class="bi bi-chevron-down"></i></a>
</div>

<div class="ped-toasts" id="avisos" aria-live="polite"></div>

<script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    'use strict';

    const PRODUCTOS = <?= json_encode($productos, $json_flags) ?>;
    const ICONOS = { 'Café': 'bi-cup-hot', 'Repostería': 'bi-cake2', 'Desayunos': 'bi-egg-fried', 'Bebidas Frías': 'bi-cup-straw' };

    const $ = id => document.getElementById(id);
    const MAPA = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
    const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => MAPA[c]);
    const dinero = n => n.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
    const normaliza = s => String(s || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();

    const carrito = new Map(); // id producto -> { id, nombre, precio, cantidad, notas }
    let categoriaActiva = 'todos';

    /* ---------- Catálogo ---------- */
    function renderProductos() {
        const q = normaliza($('buscador').value);
        const lista = PRODUCTOS.filter(p =>
            (categoriaActiva === 'todos' || p.categoria === categoriaActiva) &&
            (!q || normaliza(p.nombre).includes(q)));

        $('sinProductos').classList.toggle('d-none', lista.length > 0);
        $('gridProductos').innerHTML = lista.map(p => {
            const agotado = p.estado === 'agotado';
            const en = carrito.get(p.id);
            return '<div class="col"><button type="button" class="prod' + (agotado ? ' agotado' : '') + (en ? ' en-pedido' : '') + '"' +
                   ' data-agregar="' + p.id + '"' + (agotado ? ' disabled' : '') + '>' +
                   '<span class="prod-icono"><i class="bi ' + (ICONOS[p.categoria] || 'bi-cup-hot') + '"></i></span>' +
                   '<span class="prod-info">' +
                       '<span class="prod-nombre">' + esc(p.nombre) + '</span>' +
                       '<span class="prod-precio">' + (agotado ? 'Agotado' : dinero(p.precio)) + '</span>' +
                   '</span>' +
                   (en ? '<span class="prod-cant">' + en.cantidad + '</span>' : '') +
                   '</button></div>';
        }).join('');
    }

    $('chipsCategoria').addEventListener('click', e => {
        const ch = e.target.closest('.ped-chip');
        if (!ch) return;
        categoriaActiva = ch.dataset.cat;
        document.querySelectorAll('#chipsCategoria .ped-chip')
            .forEach(b => b.classList.toggle('activo', b === ch));
        renderProductos();
    });
    $('buscador').addEventListener('input', renderProductos);

    $('gridProductos').addEventListener('click', e => {
        const btn = e.target.closest('[data-agregar]');
        if (!btn) return;
        const p = PRODUCTOS.find(x => x.id === Number(btn.dataset.agregar));
        if (!p || p.estado === 'agotado') return;
        const en = carrito.get(p.id);
        if (en) en.cantidad++;
        else carrito.set(p.id, { id: p.id, nombre: p.nombre, precio: p.precio, cantidad: 1, notas: '' });
        $('errLineas').classList.add('d-none');
        renderTicket();
        renderProductos();
    });

    /* ---------- Resumen del pedido ---------- */
    function renderTicket() {
        const lineas = Array.from(carrito.values());
        $('ticketVacio').classList.toggle('d-none', lineas.length > 0);

        $('ticketLineas').innerHTML = lineas.map(l =>
            '<div class="linea" data-id="' + l.id + '">' +
                '<div class="d-flex justify-content-between gap-2">' +
                    '<span class="linea-nombre">' + esc(l.nombre) + '</span>' +
                    '<span class="linea-sub">' + dinero(l.precio * l.cantidad) + '</span>' +
                '</div>' +
                '<div class="d-flex align-items-center gap-2 mt-2">' +
                    '<div class="stepper">' +
                        '<button type="button" data-menos="' + l.id + '" aria-label="Quitar uno">−</button>' +
                        '<span>' + l.cantidad + '</span>' +
                        '<button type="button" data-mas="' + l.id + '" aria-label="Agregar uno">+</button>' +
                    '</div>' +
                    '<span class="text-secondary small">× ' + dinero(l.precio) + '</span>' +
                    '<button type="button" class="linea-quitar ms-auto" data-quitar="' + l.id + '" aria-label="Quitar ' + esc(l.nombre) + '"><i class="bi bi-trash3"></i></button>' +
                '</div>' +
                '<input type="text" class="form-control linea-notas" data-notas="' + l.id + '" maxlength="150"' +
                       ' placeholder="Notas (ej. sin cebolla)" value="' + esc(l.notas) + '" autocomplete="off">' +
            '</div>').join('');

        const total = lineas.reduce((s, l) => s + l.precio * l.cantidad, 0);
        const piezas = lineas.reduce((s, l) => s + l.cantidad, 0);
        $('total').textContent = dinero(total);
        $('barraTotal').textContent = dinero(total);
        $('barraCantidad').textContent = piezas + (piezas === 1 ? ' producto' : ' productos');
    }

    $('ticketLineas').addEventListener('click', e => {
        const mas = e.target.closest('[data-mas]');
        const menos = e.target.closest('[data-menos]');
        const quitar = e.target.closest('[data-quitar]');
        if (mas)    carrito.get(Number(mas.dataset.mas)).cantidad++;
        if (menos) {
            const l = carrito.get(Number(menos.dataset.menos));
            l.cantidad--;                              // cantidad debe ser mayor a 0
            if (l.cantidad < 1) carrito.delete(l.id);
        }
        if (quitar) carrito.delete(Number(quitar.dataset.quitar));
        if (mas || menos || quitar) { renderTicket(); renderProductos(); }
    });

    // Las notas se guardan al escribir, sin volver a dibujar (para no perder el foco)
    $('ticketLineas').addEventListener('input', e => {
        const n = e.target.closest('[data-notas]');
        if (n) carrito.get(Number(n.dataset.notas)).notas = n.value;
    });

    /* ---------- Enviar (solo diseño) ---------- */
    ['mesa', 'mesero'].forEach(id => $(id).addEventListener('change', e => e.target.classList.remove('is-invalid')));

    $('formPedido').addEventListener('submit', function (e) {
        e.preventDefault();

        const okMesa   = $('mesa').value !== '';
        const okMesero = $('mesero').value !== '';
        const okLineas = carrito.size > 0;

        $('mesa').classList.toggle('is-invalid', !okMesa);
        $('mesero').classList.toggle('is-invalid', !okMesero);
        $('errLineas').classList.toggle('d-none', okLineas);

        if (!okMesa || !okMesero || !okLineas) {
            (!okMesa ? $('mesa') : !okMesero ? $('mesero') : $('ticket')).scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        /* Cuando haya backend: fetch POST a public/acciones/crear-pedido.php con
           { fk_mesa, fk_usuario, detalles: [{ fk_producto, cantidad, notas }] }.
           El servidor debe tomar el precio de la BD (precio_unitario), calcular el total,
           generar cod_pedido y pasar la mesa a 'ocupada' si estaba libre. */
        const payload = {
            fk_mesa: Number($('mesa').value),
            fk_usuario: Number($('mesero').value),
            detalles: Array.from(carrito.values()).map(l => ({ fk_producto: l.id, cantidad: l.cantidad, notas: l.notas.trim() }))
        };
        console.log('Pedido a enviar:', payload);

        aviso('Pedido enviado a cocina.');
        carrito.clear();
        this.reset();
        renderTicket();
        renderProductos();
    });

    function aviso(texto) {
        const t = document.createElement('div');
        t.className = 'ped-toast';
        t.innerHTML = '<i class="bi bi-check-circle-fill"></i><span>' + esc(texto) + '</span>';
        $('avisos').appendChild(t);
        setTimeout(() => { t.classList.add('saliendo'); setTimeout(() => t.remove(), 300); }, 3200);
    }

    renderProductos();
    renderTicket();
})();
</script>
</body>
</html>