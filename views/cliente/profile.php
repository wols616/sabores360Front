<?php
require __DIR__ . '/../../includes/auth_check.php';
require_auth();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Mi perfil</h1>
        <nav>
            <a href="/Sabores360/views/cliente/dashboard.php">Menú</a> |
            <a href="/Sabores360/views/cliente/my_orders.php">Mis pedidos</a> |
            <a href="/Sabores360/views/cliente/profile.php">Mi perfil</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <form id="profile-form">
            <label>Nombre<br><input name="name"></label><br>
            <label>Email<br><input name="email" type="email"></label><br>
            <label>Dirección<br><textarea name="address"></textarea></label><br>
            <button type="submit">Guardar</button>
        </form>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const form = document.getElementById('profile-form');
            try {
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('client/profile') : (async () => { const res = await fetch(base + 'client/profile', { credentials: 'include' }); return res.json(); })());
                if (d && d.success && d.profile) {
                    form.name.value = d.profile.name || '';
                    form.email.value = d.profile.email || '';
                    form.address.value = d.profile.address || '';
                }
            } catch (err) { }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(form);
                const payload = { name: fd.get('name'), email: fd.get('email'), address: fd.get('address') };
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.put('client/profile', payload) : (async () => { const res = await fetch(base + 'client/profile', { method: 'PUT', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); return res.json(); })());
                    if (d && d.success) alert('Perfil actualizado'); else alert(d.message || 'Error');
                } catch (err) { alert('Error al actualizar perfil'); }
            });
        })();
    </script>
</body>

</html>