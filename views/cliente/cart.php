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
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Carrito</h1>
        <nav>
            <a href="/Sabores360/views/cliente/dashboard.php">Menú</a> |
            <a href="/Sabores360/views/cliente/my_orders.php">Mis pedidos</a> |
            <a href="/Sabores360/views/cliente/profile.php">Mi perfil</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Items en el carrito</h2>
            <div id="cart">Cargando carrito...</div>
            <div style="margin-top:12px;"><button id="checkout">Pagar</button> <button id="clear">Vaciar
                    carrito</button></div>
        </section>
    </main>

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

            async function render() {
                let cart = loadCart();
                await ensureImages(cart);
                cart = loadCart();
                if (!cart || !cart.length) { container.innerHTML = '<div>Carrito vacío</div>'; return; }
                container.innerHTML = '';
                let total = 0;
                cart.forEach((it, idx) => {
                    total += (it.price || 0) * (it.quantity || 1);
                    const row = document.createElement('div');
                    const placeholder = '/Sabores360/assets/img/no-image.svg';
                    const raw = extractImage(it.image);
                    const imgSrc = raw ? normalizeImageUrl(raw) : null;
                    const img = `<img src="${imgSrc ? imgSrc : placeholder}" onerror="this.onerror=null;this.src='${placeholder}';" alt="${it.name || ''}" style="max-width:120px;display:inline-block;vertical-align:middle;margin-right:8px;">`;
                    row.innerHTML = `${img}<strong>${it.name}</strong> - ${it.price} x <input type="number" min="1" value="${it.quantity || 1}" data-idx="${idx}" class="qty"> = <span class="subtotal">${((it.price || 0) * (it.quantity || 1)).toFixed(2)}</span> <button class="remove" data-idx="${idx}">Quitar</button>`;
                    container.appendChild(row);
                });
                const footer = document.createElement('div'); footer.innerHTML = `<strong>Total: ${total.toFixed(2)}</strong>`; container.appendChild(footer);
            }

            container.addEventListener('click', (ev) => {
                if (ev.target.matches('.remove')) {
                    const idx = parseInt(ev.target.getAttribute('data-idx'), 10);
                    const cart = loadCart(); cart.splice(idx, 1); saveCart(cart); render();
                }
            });

            container.addEventListener('change', (ev) => {
                if (ev.target.matches('.qty')) {
                    const idx = parseInt(ev.target.getAttribute('data-idx'), 10);
                    const q = parseInt(ev.target.value, 10) || 1;
                    const cart = loadCart();
                    if (cart[idx]) { cart[idx].quantity = q; saveCart(cart); render(); }
                }
            });

            document.getElementById('clear').addEventListener('click', () => { if (!confirm('Vaciar carrito?')) return; saveCart([]); render(); });

            document.getElementById('checkout').addEventListener('click', async () => {
                const cart = loadCart();
                if (!cart || !cart.length) return alert('Carrito vacío');
                const delivery_address = prompt('Dirección de entrega');
                const payment_method = prompt('Método de pago (Efectivo/Tarjeta)');
                try {
                    const payload = { delivery_address, payment_method, cart };
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post('client/orders', payload) : (async () => { const r = await fetch(base + 'client/orders', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    if (d && d.success) {
                        alert('Pedido creado #' + (d.order_id || (d.data && d.data.order_id) || ''));
                        saveCart([]);
                        render();
                        window.location.href = '/Sabores360/views/cliente/my_orders.php?_t=' + Date.now();
                    } else {
                        console.error('Order API error', d);
                        alert(d && (d.message || d.error || d.raw) ? (d.message || d.error || JSON.stringify(d.raw || d)) : 'No se pudo crear pedido');
                    }
                } catch (err) { console.error('Checkout failed', err); alert('Error al crear pedido: ' + (err && err.message ? err.message : String(err))); }
            });

            // initial render
            render();
        })();
    </script>
</body>

</html>