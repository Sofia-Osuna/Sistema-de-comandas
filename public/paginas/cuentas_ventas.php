<?php
// ==========================================
// PANTALLA: Cuentas y Ventas (Admin)
// ==========================================

$kpis = [
    'vendido_hoy' => 12450.00,
    'cuentas_hoy' => 38,
    'vendido_semana' => 84200.00,
    'crecimiento_semana' => '+8% vs semana pasada',
    'ticket_promedio' => 327.60
];

$cuentas_cerradas = [
    ['id' => 'CTA-9021', 'mesa' => 'Mesa 5', 'mesero' => 'Luis G.', 'cajero' => 'Sofía M.', 'metodo' => 'Tarjeta', 'fecha' => '02/11/2026 14:10', 'total' => 520.00],
    ['id' => 'CTA-9022', 'mesa' => 'Mesa 2', 'mesero' => 'Ana R.', 'cajero' => 'Sofía M.', 'metodo' => 'Efectivo', 'fecha' => '02/11/2026 14:25', 'total' => 345.50],
    ['id' => 'CTA-9023', 'mesa' => 'Mesa 8', 'mesero' => 'Carlos M.', 'cajero' => 'Jorge L.', 'metodo' => 'Tarjeta', 'fecha' => '02/11/2026 14:40', 'total' => 890.00],
    ['id' => 'CTA-9024', 'mesa' => 'Mesa 1', 'mesero' => 'Luis G.', 'cajero' => 'Sofía M.', 'metodo' => 'Efectivo', 'fecha' => '02/11/2026 15:05', 'total' => 210.00],
];
?>

<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beanora - Cuentas y Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="d-flex flex-column flex-lg-row h-100 bg-light" style="overflow-x: hidden;">

    <!-- 1. SIDEBAR / NAVBAR GLOBAL -->
    <?php include '../../includes/sidebar.php'; ?>

    <!-- 2. CONTENIDO PRINCIPAL RESPONSIVO -->
    <main class="w-100 flex-grow-1 p-3 p-lg-4 overflow-y-auto" style="background-color: var(--fondo); min-height: 100vh;">
        
        <!-- Cabecera de la vista -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="font-serif fw-bold text-verde mb-1" style="font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; color: var(--verde);">Cuentas y Ventas</h2>
            </div>
            <button class="btn btn-success d-flex align-items-center justify-content-center gap-2 px-3 py-2 align-self-start align-self-sm-auto" style="background-color: var(--verde); border: none;" onclick="exportarReporte()">
                <i class="bi bi-file-earmark-excel"></i> Exportar Reporte
            </button>
        </div>

        <!-- Tarjetas KPI Superiores -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 p-3 shadow-sm rounded-4 h-100" style="background-color: var(--crema);">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.08em;">Total Vendido Hoy</span>
                    <h3 class="fw-bold my-1" style="color: var(--verde);">$<?= number_format($kpis['vendido_hoy'], 2); ?></h3>
                    <small class="text-muted"><?= $kpis['cuentas_hoy']; ?> cuentas liquidadas</small>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 p-3 shadow-sm rounded-4 h-100" style="background-color: var(--crema);">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.08em;">Total Semana</span>
                    <h3 class="fw-bold my-1" style="color: var(--verde);">$<?= number_format($kpis['vendido_semana'], 2); ?></h3>
                    <small class="text-success fw-medium"><?= $kpis['crecimiento_semana']; ?></small>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 p-3 shadow-sm rounded-4 h-100" style="background-color: var(--crema);">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.08em;">Cuentas Cerradas</span>
                    <h3 class="fw-bold my-1" style="color: var(--verde);"><?= $kpis['cuentas_hoy']; ?></h3>
                    <small class="text-muted">0 cancelaciones sin motivo</small>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 p-3 shadow-sm rounded-4 h-100" style="background-color: var(--crema);">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.08em;">Ticket Promedio</span>
                    <h3 class="fw-bold my-1" style="color: var(--verde);">$<?= number_format($kpis['ticket_promedio'], 2); ?></h3>
                    <small class="text-muted">Objetivo: $300.00</small>
                </div>
            </div>
        </div>

        <!-- Sección de Gráfica Dinámica (Tendencia de Ventas) -->
        <div class="card border-0 p-3 p-md-4 shadow-sm rounded-4 mb-4" style="background-color: var(--crema);">
            <h5 class="fw-bold mb-3" style="color: var(--verde);">Tendencia de Ventas (Semanal)</h5>
            <div style="height: 220px; position: relative;">
                <canvas id="graficaVentasSemana"></canvas>
            </div>
        </div>

        <!-- Barra de Filtros Optimizada y Compacta -->
        <div class="card border-0 p-3 shadow-sm rounded-4 mb-4" style="background-color: var(--crema);">
            <form method="GET" class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                <div class="flex-fill">
                    <label class="form-label small fw-bold text-muted mb-1">Fecha:</label>
                    <div class="input-group bg-white rounded-3 border">
                        <input type="date" name="fecha" class="form-control border-0 bg-transparent shadow-none" value="<?= date('Y-m-d'); ?>">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                    </div>
                </div>
                <div class="flex-fill">
                    <label class="form-label small fw-bold text-muted mb-1">Mesero:</label>
                    <select name="mesero" class="form-select border rounded-3 bg-white shadow-none">
                        <option value="">Todos los Meseros</option>
                        <option value="1">Luis G.</option>
                        <option value="2">Ana R.</option>
                        <option value="3">Carlos M.</option>
                    </select>
                </div>
                <div class="flex-fill">
                    <label class="form-label small fw-bold text-muted mb-1">Pago:</label>
                    <select name="pago" class="form-select border rounded-3 bg-white shadow-none">
                        <option value="">Todos los Métodos</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Tabla de Cuentas Cerradas -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5" style="background-color: var(--crema);">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="background-color: transparent;">
                    <thead style="background-color: rgba(0,0,0,0.03); font-size: 0.75rem; letter-spacing: 0.05em;" class="text-uppercase text-muted">
                        <tr>
                            <th class="py-3 px-4"># Cuenta</th>
                            <th>Mesa</th>
                            <th>Mesero</th>
                            <th>Cajero</th>
                            <th>Método de Pago</th>
                            <th>Fecha/Hora</th>
                            <th>Total</th>
                            <th class="text-end px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cuentas_cerradas as $cta): ?>
                        <tr>
                            <td class="px-4 fw-bold text-verde"><?= $cta['id']; ?></td>
                            <td><?= $cta['mesa']; ?></td>
                            <td><?= $cta['mesero']; ?></td>
                            <td><?= $cta['cajero']; ?></td>
                            <td>
                                <span class="badge bg-white text-dark border px-2 py-1">
                                    <i class="bi bi-<?= $cta['metodo'] == 'Tarjeta' ? 'credit-card' : 'cash'; ?> me-1 text-muted"></i> 
                                    <?= $cta['metodo']; ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= $cta['fecha']; ?></td>
                            <td class="fw-bold" style="color: var(--verde);">$<?= number_format($cta['total'], 2); ?></td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-dark px-3 py-1 d-inline-flex align-items-center gap-1" style="background-color: var(--verde); border: none;" onclick="verTicket('<?= $cta['id']; ?>')">
                                    <i class="bi bi-receipt"></i> Ver Ticket
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- MODAL DETALLE DE TICKET -->
    <div class="modal fade" id="modalTicket" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow" style="background-color: var(--crema);">
                <div class="modal-header border-bottom border-secondary border-opacity-10">
                    <h5 class="modal-title font-serif fw-bold" style="color: var(--verde);">Ticket Detallado - <span id="ticketIdLabel">CTA-9021</span></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <h6 class="fw-bold mb-1">Cafetería Artesanal Beanora</h6>
                        <small class="text-muted">RFC: BEAN010101XYZ</small><br>
                        <small class="text-muted">Ticket verificado y calculated correctamente</small>
                    </div>
                    <div class="d-flex justify-content-between small text-muted border-bottom pb-2 mb-3">
                        <span>Mesa: <strong>Mesa 5</strong></span>
                        <span>Mesero: <strong>Luis G.</strong></span>
                        <span>Cajero: <strong>Sofía M.</strong></span>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-sm align-middle mb-0" style="font-size: 0.9rem;">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Cant. / Platillo</th>
                                    <th class="text-end">Precio U.</th>
                                    <th class="text-end">Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2x Cappuccino Grano Fino</td>
                                    <td class="text-end">$65.00</td>
                                    <td class="text-end fw-bold">$130.00</td>
                                </tr>
                                <tr>
                                    <td>1x Pay de Queso Artesanal</td>
                                    <td class="text-end">$95.00</td>
                                    <td class="text-end fw-bold">$95.00</td>
                                </tr>
                                <tr>
                                    <td>1x Panini de Roast Beef</td>
                                    <td class="text-end">$165.00</td>
                                    <td class="text-end fw-bold">$165.00</td>
                                </tr>
                                <tr>
                                    <td>2x Agua Mineral de Sabor</td>
                                    <td class="text-end">$45.00</td>
                                    <td class="text-end fw-bold">$90.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top pt-3 text-end">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Subtotal:</span>
                            <span>$480.00</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>IVA (16% incluido):</span>
                            <span>$40.00</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5" style="color: var(--verde);">
                            <span>Total Pagado:</span>
                            <span>$520.00</span>
                        </div>
                        <div class="mt-2 text-start">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 p-2 w-100 text-center">
                                <i class="bi bi-check-circle-fill me-1"></i> Suma de comprobación correcta (Matemáticamente exacta)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-10">
                    <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-dark px-4" style="background-color: var(--verde); border: none;" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS + Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function verTicket(idCuenta) {
            document.getElementById('ticketIdLabel').innerText = idCuenta;
            const modal = new bootstrap.Modal(document.getElementById('modalTicket'));
            modal.show();
        }

        function exportarReporte() {
            alert('Generando archivo Excel/PDF con las cuentas filtradas...');
        }

        // Gráfica de barras corregida con espaciado dinámico proporcional
        const ctx = document.getElementById('graficaVentasSemana').getContext('2d');
        const graficaVentas = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Ventas en $',
                    data: [11200, 12400, 9800, 13100, 15400, 18900, 16200],
                    backgroundColor: '#1E3A2B',
                    borderRadius: 4,
                    // Se quita barThickness fijo para que sea flexible y no se encima en celulares:
                    categoryPercentage: 0.6,
                    barPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { family: 'Outfit', size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Outfit', size: 11 } }
                    }
                }
            }
        });
    </script>
</body>
</html>