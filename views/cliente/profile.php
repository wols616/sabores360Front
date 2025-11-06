<?php
require __DIR__ . '/../../includes/auth_check.php';
require_auth();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cliente - Mi Perfil</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">

    <style>
        :root {
            --orange-primary: #ff6b35;
            --orange-secondary: #ff8c42;
            --orange-light: #ffeaa7;
            --orange-dark: #e55a2b;
        }

        body {
            background: linear-gradient(135deg, #fff4f0 0%, #feeee7 100%);
            min-height: 100vh;
        }

        .main-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .page-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.1);
            border-left: 5px solid var(--orange-primary);
        }

        .page-header h1 {
            color: var(--orange-primary);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-sections {
            display: grid;
            gap: 2rem;
        }

        .profile-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.1);
        }

        .section-title {
            color: var(--orange-primary);
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--orange-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--orange-dark), var(--orange-primary));
        }

        .btn-primary:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
            color: white;
        }

        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        .requirement {
            color: #6c757d;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .requirement.valid {
            color: #28a745;
        }

        .requirement.invalid {
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .profile-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php
    $active = 'profile';
    require __DIR__ . '/_cliente_nav.php';
    ?>

    <div class="main-container">
        <div class="page-header">
            <h1>
                <i class="bi bi-person-circle"></i>
                Mi Perfil
            </h1>
            <p class="text-muted mb-0">Gestiona tu información personal y configuración de cuenta</p>
        </div>

        <div class="profile-sections">
            <!-- Profile Information -->
            <div class="profile-section">
                <h3 class="section-title">
                    <i class="bi bi-person-gear"></i>
                    Información Personal
                </h3>

                <form id="profile-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person"></i>
                                    Nombre completo
                                </label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Ingresa tu nombre completo" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Correo electrónico
                                </label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="tu@email.com" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">
                            <i class="bi bi-geo-alt"></i>
                            Dirección
                        </label>
                        <textarea id="address" name="address" class="form-control" rows="3"
                            placeholder="Ingresa tu dirección completa..."></textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-check-circle"></i>
                            Guardar Cambios
                        </button>
                    </div>

                    <div id="profile-msg"></div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="profile-section">
                <h3 class="section-title">
                    <i class="bi bi-shield-lock"></i>
                    Seguridad de la Cuenta
                </h3>

                <form id="change-password-form">
                    <div class="form-group">
                        <label for="currentPassword" class="form-label">
                            <i class="bi bi-key"></i>
                            Contraseña actual
                        </label>
                        <input type="password" id="currentPassword" name="currentPassword" class="form-control"
                            placeholder="Ingresa tu contraseña actual" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="newPassword" class="form-label">
                                    <i class="bi bi-lock"></i>
                                    Nueva contraseña
                                </label>
                                <input type="password" id="newPassword" name="newPassword" class="form-control"
                                    placeholder="Nueva contraseña" minlength="8" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirmPassword" class="form-label">
                                    <i class="bi bi-lock-fill"></i>
                                    Confirmar contraseña
                                </label>
                                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control"
                                    placeholder="Confirma la nueva contraseña" minlength="8" required>
                            </div>
                        </div>
                    </div>

                    <div class="password-requirements">
                        <h6 class="mb-2">Requisitos de la contraseña:</h6>
                        <div class="requirement" id="req-length">
                            <i class="bi bi-x-circle"></i>
                            Mínimo 8 caracteres
                        </div>
                        <div class="requirement" id="req-match">
                            <i class="bi bi-x-circle"></i>
                            Las contraseñas coinciden
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-3">
                        <button type="submit" class="btn-secondary">
                            <i class="bi bi-shield-check"></i>
                            Cambiar Contraseña
                        </button>
                    </div>

                    <div id="change-pass-message"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // API base (use injected value if available, otherwise default to the same fallback as other pages)
        // Match the `cart.php` fallback so requests work in development and production environments.
        const apiBase = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';

        // Helper: read cookie by name
        function getCookie(name) {
            const match = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
            return match ? match.pop() : '';
        }

        // Try to find an auth token from multiple places (SABORES360 helper, localStorage, cookie)
        function getAuthToken() {
            try {
                if (window.SABORES360 && (SABORES360.AUTH_TOKEN || SABORES360.token)) return SABORES360.AUTH_TOKEN || SABORES360.token;
            } catch (e) { }
            const fromLocal = localStorage.getItem('auth_token');
            if (fromLocal) return fromLocal;
            return getCookie('auth_token');
        }

        function buildAuthHeaders() {
            const headers = { 'Content-Type': 'application/json' };
            const token = getAuthToken();
            if (token) headers['Authorization'] = 'Bearer ' + token;
            return headers;
        }

        // Load profile data on page load
        async function loadProfile() {
            try {
                // If a central SABORES360.API helper exists, prefer it (it usually injects auth)
                if (window.SABORES360 && SABORES360.API && typeof SABORES360.API.get === 'function') {
                    try {
                        const d = await SABORES360.API.get('auth/me');
                        if (d && d.success && d.user) {
                            const user = d.user;
                            document.getElementById('name').value = user.name || '';
                            document.getElementById('email').value = user.email || '';
                            document.getElementById('address').value = user.address || '';
                        }
                        return;
                    } catch (e) { /* fallthrough to fetch */ }
                }

                const opts = { method: 'GET', credentials: 'include', headers: buildAuthHeaders() };
                const response = await fetch(apiBase + 'auth/me', opts);

                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.user) {
                        const user = data.user;
                        document.getElementById('name').value = user.name || '';
                        document.getElementById('email').value = user.email || '';
                        document.getElementById('address').value = user.address || '';
                    }
                } else {
                    // non-ok: try to parse and log
                    try { const txt = await response.text(); console.warn('auth/me returned', response.status, txt); } catch (e) { }
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Profile form handler
        document.getElementById('profile-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const msgDiv = document.getElementById('profile-msg');

            const formData = new FormData(e.target);
            const profileData = {
                name: formData.get('name'),
                email: formData.get('email'),
                address: formData.get('address')
            };

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                Guardando...
            `;

            try {
                // Use SABORES360 API helper if available, otherwise fetch with auth header
                let response;
                if (window.SABORES360 && SABORES360.API && typeof SABORES360.API.put === 'function') {
                    try {
                        const d = await SABORES360.API.put('auth/profile', profileData);
                        response = { ok: !!d.success, _data: d };
                    } catch (e) {
                        console.error('SABORES360.API.put failed', e);
                        response = null;
                    }
                }

                if (!response) {
                    response = await fetch(apiBase + 'auth/profile', {
                        method: 'PUT',
                        headers: buildAuthHeaders(),
                        credentials: 'include',
                        body: JSON.stringify(profileData)
                    });
                }

                const data = response._data ? response._data : await (response.json ? response.json() : Promise.resolve({ success: false }));

                if (data.success) {
                    msgDiv.innerHTML = `
                    <div class="alert alert-success alert-custom">
                        <i class="bi bi-check-circle"></i>
                        Perfil actualizado correctamente.
                    </div>
                    `;
                } else {
                    let errorMessage = 'Error al actualizar el perfil.';

                    if (data.message === 'email_exists') {
                        errorMessage = 'El email ya está en uso por otro usuario.';
                    } else if (data.message === 'validation_failed') {
                        errorMessage = 'Por favor, verifica que todos los campos sean válidos.';
                    } else if (data.message) {
                        errorMessage = data.message;
                    }

                    msgDiv.innerHTML = `
                    <div class="alert alert-danger alert-custom">
                        <i class="bi bi-exclamation-triangle"></i>
                        ${errorMessage}
                    </div>
                    `;
                }
            } catch (error) {
                console.error('Profile update error:', error);
                msgDiv.innerHTML = `
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-wifi-off"></i>
                    Error de conexión. Por favor, intenta nuevamente.
                </div>
                `;
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <i class="bi bi-check-circle"></i>
                    Guardar Cambios
                `;
            }
        });

        // Password requirements validation
        function validatePassword() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            const lengthReq = document.getElementById('req-length');
            const matchReq = document.getElementById('req-match');

            // Length requirement
            if (newPassword.length >= 8) {
                lengthReq.classList.remove('invalid');
                lengthReq.classList.add('valid');
                lengthReq.innerHTML = '<i class="bi bi-check-circle"></i> Mínimo 8 caracteres';
            } else {
                lengthReq.classList.remove('valid');
                lengthReq.classList.add('invalid');
                lengthReq.innerHTML = '<i class="bi bi-x-circle"></i> Mínimo 8 caracteres';
            }

            // Match requirement
            if (newPassword && confirmPassword && newPassword === confirmPassword) {
                matchReq.classList.remove('invalid');
                matchReq.classList.add('valid');
                matchReq.innerHTML = '<i class="bi bi-check-circle"></i> Las contraseñas coinciden';
            } else if (confirmPassword) {
                matchReq.classList.remove('valid');
                matchReq.classList.add('invalid');
                matchReq.innerHTML = '<i class="bi bi-x-circle"></i> Las contraseñas coinciden';
            } else {
                matchReq.classList.remove('valid', 'invalid');
                matchReq.innerHTML = '<i class="bi bi-x-circle"></i> Las contraseñas coinciden';
            }
        }

        // Add event listeners for password validation
        document.getElementById('newPassword').addEventListener('input', validatePassword);
        document.getElementById('confirmPassword').addEventListener('input', validatePassword);

        // Change password form handler
        document.getElementById('change-password-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const msgDiv = document.getElementById('change-pass-message');

            const formData = new FormData(e.target);
            const currentPassword = formData.get('currentPassword');
            const newPassword = formData.get('newPassword');
            const confirmPassword = formData.get('confirmPassword');

            // Validation
            if (!currentPassword || !newPassword || !confirmPassword) {
                msgDiv.innerHTML = `
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-exclamation-triangle"></i>
                    Por favor, completa todos los campos.
                </div>
                `;
                return;
            }

            if (newPassword.length < 8) {
                msgDiv.innerHTML = `
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-exclamation-triangle"></i>
                    La nueva contraseña debe tener al menos 8 caracteres.
                </div>
                `;
                return;
            }

            if (newPassword !== confirmPassword) {
                msgDiv.innerHTML = `
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-exclamation-triangle"></i>
                    Las contraseñas no coinciden.
                </div>
                `;
                return;
            }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                Cambiando...
            `;

            const passwordData = {
                currentPassword: currentPassword,
                newPassword: newPassword
            };

            try {
                // Prefer SABORES360 helper, otherwise use fetch with auth header
                let response;
                if (window.SABORES360 && SABORES360.API && typeof SABORES360.API.post === 'function') {
                    try {
                        const d = await SABORES360.API.post('auth/change-password', passwordData);
                        response = { ok: !!d.success, _data: d };
                    } catch (e) {
                        console.error('SABORES360.API.post failed', e);
                        response = null;
                    }
                }

                if (!response) {
                    response = await fetch(apiBase + 'auth/change-password', {
                        method: 'POST',
                        headers: buildAuthHeaders(),
                        credentials: 'include',
                        body: JSON.stringify(passwordData)
                    });
                }

                const data = response._data ? response._data : await (response.json ? response.json() : Promise.resolve({ success: false }));

                if (data.success) {
                    msgDiv.innerHTML = `
                    <div class="alert alert-success alert-custom">
                        <i class="bi bi-check-circle"></i>
                        Contraseña actualizada correctamente.
                    </div>
                    `;

                    // Reset form
                    e.target.reset();
                    validatePassword(); // Reset validation indicators
                } else {
                    let errorMessage = 'Error al cambiar la contraseña.';

                    if (data.message === 'invalid_current_password') {
                        errorMessage = 'La contraseña actual no es correcta.';
                    } else if (data.message === 'validation_failed') {
                        errorMessage = 'La nueva contraseña no cumple con los requisitos.';
                    } else if (data.message) {
                        errorMessage = data.message;
                    }

                    msgDiv.innerHTML = `
                    <div class="alert alert-danger alert-custom">
                        <i class="bi bi-exclamation-triangle"></i>
                        ${errorMessage}
                    </div>
                    `;
                }
            } catch (error) {
                console.error('Password change error:', error);
                msgDiv.innerHTML = `
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-wifi-off"></i>
                    Error de conexión. Por favor, intenta nuevamente.
                </div>
                `;
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <i class="bi bi-shield-check"></i>
                    Cambiar Contraseña
                `;
            }
        });

        // Load profile data on page load
        document.addEventListener('DOMContentLoaded', loadProfile);
    </script>
</body>

</html>