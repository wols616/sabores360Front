<?php
require __DIR__ . '/../../includes/auth_check.php';
require_auth();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mis Pedidos</title>

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
            max-width: 1000px;
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
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .order-card:hover {
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.15);
            transform: translateY(-2px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-id {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--orange-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .order-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .order-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        .status-ready {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #e2e3e5;
            color: #383d41;
        }

        .status-cancelled {
            background: #f5c6cb;
            color: #721c24;
        }

        .order-info {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            align-items: center;
        }

        .order-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .order-total {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--orange-dark);
        }

        .order-address {
            color: #6c757d;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .order-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-view-details {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view-details:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .btn-reorder {
            background: white;
            border: 2px solid var(--orange-primary);
            color: var(--orange-primary);
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-reorder:hover {
            background: var(--orange-primary);
            color: white;
            transform: translateY(-1px);
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 12px;
            height: 120px;
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
            padding: 4rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--orange-primary);
            margin-bottom: 1rem;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .modal-title {
            font-weight: 700;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .order-detail-section {
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-weight: 700;
            color: var(--orange-primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }

        .detail-value {
            color: #333;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 600;
            color: #333;
        }

        .item-quantity {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .item-price {
            font-weight: 700;
            color: var(--orange-dark);
        }

        .total-row {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .total-amount {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--orange-dark);
        }

        /* Search and Filter Styles */
        .search-container .input-group-text {
            background: white;
            border-color: #dee2e6;
        }

        .search-container .form-control {
            border-color: #dee2e6;
        }

        .search-container .form-control:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .form-select:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .filter-summary .badge {
            background: var(--orange-primary);
            color: white;
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .filter-summary .badge .remove {
            cursor: pointer;
            font-size: 0.9rem;
            margin-left: 0.25rem;
            opacity: 0.8;
        }

        .filter-summary .badge .remove:hover {
            opacity: 1;
        }

        #clearFilters {
            border-color: var(--orange-primary);
            color: var(--orange-primary);
        }

        #clearFilters:hover {
            background: var(--orange-primary);
            border-color: var(--orange-primary);
            color: white;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .orders-container {
                padding: 1.5rem;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-info {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .order-actions {
                width: 100%;
                justify-content: stretch;
            }

            .btn-view-details,
            .btn-reorder {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <?php
    $active = 'orders';
    require __DIR__ . '/_cliente_nav.php';
    ?>

    <div class="main-container">
        <div class="page-header">
            <h1>
                <i class="bi bi-bag-check"></i>
                Mis Pedidos
            </h1>
            <p class="text-muted mb-0">Revisa el estado y detalles de tus pedidos</p>
        </div>

        <div class="orders-container">
            <!-- Search and Filters -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" id="searchInput"
                                placeholder="Buscar por ID de pedido...">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Todos los estados</option>
                        <option value="pending">Pendiente</option>
                        <option value="confirmed">Confirmado</option>
                        <option value="preparing">Preparando</option>
                        <option value="ready">Listo</option>
                        <option value="delivered">Entregado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="dateFilter">
                        <option value="">Todas las fechas</option>
                        <option value="today">Hoy</option>
                        <option value="week">Última semana</option>
                        <option value="month">Último mes</option>
                        <option value="3months">Últimos 3 meses</option>
                    </select>
                </div>
            </div>

            <!-- Filter Summary and Clear Button -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="filter-summary d-flex align-items-center gap-2 flex-wrap">
                        <span class="text-muted small">
                            <span id="orderCount">0</span> pedidos encontrados
                        </span>
                        <span id="activeFilters"></span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearFilters"
                        style="display: none;">
                        <i class="bi bi-x-circle me-1"></i>
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <div id="orders">
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">
                        <i class="bi bi-receipt me-2"></i>
                        Detalles del Pedido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
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
        let allOrders = [];
        let filteredOrders = [];

        // Helper functions
        function getStatusClass(status) {
            const statusMap = {
                'pending': 'status-pending',
                'confirmed': 'status-confirmed',
                'preparing': 'status-preparing',
                'ready': 'status-ready',
                'delivered': 'status-delivered',
                'cancelled': 'status-cancelled'
            };
            return statusMap[status?.toLowerCase()] || 'status-pending';
        }

        function getStatusIcon(status) {
            const iconMap = {
                'pending': 'clock',
                'confirmed': 'check-circle',
                'preparing': 'tools',
                'ready': 'bag-check',
                'delivered': 'truck',
                'cancelled': 'x-circle'
            };
            return iconMap[status?.toLowerCase()] || 'clock';
        }

        function getStatusText(status) {
            const textMap = {
                'pending': 'Pendiente',
                'confirmed': 'Confirmado',
                'preparing': 'Preparando',
                'ready': 'Listo',
                'delivered': 'Entregado',
                'cancelled': 'Cancelado'
            };
            return textMap[status?.toLowerCase()] || status || 'Pendiente';
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                return dateString;
            }
        }

        function formatCurrency(amount) {
            const num = parseFloat(amount) || 0;
            return num.toLocaleString('es-ES', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2
            });
        }

        async function fetchOrderDetails(orderId) {
            try {
                const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                const url = base + `client/orders/${orderId}`;

                console.debug('Fetching order details from:', url);

                const d = await (window.SABORES360 && SABORES360.API ?
                    SABORES360.API.get(`client/orders/${orderId}`) :
                    (async () => {
                        const res = await fetch(url, { credentials: 'include' });
                        const t = await res.text();

                        console.debug('Raw response:', {
                            status: res.status,
                            statusText: res.statusText,
                            ok: res.ok,
                            headers: Object.fromEntries(res.headers.entries()),
                            body: t
                        });

                        try {
                            return JSON.parse(t);
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            return {
                                success: res.ok,
                                httpStatus: res.status,
                                statusText: res.statusText,
                                raw: t,
                                parseError: e.message
                            };
                        }
                    })());

                console.log('=== ORDER DETAILS DEBUG ===');
                console.log('Full parsed response:', JSON.stringify(d, null, 2));
                console.log('Response type:', typeof d);
                console.log('Has success property:', 'success' in d);
                console.log('Success value:', d?.success);
                console.log('Has order property:', 'order' in d);
                console.log('Order value:', d?.order);
                console.log('=== END DEBUG ===');

                // Check for various success conditions and response shapes
                // Handle multiple possible response structures:
                // 1. { order: OrderDetailDto } (API docs format)
                // 2. { success: true, data: OrderDetailDto } (actual backend format)
                // 3. { success: true, data: { order: OrderDetailDto } } (nested format)

                let orderData = null;

                if (d && d.order && typeof d.order === 'object') {
                    // Format 1: Direct order property
                    console.log('✅ Found order data in direct order property');
                    orderData = d.order;
                } else if (d && d.success === true && d.data && typeof d.data === 'object') {
                    // Format 2: Order data in success response data
                    if (d.data.order && typeof d.data.order === 'object') {
                        // Format 3: Nested order in data
                        console.log('✅ Found order data nested in data.order');
                        orderData = d.data.order;
                    } else if (d.data.id || d.data.status || d.data.totalAmount) {
                        // Format 2: Order data directly in data
                        console.log('✅ Found order data directly in data property');
                        orderData = d.data;
                    }
                }

                if (orderData) {
                    console.log('✅ Returning order data:', orderData);
                    return orderData;
                }

                // Handle error cases
                if (d && d.order === null) {
                    // Explicit null order (not found)
                    console.log('❌ Order is null (not found)');
                    throw new Error('Pedido no encontrado');
                } else if (d && d.success === false) {
                    // Explicit error response
                    const errorMsg = d.message || d.error || 'Error al cargar detalles del pedido';
                    console.log('❌ Explicit error response:', errorMsg);
                    throw new Error(`Error del servidor: ${errorMsg}`);
                } else if (d && d.httpStatus && d.httpStatus >= 400) {
                    // HTTP error status
                    const errorMsg = d.statusText || `Error HTTP ${d.httpStatus}`;
                    console.log('❌ HTTP error:', errorMsg);
                    throw new Error(`Error de conexión: ${errorMsg}`);
                } else if (d && d.raw && d.parseError) {
                    // JSON parsing error
                    console.log('❌ Parse error:', d.parseError, 'Raw:', d.raw);
                    throw new Error(`Error de formato: ${d.parseError}. Respuesta: ${d.raw.substring(0, 200)}...`);
                } else if (d && d.message && d.message.includes('forbidden')) {
                    // Forbidden access (based on API docs)
                    console.log('❌ Forbidden access');
                    throw new Error('No tienes permisos para ver este pedido');
                } else {
                    // Unexpected response structure
                    console.error('❌ Unexpected response structure. Full object:', d);
                    console.error('Object keys:', d ? Object.keys(d) : 'null/undefined');

                    // Try to extract useful error info
                    let debugInfo = 'No se pudo encontrar datos del pedido en la respuesta.';
                    if (d) {
                        debugInfo += ` Estructura recibida: { ${Object.keys(d).join(', ')} }`;
                        if (d.data && typeof d.data === 'object') {
                            debugInfo += `. Datos: { ${Object.keys(d.data).join(', ')} }`;
                        }
                        if (d.message) debugInfo += `. Mensaje: ${d.message}`;
                        if (d.error) debugInfo += `. Error: ${d.error}`;
                    }

                    throw new Error(debugInfo);
                }
            } catch (err) {
                console.error('Error fetching order details:', err);

                // If it's already our custom error, just re-throw
                if (err.message.includes('Error del servidor:') ||
                    err.message.includes('Error de conexión:') ||
                    err.message.includes('Error de formato:') ||
                    err.message.includes('Pedido no encontrado') ||
                    err.message.includes('Respuesta inesperada')) {
                    throw err;
                }

                // For network errors or other unexpected errors
                throw new Error(`Error de red: ${err.message}`);
            }
        }

        function renderOrderDetails(order) {
            const content = document.getElementById('orderDetailsContent');
            const modalTitle = document.getElementById('orderDetailsModalLabel');

            modalTitle.innerHTML = `
                <i class="bi bi-receipt me-2"></i>
                Pedido #${order.id}
            `;

            content.innerHTML = `
                <div class="order-detail-section">
                    <div class="section-title">
                        <i class="bi bi-info-circle"></i>
                        Información General
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Estado:</span>
                        <span class="detail-value">
                            <span class="order-status ${getStatusClass(order.status)}">
                                <i class="bi bi-${getStatusIcon(order.status)}"></i>
                                ${getStatusText(order.status)}
                            </span>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Fecha:</span>
                        <span class="detail-value">${formatDate(order.createdAt || order.created_at)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Dirección:</span>
                        <span class="detail-value">${order.deliveryAddress || order.delivery_address || 'No especificada'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Método de Pago:</span>
                        <span class="detail-value">${order.paymentMethod || order.payment_method || 'No especificado'}</span>
                    </div>
                </div>

                <div class="order-detail-section">
                    <div class="section-title">
                        <i class="bi bi-bag"></i>
                        Productos Pedidos
                    </div>
                    <div id="order-items">
                        ${(order.items || []).map(item => `
                            <div class="item-row">
                                <div>
                                    <div class="item-name">${item.productName || item.product_name || 'Producto'}</div>
                                    <div class="item-quantity">Cantidad: ${item.quantity}</div>
                                </div>
                                <div class="text-end">
                                    <div class="item-price">${formatCurrency(item.unitPrice || item.unit_price || 0)}</div>
                                    <div class="text-muted small">Total: ${formatCurrency((item.unitPrice || item.unit_price || 0) * item.quantity)}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <div class="total-row">
                    <div class="detail-row">
                        <span class="detail-label">Total del Pedido:</span>
                        <span class="total-amount">${formatCurrency(order.totalAmount || order.total_amount || 0)}</span>
                    </div>
                </div>
            `;
        }

        async function showOrderDetails(orderId) {
            const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            const content = document.getElementById('orderDetailsContent');

            // Show loading state
            content.innerHTML = `
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando detalles del pedido...</p>
                </div>
            `;

            modal.show();

            try {
                const orderDetails = await fetchOrderDetails(orderId);
                renderOrderDetails(orderDetails);
            } catch (err) {
                console.error('Modal error details:', err);
                content.innerHTML = `
                    <div class="text-center p-4">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Error al cargar detalles</h5>
                        <p class="text-muted mb-3">${err.message}</p>
                        <details class="text-start">
                            <summary class="btn btn-outline-secondary btn-sm mb-3">Ver información técnica</summary>
                            <div class="alert alert-light">
                                <strong>Pedido ID:</strong> ${orderId}<br>
                                <strong>URL esperada:</strong> /api/client/orders/${orderId}<br>
                                <strong>Error:</strong> ${err.message}<br>
                                <strong>Formato esperado:</strong> { order: { id, status, totalAmount, ... } }<br>
                                <strong>Revisar:</strong> Consola del navegador (F12) → busca "ORDER DETAILS DEBUG"<br>
                                <strong>Nota:</strong> Verifica que el backend esté ejecutándose y que el endpoint exista
                            </div>
                        </details>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                `;
            }
        }

        function renderOrders(orders) {
            const container = document.getElementById('orders');

            if (!orders || orders.length === 0) {
                const isEmpty = !allOrders || allOrders.length === 0;
                const message = isEmpty
                    ? "No tienes pedidos"
                    : "No se encontraron pedidos con los filtros aplicados";
                const subMessage = isEmpty
                    ? "Los pedidos que realices aparecerán aquí."
                    : "Intenta ajustar los filtros de búsqueda.";

                container.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-${isEmpty ? 'bag-x' : 'search'}"></i>
                        <h4>${message}</h4>
                        <p>${subMessage}</p>
                        ${isEmpty ? `
                            <a href="/Sabores360/views/cliente/dashboard.php" class="btn btn-primary mt-3">
                                <i class="bi bi-grid-3x2-gap me-2"></i>
                                Ver Menú
                            </a>
                        ` : ''}
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            orders.forEach(order => {
                const orderCard = document.createElement('div');
                orderCard.className = 'order-card';
                orderCard.setAttribute('data-order-id', order.id);

                const total = order.total_amount || order.totalAmount || order.total || 0;
                const date = order.created_at || order.createdAt || order.date || '';
                const status = order.status || order.state || 'pending';

                orderCard.innerHTML = `
                    <div class="order-header">
                        <div>
                            <div class="order-id">
                                <i class="bi bi-receipt"></i>
                                Pedido #${order.id}
                            </div>
                            <div class="order-date">${formatDate(date)}</div>
                        </div>
                        <div class="order-status ${getStatusClass(status)}">
                            <i class="bi bi-${getStatusIcon(status)}"></i>
                            ${getStatusText(status)}
                        </div>
                    </div>

                    <div class="order-info">
                        <div class="order-details">
                            <div class="order-total">${formatCurrency(total)}</div>
                        </div>
                        
                        <div class="order-actions">
                            <button type="button" class="btn-view-details" data-id="${order.id}">
                                <i class="bi bi-eye"></i>
                                Ver Detalles
                            </button>
                            <button type="button" class="btn-reorder" data-id="${order.id}">
                                <i class="bi bi-arrow-repeat"></i>
                                Reordenar
                            </button>
                        </div>
                    </div>
                `;

                container.appendChild(orderCard);
            });
        }

        // Filtering functions
        function applyFilters() {
            try {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
                const statusFilter = document.getElementById('statusFilter').value;
                const dateFilter = document.getElementById('dateFilter').value;

                filteredOrders = allOrders.filter(order => {
                    // Search filter (by order ID)
                    const matchesSearch = !searchTerm ||
                        order.id.toString().toLowerCase().includes(searchTerm);

                    // Status filter
                    const orderStatus = (order.status || order.state || 'pending').toLowerCase();
                    const matchesStatus = !statusFilter || orderStatus === statusFilter.toLowerCase();

                    // Date filter
                    let matchesDate = true;
                    if (dateFilter) {
                        const orderDate = new Date(order.created_at || order.createdAt || order.date);
                        const now = new Date();

                        switch (dateFilter) {
                            case 'today':
                                matchesDate = orderDate.toDateString() === now.toDateString();
                                break;
                            case 'week':
                                const weekAgo = new Date();
                                weekAgo.setDate(now.getDate() - 7);
                                matchesDate = orderDate >= weekAgo;
                                break;
                            case 'month':
                                const monthAgo = new Date();
                                monthAgo.setMonth(now.getMonth() - 1);
                                matchesDate = orderDate >= monthAgo;
                                break;
                            case '3months':
                                const threeMonthsAgo = new Date();
                                threeMonthsAgo.setMonth(now.getMonth() - 3);
                                matchesDate = orderDate >= threeMonthsAgo;
                                break;
                        }
                    }

                    return matchesSearch && matchesStatus && matchesDate;
                });

                renderOrders(filteredOrders);
                updateFilterSummary();
            } catch (error) {
                console.error('Error applying filters:', error);
            }
        }

        function updateFilterSummary() {
            const orderCount = document.getElementById('orderCount');
            const activeFilters = document.getElementById('activeFilters');
            const clearFiltersBtn = document.getElementById('clearFilters');

            // Update count
            orderCount.textContent = filteredOrders.length;

            // Update active filters display
            const filters = [];
            const searchTerm = document.getElementById('searchInput').value.trim();
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            if (searchTerm) {
                filters.push({
                    type: 'search',
                    label: `ID: "${searchTerm}"`
                });
            }

            if (statusFilter) {
                const statusText = getStatusText(statusFilter);
                filters.push({
                    type: 'status',
                    label: `Estado: ${statusText}`
                });
            }

            if (dateFilter) {
                const dateLabels = {
                    'today': 'Hoy',
                    'week': 'Última semana',
                    'month': 'Último mes',
                    '3months': 'Últimos 3 meses'
                };
                filters.push({
                    type: 'date',
                    label: `Fecha: ${dateLabels[dateFilter]}`
                });
            }

            if (filters.length > 0) {
                activeFilters.innerHTML = filters.map(filter =>
                    `<span class="badge">
                        ${filter.label}
                        <span class="remove" data-filter="${filter.type}">×</span>
                    </span>`
                ).join('');
                clearFiltersBtn.style.display = 'inline-block';
            } else {
                activeFilters.innerHTML = '';
                clearFiltersBtn.style.display = 'none';
            }
        }

        function clearAllFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('dateFilter').value = '';
            applyFilters();
        }

        function removeFilter(filterType) {
            switch (filterType) {
                case 'search':
                    document.getElementById('searchInput').value = '';
                    break;
                case 'status':
                    document.getElementById('statusFilter').value = '';
                    break;
                case 'date':
                    document.getElementById('dateFilter').value = '';
                    break;
            }
            applyFilters();
        }

        async function fetchOrders() {
            const container = document.getElementById('orders');
            try {
                const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                const d = await (window.SABORES360 && SABORES360.API ?
                    SABORES360.API.get('client/orders') :
                    (async () => {
                        const res = await fetch(base + 'client/orders', { credentials: 'include' });
                        const t = await res.text();
                        try { return JSON.parse(t); } catch (e) { return { success: res.ok, httpStatus: res.status, raw: t }; }
                    })());

                console.debug('Orders response', d);

                // Normalize possible response shapes
                let orders = [];
                if (d && d.success) {
                    if (Array.isArray(d.orders)) orders = d.orders;
                    else if (Array.isArray(d.data)) orders = d.data;
                    else if (d.data && Array.isArray(d.data.orders)) orders = d.data.orders;
                    else if (d.data && Array.isArray(d.data.items)) orders = d.data.items;
                    else if (Array.isArray(d.items)) orders = d.items;
                }

                if (orders && Array.isArray(orders)) {
                    allOrders = orders;
                    filteredOrders = [...allOrders];
                    applyFilters();
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
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Search and filter event listeners
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');

            // Real-time search
            searchInput.addEventListener('input', applyFilters);

            // Filter changes
            statusFilter.addEventListener('change', applyFilters);
            dateFilter.addEventListener('change', applyFilters);

            // Clear filters button
            clearFiltersBtn.addEventListener('click', clearAllFilters);

            // Remove individual filters
            document.addEventListener('click', (e) => {
                if (e.target.matches('.filter-summary .badge .remove')) {
                    const filterType = e.target.getAttribute('data-filter');
                    removeFilter(filterType);
                }
            });

            // Order action event delegation
            document.addEventListener('click', async (ev) => {
                // View details button
                if (ev.target.matches('.btn-view-details') || ev.target.closest('.btn-view-details')) {
                    const btn = ev.target.matches('.btn-view-details') ? ev.target : ev.target.closest('.btn-view-details');
                    const orderId = btn.getAttribute('data-id');

                    if (orderId) {
                        await showOrderDetails(orderId);
                    }
                }

                // Reorder button
                if (ev.target.matches('.btn-reorder') || ev.target.closest('.btn-reorder')) {
                    const btn = ev.target.matches('.btn-reorder') ? ev.target : ev.target.closest('.btn-reorder');
                    const orderId = btn.getAttribute('data-id');

                    if (orderId) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Reordenando...';

                        try {
                            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                            const d2 = await (window.SABORES360 && SABORES360.API ?
                                SABORES360.API.post(`client/orders/${orderId}/reorder`) :
                                (async () => {
                                    const res = await fetch(base + `client/orders/${orderId}/reorder`, {
                                        method: 'POST',
                                        credentials: 'include'
                                    });
                                    const t = await res.text();
                                    try { return JSON.parse(t); } catch (e) { return { success: res.ok, httpStatus: res.status, raw: t }; }
                                })());

                            if (d2 && d2.success) {
                                // Show success message
                                const toast = document.createElement('div');
                                toast.className = 'toast-container position-fixed top-0 end-0 p-3';
                                toast.innerHTML = `
                                    <div class="toast show" role="alert">
                                        <div class="toast-header">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <strong class="me-auto">Éxito</strong>
                                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                                        </div>
                                        <div class="toast-body">
                                            Productos añadidos al carrito correctamente
                                        </div>
                                    </div>
                                `;
                                document.body.appendChild(toast);

                                // Trigger cart update event for navbar badge
                                window.dispatchEvent(new Event('cartUpdated'));

                                // Remove toast after 3 seconds
                                setTimeout(() => toast.remove(), 3000);

                                // Refresh orders list
                                await fetchOrders();
                            } else {
                                alert(d2 && d2.message ? d2.message : 'No se pudo reordenar');
                            }
                        } catch (e) {
                            console.error('Reorder error:', e);
                            alert('Error al reordenar');
                        } finally {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Reordenar';
                        }
                    }
                }
            });

            // Load orders on page load
            fetchOrders();
        });

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