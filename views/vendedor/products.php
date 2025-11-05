<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Productos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Productos (Vendedor)</h1>
        <nav>
            <a href="/Sabores360/views/vendedor/dashboard.php">Dashboard</a> |
            <a href="/Sabores360/views/vendedor/orders.php">Pedidos</a> |
            <a href="/Sabores360/views/vendedor/products.php">Productos</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Control de stock</h2>
            <div id="seller-products">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const res = await fetch(base + 'seller/products', { credentials: 'include' });
                const d = await res.json();
                const container = document.getElementById('seller-products');
                if (d && d.success && Array.isArray(d.products)) {
                    container.innerHTML = '';
                    d.products.forEach(p => {
                        const el = document.createElement('div');
                        el.innerHTML = `<strong>${p.name}</strong> - Stock: <input data-id="${p.id}" class="stock-input" value="${p.stock}"> <button class="save-stock" data-id="${p.id}">Guardar</button>`;
                        container.appendChild(el);
                    });
                    container.addEventListener('click', async (ev) => {
                        if (ev.target.matches('.save-stock')) {
                            const id = ev.target.getAttribute('data-id');
                            const input = container.querySelector(`.stock-input[data-id="${id}"]`);
                            const val = parseInt(input.value, 10) || 0;
                            await fetch(base + `seller/products/${id}/stock`, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ stock: val }) });
                            alert('Stock actualizado');
                        }
                    });
                } else container.textContent = 'No hay productos.';
            } catch (err) { document.getElementById('seller-products').textContent = 'Error.'; }
        })();
    </script>
</body>

</html>