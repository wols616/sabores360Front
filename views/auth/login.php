<?php
// Generated login view (safe scaffold). Move to `views/auth/login.php` when ready.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Sabores360</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
    <style>
        :root {
            --orange-primary: #ff6b35;
            --orange-secondary: #ff8c42;
            --orange-light: #ffad73;
            --orange-dark: #e55a2b;
            --orange-bg: #fff4f0;
        }

        body {
            background: linear-gradient(135deg, var(--orange-bg) 0%, #ffeee6 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.15);
            border-radius: 20px;
        }

        .btn-orange {
            background: linear-gradient(45deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            background: linear-gradient(45deg, var(--orange-dark), var(--orange-primary));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .form-control:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .text-orange {
            color: var(--orange-primary) !important;
        }

        .text-orange:hover {
            color: var(--orange-dark) !important;
        }

        .brand-logo {
            color: var(--orange-primary);
            font-weight: 700;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(255, 107, 53, 0.1);
        }
    </style>
</head>

<body>
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card auth-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="brand-logo mb-2">
                                <i class="bi bi-shop"></i> Sabores360
                            </h1>
                            <h4 class="text-orange">Iniciar Sesión</h4>
                            <p class="text-muted">Bienvenido de vuelta</p>
                        </div>

                        <form id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Correo electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" required minlength="8">
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-orange btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-2">
                                <a href="/Sabores360/views/auth/register.php" class="text-orange text-decoration-none">
                                    <i class="bi bi-person-plus"></i> Crear cuenta nueva
                                </a>
                            </p>
                            <p class="mb-0">
                                <a href="/Sabores360/views/auth/forgot_password.php"
                                    class="text-orange text-decoration-none">
                                    <i class="bi bi-question-circle"></i> ¿Olvidaste tu contraseña?
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php require __DIR__ . '/../../includes/print_api_js.php'; ?>
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

                // Show loading
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Iniciando sesión...';
                submitBtn.disabled = true;

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
                        await Swal.fire({
                            icon: 'error',
                            title: 'Error de autenticación',
                            text: msg,
                            confirmButtonColor: '#ff6b35'
                        });
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

                    await Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: 'Inicio de sesión exitoso',
                        timer: 1500,
                        showConfirmButton: false,
                        confirmButtonColor: '#ff6b35'
                    });

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
                    await Swal.fire({
                        icon: 'error',
                        title: 'Error del servidor',
                        text: 'No se pudo conectar con el servidor. Intenta de nuevo.',
                        confirmButtonColor: '#ff6b35'
                    });
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
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