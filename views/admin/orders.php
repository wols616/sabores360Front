<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Pedidos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Panel de Administrador</h1>
        <?php $active = 'orders';
        require __DIR__ . '/_admin_nav.php'; ?>
    </header>


    <main>
        <section>
            <h2>Listado de pedidos</h2>
            <div id="orders-list">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const container = document.getElementById('orders-list');
            container.textContent = 'Cargando...';
            try {
                // Fetch vendors and orders in parallel
                const [vendorsResp, ordersResp] = await Promise.all([
                    (window.SABORES360 && SABORES360.API) ? SABORES360.API.get('admin/vendors') : (async () => { const r = await fetch(base + 'admin/vendors', { credentials: 'include' }); return r.json(); })(),
                    (window.SABORES360 && SABORES360.API) ? SABORES360.API.get('admin/orders') : (async () => { const r = await fetch(base + 'admin/orders', { credentials: 'include' }); return r.json(); })()
                ]);

                // normalize vendors list
                let vendors = [];
                if (vendorsResp) {
                    if (Array.isArray(vendorsResp.vendors)) vendors = vendorsResp.vendors;
                    else if (vendorsResp.data && Array.isArray(vendorsResp.data.vendors)) vendors = vendorsResp.data.vendors;
                    else if (Array.isArray(vendorsResp.data)) vendors = vendorsResp.data;
                }

                // normalize orders list
                let orders = [];
                const d = ordersResp;
                if (d) {
                    if (Array.isArray(d.orders)) orders = d.orders;
                    else if (d.data && Array.isArray(d.data.orders)) orders = d.data.orders;
                    else if (Array.isArray(d.data)) orders = d.data;
                    else if (Array.isArray(d.items)) orders = d.items;
                }

                if (!orders || !orders.length) {
                    container.textContent = 'No hay pedidos.';
                    return;
                }

                // render orders with seller info and assign control
                container.innerHTML = '';
                orders.forEach(o => {
                    const id = o.id || '';
                    const status = o.status || o.state || '';
                    const total = (o.totalAmount || o.total_amount || o.total || '');
                    const created = o.createdAt || o.created_at || o.date || '';
                    const seller = (o.seller && (o.seller.name || o.seller.email)) ? (o.seller.name || o.seller.email) : null;

                    const card = document.createElement('div');
                    card.className = 'order-item';

                    // seller display + selector
                    const sellerLabel = document.createElement('div');
                    sellerLabel.innerHTML = `<strong>Vendedor asignado:</strong> ${seller ? '<span class="seller-name">' + escapeHtml(seller) + '</span>' : '<em>Sin asignar</em>'}`;

                    const select = document.createElement('select');
                    select.className = 'vendor-select';
                    const emptyOption = document.createElement('option');
                    emptyOption.value = '';
                    emptyOption.textContent = seller ? '-- cambiar vendedor --' : '-- seleccionar vendedor --';
                    select.appendChild(emptyOption);
                    let currentSellerId = null;
                    if (o.seller && o.seller.id) currentSellerId = o.seller.id;
                    vendors.forEach(v => {
                        const opt = document.createElement('option');
                        opt.value = v.id;
                        opt.textContent = v.name || v.email || ('Vendedor ' + v.id);
                        if (currentSellerId && Number(currentSellerId) === Number(v.id)) opt.selected = true;
                        select.appendChild(opt);
                    });

                    const assignBtn = document.createElement('button');
                    assignBtn.className = 'btn-assign';
                    assignBtn.textContent = 'Asignar';
                    assignBtn.dataset.orderId = id;

                    const msg = document.createElement('div');
                    msg.className = 'assign-msg';
                    msg.style.marginTop = '6px';

                    card.innerHTML = `<strong>#${escapeHtml(String(id))}</strong> - ${escapeHtml(status)} - ${escapeHtml(String(total))}€ - ${escapeHtml(created)}`;
                    card.appendChild(sellerLabel);
                    card.appendChild(select);
                    card.appendChild(assignBtn);
                    card.appendChild(msg);

                    container.appendChild(card);

                    assignBtn.addEventListener('click', async () => {
                        const sellerId = select.value;
                        if (!sellerId) {
                            msg.textContent = 'Seleccione un vendedor antes de asignar.';
                            return;
                        }
                        assignBtn.disabled = true;
                        msg.textContent = 'Asignando...';
                        try {
                            const path = `admin/orders/${id}/assign`;
                            const body = { sellerId: Number(sellerId) };
                            const res = (window.SABORES360 && SABORES360.API) ? await SABORES360.API.post(path, body) : await (async () => { const r = await fetch(base + path, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) }); return r.json(); })();
                            if (res && res.success) {
                                msg.textContent = 'Vendedor asignado.';
                                // update seller display
                                const selName = vendors.find(v => Number(v.id) === Number(sellerId));
                                if (selName) sellerLabel.querySelector('.seller-name') ? sellerLabel.querySelector('.seller-name').textContent = selName.name || selName.email : sellerLabel.innerHTML = `<strong>Vendedor asignado:</strong> <span class="seller-name">${escapeHtml(selName.name || selName.email)}</span>`;
                            } else {
                                msg.textContent = (res && res.message) ? res.message : 'Error al asignar vendedor.';
                            }
                        } catch (err) {
                            msg.textContent = 'Error de red al asignar.';
                        } finally { assignBtn.disabled = false; }
                    });
                });
            } catch (err) {
                container.textContent = 'Error al cargar pedidos o vendedores.';
            }

            // small helper to avoid XSS when inserting strings
            function escapeHtml(str) {
                if (!str) return '';
                return String(str).replace(/[&<>"'`]/g, function (s) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '`': '&#96;' })[s]; });
            }
        })();
    </script>
</body>

</html>