<?php
require __DIR__ . '/../../includes/auth_check.php';
// any authenticated user may access client dashboard
require_auth();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cliente - Menú</title>

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
            margin-bottom: 2rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .product-card {
            background: white;
            border: 1px solid #f0f0f0;
            border-radius: 15px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            box-shadow: 0 8px 30px rgba(255, 107, 53, 0.15);
            transform: translateY(-3px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 1rem;
            background: #f8f9fa;
        }

        .product-name {
            font-weight: 700;
            color: var(--orange-primary);
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--orange-dark);
            margin-bottom: 0.75rem;
        }

        .product-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }

        .status-available {
            background: #d4edda;
            color: #155724;
        }

        .status-unavailable {
            background: #f8d7da;
            color: #721c24;
        }

        .quantity-control {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .quantity-control:focus-within {
            border-color: var(--orange-primary);
            background: white;
        }

        .quantity-label {
            font-weight: 600;
            color: var(--orange-primary);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-input-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .quantity-btn {
            background: var(--orange-primary);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .quantity-btn:hover {
            background: var(--orange-dark);
            transform: scale(1.05);
        }

        .quantity-btn:active {
            transform: scale(0.95);
        }

        .quantity-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .quantity-input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem;
            font-size: 1.1rem;
            font-weight: 700;
            text-align: center;
            width: 80px;
            transition: all 0.3s ease;
            background: white;
        }

        .quantity-input:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            outline: none;
        }

        .btn-add-cart {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .btn-add-cart:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 15px;
            height: 400px;
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

        .cart-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.1);
            text-align: center;
        }

        .cart-link {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            text-decoration: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .cart-link:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }

        .custom-toast {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
            border-left: 4px solid var(--orange-primary);
        }

        .search-section {
            margin-bottom: 2rem;
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

        .form-select {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            min-width: 150px;
        }

        .form-select:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .search-section {
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .main-container {
                padding: 1rem 0.5rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .products-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php
    $active = 'dashboard';
    require __DIR__ . '/_cliente_nav.php';
    ?>

    <div class="main-container">
        <div class="page-header">
            <h1>
                <i class="bi bi-grid-3x2-gap"></i>
                Nuestro Menú
            </h1>
            <p class="text-muted mb-0">Descubre nuestros deliciosos productos y añádelos a tu carrito</p>
        </div>

        <div class="products-container">
            <div class="search-section">
                <div class="row g-3 align-items-center">
                    <!-- Buscador - Ocupa más espacio -->
                    <div class="col-lg-5 col-md-12">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control search-input border-start-0"
                                placeholder="Buscar productos...">
                        </div>
                    </div>
                    
                    <!-- Filtros en una fila separada en móvil -->
                    <div class="col-lg-7 col-md-12">
                        <div class="row g-2 align-items-center justify-content-end">
                            <div class="col-auto">
                                <select id="categoryFilter" class="form-select">
                                    <option value="">Todas las categorías</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <select id="sortSelect" class="form-select">
                                    <option value="">Ordenar</option>
                                    <option value="price_desc">Precio: Mayor a menor</option>
                                    <option value="price_asc">Precio: Menor a mayor</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <span class="text-muted small">Total: <strong id="totalProducts">0</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="product-list" class="products-grid">
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
                <div class="loading-skeleton"></div>
            </div>
        </div>

        <div class="cart-section">
            <h3 class="mb-3">
                <i class="bi bi-cart3 me-2"></i>
                Tu Carrito
            </h3>
            <p class="text-muted mb-3">Revisa tus productos seleccionados y procede al pago</p>
            <a href="/Sabores360/views/cliente/cart.php" class="cart-link">
                <i class="bi bi-bag-check"></i>
                Ver Carrito y Pagar
            </a>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container">
        <div id="cart-toast" class="toast custom-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-check-circle text-success me-2"></i>
                <strong class="me-auto">Producto Añadido</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                El producto se ha añadido correctamente a tu carrito.
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php require __DIR__ . '/../../includes/print_api_js.php'; ?>
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

        function addToCart(productId, name, price, image, quantity) {
            try {
                const key = 'sabores360_cart';
                let store = [];
                try {
                    store = JSON.parse(localStorage.getItem(key) || '[]');
                } catch (e) {
                    store = [];
                }

                store.push({
                    id: parseInt(productId, 10),
                    name,
                    price: parseFloat(price),
                    quantity: parseInt(quantity, 10),
                    image: normalizeImageUrl(image)
                });

                localStorage.setItem(key, JSON.stringify(store));

                // Trigger cart update event for navbar badge
                window.dispatchEvent(new Event('cartUpdated'));

                // Show toast notification
                showToast('Producto añadido al carrito');

                return true;
            } catch (err) {
                console.error('Error adding to cart:', err);
                return false;
            }
        }

        function showToast(message) {
            const toastElement = document.getElementById('cart-toast');
            const toastBody = toastElement.querySelector('.toast-body');
            if (toastBody) toastBody.textContent = message;

            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        function updateQuantity(productId, change) {
            const input = document.querySelector(`.quantity-input[data-id="${productId}"]`);
            if (!input) return;

            let currentValue = parseInt(input.value) || 1;
            let newValue = currentValue + change;

            if (newValue < 1) newValue = 1;
            if (newValue > 99) newValue = 99;

            input.value = newValue;

            // Update decrease button state
            const decreaseBtn = document.querySelector(`.quantity-btn[data-action="decrease"][data-id="${productId}"]`);
            if (decreaseBtn) {
                decreaseBtn.disabled = newValue <= 1;
            }
        }

        function renderProducts(products) {
            const container = document.getElementById('product-list');

            if (!products || products.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-grid-3x2-gap"></i>
                            <h4>No hay productos disponibles</h4>
                            <p>Los productos aparecerán aquí cuando estén disponibles en el menú.</p>
                        </div>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            products.forEach(p => {
                const available = (typeof p.is_available !== 'undefined') ?
                    p.is_available :
                    (typeof p.stock !== 'undefined' ? (p.stock > 0) : true);

                const rawImg = p.imageUrl || p.image_url || p.image;
                const imgSrc = normalizeImageUrl(rawImg);
                const placeholder = '/Sabores360/assets/img/no-image.svg';

                const price = parseFloat(p.price) || 0;
                const formattedPrice = price.toLocaleString('es-ES', {
                    style: 'currency',
                    currency: 'EUR',
                    minimumFractionDigits: 2
                });

                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.setAttribute('data-product-id', p.id);

                productCard.innerHTML = `
                    <img src="${imgSrc || placeholder}" 
                         onerror="this.onerror=null;this.src='${placeholder}';" 
                         class="product-image" alt="${p.name || 'Producto'}">
                    
                    <div class="product-name">${p.name || 'Producto sin nombre'}</div>
                    <div class="product-price">${formattedPrice}</div>
                    
                    <div class="product-status ${available ? 'status-available' : 'status-unavailable'}">
                        <i class="bi bi-${available ? 'check-circle' : 'x-circle'}"></i>
                        ${available ? 'Disponible' : 'No Disponible'}
                    </div>
                    
                    ${available ? `
                        <div class="quantity-control">
                            <div class="quantity-label">
                                <i class="bi bi-plus-slash-minus"></i>
                                Cantidad
                            </div>
                            
                            <div class="quantity-input-group">
                                <button type="button" class="quantity-btn" data-action="decrease" data-id="${p.id}">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="quantity-input" data-id="${p.id}" value="1" min="1" max="99">
                                <button type="button" class="quantity-btn" data-action="increase" data-id="${p.id}">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" class="btn-add-cart" data-id="${p.id}" data-name="${p.name}" 
                                data-price="${price}" data-image="${imgSrc || ''}">
                            <i class="bi bi-cart-plus"></i>
                            Añadir al Carrito
                        </button>
                    ` : `
                        <button type="button" class="btn-add-cart" disabled>
                            <i class="bi bi-x-circle"></i>
                            No Disponible
                        </button>
                    `}
                `;

                container.appendChild(productCard);
            });

            // Update total count
            document.getElementById('totalProducts').textContent = products.length;
        }

        function filterProducts(searchTerm) {
            // Read additional filters
            const categoryEl = document.getElementById('categoryFilter');
            const sortEl = document.getElementById('sortSelect');
            const selectedCategory = categoryEl ? categoryEl.value : '';
            const sortOrder = sortEl ? sortEl.value : '';
            // helper: extract product category id or name in normalized form
            function getProductCategoryId(p) {
                // try several possible shapes: category may be object or primitive
                if (!p) return '';
                const c = p.category || p.category_id || p.categoryId || p.categoryName || p.categoria || p.category_name;
                if (!c && p.category && typeof p.category === 'object') return String(p.category.id || p.categoryId || '');
                if (typeof c === 'object') return String(c.id || c.categoryId || c.category_id || '');
                return (c != null) ? String(c) : '';
            }

            let list = [];
            if (!searchTerm || !searchTerm.trim()) {
                list = [...allProducts];
            } else {
                const term = searchTerm.toLowerCase();
                list = allProducts.filter(p =>
                    (p.name || '').toLowerCase().includes(term) ||
                    (p.description || '').toLowerCase().includes(term)
                );
            }

            // Apply category filter (client-side)
            if (selectedCategory) {
                list = list.filter(p => {
                    const prodCat = getProductCategoryId(p);
                    // compare by id (preferred) or by name if backend uses names as category value
                    return prodCat === selectedCategory || String((p.category || p.category_name || p.categoryName || p.categoria || '')).trim() === selectedCategory;
                });
            }

            // Apply sorting
            if (sortOrder === 'price_desc') {
                list.sort((a, b) => (parseFloat(b.price) || 0) - (parseFloat(a.price) || 0));
            } else if (sortOrder === 'price_asc') {
                list.sort((a, b) => (parseFloat(a.price) || 0) - (parseFloat(b.price) || 0));
            }

            filteredProducts = list;
            renderProducts(filteredProducts);
        }

        // Load products
        async function loadProducts() {
            const container = document.getElementById('product-list');
            try {
                const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                const d = await (window.SABORES360 && SABORES360.API ?
                    SABORES360.API.get('client/products/full') :
                    (async () => {
                        const res = await fetch(base + 'client/products/full', { credentials: 'include' });
                        const t = await res.text();
                        try { return JSON.parse(t); } catch (e) { return { success: res.ok, httpStatus: res.status, raw: t }; }
                    })());

                console.debug('client/products/full response', d);

                // Support multiple response shapes
                let products = [];
                if (d && d.success) {
                    if (Array.isArray(d.products)) products = d.products;
                    else if (Array.isArray(d.data)) products = d.data;
                    else if (d.data && Array.isArray(d.data.products)) products = d.data.products;
                    else if (d.data && Array.isArray(d.data.items)) products = d.data.items;
                    else if (Array.isArray(d.items)) products = d.items;
                }

                if (products && Array.isArray(products)) {
                    allProducts = products;
                    filteredProducts = [...products];
                    renderProducts(filteredProducts);
                    // Try to populate categories from public endpoint (preferred)
                    try {
                        await loadCategories();
                    } catch (e) {
                        // fallback: derive categories from products if endpoint fails
                        console.debug('Could not load categories from public endpoint, falling back to product-derived categories', e);
                        try {
                            const catEl = document.getElementById('categoryFilter');
                            if (catEl) {
                                const set = new Set();
                                products.forEach(p => {
                                    const name = (p.category || p.category_name || p.categoryName || p.categoria || '').toString();
                                    if (name && name.trim()) set.add(name);
                                });
                                // remove any non-empty existing options except the first
                                // (we will append derived categories)
                                const cats = Array.from(set).sort((a, b) => a.localeCompare(b, 'es'));
                                cats.forEach(c => {
                                    const opt = document.createElement('option');
                                    opt.value = c;
                                    opt.textContent = c;
                                    catEl.appendChild(opt);
                                });
                            }
                        } catch (e2) { console.debug('Error populating fallback categories', e2); }
                    }
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
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    filterProducts(e.target.value);
                });
            }

                // Category filter
                const categorySelect = document.getElementById('categoryFilter');
                if (categorySelect) {
                    categorySelect.addEventListener('change', (e) => {
                        const term = (document.getElementById('searchInput') || {}).value || '';
                        filterProducts(term);
                    });
                }

                // Sort select
                const sortSelect = document.getElementById('sortSelect');
                if (sortSelect) {
                    sortSelect.addEventListener('change', (e) => {
                        const term = (document.getElementById('searchInput') || {}).value || '';
                        filterProducts(term);
                    });
                }

            // Product interaction event delegation
            document.addEventListener('click', async (ev) => {
                // Quantity control buttons
                if (ev.target.matches('.quantity-btn') || ev.target.closest('.quantity-btn')) {
                    const btn = ev.target.matches('.quantity-btn') ? ev.target : ev.target.closest('.quantity-btn');
                    const action = btn.getAttribute('data-action');
                    const productId = btn.getAttribute('data-id');

                    if (action === 'increase') {
                        updateQuantity(productId, 1);
                    } else if (action === 'decrease') {
                        updateQuantity(productId, -1);
                    }
                }

                // Add to cart button
                if (ev.target.matches('.btn-add-cart') || ev.target.closest('.btn-add-cart')) {
                    const btn = ev.target.matches('.btn-add-cart') ? ev.target : ev.target.closest('.btn-add-cart');
                    const productId = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name');
                    const price = btn.getAttribute('data-price');
                    const image = btn.getAttribute('data-image');

                    const quantityInput = document.querySelector(`.quantity-input[data-id="${productId}"]`);
                    const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

                    if (productId && name && price) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Añadiendo...';

                        const success = addToCart(productId, name, price, image, quantity);

                        setTimeout(() => {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-cart-plus"></i> Añadir al Carrito';
                        }, 1000);
                    }
                }
            });

            // Quantity input validation
            document.addEventListener('input', (ev) => {
                if (ev.target.matches('.quantity-input')) {
                    let value = parseInt(ev.target.value) || 1;
                    if (value < 1) value = 1;
                    if (value > 99) value = 99;
                    ev.target.value = value;

                    const productId = ev.target.getAttribute('data-id');
                    const decreaseBtn = document.querySelector(`.quantity-btn[data-action="decrease"][data-id="${productId}"]`);
                    if (decreaseBtn) {
                        decreaseBtn.disabled = value <= 1;
                    }
                }
            });

            // Load categories (public) first, then products
            loadCategories().catch(() => { /* non-blocking */ }).finally(() => {
                loadProducts();
            });
        });

        // Load categories from public endpoint (no token required)
        async function loadCategories() {
            try {
                let json;
                if (window.SABORES360 && SABORES360.API) {
                    json = await SABORES360.API.get('public/categories');
                } else {
                    const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                    const res = await fetch(base + 'public/categories');
                    const t = await res.text();
                    try { json = JSON.parse(t); } catch (e) { json = { success: res.ok, raw: t }; }
                }

                console.debug('public/categories response', json);

                if (json && json.success && Array.isArray(json.categories)) {
                    const catEl = document.getElementById('categoryFilter');
                    if (!catEl) return;
                    // Remove existing options except the first default
                    while (catEl.options.length > 1) catEl.remove(1);

                    // Append categories by id (value) and name (label)
                    json.categories
                        .slice()
                        .sort((a, b) => (String(a.name || '').localeCompare(String(b.name || ''), 'es')))
                        .forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = String(c.id ?? c._id ?? '');
                            opt.textContent = c.name || c.nombre || String(c.id ?? '');
                            catEl.appendChild(opt);
                        });

                    // If there are no categories, add a disabled hint
                    if (catEl.options.length === 1) {
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = 'No hay categorías';
                        opt.disabled = true;
                        catEl.appendChild(opt);
                    }
                } else {
                    console.warn('Categorías: respuesta inválida', json);
                }
            } catch (err) {
                console.debug('Error loading public categories:', err);
                // do not throw to avoid blocking products load
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