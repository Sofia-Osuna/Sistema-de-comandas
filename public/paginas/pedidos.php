<?php
// ==========================================
// PANTALLA: Monitor General de Pedidos (Admin)
// ==========================================
// Datos de ejemplo (mismo enfoque que cuentas_ventas.php) mientras no exista la BD.
// Los tiempos se calculan relativos al momento de la carga para que el
// "tiempo de espera" se vea real. 'min' = hace cuántos minutos se creó el pedido;
// 'dur' = minutos que tardó en cerrarse (solo entregados/cancelados).

$ahora_ms = (int) round(microtime(true) * 1000);

$mesas = range(1, 8);

$pedidos_base = [
    ['id' => 'PED-112', 'mesa' => 3, 'mesero' => 'Ana R.',    'estado' => 'pendiente',  'min' => 3,   'dur' => null,
     'items' => [['Latte de Avellana', 2, 75.00], ['Croissant de Almendras', 1, 85.00]], 'notas' => ''],
    ['id' => 'PED-111', 'mesa' => 5, 'mesero' => 'Luis G.',   'estado' => 'pendiente',  'min' => 6,   'dur' => null,
     'items' => [['Cheesecake de Frutos Rojos', 1, 110.00], ['Flat White', 1, 70.00]], 'notas' => 'Bien caliente el Flat White.'],
    ['id' => 'PED-109', 'mesa' => 7, 'mesero' => 'Carlos M.', 'estado' => 'preparando', 'min' => 11,  'dur' => null,
     'items' => [['Omelette de Champiñones', 2, 140.00], ['Jugo de Naranja', 2, 60.00]], 'notas' => 'Cubiertos extra.'],
    ['id' => 'PED-107', 'mesa' => 4, 'mesero' => 'Carlos M.', 'estado' => 'preparando', 'min' => 19,  'dur' => null,
     'items' => [['Latte de Avellana', 2, 75.00], ['Panini Pavo & Queso Brie', 1, 190.00]], 'notas' => 'Sin leche entera, usar leche de almendras en los lattes.'],
    ['id' => 'PED-108', 'mesa' => 2, 'mesero' => 'Ana R.',    'estado' => 'listo',      'min' => 17,  'dur' => null,
     'items' => [['Croissant de Almendras', 1, 85.00], ['Espresso Doble', 1, 55.00]], 'notas' => 'El croissant es para llevar.'],
    ['id' => 'PED-110', 'mesa' => 1, 'mesero' => 'Luis G.',   'estado' => 'listo',      'min' => 8,   'dur' => null,
     'items' => [['Cold Brew Vainilla Coconut', 2, 90.00]], 'notas' => ''],
    ['id' => 'PED-106', 'mesa' => 8, 'mesero' => 'Luis G.',   'estado' => 'entregado',  'min' => 42,  'dur' => 18,
     'items' => [['Omelette de Champiñones', 2, 140.00], ['Jugo de Naranja', 2, 60.00]], 'notas' => ''],
    ['id' => 'PED-105', 'mesa' => 6, 'mesero' => 'Ana R.',    'estado' => 'entregado',  'min' => 55,  'dur' => 21,
     'items' => [['Cappuccino', 3, 65.00], ['Cheesecake de Frutos Rojos', 1, 110.00]], 'notas' => ''],
    ['id' => 'PED-104', 'mesa' => 2, 'mesero' => 'Carlos M.', 'estado' => 'entregado',  'min' => 78,  'dur' => 16,
     'items' => [['Panini Pavo & Queso Brie', 2, 190.00]], 'notas' => 'Sin brie en uno de los paninis.'],
    ['id' => 'PED-103', 'mesa' => 5, 'mesero' => 'Luis G.',   'estado' => 'entregado',  'min' => 96,  'dur' => 14,
     'items' => [['Espresso Doble', 2, 55.00], ['Croissant de Almendras', 2, 85.00]], 'notas' => ''],
    ['id' => 'PED-102', 'mesa' => 3, 'mesero' => 'Ana R.',    'estado' => 'cancelado',  'min' => 110, 'dur' => 6,
     'items' => [['Cold Brew Vainilla Coconut', 1, 90.00]], 'notas' => '',
     'motivo' => 'Cliente se retiró antes de tiempo', 'obs' => ''],
    ['id' => 'PED-101', 'mesa' => 1, 'mesero' => 'Carlos M.', 'estado' => 'entregado',  'min' => 130, 'dur' => 19,
     'items' => [['Cappuccino', 2, 65.00], ['Panini Pavo & Queso Brie', 1, 190.00]], 'notas' => ''],
];

$pedidos_semilla = [];
foreach ($pedidos_base as $p) {
    $creado = $ahora_ms - $p['min'] * 60000;
    $pedidos_semilla[] = [
        'id'      => $p['id'],
        'mesa'    => $p['mesa'],
        'mesero'  => $p['mesero'],
        'estado'  => $p['estado'],
        'creado'  => $creado,
        'fin'     => $p['dur'] !== null ? $creado + $p['dur'] * 60000 : null,
        'items'   => array_map(fn($i) => ['nombre' => $i[0], 'cant' => $i[1], 'precio' => $i[2]], $p['items']),
        'notas'   => $p['notas'],
        'motivo'  => $p['motivo'] ?? '',
        'obs'     => $p['obs'] ?? '',
    ];
}

// Flags seguros para incrustar JSON dentro de <script>
$json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
?>
<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beanora - Monitor de Pedidos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/modulos-admin.css">
</head>
<body class="d-flex flex-column flex-lg-row h-100" style="overflow-x: hidden;">

    <!-- 1. SIDEBAR / NAVBAR GLOBAL (marca "Monitor de Pedidos" como activo según el archivo actual) -->
    <?php include '../../includes/sidebar.php'; ?>

    <!-- 2. CONTENIDO PRINCIPAL -->
    <main class="w-100 flex-grow-1 p-3 p-lg-4 overflow-y-auto" style="background-color: var(--fondo); min-height: 100vh;">

        <!-- Cabecera -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-4">
            <div>
                <h2 class="bn-titulo">Monitor General de Pedidos</h2>
                <p class="bn-subtitulo">Supervisión en tiempo real entre el salón y la cocina</p>
            </div>
            <span class="bn-en-vivo"><span class="punto"></span> En vivo · actualizado <strong id="horaActualizacion">--:--:--</strong></span>
        </div>

        <!-- Filtros + contador -->
        <div class="bn-card bn-filtros mb-4">
            <div class="d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center">
                <div class="d-flex flex-column flex-sm-row gap-2 flex-grow-1" style="max-width: 560px;">
                    <div class="flex-fill">
                        <label for="filtroEstado" class="bn-label">Estado</label>
                        <select id="filtroEstado" class="form-select bn-select">
                            <option value="todos">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="preparando">Preparando</option>
                            <option value="listo">Listo</option>
                            <option value="entregado">Entregado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="flex-fill">
                        <label for="filtroMesa" class="bn-label">Mesa</label>
                        <select id="filtroMesa" class="form-select bn-select">
                            <option value="todas">Todas las mesas</option>
                            <?php foreach ($mesas as $m): ?>
                                <option value="<?= $m; ?>">Mesa <?= $m; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="d-flex flex-column align-items-lg-end gap-1">
                    <span class="bn-contador">
                        <i class="bi bi-receipt-cutoff" style="color: var(--acento);"></i>
                        Pedidos registrados hoy
                        <strong id="totalHoy">0</strong>
                    </span>
                    <small class="text-muted pe-lg-2" id="textoMostrando"></small>
                </div>
            </div>
        </div>

        <!-- Tabla de pedidos -->
        <div class="bn-card overflow-hidden mb-5">
            <div class="table-responsive">
                <table class="table bn-tabla align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4"># Pedido</th>
                            <th>Mesa</th>
                            <th>Mesero</th>
                            <th>Estado</th>
                            <th>Hora</th>
                            <th>Tiempo de espera</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPedidos"></tbody>
                </table>
            </div>
            <div id="vacioPedidos" class="bn-vacio d-none">
                <i class="bi bi-inboxes"></i>
                <h4>Sin pedidos para mostrar</h4>
                <p class="mb-0">Prueba cambiando los filtros de estado o de mesa.</p>
            </div>
        </div>
    </main>

    <!-- MODAL: DETALLE DEL PEDIDO -->
    <div class="modal fade bn-modal" id="modalDetalle" tabindex="-1" aria-labelledby="detalleTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="detalleTitulo">Detalle del pedido</h5>
                        <small class="text-muted" id="detalleSub"></small>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <span id="detalleEstado"></span>
                        <small class="text-muted" id="detalleTiempos"></small>
                    </div>
                    <div class="d-flex flex-column gap-2 mb-3" id="detalleItems"></div>
                    <div class="bn-nota mb-3" id="detalleNotas"></div>
                    <div class="bn-nota d-none mb-3" id="detalleMotivo" style="background:#fbe3e0; border-color:#f4c6c0; color:#8f2a20;"></div>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3" style="border-color: var(--borde) !important;">
                        <span class="text-muted">Total del pedido</span>
                        <span class="fw-bold fs-4" style="color: var(--verde);" id="detalleTotal">$0.00</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bn-btn bn-btn-verde" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: CANCELAR PEDIDO -->
    <div class="modal fade bn-modal" id="modalCancelar" tabindex="-1" aria-labelledby="cancelarTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="formCancelar" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelarTitulo"><i class="bi bi-exclamation-triangle-fill me-2" style="color:#c0392b;"></i>Cancelar pedido</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Indica el motivo para dejar constancia en la bitácora. Vas a cancelar el pedido <strong id="cancelarId"></strong>.</p>
                    <div class="mb-3">
                        <label for="cancelarMotivo" class="bn-label">Motivo de cancelación</label>
                        <select id="cancelarMotivo" class="form-select bn-select" required>
                            <option value="">Selecciona un motivo...</option>
                            <option>Cliente se retiró antes de tiempo</option>
                            <option>Error de captura del mesero</option>
                            <option>Sin insumos en cocina o barra</option>
                            <option>Otro motivo</option>
                        </select>
                        <div class="invalid-feedback">Selecciona un motivo para continuar.</div>
                    </div>
                    <div>
                        <label for="cancelarObs" class="bn-label">Observaciones (opcional)</label>
                        <textarea id="cancelarObs" class="form-control bn-input" rows="3" maxlength="200" placeholder="Detalles adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bn-btn bn-btn-suave" data-bs-dismiss="modal">Regresar</button>
                    <button type="submit" class="bn-btn bn-btn-peligro"><i class="bi bi-x-circle"></i> Confirmar cancelación</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bn-toasts" id="avisos" aria-live="polite"></div>

    <script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        'use strict';

        // ---------- Datos ----------
        // Los pedidos del día se guardan en el navegador (localStorage) para que
        // las cancelaciones sobrevivan a recargar la página. Cuando exista la BD,
        // basta con reemplazar cargar()/guardar() por llamadas a /acciones/*.php.
        const SEMILLA = <?= json_encode($pedidos_semilla, $json_flags); ?>;
        const CLAVE   = 'beanora_pedidos_<?= date('Ymd'); ?>';
        const ESPERA_AVISO  = 10;  // minutos: aviso amarillo
        const ESPERA_ALERTA = 15;  // minutos: alerta roja

        const ESTADOS = {
            pendiente:  { txt: 'Pendiente',  icono: 'bi-hourglass-split' },
            preparando: { txt: 'Preparando', icono: 'bi-fire' },
            listo:      { txt: 'Listo',      icono: 'bi-bell-fill' },
            entregado:  { txt: 'Entregado',  icono: 'bi-check2-circle' },
            cancelado:  { txt: 'Cancelado',  icono: 'bi-x-circle' }
        };

        let pedidos = cargar();
        let pedidoParaCancelar = null;

        function cargar() {
            try {
                const crudo = localStorage.getItem(CLAVE);
                if (crudo) {
                    const datos = JSON.parse(crudo);
                    if (Array.isArray(datos) && datos.length) return datos;
                }
            } catch (e) { /* almacenamiento no disponible: se usa la semilla */ }
            const inicial = SEMILLA.map(p => Object.assign({}, p));
            guardar(inicial);
            return inicial;
        }
        function guardar(lista) {
            try { localStorage.setItem(CLAVE, JSON.stringify(lista || pedidos)); } catch (e) {}
        }

        // ---------- Utilidades ----------
        const $ = id => document.getElementById(id);
        const MAPA_HTML = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
        const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => MAPA_HTML[c]);
        const dinero = n => n.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
        const hora = ms => new Date(ms).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit', hour12: false });
        const totalPedido = p => p.items.reduce((s, i) => s + i.cant * i.precio, 0);

        function minutosEspera(p) {
            const fin = p.fin != null ? p.fin : Date.now();
            return Math.max(0, Math.floor((fin - p.creado) / 60000));
        }
        function formatoMinutos(m) {
            if (m < 60) return m + ' min';
            const h = Math.floor(m / 60), r = m % 60;
            return h + ' h ' + String(r).padStart(2, '0') + ' min';
        }
        function etiquetaEstado(estado) {
            const e = ESTADOS[estado] || ESTADOS.pendiente;
            return '<span class="bn-estado bn-estado-' + esc(estado) + '"><i class="bi ' + e.icono + '"></i>' + e.txt + '</span>';
        }
        function celdaEspera(p) {
            const m = minutosEspera(p);
            if (p.estado === 'entregado' || p.estado === 'cancelado') {
                return '<span class="bn-espera fin">' + formatoMinutos(m) + '</span>';
            }
            let clase = '', icono = '';
            if (m >= ESPERA_ALERTA) { clase = 'alerta'; icono = '<i class="bi bi-exclamation-circle-fill me-1"></i>'; }
            else if (m >= ESPERA_AVISO) { clase = 'aviso'; icono = '<i class="bi bi-clock-fill me-1"></i>'; }
            return '<span class="bn-espera ' + clase + '">' + icono + formatoMinutos(m) + '</span>';
        }

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

        // ---------- Tabla ----------
        function renderTabla() {
            const fEstado = $('filtroEstado').value;
            const fMesa   = $('filtroMesa').value;

            const lista = pedidos
                .filter(p => (fEstado === 'todos' || p.estado === fEstado) &&
                             (fMesa === 'todas' || String(p.mesa) === fMesa))
                .sort((a, b) => b.creado - a.creado);

            $('totalHoy').textContent = pedidos.length;
            $('textoMostrando').textContent = lista.length === pedidos.length
                ? 'Mostrando todos los pedidos'
                : 'Mostrando ' + lista.length + ' de ' + pedidos.length;

            $('vacioPedidos').classList.toggle('d-none', lista.length > 0);

            $('tbodyPedidos').innerHTML = lista.map(p => {
                const puedeCancelar = p.estado !== 'entregado' && p.estado !== 'cancelado';
                return '<tr class="' + (p.estado === 'cancelado' ? 'cancelado' : '') + '">' +
                    '<td class="ps-4 bn-num-pedido">' + esc(p.id) + '</td>' +
                    '<td>Mesa ' + esc(p.mesa) + '</td>' +
                    '<td>' + esc(p.mesero) + '</td>' +
                    '<td>' + etiquetaEstado(p.estado) + '</td>' +
                    '<td class="text-muted">' + hora(p.creado) + '</td>' +
                    '<td>' + celdaEspera(p) + '</td>' +
                    '<td class="text-end pe-4 text-nowrap">' +
                        '<button type="button" class="bn-accion me-1" data-accion="detalle" data-id="' + esc(p.id) + '"><i class="bi bi-eye"></i> Detalle</button>' +
                        '<button type="button" class="bn-accion peligro" data-accion="cancelar" data-id="' + esc(p.id) + '"' +
                            (puedeCancelar ? '' : ' disabled') +
                            ' title="' + (puedeCancelar ? 'Cancelar pedido' : 'Este pedido ya no se puede cancelar') + '"><i class="bi bi-x-circle"></i> Cancelar</button>' +
                    '</td></tr>';
            }).join('');

            $('horaActualizacion').textContent = new Date().toLocaleTimeString('es-MX', { hour12: false });
        }

        // ---------- Detalle ----------
        const modalDetalle  = new bootstrap.Modal($('modalDetalle'));
        const modalCancelar = new bootstrap.Modal($('modalCancelar'));

        function verDetalle(id) {
            const p = pedidos.find(x => x.id === id);
            if (!p) return;
            $('detalleTitulo').textContent = 'Pedido ' + p.id;
            $('detalleSub').textContent = 'Mesa ' + p.mesa + ' · Atendido por ' + p.mesero;
            $('detalleEstado').innerHTML = etiquetaEstado(p.estado);
            $('detalleTiempos').textContent = 'Hora: ' + hora(p.creado) + ' · Espera: ' + formatoMinutos(minutosEspera(p));
            $('detalleItems').innerHTML = p.items.map(i =>
                '<div class="bn-item-linea">' +
                    '<span><strong>' + esc(i.cant) + '×</strong> ' + esc(i.nombre) +
                    ' <span class="text-muted small">(' + dinero(i.precio) + ' c/u)</span></span>' +
                    '<strong>' + dinero(i.cant * i.precio) + '</strong></div>').join('');
            $('detalleNotas').innerHTML = '<strong><i class="bi bi-sticky me-1"></i>Notas de cocina / barra:</strong><br>' +
                (p.notas ? esc(p.notas) : '<em>Sin notas especiales.</em>');
            const caja = $('detalleMotivo');
            if (p.estado === 'cancelado' && p.motivo) {
                caja.classList.remove('d-none');
                caja.innerHTML = '<strong><i class="bi bi-x-octagon me-1"></i>Motivo de cancelación:</strong><br>' + esc(p.motivo) +
                    (p.obs ? '<br><span class="small">' + esc(p.obs) + '</span>' : '');
            } else {
                caja.classList.add('d-none');
            }
            $('detalleTotal').textContent = dinero(totalPedido(p));
            modalDetalle.show();
        }

        // ---------- Cancelación ----------
        function abrirCancelar(id) {
            const p = pedidos.find(x => x.id === id);
            if (!p || p.estado === 'entregado' || p.estado === 'cancelado') return;
            pedidoParaCancelar = id;
            $('cancelarId').textContent = id;
            $('formCancelar').reset();
            $('cancelarMotivo').classList.remove('is-invalid');
            modalCancelar.show();
        }

        $('formCancelar').addEventListener('submit', function (e) {
            e.preventDefault();
            const motivo = $('cancelarMotivo').value;
            if (!motivo) { $('cancelarMotivo').classList.add('is-invalid'); return; }
            const p = pedidos.find(x => x.id === pedidoParaCancelar);
            if (!p) return;
            p.estado = 'cancelado';
            p.fin    = Date.now();
            p.motivo = motivo;
            p.obs    = $('cancelarObs').value.trim().slice(0, 200);
            guardar();
            modalCancelar.hide();
            renderTabla();
            aviso('Pedido ' + p.id + ' cancelado y registrado en la bitácora.', 'aviso');
            pedidoParaCancelar = null;
        });
        $('cancelarMotivo').addEventListener('change', e => e.target.classList.remove('is-invalid'));

        // ---------- Eventos ----------
        $('tbodyPedidos').addEventListener('click', function (e) {
            const btn = e.target.closest('button[data-accion]');
            if (!btn) return;
            if (btn.dataset.accion === 'detalle') verDetalle(btn.dataset.id);
            if (btn.dataset.accion === 'cancelar') abrirCancelar(btn.dataset.id);
        });
        $('filtroEstado').addEventListener('change', renderTabla);
        $('filtroMesa').addEventListener('change', renderTabla);

        // Actualiza los tiempos de espera cada 30 s ("tiempo real")
        renderTabla();
        setInterval(renderTabla, 30000);
    })();
    </script>
</body>
</html>
