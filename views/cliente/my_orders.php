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
        <nav>
            <a href="/Sabores360/views/cliente/dashboard.php">Menú</a> |
            <a href="/Sabores360/views/cliente/my_orders.php">Mis pedidos</a> |
            <a href="/Sabores360/views/cliente/profile.php">Mi perfil</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <div id="orders">Cargando...</div>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('client/orders') : (async () => { const res = await fetch(base + 'client/orders', { credentials: 'include' }); return res.json(); })());
                const container = document.getElementById('orders');
                if (d && d.success && Array.isArray(d.orders)) {
                    container.innerHTML = '';
                    d.orders.forEach(o => {
                        const el = document.createElement('div');
                        el.innerHTML = `<strong>#${o.id}</strong> - ${o.status} - ${o.total_amount} - ${o.created_at} <button data-id="${o.id}" class="reorder">Reordenar</button>`;
                        container.appendChild(el);
                    });
                    container.addEventListener('click', async (ev) => {
                        if (ev.target.matches('.reorder')) {
                            const id = ev.target.getAttribute('data-id');
                            const d2 = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post(`client/orders/${id}/reorder`) : (async () => { const res = await fetch(base + `client/orders/${id}/reorder`, { method: 'POST', credentials: 'include' }); return res.json(); })());
                            if (d2 && d2.success) alert('Pedido agregado al carrito');
                        }
                    });
                } else container.textContent = 'No tienes pedidos.';
            } catch (err) { document.getElementById('orders').textContent = 'Error al cargar pedidos.'; }
        })();
    </script>
</body>

</html>