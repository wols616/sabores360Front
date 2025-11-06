<?php
// Admin navbar include - place inside views/admin and include with require
// Optional: set $active to the current section (e.g. 'products', 'categories') to style the active link
?>
<nav class="admin-nav" style="margin-top:6px;margin-bottom:12px">
    <a href="/Sabores360/views/admin/dashboard.php" <?= (isset($active) && $active === 'dashboard') ? ' style="font-weight:600"' : '' ?>>Dashboard</a> |
    <a href="/Sabores360/views/admin/orders.php" <?= (isset($active) && $active === 'orders') ? ' style="font-weight:600"' : '' ?>>Pedidos</a> |
    <a href="/Sabores360/views/admin/products.php" <?= (isset($active) && $active === 'products') ? ' style="font-weight:600"' : '' ?>>Productos</a> |
    <a href="/Sabores360/views/admin/stats.php" <?= (isset($active) && $active === 'stats') ? ' style="font-weight:600"' : '' ?>>Gráficos</a> |
    <a href="/Sabores360/views/admin/reportes.php" <?= (isset($active) && $active === 'reportes') ? ' style="font-weight:600"' : '' ?>>Reportes</a> |
    <a href="/Sabores360/views/admin/categories.php" <?= (isset($active) && $active === 'categories') ? ' style="font-weight:600"' : '' ?>>Categorías</a> |
    <a href="/Sabores360/views/admin/users.php" <?= (isset($active) && $active === 'users') ? ' style="font-weight:600"' : '' ?>>Usuarios</a> |
    <a href="/Sabores360/views/admin/profile.php" <?= (isset($active) && $active === 'profile') ? ' style="font-weight:600"' : '' ?>>Perfil</a> |
    <a href="/Sabores360/logout.php">Cerrar sesión</a>
</nav>