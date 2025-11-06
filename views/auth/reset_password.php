<?php
require __DIR__ . '/../../includes/auth_check.php';
// allow unauthenticated users to access reset page
// no require_auth()
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Restablecer contraseña</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
    <style>
        .form-row {
            margin-bottom: 10px
        }

        label {
            display: block
        }

        .error {
            color: #b91c1c
        }

        .success {
            color: #065f46
        }
    </style>
</head>

<body>
    <main style="max-width:480px;margin:24px auto;padding:12px;">
        <h1>Restablecer contraseña</h1>
        <p>Introduce el código que recibiste por correo y la nueva contraseña.</p>
        <div id="message" aria-live="polite"></div>
        <form id="reset-form">
            <div class="form-row"><label>Token (código)<br><input name="token" required></label></div>
            <div class="form-row"><label>Nueva contraseña<br><input name="password" type="password" required
                        minlength="8"></label></div>
            <div class="form-row"><label>Confirmar contraseña<br><input name="confirm" type="password" required
                        minlength="8"></label></div>
            <div class="form-row"><button type="submit">Restablecer contraseña</button> <a
                    href="/Sabores360/views/auth/login.php">Volver a iniciar sesión</a></div>
        </form>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const form = document.getElementById('reset-form');
            const msg = document.getElementById('message');
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';

            function showError(text) { msg.innerHTML = '<div class="error">' + text + '</div>'; }
            function showSuccess(text) { msg.innerHTML = '<div class="success">' + text + '</div>'; }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                msg.textContent = '';
                const token = form.querySelector('[name="token"]').value.trim();
                const password = form.querySelector('[name="password"]').value;
                const confirm = form.querySelector('[name="confirm"]').value;
                if (!token) return showError('El código es requerido');
                if (password.length < 8) return showError('La contraseña debe tener al menos 8 caracteres');
                if (password !== confirm) return showError('Las contraseñas no coinciden');

                try {
                    const payload = { token, password };
                    let res;
                    if (window.SABORES360 && SABORES360.API) {
                        res = await SABORES360.API.post('auth/reset-password', payload);
                    } else {
                        const r = await fetch(base + 'auth/reset-password', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                        const t = await r.text(); try { res = JSON.parse(t); } catch (e) { res = { success: r.ok, raw: t }; }
                    }
                    if (res && res.success) {
                        showSuccess('Contraseña restablecida. Redirigiendo a inicio de sesión...');
                        setTimeout(() => location.href = '/Sabores360/views/auth/login.php', 1400);
                    } else {
                        const text = res && (res.message || res.error || (res.raw && String(res.raw))) ? (res.message || res.error || String(res.raw)) : 'No se pudo restablecer la contraseña';
                        showError(text);
                    }
                } catch (err) { showError('Error en la petición: ' + (err && err.message ? err.message : String(err))); }
            });
        })();
    </script>
</body>

</html>