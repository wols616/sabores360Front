<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Mi Perfil | Sabores360</title>
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
            background: linear-gradient(135deg, var(--orange-bg) 0%, #feeee7 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.2);
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.15);
        }

        .btn-orange {
            background: linear-gradient(45deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
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

        .form-label {
            color: #666;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .alert-custom-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: none;
            color: #155724;
            border-radius: 10px;
        }

        .alert-custom-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            border: none;
            color: #721c24;
            border-radius: 10px;
        }

        .section-divider {
            height: 2px;
            background: linear-gradient(90deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            border-radius: 1px;
            margin: 2rem 0;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <?php $active = 'profile';
        require __DIR__ . '/_admin_nav.php'; ?>

        <div class="page-header text-center">
            <h1 class="mb-2">
                <i class="bi bi-person-circle"></i> Mi Perfil
            </h1>
            <p class="mb-0 opacity-75">Gestiona tu información personal y configuración de cuenta</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Profile Information Card -->
                <div class="card profile-card mb-4">
                    <div class="card-header bg-transparent border-0 text-center py-4">
                        <div class="profile-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <h4 class="text-orange mb-1">Información Personal</h4>
                        <small class="text-muted">Actualiza tus datos básicos</small>
                    </div>
                    <div class="card-body p-4">
                        <form id="profile-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-person text-orange me-2"></i>Nombre completo
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Ingresa tu nombre">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope text-orange me-2"></i>Correo electrónico
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="correo@example.com">
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">
                                        <i class="bi bi-geo-alt text-orange me-2"></i>Dirección
                                    </label>
                                    <textarea class="form-control" id="address" name="address" rows="3"
                                        placeholder="Ingresa tu dirección completa"></textarea>
                                </div>
                            </div>

                            <div id="profile-msg" class="mt-3"></div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-orange btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="card profile-card">
                    <div class="card-header bg-transparent border-0 text-center py-4">
                        <h4 class="text-orange mb-1">
                            <i class="bi bi-shield-lock"></i> Cambiar Contraseña
                        </h4>
                        <small class="text-muted">Mantén tu cuenta segura actualizando tu contraseña</small>
                    </div>
                    <div class="card-body p-4">
                        <form id="change-password-form">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="currentPassword" class="form-label">
                                        <i class="bi bi-key text-orange me-2"></i>Contraseña actual
                                    </label>
                                    <input type="password" class="form-control" id="currentPassword"
                                        name="currentPassword" required placeholder="Ingresa tu contraseña actual">
                                </div>
                                <div class="col-md-6">
                                    <label for="newPassword" class="form-label">
                                        <i class="bi bi-lock text-orange me-2"></i>Nueva contraseña
                                    </label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword"
                                        required minlength="8" placeholder="Mínimo 8 caracteres">
                                    <small class="text-muted">Mínimo 8 caracteres</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="confirmPassword" class="form-label">
                                        <i class="bi bi-lock-fill text-orange me-2"></i>Confirmar contraseña
                                    </label>
                                    <input type="password" class="form-control" id="confirmPassword"
                                        name="confirmPassword" required minlength="8"
                                        placeholder="Repite la nueva contraseña">
                                </div>
                            </div>

                            <div id="change-pass-message" class="mt-3"></div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-orange btn-lg">
                                    <i class="bi bi-shield-check me-2"></i>Cambiar Contraseña
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                </button>
                            </div>
                        </form>
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
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const form = document.getElementById('profile-form');
            const changeForm = document.getElementById('change-password-form');
            const changeMsg = document.getElementById('change-pass-message');
            const profileMsg = document.getElementById('profile-msg');

            function getCookie(name) {
                const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
                return v ? v.pop() : '';
            }

            function buildAuthHeaders() {
                const headers = { 'Content-Type': 'application/json' };
                if (!(window.SABORES360 && SABORES360.API)) {
                    const token = getCookie('auth_token');
                    if (token) headers['Authorization'] = 'Bearer ' + token;
                }
                return headers;
            }

            function showMessage(element, message, isSuccess = false) {
                if (!element) return;
                element.innerHTML = `
                    <div class="alert ${isSuccess ? 'alert-custom-success' : 'alert-custom-danger'} border-0 d-flex align-items-center">
                        <i class="bi ${isSuccess ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2"></i>
                        ${message}
                    </div>
                `;
                setTimeout(() => {
                    if (element.innerHTML) element.innerHTML = '';
                }, 5000);
            }

            function toggleLoadingButton(button, loading = false) {
                if (!button) return;
                const spinner = button.querySelector('.spinner-border');
                if (loading) {
                    button.disabled = true;
                    if (spinner) spinner.classList.remove('d-none');
                } else {
                    button.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                }
            }

            async function loadProfile() {
                const endpoints = ['auth/me', 'auth/profile', 'admin/profile'];
                for (const ep of endpoints) {
                    try {
                        let d;
                        if (window.SABORES360 && SABORES360.API) {
                            d = await SABORES360.API.get(ep);
                        } else {
                            const res = await fetch(base + ep, { credentials: 'include' });
                            if (!res.ok) {
                                console.warn('Profile endpoint', ep, 'status', res.status);
                                continue;
                            }
                            try {
                                d = await res.json();
                            } catch (e) {
                                d = null;
                            }
                        }
                        if (d && d.success && d.profile) return d.profile;
                        if (d && d.success && (d.user || d.data)) return d.user || d.data;
                        if (d && d.name) return d;
                    } catch (e) {
                        console.warn('Profile load error:', e);
                    }
                }
                return null;
            }

            // Load profile data
            try {
                const profile = await loadProfile();
                if (profile && form) {
                    form.name.value = profile.name || profile.fullName || '';
                    form.email.value = profile.email || profile.username || '';
                    form.address.value = profile.address || profile.addr || '';
                }
            } catch (e) {
                console.error('Error loading profile:', e);
            }

            // Profile form handler
            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const fd = new FormData(form);
                    const payload = {
                        name: fd.get('name'),
                        email: fd.get('email'),
                        address: fd.get('address')
                    };

                    const submitBtn = form.querySelector('button[type="submit"]');
                    toggleLoadingButton(submitBtn, true);
                    profileMsg.innerHTML = '';

                    try {
                        let d;
                        if (window.SABORES360 && SABORES360.API) {
                            d = await SABORES360.API.put('auth/profile', payload);
                        } else {
                            const res = await fetch(base + 'auth/profile', {
                                method: 'PUT',
                                credentials: 'include',
                                headers: buildAuthHeaders(),
                                body: JSON.stringify(payload)
                            });
                            try {
                                d = await res.json();
                            } catch (e) {
                                d = { success: false, message: 'server_error' };
                            }
                            if (res.status === 401 || res.status === 403) {
                                showMessage(profileMsg, 'No autorizado. Inicie sesión de nuevo.');
                                return;
                            }
                        }

                        if (d && d.success) {
                            showMessage(profileMsg, 'Perfil actualizado correctamente.', true);
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Tu perfil ha sido actualizado correctamente.',
                                confirmButtonColor: '#ff6b35'
                            });
                        } else {
                            const msg = (d && d.message) ? d.message : 'error';
                            let errorText = 'Error al actualizar perfil.';

                            if (msg === 'email_exists') {
                                errorText = 'El email ya está en uso por otro usuario.';
                            } else if (msg === 'validation_failed') {
                                errorText = 'Formato inválido. Revise los campos.';
                            } else if (d.message) {
                                errorText = d.message;
                            }

                            showMessage(profileMsg, errorText);
                        }
                    } catch (err) {
                        console.error('Profile update error:', err);
                        showMessage(profileMsg, 'Error de red al actualizar perfil.');
                    } finally {
                        toggleLoadingButton(submitBtn, false);
                    }
                });
            }

            // Change password form handler
            if (changeForm) {
                changeForm.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const fd = new FormData(changeForm);
                    const currentPassword = fd.get('currentPassword') || '';
                    const newPassword = fd.get('newPassword') || '';
                    const confirmPassword = fd.get('confirmPassword') || '';

                    // Validation
                    if (!currentPassword || !newPassword || !confirmPassword) {
                        showMessage(changeMsg, 'Por favor complete todos los campos.');
                        return;
                    }
                    if (newPassword.length < 8) {
                        showMessage(changeMsg, 'La nueva contraseña debe tener al menos 8 caracteres.');
                        return;
                    }
                    if (newPassword !== confirmPassword) {
                        showMessage(changeMsg, 'La confirmación no coincide con la nueva contraseña.');
                        return;
                    }

                    const payload = {
                        currentPassword: currentPassword,
                        newPassword: newPassword
                    };

                    const submitBtn = changeForm.querySelector('button[type="submit"]');
                    toggleLoadingButton(submitBtn, true);
                    changeMsg.innerHTML = '';

                    try {
                        let d;
                        if (window.SABORES360 && SABORES360.API) {
                            d = await SABORES360.API.post('auth/change-password', payload);
                        } else {
                            const res = await fetch(base + 'auth/change-password', {
                                method: 'POST',
                                credentials: 'include',
                                headers: buildAuthHeaders(),
                                body: JSON.stringify(payload)
                            });
                            try {
                                d = await res.json();
                            } catch (e) {
                                d = { success: false, message: 'server_error' };
                            }
                            if (res.status === 401 || res.status === 403) {
                                showMessage(changeMsg, 'No autorizado. Inicie sesión de nuevo.');
                                return;
                            }
                        }

                        if (d && d.success) {
                            showMessage(changeMsg, 'Contraseña actualizada correctamente.', true);
                            changeForm.reset();

                            Swal.fire({
                                icon: 'success',
                                title: '¡Contraseña actualizada!',
                                text: 'Tu contraseña ha sido cambiada exitosamente.',
                                confirmButtonColor: '#ff6b35'
                            });
                        } else {
                            const msg = (d && d.message) ? d.message : 'error';
                            let errorText = 'Error al cambiar la contraseña.';

                            if (msg === 'invalid_current_password') {
                                errorText = 'La contraseña actual no coincide.';
                            } else if (msg === 'validation_failed' || msg === 'new_password_too_short') {
                                errorText = 'La nueva contraseña no cumple las reglas de validación.';
                            } else if (d.message) {
                                errorText = d.message;
                            }

                            showMessage(changeMsg, errorText);
                        }
                    } catch (err) {
                        console.error('Password change error:', err);
                        showMessage(changeMsg, 'Error de red al cambiar la contraseña.');
                    } finally {
                        toggleLoadingButton(submitBtn, false);
                    }
                });
            }
        })();
    </script>
</body>

</html>