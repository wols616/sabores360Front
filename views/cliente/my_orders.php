<?php
require __DIR__ . '/../../includes/auth_check.php';
require_auth();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mis pedidos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Mis pedidos</h1>
        <?php require __DIR__ . '/../_navbar.php'; ?>
    </header>

    <main>
        <div id="orders">Cargando...</div>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('orders');

            async function fetchOrders() {
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('client/orders') : (async () => { const res = await fetch(base + 'client/orders', { credentials: 'include' }); return res.json(); })());
                    // normalize possible response shapes: d.orders | d.data | d.data.orders | d.items
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
                            const el = document.createElement('div');
                            el.innerHTML = `<strong>#${o.id}</strong> - ${o.status || o.state || 'N/A'} - ${o.total_amount || o.total || '0.00'} - ${o.created_at || o.createdAt || o.date || ''} <button data-id="${o.id}" class="reorder">Reordenar</button>`;
                            container.appendChild(el);
                        });
                    } else container.textContent = 'No tienes pedidos.';
                } catch (err) { container.textContent = 'Error al cargar pedidos.'; }
            }

            // Delegate reorder clicks and refresh list after success
            container.addEventListener('click', async (ev) => {
                if (ev.target.matches('.reorder')) {
                    const id = ev.target.getAttribute('data-id');
                    try {
                        const d2 = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post(`client/orders/${id}/reorder`) : (async () => { const res = await fetch(base + `client/orders/${id}/reorder`, { method: 'POST', credentials: 'include' }); return res.json(); })());
                        if (d2 && d2.success) {
                            // notify and refresh the orders list so UI reflects changes
                            alert('Pedido agregado al carrito');
                            await fetchOrders();
                        } else {
                            alert(d2 && d2.message ? d2.message : 'No se pudo reordenar');
                        }
                    } catch (e) { alert('Error al reordenar'); }
                }
            });

            // initial load
            await fetchOrders();
        })();
    </script>
</body>

</html>