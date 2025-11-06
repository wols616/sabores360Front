<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Pedidos</title>

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
            max-width: 1200px;
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

        .orders-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.1);
        }

        .order-card {
            background: white;
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .order-card:hover {
            box-shadow: 0 6px 25px rgba(255, 107, 53, 0.15);
            transform: translateY(-2px);
        }

        .order-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-id {
            font-weight: 700;
            color: var(--orange-primary);
            font-size: 1.1rem;
        }

        .order-info {
            flex: 1;
            min-width: 200px;
        }

        .order-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-preparing {
            background: #f8d7da;
            color: #721c24;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .status-select:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .btn-update {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .btn-view {
            background: white;
            border: 2px solid var(--orange-primary);
            color: var(--orange-primary);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background: var(--orange-primary);
            color: white;
            transform: translateY(-1px);
        }

        .client-info {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0.5rem 0;
        }

        .client-info i {
            color: var(--orange-primary);
            margin-right: 0.5rem;
        }

        .status-msg {
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .status-msg.success {
            background: #d4edda;
            color: #155724;
        }

        .status-msg.error {
            background: #f8d7da;
            color: #721c24;
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 8px;
            height: 100px;
            margin-bottom: 1rem;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--orange-primary);
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <?php
    $active = 'orders';
    require __DIR__ . '/_vendedor_nav.php';
    ?>

    <div class="main-container">
        <div class="page-header">
            <h1>
                <i class="bi bi-box-seam"></i>
                Pedidos Asignados
            </h1>
            <p class="text-muted mb-0">Gestiona y actualiza el estado de tus pedidos</p>
        </div>

        <div class="orders-container">
            <div id="my-orders">
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary)); color: white;">
                    <h5 class="modal-title" id="orderDetailModalLabel">
                        <i class="bi bi-box-seam"></i> Detalle del Pedido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading state -->
                    <div id="order-detail-loading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando detalles del pedido...</p>
                    </div>

                    <!-- Content -->
                    <div id="order-detail-content" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información del Pedido</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>ID:</strong> #<span id="od-id"></span></p>
                                        <p><strong>Fecha:</strong> <span id="od-created"></span></p>
                                        <p><strong>Estado:</strong> <span id="od-status" class="status-badge"></span>
                                        </p>
                                        <p><strong>Método de Pago:</strong> <span id="od-payment"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="bi bi-person"></i> Cliente</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="od-client"></div>
                                        <hr>
                                        <p><strong><i class="bi bi-geo-alt"></i> Dirección:</strong></p>
                                        <p class="text-muted" id="od-address"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bi bi-cart"></i> Productos</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Producto</th>
                                                <th class="text-end">Cantidad</th>
                                                <th class="text-end">Precio Unit.</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="od-items">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end">
                                    <h5 class="text-primary">
                                        <strong>Total: <span id="od-total"></span></strong>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        // Helper functions
        function formatCurrency(amount) {
            return new Intl.NumberFormat('es-ES', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount || 0);
        }

        function getStatusBadgeClass(status) {
            const s = (status || '').toString().toLowerCase();
            if (s.includes('pendiente') || s.includes('pending')) return 'status-pending';
            if (s.includes('confirmado') || s.includes('confirmed')) return 'status-confirmed';
            if (s.includes('preparación') || s.includes('preparacion') || s.includes('preparing')) return 'status-preparing';
            if (s.includes('entregado') || s.includes('delivered')) return 'status-delivered';
            return 'status-pending';
        }

        // Make getStatusBadgeClass available globally for modal
        window.getStatusBadgeClass = getStatusBadgeClass;

        function formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Load and render orders
        (async function () {
            const container = document.getElementById('my-orders');
            try {
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('seller/orders') : (async () => {
                    const res = await fetch((window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE + 'seller/orders' : 'http://localhost:8080/api/seller/orders', { credentials: 'include' });
                    const t = await res.text();
                    try { return JSON.parse(t); } catch (e) { return { success: res.ok, httpStatus: res.status, raw: t }; }
                })());

                console.debug('seller/orders response', d);

                // Helper function to find orders array in response
                function findArrayOfObjectsWithId(obj) {
                    if (!obj) return null;
                    if (Array.isArray(obj) && obj.length && obj[0] && (obj[0].id !== undefined)) return obj;
                    if (obj.orders && Array.isArray(obj.orders)) return obj.orders;
                    if (obj.data && Array.isArray(obj.data.orders)) return obj.data.orders;
                    if (obj.data && Array.isArray(obj.data)) return obj.data;

                    // Try to find any property that's an array of objects with id
                    for (const k of Object.keys(obj)) {
                        const v = obj[k];
                        if (Array.isArray(v) && v.length && v[0] && (v[0].id !== undefined)) return v;
                    }
                    return null;
                }

                // Prefer the explicit shape { success:true, data:{ orders: [...] } }
                const orders = (d && d.data && Array.isArray(d.data.orders)) ? d.data.orders :
                    ((d && Array.isArray(d.orders)) ? d.orders :
                        (findArrayOfObjectsWithId(d) || findArrayOfObjectsWithId(d && d.data) || null));

                if (orders && Array.isArray(orders)) {
                    if (orders.length === 0) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="bi bi-box-seam"></i>
                                <h4>No tienes pedidos asignados</h4>
                                <p>Los nuevos pedidos aparecerán aquí cuando se te asignen.</p>
                            </div>
                        `;
                        return;
                    }

                    container.innerHTML = '';
                    orders.forEach(o => {
                        const status = o.status || o.state || 'Pendiente';
                        const clientName = (o.client && (o.client.name || o.client.email)) ? (o.client.name || o.client.email) : 'Cliente';
                        const clientEmail = (o.client && o.client.email) ? o.client.email : '';
                        const addr = o.deliveryAddress || o.delivery_address || '';
                        const total = o.totalAmount || o.total_amount || o.total || 0;
                        const createdAt = o.createdAt || o.created_at || '';

                        const orderCard = document.createElement('div');
                        orderCard.className = 'order-card';
                        orderCard.setAttribute('data-id', o.id);
                        orderCard.setAttribute('data-total', total);
                        orderCard.setAttribute('data-client', clientName);
                        orderCard.setAttribute('data-addr', addr);

                        orderCard.innerHTML = `
                            <div class="order-header">
                                <div class="order-info">
                                    <div class="order-id">#${o.id}</div>
                                    <div class="client-info">
                                        <i class="bi bi-person"></i>
                                        <strong>${clientName}</strong>
                                        ${clientEmail ? `<span class="text-muted">(${clientEmail})</span>` : ''}
                                    </div>
                                    <div class="client-info">
                                        <i class="bi bi-geo-alt"></i>
                                        ${addr || 'Dirección no especificada'}
                                    </div>
                                    <div class="client-info">
                                        <i class="bi bi-calendar"></i>
                                        ${formatDate(createdAt)}
                                    </div>
                                </div>
                                <div class="order-actions">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="status-badge ${getStatusBadgeClass(status)}">${status}</span>
                                        <strong class="text-success">${formatCurrency(total)}</strong>
                                    </div>
                                    <div class="d-flex gap-2 w-100">
                                        <select class="status-select form-select form-select-sm" data-id="${o.id}">
                                            <option value="Pendiente" ${status.toLowerCase() === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                                            <option value="Confirmado" ${status.toLowerCase() === 'confirmado' ? 'selected' : ''}>Confirmado</option>
                                            <option value="En preparación" ${status.toLowerCase().includes('preparación') || status.toLowerCase().includes('preparacion') ? 'selected' : ''}>En preparación</option>
                                            <option value="En camino" ${status.toLowerCase().includes('camino') ? 'selected' : ''}>En camino</option>
                                            <option value="Entregado" ${status.toLowerCase() === 'entregado' ? 'selected' : ''}>Entregado</option>
                                            <option value="Cancelado" ${status.toLowerCase() === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                                        </select>
                                        <button class="btn btn-update btn-sm" data-id="${o.id}">
                                            <i class="bi bi-arrow-repeat"></i> Actualizar
                                        </button>
                                        <button class="btn btn-view btn-sm" data-id="${o.id}" onclick="viewOrder(${o.id})">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="status-msg"></div>
                        `;

                        container.appendChild(orderCard);
                    });

                    // Event handlers for update buttons
                    container.addEventListener('click', async (ev) => {
                        if (ev.target.matches('.btn-update') || ev.target.closest('.btn-update')) {
                            const btn = ev.target.matches('.btn-update') ? ev.target : ev.target.closest('.btn-update');
                            const id = btn.getAttribute('data-id');
                            const card = btn.closest('.order-card');
                            const select = card && card.querySelector('.status-select');
                            const msg = card && card.querySelector('.status-msg');

                            if (!select) return;

                            const newStatus = select.value && select.value.toString().trim();
                            if (!newStatus) {
                                if (msg) {
                                    msg.textContent = 'Seleccione un estado antes de actualizar.';
                                    msg.className = 'status-msg error';
                                }
                                return;
                            }

                            btn.disabled = true;
                            btn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Actualizando...';

                            if (msg) {
                                msg.textContent = 'Actualizando estado...';
                                msg.className = 'status-msg';
                            }

                            try {
                                const path = `seller/orders/${id}/status`;
                                const body = { newStatus: newStatus, status: newStatus };
                                const res = (window.SABORES360 && SABORES360.API) ?
                                    await SABORES360.API.post(path, body) :
                                    await (async () => {
                                        const r = await fetch((window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE + path : `http://localhost:8080/api/${path}`, {
                                            method: 'POST',
                                            credentials: 'include',
                                            headers: { 'Content-Type': 'application/json' },
                                            body: JSON.stringify(body)
                                        });
                                        const t = await r.text();
                                        try { return JSON.parse(t); } catch (e) { return { success: r.ok, httpStatus: r.status, raw: t }; }
                                    })();

                                if (res && res.success) {
                                    if (msg) {
                                        msg.textContent = 'Estado actualizado correctamente.';
                                        msg.className = 'status-msg success';
                                    }

                                    // Update status badge
                                    const statusBadge = card.querySelector('.status-badge');
                                    if (statusBadge) {
                                        statusBadge.textContent = newStatus;
                                        statusBadge.className = 'status-badge ' + getStatusBadgeClass(newStatus);
                                    }

                                    setTimeout(() => {
                                        if (msg) msg.textContent = '';
                                    }, 3000);
                                } else {
                                    if (msg) {
                                        msg.textContent = (res && res.message) ? res.message : 'Error al actualizar estado.';
                                        msg.className = 'status-msg error';
                                    }
                                }
                            } catch (err) {
                                if (msg) {
                                    msg.textContent = 'Error de conexión';
                                    msg.className = 'status-msg error';
                                }
                            } finally {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Actualizar';
                            }
                        }
                    });
                } else {
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="bi bi-exclamation-triangle"></i>
                            <h4>No se pudieron cargar los pedidos</h4>
                            <p>Inténtalo de nuevo más tarde.</p>
                            <details class="mt-3">
                                <summary class="text-muted">Información técnica</summary>
                                <pre class="small mt-2">${JSON.stringify(d, null, 2)}</pre>
                            </details>
                        </div>
                    `;
                }
            } catch (err) {
                console.error('Error loading orders:', err);
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-x-circle"></i>
                        <h4>Error al cargar pedidos</h4>
                        <p>Ha ocurrido un error al conectar con el servidor.</p>
                    </div>
                `;
            }
        })();

        // Order detail modal function
        async function viewOrder(orderId) {
            try {
                const modalEl = document.getElementById('orderDetailModal');
                const bsModal = new bootstrap.Modal(modalEl);

                // Reset modal content
                document.getElementById('order-detail-loading').classList.remove('d-none');
                document.getElementById('order-detail-content').classList.add('d-none');
                document.getElementById('od-id').textContent = '';
                document.getElementById('od-created').textContent = '';
                document.getElementById('od-status').textContent = '';
                document.getElementById('od-client').textContent = '';
                document.getElementById('od-address').textContent = '';
                document.getElementById('od-payment').textContent = '';
                document.getElementById('od-items').innerHTML = '';
                document.getElementById('od-total').textContent = '';

                bsModal.show();

                // Build endpoint and fetch
                let detailJson = null;
                if (window.SABORES360 && SABORES360.API) {
                    try {
                        detailJson = await SABORES360.API.get(`orders/${orderId}/details`);
                    } catch (e) { detailJson = null; }
                } else {
                    const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                    try {
                        const r = await fetch(base + `orders/${orderId}/details`, { credentials: 'include' });
                        detailJson = await r.json();
                    } catch (e) { detailJson = null; }
                }

                if (!detailJson || !detailJson.order) {
                    // Try alternate shapes
                    if (detailJson && detailJson.data && detailJson.data.order) detailJson = { order: detailJson.data.order };
                }

                if (!detailJson || !detailJson.order) {
                    document.getElementById('order-detail-loading').classList.add('d-none');
                    document.getElementById('order-detail-content').classList.remove('d-none');
                    document.getElementById('od-client').innerHTML = '<div class="text-danger">No se pudo cargar el detalle del pedido.</div>';
                    return;
                }

                const order = detailJson.order;

                // Fill modal fields
                document.getElementById('od-id').textContent = order.id || '';
                document.getElementById('od-created').textContent = order.createdAt ? formatDate(order.createdAt) : '';

                const statusEl = document.getElementById('od-status');
                statusEl.textContent = order.status || '';
                statusEl.className = 'status-badge ' + (getStatusBadgeClass(order.status) || 'status-pending');

                const client = order.client || {};
                document.getElementById('od-client').innerHTML = `
                    <div><strong>${client.name || client.fullName || client.email || 'Cliente'}</strong></div>
                    <div class="small text-muted">ID: ${client.id || '-'}</div>
                    <div class="small text-muted">${client.email || ''}</div>
                `;

                document.getElementById('od-address').textContent = order.deliveryAddress || client.address || 'No especificada';
                document.getElementById('od-payment').textContent = order.paymentMethod ? order.paymentMethod : 'No especificado';

                // Items
                const itemsEl = document.getElementById('od-items');
                itemsEl.innerHTML = '';
                if (Array.isArray(order.items) && order.items.length) {
                    order.items.forEach(it => {
                        const prod = it.productName || it.name || it.product || it.product_title || ('#' + (it.productId || it.product_id || ''));
                        const qty = it.quantity || it.qty || 0;
                        const unit = Number(it.unitPrice || it.unit_price || it.price || 0);
                        const total = Number(it.total || unit * qty || 0);
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${prod}</td>
                            <td class="text-end">${qty}</td>
                            <td class="text-end">${formatCurrency(unit)}</td>
                            <td class="text-end">${formatCurrency(total)}</td>
                        `;
                        itemsEl.appendChild(tr);
                    });
                } else {
                    itemsEl.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay productos disponibles</td></tr>';
                }

                document.getElementById('od-total').textContent = formatCurrency(order.totalAmount || order.total || order.amount || 0);

                // Show content
                document.getElementById('order-detail-loading').classList.add('d-none');
                document.getElementById('order-detail-content').classList.remove('d-none');

            } catch (err) {
                console.error('Error loading order details:', err);
                alert('Error al cargar detalle del pedido.');
            }
        }

        // Add spinning animation for loading states
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .spin {
                animation: spin 1s linear infinite;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>