<?php
// Cuando exista auth.php, descomentar. Cualquier rol logueado puede entrar (no solo admin):
// require_once __DIR__ . '/../../includes/auth.php';
// requerirLogin();
// $idUsuario = $_SESSION['pk_usuario'];   // OJO: el id sale de la SESIÓN, nunca de $_GET

// Datos de ejemplo del usuario con sesión iniciada
// (después vendrán de modelos/Usuario.php con PDO; nombres de campo = tabla `usuarios`).
$usuario = [
    'nombres'          => 'Sofía',
    'apellido_paterno' => 'Mendoza',
    'apellido_materno' => 'Ruiz',
    'correo'           => 'admin@beanora.com',
    'rol'              => 'Admin',
    'ultimo_acceso'    => '17/11/2026 22:10',
    'google_vinculado' => false,
];

// usuarios.password_hash es NULL cuando la persona solo entra con Google.
// En ese caso no tiene "contraseña actual": solo puede crear una nueva.
$tienePassword = true;

// Primera letra de nombre y apellido (preg_match con /u funciona con acentos y no requiere mbstring)
preg_match('/^./u', $usuario['nombres'], $n1);
preg_match('/^./u', $usuario['apellido_paterno'], $a1);
$iniciales = ($n1[0] ?? '') . ($a1[0] ?? '');   // el CSS la pone en mayúsculas
$nombreCompleto = trim($usuario['nombres'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno']);
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi perfil | Beanora</title>

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

        <div class="form-centrado form-centrado-xl">

            <!-- Encabezado -->
            <a href="dashboard.php" class="volver d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left"></i> Volver al panel
            </a>
            <h1 class="titulo-pagina mb-1">Mi perfil</h1>
            <p class="form-subtitulo mb-4">Actualiza tus datos personales y cambia tu contraseña de acceso.</p>

            <div class="perfil-grid">

                <!-- ============ TARJETA 1: DATOS PERSONALES ============ -->
                <form id="formPerfil" class="form-card needs-validation" action="#" method="POST" novalidate>
                    <input type="hidden" name="accion" value="datos">

                    <div class="perfil-cabecera">
                        <div class="perfil-avatar" aria-hidden="true"><?= $e($iniciales) ?></div>
                        <div class="min-w-0">
                            <p class="perfil-nombre"><?= $e($nombreCompleto) ?></p>
                            <p class="perfil-correo"><?= $e($usuario['correo']) ?></p>
                        </div>
                    </div>

                    <p class="form-aviso" id="avisoDatos" role="status">
                        <i class="bi bi-check-circle-fill"></i>
                        Datos válidos. (Modo diseño: todavía no se guardan.)
                    </p>

                    <section class="form-seccion">
                        <h2 class="form-seccion-titulo">Datos personales</h2>

                        <div class="mb-3">
                            <label for="nombres" class="form-etiqueta">Nombre(s)</label>
                            <input type="text" class="form-control" id="nombres" name="nombres"
                                   value="<?= $e($usuario['nombres']) ?>" maxlength="50"
                                   placeholder="Ej. Sofía" autocomplete="given-name" required>
                            <div class="invalid-feedback">Escribe tu nombre.</div>
                        </div>

                        <div class="form-fila-2 mb-3">
                            <div>
                                <label for="apellido_paterno" class="form-etiqueta">Apellido paterno</label>
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                                       value="<?= $e($usuario['apellido_paterno']) ?>" maxlength="40"
                                       autocomplete="family-name" required>
                                <div class="invalid-feedback">Escribe tu apellido paterno.</div>
                            </div>
                            <div>
                                <label for="apellido_materno" class="form-etiqueta">Apellido materno <span class="text-lowercase fw-normal text-muted">(opcional)</span></label>
                                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                                       value="<?= $e($usuario['apellido_materno']) ?>" maxlength="40">
                            </div>
                        </div>

                        <div>
                            <label for="correo" class="form-etiqueta">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo"
                                   value="<?= $e($usuario['correo']) ?>" maxlength="100"
                                   autocomplete="email" required>
                            <div class="invalid-feedback">Escribe un correo válido.</div>
                            <div class="form-ayuda">Es el correo con el que inicias sesión. No puede repetirse con otro usuario.</div>
                        </div>
                    </section>

                    <section class="form-seccion">
                        <h2 class="form-seccion-titulo">Información de la cuenta</h2>
                        <div class="datos-fijos">
                            <div class="dato-fijo"><small>Rol</small><strong><?= $e($usuario['rol']) ?></strong></div>
                            <div class="dato-fijo"><small>Último acceso</small><strong><?= $e($usuario['ultimo_acceso']) ?></strong></div>
                            <div class="dato-fijo"><small>Google</small><strong><?= $usuario['google_vinculado'] ? 'Vinculado' : 'No vinculado' ?></strong></div>
                        </div>
                        <div class="form-ayuda mt-2">El rol lo asigna un administrador desde Usuarios y Roles.</div>
                    </section>

                    <div class="form-acciones">
                        <button type="reset" class="btn btn-light" id="btnRestablecer">Restablecer</button>
                        <button type="submit" class="btn btn-principal d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-check-lg"></i> Guardar datos
                        </button>
                    </div>
                </form>

                <!-- ============ TARJETA 2: CAMBIAR CONTRASEÑA ============ -->
                <form id="formClave" class="form-card needs-validation" action="#" method="POST" novalidate>
                    <input type="hidden" name="accion" value="clave">

                    <p class="form-aviso" id="avisoClave" role="status">
                        <i class="bi bi-check-circle-fill"></i>
                        Contraseña válida. (Modo diseño: todavía no se guarda.)
                    </p>

                    <section class="form-seccion">
                        <h2 class="form-seccion-titulo"><?= $tienePassword ? 'Cambiar contraseña' : 'Crear contraseña' ?></h2>

                        <?php if (!$tienePassword): ?>
                            <p class="form-ayuda mb-3">Tu cuenta entra con Google. Crea una contraseña si también quieres iniciar sesión con tu correo.</p>
                        <?php else: ?>
                            <div class="mb-3">
                                <label for="actual" class="form-etiqueta">Contraseña actual</label>
                                <div class="campo-pass">
                                    <input type="password" class="form-control" id="actual" name="actual"
                                           autocomplete="current-password" placeholder="Tu contraseña de ahora" required>
                                    <button type="button" class="btn-ojo" data-ver-pass="actual" aria-label="Mostrar contraseña">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Escribe tu contraseña actual.</div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nueva" class="form-etiqueta">Nueva contraseña</label>
                            <div class="campo-pass">
                                <input type="password" class="form-control" id="nueva" name="nueva"
                                       minlength="8" maxlength="72" autocomplete="new-password"
                                       placeholder="Mínimo 8 caracteres" required>
                                <button type="button" class="btn-ojo" data-ver-pass="nueva" aria-label="Mostrar contraseña">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback" id="errNueva">Usa al menos 8 caracteres.</div>
                            </div>
                            <div class="fuerza" id="fuerza" data-nivel="0" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
                            <div class="fuerza-texto" id="fuerzaTexto">Escribe una contraseña para ver su seguridad.</div>
                        </div>

                        <div class="mb-1">
                            <label for="confirmar" class="form-etiqueta">Confirmar nueva contraseña</label>
                            <div class="campo-pass">
                                <input type="password" class="form-control" id="confirmar" name="confirmar"
                                       maxlength="72" autocomplete="new-password" placeholder="Repite la nueva contraseña" required>
                                <button type="button" class="btn-ojo" data-ver-pass="confirmar" aria-label="Mostrar contraseña">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                            </div>
                        </div>

                        <ul class="requisitos" id="requisitos">
                            <li data-req="largo"><i class="bi bi-circle"></i> Al menos 8 caracteres</li>
                            <li data-req="mezcla"><i class="bi bi-circle"></i> Letras y números (recomendado: también mayúsculas y símbolos)</li>
                            <?php if ($tienePassword): ?>
                                <li data-req="distinta"><i class="bi bi-circle"></i> Distinta a tu contraseña actual</li>
                            <?php endif; ?>
                        </ul>
                    </section>

                    <div class="form-acciones">
                        <button type="submit" class="btn btn-principal d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-shield-lock"></i> <?= $tienePassword ? 'Cambiar contraseña' : 'Crear contraseña' ?>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>

<script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        'use strict';
        const $ = id => document.getElementById(id);

        // ----- Mostrar / ocultar contraseña (los tres campos) -----
        document.querySelectorAll('[data-ver-pass]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const campo = $(btn.dataset.verPass);
                const oculto = campo.type === 'password';
                campo.type = oculto ? 'text' : 'password';
                btn.querySelector('i').className = oculto ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });

        // ================= FORMULARIO DE DATOS =================
        const formPerfil = $('formPerfil');
        const textosObligatorios = ['nombres', 'apellido_paterno'].map($);

        formPerfil.addEventListener('submit', function (e) {
            e.preventDefault(); // Solo diseño: no envía nada todavía
            textosObligatorios.forEach(function (c) {
                c.value = c.value.trim();
                c.setCustomValidity(c.value === '' ? 'Vacío' : '');
            });
            $('apellido_materno').value = $('apellido_materno').value.trim();
            $('correo').value = $('correo').value.trim();
            formPerfil.classList.add('was-validated');
            $('avisoDatos').classList.toggle('visible', formPerfil.checkValidity());
        });
        $('btnRestablecer').addEventListener('click', function () {
            formPerfil.classList.remove('was-validated');
            $('avisoDatos').classList.remove('visible');
        });

        // ================= FORMULARIO DE CONTRASEÑA =================
        const formClave = $('formClave');
        const actual    = $('actual');       // null si la cuenta solo usa Google
        const nueva     = $('nueva');
        const confirmar = $('confirmar');
        const fuerza    = $('fuerza');
        const reqs      = {};
        document.querySelectorAll('#requisitos li').forEach(li => reqs[li.dataset.req] = li);

        function marcar(clave, cumple) {
            const li = reqs[clave];
            if (!li) return;
            li.classList.toggle('cumple', cumple);
            li.querySelector('i').className = cumple ? 'bi bi-check-circle-fill' : 'bi bi-circle';
        }

        // Puntaje 0-4 (solo orientativo para la persona; la regla real la valida el servidor)
        function puntaje(p) {
            if (!p) return 0;
            let s = 0;
            if (p.length >= 8) s++;
            if (p.length >= 12) s++;
            if (/[a-z]/.test(p) && /[A-Z]/.test(p)) s++;
            if (/\d/.test(p) && /[^A-Za-z0-9]/.test(p)) s++;
            if (p.length < 8) s = Math.min(s, 1);
            return Math.max(p ? 1 : 0, Math.min(4, s));
        }
        const ETIQUETAS = ['Escribe una contraseña para ver su seguridad.', 'Débil', 'Regular', 'Buena', 'Muy segura'];

        function revisar() {
            const p = nueva.value;
            const nivel = puntaje(p);
            fuerza.dataset.nivel = nivel;
            $('fuerzaTexto').textContent = nivel === 0 ? ETIQUETAS[0] : 'Seguridad: ' + ETIQUETAS[nivel];

            marcar('largo', p.length >= 8);
            marcar('mezcla', /[A-Za-z]/.test(p) && /\d/.test(p));

            // Validaciones del campo "nueva"
            let msg = '';
            if (p.length > 0 && p.length < 8) msg = 'Usa al menos 8 caracteres.';
            else if (p !== '' && !(/[A-Za-z]/.test(p) && /\d/.test(p))) msg = 'Combina letras y números.';
            if (actual) {
                const distinta = p !== '' && p !== actual.value;
                marcar('distinta', distinta);
                if (!msg && p !== '' && p === actual.value) msg = 'La nueva contraseña debe ser distinta a la actual.';
            }
            $('errNueva').textContent = msg || 'Usa al menos 8 caracteres.';
            nueva.setCustomValidity(msg);

            confirmar.setCustomValidity(confirmar.value !== p ? 'No coinciden' : '');
        }
        [nueva, confirmar].forEach(c => c.addEventListener('input', revisar));
        if (actual) actual.addEventListener('input', revisar);
        revisar();

        formClave.addEventListener('submit', function (e) {
            e.preventDefault(); // Solo diseño: no envía nada todavía
            revisar();
            formClave.classList.add('was-validated');
            const ok = formClave.checkValidity();
            $('avisoClave').classList.toggle('visible', ok);
            if (ok) { // Por seguridad, se vacían los campos tras un cambio exitoso
                formClave.reset();
                formClave.classList.remove('was-validated');
                revisar();
            }
        });
    })();
</script>
</body>
</html>
