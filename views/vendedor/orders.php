<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Pedidos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Pedidos asignados</h1>
        <?php $active = 'orders';
        require __DIR__ . '/../_navbar.php'; ?>
    </header>

    <main>
        <div id="my-orders">Cargando pedidos...</div>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const container = document.getElementById('my-orders');
            try {
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('seller/orders') : (async () => { const res = await fetch((window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE + 'seller/orders' : 'http://localhost:8080/api/seller/orders', { credentials: 'include' }); const t = await res.text(); try { return JSON.parse(t); } catch (e) { return { success: res.ok, httpStatus: res.status, raw: t }; } })());
                console.debug('seller/orders response', d);
                // try to resolve orders array in several common shapes
                function findArrayOfObjectsWithId(obj) {
                    if (!obj) return null;
                    if (Array.isArray(obj) && obj.length && obj[0] && (obj[0].id !== undefined)) return obj;
                    if (obj.orders && Array.isArray(obj.orders)) return obj.orders;
                    if (obj.data && Array.isArray(obj.data.orders)) return obj.data.orders;
                    if (obj.data && Array.isArray(obj.data)) return obj.data;
                    // try to find any property that's an array of objects with id
                    for (const k of Object.keys(obj)) {
                        const v = obj[k];
                        if (Array.isArray(v) && v.length && v[0] && (v[0].id !== undefined)) return v;
                    }
                    return null;
                }

                // Prefer the explicit shape { success:true, data:{ orders: [...] } }
                const orders = (d && d.data && Array.isArray(d.data.orders)) ? d.data.orders : ((d && Array.isArray(d.orders)) ? d.orders : (findArrayOfObjectsWithId(d) || findArrayOfObjectsWithId(d && d.data) || null));
                if (orders && Array.isArray(orders)) {
                    container.innerHTML = '';
                    orders.forEach(o => {
                        const el = document.createElement('div');
                        el.className = 'order-row';
                        const status = o.status || o.state || '';
                        const clientName = (o.client && (o.client.name || o.client.email)) ? (o.client.name || o.client.email) : '';
                        const addr = o.deliveryAddress || o.delivery_address || '';
                        const total = o.totalAmount || o.total_amount || o.total || '';

                        // build status select (fixed set of options)
                        const statuses = ['Pendiente', 'Confirmado', 'En preparación', 'En camino', 'Entregado', 'Cancelado'];
                        const sel = document.createElement('select'); sel.className = 'status-select'; sel.setAttribute('data-id', o.id);
                        // populate select with all allowed statuses; current status will be selected when matching
                        statuses.forEach(s => { const opt = document.createElement('option'); opt.value = s; opt.textContent = s; if (status && status.toString().toLowerCase() === s.toLowerCase()) opt.selected = true; sel.appendChild(opt); });

                        const btn = document.createElement('button'); btn.className = 'btn-change'; btn.textContent = 'Actualizar estado'; btn.setAttribute('data-id', o.id);
                        const info = document.createElement('div'); info.className = 'order-info';
                        info.innerHTML = `<strong>#${o.id}</strong> - ${status} - ${total}€ - ${clientName} - ${addr}`;
                        // store values on the row for later updates
                        el.setAttribute('data-id', o.id);
                        el.setAttribute('data-total', total);
                        el.setAttribute('data-client', clientName);
                        el.setAttribute('data-addr', addr);

                        el.appendChild(info);
                        el.appendChild(sel);
                        el.appendChild(btn);
                        const msg = document.createElement('div'); msg.className = 'status-msg'; msg.style.marginTop = '6px'; el.appendChild(msg);
                        container.appendChild(el);
                    });

                    // delegated handler for update buttons
                    container.addEventListener('click', async (ev) => {
                        if (ev.target.matches('.btn-change')) {
                            const id = ev.target.getAttribute('data-id');
                            const row = ev.target.closest('.order-row');
                            const select = row && row.querySelector('.status-select');
                            const msg = row && row.querySelector('.status-msg');
                            if (!select) return;
                            const newStatus = select.value && select.value.toString().trim();
                            if (!newStatus) { if (msg) msg.textContent = 'Seleccione un estado antes de actualizar.'; return; }
                            ev.target.disabled = true; if (msg) msg.textContent = 'Actualizando...';
                            try {
                                const path = `seller/orders/${id}/status`;
                                const body = { newStatus: newStatus, status: newStatus };
                                const res = (window.SABORES360 && SABORES360.API) ? await SABORES360.API.post(path, body) : await (async () => { const r = await fetch((window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE + path : `http://localhost:8080/api/${path}`, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, httpStatus: r.status, raw: t }; } })();
                                if (res && res.success) {
                                    if (msg) msg.textContent = 'Estado actualizado.';
                                    // update displayed status using stored data attributes
                                    const info = row.querySelector('.order-info');
                                    if (info) {
                                        const idLabel = `<strong>#${id}</strong>`;
                                        const totalLabel = row.getAttribute('data-total') || '';
                                        const clientLabel = row.getAttribute('data-client') || '';
                                        const addrLabel = row.getAttribute('data-addr') || '';
                                        info.innerHTML = `${idLabel} - ${newStatus} - ${totalLabel}€ - ${clientLabel} - ${addrLabel}`;
                                    }
                                } else {
                                    if (msg) msg.textContent = (res && res.message) ? res.message : 'Error al actualizar estado.';
                                }
                            } catch (err) {
                                if (msg) msg.textContent = 'Error de red';
                            } finally { ev.target.disabled = false; }
                        }
                    });
                    // allow pressing Enter on the custom input to submit
                    container.addEventListener('keydown', (ev) => {
                        if (ev.target && ev.target.matches && ev.target.matches('.status-custom') && ev.key === 'Enter') {
                            ev.preventDefault();
                            const id = ev.target.getAttribute('data-id');
                            const btn = container.querySelector(`.btn-change[data-id="${id}"]`);
                            if (btn) btn.click();
                        }
                        // toggle visibility of custom input when select changes
                        if (ev.target && ev.target.matches && ev.target.matches('.status-select') && ev.type === 'keydown') {
                            // no-op for keydown on select
                        }
                    });

                    // show/hide custom input when selecting 'Otro...'
                    container.addEventListener('change', (ev) => {
                        if (ev.target && ev.target.matches && ev.target.matches('.status-select')) {
                            const row = ev.target.closest('.order-row');
                            const custom = row && row.querySelector('.status-custom');
                            if (!custom) return;
                            if (ev.target.value === '__other__') { custom.style.display = 'inline-block'; custom.focus(); }
                            else { custom.style.display = 'none'; }
                        }
                    });
                } else {
                    container.textContent = 'No tienes pedidos.';
                    const dbg = document.createElement('pre'); dbg.style.fontSize = '0.8em'; dbg.style.marginTop = '8px'; dbg.textContent = JSON.stringify(d, null, 2);
                    container.appendChild(dbg);
                }
            } catch (err) { container.textContent = 'Error al cargar pedidos.'; }
        })();
    </script>
</body>

</html>