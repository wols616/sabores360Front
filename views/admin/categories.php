<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Categorías | Sabores360</title>
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

        .table-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
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

        .table thead th {
            background: linear-gradient(45deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border: none;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 107, 53, 0.05);
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0.1rem;
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
        <?php $active = 'categories';
        require __DIR__ . '/_admin_nav.php'; ?>

        <div class="page-header text-center">
            <h1 class="mb-2">
                <i class="bi bi-tags"></i> Gestión de Categorías
            </h1>
            <p class="mb-0 opacity-75">Administra las categorías de productos</p>
        </div>

        <!-- Search Bar -->
        <div class="card mb-4"
            style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-search text-orange"></i> Buscar categorías
                        </label>
                        <input type="text" class="form-control" id="search-input"
                            placeholder="Buscar por nombre o descripción...">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-orange btn-sm" id="clear-search">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </button>
                    </div>
                    <div class="col-md-3 text-end">
                        <span class="badge bg-secondary" id="results-count">0 resultados</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card table-card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list text-orange"></i> Lista de Categorías
                </h5>
                <button id="new-cat" class="btn btn-orange">
                    <i class="bi bi-plus-circle"></i> Nueva Categoría
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="cat-table">
                        <thead>
                            <tr>
                                <th width="10%">
                                    <i class="bi bi-hash"></i> ID
                                </th>
                                <th width="25%">
                                    <i class="bi bi-tag"></i> Nombre
                                </th>
                                <th width="45%">
                                    <i class="bi bi-text-paragraph"></i> Descripción
                                </th>
                                <th width="20%" class="text-center">
                                    <i class="bi bi-gear"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="cat-body">
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="spinner-border text-orange" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando categorías...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
            const body = document.getElementById('cat-body');

            // Global variables for filtering
            let allCategories = [];
            let filteredCategories = [];

            // Get filter elements
            const searchInput = document.getElementById('search-input');
            const clearSearchBtn = document.getElementById('clear-search');
            const resultsCount = document.getElementById('results-count');

            function applyFilter() {
                const searchTerm = searchInput.value.toLowerCase().trim();

                filteredCategories = allCategories.filter(category => {
                    if (!searchTerm) return true;

                    const name = String(category.name || '').toLowerCase();
                    const description = String(category.description || '').toLowerCase();
                    const id = String(category.id || '').toLowerCase();

                    return name.includes(searchTerm) ||
                        description.includes(searchTerm) ||
                        id.includes(searchTerm);
                });

                render();
            }

            function render() {
                // Update results count
                resultsCount.textContent = `${filteredCategories.length} resultado${filteredCategories.length !== 1 ? 's' : ''}`;

                if (!filteredCategories || !filteredCategories.length) {
                    body.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="mt-2 text-muted">${allCategories.length > 0 ? 'No se encontraron categorías con el término de búsqueda' : 'No hay categorías registradas'}</p>
                                ${allCategories.length > 0 ? '<button class="btn btn-outline-orange btn-sm mt-2" onclick="clearAllFilters()"><i class="bi bi-x-circle"></i> Limpiar Búsqueda</button>' : ''}
                            </td>
                        </tr>
                    `;
                    return;
                }
                body.innerHTML = filteredCategories.map(c => `
                    <tr>
                        <td>
                            <span class="badge bg-secondary">#${c.id}</span>
                        </td>
                        <td>
                            <strong>${c.name || ''}</strong>
                        </td>
                        <td>
                            <span class="text-muted">${c.description || 'Sin descripción'}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary btn-action edit" data-id="${c.id}" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-action delete" data-id="${c.id}" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
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
                    allCategories = list;
                    filteredCategories = [...allCategories];
                } catch (e) {
                    allCategories = [];
                    filteredCategories = [];
                    console.error('load categories error', e);
                }
                render();
            }

            // modal HTML
            const modalHtml = `
        <div class="modal fade" id="cat-modal" tabindex="-1" aria-labelledby="cat-title" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cat-title">
                            <i class="bi bi-tag"></i> Nueva categoría
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="cat-form">
                            <input type="hidden" name="id">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-tag"></i> Nombre de la categoría
                                </label>
                                <input name="name" class="form-control" required placeholder="Ej: Bebidas, Comida rápida, etc.">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-text-paragraph"></i> Descripción
                                </label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Descripción opcional de la categoría..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </button>
                        <button type="submit" form="cat-form" class="btn btn-orange">
                            <i class="bi bi-check-circle"></i> Guardar Categoría
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('cat-modal'));
            const modalElement = document.getElementById('cat-modal');
            const form = document.getElementById('cat-form');
            const title = document.getElementById('cat-title');

            function openCat(c) {
                form.reset();
                const get = n => form.querySelector(`[name="${n}"]`);
                const idEl = get('id'); const nameEl = get('name'); const descEl = get('description');
                if (idEl) idEl.value = c && c.id ? c.id : '';
                if (nameEl) nameEl.value = c && c.name ? c.name : '';
                if (descEl) descEl.value = c && c.description ? c.description : '';
                title.innerHTML = c && c.id ? '<i class="bi bi-pencil"></i> Editar categoría' : '<i class="bi bi-tag"></i> Nueva categoría';
                modal.show();
            }

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
                        modal.hide();
                        await load();
                        // Show success message
                        const toast = document.createElement('div');
                        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                        toast.innerHTML = `
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="bi bi-check-circle"></i> Categoría ${id ? 'actualizada' : 'creada'} exitosamente
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                            </div>
                        `;
                        document.body.appendChild(toast);
                        const bsToast = new bootstrap.Toast(toast);
                        bsToast.show();
                        setTimeout(() => toast.remove(), 5000);
                    } else {
                        console.error('Category API error response', res);
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
                    console.error('Category request failed', err);
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

            // table actions
            document.getElementById('cat-table').addEventListener('click', async (ev) => {
                const btn = ev.target.closest('.edit, .delete');
                if (!btn) return;

                if (btn.matches('.edit')) {
                    const id = btn.getAttribute('data-id');
                    const c = allCategories.find(x => String(x.id) === String(id));
                    openCat(c);
                } else if (btn.matches('.delete')) {
                    const id = btn.getAttribute('data-id');
                    const category = allCategories.find(c => String(c.id) === String(id));
                    const categoryName = category ? category.name : `#${id}`;

                    // Create confirmation modal
                    const confirmModal = document.createElement('div');
                    confirmModal.className = 'modal fade';
                    confirmModal.innerHTML = `
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                                    </h5>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que deseas eliminar la categoría <strong>"${categoryName}"</strong>?</p>
                                    <div class="alert alert-warning">
                                        <i class="bi bi-info-circle"></i> Esta acción no se puede deshacer.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-danger" id="confirm-delete">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(confirmModal);
                    const bsModal = new bootstrap.Modal(confirmModal);
                    bsModal.show();

                    confirmModal.querySelector('#confirm-delete').onclick = async () => {
                        try {
                            const res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.delete(`admin/categories/${id}`) : (async () => { const r = await fetch(base + `admin/categories/${id}`, { method: 'DELETE', credentials: 'include' }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                            if (res && res.success) {
                                bsModal.hide();
                                await load();
                                // Show success toast
                                const toast = document.createElement('div');
                                toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                                toast.innerHTML = `
                                    <div class="d-flex">
                                        <div class="toast-body">
                                            <i class="bi bi-check-circle"></i> Categoría eliminada exitosamente
                                        </div>
                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                                    </div>
                                `;
                                document.body.appendChild(toast);
                                const bsToast = new bootstrap.Toast(toast);
                                bsToast.show();
                                setTimeout(() => toast.remove(), 5000);
                            } else {
                                alert(res && res.message ? res.message : 'No se pudo eliminar');
                            }
                        } catch (e) {
                            alert('Error al eliminar');
                        }
                        confirmModal.remove();
                    };

                    confirmModal.addEventListener('hidden.bs.modal', () => {
                        confirmModal.remove();
                    });
                }
            });

            document.getElementById('new-cat').addEventListener('click', () => openCat(null));

            // Event listeners for search
            searchInput.addEventListener('input', applyFilter);

            // Clear search functionality
            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                applyFilter();
            });

            // Global function for clear search button in no results state
            window.clearAllFilters = () => {
                searchInput.value = '';
                applyFilter();
            };

            // initial
            await load();
        })();
    </script>
</body>

</html>