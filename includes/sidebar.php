<?php
// Obtener el nombre del archivo actual para activar el enlace correspondiente en el Sidebar
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<!-- 1. NAVBAR SUPERIOR MÓVIL (Solo visible en pantallas medianas y pequeñas: d-lg-none) -->
<nav class="navbar navbar-dark w-100 px-3 d-lg-none" style="background-color: var(--verde); z-index: 1030;">
    <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
        <a class="navbar-brand d-flex align-items-center gap-2 m-0" href="dashboard.php">
            <div class="marca-icono shadow-sm" style="width: 36px; height: 36px; font-size: 1rem;">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <span class="font-serif fw-bold text-white" style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem;">Beanora</span>
        </a>
        <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuResponsive" aria-controls="menuResponsive">
            <i class="bi bi-list text-white fs-1"></i>
        </button>
    </div>
</nav>

<!-- 2. OFFCANVAS MÓVIL (Menú deslizante cuando se presiona el botón en celular/tablet) -->
<div class="offcanvas offcanvas-start text-bg-dark border-0" tabindex="-1" id="menuResponsive" aria-labelledby="menuResponsiveLabel" style="background-color: var(--verde) !important; width: 280px;">
    <div class="offcanvas-header border-bottom border-secondary border-opacity-25 px-4 pt-4">
        <div class="sidebar-header d-flex align-items-center gap-3">
            <div class="marca-icono shadow-sm">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <div>
                <h1 class="marca-nombre m-0 lh-1 fs-3">Beanora</h1>
                <span class="subtitulo-marca" style="font-size: 0.6rem;">TOSTADURÍA ARTESANAL</span>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between px-3 py-4">
        <!-- Copia del contenido de navegación para móviles -->
        <div>
            <div class="sidebar-status px-3 py-2 my-2 d-flex justify-content-between align-items-center rounded bg-black bg-opacity-10">
                <span class="d-flex align-items-center gap-2 status-texto small">
                    <span class="punto-verde"></span> Modo Administrador
                </span>
            </div>
            <nav class="sidebar-nav mt-3">
                <ul class="nav nav-pills flex-column gap-2">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'dashboard.php' || $pagina_actual == 'index.php') ? 'active' : ''; ?>">
                            <i class="bi bi-pie-chart-fill icon-menu"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="mesas.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'mesas.php') ? 'active' : ''; ?>">
                            <i class="bi bi-cup-hot-fill icon-menu"></i>                        
                            <span>Tablero de Mesas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pedidos.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'pedidos.php') ? 'active' : ''; ?>">
                            <i class="bi bi-receipt-cutoff icon-menu"></i>
                            <span>Monitor de Pedidos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="menu.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'menu.php') ? 'active' : ''; ?>">
                            <i class="bi bi-book-fill icon-menu"></i>
                            <span>Gestión del Menú</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="usuarios.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'usuarios.php') ? 'active' : ''; ?>">
                            <i class="bi bi-people-fill icon-menu"></i>
                            <span>Usuarios y Roles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="cuentas_ventas.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'cuentas_ventas.php') ? 'active' : ''; ?>">
                            <i class="bi bi-cash-coin icon-menu"></i>
                            <span>Cuentas y Ventas</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Perfil móvil -->
        <div class="sidebar-footer pt-3 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80" alt="Usuario" class="avatar-usuario rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                <div class="info-usuario overflow-hidden">
                    <h6 class="mb-0 text-truncate font-weight-bold text-white small">ADMIN</h6>
                    <small class="text-white-50 text-truncate d-block" style="font-size: 0.75rem;">admin@beanora.com</small>
                </div>
            </div>
            <a href="login.php" class="btn-logout" title="Cerrar sesión">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- 3. SIDEBAR DE ESCRITORIO (Oculto en móviles con d-none, visible en pantallas grandes con d-lg-flex) -->
<aside class="sidebar-wrapper d-none d-lg-flex flex-column justify-content-between shadow-sm">
    <div>
        <!-- Branding Top -->
        <div class="sidebar-header d-flex align-items-center gap-3 p-4 pb-3">
            <div class="marca-icono shadow-sm">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <div>
                <h1 class="marca-nombre m-0 lh-1">Beanora</h1>
                <span class="subtitulo-marca">TOSTADURÍA ARTESANAL</span>
            </div>
        </div>

        <!-- Banner de Rol y Versión -->
        <div class="sidebar-status px-4 py-2 my-2 d-flex justify-content-between align-items-center">
            <span class="d-flex align-items-center gap-2 status-texto">
                <span class="punto-verde"></span> Modo Administrador
            </span>
        </div>

        <!-- Menú de Navegación -->
        <nav class="sidebar-nav px-3 mt-3">
            <ul class="nav nav-pills flex-column gap-2">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'dashboard.php' || $pagina_actual == 'index.php') ? 'active' : ''; ?>">
                        <i class="bi bi-pie-chart-fill icon-menu"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="mesas.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'mesas.php') ? 'active' : ''; ?>">
                        <i class="bi bi-cup-hot-fill icon-menu"></i>                        
                        <span>Tablero de Mesas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pedidos.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'pedidos.php') ? 'active' : ''; ?>">
                        <i class="bi bi-receipt-cutoff icon-menu"></i>
                        <span>Monitor de Pedidos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="menu.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'menu.php') ? 'active' : ''; ?>">
                        <i class="bi bi-book-fill icon-menu"></i>
                        <span>Gestión del Menú</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="usuarios.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'usuarios.php') ? 'active' : ''; ?>">
                        <i class="bi bi-people-fill icon-menu"></i>
                        <span>Usuarios y Roles</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="cuentas_ventas.php" class="nav-link d-flex align-items-center gap-3 <?= ($pagina_actual == 'cuentas_ventas.php') ? 'active' : ''; ?>">
                        <i class="bi bi-cash-coin icon-menu"></i>
                        <span>Cuentas y Ventas</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Perfil del usuario al final (HU-02: Cerrar Sesión) -->
    <div class="sidebar-footer p-3 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80" alt="Usuario" class="avatar-usuario rounded-circle">
            <div class="info-usuario overflow-hidden">
                <h6 class="mb-0 text-truncate font-weight-bold">ADMIN</h6>
                <small class="text-white-50 text-truncate d-block">admin@beanora.com</small>
            </div>
        </div>
        <a href="login.php" class="btn-logout" title="Cerrar sesión">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</aside>