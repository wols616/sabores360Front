<?php
// Shared navbar include. Assumes session is started by auth_check.php in the including page.
$role = '';
if (!empty($_SESSION['user_role']))
    $role = strtolower($_SESSION['user_role']);
elseif (!empty($_SESSION['user']['role']))
    $role = strtolower($_SESSION['user']['role']);
?>
<nav>
    <?php if ($role && strpos($role, 'admin') !== false): ?>
        <a href="/Sabores360/views/admin/dashboard.php">Dashboard</a> |
        <a href="/Sabores360/views/admin/orders.php">Pedidos</a> |
        <a href="/Sabores360/views/admin/products.php">Productos</a> |
        <a href="/Sabores360/views/admin/profile.php">Perfil</a> |
        <a href="/Sabores360/logout.php">Cerrar sesión</a>
    <?php elseif ($role && (strpos($role, 'vendedor') !== false || strpos($role, 'seller') !== false)): ?>
        <a href="/Sabores360/views/vendedor/dashboard.php">Dashboard</a> |
        <a href="/Sabores360/views/vendedor/orders.php">Pedidos</a> |
        <a href="/Sabores360/views/vendedor/products.php">Productos</a> |
        <a href="/Sabores360/views/vendedor/profile.php">Perfil</a> |
        <a href="/Sabores360/logout.php">Cerrar sesión</a>
    <?php elseif ($role): ?>
        <a href="/Sabores360/views/cliente/dashboard.php">Menú</a> |
        <a href="/Sabores360/views/cliente/my_orders.php">Mis pedidos</a> |
        <a href="/Sabores360/views/cliente/cart.php">Carrito</a> |
        <a href="/Sabores360/views/cliente/profile.php">Mi perfil</a> |
        <a href="/Sabores360/logout.php">Cerrar sesión</a>
    <?php else: ?>
        <a href="/Sabores360/views/auth/login.php">Iniciar sesión</a> |
        <a href="/Sabores360/views/auth/register.php">Registrarse</a>
    <?php endif; ?>
</nav>