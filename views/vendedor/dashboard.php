<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Dashboard</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Panel Vendedor</h1>
        <?php $active = 'dashboard';
        require __DIR__ . '/../_navbar.php'; ?>
    </header>

    <main>
        <section id="seller-stats">Cargando...</section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const el = document.getElementById('seller-stats');
            try {
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('seller/dashboard') : (async () => { const res = await fetch((window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE + 'seller/dashboard' : 'http://localhost:8080/api/seller/dashboard', { credentials: 'include' }); const t = await res.text(); try { return JSON.parse(t); } catch (e) { return { success: res.ok, httpStatus: res.status, raw: t }; } })());
                console.debug('seller/dashboard response', d);
                if (d && d.success) {
                    const data = d.data || d;
                    // explicit expected shape: data.pending and data.recent_orders
                    let pending = (data && (typeof data.pending !== 'undefined' ? data.pending : (data.pending_count || data.pendingCount || data.pending_orders || 0))) || 0;
                    let recent = [];
                    if (data) {
                        if (Array.isArray(data.recent_orders)) recent = data.recent_orders;
                        else if (Array.isArray(data.recentOrders)) recent = data.recentOrders;
                        else if (Array.isArray(data.orders)) recent = data.orders;
                        else if (Array.isArray(data.items)) recent = data.items;
                        else if (Array.isArray(data.data)) recent = data.data;
                    }
                    // Infer pending from recent orders when server value is missing or zero.
                    let inferredPending = 0;
                    if (Array.isArray(recent) && recent.length) {
                        // include common spanish/english synonyms for pending/confirmed/preparing states
                        const lowerPendingStates = ['confirmado', 'pendiente', 'pending', 'en preparación', 'en preparacion', 'preparacion', 'confirmed', 'preparing', 'processing', 'por preparar'];
                        try {
                            inferredPending = recent.filter(o => {
                                const s = (o.status || o.state || '').toString().toLowerCase();
                                return lowerPendingStates.some(ps => s.includes(ps));
                            }).length;
                        } catch (e) { inferredPending = 0; }
                    }
                    // prefer the server-provided pending when > 0, otherwise use inferred; show the maximum either way
                    pending = Math.max(Number(pending) || 0, inferredPending);
                    let html = `<div><strong>Pedidos pendientes:</strong> ${pending}</div>`;
                    html += `<h3>Pedidos recientes</h3>`;
                    if (recent.length) {
                        html += '<ul>';
                        recent.forEach(o => {
                            const total = o.totalAmount || o.total_amount || o.total || '';
                            const status = o.status || o.state || '';
                            const created = o.createdAt || o.created_at || o.date || '';
                            const client = o.client && (o.client.name || o.client.email) ? (o.client.name || o.client.email) : '';
                            html += `<li>#${o.id} - ${status} - ${total}€ - ${client} - ${created}</li>`;
                        });
                        html += '</ul>';
                    } else html += '<div>No hay pedidos recientes.</div>';
                    el.innerHTML = html;
                } else {
                    el.textContent = 'No hay datos.';
                    const dbg = document.createElement('pre'); dbg.style.fontSize = '0.8em'; dbg.style.marginTop = '8px'; dbg.textContent = JSON.stringify(d, null, 2); el.appendChild(dbg);
                }
            } catch (err) {
                el.textContent = 'Error';
            }
        })();
    </script>
</body>

</html>