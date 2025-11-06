<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Productos</title>

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

        .products-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.1);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .product-card {
            background: white;
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            box-shadow: 0 6px 25px rgba(255, 107, 53, 0.15);
            transform: translateY(-2px);
        }

        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
            background: #f8f9fa;
        }

        .product-name {
            font-weight: 700;
            color: var(--orange-primary);
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-info {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .stock-control {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .stock-control:focus-within {
            border-color: var(--orange-primary);
            background: white;
        }

        .stock-label {
            font-weight: 600;
            color: var(--orange-primary);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stock-input-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .stock-btn {
            background: var(--orange-primary);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stock-btn:hover {
            background: var(--orange-dark);
            transform: scale(1.05);
        }

        .stock-btn:active {
            transform: scale(0.95);
        }

        .stock-input {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            width: 80px;
            transition: all 0.3s ease;
        }

        .stock-input:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            outline: none;
        }

        .btn-save-stock {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-save-stock:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .btn-save-stock:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
        }

        .stock-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .stock-high {
            background: #d4edda;
            color: #155724;
        }

        .stock-medium {
            background: #fff3cd;
            color: #856404;
        }

        .stock-low {
            background: #f8d7da;
            color: #721c24;
        }

        .stock-out {
            background: #f5c6cb;
            color: #721c24;
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 12px;
            height: 320px;
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

        .status-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            z-index: 1050;
            transform: translateX(400px);
            transition: all 0.3s ease;
        }

        .status-message.show {
            transform: translateX(0);
        }

        .status-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .search-section {
            margin-bottom: 1.5rem;
        }

        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            outline: none;
        }

        .availability-control {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .availability-control:focus-within {
            border-color: var(--orange-primary);
            background: white;
        }

        .availability-label {
            font-weight: 600;
            color: var(--orange-primary);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .availability-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .availability-status {
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

        .availability-available {
            background: #d4edda;
            color: #155724;
        }

        .availability-unavailable {
            background: #f8d7da;
            color: #721c24;
        }

        .availability-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .availability-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .availability-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .availability-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.availability-slider {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        }

        input:focus+.availability-slider {
            box-shadow: 0 0 1px var(--orange-primary);
        }

        input:checked+.availability-slider:before {
            transform: translateX(26px);
        }
    </style>
</head>

<body>
    <?php
    $active = 'products';
    require __DIR__ . '/_vendedor_nav.php';
    ?>

    <div class="main-container">
        <div class="page-header">
            <h1>
                <i class="bi bi-grid-3x3-gap"></i>
                Control de Stock
            </h1>
            <p class="text-muted mb-0">Gestiona el inventario de tus productos</p>
        </div>

        <div class="products-container">
            <div class="search-section">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control search-input border-start-0"
                                placeholder="Buscar productos...">
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <span class="text-muted">Total: <strong id="totalProducts">0</strong> productos</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="seller-products" class="products-grid">
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        let allProducts = [];
        let filteredProducts = [];

        // Helper functions
        function normalizeImageUrl(u) {
            if (!u) return null;
            if (u === 'undefined' || u === 'null') return null;
            try {
                if (u.startsWith('http://') || u.startsWith('https://') || u.startsWith('//')) return u;
            } catch (e) { }
            if (u.startsWith('/')) return window.location.origin + u;
            return window.location.origin + '/' + u;
        }

        function coerceToBoolean(v) {
            if (v === true || v === false) return v;
            if (v === undefined || v === null) return null;
            if (typeof v === 'number') return v !== 0;
            if (typeof v === 'string') {
                const s = v.trim().toLowerCase();
                if (s === '1' || s === 'true' || s === 'yes' || s === 'y' || s === 'si') return true;
                if (s === '0' || s === 'false' || s === 'no' || s === 'n') return false;
            }
            return Boolean(v);
        }

        function getStockStatus(stock) {
            const s = parseInt(stock) || 0;
            if (s === 0) return { class: 'stock-out', text: 'Sin stock', icon: 'exclamation-triangle' };
            if (s <= 5) return { class: 'stock-low', text: 'Stock bajo', icon: 'exclamation-circle' };
            if (s <= 20) return { class: 'stock-medium', text: 'Stock medio', icon: 'info-circle' };
            return { class: 'stock-high', text: 'Stock alto', icon: 'check-circle' };
        }

        function showStatusMessage(message, type = 'success') {
            // Remove existing messages
            const existing = document.querySelector('.status-message');
            if (existing) existing.remove();

            const statusDiv = document.createElement('div');
            statusDiv.className = `status-message ${type}`;
            statusDiv.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'x-circle'}"></i>
                ${message}
            `;
            document.body.appendChild(statusDiv);

            // Show with animation
            setTimeout(() => statusDiv.classList.add('show'), 100);

            // Hide after 3 seconds
            setTimeout(() => {
                statusDiv.classList.remove('show');
                setTimeout(() => statusDiv.remove(), 300);
            }, 3000);
        }

        async function updateStock(productId, newStock) {
            try {
                const res = await (window.SABORES360 && SABORES360.API ?
                    SABORES360.API.post(`seller/products/${productId}/stock`, { stock: newStock }) :
                    (async () => {
                        const r = await fetch((window.SABORES360 && SABORES360.API_BASE) ?
                            SABORES360.API_BASE + `seller/products/${productId}/stock` :
                            `http://localhost:8080/api/seller/products/${productId}/stock`, {
                            method: 'POST',
                            credentials: 'include',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ stock: newStock })
                        });
                        const t = await r.text();
                        try { return JSON.parse(t); } catch (e) { return { success: r.ok, httpStatus: r.status, raw: t }; }
                    })());

                if (res && res.success) {
                    // Update the product in our local array
                    const product = allProducts.find(p => p.id == productId);
                    if (product) {
                        product.stock = newStock;
                        updateProductCard(productId, newStock);
                    }
                    showStatusMessage('Stock actualizado correctamente');
                    return true;
                } else {
                    showStatusMessage(res && res.message ? res.message : 'Error al actualizar stock', 'error');
                    return false;
                }
            } catch (err) {
                showStatusMessage('Error de conexión', 'error');
                return false;
            }
        }

        async function updateAvailability(productId, isAvailable) {
            try {
                const res = await (window.SABORES360 && SABORES360.API ?
                    SABORES360.API.post(`seller/products/${productId}/availability`, { available: !!isAvailable }) :
                    (async () => {
                        const r = await fetch((window.SABORES360 && SABORES360.API_BASE) ?
                            SABORES360.API_BASE + `seller/products/${productId}/availability` :
                            `http://localhost:8080/api/seller/products/${productId}/availability`, {
                            method: 'POST',
                            credentials: 'include',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ available: !!isAvailable })
                        });
                        const t = await r.text();
                        try { return JSON.parse(t); } catch (e) { return { success: r.ok, httpStatus: r.status, raw: t }; }
                    })());

                // Be tolerant with API shapes: success flag or http status
                const ok = (res && (res.success === true)) || (res && res.httpStatus && (res.httpStatus >= 200 && res.httpStatus < 300));
                if (ok) {
                    // Update the product in our local array
                    const product = allProducts.find(p => p.id == productId);
                    if (product) {
                        product.available = isAvailable;
                        product.is_available = isAvailable;
                        // also update a canonical status field if present
                        if (product.status !== undefined) product.status = isAvailable ? 'available' : 'unavailable';
                        updateAvailabilityDisplay(productId, !!isAvailable);
                    }
                    showStatusMessage(`Producto ${isAvailable ? 'habilitado' : 'deshabilitado'} correctamente`);
                    return true;
                } else {
                    showStatusMessage(res && res.message ? res.message : 'Error al cambiar disponibilidad', 'error');
                    return false;
                }
            } catch (err) {
                showStatusMessage('Error de conexión', 'error');
                return false;
            }
        } function updateProductCard(productId, newStock) {
            const card = document.querySelector(`[data-product-id="${productId}"]`);
            if (!card) return;

            const stockInput = card.querySelector('.stock-input');
            const stockStatus = card.querySelector('.stock-status');

            if (stockInput) stockInput.value = newStock;
            if (stockStatus) {
                const status = getStockStatus(newStock);
                stockStatus.className = `stock-status ${status.class}`;
                stockStatus.innerHTML = `<i class="bi bi-${status.icon}"></i> ${status.text}`;
            }
        }

        function updateAvailabilityDisplay(productId, isAvailable) {
            const card = document.querySelector(`[data-product-id="${productId}"]`);
            if (!card) return;

            const availabilityStatus = card.querySelector('.availability-status');
            const availabilityCheckbox = card.querySelector('.availability-checkbox');

            if (availabilityStatus) {
                availabilityStatus.className = `availability-status ${isAvailable ? 'availability-available' : 'availability-unavailable'}`;
                availabilityStatus.innerHTML = `
                    <i class="bi bi-${isAvailable ? 'check-circle' : 'x-circle'}"></i>
                    ${isAvailable ? 'Disponible' : 'No Disponible'}
                `;
            }

            if (availabilityCheckbox) {
                availabilityCheckbox.checked = !!isAvailable;
                availabilityCheckbox.setAttribute('data-available', !!isAvailable ? '1' : '0');
            }
        }

        // Fetch authoritative availability from the dedicated endpoint
        async function fetchAvailabilityStatus(productId) {
            try {
                const res = await (window.SABORES360 && SABORES360.API ?
                    SABORES360.API.get(`seller/products/${productId}/status`) :
                    (async () => {
                        const r = await fetch((window.SABORES360 && SABORES360.API_BASE) ?
                            SABORES360.API_BASE + `seller/products/${productId}/status` :
                            `http://localhost:8080/api/seller/products/${productId}/status`, { credentials: 'include' });
                        const t = await r.text();
                        try { return JSON.parse(t); } catch (e) { return { success: r.ok, httpStatus: r.status, raw: t }; }
                    })());

                // Accept several shapes: { isAvailable: true } or { data: { isAvailable: true } }
                let candidate = null;
                if (res && typeof res === 'object') {
                    if (res.isAvailable !== undefined) candidate = res.isAvailable;
                    else if (res.is_available !== undefined) candidate = res.is_available;
                    else if (res.data && res.data.isAvailable !== undefined) candidate = res.data.isAvailable;
                    else if (res.data && res.data.is_available !== undefined) candidate = res.data.is_available;
                }

                const parsed = coerceToBoolean(candidate);

                // If API explicitly returned null, treat as unavailable; otherwise update UI
                const final = parsed === null ? false : !!parsed;

                // Update local model and UI
                const product = allProducts.find(p => p.id == productId);
                if (product) {
                    product.available = final;
                    product.is_available = final;
                    if (product.status !== undefined) product.status = final ? 'available' : 'unavailable';
                }
                updateAvailabilityDisplay(productId, final);
                return final;
            } catch (err) {
                console.error('Error fetching product status', err);
                return null;
            }
        }

        function renderProducts(products) {
            const container = document.getElementById('seller-products');

            if (!products || products.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-grid-3x3-gap"></i>
                            <h4>No hay productos disponibles</h4>
                            <p>Los productos aparecerán aquí cuando estén disponibles para gestionar.</p>
                        </div>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            products.forEach(p => {
                const stockVal = (p.stock !== undefined && p.stock !== null) ? parseInt(p.stock) : 0;
                const rawImg = p.url_imagen || p.urlImagen || p.imageUrl || p.image_url || p.image || null;
                const imgSrc = normalizeImageUrl(rawImg);
                const placeholder = '/Sabores360/assets/img/no-image.svg';
                const status = getStockStatus(stockVal);

                // Get availability status - try multiple possible field names and coerce to boolean
                let rawAvail = null;
                if (p.available !== undefined) rawAvail = p.available;
                else if (p.is_available !== undefined) rawAvail = p.is_available;
                else if (p.available_int !== undefined) rawAvail = p.available_int;
                else if (p.status !== undefined) rawAvail = (p.status === 'active' || p.status === 'available' || p.status === 1 || p.status === '1');

                let isAvailable = coerceToBoolean(rawAvail);
                // Don't assume available by default (previous behaviour caused all items to show as available)
                if (isAvailable === null) isAvailable = false;

                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.setAttribute('data-product-id', p.id);

                productCard.innerHTML = `
                    <img src="${imgSrc || placeholder}" 
                         onerror="this.onerror=null;this.src='${placeholder}';" 
                         class="product-image" alt="${p.name || 'Producto'}">
                    
                    <div class="product-name">${p.name || 'Producto sin nombre'}</div>
                    
                    <div class="product-info">
                        <div class="stock-status ${status.class}">
                            <i class="bi bi-${status.icon}"></i>
                            ${status.text}
                        </div>
                    </div>
                    
                    <div class="availability-control">
                        <div class="availability-label">
                            <i class="bi bi-toggle-on"></i>
                            Estado del Producto
                        </div>
                        
                        <div class="availability-toggle">
                            <div class="availability-status ${isAvailable ? 'availability-available' : 'availability-unavailable'}">
                                <i class="bi bi-${isAvailable ? 'check-circle' : 'x-circle'}"></i>
                                ${isAvailable ? 'Disponible' : 'No Disponible'}
                            </div>
                            
                            <label class="availability-switch">
                                <input type="checkbox" data-id="${p.id}" class="availability-checkbox" ${isAvailable ? 'checked' : ''} data-available="${isAvailable ? 1 : 0}">
                                <span class="availability-slider"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="stock-control">
                        <div class="stock-label">
                            <i class="bi bi-boxes"></i>
                            Cantidad en Stock
                        </div>
                        
                        <div class="stock-input-group">
                            <button type="button" class="stock-btn" data-action="decrease" data-id="${p.id}">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="stock-input" data-id="${p.id}" value="${stockVal}" min="0">
                            <button type="button" class="stock-btn" data-action="increase" data-id="${p.id}">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        
                        <button type="button" class="btn-save-stock" data-id="${p.id}">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                `;

                container.appendChild(productCard);
            });

            // Update total count
            document.getElementById('totalProducts').textContent = products.length;

            // After rendering, fetch authoritative availability for each product and update UI
            products.forEach(p => {
                if (!p || !p.id) return;
                // Fire and forget - updates the UI when response arrives
                fetchAvailabilityStatus(p.id).catch(e => console.warn('Status fetch failed for', p.id, e));
            });
        }

        function filterProducts(searchTerm) {
            if (!searchTerm.trim()) {
                filteredProducts = [...allProducts];
            } else {
                const term = searchTerm.toLowerCase();
                filteredProducts = allProducts.filter(p =>
                    (p.name || '').toLowerCase().includes(term) ||
                    (p.description || '').toLowerCase().includes(term)
                );
            }
            renderProducts(filteredProducts);
        }

        // Load products
        (async function () {
            const container = document.getElementById('seller-products');
            try {
                const d = await (window.SABORES360 && SABORES360.API ?
                    SABORES360.API.get('seller/products') :
                    (async () => {
                        const res = await fetch((window.SABORES360 && SABORES360.API_BASE) ?
                            SABORES360.API_BASE + 'seller/products' :
                            'http://localhost:8080/api/seller/products', { credentials: 'include' });
                        const t = await res.text();
                        try { return JSON.parse(t); } catch (e) { return { success: res.ok, httpStatus: res.status, raw: t }; }
                    })());

                console.debug('seller/products response', d);

                const products = (d && d.products) ||
                    (d && d.data && d.data.products) ||
                    (d && d.productsList) ||
                    (d && d.data && d.data.items) ||
                    (Array.isArray(d) ? d : null) ||
                    (d && d.data && Array.isArray(d.data) ? d.data : null);

                if (products && Array.isArray(products)) {
                    allProducts = products;
                    filteredProducts = [...products];
                    renderProducts(filteredProducts);
                } else {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="bi bi-exclamation-triangle"></i>
                                <h4>No se pudieron cargar los productos</h4>
                                <p>Inténtalo de nuevo más tarde.</p>
                                <details class="mt-3">
                                    <summary class="text-muted">Información técnica</summary>
                                    <pre class="small mt-2">${JSON.stringify(d, null, 2)}</pre>
                                </details>
                            </div>
                        </div>
                    `;
                }
            } catch (err) {
                console.error('Error loading products:', err);
                container.innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-x-circle"></i>
                            <h4>Error al cargar productos</h4>
                            <p>Ha ocurrido un error al conectar con el servidor.</p>
                        </div>
                    </div>
                `;
            }
        })();

        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    filterProducts(e.target.value);
                });
            }

            // Stock control event delegation
            document.addEventListener('click', async (ev) => {
                // Increment/Decrement buttons
                if (ev.target.matches('.stock-btn') || ev.target.closest('.stock-btn')) {
                    const btn = ev.target.matches('.stock-btn') ? ev.target : ev.target.closest('.stock-btn');
                    const action = btn.getAttribute('data-action');
                    const productId = btn.getAttribute('data-id');
                    const input = document.querySelector(`.stock-input[data-id="${productId}"]`);

                    if (input) {
                        let currentValue = parseInt(input.value) || 0;
                        if (action === 'increase') {
                            currentValue++;
                        } else if (action === 'decrease' && currentValue > 0) {
                            currentValue--;
                        }
                        input.value = currentValue;
                        updateProductCard(productId, currentValue);
                    }
                }

                // Save stock button
                if (ev.target.matches('.btn-save-stock') || ev.target.closest('.btn-save-stock')) {
                    const btn = ev.target.matches('.btn-save-stock') ? ev.target : ev.target.closest('.btn-save-stock');
                    const productId = btn.getAttribute('data-id');
                    const input = document.querySelector(`.stock-input[data-id="${productId}"]`);

                    if (input) {
                        const newStock = parseInt(input.value) || 0;

                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Guardando...';

                        const success = await updateStock(productId, newStock);

                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-save"></i> Guardar Cambios';
                    }
                }
            });

            // Availability toggle event delegation
            document.addEventListener('change', async (ev) => {
                if (ev.target.matches('.availability-checkbox')) {
                    const checkbox = ev.target;
                    const productId = checkbox.getAttribute('data-id');
                    const isAvailable = checkbox.checked;

                    // Disable checkbox during update
                    checkbox.disabled = true;

                    const success = await updateAvailability(productId, isAvailable);

                    if (!success) {
                        // Revert checkbox state if update failed
                        checkbox.checked = !isAvailable;
                        updateAvailabilityDisplay(productId, !isAvailable);
                    }

                    checkbox.disabled = false;
                }
            });

            // Enter key support for stock input
            document.addEventListener('keydown', (ev) => {
                if (ev.target.matches('.stock-input') && ev.key === 'Enter') {
                    const productId = ev.target.getAttribute('data-id');
                    const saveBtn = document.querySelector(`.btn-save-stock[data-id="${productId}"]`);
                    if (saveBtn) saveBtn.click();
                }
            });

            // Update stock status when input changes
            document.addEventListener('input', (ev) => {
                if (ev.target.matches('.stock-input')) {
                    const productId = ev.target.getAttribute('data-id');
                    const newStock = parseInt(ev.target.value) || 0;
                    updateProductCard(productId, newStock);
                }
            });
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