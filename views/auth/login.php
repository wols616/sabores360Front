<?php
// Generated login view (safe scaffold). Move to `views/auth/login.php` when ready.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Sabores360</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <main class="container">
        <h1>Iniciar sesión</h1>
        <form id="login-form">
            <label>Email<br><input type="email" name="email" required></label><br>
            <label>Contraseña<br><input type="password" name="password" required minlength="8"></label><br>
            <button type="submit">Entrar</button>
        </form>
        <p><a href="/Sabores360/views/auth/register.php">Crear cuenta</a> | <a
                href="/Sabores360/views/auth/forgot_password.php">Olvidé mi contraseña</a></p>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const form = document.getElementById('login-form');

            async function doLogin(payload) {
                // Prefer the API helper if available
                if (window.SABORES360 && SABORES360.API && typeof SABORES360.API.post === 'function') {
                    return SABORES360.API.post('auth/login', payload);
                }
                // Fallback: fetch directly to API base if available
                const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                const res = await fetch(base + 'auth/login', {
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
                const payload = { email: fd.get('email'), password: fd.get('password') };
                try {
                    const data = await doLogin(payload);
                    console.log('login response:', data);

                    // Determine success in several possible shapes returned by backends
                    const hasToken = data && (data.token || data.access_token || data.auth_token);
                    const hasUser = data && data.user;
                    const successFlag = data && (data.success === true || hasToken || hasUser);

                    if (!successFlag) {
                        const msg = (data && data.message) ? data.message : 'Credenciales inválidas';
                        if (window.SABORES360 && SABORES360.Notifications) SABORES360.Notifications.error(msg);
                        else alert(msg);
                        return;
                    }

                    // If backend returned a token in the body, persist it client-side (cookie) so server-side PHP can forward it.
                    if (hasToken) {
                        const token = data.token || data.access_token || data.auth_token;
                        // set a session cookie (path=/). In production prefer HttpOnly cookies from backend.
                        document.cookie = 'auth_token=' + encodeURIComponent(token) + '; path=/';
                    }

                    // If backend didn't include user, try to retrieve via /auth/me
                    let user = null;
                    if (hasUser) user = data.user;
                    else {
                        try {
                            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                            const meRes = await fetch(base + 'auth/me', { credentials: 'include' });
                            if (meRes.ok) {
                                const meJson = await meRes.json();
                                if (meJson && meJson.success && meJson.user) user = meJson.user;
                            }
                        } catch (e) { /* ignore */ }
                    }

                    if (window.SABORES360 && SABORES360.Notifications) SABORES360.Notifications.success('Bienvenido!');

                    const role = (user && (user.role || user.role_name)) || data.role || 'client';
                    const r = role.toString().toLowerCase();
                    if (r.includes('admin')) {
                        window.location.href = '/Sabores360/views/admin/dashboard.php';
                    } else if (r.includes('vend') || r.includes('seller')) {
                        window.location.href = '/Sabores360/views/vendedor/dashboard.php';
                    } else {
                        window.location.href = '/Sabores360/views/cliente/dashboard.php';
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error en el servidor');
                }
            });

            // If already logged in, redirect based on session check
            document.addEventListener('DOMContentLoaded', async () => {
                try {
                    const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                    const res = await fetch(base + 'auth/me', { credentials: 'include' });
                    if (res.ok) {
                        const d = await res.json();
                        if (d && d.success && d.user) {
                            const role = d.user.role || d.user.role_name || 'client';
                            if (role === 'Administrador' || role === 'admin') window.location.href = '/Sabores360/views/admin/dashboard.php';
                            else if (role === 'Vendedor' || role === 'seller' || role === 'vendedor') window.location.href = '/Sabores360/views/vendedor/dashboard.php';
                            else window.location.href = '/Sabores360/views/cliente/dashboard.php';
                        }
                    }
                } catch (e) { /* ignore */ }
            });

        })();
    </script>
</body>

</html>