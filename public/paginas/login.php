<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión | Beanora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<!-- p-0 quita la línea blanca. overflow-hidden evita el scroll global -->
<div class="container-fluid p-0 overflow-hidden">
    <!-- vh-100 fuerza exactamente el alto de la pantalla -->
    <div class="row g-0 vh-100">

        <!-- ========== PANEL IZQUIERDO ========== -->
        <div class="col-lg-6 d-none d-lg-flex login-hero">

            <div class="hero-top">
                <div class="marca">
                    <span class="marca-icono"><i class="bi bi-cup-hot"></i></span>
                    <span class="marca-nombre">Beanora</span>
                </div>
                <span class="pill-hero"><span class="punto"></span> Tostaduría artesanal</span>
            </div>

            <div class="hero-bottom">
                <div class="hero-etiqueta">
                    <span class="linea"></span> Café de especialidad
                </div>
                <h1 class="hero-titulo">
                    El Buen Café Trae<br><em>Mejores Días</em>
                </h1>
                <p class="hero-texto">
                    En Beanora servimos más que café: servimos momentos,
                    tazas recién tostadas y un espacio acogedor para ti.
                </p>
                <div class="hero-detalles">
                    <span><i class="bi bi-check2"></i> Granos Recién Tostados</span>
                    <span><i class="bi bi-check2"></i> Ambiente Artesanal</span>
                </div>
            </div>
        </div>

        <!-- ========== PANEL DERECHO ========== -->
        <div class="col-12 col-lg-6 login-lado">
            <div class="login-envoltura">

                <div class="login-card">
                    <i class="bi bi-globe-americas icono-globo"></i>

                    <span class="pill-portal">Portal de miembros</span>
                    <h2 class="login-titulo">Bienvenido de Nuevo</h2>
                    <p class="login-subtitulo">Inicia sesión para disfrutar de tu café favorito</p>

                    <form action="../acciones/login_validar.php" method="POST">
                        
                        <label for="correo" class="etiqueta">Correo electrónico</label>
                        <div class="campo mb-3">
                            <i class="bi bi-envelope"></i>
                            <input type="email" class="form-control" id="correo" name="correo"
                                   placeholder="Ingresa tu correo" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="etiqueta">Contraseña</label>
                            <a href="#" class="enlace-acento">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div class="campo mb-2">
                            <i class="bi bi-lock"></i>
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Ingresa tu contraseña" required>
                            <button type="button" class="btn-ojo" id="verPassword" aria-label="Mostrar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="recordar" name="recordar">
                            <label class="form-check-label" for="recordar">Recordar este dispositivo</label>
                        </div>

                        <button type="submit" class="btn-login">
                            Iniciar Sesión <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>

                    <div class="divisor my-3">O continúa con</div>

                    <button type="button" class="btn-google">
                        <svg width="20" height="20" viewBox="0 0 48 48" aria-hidden="true">
                            <path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9 3.6l6.7-6.7C35.6 2.4 30.2 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.8 6.1C12.3 13.6 17.7 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17.5z"/>
                            <path fill="#FBBC05" d="M10.4 28.7c-.5-1.5-.8-3-.8-4.7s.3-3.2.8-4.7l-7.8-6.1C.9 16.4 0 20.100 0 24s.9 7.600 2.600 10.800l7.800-6.100z"/>
                            <path fill="#34A853" d="M24 48c6.200 0 11.500-2 15.300-5.600l-7.500-5.800c-2.100 1.400-4.700 2.300-7.800 2.300-6.300 0-11.700-4.100-13.600-9.800l-7.800 6.100C6.500 42.600 14.600 48 24 48z"/>
                        </svg>
                        Continuar con Google
                    </button>

                    <p class="registro">¿No tienes una cuenta? <a href="#" class="enlace-acento">Regístrate</a></p>
                </div>

                <p class="pie-login">
                    © 2025 Beanora Cafetería Artesanal <span>•</span>
                    <a href="#">Privacidad</a> <span>•</span> <a href="#">Términos</a>
                </p>

            </div>
        </div>

    </div>
</div>

<script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('verPassword').addEventListener('click', function () {
        const campo = document.getElementById('password');
        const icono = this.querySelector('i');
        const oculto = campo.type === 'password';
        campo.type = oculto ? 'text' : 'password';
        icono.className = oculto ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
</script>
</body>
</html>