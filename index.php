<?php
// index.php - Redirector for Sabores360
// Place this file at the project root served by XAMPP: http://localhost:8888/Sabores360/
// It performs a client-side check to /api/auth/me (credentials included) and redirects
// to the appropriate dashboard or login page.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sabores360</title>
</head>

<body>
    <p>Comprobando sesión, redirigiendo...</p>
    <script>
        // If you have assets/js/common.js that sets SABORES360.API_BASE, it will be used.
        const API_BASE = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';

        (async function () {
            try {
                // Call auth/me to detect active session (backend must support this and accept credentials)
                const res = await fetch(API_BASE + 'auth/me', { credentials: 'include' });
                if (res.ok) {
                    const data = await res.json();
                    if (data && data.success && data.user) {
                        const roleRaw = (data.user.role || data.user.role_name || data.role || '').toString().toLowerCase();
                        if (roleRaw.includes('admin') || roleRaw.includes('administrador')) {
                            window.location.href = '/Sabores360/views/admin/dashboard.php';
                            return;
                        }
                        if (roleRaw.includes('vend') || roleRaw.includes('seller') || roleRaw.includes('vendedor')) {
                            window.location.href = '/Sabores360/views/vendedor/dashboard.php';
                            return;
                        }
                        // default: client
                        window.location.href = '/Sabores360/views/cliente/dashboard.php';
                        return;
                    }
                }
            } catch (err) {
                // console.error(err);
            }
            // No session -> go to login
            window.location.href = '/Sabores360/views/auth/login.php';
        })();
    </script>
</body>

</html>