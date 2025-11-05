<?php
// Generated forgot password view (safe scaffold).
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Recuperar contraseña - Sabores360</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <main class="container">
        <h1>Recuperar contraseña</h1>
        <form id="forgot-form">
            <label>Email<br><input type="email" name="email" required></label><br>
            <button type="submit">Enviar enlace de recuperación</button>
        </form>
        <p><a href="/Sabores360/views/auth/login.php">Volver al login</a></p>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const form = document.getElementById('forgot-form');
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(form);
                const payload = { email: fd.get('email') };
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                try {
                    const res = await fetch(base + 'auth/forgot-password', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                    const d = await res.json();
                    if (d && d.success) {
                        if (window.SABORES360 && SABORES360.Notifications) SABORES360.Notifications.success('Revisa tu correo');
                        else alert('Revisa tu correo');
                    } else {
                        alert(d.message || 'No se pudo enviar el correo');
                    }
                } catch (err) { console.error(err); alert('Error en servidor'); }
            });
        })();
    </script>
</body>

</html>