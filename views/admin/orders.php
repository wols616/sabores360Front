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
        <?php $active = 'orders';
        require __DIR__ . '/_admin_nav.php'; ?>
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
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/orders') : (async () => { const res = await fetch(base + 'admin/orders', { credentials: 'include' }); return res.json(); })());
                const container = document.getElementById('orders-list');
                // normalize possible shapes: d.orders | d.data | d.data.orders | d.items
                let orders = [];
                if (d && d.success) {
                    if (Array.isArray(d.orders)) orders = d.orders;
                    else if (Array.isArray(d.data)) orders = d.data;
                    else if (d.data && Array.isArray(d.data.orders)) orders = d.data.orders;
                    else if (d.data && Array.isArray(d.data.items)) orders = d.data.items;
                    else if (Array.isArray(d.items)) orders = d.items;
                }
                if (orders && orders.length) {
                    container.innerHTML = '';
                    orders.forEach(o => {
                        const id = o.id || o.orderId || o.order_id || '';
                        const status = o.status || o.state || '';
                        const total = o.total_amount || o.totalAmount || o.total || '';
                        const created = o.created_at || o.createdAt || o.date || '';
                        const el = document.createElement('div');
                        el.className = 'order-item';
                        el.innerHTML = `<strong>#${id}</strong> - ${status} - ${total} - ${created}`;
                        container.appendChild(el);
                    });
                } else {
                    container.textContent = 'No hay pedidos.';
                }
            } catch (err) { document.getElementById('orders-list').textContent = 'Error al cargar pedidos.'; }
        })();
    </script>
</body>

</html>