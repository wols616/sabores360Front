<?php
// Generated register view (safe scaffold). Move to `views/auth/register.php` when ready.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Registro - Sabores360</title>
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
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card auth-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <img src="https://i.ibb.co/nMX5PL04/Logo360.png" alt="Sabores360" height="300"
                                    class="mb-2">
                            </div>
                            <h4 class="text-orange">Crear Cuenta</h4>
                            <p class="text-muted">Únete a nuestra comunidad gastronómica</p>
                        </div>

                        <form id="register-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person"></i> Nombre completo
                                </label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Correo electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="bi bi-geo-alt"></i> Dirección de entrega
                                </label>
                                <textarea class="form-control" id="address" name="address" rows="3" required
                                    placeholder="Ingresa tu dirección completa para las entregas"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" required minlength="8">
                                <div class="form-text">Mínimo 8 caracteres</div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-orange btn-lg">
                                    <i class="bi bi-person-plus"></i> Crear mi cuenta
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0">
                                ¿Ya tienes una cuenta?
                                <a href="/Sabores360/views/auth/login.php"
                                    class="text-orange text-decoration-none fw-bold">
                                    <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
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
        document.getElementById('register-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creando cuenta...';

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            try {
                const response = await SABORES360.API.post('auth/register', data);
                if (response.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Cuenta creada!',
                        text: 'Tu cuenta ha sido creada exitosamente. Ahora puedes iniciar sesión.',
                        confirmButtonText: 'Iniciar sesión',
                        confirmButtonColor: '#ff6b35',
                        showClass: {
                            popup: 'animate__animated animate__fadeInUp'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutDown'
                        }
                    });
                    window.location.href = '/Sabores360/views/auth/login.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al crear cuenta',
                        text: response.message || 'No se pudo crear la cuenta. Intenta nuevamente.',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#ff6b35'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Verifica tu conexión.',
                    confirmButtonText: 'Reintentar',
                    confirmButtonColor: '#ff6b35'
                });
            } finally {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
</body>

</html>