<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Productos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Productos (Administrador)</h1>
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
            <h2>Lista de productos</h2>
            <div><button id="new-product">Agregar producto</button></div>
            <div id="product-list">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const res = await fetch(base + 'admin/products', { credentials: 'include' });
                const d = await res.json();
                const container = document.getElementById('product-list');
                if (d && d.success && Array.isArray(d.products)) {
                    container.innerHTML = '';
                    d.products.forEach(p => {
                        const el = document.createElement('div');
                        el.className = 'product-item';
                        el.innerHTML = `<strong>${p.name}</strong> - ${p.price} - ${p.is_available ? 'Disponible' : 'No disponible'}<br>`;
                        container.appendChild(el);
                    });
                } else {
                    container.textContent = 'No hay productos.';
                }
            } catch (err) { document.getElementById('product-list').textContent = 'Error al cargar productos.'; }
        })();
    </script>
</body>

</html>