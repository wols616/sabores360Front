<?php
// Protected admin dashboard - require admin role
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Dashboard</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Panel de Administrador</h1>
        <nav>
            <a href="/Sabores360/views/admin/dashboard.php">Dashboard</a> |
            <a href="/Sabores360/views/admin/orders.php">Pedidos</a> |
            <a href="/Sabores360/views/admin/products.php">Productos</a> |
            <a href="/Sabores360/views/admin/users.php">Usuarios</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <main>
        <section id="stats">
            <h2>Estadísticas</h2>
            <div id="stats-content">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/dashboard') : (async () => { const res = await fetch(base + 'admin/dashboard', { credentials: 'include' }); return res.json(); })());
                if (d && d.success) {
                    // Render dashboard summary cards and recent orders table
                    const data = d.data || d;
                    const statsEl = document.getElementById('stats-content');
                    const ordersCount = data.orders_count || data.ordersCount || 0;
                    const usersCount = data.users_count || data.usersCount || 0;
                    const productsCount = data.products_count || data.productsCount || 0;
                    const lowStock = data.low_stock_count || data.lowStockCount || 0;

                    let html = '';
                    html += `<div class="cards">`;
                    html += `<div class="card"><strong>Pedidos</strong><div class="big">${ordersCount}</div></div>`;
                    html += `<div class="card"><strong>Usuarios</strong><div class="big">${usersCount}</div></div>`;
                    html += `<div class="card"><strong>Productos</strong><div class="big">${productsCount}</div></div>`;
                    html += `<div class="card"><strong>Bajo stock</strong><div class="big">${lowStock}</div></div>`;
                    html += `</div>`;

                    // recent orders
                    const recent = Array.isArray(data.recent_orders) ? data.recent_orders : (Array.isArray(data.recentOrders) ? data.recentOrders : []);
                    html += `<h3>Pedidos recientes</h3>`;
                    if (recent.length) {
                        html += `<table class="table"><thead><tr><th>ID</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Pago</th><th>Dirección</th><th>Creado</th></tr></thead><tbody>`;
                        recent.forEach(o => {
                            const client = o.client && (o.client.name || o.client.email) ? (o.client.name ? `${o.client.name} <small>(${o.client.email || ''})</small>` : (o.client.email || '')) : '';
                            const total = o.totalAmount || o.total_amount || o.total || '';
                            const status = o.status || o.state || '';
                            const payment = o.paymentMethod || o.payment_method || '';
                            const addr = o.deliveryAddress || o.delivery_address || '';
                            const created = o.createdAt || o.created_at || o.date || '';
                            html += `<tr><td>#${o.id}</td><td>${client}</td><td>${total}</td><td>${status}</td><td>${payment}</td><td>${addr}</td><td>${created}</td></tr>`;
                        });
                        html += `</tbody></table>`;
                    } else {
                        html += `<div>No hay pedidos recientes.</div>`;
                    }

                    statsEl.innerHTML = html;
                } else {
                    document.getElementById('stats-content').textContent = 'No se pudieron cargar las estadísticas.';
                }
            } catch (err) { document.getElementById('stats-content').textContent = 'Error en el servidor'; }
        })();
    </script>
</body>

</html>