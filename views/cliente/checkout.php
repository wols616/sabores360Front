<?php
require __DIR__ . '/../../includes/auth_check.php';
require_auth();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cliente - Checkout</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
    <style>
        .checkout-summary {
            margin-bottom: 12px;
        }

        .checkout-item {
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }

        .checkout-actions {
            margin-top: 12px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Checkout</h1>
        <?php require __DIR__ . '/../_navbar.php'; ?>
    </header>

    <main>
        <section>
            <h2>Resumen del pedido</h2>
            <div id="summary" class="checkout-summary">Cargando...</div>

            <h3>Datos de entrega y pago</h3>
            <form id="checkout-form">
                <div>
                    <label for="delivery_address">Dirección de entrega</label><br>
                    <textarea id="delivery_address" name="delivery_address" rows="3" style="width:100%;"></textarea>
                </div>
                <div style="margin-top:8px;">
                    <label for="payment_method">Método de pago</label><br>
                    <select id="payment_method" name="payment_method">
                        <option value="">-- Seleccione método --</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                    </select>
                </div>
                <div class="checkout-actions">
                    <button type="submit">Confirmar y pagar</button>
                    <button type="button" id="cancel">Cancelar</button>
                </div>
                <div id="checkout-msg" style="margin-top:8px;color:#a00;"></div>
            </form>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const key = 'sabores360_cart';
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const summary = document.getElementById('summary');
            const form = document.getElementById('checkout-form');
            const msgEl = document.getElementById('checkout-msg');

            function loadCart() { try { return JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) { return []; } }
            function saveCart(items) { localStorage.setItem(key, JSON.stringify(items || [])); }

            function renderSummary() {
                const cart = loadCart();
                if (!cart || !cart.length) { summary.innerHTML = '<div>Carrito vacío</div>'; return; }
                let total = 0;
                summary.innerHTML = '';
                cart.forEach(it => {
                    const div = document.createElement('div');
                    div.className = 'checkout-item';
                    const subtotal = (it.price || 0) * (it.quantity || 1);
                    total += subtotal;
                    div.innerHTML = `${escapeHtml(it.name)} x ${it.quantity} - ${Number(subtotal).toFixed(2)}€`;
                    summary.appendChild(div);
                });
                const t = document.createElement('div'); t.innerHTML = `<strong>Total: ${Number(total).toFixed(2)}€</strong>`; summary.appendChild(t);
            }

            form.addEventListener('submit', async (ev) => {
                ev.preventDefault();
                msgEl.textContent = '';
                const delivery_address = document.getElementById('delivery_address').value.trim();
                const payment_method = document.getElementById('payment_method').value;
                const cart = loadCart();
                if (!cart || !cart.length) { msgEl.textContent = 'Carrito vacío'; return; }
                if (!delivery_address) { msgEl.textContent = 'Dirección de entrega obligatoria'; return; }
                if (!payment_method) { msgEl.textContent = 'Seleccione método de pago'; return; }
                try {
                    const payload = { delivery_address, payment_method, cart };
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post('client/orders', payload) : (async () => { const r = await fetch(base + 'client/orders', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t } } })());
                    if (d && d.success) {
                        // clear cart and redirect to my_orders
                        saveCart([]);
                        window.location.href = '/Sabores360/views/cliente/my_orders.php?_t=' + Date.now();
                    } else {
                        msgEl.textContent = (d && d.message) ? d.message : 'Error al crear pedido';
                    }
                } catch (err) { msgEl.textContent = 'Error de red: ' + (err && err.message ? err.message : String(err)); }
            });

            document.getElementById('cancel').addEventListener('click', () => { window.location.href = '/Sabores360/views/cliente/cart.php'; });

            // helper to avoid XSS
            function escapeHtml(str) {
                if (!str) return '';
                return String(str).replace(/[&<>\"'`]/g, function (s) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '`': '&#96;' })[s]; });
            }

            renderSummary();
        })();
    </script>
</body>

</html>