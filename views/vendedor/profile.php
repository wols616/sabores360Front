<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Mi Perfil</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            text-align: center;
        }

        .page-header h1 {
            color: var(--orange-primary);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 1rem;
            position: relative;
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.1);
            border-top: 3px solid var(--orange-primary);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 1rem 1.5rem;
            margin: -2rem -2rem 1.5rem -2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .form-label {
            color: var(--orange-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .btn-primary-custom:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary-custom {
            background: white;
            border: 2px solid var(--orange-primary);
            color: var(--orange-primary);
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: var(--orange-primary);
            color: white;
            transform: translateY(-2px);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            color: var(--orange-primary);
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--orange-primary);
            background: rgba(255, 107, 53, 0.1);
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak .strength-fill {
            width: 33%;
            background: #dc3545;
        }

        .strength-medium .strength-fill {
            width: 66%;
            background: #ffc107;
        }

        .strength-strong .strength-fill {
            width: 100%;
            background: #28a745;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(255, 107, 53, 0.1);
            border-top: 3px solid var(--orange-primary);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--orange-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e9ecef;
            border-top: 3px solid var(--orange-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
        }

        .alert-success-custom {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger-custom {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>

<body>
    <?php
    $active = 'profile';
    require __DIR__ . '/_vendedor_nav.php';
    ?>

    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="profile-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <h1>
                <i class="bi bi-person-gear"></i>
                Mi Perfil
            </h1>
            <p class="text-muted mb-0">Gestiona tu información personal y configuración</p>
        </div>

        <!-- Profile Stats -->
        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-value">Activo</div>
                <div class="stat-label">Estado</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="stat-value">Vendedor</div>
                <div class="stat-label">Rol</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-value" id="lastLogin">--</div>
                <div class="stat-label">Último Acceso</div>
            </div>
        </div>

        <!-- Profile Information Form -->
        <div class="profile-card">
            <div class="card-header-custom">
                <i class="bi bi-person-lines-fill"></i>
                Información Personal
            </div>

            <form id="profile-form">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person"></i>
                            Nombre completo
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Tu nombre completo">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i>
                            Correo electrónico
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="tu@email.com">
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">
                            <i class="bi bi-geo-alt"></i>
                            Dirección
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-geo-alt"></i>
                            </span>
                            <textarea class="form-control" id="address" name="address" rows="3"
                                placeholder="Tu dirección completa"></textarea>
                        </div>
                    </div>
                </div>

                <div id="profile-msg" class="alert-custom" style="display: none;"></div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary-custom me-2" onclick="resetForm()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Restablecer
                    </button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-save"></i>
                        Guardar Cambios
                    </button>
                </div>

                <div class="loading-overlay" id="profile-loading">
                    <div class="loading-spinner"></div>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="profile-card">
            <div class="card-header-custom">
                <i class="bi bi-shield-lock"></i>
                Cambiar Contraseña
            </div>

            <form id="change-password-form">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="currentPassword" class="form-label">
                            <i class="bi bi-lock"></i>
                            Contraseña actual
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                                required>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePassword('currentPassword')">
                                <i class="bi bi-eye" id="currentPassword-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="newPassword" class="form-label">
                            <i class="bi bi-key"></i>
                            Nueva contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-key"></i>
                            </span>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required
                                minlength="8">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePassword('newPassword')">
                                <i class="bi bi-eye" id="newPassword-icon"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="password-strength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill"></div>
                            </div>
                            <small class="strength-text text-muted">Fortaleza de la contraseña</small>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="confirmPassword" class="form-label">
                            <i class="bi bi-check-circle"></i>
                            Confirmar contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-check-circle"></i>
                            </span>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                required minlength="8">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePassword('confirmPassword')">
                                <i class="bi bi-eye" id="confirmPassword-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="change-pass-message" class="alert-custom" style="display: none;"></div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary-custom me-2" onclick="resetPasswordForm()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-shield-check"></i>
                        Cambiar Contraseña
                    </button>
                </div>

                <div class="loading-overlay" id="password-loading">
                    <div class="loading-spinner"></div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php require __DIR__ . '/../../includes/print_api_js.php'; ?>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        let originalProfileData = {};

        // Helper functions
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

        function showMessage(elementId, message, type = 'success') {
            const element = document.getElementById(elementId);
            if (!element) return;

            element.style.display = 'block';
            element.className = `alert-custom alert-${type === 'success' ? 'success' : 'danger'}-custom`;
            element.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                ${message}
            `;

            // Use SweetAlert2 for better notifications
            if (type === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    timer: 5000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }

            setTimeout(() => {
                element.style.display = 'none';
            }, 5000);
        }

        function toggleLoadingOverlay(overlayId, show) {
            const overlay = document.getElementById(overlayId);
            if (overlay) {
                if (show) {
                    overlay.classList.add('show');
                } else {
                    overlay.classList.remove('show');
                }
            }
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        function checkPasswordStrength(password) {
            const strengthEl = document.getElementById('password-strength');
            if (!strengthEl) return;

            if (!password) {
                strengthEl.style.display = 'none';
                return;
            }

            strengthEl.style.display = 'block';

            let score = 0;
            if (password.length >= 8) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            const strengthBar = strengthEl.querySelector('.strength-bar');
            const strengthText = strengthEl.querySelector('.strength-text');

            strengthBar.className = 'strength-bar';

            if (score < 3) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Contraseña débil';
                strengthText.className = 'strength-text text-danger';
            } else if (score < 5) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Contraseña media';
                strengthText.className = 'strength-text text-warning';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Contraseña fuerte';
                strengthText.className = 'strength-text text-success';
            }
        }

        function resetForm() {
            const form = document.getElementById('profile-form');
            if (form && originalProfileData) {
                form.name.value = originalProfileData.name || '';
                form.email.value = originalProfileData.email || '';
                form.address.value = originalProfileData.address || '';
            }
        }

        function resetPasswordForm() {
            const form = document.getElementById('change-password-form');
            if (form) {
                form.reset();
                document.getElementById('password-strength').style.display = 'none';
            }
        }

        async function loadProfile() {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const endpoints = ['auth/me', 'client/profile', 'auth/profile', 'seller/profile'];

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
                        try { d = await res.json(); } catch (e) { d = null; }
                    }

                    if (d && d.success && d.profile) return d.profile;
                    if (d && d.success && (d.user || d.data)) return d.user || d.data;
                    if (d && d.name) return d;
                } catch (e) {
                    console.warn('Error loading profile from', ep, e);
                }
            }
            return null;
        }

        // Initialize page
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const form = document.getElementById('profile-form');
            const changeForm = document.getElementById('change-password-form');

            // Load profile data
            try {
                const profile = await loadProfile();
                if (profile && form) {
                    originalProfileData = {
                        name: profile.name || profile.fullName || '',
                        email: profile.email || profile.username || '',
                        address: profile.address || profile.addr || ''
                    };

                    form.name.value = originalProfileData.name;
                    form.email.value = originalProfileData.email;
                    form.address.value = originalProfileData.address;

                    // Update last login if available
                    if (profile.lastLogin) {
                        const lastLoginEl = document.getElementById('lastLogin');
                        if (lastLoginEl) {
                            const date = new Date(profile.lastLogin);
                            lastLoginEl.textContent = date.toLocaleDateString('es-ES');
                        }
                    }
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

                    // Validation
                    if (!payload.name.trim()) {
                        showMessage('profile-msg', 'El nombre es requerido', 'error');
                        return;
                    }

                    if (!payload.email.trim()) {
                        showMessage('profile-msg', 'El email es requerido', 'error');
                        return;
                    }

                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;
                    toggleLoadingOverlay('profile-loading', true);

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
                            try { d = await res.json(); } catch (e) { d = { success: false, message: 'server_error' }; }

                            if (res.status === 401 || res.status === 403) {
                                showMessage('profile-msg', 'No autorizado. Inicie sesión de nuevo.', 'error');
                                return;
                            }
                        }

                        if (d && d.success) {
                            showMessage('profile-msg', 'Perfil actualizado correctamente', 'success');
                            // Update original data
                            originalProfileData = { ...payload };
                        } else {
                            const msg = (d && d.message) ? d.message : 'error';
                            let errorMessage = 'Error al actualizar perfil';

                            if (msg === 'email_exists') {
                                errorMessage = 'El email ya está en uso por otro usuario';
                            } else if (msg === 'validation_failed') {
                                errorMessage = 'Formato inválido. Revise los campos';
                            } else if (d.message) {
                                errorMessage = d.message;
                            }

                            showMessage('profile-msg', errorMessage, 'error');
                        }
                    } catch (err) {
                        console.error('Profile update error:', err);
                        showMessage('profile-msg', 'Error de conexión al actualizar perfil', 'error');
                    } finally {
                        if (submitBtn) submitBtn.disabled = false;
                        toggleLoadingOverlay('profile-loading', false);
                    }
                });
            }

            // Change password form handler
            if (changeForm) {
                // Password strength checker
                const newPasswordInput = document.getElementById('newPassword');
                if (newPasswordInput) {
                    newPasswordInput.addEventListener('input', (e) => {
                        checkPasswordStrength(e.target.value);
                    });
                }

                changeForm.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const fd = new FormData(changeForm);
                    const currentPassword = fd.get('currentPassword') || '';
                    const newPassword = fd.get('newPassword') || '';
                    const confirmPassword = fd.get('confirmPassword') || '';

                    // Validation
                    if (!currentPassword || !newPassword || !confirmPassword) {
                        showMessage('change-pass-message', 'Por favor complete todos los campos', 'error');
                        return;
                    }

                    if (newPassword.length < 8) {
                        showMessage('change-pass-message', 'La nueva contraseña debe tener al menos 8 caracteres', 'error');
                        return;
                    }

                    if (newPassword !== confirmPassword) {
                        showMessage('change-pass-message', 'Las contraseñas no coinciden', 'error');
                        return;
                    }

                    const payload = { currentPassword, newPassword };
                    const submitBtn = changeForm.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;
                    toggleLoadingOverlay('password-loading', true);

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
                            try { d = await res.json(); } catch (e) { d = { success: false, message: 'server_error' }; }

                            if (res.status === 401 || res.status === 403) {
                                showMessage('change-pass-message', 'No autorizado. Inicie sesión de nuevo.', 'error');
                                return;
                            }
                        }

                        if (d && d.success) {
                            showMessage('change-pass-message', 'Contraseña actualizada correctamente', 'success');
                            changeForm.reset();
                            document.getElementById('password-strength').style.display = 'none';
                        } else {
                            const msg = (d && d.message) ? d.message : 'error';
                            let errorMessage = 'Error al cambiar la contraseña';

                            if (msg === 'invalid_current_password') {
                                errorMessage = 'La contraseña actual no es correcta';
                            } else if (msg === 'validation_failed' || msg === 'new_password_too_short') {
                                errorMessage = 'La nueva contraseña no cumple los requisitos';
                            } else if (d.message) {
                                errorMessage = d.message;
                            }

                            showMessage('change-pass-message', errorMessage, 'error');
                        }
                    } catch (err) {
                        console.error('Password change error:', err);
                        showMessage('change-pass-message', 'Error de conexión al cambiar la contraseña', 'error');
                    } finally {
                        if (submitBtn) submitBtn.disabled = false;
                        toggleLoadingOverlay('password-loading', false);
                    }
                });
            }
        })();

        // Make functions available globally
        window.togglePassword = togglePassword;
        window.resetForm = resetForm;
        window.resetPasswordForm = resetPasswordForm;
    </script>
</body>

</html>