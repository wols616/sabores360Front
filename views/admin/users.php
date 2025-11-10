<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Usuarios | Sabores360</title>
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

        .users-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
        }

        .user-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 107, 53, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.15);
            border-color: var(--orange-primary);
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

        .form-control:focus,
        .form-select:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .text-orange {
            color: var(--orange-primary) !important;
        }

        .role-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }

        .btn-action {
            padding: 0.4rem 0.8rem;
            margin: 0.2rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(45deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border-radius: 15px 15px 0 0;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <?php $active = 'users';
        require __DIR__ . '/_admin_nav.php'; ?>

        <div class="page-header text-center">
            <h1 class="mb-2">
                <i class="bi bi-people"></i> Gestión de Usuarios
            </h1>
            <p class="mb-0 opacity-75">Administra los usuarios del sistema</p>
        </div>

        <!-- Search and Filters -->
        <div class="card mb-4"
            style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 text-orange">
                    <i class="bi bi-funnel"></i> Búsqueda y Filtros
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Search Bar -->
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-search"></i> Buscar usuarios
                        </label>
                        <input type="text" class="form-control" id="search-input"
                            placeholder="Buscar por nombre, email...">
                    </div>

                    <!-- Role Filter -->
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="bi bi-person-badge"></i> Rol
                        </label>
                        <select class="form-select" id="role-filter">
                            <option value="">Todos los roles</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="bi bi-toggle-on"></i> Estado
                        </label>
                        <select class="form-select" id="status-filter">
                            <option value="">Todos</option>
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>

                    <!-- Clear and Results -->
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2 align-items-center">
                            <button class="btn btn-outline-orange btn-sm" id="clear-filters">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </button>
                            <span class="badge bg-secondary" id="results-count">0 resultados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card users-card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-person-lines-fill text-orange"></i> Lista de Usuarios
                </h5>
                <button id="new-user" class="btn btn-orange">
                    <i class="bi bi-person-plus"></i> Nuevo Usuario
                </button>
            </div>
            <div class="card-body">
                <div id="users-list">
                    <div class="text-center py-5">
                        <div class="spinner-border text-orange" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando usuarios...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php require __DIR__ . '/../../includes/print_api_js.php'; ?>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('users-list');

            // Global variables for filtering
            let allUsers = [];
            let allRoles = [];
            let filteredUsers = [];

            // Get filter elements
            const searchInput = document.getElementById('search-input');
            const roleFilter = document.getElementById('role-filter');
            const statusFilter = document.getElementById('status-filter');
            const clearFiltersBtn = document.getElementById('clear-filters');
            const resultsCount = document.getElementById('results-count');

            function populateRoleFilter() {
                const roleSelect = document.getElementById('role-filter');
                const currentValue = roleSelect.value;

                // Clear existing options except default
                roleSelect.innerHTML = '<option value="">Todos los roles</option>';

                // Add role options
                allRoles.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role.id;
                    option.textContent = role.name || role.label || role.title || `Rol ${role.id}`;
                    roleSelect.appendChild(option);
                });

                // Restore previous selection if still valid
                if (currentValue) {
                    roleSelect.value = currentValue;
                }
            }

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const roleValue = roleFilter.value;
                const statusValue = statusFilter.value;

                filteredUsers = allUsers.filter(user => {
                    // Search filter (name, email)
                    if (searchTerm) {
                        const name = String(user.name || user.fullName || user.username || '').toLowerCase();
                        const email = String(user.email || '').toLowerCase();
                        const id = String(user.id || user.userId || '').toLowerCase();
                        const searchMatch = name.includes(searchTerm) ||
                            email.includes(searchTerm) ||
                            id.includes(searchTerm);
                        if (!searchMatch) return false;
                    }

                    // Role filter
                    if (roleValue) {
                        const userRoleId = String(user.roleId || user.role_id || user.role?.id || '');
                        if (userRoleId !== roleValue) return false;
                    }

                    // Status filter
                    if (statusValue) {
                        const isActive = (typeof user.isActive !== 'undefined') ?
                            user.isActive :
                            (typeof user.active !== 'undefined' ? user.active : true);

                        if (statusValue === 'active' && !isActive) return false;
                        if (statusValue === 'inactive' && isActive) return false;
                    }

                    return true;
                });

                renderUsers();
            }

            function renderUsers() {
                // Update results count
                resultsCount.textContent = `${filteredUsers.length} resultado${filteredUsers.length !== 1 ? 's' : ''}`;

                if (!filteredUsers || !filteredUsers.length) {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="bi bi-person-x display-4 text-muted"></i>
                            <p class="mt-3 text-muted">${allUsers.length > 0 ? 'No se encontraron usuarios con los filtros aplicados' : 'No hay usuarios registrados'}</p>
                            ${allUsers.length > 0 ? '<button class="btn btn-outline-orange btn-sm mt-2" onclick="clearAllFilters()"><i class="bi bi-x-circle"></i> Limpiar Filtros</button>' : ''}
                        </div>
                    `;
                    return;
                }

                const userCards = filteredUsers.map(u => {
                    const id = u.id || u.userId || '';
                    const name = u.name || u.fullName || u.username || '';
                    const email = u.email || '';
                    const roleLabel = (u.role && (u.role.name || u.role.label)) || u.role_name || u.role || '';
                    const active = (typeof u.isActive !== 'undefined') ? u.isActive : (typeof u.active !== 'undefined' ? u.active : true);

                    // Role badge color
                    const roleColor = roleLabel.toLowerCase() === 'admin' ? 'danger' :
                        roleLabel.toLowerCase() === 'vendedor' ? 'warning' : 'primary';

                    return `
                        <div class="user-card p-3" data-id="${id}">
                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    <div class="text-center">
                                        <i class="bi bi-person-circle display-6 text-orange"></i>
                                        <small class="badge bg-secondary d-block mt-1">#${id}</small>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="mb-1 fw-bold">${name}</h6>
                                    <p class="mb-1 text-muted">
                                        <i class="bi bi-envelope"></i> ${email}
                                    </p>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-${roleColor} role-badge">
                                            <i class="bi bi-shield"></i> ${roleLabel}
                                        </span>
                                        <span class="badge ${active ? 'bg-success' : 'bg-secondary'} status-badge">
                                            <i class="bi bi-${active ? 'check-circle' : 'x-circle'}"></i> ${active ? 'Activo' : 'Inactivo'}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button class="btn btn-sm btn-outline-primary btn-action edit" data-id="${id}" title="Editar usuario">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-${active ? 'warning' : 'success'} btn-action toggle" data-id="${id}" title="${active ? 'Desactivar' : 'Activar'} usuario">
                                        <i class="bi bi-${active ? 'pause' : 'play'}"></i> ${active ? 'Desactivar' : 'Activar'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                container.innerHTML = userCards;
            }

            async function loadRoles() {
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/roles') : (async () => { const r = await fetch(base + 'admin/roles', { credentials: 'include' }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    let list = [];
                    if (d && d.success) {
                        if (Array.isArray(d.roles)) list = d.roles;
                        else if (d.data && Array.isArray(d.data.roles)) list = d.data.roles;
                        else if (Array.isArray(d.data)) list = d.data;
                    }
                    allRoles = list;

                    // Update filter dropdown
                    populateRoleFilter();
                } catch (e) {
                    console.error('loadRoles error', e);
                    allRoles = [];
                }
            }

            async function loadUsers() {
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/users') : (async () => { const res = await fetch(base + 'admin/users', { credentials: 'include' }); const t = await res.text(); try { return JSON.parse(t); } catch (e) { return { success: res.ok, raw: t } } })());
                    let list = [];
                    if (d && d.success) {
                        if (Array.isArray(d.users)) list = d.users;
                        else if (Array.isArray(d.data)) list = d.data;
                        else if (d.data && Array.isArray(d.data.users)) list = d.data.users;
                        else if (Array.isArray(d.items)) list = d.items;
                    }
                    allUsers = list;
                    filteredUsers = [...allUsers];
                } catch (err) {
                    allUsers = [];
                    filteredUsers = [];
                    console.error('loadUsers error', err);
                }
                renderUsers();
            }

            // user modal
            const modalHtml = `
            <div class="modal fade" id="user-modal" tabindex="-1" aria-labelledby="user-title" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="user-title">
                                <i class="bi bi-person-plus"></i> Nuevo usuario
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="user-form">
                                <input type="hidden" name="id">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-person"></i> Nombre completo
                                        </label>
                                        <input name="name" class="form-control" required placeholder="Nombre y apellidos">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-envelope"></i> Email
                                        </label>
                                        <input name="email" type="email" class="form-control" required placeholder="correo@ejemplo.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-key"></i> Contraseña
                                        </label>
                                        <input name="password" type="password" class="form-control" placeholder="Dejar vacío para mantener actual">
                                        <div class="form-text">Mínimo 6 caracteres</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-shield-check"></i> Rol
                                        </label>
                                        <select name="roleId" class="form-select">
                                            <option value="">Seleccionar rol...</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">
                                            <i class="bi bi-geo-alt"></i> Dirección
                                        </label>
                                        <input name="address" class="form-control" placeholder="Dirección completa (opcional)">
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input name="isActive" type="checkbox" class="form-check-input" id="isActiveSwitch">
                                            <label class="form-check-label" for="isActiveSwitch">
                                                <i class="bi bi-toggle-on"></i> Usuario activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                            <button type="submit" form="user-form" class="btn btn-orange">
                                <i class="bi bi-check-circle"></i> Guardar Usuario
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('user-modal'));
            const modalElement = document.getElementById('user-modal');
            const form = document.getElementById('user-form');
            const title = document.getElementById('user-title');

            function fillRoleOptions() {
                const sel = form.querySelector('[name="roleId"]');
                if (!sel) return;
                if (!allRoles || !allRoles.length) { sel.innerHTML = '<option value="">(sin roles)</option>'; return; }
                const opts = ['<option value="">--Seleccionar rol--</option>'].concat(allRoles.map(r => `<option value="${r.id}">${r.name || r.label || r.title}</option>`));
                sel.innerHTML = opts.join('');
            }

            async function openUser(u) {
                form.reset();
                if (!allRoles || !allRoles.length) await loadRoles();
                fillRoleOptions();
                const get = n => form.querySelector(`[name="${n}"]`);
                const idEl = get('id'), nameEl = get('name'), emailEl = get('email'), pwdEl = get('password'), addrEl = get('address'), roleEl = get('roleId'), activeEl = get('isActive');
                if (idEl) idEl.value = u && u.id ? u.id : '';
                if (nameEl) nameEl.value = u && u.name ? u.name : '';
                if (emailEl) emailEl.value = u && u.email ? u.email : '';
                if (pwdEl) pwdEl.value = '';
                if (addrEl) addrEl.value = u && u.address ? u.address : '';
                if (roleEl) {
                    const rid = u && (u.roleId || u.role_id) ? (u.roleId || u.role_id) : (u && u.role && (u.role.id || u.roleId) ? (u.role.id || u.roleId) : '');
                    if (rid) { const opt = roleEl.querySelector(`option[value="${rid}"]`); if (opt) roleEl.value = String(rid); else roleEl.value = ''; }
                    else roleEl.value = '';
                }
                if (activeEl) {
                    let activeFlag = true;
                    if (u && (typeof u === 'object')) {
                        if (Object.prototype.hasOwnProperty.call(u, 'isActive')) activeFlag = !!u.isActive;
                        else if (Object.prototype.hasOwnProperty.call(u, 'active')) activeFlag = !!u.active;
                        else activeFlag = true;
                    } else {
                        activeFlag = true; // new user default
                    }
                    activeEl.checked = activeFlag;
                }
                title.innerHTML = u && u.id ? '<i class="bi bi-pencil"></i> Editar usuario' : '<i class="bi bi-person-plus"></i> Nuevo usuario';
                modal.show();
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const get = n => form.querySelector(`[name="${n}"]`);
                const id = get('id') ? get('id').value : '';
                const nameVal = get('name') ? String(get('name').value).trim() : '';
                const emailVal = get('email') ? String(get('email').value).trim() : '';
                const pwdVal = get('password') ? String(get('password').value) : '';
                const addrVal = get('address') ? String(get('address').value).trim() : '';
                const roleVal = get('roleId') ? get('roleId').value : '';
                const activeEl = get('isActive');
                const payload = {};
                if (!id) {
                    // create requires name,email,password,roleId
                    if (!nameVal || !emailVal || !roleVal) { alert('name, email y roleId son obligatorios para crear'); return; }
                    payload.name = nameVal; payload.email = emailVal; payload.password = pwdVal || '';
                    if (addrVal) payload.address = addrVal;
                    payload.roleId = parseInt(roleVal, 10);
                    if (activeEl && activeEl.checked !== undefined) payload.isActive = !!activeEl.checked;
                } else {
                    // update - only include provided fields
                    if (nameVal) payload.name = nameVal;
                    if (emailVal) payload.email = emailVal;
                    if (pwdVal) payload.password = pwdVal;
                    if (addrVal) payload.address = addrVal;
                    if (roleVal) payload.roleId = parseInt(roleVal, 10);
                    if (activeEl) payload.isActive = !!activeEl.checked;
                    if (Object.keys(payload).length === 0) { alert('Seleccione al menos un campo para actualizar'); return; }
                }

                try {
                    let res;
                    if (!id) {
                        res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post('admin/users', payload) : (async () => { const r = await fetch(base + 'admin/users', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    } else {
                        res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.put(`admin/users/${id}`, payload) : (async () => { const r = await fetch(base + `admin/users/${id}`, { method: 'PUT', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    }
                    if (res && res.success) {
                        modal.hide();
                        await loadUsers();
                        // Show success toast
                        const toast = document.createElement('div');
                        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                        toast.innerHTML = `
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="bi bi-check-circle"></i> Usuario ${id ? 'actualizado' : 'creado'} exitosamente
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                            </div>
                        `;
                        document.body.appendChild(toast);
                        const bsToast = new bootstrap.Toast(toast);
                        bsToast.show();
                        setTimeout(() => toast.remove(), 5000);
                    }
                    else {
                        console.error('User API error', res);
                        const msg = res && (res.message || res.error || res.raw) ? (res.message || res.error || JSON.stringify(res.raw || res)) : 'Error en servidor';
                        // Show error using Bootstrap alert
                        const alertDiv = modalElement.querySelector('.modal-body');
                        const existingAlert = alertDiv.querySelector('.alert');
                        if (existingAlert) existingAlert.remove();
                        alertDiv.insertAdjacentHTML('afterbegin', `
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle"></i> ${msg}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    }
                } catch (err) {
                    console.error('User request failed', err);
                    const alertDiv = modalElement.querySelector('.modal-body');
                    const existingAlert = alertDiv.querySelector('.alert');
                    if (existingAlert) existingAlert.remove();
                    alertDiv.insertAdjacentHTML('afterbegin', `
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> Error en la petición: ${err && err.message ? err.message : String(err)}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                }
            });

            // delegate actions
            container.addEventListener('click', async (ev) => {
                const btn = ev.target;
                if (btn.matches('.edit')) {
                    const id = btn.getAttribute('data-id');
                    const u = allUsers.find(x => String(x.id) === String(id));
                    await openUser(u);
                } else if (btn.matches('.toggle')) {
                    const id = btn.getAttribute('data-id');
                    const u = allUsers.find(x => String(x.id) === String(id));
                    if (!u) return;
                    const active = (typeof u.isActive !== 'undefined') ? u.isActive : (typeof u.active !== 'undefined' ? u.active : true);
                    const newStatus = active ? 'inactive' : 'active';
                    const userName = u.name || `Usuario #${id}`;

                    // Create confirmation modal
                    const confirmModal = document.createElement('div');
                    confirmModal.className = 'modal fade';
                    confirmModal.innerHTML = `
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-${active ? 'warning' : 'success'} text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-${active ? 'pause-circle' : 'play-circle'}"></i> ${active ? 'Desactivar' : 'Activar'} Usuario
                                    </h5>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que deseas <strong>${active ? 'desactivar' : 'activar'}</strong> al usuario <strong>"${userName}"</strong>?</p>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> ${active ? 'El usuario no podrá acceder al sistema.' : 'El usuario recuperará el acceso al sistema.'}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-${active ? 'warning' : 'success'}" id="confirm-toggle">
                                        <i class="bi bi-${active ? 'pause' : 'play'}"></i> ${active ? 'Desactivar' : 'Activar'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(confirmModal);
                    const bsModal = new bootstrap.Modal(confirmModal);
                    bsModal.show();

                    confirmModal.querySelector('#confirm-toggle').onclick = async () => {
                        try {
                            const body = { status: newStatus };
                            const res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post(`admin/users/${id}/status`, body) : (async () => { const r = await fetch(base + `admin/users/${id}/status`, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                            if (res && res.success) {
                                bsModal.hide();
                                await loadUsers();
                                // Show success toast
                                const toast = document.createElement('div');
                                toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                                toast.innerHTML = `
                                    <div class="d-flex">
                                        <div class="toast-body">
                                            <i class="bi bi-check-circle"></i> Usuario ${active ? 'desactivado' : 'activado'} exitosamente
                                        </div>
                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                                    </div>
                                `;
                                document.body.appendChild(toast);
                                const bsToast = new bootstrap.Toast(toast);
                                bsToast.show();
                                setTimeout(() => toast.remove(), 5000);
                            } else {
                                console.error('Status API error', res);
                                alert('No se pudo cambiar estado');
                            }
                        } catch (err) {
                            console.error('Status request failed', err);
                            alert('Error al cambiar estado');
                        }
                        confirmModal.remove();
                    };

                    confirmModal.addEventListener('hidden.bs.modal', () => {
                        confirmModal.remove();
                    });
                }
            });

            document.getElementById('new-user').addEventListener('click', () => openUser(null));

            // Event listeners for filters
            searchInput.addEventListener('input', applyFilters);
            roleFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);

            // Clear filters functionality
            clearFiltersBtn.addEventListener('click', () => {
                searchInput.value = '';
                roleFilter.value = '';
                statusFilter.value = '';
                applyFilters();
            });

            // Global function for clear filters button in no results state
            window.clearAllFilters = () => {
                searchInput.value = '';
                roleFilter.value = '';
                statusFilter.value = '';
                applyFilters();
            };

            // initial
            await loadRoles();
            await loadUsers();
        })();
    </script>
</body>

</html>