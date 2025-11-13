<?php
// Generated forgot password view (safe scaffold).
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Recuperar contraseña - Sabores360</title>
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

        .btn-outline-orange {
            border: 2px solid var(--orange-primary);
            color: var(--orange-primary);
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-orange:hover {
            background: var(--orange-primary);
            border-color: var(--orange-primary);
            color: white;
            transform: translateY(-2px);
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

        .recovery-icon {
            font-size: 4rem;
            color: var(--orange-primary);
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card auth-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <img src="https://i.ibb.co/nMX5PL04/Logo360.png" alt="Sabores360" height="300"
                                    class="mb-2">
                            </div>
                            <div class="recovery-icon mb-3">
                                <i class="bi bi-key"></i>
                            </div>
                            <h4 class="text-orange mb-2">Recuperar Contraseña</h4>
                            <p class="text-muted">
                                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña
                            </p>
                        </div>

                        <form id="forgot-form">
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Correo electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    required placeholder="tu@email.com" autocomplete="email">
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-orange btn-lg">
                                    <i class="bi bi-send"></i> Enviar enlace de recuperación
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <div id="reset-link-section" class="mb-3" style="display: none;">
                                <div class="alert alert-info border-0" style="background: rgba(255, 107, 53, 0.1);">
                                    <p class="mb-2">
                                        <i class="bi bi-envelope-check text-orange"></i>
                                        <strong>¡Código enviado!</strong>
                                    </p>
                                    <p class="mb-3 small">Revisa tu correo y haz clic en el botón de abajo cuando tengas
                                        el código:</p>
                                    <a href="/Sabores360/views/auth/reset_password.php" class="btn btn-outline-orange">
                                        <i class="bi bi-key"></i> Ingresar código de recuperación
                                    </a>
                                </div>
                            </div>

                            <p class="mb-0">
                                ¿Recordaste tu contraseña?
                                <a href="/Sabores360/views/auth/login.php"
                                    class="text-orange text-decoration-none fw-bold">
                                    <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
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
        document.getElementById('forgot-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            try {
                const response = await SABORES360.API.post('auth/forgot-password', data);
                if (response.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Correo enviado!',
                        text: 'Hemos enviado un enlace de recuperación a tu correo electrónico. Revisa tu bandeja de entrada y spam.',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#ff6b35',
                        showClass: {
                            popup: 'animate__animated animate__fadeInUp'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutDown'
                        }
                    });

                    // Clear the form and show reset link
                    this.reset();
                    document.getElementById('reset-link-section').style.display = 'block';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al enviar correo',
                        text: response.message || 'No se pudo enviar el correo de recuperación. Verifica que el email sea correcto.',
                        confirmButtonText: 'Reintentar',
                        confirmButtonColor: '#ff6b35'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Verifica tu conexión a internet.',
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