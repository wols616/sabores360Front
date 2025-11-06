<?php
require __DIR__ . '/../../includes/auth_check.php';
require_auth();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Mi perfil</h1>
                <?php require __DIR__ . '/../_navbar.php'; ?>
    </header>

    <main>
        <form id="profile-form">
            <label>Nombre<br><input name="name"></label><br>
            <label>Email<br><input name="email" type="email"></label><br>
            <label>Dirección<br><textarea name="address"></textarea></label><br>
            <div id="profile-msg" aria-live="polite" style="color:#a00;margin:6px 0;"></div>
            <button type="submit">Guardar</button>
        </form>

        <section style="margin-top:2rem;">
            <h2>Cambiar contraseña</h2>
            <form id="change-password-form">
                <label>Contraseña actual<br><input name="currentPassword" type="password" required></label><br>
                <label>Nueva contraseña (mín. 8 caracteres)<br><input name="newPassword" type="password" required
                        minlength="8"></label><br>
                <label>Confirmar nueva contraseña<br><input name="confirmPassword" type="password" required
                        minlength="8"></label><br>
                <div id="change-pass-message" aria-live="polite" style="color:#a00;margin:6px 0;"></div>
                <button type="submit">Cambiar contraseña</button>
            </form>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const form = document.getElementById('profile-form');
            const changeForm = document.getElementById('change-password-form');
            const changeMsg = document.getElementById('change-pass-message');

            function getCookie(name) {
                const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
                return v ? v.pop() : '';
            }

            function buildAuthHeaders() {
                // Prefer SABORES360.API for requests (it injects Authorization). If not present, try cookie fallback.
                const headers = { 'Content-Type': 'application/json' };
                if (!(window.SABORES360 && SABORES360.API)) {
                    const token = getCookie('auth_token');
                    if (token) headers['Authorization'] = 'Bearer ' + token;
                }
                return headers;
            }

            // load profile: try several endpoints (prefer central auth/profile)
            async function loadProfile() {
                // Try the safest/read endpoints first. Avoid calling PUT-only endpoints like auth/profile.
                const endpoints = ['auth/me', 'client/profile'];
                for (const ep of endpoints) {
                    try {
                        let d;
                        if (window.SABORES360 && SABORES360.API) {
                            // try the helper which knows base and auth
                            d = await SABORES360.API.get(ep);
                        } else {
                            const res = await fetch(base + ep, { credentials: 'include' });
                            const text = await res.text();
                            try { d = JSON.parse(text); } catch (e) { d = { success: res.ok, httpStatus: res.status, raw: text }; }
                            // if server returned 5xx or non-ok, skip this endpoint quietly
                            if (!res.ok) {
                                console.warn('Profile endpoint', ep, 'returned status', res.status);
                                d = null;
                            }
                        }
                        if (d && d.success && d.profile) return d.profile;
                        // Some endpoints may return the user directly (e.g. auth/me)
                        if (d && d.success && (d.user || d.data)) return d.user || d.data;
                        if (d && d.name && (d.email || d.username)) return d; // raw user object
                    } catch (e) { /* try next */ }
                }
                return null;
            }

            const profileMsg = document.getElementById('profile-msg');
            try {
                const profile = await loadProfile();
                if (profile && form) {
                    form.name.value = profile.name || profile.fullName || '';
                    form.email.value = profile.email || profile.username || '';
                    form.address.value = profile.address || profile.addr || '';
                }
            } catch (e) { /* ignore */ }

            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (profileMsg) { profileMsg.style.color = '#a00'; profileMsg.textContent = ''; }
                    const fd = new FormData(form);
                    const payload = { name: fd.get('name'), email: fd.get('email'), address: fd.get('address') };
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;
                    try {
                        let d;
                        if (window.SABORES360 && SABORES360.API) {
                            d = await SABORES360.API.put('auth/profile', payload);
                        } else {
                            const res = await fetch(base + 'auth/profile', { method: 'PUT', credentials: 'include', headers: buildAuthHeaders(), body: JSON.stringify(payload) });
                            try { d = await res.json(); } catch (e) { d = { success: false, message: 'server_error' }; }
                            if (res.status === 401 || res.status === 403) {
                                if (profileMsg) profileMsg.textContent = 'No autorizado. Inicie sesión de nuevo.';
                                return;
                            }
                        }

                        if (d && d.success) {
                            if (profileMsg) { profileMsg.style.color = '#080'; profileMsg.textContent = 'Perfil actualizado.'; }
                        } else {
                            const msg = (d && d.message) ? d.message : 'error';
                            if (profileMsg) profileMsg.style.color = '#a00';
                            if (msg === 'email_exists') {
                                if (profileMsg) profileMsg.textContent = 'El email ya está en uso por otro usuario.';
                            } else if (msg === 'validation_failed') {
                                if (profileMsg) profileMsg.textContent = 'Formato inválido. Revise los campos.';
                            } else {
                                if (profileMsg) profileMsg.textContent = d.message || 'Error al actualizar perfil.';
                            }
                        }
                    } catch (err) {
                        if (profileMsg) { profileMsg.style.color = '#a00'; profileMsg.textContent = 'Error de red al actualizar perfil.'; }
                    } finally {
                        if (submitBtn) submitBtn.disabled = false;
                    }
                });
            }

            // change password handler
            if (changeForm) {
                changeForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    changeMsg.textContent = '';
                    const fd = new FormData(changeForm);
                    const currentPassword = fd.get('currentPassword') || '';
                    const newPassword = fd.get('newPassword') || '';
                    const confirmPassword = fd.get('confirmPassword') || '';

                    if (!currentPassword || !newPassword || !confirmPassword) {
                        changeMsg.textContent = 'Por favor complete todos los campos.';
                        return;
                    }
                    if (newPassword.length < 8) {
                        changeMsg.textContent = 'La nueva contraseña debe tener al menos 8 caracteres.';
                        return;
                    }
                    if (newPassword !== confirmPassword) {
                        changeMsg.textContent = 'La confirmación no coincide con la nueva contraseña.';
                        return;
                    }

                    const payload = { currentPassword: currentPassword, newPassword: newPassword };
                    const submitBtn = changeForm.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;
                    try {
                        let d;
                        if (window.SABORES360 && SABORES360.API) {
                            // preferred central helper
                            d = await SABORES360.API.post('auth/change-password', payload);
                        } else {
                            const res = await fetch(base + 'auth/change-password', { method: 'POST', credentials: 'include', headers: buildAuthHeaders(), body: JSON.stringify(payload) });
                            // try to parse json even for non-2xx
                            try { d = await res.json(); } catch (e) { d = { success: false, message: 'server_error' }; }
                            if (res.status === 401 || res.status === 403) {
                                changeMsg.textContent = 'No autorizado. Inicie sesión de nuevo.';
                                return;
                            }
                        }

                        if (d && d.success) {
                            changeMsg.style.color = '#080';
                            changeMsg.textContent = 'Contraseña actualizada correctamente.';
                            changeForm.reset();
                        } else {
                            changeMsg.style.color = '#a00';
                            const msg = (d && d.message) ? d.message : 'error';
                            // map known server messages to friendly text
                            if (msg === 'invalid_current_password') changeMsg.textContent = 'La contraseña actual no coincide.';
                            else if (msg === 'validation_failed' || msg === 'new_password_too_short') changeMsg.textContent = 'La nueva contraseña no cumple las reglas de validación.';
                            else changeMsg.textContent = d.message || 'Error al cambiar la contraseña.';
                        }
                    } catch (err) {
                        changeMsg.style.color = '#a00';
                        changeMsg.textContent = 'Error de red al cambiar la contraseña.';
                    } finally {
                        if (submitBtn) submitBtn.disabled = false;
                    }
                });
            }
        })();
    </script>
</body>

</html>