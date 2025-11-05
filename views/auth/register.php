<?php
// Generated register view (safe scaffold). Move to `views/auth/register.php` when ready.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Registro - Sabores360</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <main class="container">
        <h1>Crear cuenta</h1>
        <form id="register-form">
            <label>Nombre<br><input type="text" name="name" required></label><br>
            <label>Email<br><input type="email" name="email" required></label><br>
            <label>Dirección<br><textarea name="address" required></textarea></label><br>
            <label>Contraseña<br><input type="password" name="password" required minlength="8"></label><br>
            <button type="submit">Registrarme</button>
        </form>
        <p><a href="/Sabores360/views/auth/login.php">Ya tengo cuenta</a></p>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const form = document.getElementById('register-form');

            async function doRegister(payload) {
                if (window.SABORES360 && SABORES360.API && typeof SABORES360.API.post === 'function') {
                    return SABORES360.API.post('auth/register', payload);
                }
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                const res = await fetch(base + 'auth/register', {
                    method: 'POST',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                return res.json();
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(form);
                const payload = { name: fd.get('name'), email: fd.get('email'), address: fd.get('address'), password: fd.get('password') };
                try {
                    const data = await doRegister(payload);
                    if (data && data.success) {
                        if (window.SABORES360 && SABORES360.Notifications) SABORES360.Notifications.success('Cuenta creada!');
                        // after register, redirect to login or dashboard if backend logs in automatically
                        setTimeout(() => { window.location.href = '/Sabores360/views/auth/login.php'; }, 800);
                    } else {
                        const msg = (data && data.message) ? data.message : 'Error en el registro';
                        if (window.SABORES360 && SABORES360.Notifications) SABORES360.Notifications.error(msg);
                        else alert(msg);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error en el servidor');
                }
            });
        })();
    </script>
</body>

</html>