<?php
// Navbar específica para clientes
// Requiere que auth_check.php haya sido incluido previamente y $active esté definido
?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/Sabores360/views/cliente/dashboard.php">
            <img src="https://i.ibb.co/84R9H5nw/image-removebg-preview.png" alt="Sabores360" height="35" class="me-2">
            <span class="brand-text">Sabores360</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>"
                        href="/Sabores360/views/cliente/dashboard.php">
                        <i class="bi bi-grid-3x2-gap me-1"></i>
                        Menú
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'orders' ? 'active' : '' ?>"
                        href="/Sabores360/views/cliente/my_orders.php">
                        <i class="bi bi-bag-check me-1"></i>
                        Mis Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'cart' ? 'active' : '' ?>"
                        href="/Sabores360/views/cliente/cart.php">
                        <i class="bi bi-cart3 me-1"></i>
                        Carrito
                        <span id="cart-badge" class="badge bg-warning text-dark ms-1" style="display: none;">0</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'profile' ? 'active' : '' ?>"
                        href="/Sabores360/views/cliente/profile.php">
                        <i class="bi bi-person me-1"></i>
                        Mi Perfil
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle me-2">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="user-name">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['user']['name'] ?? 'Cliente') ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/Sabores360/views/cliente/profile.php">
                                <i class="bi bi-person-gear me-2"></i>
                                Mi Perfil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="/Sabores360/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    :root {
        --orange-primary: #ff6b35;
        --orange-secondary: #ff8c42;
        --orange-light: #ffeaa7;
        --orange-dark: #e55a2b;
    }

    .navbar {
        background: linear-gradient(135deg, var(--orange-primary) 0%, var(--orange-secondary) 100%);
        box-shadow: 0 2px 15px rgba(255, 107, 53, 0.3);
        padding: 0.75rem 0;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        color: white !important;
        transition: all 0.3s ease;
    }

    .navbar-brand:hover {
        transform: scale(1.05);
        color: var(--orange-light) !important;
    }

    .brand-text {
        background: linear-gradient(45deg, #fff, var(--orange-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        padding: 0.75rem 1rem !important;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
    }

    .nav-link:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-1px);
    }

    .nav-link.active {
        color: white !important;
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 3px;
        background: white;
        border-radius: 2px;
    }

    .avatar-circle {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .dropdown-toggle:hover .avatar-circle {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: scale(1.1);
    }

    .user-name {
        font-weight: 600;
        font-size: 0.95rem;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-radius: 12px;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
    }

    .dropdown-item {
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        color: white;
        transform: translateX(5px);
    }

    .dropdown-item.text-danger:hover {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .dropdown-divider {
        margin: 0.5rem 0;
        border-color: rgba(255, 107, 53, 0.2);
    }

    .navbar-toggler {
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 0.5rem;
    }

    .navbar-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    #cart-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 10px;
        font-weight: 600;
        background: var(--orange-light) !important;
        color: var(--orange-dark) !important;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    @media (max-width: 991.98px) {
        .navbar-nav {
            margin-top: 1rem;
        }

        .nav-link {
            padding: 0.75rem 0 !important;
            border-radius: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-link:last-child {
            border-bottom: none;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Update cart badge from localStorage
        function updateCartBadge() {
            try {
                const cart = JSON.parse(localStorage.getItem('sabores360_cart') || '[]');
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    const itemCount = cart.reduce((total, item) => total + (item.quantity || 1), 0);
                    if (itemCount > 0) {
                        badge.textContent = itemCount;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            } catch (e) {
                console.warn('Error updating cart badge:', e);
            }
        }

        // Update badge on page load
        updateCartBadge();

        // Listen for storage changes to update badge across tabs
        window.addEventListener('storage', function (e) {
            if (e.key === 'sabores360_cart') {
                updateCartBadge();
            }
        });

        // Update badge when cart is modified (custom event)
        window.addEventListener('cartUpdated', updateCartBadge);
    });
</script>