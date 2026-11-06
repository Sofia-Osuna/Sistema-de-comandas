<?php
// Cuando exista auth.php, descomentar:
// require_once __DIR__ . '/../../includes/auth.php';
// requerirRol(['admin']);

// Datos de ejemplo (después vendrán de modelos/Usuario.php con PDO)
$usuarios = [
    ['nombre' => 'Sofía Mendoza',    'correo' => 'admin@beanora.com',    'rol' => 'Admin',  'estado' => 'activo',    'acceso' => 'Hace 2 min'],
    ['nombre' => 'Carlos Morales',   'correo' => 'carlos.m@beanora.com', 'rol' => 'Mesero', 'estado' => 'activo',    'acceso' => 'Hace 15 min'],
    ['nombre' => 'Ana Ruiz',         'correo' => 'ana.r@beanora.com',    'rol' => 'Mesero', 'estado' => 'activo',    'acceso' => 'Hace 1 hora'],
    ['nombre' => 'Chef Mateo Silva', 'correo' => 'cocina@beanora.com',   'rol' => 'Cocina', 'estado' => 'activo',    'acceso' => 'En línea'],
    ['nombre' => 'Lucía Fernández',  'correo' => 'lucia.f@beanora.com',  'rol' => 'Cajero', 'estado' => 'bloqueado', 'acceso' => 'Ayer 19:30'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios y Roles | Beanora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="d-flex flex-column flex-lg-row">

    <?php include '../../includes/sidebar.php'; ?>

    <main class="contenido-panel flex-grow-1 p-4 p-lg-5">

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <h1 class="titulo-pagina mb-1">Usuarios y Roles</h1>
                <p class="fs-5 text-secondary mb-0">Administración de credenciales y permisos del personal.</p>
            </div>
            <button type="button" class="btn btn-principal btn-lg rounded-3 d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
            </button>
        </div>

        <!-- ================= TARJETAS (celular) ================= -->
        <div class="d-md-none">
            <?php foreach ($usuarios as $u): ?>
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="avatar-inicial">
                                <?= htmlspecialchars(mb_strtoupper(mb_substr($u['nombre'], 0, 1))) ?>
                            </span>
                            <div class="overflow-hidden">
                                <div class="fw-semibold fs-5 text-truncate"><?= htmlspecialchars($u['nombre']) ?></div>
                                <div class="text-secondary text-truncate"><?= htmlspecialchars($u['correo']) ?></div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <span class="badge rounded-pill rol-<?= strtolower($u['rol']) ?>">
                                <?= htmlspecialchars($u['rol']) ?>
                            </span>
                            <?php if ($u['estado'] === 'activo'): ?>
                                <span class="badge estado-activo">ACTIVO</span>
                            <?php else: ?>
                                <span class="badge estado-bloqueado"><i class="bi bi-lock-fill"></i> BLOQUEADO</span>
                            <?php endif; ?>
                            <span class="text-muted small ms-auto">
                                <i class="bi bi-clock"></i> <?= htmlspecialchars($u['acceso']) ?>
                            </span>
                        </div>

                        <div class="d-flex flex-wrap gap-2 border-top pt-3">
                            <?php if ($u['estado'] === 'bloqueado'): ?>
                                <button type="button" class="btn btn-warning flex-fill">
                                    <i class="bi bi-key-fill"></i> Desbloquear
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-light flex-fill">Desactivar</button>
                            <button type="button" class="btn btn-light" title="Restablecer contraseña">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ================= TABLA (tableta y escritorio) ================= -->
        <div class="d-none d-md-block bg-white rounded-4 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tabla-usuarios">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="d-none d-lg-table-cell">Último acceso</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="avatar-inicial">
                                        <?= htmlspecialchars(mb_strtoupper(mb_substr($u['nombre'], 0, 1))) ?>
                                    </span>
                                    <span class="fw-semibold"><?= htmlspecialchars($u['nombre']) ?></span>
                                </div>
                            </td>
                            <td class="text-secondary"><?= htmlspecialchars($u['correo']) ?></td>
                            <td>
                                <span class="badge rounded-pill rol-<?= strtolower($u['rol']) ?>">
                                    <?= htmlspecialchars($u['rol']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($u['estado'] === 'activo'): ?>
                                    <span class="badge estado-activo">ACTIVO</span>
                                <?php else: ?>
                                    <span class="badge estado-bloqueado"><i class="bi bi-lock-fill"></i> BLOQUEADO</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted d-none d-lg-table-cell"><?= htmlspecialchars($u['acceso']) ?></td>
                            <td>
                                <div class="d-flex justify-content-end align-items-center flex-wrap gap-2">
                                    <?php if ($u['estado'] === 'bloqueado'): ?>
                                        <button type="button" class="btn btn-warning">
                                            <i class="bi bi-key-fill"></i> Desbloquear
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-light">Desactivar</button>
                                    <button type="button" class="btn btn-link text-secondary" title="Restablecer contraseña">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>