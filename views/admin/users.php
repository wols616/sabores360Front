<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Usuarios</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Usuarios (Administrador)</h1>
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
            <h2>Listado de usuarios</h2>
            <div id="users-list">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const res = await fetch(base + 'admin/users', { credentials: 'include' });
                const d = await res.json();
                const container = document.getElementById('users-list');
                if (d && d.success && Array.isArray(d.users)) {
                    container.innerHTML = '';
                    d.users.forEach(u => {
                        const el = document.createElement('div');
                        el.innerHTML = `<strong>${u.name}</strong> - ${u.email} - ${u.role || u.role_name || u.role_id}`;
                        container.appendChild(el);
                    });
                } else {
                    container.textContent = 'No hay usuarios.';
                }
            } catch (err) { document.getElementById('users-list').textContent = 'Error al cargar usuarios.'; }
        })();
    </script>
</body>

</html>