<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Usuarios</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Usuarios (Administrador)</h1>
        <?php $active = 'users';
        require __DIR__ . '/_admin_nav.php'; ?>
    </header>

    <main>
        <section>
            <h2>Listado de usuarios</h2>
            <div class="toolbar"><button id="new-user">Agregar usuario</button></div>
            <div id="users-list">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('users-list');
            let users = [];
            let roles = [];

            function renderUsers() {
                if (!users || !users.length) { container.innerHTML = '<div>No hay usuarios.</div>'; return; }
                const rows = users.map(u => {
                    const id = u.id || u.userId || '';
                    const name = u.name || u.fullName || u.username || '';
                    const email = u.email || '';
                    const roleLabel = (u.role && (u.role.name || u.role.label)) || u.role_name || u.role || '';
                    const active = (typeof u.isActive !== 'undefined') ? u.isActive : (typeof u.active !== 'undefined' ? u.active : true);
                    const actions = `<button class="edit" data-id="${id}">Editar</button> <button class="toggle" data-id="${id}">${active ? 'Desactivar' : 'Activar'}</button>`;
                    return `<div class="user-item" data-id="${id}"><strong>#${id} ${name}</strong> - ${email} - ${roleLabel} - ${active ? 'Activo' : 'Inactivo'} - ${actions}</div>`;
                }).join('');
                container.innerHTML = rows;
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
                    roles = list;
                } catch (e) { console.error('loadRoles error', e); roles = []; }
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
                    users = list;
                } catch (err) { users = []; console.error('loadUsers error', err); }
                renderUsers();
            }

            // user modal
            const modalHtml = `
            <div id="user-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
              <div style="background:#fff;padding:16px;max-width:560px;margin:48px auto;">
                <h3 id="user-title">Nuevo usuario</h3>
                <form id="user-form">
                  <input type="hidden" name="id">
                  <div><label>Nombre<br><input name="name" required></label></div>
                  <div><label>Email<br><input name="email" type="email" required></label></div>
                  <div><label>Contraseña<br><input name="password" type="password"></label></div>
                  <div><label>Dirección<br><input name="address" type="text"></label></div>
                  <div><label>Rol<br><select name="roleId"><option value="">Cargando roles...</option></select></label></div>
                  <div><label>Activo <input name="isActive" type="checkbox"></label></div>
                  <div style="margin-top:8px;"><button type="submit">Guardar</button> <button type="button" id="user-cancel">Cancelar</button></div>
                </form>
              </div>
            </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = document.getElementById('user-modal');
            const form = document.getElementById('user-form');
            const title = document.getElementById('user-title');
            const cancel = document.getElementById('user-cancel');

            function fillRoleOptions() {
                const sel = form.querySelector('[name="roleId"]');
                if (!sel) return;
                if (!roles || !roles.length) { sel.innerHTML = '<option value="">(sin roles)</option>'; return; }
                const opts = ['<option value="">--Seleccionar rol--</option>'].concat(roles.map(r => `<option value="${r.id}">${r.name || r.label || r.title}</option>`));
                sel.innerHTML = opts.join('');
            }

            async function openUser(u) {
                form.reset();
                if (!roles || !roles.length) await loadRoles();
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
                title.textContent = u && u.id ? 'Editar usuario' : 'Nuevo usuario';
                modal.style.display = 'flex';
            }

            cancel.addEventListener('click', () => modal.style.display = 'none');

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
                    if (res && res.success) { modal.style.display = 'none'; await loadUsers(); }
                    else { console.error('User API error', res); alert(res && (res.message || res.error || res.raw) ? (res.message || res.error || JSON.stringify(res.raw || res)) : 'Error en servidor'); }
                } catch (err) { console.error('User request failed', err); alert('Error en la petición: ' + (err && err.message ? err.message : String(err))); }
            });

            // delegate actions
            container.addEventListener('click', async (ev) => {
                const btn = ev.target;
                if (btn.matches('.edit')) {
                    const id = btn.getAttribute('data-id');
                    const u = users.find(x => String(x.id) === String(id));
                    await openUser(u);
                } else if (btn.matches('.toggle')) {
                    const id = btn.getAttribute('data-id');
                    const u = users.find(x => String(x.id) === String(id));
                    if (!u) return;
                    const active = (typeof u.isActive !== 'undefined') ? u.isActive : (typeof u.active !== 'undefined' ? u.active : true);
                    const newStatus = active ? 'inactive' : 'active';
                    if (!confirm(`${active ? 'Desactivar' : 'Activar'} usuario #${id}?`)) return;
                    try {
                        const body = { status: newStatus };
                        const res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post(`admin/users/${id}/status`, body) : (async () => { const r = await fetch(base + `admin/users/${id}/status`, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                        if (res && res.success) await loadUsers(); else { console.error('Status API error', res); alert('No se pudo cambiar estado'); }
                    } catch (err) { console.error('Status request failed', err); alert('Error al cambiar estado'); }
                }
            });

            document.getElementById('new-user').addEventListener('click', () => openUser(null));

            // initial
            await loadRoles();
            await loadUsers();
        })();
    </script>
</body>

</html>