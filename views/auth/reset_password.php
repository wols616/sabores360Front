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
    <title>Restablecer contraseña - Sabores360</title>
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

        .reset-icon {
            font-size: 4rem;
            color: var(--orange-primary);
            opacity: 0.8;
        }

        .security-note {
            background: rgba(255, 107, 53, 0.1);
            border-left: 4px solid var(--orange-primary);
        }
    </style>
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="brand-logo mb-2">
                                <i class="bi bi-shop"></i> Sabores360
                            </h1>
                            <div class="reset-icon mb-3">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h4 class="text-orange mb-2">Restablecer Contraseña</h4>
                            <p class="text-muted">
                                Introduce el código que recibiste por correo y tu nueva contraseña
                            </p>
                        </div>

                        <div class="security-note p-3 rounded mb-4">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Por tu seguridad, este enlace expirará en 24 horas
                            </small>
                        </div>

                        <form id="reset-form">
                            <div class="mb-3">
                                <label for="token" class="form-label">
                                    <i class="bi bi-key"></i> Código de verificación
                                </label>
                                <input type="text" class="form-control form-control-lg" id="token" name="token" required
                                    placeholder="Código de 6 dígitos" maxlength="6" pattern="[0-9]{6}">
                                <div class="form-text">Revisa tu correo electrónico</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Nueva contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" required minlength="8" autocomplete="new-password">
                                <div class="form-text">Mínimo 8 caracteres</div>
                            </div>

                            <div class="mb-4">
                                <label for="confirm" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Confirmar contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg" id="confirm" name="confirm"
                                    required minlength="8" autocomplete="new-password">
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-orange btn-lg">
                                    <i class="bi bi-check-circle"></i> Restablecer contraseña
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0">
                                ¿No tienes el código?
                                <a href="/Sabores360/views/auth/forgot_password.php"
                                    class="text-orange text-decoration-none fw-bold">
                                    <i class="bi bi-arrow-repeat"></i> Solicitar nuevo código
                                </a>
                            </p>
                            <p class="mt-2 mb-0">
                                <a href="/Sabores360/views/auth/login.php" class="text-muted text-decoration-none">
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
        document.getElementById('reset-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Get form values
            const token = this.querySelector('[name="token"]').value.trim();
            const password = this.querySelector('[name="password"]').value;
            const confirm = this.querySelector('[name="confirm"]').value;

            // Client-side validation
            if (!token) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Código requerido',
                    text: 'Por favor ingresa el código de verificación que recibiste por correo.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#ff6b35'
                });
                return;
            }

            if (password.length < 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseña muy corta',
                    text: 'La contraseña debe tener al menos 8 caracteres.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#ff6b35'
                });
                return;
            }

            if (password !== confirm) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseñas no coinciden',
                    text: 'La confirmación de contraseña debe ser igual a la nueva contraseña.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#ff6b35'
                });
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Restableciendo...';

            const data = { token, password };

            try {
                const response = await SABORES360.API.post('auth/reset-password', data);
                if (response.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Contraseña restablecida!',
                        text: 'Tu contraseña ha sido cambiada exitosamente. Serás redirigido al inicio de sesión.',
                        confirmButtonText: 'Continuar',
                        confirmButtonColor: '#ff6b35',
                        timer: 3000,
                        timerProgressBar: true,
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
                        title: 'Error al restablecer',
                        text: response.message || 'No se pudo restablecer la contraseña. Verifica que el código sea correcto.',
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

        // Auto-format token input (only numbers, max 6 digits)
        document.getElementById('token').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });

        // Real-time password confirmation validation
        document.getElementById('confirm').addEventListener('input', function (e) {
            const password = document.getElementById('password').value;
            const confirm = this.value;

            if (confirm && password !== confirm) {
                this.setCustomValidity('Las contraseñas no coinciden');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                if (confirm) this.classList.add('is-valid');
            }
        });
    </script>
</body>

</html>