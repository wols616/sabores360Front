<?php
// Admin navbar include - place inside views/admin and include with require
// Optional: set $active to the current section (e.g. 'products', 'categories') to style the active link
?>
<style>
    :root {
        --orange-primary: #ff6b35;
        --orange-secondary: #ff8c42;
        --orange-light: #ffad73;
        --orange-dark: #e55a2b;
        --orange-bg: #fff4f0;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark mb-4"
    style="background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary)); border-radius: 15px; box-shadow: 0 4px 15px rgba(255, 107, 53, 0.2);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/Sabores360/views/admin/dashboard.php">
            <img src="https://i.ibb.co/84R9H5nw/image-removebg-preview.png" alt="Sabores360" height="35" class="me-2">
            <span>Admin Panel</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
            aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= (isset($active) && $active === 'dashboard') ? 'active fw-bold' : '' ?>"
                        href="/Sabores360/views/admin/dashboard.php">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($active) && $active === 'orders') ? 'active fw-bold' : '' ?>"
                        href="/Sabores360/views/admin/orders.php">
                        <i class="bi bi-cart-check"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($active) && $active === 'products') ? 'active fw-bold' : '' ?>"
                        href="/Sabores360/views/admin/products.php">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($active) && $active === 'stats') ? 'active fw-bold' : '' ?>"
                        href="/Sabores360/views/admin/stats.php">
                        <i class="bi bi-bar-chart"></i> Gráficos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($active) && $active === 'reports') ? 'active fw-bold' : '' ?>"
                        href="/Sabores360/views/admin/reports.php">
                        <i class="bi bi-file-earmark-text"></i> Reportes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($active) && $active === 'categories') ? 'active fw-bold' : '' ?>"
                        href="/Sabores360/views/admin/categories.php">
                        <i class="bi bi-tags"></i> Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($active) && $active === 'users') ? 'active fw-bold' : '' ?>"
                        href="/Sabores360/views/admin/users.php">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item <?= (isset($active) && $active === 'profile') ? 'active' : '' ?>"
                                href="/Sabores360/views/admin/profile.php">
                                <i class="bi bi-person-gear"></i> Mi Perfil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="/Sabores360/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>