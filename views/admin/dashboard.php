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
                const res = await fetch(base + 'admin/dashboard', { credentials: 'include' });
                const d = await res.json();
                if (d && d.success) {
                    document.getElementById('stats-content').textContent = JSON.stringify(d.data || d, null, 2);
                } else {
                    document.getElementById('stats-content').textContent = 'No se pudieron cargar las estadísticas.';
                }
            } catch (err) { document.getElementById('stats-content').textContent = 'Error en el servidor'; }
        })();
    </script>
</body>

</html>