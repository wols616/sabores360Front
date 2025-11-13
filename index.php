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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --orange-primary: #ff6b35;
            --orange-secondary: #ff8c42;
            --orange-bg: #fff4f0;
        }

        body {
            background: linear-gradient(135deg, var(--orange-bg) 0%, #ffeee6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .loading-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.15);
        }

        .logo {
            animation: pulse 2s infinite;
        }

        .loading-text {
            color: var(--orange-primary);
            font-weight: 600;
            margin-top: 1rem;
        }

        .spinner {
            border: 3px solid rgba(255, 107, 53, 0.3);
            border-top: 3px solid var(--orange-primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 1rem auto;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="loading-container">
        <div class="logo">
            <img src="https://i.ibb.co/84R9H5nw/image-removebg-preview.png" alt="Sabores360" height="100">
        </div>
        <h2 class="loading-text">Sabores360</h2>
        <div class="spinner"></div>
        <p class="text-muted">Comprobando sesión...</p>
    </div>
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