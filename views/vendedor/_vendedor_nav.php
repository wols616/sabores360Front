<?php
// Vendedor navigation - similar to admin nav but for seller role
$current_user = $_SESSION['user'] ?? null;
$user_name = $current_user['name'] ?? $current_user['email'] ?? 'Vendedor';
?>
<nav class="navbar navbar-expand-lg navbar-dark shadow-lg mb-4"
    style="background: linear-gradient(135deg, #ff6b35, #ff8c42);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/Sabores360/views/vendedor/dashboard.php">
            <img src="https://i.ibb.co/84R9H5nw/image-removebg-preview.png" alt="Sabores360" height="35" class="me-2">
            <span>Sabores360</span> <span class="badge bg-light text-orange ms-2">Vendedor</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#vendedorNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="vendedorNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>"
                        href="/Sabores360/views/vendedor/dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'orders' ? 'active' : '' ?>"
                        href="/Sabores360/views/vendedor/orders.php">
                        <i class="bi bi-box-seam"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'products' ? 'active' : '' ?>"
                        href="/Sabores360/views/vendedor/products.php">
                        <i class="bi bi-grid-3x3-gap"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'profile' ? 'active' : '' ?>"
                        href="/Sabores360/views/vendedor/profile.php">
                        <i class="bi bi-person-circle"></i> Mi Perfil
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown">
                        <div class="avatar-circle me-2">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <?= htmlspecialchars($user_name) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/Sabores360/views/vendedor/profile.php">
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

<style>
    .text-orange {
        color: #ff6b35 !important;
    }

    .navbar-nav .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        padding: 0.75rem 1rem !important;
        border-radius: 8px;
        margin: 0 0.25rem;
        transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white !important;
        transform: translateY(-1px);
    }

    .navbar-nav .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
        color: white !important;
        font-weight: 600;
    }

    .avatar-circle {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        padding: 0.5rem 0;
    }

    .dropdown-item {
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, #fff4f0, #feeee7);
        color: #ff6b35;
    }

    .navbar-brand .badge {
        font-size: 0.6em;
        padding: 0.4em 0.6em;
    }
</style>