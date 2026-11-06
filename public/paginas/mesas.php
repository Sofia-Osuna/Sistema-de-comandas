<?php
/**
 * Beanora - Tablero de Mesas
 * Ubicación: public/paginas/mesas.php
 *
 * Los datos son de ejemplo (los de la maqueta). Cuando tengas el modelo de mesas,
 * reemplaza $mesas por la consulta a la base de datos y haz que el cambio de estado
 * y el alta de mesa llamen a public/acciones/*.php (fetch/POST) en lugar de solo
 * modificar el arreglo en JavaScript.
 */

$paginaActual = 'mesas'; // por si el sidebar marca el ítem activo con esta variable

/* 'minutos' = tiempo de ocupación. 'estado': libre | ocupada | cuenta */
$mesas = [
    ['id' => 1, 'numero' => 1, 'estado' => 'libre',   'mesero' => 'Carlos M.',    'minutos' => 0],
    ['id' => 2, 'numero' => 2, 'estado' => 'ocupada', 'mesero' => 'Ana R.',       'minutos' => 18],
    ['id' => 3, 'numero' => 3, 'estado' => 'libre',   'mesero' => 'Sin asignar',  'minutos' => 0],
    ['id' => 4, 'numero' => 4, 'estado' => 'ocupada', 'mesero' => 'Carlos M.',    'minutos' => 42],
    ['id' => 5, 'numero' => 5, 'estado' => 'cuenta',  'mesero' => 'Luis G.',      'minutos' => 55],
    ['id' => 6, 'numero' => 6, 'estado' => 'ocupada', 'mesero' => 'Ana R.',       'minutos' => 10],
    ['id' => 7, 'numero' => 7, 'estado' => 'libre',   'mesero' => 'Sin asignar',  'minutos' => 0],
    ['id' => 8, 'numero' => 8, 'estado' => 'cuenta',  'mesero' => 'Luis G.',      'minutos' => 35],
];

$meseros = ['Sin asignar', 'Carlos Morales', 'Ana Ruiz', 'Luis Gómez'];

$umbralAlerta = 40; // minutos: una mesa ocupada con más tiempo se marca en alerta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beanora - Tablero de Mesas</title>

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
                <h1 class="bn-titulo">Tablero de Mesas</h1>
                <p class="bn-subtitulo">Control de distribución en salón y tiempos de ocupación.</p>
            </div>
            <button type="button" class="bn-btn bn-btn-verde align-self-start" data-bs-toggle="modal" data-bs-target="#modalNuevaMesa">
                <i class="bi bi-plus-lg"></i> Agregar Mesa
            </button>
        </div>

        <!-- ================= Leyenda y filtros ================= -->
        <div class="bn-card bn-filtros mb-4">
            <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3">

                <!-- Leyenda (HU-05) -->
                <div class="d-flex flex-wrap align-items-center gap-3 small fw-medium">
                    <span class="bn-leyenda-titulo">Leyenda:</span>
                    <span class="d-inline-flex align-items-center gap-2"><span class="bn-dot bn-dot-libre"></span> Libre</span>
                    <span class="d-inline-flex align-items-center gap-2"><span class="bn-dot bn-dot-ocupada"></span> Ocupada</span>
                    <span class="d-inline-flex align-items-center gap-2"><span class="bn-dot bn-dot-cuenta"></span> Esperando Cuenta</span>
                </div>

                <!-- Filtros por estado -->
                <div class="d-flex flex-wrap align-items-center gap-2" id="filtrosMesas">
                    <button type="button" class="bn-chip activo" data-filtro="todas">Todas <span class="n" data-cuenta="todas">0</span></button>
                    <button type="button" class="bn-chip" data-filtro="libre">Libres <span class="n" data-cuenta="libre">0</span></button>
                    <button type="button" class="bn-chip" data-filtro="ocupada">Ocupadas <span class="n" data-cuenta="ocupada">0</span></button>
                    <button type="button" class="bn-chip" data-filtro="cuenta">Esperando Cuenta <span class="n" data-cuenta="cuenta">0</span></button>
                </div>
            </div>
        </div>

        <!-- ================= Grid de mesas (HU-05, HU-07) ================= -->
        <div id="gridMesas" class="row g-3 g-xl-4"></div>

        <!-- Estado vacío -->
        <div id="mesasVacio" class="bn-card bn-vacio d-none">
            <i class="bi bi-grid-3x3-gap"></i>
            <h4>No hay mesas en este estado</h4>
            <p class="mb-0">Prueba con otro filtro o agrega una mesa nueva.</p>
        </div>

    </main>
</div>

<!-- ================= Modal: Agregar mesa (HU-06) ================= -->
<div class="modal fade bn-modal" id="modalNuevaMesa" tabindex="-1" aria-labelledby="modalNuevaMesaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalNuevaMesaLabel">Agregar Mesa</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formNuevaMesa" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mesaNumero" class="bn-label">Número / Identificador de Mesa</label>
                        <input type="number" id="mesaNumero" class="bn-input w-100" min="1" max="50" required placeholder="Ej. 13">
                        <div class="invalid-feedback" id="mesaNumeroError">Ingresa un número entre 1 y 50.</div>
                    </div>

                    <div>
                        <label for="mesaMesero" class="bn-label">Mesero Asignado Inicial</label>
                        <select id="mesaMesero" class="bn-select form-select">
                            <?php foreach ($meseros as $m): ?>
                                <option value="<?= htmlspecialchars($m) ?>"><?= htmlspecialchars($m) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="bn-btn bn-btn-suave" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="bn-btn bn-btn-verde">Agregar</button>
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
    /* ---------- Estado ---------- */
    const UMBRAL_ALERTA = <?= (int)$umbralAlerta ?>;
    let mesas = <?= json_encode($mesas, JSON_UNESCAPED_UNICODE) ?>;
    let filtroActual = 'todas';

    const ESTADOS = {
        libre:   { etiqueta: 'Libre',          clase: 'libre'   },
        ocupada: { etiqueta: 'Ocupada',        clase: 'ocupada' },
        cuenta:  { etiqueta: 'Esperando Cta.', clase: 'cuenta'  }
    };

    const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    // "Carlos Morales" -> "Carlos M."; "Sin asignar" se deja igual
    const abreviar = n => n === 'Sin asignar' ? n : n.replace(/^(\S+) (\S)\S*$/, '$1 $2.');

    /* ---------- Render ---------- */
    function renderMesas() {
        const grid  = document.getElementById('gridMesas');
        const vacio = document.getElementById('mesasVacio');
        const lista = mesas
            .filter(m => filtroActual === 'todas' || m.estado === filtroActual)
            .sort((a, b) => a.numero - b.numero);

        grid.innerHTML = lista.map(m => {
            const e = ESTADOS[m.estado];
            const enAlerta = m.estado === 'ocupada' && m.minutos >= UMBRAL_ALERTA;
            return `
            <div class="col-12 col-sm-6 col-lg-4 col-xxl-3">
                <article class="bn-card bn-mesa bn-mesa-${e.clase} ${enAlerta ? 'en-alerta' : ''} h-100 p-3 p-xl-4 d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bn-mesa-num">${m.numero}</span>
                            <h3 class="bn-mesa-titulo">Mesa #${m.numero}</h3>
                        </div>
                        <span class="bn-estado bn-estado-mesa-${e.clase}">${e.etiqueta}</span>
                    </div>

                    <div class="small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="bn-mesa-dato"><i class="bi bi-person-badge"></i> Mesero:</span>
                            <span class="fw-medium">${esc(m.mesero)}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="bn-mesa-dato"><i class="bi bi-clock"></i> Tiempo:</span>
                            <span class="bn-espera ${enAlerta ? 'alerta' : ''}">${m.minutos} min${enAlerta ? ' <i class="bi bi-exclamation-triangle-fill"></i>' : ''}</span>
                        </div>
                    </div>

                    <div class="mt-auto pt-3 d-flex align-items-center gap-2 bn-mesa-pie">
                        <button type="button" class="bn-accion flex-grow-1 justify-content-center" data-cambiar="${m.id}">
                            <i class="bi bi-arrow-repeat"></i> Cambiar Estado
                        </button>
                        <a href="pedidos.php?mesa=${m.numero}" class="bn-accion bn-accion-solido" title="Ver comanda" aria-label="Ver comanda de la mesa ${m.numero}">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </article>
            </div>`;
        }).join('');

        vacio.classList.toggle('d-none', lista.length > 0);
        actualizarContadores();
    }

    function actualizarContadores() {
        const cuentas = { todas: mesas.length, libre: 0, ocupada: 0, cuenta: 0 };
        mesas.forEach(m => cuentas[m.estado]++);
        document.querySelectorAll('[data-cuenta]').forEach(el => el.textContent = cuentas[el.dataset.cuenta]);
    }

    /* ---------- Filtros ---------- */
    document.getElementById('filtrosMesas').addEventListener('click', e => {
        const btn = e.target.closest('[data-filtro]');
        if (!btn) return;
        filtroActual = btn.dataset.filtro;
        document.querySelectorAll('#filtrosMesas .bn-chip').forEach(b => b.classList.toggle('activo', b === btn));
        renderMesas();
    });

    /* ---------- Cambiar estado (libre -> ocupada -> cuenta -> libre) ---------- */
    document.getElementById('gridMesas').addEventListener('click', e => {
        const btn = e.target.closest('[data-cambiar]');
        if (!btn) return;
        const mesa = mesas.find(m => m.id === Number(btn.dataset.cambiar));
        if (!mesa) return;

        if (mesa.estado === 'libre') {
            mesa.estado = 'ocupada'; mesa.minutos = 2;
            if (mesa.mesero === 'Sin asignar') mesa.mesero = 'Carlos M.';
        } else if (mesa.estado === 'ocupada') {
            mesa.estado = 'cuenta';
        } else {
            mesa.estado = 'libre'; mesa.minutos = 0; mesa.mesero = 'Sin asignar';
        }
        renderMesas();
        avisar(`Mesa #${mesa.numero} actualizada a ${ESTADOS[mesa.estado].etiqueta.toUpperCase()}`);
    });

    /* ---------- Agregar mesa (HU-06) ---------- */
    const modalEl = document.getElementById('modalNuevaMesa');
    const formEl  = document.getElementById('formNuevaMesa');
    const numEl   = document.getElementById('mesaNumero');
    const numErr  = document.getElementById('mesaNumeroError');

    formEl.addEventListener('submit', e => {
        e.preventDefault();
        const num = parseInt(numEl.value, 10);

        let error = '';
        if (!Number.isInteger(num) || num < 1 || num > 50) error = 'Ingresa un número entre 1 y 50.';
        else if (mesas.some(m => m.numero === num)) error = `La mesa #${num} ya existe.`;

        numEl.classList.toggle('is-invalid', !!error);
        if (error) { numErr.textContent = error; return; }

        mesas.push({
            id: Date.now(),
            numero: num,
            estado: 'libre',
            mesero: abreviar(document.getElementById('mesaMesero').value),
            minutos: 0
        });

        bootstrap.Modal.getInstance(modalEl).hide();
        renderMesas();
        avisar(`Mesa #${num} registrada con éxito.`);
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        formEl.reset();
        numEl.classList.remove('is-invalid');
    });
    modalEl.addEventListener('shown.bs.modal', () => numEl.focus());

    /* ---------- Avisos ---------- */
    function avisar(texto) {
        const t = document.createElement('div');
        t.className = 'bn-toast';
        t.innerHTML = `<i class="bi bi-info-circle-fill"></i><span>${esc(texto)}</span>`;
        document.getElementById('toasts').appendChild(t);
        setTimeout(() => {
            t.classList.add('saliendo');
            setTimeout(() => t.remove(), 300);
        }, 3000);
    }

    renderMesas();
</script>
</body>
</html>
