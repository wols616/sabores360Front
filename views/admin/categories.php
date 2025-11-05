<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Categorías</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
    <style>
        .table {
            width: 100%;
            border-collapse: collapse
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px
        }

        .toolbar {
            margin-bottom: 12px
        }

        #cat-modal {
            display: none
        }
    </style>
</head>

<body>
    <header>
        <h1>Categorías (Administrador)</h1>
        <?php $active = 'categories';
        require __DIR__ . '/_admin_nav.php'; ?>
    </header>
    <main>
        <section>
            <div class="toolbar"><button id="new-cat">Agregar categoría</button></div>
            <table class="table" id="cat-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cat-body">
                    <tr>
                        <td colspan="4">Cargando...</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const body = document.getElementById('cat-body');
            let categories = [];

            function render() {
                if (!categories || !categories.length) { body.innerHTML = '<tr><td colspan="4">No hay categorías.</td></tr>'; return; }
                body.innerHTML = categories.map(c => `<tr><td>${c.id}</td><td>${c.name || ''}</td><td>${c.description || ''}</td><td><button class="edit" data-id="${c.id}">Editar</button> <button class="delete" data-id="${c.id}">Eliminar</button></td></tr>`).join('');
            }

            async function load() {
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/categories') : (async () => { const r = await fetch(base + 'admin/categories', { credentials: 'include' }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    let list = [];
                    if (d && d.success) {
                        if (Array.isArray(d.categories)) list = d.categories;
                        else if (d.data && Array.isArray(d.data.categories)) list = d.data.categories;
                        else if (Array.isArray(d.data)) list = d.data;
                    }
                    categories = list;
                } catch (e) { categories = []; console.error('load categories error', e); }
                render();
            }

            // modal HTML
            const modalHtml = `
        <div id="cat-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
          <div style="background:#fff;padding:16px;max-width:480px;margin:48px auto;">
            <h3 id="cat-title">Nueva categoría</h3>
            <form id="cat-form">
              <input type="hidden" name="id">
              <div><label>Nombre<br><input name="name" required></label></div>
              <div><label>Descripción<br><textarea name="description"></textarea></label></div>
              <div style="margin-top:8px;"><button type="submit">Guardar</button> <button type="button" id="cat-cancel">Cancelar</button></div>
            </form>
          </div>
        </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = document.getElementById('cat-modal');
            const form = document.getElementById('cat-form');
            const title = document.getElementById('cat-title');
            const cancel = document.getElementById('cat-cancel');

            function openCat(c) {
                form.reset();
                const get = n => form.querySelector(`[name="${n}"]`);
                const idEl = get('id'); const nameEl = get('name'); const descEl = get('description');
                if (idEl) idEl.value = c && c.id ? c.id : '';
                if (nameEl) nameEl.value = c && c.name ? c.name : '';
                if (descEl) descEl.value = c && c.description ? c.description : '';
                title.textContent = c && c.id ? 'Editar categoría' : 'Nueva categoría';
                modal.style.display = 'flex';
            }

            cancel.addEventListener('click', () => modal.style.display = 'none');

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const get = n => form.querySelector(`[name="${n}"]`);
                const id = get('id') ? get('id').value : '';
                // only include provided fields to match UpdateCategoryRequest semantics
                const nameVal = get('name') ? String(get('name').value).trim() : '';
                const descVal = get('description') ? String(get('description').value).trim() : '';
                const payload = {};
                if (nameVal) payload.name = nameVal;
                if (descVal) payload.description = descVal;

                // For create, name is required
                if (!id && !payload.name) { alert('El nombre es requerido'); return; }
                // For update, require at least one field
                if (id && Object.keys(payload).length === 0) { alert('Seleccione al menos un campo para actualizar'); return; }

                try {
                    console.debug('Category request', id ? 'PUT' : 'POST', id ? `admin/categories/${id}` : 'admin/categories', payload);
                    let res;
                    if (id) {
                        res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.put(`admin/categories/${id}`, payload) : (async () => { const r = await fetch(base + `admin/categories/${id}`, { method: 'PUT', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    } else {
                        res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post('admin/categories', payload) : (async () => { const r = await fetch(base + 'admin/categories', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    }

                    // better error reporting to help debug server-side 500s
                    if (res && res.success) {
                        modal.style.display = 'none';
                        await load();
                    } else {
                        console.error('Category API error response', res);
                        const msg = res && (res.message || res.error || res.raw) ? (res.message || res.error || JSON.stringify(res.raw || res)) : 'Error en servidor';
                        alert(msg);
                    }
                } catch (err) {
                    console.error('Category request failed', err);
                    alert('Error en la petición: ' + (err && err.message ? err.message : String(err)));
                }
            });

            // table actions
            document.getElementById('cat-table').addEventListener('click', async (ev) => {
                const btn = ev.target;
                if (btn.matches('.edit')) {
                    const id = btn.getAttribute('data-id');
                    const c = categories.find(x => String(x.id) === String(id));
                    openCat(c);
                } else if (btn.matches('.delete')) {
                    const id = btn.getAttribute('data-id');
                    if (!confirm('Eliminar categoría #' + id + '?')) return;
                    try {
                        const res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.delete(`admin/categories/${id}`) : (async () => { const r = await fetch(base + `admin/categories/${id}`, { method: 'DELETE', credentials: 'include' }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                        if (res && res.success) await load(); else alert(res && res.message ? res.message : 'No se pudo eliminar');
                    } catch (e) { alert('Error al eliminar'); }
                }
            });

            document.getElementById('new-cat').addEventListener('click', () => openCat(null));

            // initial
            await load();
        })();
    </script>
</body>

</html>