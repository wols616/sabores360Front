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
            max-width: 900px;
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

        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .summary-section,
        .form-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.1);
        }

        .section-title {
            color: var(--orange-primary);
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .checkout-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .checkout-item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .item-quantity {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .item-price {
            font-weight: 700;
            color: var(--orange-dark);
        }

        .checkout-total {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-top: 1rem;
        }

        .total-amount {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--orange-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .checkout-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-confirm {
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
            flex: 1;
            justify-content: center;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .btn-confirm:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
        }

        .btn-cancel {
            background: white;
            border: 2px solid #6c757d;
            color: #6c757d;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-cart i {
            font-size: 3rem;
            color: var(--orange-primary);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .checkout-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .summary-section,
            .form-section {
                padding: 1.5rem;
            }

            .checkout-actions {
                flex-direction: column;
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
                <i class="bi bi-credit-card"></i>
                Finalizar Pedido
            </h1>
            <p class="text-muted mb-0">Revisa tu pedido y completa los datos de entrega</p>
        </div>

        <div class="checkout-content">
            <!-- Order Summary -->
            <div class="summary-section">
                <h3 class="section-title">
                    <i class="bi bi-receipt"></i>
                    Resumen del Pedido
                </h3>
                <div id="summary">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-truck"></i>
                    Datos de Entrega y Pago
                </h3>

                <form id="checkout-form">
                    <div class="form-group">
                        <label for="delivery_address" class="form-label">
                            <i class="bi bi-geo-alt"></i>
                            Dirección de entrega
                        </label>
                        <textarea id="delivery_address" name="delivery_address" class="form-control" rows="3"
                            placeholder="Ingresa tu dirección completa..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="payment_method" class="form-label">
                            <i class="bi bi-credit-card"></i>
                            Método de pago
                        </label>
                        <select id="payment_method" name="payment_method" class="form-select" required>
                            <option value="">-- Seleccione método de pago --</option>
                            <option value="Efectivo">💵 Efectivo</option>
                            <option value="Tarjeta">💳 Tarjeta de crédito/débito</option>
                        </select>
                    </div>

                    <div class="checkout-actions">
                        <button type="submit" class="btn-confirm">
                            <i class="bi bi-check-circle"></i>
                            Confirmar Pedido
                        </button>
                        <button type="button" class="btn-cancel" id="cancel">
                            <i class="bi bi-arrow-left"></i>
                            Volver al Carrito
                        </button>
                    </div>

                    <div id="checkout-msg"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const formatCurrency = (amount) => `$${parseFloat(amount).toFixed(2)}`;

        // API base and auth helpers (match profile/cart behavior)
        const apiBase = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';

        function getCookie(name) {
            const match = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
            return match ? match.pop() : '';
        }

        function getAuthToken() {
            try { if (window.SABORES360 && (SABORES360.AUTH_TOKEN || SABORES360.token)) return SABORES360.AUTH_TOKEN || SABORES360.token; } catch (e) { }
            // Do NOT read token from localStorage; prefer cookie-based token
            return getCookie('auth_token');
        }

        function buildAuthHeaders() {
            const headers = { 'Content-Type': 'application/json' };
            const token = getAuthToken();
            if (token) headers['Authorization'] = 'Bearer ' + token;
            return headers;
        }

        function loadCheckoutSummary() {
            const cart = JSON.parse(localStorage.getItem('sabores360_cart') || '[]');

            if (cart.length === 0) {
                const summary = document.getElementById('summary');
                summary.innerHTML = `
                <div class="empty-cart">
                    <i class="bi bi-cart-x"></i>
                    <h4>Tu carrito está vacío</h4>
                    <p class="mb-3">Agrega algunos productos para continuar</p>
                    <a href="/Sabores360/views/cliente/dashboard.php" class="btn btn-primary">
                        <i class="bi bi-shop"></i> Ver Productos
                    </a>
                </div>
                `;
                return;
            }

            let html = '';
            let total = 0;

            cart.forEach(item => {
                const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
                total += itemTotal;

                html += `
                <div class="checkout-item">
                    <div class="item-info">
                        <div class="item-name">${item.name}</div>
                        <div class="item-quantity">${item.quantity} × ${formatCurrency(item.price)}</div>
                    </div>
                    <div class="item-price">${formatCurrency(itemTotal)}</div>
                </div>
                `;
            });

            html += `
            <div class="checkout-total">
                <h5 class="total-amount">Total: ${formatCurrency(total)}</h5>
            </div>
            `;

            document.getElementById('summary').innerHTML = html;
        }

        document.getElementById('checkout-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const deliveryAddress = document.getElementById('delivery_address').value.trim();
            const paymentMethod = document.getElementById('payment_method').value;
            const msgDiv = document.getElementById('checkout-msg');

            // Validación
            if (!deliveryAddress || !paymentMethod) {
                msgDiv.innerHTML = `
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-exclamation-triangle"></i>
                    Por favor, completa todos los campos requeridos.
                </div>
                `;
                return;
            }

            const cart = JSON.parse(localStorage.getItem('sabores360_cart') || '[]');

            if (cart.length === 0) {
                msgDiv.innerHTML = `
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-cart-x"></i>
                    El carrito está vacío.
                </div>
                `;
                return;
            }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                Procesando...
            `;

            // Build payload matching API: { delivery_address, payment_method, cart: [{id, quantity}, ...] }
            const payload = {
                delivery_address: deliveryAddress,
                payment_method: paymentMethod,
                cart: (cart || []).map(it => ({ id: parseInt(it.id, 10) || parseInt(it.product_id || it.productId || 0, 10) || 0, quantity: parseInt(it.quantity, 10) || 1 }))
            };

            try {
                // Prefer app helper if available
                let response;
                if (window.SABORES360 && SABORES360.API && typeof SABORES360.API.post === 'function') {
                    try {
                        const d = await SABORES360.API.post('client/orders', payload);
                        response = { ok: !!(d && (d.success || d.order_id)), _data: d };
                    } catch (e) {
                        console.error('SABORES360.API.post failed', e);
                        response = null;
                    }
                }

                if (!response) {
                    response = await fetch(apiBase + 'client/orders', {
                        method: 'POST',
                        headers: buildAuthHeaders(),
                        credentials: 'include',
                        body: JSON.stringify(payload)
                    });
                }

                const data = response._data ? response._data : await (response.json ? response.json() : Promise.resolve({ success: response.ok }));

                // success if helper returned order_id or success === true
                const success = (data && (data.success || data.order_id));
                if (success) {
                    localStorage.removeItem('sabores360_cart');

                    // Dispatch cartUpdated event
                    window.dispatchEvent(new CustomEvent('cartUpdated'));

                    msgDiv.innerHTML = `
                    <div class="alert alert-success alert-custom">
                        <i class="bi bi-check-circle"></i>
                        ¡Pedido creado exitosamente! Redirigiendo a mis pedidos...
                    </div>
                    `;

                    setTimeout(() => {
                        window.location.href = '/Sabores360/views/cliente/my_orders.php';
                    }, 2000);
                } else {
                    const errMsg = (data && data.message) ? data.message : 'No se pudo procesar el pedido';
                    msgDiv.innerHTML = `
                    <div class="alert alert-danger alert-custom">
                        <i class="bi bi-exclamation-triangle"></i>
                        Error: ${errMsg}
                    </div>
                    `;
                }
            } catch (error) {
                console.error('Checkout error:', error);
                msgDiv.innerHTML = `
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-wifi-off"></i>
                    Error de conexión. Por favor, intenta nuevamente.
                </div>
                `;
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <i class="bi bi-check-circle"></i>
                    Confirmar Pedido
                `;
            }
        });

        document.getElementById('cancel').addEventListener('click', () => {
            window.location.href = '/Sabores360/views/cliente/cart.php';
        });

        // Load summary on page load
        document.addEventListener('DOMContentLoaded', loadCheckoutSummary);
    </script>
</body>

</html>