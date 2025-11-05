<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Productos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
    <style>
        /* tiny defaults so UI isn't raw */
        .cards {
            display: flex;
            gap: 12px;
            margin-bottom: 12px
        }

        .card {
            background: #f6f6f6;
            padding: 12px;
            border-radius: 6px;
            flex: 1
        }

        .big {
            font-size: 1.5rem
        }

        .table {
            width: 100%;
            border-collapse: collapse
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px
        }

        #product-list .product-item {
            padding: 8px;
            border-bottom: 1px solid #eee
        }

        #product-modal {
            display: none
        }
    </style>
</head>

<body>
    <header>
        <h1>Productos (Administrador)</h1>
        <nav>
            <a href="/Sabores360/views/admin/dashboard.php">Dashboard</a> |
            <a href="/Sabores360/views/admin/orders.php">Pedidos</a> |
            <a href="/Sabores360/views/admin/products.php">Productos</a> |
            <a href="/Sabores360/views/admin/users.php">Usuarios</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Lista de productos</h2>
            <div><button id="new-product">Agregar producto</button></div>
            <div id="product-list">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('product-list');
            let products = [];

            function renderProducts() {
                if (!products || !products.length) { container.textContent = 'No hay productos.'; return; }
                container.innerHTML = '';
                products.forEach(p => {
                    const name = p.name || p.title || '';
                    const price = p.price || p.cost || '';
                    const available = (typeof p.is_available !== 'undefined') ? p.is_available : (typeof p.stock !== 'undefined' ? (p.stock > 0) : true);
                    const el = document.createElement('div');
                    el.className = 'product-item';
                    el.innerHTML = `<strong>${name}</strong> - ${price} - ${available ? 'Disponible' : 'No disponible'}<br>
                        <button class="edit" data-id="${p.id}">Editar</button>
                        <button class="delete" data-id="${p.id}">Eliminar</button>`;
                    container.appendChild(el);
                });
            }

            async function fetchProducts() {
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/products') : (async () => { const res = await fetch(base + 'admin/products', { credentials: 'include' }); return res.json(); })());
                    // normalize product arrays: d.products | d.data | d.data.products | d.items
                    let list = [];
                    if (d && d.success) {
                        if (Array.isArray(d.products)) list = d.products;
                        else if (Array.isArray(d.data)) list = d.data;
                        else if (d.data && Array.isArray(d.data.products)) list = d.data.products;
                        else if (d.data && Array.isArray(d.data.items)) list = d.data.items;
                        else if (Array.isArray(d.items)) list = d.items;
                    }
                    products = list;
                    renderProducts();
                } catch (err) { container.textContent = 'Error al cargar productos.'; }
            }

            // PRODUCT MODAL (simple)
            const modalHtml = `
                <div id="product-modal" style="display:none;position:fixed;left:0;top:0;right:0;bottom:0;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
                  <div style="background:#fff;padding:16px;max-width:600px;width:90%;box-shadow:0 4px 12px rgba(0,0,0,0.2);">
                    <h3 id="modal-title">Nuevo producto</h3>
                    <form id="product-form">
                      <input type="hidden" name="id" />
                      <div><label>Nombre<br><input name="name" required></label></div>
                      <div><label>Precio<br><input name="price" type="number" step="0.01" required></label></div>
                      <div><label>Descripción<br><textarea name="description"></textarea></label></div>
                      <div><label>Stock<br><input name="stock" type="number" value="0"></label></div>
                      <div><label>Disponible <input name="isAvailable" type="checkbox" value="1"></label></div>
                      <div><label>Categoría<br><select name="categoryId"><option value="">Cargando categorías...</option></select></label></div>
                      <div><label>Imagen (URL)<br><input name="imageUrl" type="url" placeholder="https://..."></label></div>
                      <div style="margin-top:8px;"><button type="submit">Guardar</button> <button type="button" id="modal-cancel">Cancelar</button></div>
                    </form>
                  </div>
                </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = document.getElementById('product-modal');
            const form = document.getElementById('product-form');
            const modalTitle = document.getElementById('modal-title');
            const modalCancel = document.getElementById('modal-cancel');

            // categories cache
            let categories = [];
            async function loadCategories() {
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/categories') : (async () => { const res = await fetch(base + 'admin/categories', { credentials: 'include' }); const t = await res.text(); try { return JSON.parse(t); } catch (e) { return { success: res.ok, raw: t } } })());
                    let list = [];
                    if (d && d.success) {
                        if (Array.isArray(d.categories)) list = d.categories;
                        else if (d.data && Array.isArray(d.data.categories)) list = d.data.categories;
                        else if (Array.isArray(d.data)) list = d.data;
                    }
                    categories = list;
                } catch (e) {
                    console.error('Failed to load categories', e);
                    categories = [];
                }
                // populate select
                const sel = form && form.querySelector('[name="categoryId"]');
                if (!sel) return;
                if (!categories || !categories.length) {
                    sel.innerHTML = '<option value="">(sin categorías)</option>';
                    return;
                }
                const opts = ['<option value="">--Seleccionar categoría--</option>'].concat(categories.map(c => `<option value="${c.id}">${c.name}</option>`));
                sel.innerHTML = opts.join('');
            }

            async function openModal(prod) {
                if (!form) { console.error('product-form element not found'); return; }
                try {
                    form.reset();
                    // ensure categories are loaded before setting selection
                    if (!categories || !categories.length) {
                        await loadCategories();
                    }
                    const el = name => form.querySelector(`[name="${name}"]`);
                    const idEl = el('id');
                    const nameEl = el('name');
                    const priceEl = el('price');
                    const descEl = el('description');
                    const stockEl = el('stock');
                    const availEl = el('isAvailable');
                    const catEl = el('categoryId');
                    const imgEl = el('imageUrl');

                    if (idEl) idEl.value = prod && prod.id ? prod.id : '';
                    if (nameEl) nameEl.value = prod && (prod.name || prod.title) ? (prod.name || prod.title) : '';
                    if (priceEl) priceEl.value = prod && (prod.price || prod.cost) ? (prod.price || prod.cost) : '';
                    if (descEl) descEl.value = prod && (prod.description || prod.desc) ? (prod.description || prod.desc) : '';
                    if (stockEl) stockEl.value = prod && (typeof prod.stock !== 'undefined') ? prod.stock : (prod && prod.stock ? prod.stock : 0);
                    // support both naming conventions (use safe checks to avoid accessing properties on null)
                    if (availEl) {
                        let availableFlag = false;
                        if (prod) {
                            if (Object.prototype.hasOwnProperty.call(prod, 'is_available')) {
                                availableFlag = !!prod.is_available;
                            } else if (Object.prototype.hasOwnProperty.call(prod, 'isAvailable')) {
                                availableFlag = !!prod.isAvailable;
                            } else if (Object.prototype.hasOwnProperty.call(prod, 'stock')) {
                                availableFlag = !!(prod.stock > 0);
                            }
                        }
                        availEl.checked = availableFlag;
                    }
                    if (catEl) {
                        // determine category id from various shapes: categoryId, category_id, category:{id}, or category (number)
                        let cid = null;
                        if (prod) {
                            if (prod.categoryId !== undefined && prod.categoryId !== null) cid = prod.categoryId;
                            else if (prod.category_id !== undefined && prod.category_id !== null) cid = prod.category_id;
                            else if (prod.category && typeof prod.category === 'object' && (prod.category.id !== undefined)) cid = prod.category.id;
                            else if (prod.category && (typeof prod.category === 'number' || typeof prod.category === 'string')) cid = prod.category;
                        }
                        if (cid !== null && typeof cid !== 'undefined') {
                            // set if option exists, convert to string for comparison
                            const str = String(cid);
                            const opt = catEl.querySelector(`option[value="${str}"]`);
                            if (opt) catEl.value = str; else catEl.value = '';
                        } else {
                            catEl.value = '';
                        }
                    }
                    if (imgEl) imgEl.value = prod && (prod.imageUrl || prod.image_url || prod.image) ? (prod.imageUrl || prod.image_url || prod.image) : '';

                    modalTitle.textContent = prod && prod.id ? 'Editar producto' : 'Nuevo producto';
                    modal.style.display = 'flex';
                } catch (err) {
                    console.error('Error opening product modal', err);
                }
            }

            modalCancel.addEventListener('click', () => { modal.style.display = 'none'; });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const get = name => form.querySelector(`[name="${name}"]`);
                const idEl = get('id');
                const nameEl = get('name');
                const priceEl = get('price');
                const descEl = get('description');
                const stockEl = get('stock');
                const availEl = get('isAvailable');
                const catEl = get('categoryId');
                const imgEl = get('imageUrl');

                const id = idEl ? idEl.value : '';
                // build JSON payload matching CreateProductRequest
                const payload = {
                    name: nameEl ? String(nameEl.value).trim() : undefined,
                    description: descEl ? String(descEl.value).trim() : undefined,
                    price: priceEl && priceEl.value ? parseFloat(priceEl.value) : 0,
                    stock: stockEl && stockEl.value ? parseInt(stockEl.value, 10) : 0,
                    categoryId: catEl && catEl.value ? parseInt(catEl.value, 10) : null,
                    imageUrl: imgEl && imgEl.value ? String(imgEl.value).trim() : undefined,
                    isAvailable: !!(availEl && availEl.checked)
                };
                // basic validation
                if (!payload.name) { alert('El nombre es requerido'); return; }
                if (!payload.categoryId && payload.categoryId !== 0) { alert('categoryId es requerido'); return; }
                try {
                    let res;
                    if (id) {
                        // update (send JSON)
                        res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.put(`admin/products/${id}`, payload) : (async () => { const r = await fetch(base + `admin/products/${id}`, { method: 'PUT', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    } else {
                        // create (send JSON)
                        res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post('admin/products', payload) : (async () => { const r = await fetch(base + 'admin/products', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    }
                    if (res && res.success) {
                        modal.style.display = 'none';
                        await fetchProducts();
                    } else {
                        alert(res && res.message ? res.message : 'Error al guardar');
                    }
                } catch (err) { alert('Error en la petición'); }
            });

            // delegate edit/delete
            container.addEventListener('click', async (ev) => {
                const btn = ev.target;
                if (btn.matches('.edit')) {
                    const id = btn.getAttribute('data-id');
                    const prod = products.find(p => String(p.id) === String(id));
                    openModal(prod);
                } else if (btn.matches('.delete')) {
                    const id = btn.getAttribute('data-id');
                    if (!confirm('Eliminar producto #' + id + '?')) return;
                    try {
                        const res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.delete(`admin/products/${id}`) : (async () => { const r = await fetch(base + `admin/products/${id}`, { method: 'DELETE', credentials: 'include' }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                        if (res && res.success) await fetchProducts(); else alert(res && res.message ? res.message : 'No se pudo eliminar');
                    } catch (e) { alert('Error al eliminar'); }
                }
            });

            document.getElementById('new-product').addEventListener('click', () => openModal(null));

            // initial load
            await loadCategories();
            await fetchProducts();
        })();
    </script>
</body>

</html>