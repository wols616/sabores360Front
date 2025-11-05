<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Pedidos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Pedidos asignados</h1>
        <nav>
            <a href="/Sabores360/views/vendedor/dashboard.php">Dashboard</a> |
            <a href="/Sabores360/views/vendedor/orders.php">Pedidos</a> |
            <a href="/Sabores360/views/vendedor/products.php">Productos</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <div id="my-orders">Cargando pedidos...</div>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const res = await fetch(base + 'seller/orders', { credentials: 'include' });
                const d = await res.json();
                const container = document.getElementById('my-orders');
                if (d && d.success && Array.isArray(d.orders)) {
                    container.innerHTML = '';
                    d.orders.forEach(o => {
                        const el = document.createElement('div');
                        el.innerHTML = `<strong>#${o.id}</strong> - ${o.status} - <button data-id="${o.id}" class="btn-change">Cambiar estado</button>`;
                        container.appendChild(el);
                    });
                    container.addEventListener('click', async (ev) => {
                        if (ev.target.matches('.btn-change')) {
                            const id = ev.target.getAttribute('data-id');
                            const newStatus = prompt('Nuevo estado (En preparación, En camino, Entregado, Cancelado):');
                            if (!newStatus) return;
                            await fetch(base + `seller/orders/${id}/status`, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ status: newStatus }) });
                            alert('Actualizado');
                            location.reload();
                        }
                    });
                } else container.textContent = 'No tienes pedidos.';
            } catch (err) { document.getElementById('my-orders').textContent = 'Error al cargar pedidos.'; }
        })();
    </script>
</body>

</html>