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

        .btn-outline-orange {
            color: var(--orange-primary);
            border-color: var(--orange-primary);
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-orange:hover {
            background: var(--orange-primary);
            border-color: var(--orange-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
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
                            <i class="bi bi-search"></i> Buscar pedidos
                        </label>
                        <input type="text" class="form-control" id="search-input"
                            placeholder="Buscar por ID, cliente, email...">
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2">
                        <label class="form-label">
                            <i class="bi bi-flag"></i> Estado
                        </label>
                        <select class="form-select" id="status-filter">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmado">Confirmado</option>
                            <option value="en preparación">En Preparación</option>
                            <option value="en camino">En Camino</option>
                            <option value="entregado">Entregado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>

                    <!-- Seller Filter -->
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="bi bi-person-badge"></i> Vendedor
                        </label>
                        <select class="form-select" id="seller-filter">
                            <option value="">Todos los vendedores</option>
                            <option value="unassigned">Sin asignar</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="bi bi-calendar-range"></i> Rango de fechas
                        </label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="date-from" title="Desde">
                            <input type="date" class="form-control" id="date-to" title="Hasta">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-outline-orange btn-sm" id="clear-filters">
                            <i class="bi bi-x-circle"></i> Limpiar Filtros
                        </button>
                        <div class="ms-auto">
                            <span class="badge bg-secondary" id="results-count">0 resultados</span>
                        </div>
                    </div>
                </div>
            </div>
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

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary)); color: white;">
                    <h5 class="modal-title" id="orderDetailsModalLabel">
                        <i class="bi bi-receipt-cutoff"></i> Detalles del Pedido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-orange" role="status">
                            <span class="visually-hidden">Cargando detalles...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando detalles del pedido...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        // Authentication helpers
        function getCookie(name) {
            const value = "; " + document.cookie;
            const parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
            return null;
        }

        function getAuthToken() {
            return getCookie('auth_token') || localStorage.getItem('auth_token');
        }

        function buildAuthHeaders() {
            const token = getAuthToken();
            const headers = {
                'Content-Type': 'application/json'
            };
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
            return headers;
        }

        // Order details functionality
        async function fetchOrderDetails(orderId) {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const modal = document.getElementById('orderDetailsModal');
            const content = document.getElementById('orderDetailsContent');

            // Show loading state
            content.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-orange" role="status">
                        <span class="visually-hidden">Cargando detalles...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando detalles del pedido...</p>
                </div>
            `;

            try {
                let orderData;

                if (window.SABORES360 && SABORES360.API) {
                    orderData = await SABORES360.API.get(`admin/orders/${orderId}`);
                } else {
                    const response = await fetch(`${base}admin/orders/${orderId}`, {
                        method: 'GET',
                        headers: buildAuthHeaders(),
                        credentials: 'include'
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    orderData = await response.json();
                }

                // Normalize order data
                let order = null;
                if (orderData) {
                    if (orderData.order) order = orderData.order;
                    else if (orderData.data && orderData.data.order) order = orderData.data.order;
                    else if (orderData.data) order = orderData.data;
                    else order = orderData;
                }

                if (!order) {
                    throw new Error('Datos del pedido no encontrados');
                }

                displayOrderDetails(order);

            } catch (error) {
                console.error('Error fetching order details:', error);
                content.innerHTML = `
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <strong>Error al cargar los detalles:</strong><br>
                            ${error.message || 'No se pudieron cargar los detalles del pedido. Intente nuevamente.'}
                        </div>
                    </div>
                `;
            }
        }

        function displayOrderDetails(order) {
            const content = document.getElementById('orderDetailsContent');
            const modalTitle = document.getElementById('orderDetailsModalLabel');

            // Update modal title
            modalTitle.innerHTML = `<i class="bi bi-receipt-cutoff"></i> Pedido #${escapeHtml(String(order.id || ''))}`;

            // Extract order information
            const id = order.id || '';
            const status = order.status || order.state || '';
            const total = order.totalAmount || order.total_amount || order.total || '';
            const created = order.createdAt || order.created_at || order.date || '';
            const deliveryAddress = order.deliveryAddress || order.delivery_address || '';
            const paymentMethod = order.paymentMethod || order.payment_method || '';
            const items = order.items || order.products || [];
            const client = order.client || order.user || {};
            const seller = order.seller || {};

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

            let itemsHtml = '';
            if (items && items.length > 0) {
                items.forEach(item => {
                    const name = item.name || item.product_name || item.title || '';
                    const quantity = item.quantity || 1;
                    const price = item.price || item.unit_price || 0;
                    const subtotal = item.subtotal || (quantity * price) || 0;

                    itemsHtml += `
                        <tr>
                            <td>${escapeHtml(name)}</td>
                            <td class="text-center">${quantity}</td>
                            <td class="text-end">$${parseFloat(price).toFixed(2)}</td>
                            <td class="text-end fw-bold">$${parseFloat(subtotal).toFixed(2)}</td>
                        </tr>
                    `;
                });
            } else {
                itemsHtml = `
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            <i class="bi bi-cart-x"></i> No hay productos en este pedido
                        </td>
                    </tr>
                `;
            }

            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-orange mb-3">
                            <i class="bi bi-info-circle"></i> Información General
                        </h6>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>ID:</strong></div>
                                    <div class="col-sm-8">#${escapeHtml(String(id))}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Estado:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge ${statusClass}">${escapeHtml(status)}</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Fecha:</strong></div>
                                    <div class="col-sm-8">${escapeHtml(created)}</div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-sm-4"><strong>Total:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="h5 text-orange mb-0">$${parseFloat(total || 0).toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-orange mb-3">
                            <i class="bi bi-person"></i> Cliente y Entrega
                        </h6>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Cliente:</strong></div>
                                    <div class="col-sm-8">${escapeHtml(client.name || client.email || 'No especificado')}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8">${escapeHtml(client.email || 'No especificado')}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Dirección:</strong></div>
                                    <div class="col-sm-8">${escapeHtml(deliveryAddress || 'No especificada')}</div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-sm-4"><strong>Pago:</strong></div>
                                    <div class="col-sm-8">${escapeHtml(paymentMethod || 'No especificado')}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                ${seller && (seller.name || seller.email) ? `
                <div class="row mb-3">
                    <div class="col-12">
                        <h6 class="text-orange mb-3">
                            <i class="bi bi-person-badge"></i> Vendedor Asignado
                        </h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-check-fill text-success me-2 fs-4"></i>
                                    <div>
                                        <div class="fw-bold">${escapeHtml(seller.name || seller.email)}</div>
                                        ${seller.email && seller.name ? `<div class="text-muted small">${escapeHtml(seller.email)}</div>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ` : ''}

                <div class="row">
                    <div class="col-12">
                        <h6 class="text-orange mb-3">
                            <i class="bi bi-cart"></i> Productos del Pedido
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Precio Unit.</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml}
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                        <td class="text-end fw-bold text-orange fs-5">$${parseFloat(total || 0).toFixed(2)}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        }

        // Helper function to avoid XSS when inserting strings
        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>"'`]/g, function (s) {
                return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '`': '&#96;' })[s];
            });
        }

        // Main orders loading functionality
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('orders-list');

            // Global variables for filtering
            let allOrders = [];
            let allVendors = [];
            let filteredOrders = [];

            // Get filter elements
            const searchInput = document.getElementById('search-input');
            const statusFilter = document.getElementById('status-filter');
            const sellerFilter = document.getElementById('seller-filter');
            const dateFromFilter = document.getElementById('date-from');
            const dateToFilter = document.getElementById('date-to');
            const clearFiltersBtn = document.getElementById('clear-filters');
            const resultsCount = document.getElementById('results-count');

            container.textContent = 'Cargando...';

            try {
                // Fetch vendors and orders in parallel
                const [vendorsResp, ordersResp] = await Promise.all([
                    (window.SABORES360 && SABORES360.API) ? SABORES360.API.get('admin/vendors') : (async () => { const r = await fetch(base + 'admin/vendors', { credentials: 'include' }); return r.json(); })(),
                    (window.SABORES360 && SABORES360.API) ? SABORES360.API.get('admin/orders') : (async () => { const r = await fetch(base + 'admin/orders', { credentials: 'include' }); return r.json(); })()
                ]);

                // normalize vendors list
                if (vendorsResp) {
                    if (Array.isArray(vendorsResp.vendors)) allVendors = vendorsResp.vendors;
                    else if (vendorsResp.data && Array.isArray(vendorsResp.data.vendors)) allVendors = vendorsResp.data.vendors;
                    else if (Array.isArray(vendorsResp.data)) allVendors = vendorsResp.data;
                }

                // normalize orders list
                const d = ordersResp;
                if (d) {
                    if (Array.isArray(d.orders)) allOrders = d.orders;
                    else if (d.data && Array.isArray(d.data.orders)) allOrders = d.data.orders;
                    else if (Array.isArray(d.data)) allOrders = d.data;
                    else if (Array.isArray(d.items)) allOrders = d.items;
                }

                // Populate seller filter
                populateSellerFilter();

                // Initial render
                filteredOrders = [...allOrders];
                renderOrders();

            } catch (err) {
                container.textContent = 'Error al cargar pedidos o vendedores.';
                console.error('Error loading orders:', err);
            }

            function populateSellerFilter() {
                const sellerSelect = document.getElementById('seller-filter');
                const currentValue = sellerSelect.value;

                // Clear existing options except default ones
                sellerSelect.innerHTML = `
                    <option value="">Todos los vendedores</option>
                    <option value="unassigned">Sin asignar</option>
                `;

                // Add vendor options
                allVendors.forEach(vendor => {
                    const option = document.createElement('option');
                    option.value = vendor.id;
                    option.textContent = vendor.name || vendor.email || `Vendedor ${vendor.id}`;
                    sellerSelect.appendChild(option);
                });

                // Restore previous selection if still valid
                if (currentValue) {
                    sellerSelect.value = currentValue;
                }
            }

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const statusValue = statusFilter.value.toLowerCase();
                const sellerValue = sellerFilter.value;
                const dateFrom = dateFromFilter.value;
                const dateTo = dateToFilter.value;

                filteredOrders = allOrders.filter(order => {
                    // Search filter (ID, client name, email)
                    if (searchTerm) {
                        const id = String(order.id || '').toLowerCase();
                        const clientName = String(order.client?.name || '').toLowerCase();
                        const clientEmail = String(order.client?.email || '').toLowerCase();
                        const searchMatch = id.includes(searchTerm) ||
                            clientName.includes(searchTerm) ||
                            clientEmail.includes(searchTerm);
                        if (!searchMatch) return false;
                    }

                    // Status filter
                    if (statusValue) {
                        const orderStatus = String(order.status || order.state || '').toLowerCase();
                        if (orderStatus !== statusValue) return false;
                    }

                    // Seller filter
                    if (sellerValue) {
                        if (sellerValue === 'unassigned') {
                            const hasSeller = order.seller && (order.seller.id || order.seller.name || order.seller.email);
                            if (hasSeller) return false;
                        } else {
                            const sellerId = String(order.seller?.id || '');
                            if (sellerId !== sellerValue) return false;
                        }
                    }

                    // Date range filter
                    if (dateFrom || dateTo) {
                        const orderDate = order.createdAt || order.created_at || order.date;
                        if (!orderDate) return false;

                        const orderDateObj = new Date(orderDate);
                        const fromDateObj = dateFrom ? new Date(dateFrom) : null;
                        const toDateObj = dateTo ? new Date(dateTo + 'T23:59:59') : null;

                        if (fromDateObj && orderDateObj < fromDateObj) return false;
                        if (toDateObj && orderDateObj > toDateObj) return false;
                    }

                    return true;
                });

                renderOrders();
            }

            function renderOrders() {
                // Update results count
                resultsCount.textContent = `${filteredOrders.length} resultado${filteredOrders.length !== 1 ? 's' : ''}`;

                if (!filteredOrders || !filteredOrders.length) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-cart-x display-1 text-muted opacity-50"></i>
                            <h4 class="text-muted mt-3">No hay pedidos</h4>
                            <p class="text-muted">${allOrders.length > 0 ? 'No se encontraron pedidos con los filtros aplicados' : 'Los pedidos aparecerán aquí cuando los clientes realicen compras'}</p>
                            ${allOrders.length > 0 ? '<button class="btn btn-outline-orange btn-sm" onclick="clearAllFilters()"><i class="bi bi-x-circle"></i> Limpiar Filtros</button>' : ''}
                        </div>
                    `;
                    return;
                }

                // render orders with seller info and assign control
                container.innerHTML = '';
                filteredOrders.forEach(o => {
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

                    allVendors.forEach(v => {
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
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-orange btn-details" data-order-id="${id}">
                                        <i class="bi bi-eye"></i> Ver Detalles
                                    </button>
                                    <button class="btn btn-orange btn-assign" data-order-id="${id}">
                                        <i class="bi bi-check-circle"></i> Asignar Vendedor
                                    </button>
                                </div>
                                
                                <div class="assign-msg" style="display: none;"></div>
                            </div>
                        </div>
                    `;

                    container.appendChild(col);

                    // Add event listeners for this specific card
                    const assignBtn = col.querySelector('.btn-assign');
                    const detailsBtn = col.querySelector('.btn-details');
                    const select = col.querySelector('.vendor-select');
                    const msg = col.querySelector('.assign-msg');
                    const sellerNameEl = col.querySelector('.seller-name');

                    // Details button event listener
                    detailsBtn.addEventListener('click', () => {
                        fetchOrderDetails(id);
                        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                        modal.show();
                    });

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
                                const selName = allVendors.find(v => Number(v.id) === Number(sellerId));
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
            }

            // Event listeners for filters
            searchInput.addEventListener('input', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            sellerFilter.addEventListener('change', applyFilters);
            dateFromFilter.addEventListener('change', applyFilters);
            dateToFilter.addEventListener('change', applyFilters);

            // Clear filters functionality
            clearFiltersBtn.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = '';
                sellerFilter.value = '';
                dateFromFilter.value = '';
                dateToFilter.value = '';
                applyFilters();
            });

            // Global function for clear filters button in no results state
            window.clearAllFilters = () => {
                searchInput.value = '';
                statusFilter.value = '';
                sellerFilter.value = '';
                dateFromFilter.value = '';
                dateToFilter.value = '';
                applyFilters();
            };

        })();
    </script>
</body>

</html>