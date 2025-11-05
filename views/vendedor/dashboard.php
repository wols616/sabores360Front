<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Dashboard</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Panel Vendedor</h1>
        <nav>
            <a href="/Sabores360/views/vendedor/dashboard.php">Dashboard</a> |
            <a href="/Sabores360/views/vendedor/orders.php">Pedidos</a> |
            <a href="/Sabores360/views/vendedor/products.php">Productos</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <section id="seller-stats">Cargando...</section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const res = await fetch(base + 'seller/dashboard', { credentials: 'include' });
                const d = await res.json();
                if (d && d.success) document.getElementById('seller-stats').textContent = JSON.stringify(d.data || d, null, 2);
                else document.getElementById('seller-stats').textContent = 'No hay datos.';
            } catch (err) { document.getElementById('seller-stats').textContent = 'Error'; }
        })();
    </script>
</body>

</html>