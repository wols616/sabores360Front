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
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Menú</h1>
        <?php require __DIR__ . '/../_navbar.php'; ?>
    </header>

    <main>
        <section>
            <h2>Productos</h2>
            <div id="product-list">Cargando...</div>
        </section>

        <section>
            <h2>Carrito</h2>
            <div><a href="/Sabores360/views/cliente/cart.php">Ver carrito / Pagar</a></div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const cart = [];

            function normalizeImageUrl(u) {
                if (!u) return null;
                if (u === 'undefined' || u === 'null') return null;
                try {
                    // already absolute
                    if (u.startsWith('http://') || u.startsWith('https://') || u.startsWith('//')) return u;
                } catch (e) { }
                if (u.startsWith('/')) return window.location.origin + u;
                return window.location.origin + '/' + u;
            }

            function extractImageFromProduct(p) {
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
                // arrays
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
            async function loadProducts() {
                try {
                    // Use the fuller product payload which includes image url: /client/products/full
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('client/products/full') : (async () => { const res = await fetch(base + 'client/products/full', { credentials: 'include' }); return res.json(); })());
                    const c = document.getElementById('product-list');
                    // Support multiple response shapes:
                    // - { products: [...] }
                    // - { data: [...] }
                    // - { data: { products: [...] } }
                    // - { items: [...] }
                    let products = [];
                    if (d && d.success) {
                        if (Array.isArray(d.products)) products = d.products;
                        else if (Array.isArray(d.data)) products = d.data;
                        else if (d.data && Array.isArray(d.data.products)) products = d.data.products;
                        else if (d.data && Array.isArray(d.data.items)) products = d.data.items;
                        else if (Array.isArray(d.items)) products = d.items;
                    }
                    if (products && products.length) {
                        c.innerHTML = '';
                        products.forEach(p => {
                            const el = document.createElement('div');
                            const available = (typeof p.is_available !== 'undefined') ? p.is_available : (typeof p.stock !== 'undefined' ? (p.stock > 0) : true);
                            // replicate admin product thumbnail logic: use common keys only
                            const rawImg = p.imageUrl || p.image_url || p.image;
                            const imgSrc = normalizeImageUrl(rawImg);
                            const placeholder = '/Sabores360/assets/img/no-image.svg';
                            const img = `<img src="${imgSrc ? imgSrc : placeholder}" onerror="this.onerror=null;this.src='${placeholder}';" alt="${p.name || ''}" style="max-width:120px;display:block;margin-bottom:6px;">`;
                            el.innerHTML = `${img}<strong>${p.name}</strong> - ${p.price} - ${available ? 'Disponible' : 'No disponible'} <br> Cant: <input type="number" min="1" value="1" data-id="${p.id}" class="qty"> <button class="add" data-id="${p.id}" data-name="${p.name}" data-price="${p.price}" data-image="${imgSrc || ''}">Añadir</button>`;
                            c.appendChild(el);
                        });
                        c.addEventListener('click', (ev) => {
                            if (ev.target.matches('.add')) {
                                const id = ev.target.getAttribute('data-id');
                                const price = parseFloat(ev.target.getAttribute('data-price')) || 0;
                                const name = ev.target.getAttribute('data-name');
                                const image = ev.target.getAttribute('data-image') || null;
                                const qtyInput = c.querySelector(`.qty[data-id="${id}"]`);
                                const qty = parseInt(qtyInput.value, 10) || 1;
                                // persist cart to localStorage so cart is shared across pages
                                const key = 'sabores360_cart';
                                let store = [];
                                try { store = JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) { store = []; }
                                store.push({ id: parseInt(id, 10), name, price, quantity: qty, image: normalizeImageUrl(image) });
                                localStorage.setItem(key, JSON.stringify(store));
                                // notify user (non-blocking)
                                showFlash('Producto añadido al carrito');
                            }
                        });
                    } else c.textContent = 'No hay productos disponibles.';
                } catch (err) { document.getElementById('product-list').textContent = 'Error al cargar productos.'; }
            }
            function renderCart() {
                const el = document.getElementById('cart');
                if (!el) return; // cart moved to separate page; nothing to render here if element missing
                if (!cart.length) { el.textContent = 'Carrito vacío'; return; }
                el.innerHTML = '';
                let total = 0;
                cart.forEach((it, i) => {
                    total += it.price * it.quantity;
                    const d = document.createElement('div');
                    d.innerHTML = `${it.name} x${it.quantity} - ${it.price * it.quantity} <button data-index="${i}" class="remove">Quitar</button>`;
                    el.appendChild(d);
                });
                const t = document.createElement('div'); t.innerHTML = `<strong>Total: ${total.toFixed(2)}</strong>`; el.appendChild(t);
            }

            const cartEl = document.getElementById('cart');
            if (cartEl) {
                cartEl.addEventListener('click', (ev) => {
                    if (ev.target.matches('.remove')) {
                        const idx = parseInt(ev.target.getAttribute('data-index'), 10);
                        cart.splice(idx, 1); renderCart();
                    }
                });
            }

            const checkoutEl = document.getElementById('checkout');
            if (checkoutEl) {
                checkoutEl.addEventListener('click', async () => {
                    if (!cart.length) return alert('Carrito vacío');
                    const delivery_address = prompt('Dirección de entrega');
                    const payment_method = prompt('Método de pago (Efectivo/Tarjeta)');
                    try {
                        const payload = { delivery_address, payment_method, cart };
                        const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post('client/orders', payload) : (async () => { const res = await fetch(base + 'client/orders', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); return res.json(); })());
                        if (d && d.success) {
                            alert('Pedido creado #' + (d.order_id || d.data && d.data.order_id || ''));
                            cart.length = 0; renderCart();
                            // redirect to My Orders so user sees the new order
                            window.location.href = '/Sabores360/views/cliente/my_orders.php?_t=' + Date.now();
                        } else alert(d.message || 'No se pudo crear pedido');
                    } catch (err) { alert('Error al crear pedido'); }
                });
            }

            loadProducts();

            // small flash message helper (transient)
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
        })();
    </script>
</body>

</html>