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
        <nav>
            <a href="/Sabores360/views/cliente/dashboard.php">Menú</a> |
            <a href="/Sabores360/views/cliente/my_orders.php">Mis pedidos</a> |
            <a href="/Sabores360/views/cliente/profile.php">Mi perfil</a> |
            <a href="/Sabores360/logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Productos</h2>
            <div id="product-list">Cargando...</div>
        </section>

        <section>
            <h2>Carrito</h2>
            <div id="cart">Carrito vacío</div>
            <button id="checkout">Pagar</button>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const cart = [];
            async function loadProducts() {
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('client/products') : (async () => { const res = await fetch(base + 'client/products', { credentials: 'include' }); return res.json(); })());
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
                            el.innerHTML = `<strong>${p.name}</strong> - ${p.price} - ${available ? 'Disponible' : 'No disponible'} <br> Cant: <input type="number" min="1" value="1" data-id="${p.id}" class="qty"> <button class="add" data-id="${p.id}" data-name="${p.name}" data-price="${p.price}">Añadir</button>`;
                            c.appendChild(el);
                        });
                        c.addEventListener('click', (ev) => {
                            if (ev.target.matches('.add')) {
                                const id = ev.target.getAttribute('data-id');
                                const price = parseFloat(ev.target.getAttribute('data-price')) || 0;
                                const name = ev.target.getAttribute('data-name');
                                const qtyInput = c.querySelector(`.qty[data-id="${id}"]`);
                                const qty = parseInt(qtyInput.value, 10) || 1;
                                cart.push({ id: parseInt(id, 10), name, price, quantity: qty });
                                renderCart();
                            }
                        });
                    } else c.textContent = 'No hay productos disponibles.';
                } catch (err) { document.getElementById('product-list').textContent = 'Error al cargar productos.'; }
            }
            function renderCart() {
                const el = document.getElementById('cart');
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

            document.getElementById('cart').addEventListener('click', (ev) => {
                if (ev.target.matches('.remove')) {
                    const idx = parseInt(ev.target.getAttribute('data-index'), 10);
                    cart.splice(idx, 1); renderCart();
                }
            });

            document.getElementById('checkout').addEventListener('click', async () => {
                if (!cart.length) return alert('Carrito vacío');
                const delivery_address = prompt('Dirección de entrega');
                const payment_method = prompt('Método de pago (Efectivo/Tarjeta)');
                try {
                    const payload = { delivery_address, payment_method, cart };
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post('client/orders', payload) : (async () => { const res = await fetch(base + 'client/orders', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); return res.json(); })());
                    if (d && d.success) { alert('Pedido creado #' + d.order_id); cart.length = 0; renderCart(); }
                    else alert(d.message || 'No se pudo crear pedido');
                } catch (err) { alert('Error al crear pedido'); }
            });

            loadProducts();
        })();
    </script>
</body>

</html>