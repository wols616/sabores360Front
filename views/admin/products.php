<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Productos | Sabores360</title>
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

        .product-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.2);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 2px solid var(--orange-bg);
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

        .badge-available {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .badge-unavailable {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
            color: white;
        }

        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(255, 107, 53, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border-radius: 20px 20px 0 0;
        }

        .form-control:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .form-select:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <?php $active = 'products';
        require __DIR__ . '/_admin_nav.php'; ?>

        <div class="page-header text-center">
            <h1 class="mb-2">
                <i class="bi bi-box-seam"></i> Gestión de Productos
            </h1>
            <p class="mb-0 opacity-75">Administra el catálogo de productos</p>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="text-dark">
                        <i class="bi bi-grid"></i> Productos Disponibles
                    </h3>
                    <button id="new-product" class="btn btn-orange btn-lg">
                        <i class="bi bi-plus-circle"></i> Agregar Producto
                    </button>
                </div>
            </div>
        </div>

        <div id="product-list" class="row">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-orange" role="status">
                    <span class="visually-hidden">Cargando productos...</span>
                </div>
                <p class="mt-3 text-muted">Cargando productos...</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('product-list');
            let products = [];

            function normalizeImageUrl(u) {
                if (!u) return null;
                if (u === 'undefined' || u === 'null') return null;
                try {
                    if (u.startsWith('http://') || u.startsWith('https://') || u.startsWith('//')) return u;
                } catch (e) { }
                if (u.startsWith('/')) return window.location.origin + u;
                return window.location.origin + '/' + u;
            }

            function renderProducts() {
                if (!products || !products.length) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-box display-1 text-muted opacity-50"></i>
                            <h4 class="text-muted mt-3">No hay productos</h4>
                            <p class="text-muted">Comienza agregando tu primer producto</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = '';
                products.forEach(p => {
                    const name = p.name || p.title || '';
                    const price = p.price || p.cost || '';
                    const description = p.description || p.desc || '';
                    const stock = p.stock || 0;
                    const available = (typeof p.is_available !== 'undefined') ? p.is_available : (typeof p.stock !== 'undefined' ? (p.stock > 0) : true);
                    const rawImg = p.imageUrl || p.image_url || p.image;
                    const imgSrc = normalizeImageUrl(rawImg);
                    const placeholder = '/Sabores360/assets/img/no-image.svg';

                    const availableBadge = available ?
                        '<span class="badge badge-available"><i class="bi bi-check-circle"></i> Disponible</span>' :
                        '<span class="badge badge-unavailable"><i class="bi bi-x-circle"></i> No disponible</span>';

                    const stockBadge = stock <= 5 ?
                        `<span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Stock: ${stock}</span>` :
                        `<span class="badge bg-success"><i class="bi bi-check2"></i> Stock: ${stock}</span>`;

                    const col = document.createElement('div');
                    col.className = 'col-xl-3 col-lg-4 col-md-6 mb-4';

                    col.innerHTML = `
                        <div class="card product-card h-100">
                            <img src="${imgSrc || placeholder}" 
                                 onerror="this.onerror=null;this.src='${placeholder}';" 
                                 class="product-image" 
                                 alt="${name}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-truncate" title="${name}">
                                    <i class="bi bi-tag text-orange"></i> ${name}
                                </h5>
                                <p class="card-text text-muted small flex-grow-1" style="height: 60px; overflow: hidden;">
                                    ${description || 'Sin descripción'}
                                </p>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="text-orange mb-0">$${price}</h4>
                                        ${stockBadge}
                                    </div>
                                    ${availableBadge}
                                </div>
                                <div class="mt-auto">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-orange edit" data-id="${p.id}">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                        <button class="btn btn-outline-danger delete" data-id="${p.id}">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(col);
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

            // PRODUCT MODAL (Bootstrap styled)
            const modalHtml = `
                <div class="modal fade" id="product-modal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">
                            <i class="bi bi-plus-circle"></i> Nuevo producto
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="product-form">
                        <div class="modal-body">
                          <input type="hidden" name="id" />
                          
                          <div class="row">
                            <div class="col-md-8 mb-3">
                              <label class="form-label">
                                <i class="bi bi-tag"></i> Nombre del producto
                              </label>
                              <input name="name" class="form-control form-control-lg" required placeholder="Ej: Pizza Margherita">
                            </div>
                            <div class="col-md-4 mb-3">
                              <label class="form-label">
                                <i class="bi bi-currency-dollar"></i> Precio
                              </label>
                              <input name="price" type="number" step="0.01" class="form-control form-control-lg" required placeholder="0.00">
                            </div>
                          </div>
                          
                          <div class="mb-3">
                            <label class="form-label">
                              <i class="bi bi-text-paragraph"></i> Descripción
                            </label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Describe el producto..."></textarea>
                          </div>
                          
                          <div class="row">
                            <div class="col-md-4 mb-3">
                              <label class="form-label">
                                <i class="bi bi-boxes"></i> Stock
                              </label>
                              <input name="stock" type="number" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                              <label class="form-label">
                                <i class="bi bi-list-ul"></i> Categoría
                              </label>
                              <select name="categoryId" class="form-select" required>
                                <option value="">Cargando categorías...</option>
                              </select>
                            </div>
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                              <div class="form-check form-switch">
                                <input name="isAvailable" class="form-check-input" type="checkbox" value="1" id="availableSwitch">
                                <label class="form-check-label" for="availableSwitch">
                                  <i class="bi bi-check-circle"></i> Disponible
                                </label>
                              </div>
                            </div>
                          </div>
                          
                          <div class="mb-3">
                            <label class="form-label">
                              <i class="bi bi-image"></i> URL de imagen
                            </label>
                            <input name="imageUrl" type="url" class="form-control" placeholder="https://ejemplo.com/imagen.jpg">
                            <div class="form-text">Ingresa la URL completa de la imagen del producto</div>
                          </div>
                        </div>
                        
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancelar
                          </button>
                          <button type="submit" class="btn btn-orange">
                            <i class="bi bi-check-circle"></i> Guardar Producto
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('product-modal'));
            const modalElement = document.getElementById('product-modal');
            const form = document.getElementById('product-form');
            const modalTitle = document.getElementById('modal-title');

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

                    modalTitle.innerHTML = prod && prod.id ?
                        '<i class="bi bi-pencil"></i> Editar producto' :
                        '<i class="bi bi-plus-circle"></i> Nuevo producto';
                    modal.show();
                } catch (err) {
                    console.error('Error opening product modal', err);
                }
            }

            // Modal close handled by Bootstrap

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
                        modal.hide();
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