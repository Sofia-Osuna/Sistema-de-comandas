<?php
// Cuando exista auth.php, descomentar:
// require_once __DIR__ . '/../../includes/auth.php';
// requerirRol(['admin']);

$id        = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$esEdicion = $id > 0;

// Datos de ejemplo (después vendrán de modelos/Usuario.php con PDO)
$ejemplos = [
    1 => ['nombre' => 'Sofía Mendoza',    'correo' => 'admin@beanora.com',    'rol' => 'Admin',  'activo' => true],
    2 => ['nombre' => 'Carlos Morales',   'correo' => 'carlos.m@beanora.com', 'rol' => 'Mesero', 'activo' => true],
    3 => ['nombre' => 'Ana Ruiz',         'correo' => 'ana.r@beanora.com',    'rol' => 'Mesero', 'activo' => true],
    4 => ['nombre' => 'Chef Mateo Silva', 'correo' => 'cocina@beanora.com',   'rol' => 'Cocina', 'activo' => true],
    5 => ['nombre' => 'Lucía Fernández',  'correo' => 'lucia.f@beanora.com',  'rol' => 'Cajero', 'activo' => false],
];

// Valores por defecto para "Nuevo usuario"
$usuario = ['nombre' => '', 'correo' => '', 'rol' => 'Mesero', 'activo' => true];

if ($esEdicion && isset($ejemplos[$id])) {
    $usuario = $ejemplos[$id];
}

$roles  = ['Admin', 'Mesero', 'Cocina', 'Cajero'];
$titulo = $esEdicion ? 'Editar Usuario' : 'Nuevo Usuario';
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
</head>
<body>
<div class="d-flex flex-column flex-lg-row">

    <?php include '../../includes/sidebar.php'; ?>

    <main class="contenido-panel flex-grow-1 p-4 d-flex">

        <!-- Contenedor centrado -->
        <div class="form-centrado">

            <!-- Encabezado -->
            <a href="usuarios.php" class="volver d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left"></i> Volver a usuarios
            </a>
            <h1 class="titulo-pagina mb-1"><?= $titulo ?></h1>
            <p class="form-subtitulo mb-4">
                <?= $esEdicion ? 'Actualiza los datos y permisos de este usuario.' : 'Crea las credenciales y asigna un rol al personal.' ?>
            </p>

            <!-- Formulario (solo diseño: todavía no envía nada) -->
            <form id="formUsuario" class="form-card needs-validation" action="#" method="POST" novalidate>
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="form-cuerpo">

                    <!-- ===== Columna izquierda ===== -->
                    <div class="form-col">

                        <section class="form-seccion">
                            <h2 class="form-seccion-titulo">Datos personales</h2>
                            <div class="mb-3">
                                <label for="nombre" class="form-etiqueta">Nombre completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                       value="<?= htmlspecialchars($usuario['nombre']) ?>"
                                       placeholder="Ej. Ana Ruiz" autocomplete="off" required>
                                <div class="invalid-feedback">Escribe el nombre del usuario.</div>
                            </div>
                            <div>
                                <label for="correo" class="form-etiqueta">Correo electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo"
                                       value="<?= htmlspecialchars($usuario['correo']) ?>"
                                       placeholder="usuario@beanora.com" autocomplete="off" required>
                                <div class="invalid-feedback">Escribe un correo válido.</div>
                            </div>
                        </section>

                        <section class="form-seccion">
                            <h2 class="form-seccion-titulo">Rol</h2>
                            <div class="roles-grid">
                                <?php foreach ($roles as $r): $k = strtolower($r); ?>
                                    <input type="radio" class="btn-check" name="rol" id="rol-<?= $k ?>"
                                           value="<?= $r ?>" <?= $usuario['rol'] === $r ? 'checked' : '' ?> required>
                                    <label class="opcion-rol" for="rol-<?= $k ?>"><?= $r ?></label>
                                <?php endforeach; ?>
                            </div>
                        </section>

                    </div>

                    <!-- ===== Columna derecha ===== -->
                    <div class="form-col">

                        <section class="form-seccion">
                            <h2 class="form-seccion-titulo">Acceso</h2>

                            <div class="mb-3">
                                <label for="password" class="form-etiqueta">Contraseña</label>
                                <div class="campo-pass">
                                    <input type="password" class="form-control" id="password" name="password"
                                           minlength="8" autocomplete="new-password"
                                           placeholder="Mínimo 8 caracteres" <?= $esEdicion ? '' : 'required' ?>>
                                    <button type="button" class="btn-ojo" data-ver-pass="password" aria-label="Mostrar contraseña">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Usa al menos 8 caracteres.</div>
                                </div>
                                <?php if ($esEdicion): ?>
                                    <div class="form-ayuda">Déjala vacía para conservar la actual.</div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="confirmar" class="form-etiqueta">Confirmar contraseña</label>
                                <div class="campo-pass">
                                    <input type="password" class="form-control" id="confirmar" name="confirmar"
                                           autocomplete="new-password" placeholder="Repite la contraseña"
                                           <?= $esEdicion ? '' : 'required' ?>>
                                    <button type="button" class="btn-ojo" data-ver-pass="confirmar" aria-label="Mostrar contraseña">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo"
                                       value="1" <?= $usuario['activo'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="activo">Cuenta activa</label>
                            </div>
                        </section>

                    </div>
                </div>

                <!-- Acciones -->
                <div class="form-acciones">
                    <a href="usuarios.php" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-principal d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-lg"></i> <?= $esEdicion ? 'Guardar cambios' : 'Crear usuario' ?>
                    </button>
                </div>
            </form>

        </div>
    </main>
</div>

<script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const form = document.getElementById('formUsuario');
        const pass = document.getElementById('password');
        const conf = document.getElementById('confirmar');

        // Mostrar / ocultar contraseña (funciona para los dos campos)
        document.querySelectorAll('[data-ver-pass]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const campo = document.getElementById(btn.dataset.verPass);
                const oculto = campo.type === 'password';
                campo.type = oculto ? 'text' : 'password';
                btn.querySelector('i').className = oculto ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });

        // Las contraseñas deben coincidir (en edición solo si escribe una nueva)
        function validarCoincidencia() {
            conf.required = pass.required || pass.value !== '';
            conf.setCustomValidity(conf.value !== pass.value ? 'No coinciden' : '');
        }
        pass.addEventListener('input', validarCoincidencia);
        conf.addEventListener('input', validarCoincidencia);

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Solo diseño: no envía nada todavía
            validarCoincidencia();
            form.classList.add('was-validated');
        });
    })();
</script>
</body>
</html>