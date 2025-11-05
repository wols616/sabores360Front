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
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/users') : (async () => { const res = await fetch(base + 'admin/users', { credentials: 'include' }); return res.json(); })());
                const container = document.getElementById('users-list');
                // normalize users array shapes
                let users = [];
                if (d && d.success) {
                    if (Array.isArray(d.users)) users = d.users;
                    else if (Array.isArray(d.data)) users = d.data;
                    else if (d.data && Array.isArray(d.data.users)) users = d.data.users;
                    else if (Array.isArray(d.items)) users = d.items;
                }
                if (users && users.length) {
                    container.innerHTML = '';
                    users.forEach(u => {
                        const name = u.name || u.fullName || u.username || '';
                        const email = u.email || '';
                        const role = u.role || u.role_name || (u.role && u.role.name) || '';
                        const el = document.createElement('div');
                        el.innerHTML = `<strong>${name}</strong> - ${email} - ${role}`;
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