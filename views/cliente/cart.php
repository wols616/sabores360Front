<?php
require __DIR__ . '/../../includes/auth_check.php';
require_auth();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cliente - Carrito</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .cart-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.1);
            margin-bottom: 2rem;
        }

        .cart-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .cart-item:hover {
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.15);
            transform: translateY(-2px);
        }

        .cart-item:last-child {
            margin-bottom: 0;
        }

        .item-content {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 1.5rem;
            align-items: center;
        }

        .item-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            background: #f8f9fa;
        }

        .item-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .item-name {
            font-weight: 700;
            color: var(--orange-primary);
            font-size: 1.1rem;
        }

        .item-price {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--orange-dark);
        }

        .item-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .quantity-control {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 0.75rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .quantity-control:focus-within {
            border-color: var(--orange-primary);
            background: white;
        }

        .quantity-input-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-btn {
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
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            width: 60px;
            transition: all 0.3s ease;
        }

        .quantity-input:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            outline: none;
        }

        .subtotal {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--orange-dark);
            margin-bottom: 0.5rem;
        }

        .btn-remove {
            background: #dc3545;
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-remove:hover {
            background: #c82333;
            transform: translateY(-1px);
            color: white;
        }

        .cart-total {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-top: 1rem;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0;
        }

        .cart-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .btn-clear {
            background: white;
            border: 2px solid #dc3545;
            color: #dc3545;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-clear:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
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

        .btn-menu {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-menu:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--orange-dark), var(--orange-primary));
        }

        .btn-menu:active {
            transform: translateY(-1px);
        }

        .btn-menu i {
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .cart-container {
                padding: 1.5rem;
            }

            .item-content {
                grid-template-columns: 80px 1fr;
                gap: 1rem;
            }

            .item-controls {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: space-between;
                margin-top: 1rem;
            }

            .cart-actions {
                flex-direction: column;
            }

            .btn-checkout,
            .btn-clear {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <?php
    $active = 'cart';
    require __DIR__ . '/_cliente_nav.php';
    ?>

    <div class="main-container">
        <div class="page-header">
            <h1>
                <i class="bi bi-cart3"></i>
                Mi Carrito
            </h1>
            <p class="text-muted mb-0">Revisa y modifica los productos de tu pedido</p>
        </div>

        <div class="cart-container">
            <div id="cart">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando carrito...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php require __DIR__ . '/../../includes/print_api_js.php'; ?>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const key = 'sabores360_cart';
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('cart');

            function loadCart() {
                try {
                    const raw = localStorage.getItem(key) || '[]';
                    return JSON.parse(raw);
                } catch (e) { return []; }
            }

            async function ensureImages(cart) {
                if (!cart || !cart.length) return;
                // if all items already have images (and not the placeholder), nothing to do
                const placeholder = '/Sabores360/assets/img/no-image.svg';
                const need = cart.some(it => !it.image || !String(it.image).trim() || String(it.image).endsWith('no-image.svg'));
                if (!need) return;
                try {
                    // Prefer targeted cart details endpoint which returns only requested products with imageUrl
                    const ids = Array.from(new Set(cart.map(it => parseInt(it.id, 10)).filter(Boolean)));
                    let list = [];
                    let usedEndpoint = null;
                    if (ids.length) {
                        const ep = 'client/cart/details';
                        try {
                            let d;
                            if (window.SABORES360 && SABORES360.API) {
                                d = await SABORES360.API.post(ep, { ids });
                            } else {
                                const r = await fetch(base + ep, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ ids }) });
                                const t = await r.text(); try { d = JSON.parse(t); } catch (e) { d = { success: r.ok, raw: t }; }
                            }
                            if (d && d.success) {
                                if (Array.isArray(d.products)) list = d.products;
                                else if (Array.isArray(d.data)) list = d.data;
                                else if (d.data && Array.isArray(d.data.products)) list = d.data.products;
                                else if (d.data && Array.isArray(d.data.items)) list = d.data.items;
                                else if (Array.isArray(d.items)) list = d.items;
                                if (list && list.length) usedEndpoint = ep;
                            }
                        } catch (e) { console.warn('client/cart/details failed', e); }
                        // fallback: request full products if cart-details endpoint isn't available
                        if (!list.length) {
                            const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('client/products/full') : (async () => { const r = await fetch(base + 'client/products/full', { credentials: 'include' }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                            if (d && d.success) {
                                if (Array.isArray(d.products)) list = d.products;
                                else if (Array.isArray(d.data)) list = d.data;
                                else if (d.data && Array.isArray(d.data.products)) list = d.data.products;
                                else if (d.data && Array.isArray(d.data.items)) list = d.data.items;
                                else if (Array.isArray(d.items)) list = d.items;
                                if (list && list.length) usedEndpoint = 'client/products/full';
                            }
                        }
                    }
                    if (!list.length) return;
                    const byId = {};
                    list.forEach(p => { byId[String(p.id)] = p; });
                    let changed = false;
                    // helper to extract image from product object using common keys and nested shapes
                    function extractFromProduct(p) {
                        if (!p) return null;
                        const candidates = ['imageUrl', 'image_url', 'image', 'picture', 'photo', 'thumbnail', 'thumb', 'img', 'url', 'src', 'imagePath', 'image_path'];
                        for (const k of candidates) {
                            if (p[k]) {
                                const v = p[k];
                                if (typeof v === 'string' && v.trim()) return v.trim();
                                if (typeof v === 'object') {
                                    if (v.url) return v.url;
                                    if (v.path) return v.path;
                                    if (v.src) return v.src;
                                }
                            }
                        }
                        if (Array.isArray(p.images) && p.images.length) {
                            const it = p.images[0];
                            if (typeof it === 'string') return it;
                            if (it && typeof it === 'object') return it.url || it.path || it.src || null;
                        }
                        if (Array.isArray(p.media) && p.media.length) {
                            const it = p.media[0];
                            if (typeof it === 'string') return it;
                            if (it && typeof it === 'object') return it.url || it.path || it.src || null;
                        }
                        return null;
                    }

                    cart.forEach(it => {
                        if (!it.image || !String(it.image).trim() || String(it.image).endsWith('no-image.svg')) {
                            const p = byId[String(it.id)];
                            if (p) {
                                const raw = extractFromProduct(p);
                                if (raw) {
                                    it.image = normalizeImageUrl(raw);
                                    changed = true;
                                }
                            }
                        }
                    });
                    if (changed) saveCart(cart);
                    // expose a short status so user can know which endpoint filled images
                    try {
                        const statusEl = document.getElementById('cart-status');
                        if (statusEl) statusEl.textContent = usedEndpoint ? `Imágenes cargadas desde ${usedEndpoint}` : 'Imágenes cargadas';
                    } catch (e) { }
                } catch (e) { console.error('Could not auto-fill cart images', e); }
            }

            function normalizeImageUrl(u) {
                if (!u) return null;
                if (u === 'undefined' || u === 'null') return null;
                try {
                    if (u.startsWith('http://') || u.startsWith('https://') || u.startsWith('//')) return u;
                } catch (e) { }
                if (u.startsWith('/')) return window.location.origin + u;
                return window.location.origin + '/' + u;
            }

            function extractImage(uOrObj) {
                if (!uOrObj) return null;
                if (typeof uOrObj === 'string') return uOrObj;
                if (typeof uOrObj === 'object') {
                    if (uOrObj.url) return uOrObj.url;
                    if (uOrObj.path) return uOrObj.path;
                    if (uOrObj.src) return uOrObj.src;
                }
                return null;
            }

            function saveCart(items) { localStorage.setItem(key, JSON.stringify(items || [])); }

            function formatCurrency(amount) {
                const num = parseFloat(amount) || 0;
                return num.toLocaleString('es-ES', {
                    style: 'currency',
                    currency: 'EUR',
                    minimumFractionDigits: 2
                });
            }

            function updateQuantity(idx, change) {
                const cart = loadCart();
                if (!cart[idx]) return;

                let newQuantity = (cart[idx].quantity || 1) + change;
                if (newQuantity < 1) newQuantity = 1;
                if (newQuantity > 99) newQuantity = 99;

                cart[idx].quantity = newQuantity;
                saveCart(cart);
                render();

                // Trigger cart update event for navbar badge
                window.dispatchEvent(new Event('cartUpdated'));
            }

            async function render() {
                let cart = loadCart();
                await ensureImages(cart);
                cart = loadCart();

                if (!cart || !cart.length) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="bi bi-cart-x"></i>
                            <h4>Tu carrito está vacío</h4>
                            <p class="mb-4">Descubre nuestros deliciosos productos y añádelos a tu carrito para comenzar tu pedido.</p>
                            <a href="/Sabores360/views/cliente/dashboard.php" class="btn-menu ">
                                Explorar Menú
                            </a>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = '';
                let total = 0;

                cart.forEach((it, idx) => {
                    const itemTotal = (it.price || 0) * (it.quantity || 1);
                    total += itemTotal;

                    const cartItem = document.createElement('div');
                    cartItem.className = 'cart-item';

                    const placeholder = '/Sabores360/assets/img/no-image.svg';
                    const raw = extractImage(it.image);
                    const imgSrc = raw ? normalizeImageUrl(raw) : null;

                    cartItem.innerHTML = `
                        <div class="item-content">
                            <img src="${imgSrc || placeholder}" 
                                 onerror="this.onerror=null;this.src='${placeholder}';" 
                                 class="item-image" alt="${it.name || 'Producto'}">
                            
                            <div class="item-details">
                                <div class="item-name">${it.name || 'Producto'}</div>
                                <div class="item-price">${formatCurrency(it.price || 0)} por unidad</div>
                            </div>
                            
                            <div class="item-controls">
                                <div class="quantity-control">
                                    <div class="quantity-input-group">
                                        <button type="button" class="quantity-btn" data-action="decrease" data-idx="${idx}" ${(it.quantity || 1) <= 1 ? 'disabled' : ''}>
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="quantity-input qty" data-idx="${idx}" value="${it.quantity || 1}" min="1" max="99">
                                        <button type="button" class="quantity-btn" data-action="increase" data-idx="${idx}">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="subtotal">Subtotal: ${formatCurrency(itemTotal)}</div>
                                
                                <button type="button" class="btn-remove remove" data-idx="${idx}">
                                    <i class="bi bi-trash"></i>
                                    Quitar
                                </button>
                            </div>
                        </div>
                    `;

                    container.appendChild(cartItem);
                });

                // Add total and actions
                const totalSection = document.createElement('div');
                totalSection.innerHTML = `
                    <div class="cart-total">
                        <h3 class="total-amount">Total: ${formatCurrency(total)}</h3>
                    </div>
                    
                    <div class="cart-actions">
                        <button type="button" class="btn-checkout" id="checkout">
                            <i class="bi bi-credit-card"></i>
                            Proceder al Pago
                        </button>
                        <button type="button" class="btn-clear" id="clear">
                            <i class="bi bi-trash"></i>
                            Vaciar Carrito
                        </button>
                    </div>
                `;

                container.appendChild(totalSection);
            }

            // Event delegation for cart interactions
            container.addEventListener('click', (ev) => {
                // Remove item button
                if (ev.target.matches('.remove') || ev.target.closest('.remove')) {
                    const btn = ev.target.matches('.remove') ? ev.target : ev.target.closest('.remove');
                    const idx = parseInt(btn.getAttribute('data-idx'), 10);

                    Swal.fire({
                        title: '¿Quitar producto?',
                        text: '¿Estás seguro de que quieres quitar este producto del carrito?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#ff6b35',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, quitar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const cart = loadCart();
                            cart.splice(idx, 1);
                            saveCart(cart);
                            render();

                            // Trigger cart update event for navbar badge
                            window.dispatchEvent(new Event('cartUpdated'));

                            Swal.fire({
                                title: '¡Producto eliminado!',
                                text: 'El producto ha sido quitado del carrito.',
                                icon: 'success',
                                confirmButtonColor: '#ff6b35',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }

                // Quantity control buttons
                if (ev.target.matches('.quantity-btn') || ev.target.closest('.quantity-btn')) {
                    const btn = ev.target.matches('.quantity-btn') ? ev.target : ev.target.closest('.quantity-btn');
                    const action = btn.getAttribute('data-action');
                    const idx = parseInt(btn.getAttribute('data-idx'), 10);

                    if (action === 'increase') {
                        updateQuantity(idx, 1);
                    } else if (action === 'decrease') {
                        updateQuantity(idx, -1);
                    }
                }

                // Clear cart button
                if (ev.target.matches('#clear') || ev.target.closest('#clear')) {
                    Swal.fire({
                        title: '¿Vaciar carrito?',
                        text: '¿Estás seguro de que quieres vaciar todo el carrito? Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, vaciar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            saveCart([]);
                            render();

                            // Trigger cart update event for navbar badge
                            window.dispatchEvent(new Event('cartUpdated'));

                            Swal.fire({
                                title: '¡Carrito vaciado!',
                                text: 'Todos los productos han sido eliminados del carrito.',
                                icon: 'success',
                                confirmButtonColor: '#ff6b35',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }

                // Checkout button
                if (ev.target.matches('#checkout') || ev.target.closest('#checkout')) {
                    const cart = loadCart();
                    if (!cart || !cart.length) {
                        Swal.fire({
                            title: 'Carrito vacío',
                            text: 'Agrega algunos productos antes de proceder al pago.',
                            icon: 'info',
                            confirmButtonColor: '#ff6b35',
                            confirmButtonText: 'Entendido'
                        });
                        return;
                    }
                    window.location.href = '/Sabores360/views/cliente/checkout.php';
                }
            });

            // Handle quantity input changes
            container.addEventListener('change', (ev) => {
                if (ev.target.matches('.qty')) {
                    const idx = parseInt(ev.target.getAttribute('data-idx'), 10);
                    let q = parseInt(ev.target.value, 10) || 1;

                    if (q < 1) q = 1;
                    if (q > 99) q = 99;

                    const cart = loadCart();
                    if (cart[idx]) {
                        cart[idx].quantity = q;
                        saveCart(cart);
                        render();

                        // Trigger cart update event for navbar badge
                        window.dispatchEvent(new Event('cartUpdated'));
                    }
                }
            });



            // flash helper to show non-blocking messages (same style as dashboard)
            function showFlash(msg, timeout = 2500) {
                try {
                    let el = document.getElementById('flash-msg');
                    if (!el) {
                        el = document.createElement('div');
                        el.id = 'flash-msg';
                        el.style.position = 'fixed';
                        el.style.right = '12px';
                        el.style.bottom = '12px';
                        el.style.padding = '8px 12px';
                        el.style.background = '#222';
                        el.style.color = '#fff';
                        el.style.borderRadius = '4px';
                        el.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
                        el.style.zIndex = 9999;
                        document.body.appendChild(el);
                    }
                    el.textContent = msg;
                    el.style.display = 'block';
                    clearTimeout(el._t);
                    el._t = setTimeout(() => { el.style.display = 'none'; }, timeout);
                } catch (e) { console.log(msg); }
            }

            // initial render
            render();
        })();
    </script>
</body>

</html>