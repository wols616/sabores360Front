<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Pedidos | Sabores360</title>
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

        .order-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.15);
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

        .form-select:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .seller-badge {
            background: linear-gradient(45deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border-radius: 20px;
            padding: 0.5rem 1rem;
        }

        .no-seller-badge {
            background: linear-gradient(45deg, #6c757d, #adb5bd);
            color: white;
            border-radius: 20px;
            padding: 0.5rem 1rem;
        }

        .assign-msg {
            font-size: 0.9rem;
            padding: 0.5rem;
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        .assign-msg.success {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .assign-msg.error {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <?php $active = 'orders';
        require __DIR__ . '/_admin_nav.php'; ?>

        <div class="page-header text-center">
            <h1 class="mb-2">
                <i class="bi bi-cart-check"></i> Gestión de Pedidos
            </h1>
            <p class="mb-0 opacity-75">Administra y asigna pedidos a vendedores</p>
        </div>

        <div class="row">
            <div class="col-12">
                <h3 class="text-dark mb-4">
                    <i class="bi bi-list-check"></i> Listado de Pedidos
                </h3>
                <div id="orders-list" class="row">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-orange" role="status">
                            <span class="visually-hidden">Cargando pedidos...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando pedidos...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('orders-list');
            container.textContent = 'Cargando...';
            try {
                // Fetch vendors and orders in parallel
                const [vendorsResp, ordersResp] = await Promise.all([
                    (window.SABORES360 && SABORES360.API) ? SABORES360.API.get('admin/vendors') : (async () => { const r = await fetch(base + 'admin/vendors', { credentials: 'include' }); return r.json(); })(),
                    (window.SABORES360 && SABORES360.API) ? SABORES360.API.get('admin/orders') : (async () => { const r = await fetch(base + 'admin/orders', { credentials: 'include' }); return r.json(); })()
                ]);

                // normalize vendors list
                let vendors = [];
                if (vendorsResp) {
                    if (Array.isArray(vendorsResp.vendors)) vendors = vendorsResp.vendors;
                    else if (vendorsResp.data && Array.isArray(vendorsResp.data.vendors)) vendors = vendorsResp.data.vendors;
                    else if (Array.isArray(vendorsResp.data)) vendors = vendorsResp.data;
                }

                // normalize orders list
                let orders = [];
                const d = ordersResp;
                if (d) {
                    if (Array.isArray(d.orders)) orders = d.orders;
                    else if (d.data && Array.isArray(d.data.orders)) orders = d.data.orders;
                    else if (Array.isArray(d.data)) orders = d.data;
                    else if (Array.isArray(d.items)) orders = d.items;
                }

                if (!orders || !orders.length) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-cart-x display-1 text-muted opacity-50"></i>
                            <h4 class="text-muted mt-3">No hay pedidos</h4>
                            <p class="text-muted">Los pedidos aparecerán aquí cuando los clientes realicen compras</p>
                        </div>
                    `;
                    return;
                }

                // render orders with seller info and assign control
                container.innerHTML = '';
                orders.forEach(o => {
                    const id = o.id || '';
                    const status = o.status || o.state || '';
                    const total = (o.totalAmount || o.total_amount || o.total || '');
                    const created = o.createdAt || o.created_at || o.date || '';
                    const seller = (o.seller && (o.seller.name || o.seller.email)) ? (o.seller.name || o.seller.email) : null;

                    // Status badge color
                    let statusClass = 'bg-secondary';
                    switch (status.toLowerCase()) {
                        case 'pendiente': statusClass = 'bg-warning text-dark'; break;
                        case 'confirmado': statusClass = 'bg-info'; break;
                        case 'en preparación': statusClass = 'bg-primary'; break;
                        case 'en camino': statusClass = 'bg-success'; break;
                        case 'entregado': statusClass = 'bg-success'; break;
                        case 'cancelado': statusClass = 'bg-danger'; break;
                    }

                    const col = document.createElement('div');
                    col.className = 'col-xl-4 col-lg-6 mb-4';

                    // Create select options
                    let selectOptions = `<option value="">${seller ? '-- cambiar vendedor --' : '-- seleccionar vendedor --'}</option>`;
                    let currentSellerId = null;
                    if (o.seller && o.seller.id) currentSellerId = o.seller.id;

                    vendors.forEach(v => {
                        const selected = (currentSellerId && Number(currentSellerId) === Number(v.id)) ? 'selected' : '';
                        selectOptions += `<option value="${v.id}" ${selected}>${v.name || v.email || ('Vendedor ' + v.id)}</option>`;
                    });

                    col.innerHTML = `
                        <div class="card order-card h-100">
                            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-receipt"></i> Pedido #${escapeHtml(String(id))}
                                </h5>
                                <span class="badge ${statusClass}">${escapeHtml(status)}</span>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Total</small>
                                        <div class="h4 text-orange mb-0">$${escapeHtml(String(total))}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Fecha</small>
                                        <div class="small">${escapeHtml(created)}</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-person-badge"></i> Vendedor asignado
                                    </label>
                                    <div class="mb-2">
                                        ${seller ?
                            `<span class="seller-badge seller-name"><i class="bi bi-person-check"></i> ${escapeHtml(seller)}</span>` :
                            `<span class="no-seller-badge"><i class="bi bi-person-x"></i> Sin asignar</span>`
                        }
                                    </div>
                                    <select class="form-select vendor-select">
                                        ${selectOptions}
                                    </select>
                                </div>
                                
                                <div class="d-grid">
                                    <button class="btn btn-orange btn-assign" data-order-id="${id}">
                                        <i class="bi bi-check-circle"></i> Asignar Vendedor
                                    </button>
                                </div>
                                
                                <div class="assign-msg" style="display: none;"></div>
                            </div>
                        </div>
                    `;

                    container.appendChild(col);

                    // Add event listener for this specific card
                    const assignBtn = col.querySelector('.btn-assign');
                    const select = col.querySelector('.vendor-select');
                    const msg = col.querySelector('.assign-msg');
                    const sellerNameEl = col.querySelector('.seller-name');

                    assignBtn.addEventListener('click', async () => {
                        const sellerId = select.value;
                        if (!sellerId) {
                            msg.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Seleccione un vendedor antes de asignar.';
                            msg.className = 'assign-msg error';
                            msg.style.display = 'block';
                            return;
                        }

                        assignBtn.disabled = true;
                        assignBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Asignando...';
                        msg.innerHTML = '<i class="bi bi-clock"></i> Procesando asignación...';
                        msg.className = 'assign-msg';
                        msg.style.display = 'block';

                        try {
                            const path = `admin/orders/${id}/assign`;
                            const body = { sellerId: Number(sellerId) };
                            const res = (window.SABORES360 && SABORES360.API) ? await SABORES360.API.post(path, body) : await (async () => { const r = await fetch(base + path, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) }); return r.json(); })();

                            if (res && res.success) {
                                msg.innerHTML = '<i class="bi bi-check-circle"></i> Vendedor asignado correctamente.';
                                msg.className = 'assign-msg success';

                                // Update seller display
                                const selName = vendors.find(v => Number(v.id) === Number(sellerId));
                                if (selName) {
                                    const sellerBadgeContainer = col.querySelector('.seller-badge, .no-seller-badge').parentElement;
                                    sellerBadgeContainer.innerHTML = `<span class="seller-badge"><i class="bi bi-person-check"></i> ${escapeHtml(selName.name || selName.email)}</span>`;
                                }

                                // Reset select to default
                                select.selectedIndex = 0;
                            } else {
                                msg.innerHTML = `<i class="bi bi-x-circle"></i> ${(res && res.message) ? res.message : 'Error al asignar vendedor.'}`;
                                msg.className = 'assign-msg error';
                            }
                        } catch (err) {
                            msg.innerHTML = '<i class="bi bi-wifi-off"></i> Error de conexión al asignar.';
                            msg.className = 'assign-msg error';
                        } finally {
                            assignBtn.disabled = false;
                            assignBtn.innerHTML = '<i class="bi bi-check-circle"></i> Asignar Vendedor';
                        }
                    });
                });
            } catch (err) {
                container.textContent = 'Error al cargar pedidos o vendedores.';
            }

            // small helper to avoid XSS when inserting strings
            function escapeHtml(str) {
                if (!str) return '';
                return String(str).replace(/[&<>"'`]/g, function (s) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '`': '&#96;' })[s]; });
            }
        })();
    </script>
</body>

</html>