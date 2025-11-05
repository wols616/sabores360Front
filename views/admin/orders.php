<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Pedidos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Pedidos (Administrador)</h1>
        <nav>
            <a href="/Sabores360/views/admin/dashboard.php">Dashboard</a> |
            <a href="/Sabores360/views/admin/orders.php">Pedidos</a> |
            <a href="/Sabores360/views/admin/products.php">Productos</a> |
            <a href="/Sabores360/views/admin/users.php">Usuarios</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Listado de pedidos</h2>
            <div id="orders-list">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const res = await fetch(base + 'admin/orders', { credentials: 'include' });
                const d = await res.json();
                if (d && d.success && Array.isArray(d.orders)) {
                    const container = document.getElementById('orders-list');
                    container.innerHTML = '';
                    d.orders.forEach(o => {
                        const el = document.createElement('div');
                        el.className = 'order-item';
                        el.innerHTML = `<strong>#${o.id}</strong> - ${o.status} - ${o.total_amount} - ${o.created_at}`;
                        container.appendChild(el);
                    });
                } else {
                    document.getElementById('orders-list').textContent = 'No hay pedidos.';
                }
            } catch (err) { document.getElementById('orders-list').textContent = 'Error al cargar pedidos.'; }
        })();
    </script>
</body>

</html>