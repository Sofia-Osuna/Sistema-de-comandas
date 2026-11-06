<?php
/**
 * Beanora - Dashboard (Resumen del Día)
 * Ubicación: public/dashboard.php
 *
 * Los datos de abajo son de ejemplo (los mismos de la maqueta).
 * Sustitúyelos por consultas a la base de datos cuando tengas los modelos.
 */

$paginaActual = 'dashboard'; // por si el sidebar marca el ítem activo con esta variable

/* ---------- Fecha en español ---------- */
$meses  = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
$hoyStr = date('j') . ' de ' . ucfirst($meses[(int)date('n') - 1]) . ', ' . date('Y');

/* ---------- Datos de ejemplo ---------- */
$alerta = [
    'mesa'   => 4,
    'minutos'=> 42,
    'mesero' => 'Carlos Morales',
];

$tarjetas = [
    [
        'titulo' => 'Ventas del Día',
        'valor'  => '$12,450.00',
        'detalle'=> '<i class="bi bi-arrow-up"></i> +14.5% vs ayer',
        'clase'  => 'ok',
        'icono'  => 'bi-currency-dollar',
        'tono'   => 'verde',
    ],
    [
        'titulo' => 'Pedidos Activos',
        'valor'  => '8',
        'detalle'=> '3 en cocina / 5 listos',
        'clase'  => 'aviso',
        'icono'  => 'bi-bell',
        'tono'   => 'ambar',
    ],
    [
        'titulo' => 'Mesas Ocupadas',
        'valor'  => '6 / 12',
        'detalle'=> '50% de ocupación',
        'clase'  => 'info',
        'icono'  => 'bi-people',
        'tono'   => 'indigo',
    ],
    [
        'titulo' => 'Más Vendido',
        'valor'  => 'Latte Avellana',
        'detalle'=> '45 unidades hoy',
        'clase'  => 'acento',
        'icono'  => 'bi-cup-hot',
        'tono'   => 'ambar',
        'valor_chico' => true,
    ],
];

$ultimosPedidos = [
    ['folio' => 'PED-104', 'mesa' => 4, 'mesero' => 'Carlos M.', 'estado' => 'preparando', 'texto' => 'Preparando',      'total' => 340.00],
    ['folio' => 'PED-103', 'mesa' => 2, 'mesero' => 'Ana R.',    'estado' => 'listo',      'texto' => 'Listo',           'total' => 185.00],
    ['folio' => 'PED-102', 'mesa' => 8, 'mesero' => 'Luis G.',   'estado' => 'cuenta',     'texto' => 'Esperando Cta.',  'total' => 520.00],
];

$ventasHora = [
    'etiquetas' => ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
    'valores'   => [1200, 2400, 3800, 2650, 1800, 2900, 1400],
];

$promedioPrep = 14; // minutos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beanora - Dashboard</title>

    <!-- Bootstrap 5 + Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">

    <!-- Estilos del proyecto -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/modulos-admin.css">
</head>
<body>

<div class="d-flex flex-column flex-lg-row">

    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="contenido-panel flex-grow-1 p-3 p-md-4 p-lg-5">

        <!-- ================= Encabezado ================= -->
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="bn-titulo">Resumen del Día</h1>
                <p class="bn-subtitulo">Bienvenido de vuelta. Aquí está el estado actual del restaurante.</p>
            </div>
            <span class="bn-contador align-self-start">
                <i class="bi bi-calendar3" style="color: var(--acento);"></i>
                <?= htmlspecialchars($hoyStr) ?>
            </span>
        </div>

        <!-- ================= Alerta de espera (HU-04) ================= -->
        <div class="bn-alerta d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 p-3 mb-4" role="alert">
            <div class="d-flex align-items-start gap-3">
                <div class="bn-alerta-icono">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h2 class="bn-alerta-titulo">Alerta de Atención en Mesas</h2>
                    <p class="bn-alerta-texto mb-0">
                        Mesa <?= (int)$alerta['mesa'] ?> lleva <strong><?= (int)$alerta['minutos'] ?> min</strong>
                        sin registrar nuevos pedidos o atención.
                        Mesero asignado: <strong><?= htmlspecialchars($alerta['mesero']) ?></strong>.
                    </p>
                </div>
            </div>
            <a href="paginas/mesas.php" class="bn-btn bn-btn-alerta flex-shrink-0 text-decoration-none">Ver Mesa</a>
        </div>

        <!-- ================= Tarjetas de resumen (HU-03) ================= -->
        <div class="row g-3 g-xl-4 mb-4">
            <?php foreach ($tarjetas as $t): ?>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="bn-card bn-stat h-100 p-3 p-xl-4 d-flex align-items-center justify-content-between gap-3">
                        <div class="min-w-0">
                            <p class="bn-stat-etiqueta mb-1"><?= htmlspecialchars($t['titulo']) ?></p>
                            <h3 class="bn-stat-valor <?= !empty($t['valor_chico']) ? 'bn-stat-valor-chico text-truncate' : '' ?> mb-1">
                                <?= htmlspecialchars($t['valor']) ?>
                            </h3>
                            <p class="bn-stat-detalle bn-stat-<?= $t['clase'] ?> mb-0"><?= $t['detalle'] ?></p>
                        </div>
                        <div class="bn-stat-icono bn-tono-<?= $t['tono'] ?>">
                            <i class="bi <?= $t['icono'] ?>"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ================= Gráfica + últimos pedidos ================= -->
        <div class="row g-3 g-xl-4">

            <!-- Ventas por hora -->
            <div class="col-12 col-lg-8">
                <div class="bn-card h-100 p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="bn-card-titulo">Ventas por Hora</h2>
                        <span class="bn-en-vivo"><span class="punto"></span> Actualizado hace 5 min</span>
                    </div>
                    <div class="bn-grafica">
                        <canvas id="salesHourlyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Últimos pedidos -->
            <div class="col-12 col-lg-4">
                <div class="bn-card h-100 p-3 p-md-4 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="bn-card-titulo">Últimos Pedidos</h2>
                        <a href="paginas/pedidos.php" class="enlace-acento">Ver todos</a>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($ultimosPedidos as $p): ?>
                            <div class="bn-pedido-mini d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <span class="bn-num-pedido small">#<?= htmlspecialchars($p['folio']) ?></span>
                                    <p class="small mb-0" style="color: var(--texto-suave);">
                                        Mesa <?= (int)$p['mesa'] ?> • <?= htmlspecialchars($p['mesero']) ?>
                                    </p>
                                </div>
                                <span class="bn-estado bn-estado-<?= $p['estado'] ?>"><?= htmlspecialchars($p['texto']) ?></span>
                                <span class="fw-bold small" style="color: var(--verde);">$<?= number_format($p['total'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-auto pt-3 text-center small border-top" style="color: var(--texto-suave); border-color: var(--borde) !important;">
                        Promedio de preparación hoy: <strong><?= (int)$promedioPrep ?> min</strong>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- Bootstrap JS + Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const ventas = <?= json_encode($ventasHora, JSON_UNESCAPED_UNICODE) ?>;

    new Chart(document.getElementById('salesHourlyChart'), {
        type: 'line',
        data: {
            labels: ventas.etiquetas,
            datasets: [{
                label: 'Ventas ($ MXN)',
                data: ventas.valores,
                borderColor: '#1f3a2b',
                backgroundColor: 'rgba(31, 58, 43, 0.08)',
                fill: true,
                tension: 0.35,
                borderWidth: 2,
                pointBackgroundColor: '#d98a2b'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f0ebe1' }, ticks: { font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });
</script>
</body>
</html>
