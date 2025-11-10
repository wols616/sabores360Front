<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Dashboard | Sabores360</title>
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

        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.2);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--orange-primary);
            text-shadow: 1px 1px 2px rgba(255, 107, 53, 0.1);
        }

        .stats-icon {
            font-size: 3rem;
            opacity: 0.2;
            color: var(--orange-primary);
        }

        .orders-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
        }

        .order-item {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 107, 53, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .order-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.15);
            border-color: var(--orange-primary);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-pending {
            background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
            color: #e17055;
        }

        .status-confirmed {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
        }

        .status-preparing {
            background: linear-gradient(135deg, var(--orange-light), var(--orange-primary));
            color: white;
        }

        .status-delivered {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }

        .btn-orange {
            background: linear-gradient(45deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
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
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-orange:hover {
            background: var(--orange-primary);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(255, 107, 53, 0.3);
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .welcome-card {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.05), rgba(255, 140, 66, 0.05));
            border: 2px dashed rgba(255, 107, 53, 0.3);
            border-radius: 15px;
        }

        .text-orange {
            color: var(--orange-primary) !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <?php $active = 'dashboard';
        require __DIR__ . '/_vendedor_nav.php'; ?>

        <div class="page-header text-center">
            <h1 class="mb-2">
                <i class="bi bi-shop"></i> Panel de Vendedor
            </h1>
            <p class="mb-0 opacity-75">Gestiona tus pedidos y supervisa tu rendimiento</p>
        </div>

        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card welcome-card">
                    <div class="card-body text-center py-4">
                        <h4 class="text-orange mb-2">
                            <i class="bi bi-emoji-smile"></i> ¡Bienvenido de vuelta!
                        </h4>
                        <p class="text-muted mb-0">Aquí tienes un resumen de tu actividad como vendedor</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mb-4" id="stats-section">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center position-relative">
                        <div class="stats-icon position-absolute top-0 end-0 p-3">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <h5 class="card-title text-muted">Pedidos Pendientes</h5>
                        <div class="stats-number" id="pending-count">
                            <div class="loading-skeleton" style="height: 3rem; border-radius: 8px;"></div>
                        </div>
                        <small class="text-muted">Por procesar</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center position-relative">
                        <div class="stats-icon position-absolute top-0 end-0 p-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h5 class="card-title text-muted">Pedidos del Día</h5>
                        <div class="stats-number" id="today-count">
                            <div class="loading-skeleton" style="height: 3rem; border-radius: 8px;"></div>
                        </div>
                        <small class="text-muted">Hoy</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center position-relative">
                        <div class="stats-icon position-absolute top-0 end-0 p-3">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="card-title text-muted">Ventas del Día</h5>
                        <div class="stats-number" id="sales-today">
                            <div class="loading-skeleton" style="height: 3rem; border-radius: 8px;"></div>
                        </div>
                        <small class="text-muted">Ingresos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center position-relative">
                        <div class="stats-icon position-absolute top-0 end-0 p-3">
                            <i class="bi bi-star"></i>
                        </div>
                        <h5 class="card-title text-muted">Productos Activos</h5>
                        <div class="stats-number" id="products-count">
                            <div class="loading-skeleton" style="height: 3rem; border-radius: 8px;"></div>
                        </div>
                        <small class="text-muted">En catálogo</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Section -->
        <div class="row">
            <div class="col-12">
                <div class="card orders-card">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history text-orange"></i> Pedidos Recientes
                        </h5>
                        <a href="/Sabores360/views/vendedor/orders.php" class="btn btn-outline-orange btn-sm">
                            <i class="bi bi-arrow-right"></i> Ver todos
                        </a>
                    </div>
                    <div class="card-body" id="recent-orders">
                        <!-- Loading skeleton -->
                        <div class="d-flex justify-content-center p-4">
                            <div class="spinner-border text-orange" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order detail modal -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">Detalle del pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="order-detail-loading" class="text-center py-4">
                        <div class="spinner-border text-orange" role="status"><span
                                class="visually-hidden">Cargando...</span></div>
                    </div>
                    <div id="order-detail-content" class="d-none">
                        <div class="mb-3 d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Pedido <span id="od-id" class="fw-bold"></span></h6>
                                <div><small id="od-created" class="text-muted"></small></div>
                            </div>
                            <div>
                                <span id="od-status" class="status-badge"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Cliente</h6>
                                <div id="od-client" class="small text-muted"></div>
                            </div>
                            <div class="col-md-6">
                                <h6>Dirección / Pago</h6>
                                <div id="od-address" class="small text-muted"></div>
                                <div id="od-payment" class="small text-muted"></div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-end">Cantidad</th>
                                        <th class="text-end">Precio unidad</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="od-items">
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <div class="text-end">
                                <div class="small text-muted">Total</div>
                                <div id="od-total" class="fs-5 fw-bold text-success"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php require __DIR__ . '/../../includes/print_api_js.php'; ?>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            // Elements
            const pendingCountEl = document.getElementById('pending-count');
            const todayCountEl = document.getElementById('today-count');
            const salesTodayEl = document.getElementById('sales-today');
            const productsCountEl = document.getElementById('products-count');
            const recentOrdersEl = document.getElementById('recent-orders');

            function formatNumber(num) {
                return Number(num || 0).toLocaleString();
            }

            function formatCurrency(amount) {
                return Number(amount || 0).toFixed(2) + ' €';
            }

            function getStatusBadgeClass(status) {
                const s = (status || '').toString().toLowerCase();
                if (s.includes('pendiente') || s.includes('pending')) return 'status-pending';
                if (s.includes('confirmado') || s.includes('confirmed')) return 'status-confirmed';
                if (s.includes('preparación') || s.includes('preparacion') || s.includes('preparing')) return 'status-preparing';
                if (s.includes('entregado') || s.includes('delivered')) return 'status-delivered';
                return 'status-pending';
            }
            // expose to global scope so modal/viewOrder (outside the IIFE) can reuse it
            try { window.getStatusBadgeClass = getStatusBadgeClass; } catch (e) { /* ignore if not available */ }

            function renderRecentOrders(orders) {
                if (!orders || orders.length === 0) {
                    recentOrdersEl.innerHTML = `
                        <div class="text-center p-4 text-muted">
                            <i class="bi bi-inbox display-4 text-orange opacity-25 d-block mb-3"></i>
                            <h6>No hay pedidos recientes</h6>
                            <small>Los nuevos pedidos aparecerán aquí</small>
                        </div>
                    `;
                    return;
                }

                let html = '<div class="row g-3">';
                orders.forEach(order => {
                    const total = order.totalAmount || order.total_amount || order.total || '0';
                    const status = order.status || order.state || 'Pendiente';
                    const created = order.createdAt || order.created_at || order.date || '';
                    const client = order.client && (order.client.name || order.client.email)
                        ? (order.client.name || order.client.email)
                        : 'Cliente';
                    const badgeClass = getStatusBadgeClass(status);

                    // Format date
                    let formattedDate = '';
                    if (created) {
                        try {
                            const date = new Date(created);
                            formattedDate = date.toLocaleDateString('es-ES', {
                                day: '2-digit',
                                month: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        } catch (e) {
                            formattedDate = created;
                        }
                    }

                    html += `
                        <div class="col-12">
                            <div class="order-item p-3 mb-2">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <strong class="text-orange">#${order.id}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-circle text-muted me-2"></i>
                                            <div>
                                                <div class="fw-medium">${client}</div>
                                                ${order.client && order.client.email ? `<small class="text-muted">${order.client.email}</small>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="status-badge ${badgeClass}">
                                            ${status}
                                        </span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <strong class="text-success">${formatCurrency(total)}</strong>
                                    </div>
                                    <div class="col-md-2 text-muted text-center">
                                        <small>
                                            <i class="bi bi-clock me-1"></i>
                                            ${formattedDate}
                                        </small>
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button class="btn btn-outline-orange btn-sm" onclick="viewOrder(${order.id})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                recentOrdersEl.innerHTML = html;
            }

            function updateStats(pending, todayOrders, salesToday, productsCount) {
                // Update pending orders
                pendingCountEl.innerHTML = formatNumber(pending);

                // Update today orders count
                todayCountEl.innerHTML = formatNumber(todayOrders);

                // Update sales today
                salesTodayEl.innerHTML = formatCurrency(salesToday);

                // Update products count
                productsCountEl.innerHTML = formatNumber(productsCount);
            }

            try {
                // Load dashboard data
                const response = await (window.SABORES360 && SABORES360.API
                    ? SABORES360.API.get('seller/dashboard')
                    : (async () => {
                        const res = await fetch(
                            (window.SABORES360 && SABORES360.API_BASE)
                                ? SABORES360.API_BASE + 'seller/dashboard'
                                : 'http://localhost:8080/api/seller/dashboard',
                            { credentials: 'include' }
                        );
                        const text = await res.text();
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            return { success: res.ok, httpStatus: res.status, raw: text };
                        }
                    })()
                );

                console.debug('seller/dashboard response', response);

                if (response && response.success) {
                    const data = response.data || response;

                    // Extract pending orders count
                    let pending = (data && (typeof data.pending !== 'undefined'
                        ? data.pending
                        : (data.pending_count || data.pendingCount || data.pending_orders || 0))) || 0;

                    // Extract recent orders
                    let recentOrders = [];
                    if (data) {
                        if (Array.isArray(data.recent_orders)) recentOrders = data.recent_orders;
                        else if (Array.isArray(data.recentOrders)) recentOrders = data.recentOrders;
                        else if (Array.isArray(data.orders)) recentOrders = data.orders;
                        else if (Array.isArray(data.items)) recentOrders = data.items;
                        else if (Array.isArray(data.data)) recentOrders = data.data;
                    }

                    // Calculate additional stats. Prefer server-provided fields when available:
                    // - data.today_orders : array of today's orders for this seller
                    // - data.today_sales_total : numeric total for today's sales
                    let todayOrders = 0;
                    let salesToday = 0;
                    let inferredPending = 0;

                    const lowerPendingStates = ['confirmado', 'pendiente', 'pending',
                        'en preparación', 'en preparacion', 'preparacion', 'confirmed',
                        'preparing', 'processing', 'por preparar'];

                    // If backend returned explicit today's orders list or total, use them
                    if (Array.isArray(data.today_orders)) {
                        try {
                            todayOrders = data.today_orders.length;
                            if (!isNaN(Number(data.today_sales_total))) {
                                salesToday = Number(data.today_sales_total);
                            } else {
                                // Sum totals from provided orders as a fallback
                                salesToday = data.today_orders.reduce((acc, o) => {
                                    return acc + (Number(o.totalAmount || o.total_amount || o.total || 0) || 0);
                                }, 0);
                            }

                            // infer pending from recentOrders if present
                            if (Array.isArray(recentOrders) && recentOrders.length) {
                                recentOrders.forEach(order => {
                                    const status = (order.status || order.state || '').toString().toLowerCase();
                                    if (lowerPendingStates.some(ps => status.includes(ps))) {
                                        inferredPending++;
                                    }
                                });
                            }
                        } catch (e) {
                            console.warn('Error using server today_orders:', e);
                        }
                    } else {
                        // Fallback: infer from recentOrders (robust date compare using Date)
                        if (Array.isArray(recentOrders) && recentOrders.length) {
                            try {
                                const now = new Date();
                                recentOrders.forEach(order => {
                                    const dateStr = order.createdAt || order.created_at || order.date || '';
                                    const orderDateObj = dateStr ? new Date(dateStr) : null;
                                    if (orderDateObj &&
                                        orderDateObj.getFullYear() === now.getFullYear() &&
                                        orderDateObj.getMonth() === now.getMonth() &&
                                        orderDateObj.getDate() === now.getDate()) {
                                        todayOrders++;
                                        salesToday += Number(order.totalAmount || order.total_amount || order.total || 0) || 0;
                                    }

                                    const status = (order.status || order.state || '').toString().toLowerCase();
                                    if (lowerPendingStates.some(ps => status.includes(ps))) {
                                        inferredPending++;
                                    }
                                });
                            } catch (e) {
                                console.warn('Error calculating stats fallback:', e);
                            }
                        }
                    }

                    // If backend provided a numeric today_sales_total, prefer it
                    if (!isNaN(Number(data.today_sales_total))) {
                        salesToday = Number(data.today_sales_total);
                    }

                    // Use the maximum between server-provided pending and inferred pending
                    pending = Math.max(Number(pending) || 0, inferredPending);

                    // Get products count (try many fallbacks to handle different API shapes)
                    let productsCount = 0;
                    if (data) {
                        if (!isNaN(Number(data.products_count))) {
                            productsCount = Number(data.products_count);
                        } else if (!isNaN(Number(data.productsCount))) {
                            productsCount = Number(data.productsCount);
                        } else if (!isNaN(Number(data.total_products))) {
                            productsCount = Number(data.total_products);
                        } else if (!isNaN(Number(data.active_products))) {
                            productsCount = Number(data.active_products);
                        } else if (Array.isArray(data.products)) {
                            productsCount = data.products.length;
                        } else if (Array.isArray(data.productsList)) {
                            productsCount = data.productsList.length;
                        } else if (Array.isArray(data.products_list)) {
                            productsCount = data.products_list.length;
                        } else if (Array.isArray(data.items)) {
                            productsCount = data.items.length;
                        } else if (Array.isArray(data.data)) {
                            // try to find products inside data.data
                            if (Array.isArray(data.data.products)) productsCount = data.data.products.length;
                            else if (Array.isArray(data.data.items)) productsCount = data.data.items.length;
                            else productsCount = data.data.length || 0;
                        } else if (data.meta && !isNaN(Number(data.meta.total))) {
                            productsCount = Number(data.meta.total);
                        } else {
                            productsCount = 0;
                        }
                    }

                    // If still zero or missing, try the dedicated endpoint GET /api/products/active-count
                    if (!productsCount || productsCount === 0) {
                        try {
                            let countJson = null;
                            if (window.SABORES360 && SABORES360.API) {
                                const resp = await SABORES360.API.get('products/active-count');
                                countJson = resp || null;
                            } else {
                                const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                                const r = await fetch(base + 'products/active-count', { credentials: 'include' });
                                try { countJson = await r.json(); } catch (e) { countJson = null; }
                            }

                            if (countJson) {
                                if (!isNaN(Number(countJson.active_count))) {
                                    productsCount = Number(countJson.active_count);
                                } else if (countJson.data && !isNaN(Number(countJson.data.active_count))) {
                                    productsCount = Number(countJson.data.active_count);
                                }
                            }
                        } catch (err) {
                            console.warn('Could not fetch products active count:', err);
                        }
                    }

                    // Update the UI
                    updateStats(pending, todayOrders, salesToday, productsCount);
                    renderRecentOrders(recentOrders);

                } else {
                    // Error state
                    recentOrdersEl.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            No se pudieron cargar los datos del dashboard.
                        </div>
                        <details class="mt-3">
                            <summary class="text-muted">Detalles técnicos</summary>
                            <pre class="small mt-2">${JSON.stringify(response, null, 2)}</pre>
                        </details>
                    `;

                    // Set default values
                    updateStats(0, 0, 0, 0);
                }
            } catch (error) {
                console.error('Dashboard error:', error);
                recentOrdersEl.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i>
                        Error al cargar los datos del dashboard.
                    </div>
                `;
                updateStats(0, 0, 0, 0);
            }
        })();

        // View order: open modal and fetch details from GET /api/orders/{id}/details
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
                document.getElementById('od-created').textContent = order.createdAt ? new Date(order.createdAt).toLocaleString('es-ES') : '';

                const statusEl = document.getElementById('od-status');
                statusEl.textContent = order.status || '';
                // map status class
                statusEl.className = 'status-badge ' + (getStatusBadgeClass(order.status) || 'status-pending');

                const client = order.client || {};
                document.getElementById('od-client').innerHTML = `
                    <div><strong>${client.name || client.fullName || client.email || 'Cliente'}</strong></div>
                    <div class="small text-muted">ID: ${client.id || '-'}</div>
                    <div class="small text-muted">${client.email || ''}</div>
                `;

                document.getElementById('od-address').textContent = order.deliveryAddress || client.address || '';
                document.getElementById('od-payment').textContent = order.paymentMethod ? 'Pago: ' + order.paymentMethod : '';

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
                            <td class="text-end">${unit.toFixed(2)} €</td>
                            <td class="text-end">${total.toFixed(2)} €</td>
                        `;
                        itemsEl.appendChild(tr);
                    });
                } else {
                    itemsEl.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay ítems disponibles</td></tr>';
                }

                document.getElementById('od-total').textContent = (Number(order.totalAmount || order.total || order.amount || 0)).toFixed(2) + ' €';

                // Show content
                document.getElementById('order-detail-loading').classList.add('d-none');
                document.getElementById('order-detail-content').classList.remove('d-none');

            } catch (err) {
                console.error('Error loading order details:', err);
                alert('Error al cargar detalle del pedido.');
            }
        }
    </script>
</body>

</html>