<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Estadísticas | Sabores360</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
    <style>
        :root {
            --orange-primary: #ff6b35;
            --orange-secondary: #ff8c42;
            --orange-light: #ffad73;
            --orange-dark: #e55a2b;
            --orange-bg: #fff4f0;
        }

        body {
            background: linear-gradient(135deg, var(--orange-bg) 0%, #feeee7 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.2);
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
        }

        .controls-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .btn-orange {
            background: linear-gradient(45deg, var(--orange-primary), var(--orange-secondary));
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            background: linear-gradient(45deg, var(--orange-dark), var(--orange-primary));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .form-control:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        canvas {
            width: 100% !important;
            height: 320px !important
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <?php $active = 'stats';
        require __DIR__ . '/_admin_nav.php'; ?>

        <div class="page-header text-center">
            <h1 class="mb-2">
                <i class="bi bi-bar-chart"></i> Estadísticas y Análisis
            </h1>
            <p class="mb-0 opacity-75">Dashboard completo de métricas de negocio</p>
        </div>

        <div class="controls-card">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="bi bi-calendar"></i> Fecha desde
                    </label>
                    <input type="date" class="form-control" id="date_from">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="bi bi-calendar"></i> Fecha hasta
                    </label>
                    <input type="date" class="form-control" id="date_to">
                </div>
                <div class="col-md-3">
                    <button id="loadBtn" class="btn btn-orange btn-lg">
                        <i class="bi bi-arrow-clockwise"></i> Cargar Datos
                    </button>
                </div>
                <div class="col-md-3">
                    <div id="status" class="text-muted small">
                        <i class="bi bi-info-circle"></i> Listo para cargar
                    </div>
                </div>
            </div>
        </div>

        <div class="card chart-card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">
                    <i class="bi bi-cash-stack text-orange"></i> Resumen de Ingresos
                </h5>
            </div>
            <div class="card-body">
                <div id="revenue-summary">
                    <div class="text-center py-3">
                        <div class="spinner-border text-orange" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando resumen...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card chart-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up text-orange"></i> Ventas por día
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-sales-by-day"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card chart-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-person-plus text-orange"></i> Usuarios nuevos por día
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-users-growth"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card chart-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-shop text-orange"></i> Ventas por vendedor
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-sales-by-seller"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card chart-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-star text-orange"></i> Top productos
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-top-products"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card chart-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart text-orange"></i> Pedidos por estado
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-orders-by-status"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card chart-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-event text-orange"></i> Cantidad de pedidos por días
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-orders-period"></canvas>
                        <div id="orders-period-metrics" class="mt-3 p-2 bg-light rounded small"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card chart-card mt-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">
                    <i class="bi bi-percent text-orange"></i> Tasas de Conversión
                </h5>
            </div>
            <div class="card-body">
                <canvas id="chart-rates" style="height:220px"></canvas>
            </div>
        </div>

        <div class="card chart-card mt-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">
                    <i class="bi bi-currency-dollar text-orange"></i> Desglose de Ingresos
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6>Por vendedor</h6>
                        <canvas id="chart-revenue-by-seller" style="height:220px"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h6>Por canal</h6>
                        <canvas id="chart-revenue-by-channel" style="height:220px"></canvas>
                    </div>
                </div>
                <div class="mt-4">
                    <h6>Por categoría</h6>
                    <canvas id="chart-revenue-by-category" style="height:220px"></canvas>
                </div>
            </div>
        </div>

        <div class="card chart-card mt-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">
                    <i class="bi bi-trophy text-orange"></i> Top Clientes
                </h5>
            </div>
            <div class="card-body">
                <canvas id="chart-top-clients" style="height:240px"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const statusEl = document.getElementById('status');
            const loadBtn = document.getElementById('loadBtn');

            function qs(params) {
                const esc = encodeURIComponent;
                return Object.keys(params).filter(k => params[k] !== undefined && params[k] !== null && String(params[k]).length > 0).map(k => esc(k) + '=' + esc(params[k])).join('&');
            }

            async function fetchApi(endpoint, params) {
                try {
                    const q = params ? ('?' + qs(params)) : '';
                    if (window.SABORES360 && SABORES360.API) {
                        // some API wrapper may not accept query in get; try passing full path
                        return await SABORES360.API.get(endpoint + (q || ''));
                    }
                    const url = base + endpoint + (q || '');
                    const r = await fetch(url, { credentials: 'include' });
                    const t = await r.text();
                    try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t }; }
                } catch (err) { return { success: false, error: String(err) }; }
            }

            // Flexible helpers for resolving nested shapes and numeric normalization
            function resolveList(resp, key) {
                if (!resp) return null;
                if (Array.isArray(resp[key])) return resp[key];
                if (resp.data && Array.isArray(resp.data[key])) return resp.data[key];
                if (resp.data && resp.data.data && Array.isArray(resp.data.data[key])) return resp.data.data[key];
                if (resp.result && Array.isArray(resp.result[key])) return resp.result[key];
                return null;
            }

            function num(v) { const n = Number(v); return Number.isFinite(n) ? n : 0; }

            function renderRevenueSummary(d) {
                const el = document.getElementById('revenue-summary');
                if (!d || !d.success) { el.textContent = 'No hay datos.'; return; }
                const data = d.data || d;
                const cur = data.current_revenue || data.currentRevenue || 0;
                const prev = data.previous_revenue || data.previousRevenue || 0;
                const pct = data.percent_change || data.percentChange || 0;
                const yoy = data.yoy_revenue || data.yoyRevenue || 0;
                const y_pct = data.yoy_percent_change || data.yoyPercentChange || 0;
                el.innerHTML = `<strong>Ingresos actuales:</strong> ${Number(cur).toFixed(2)}$`;
            }

            function makeLineChart(ctx, labels, data, label, color) {
                return new Chart(ctx, {
                    type: 'line',
                    data: { labels, datasets: [{ label, data, borderColor: color || 'rgba(75,192,192,1)', backgroundColor: 'rgba(0,0,0,0)', fill: false, pointRadius: 4, pointHoverRadius: 7 }] },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: function (items) {
                                        // items is an array; take first label (x axis)
                                        return items && items.length ? items[0].label : '';
                                    },
                                    label: function (ctx) {
                                        const dsLabel = ctx.dataset && ctx.dataset.label ? String(ctx.dataset.label) : '';
                                        const y = (ctx.parsed && ctx.parsed.y !== undefined) ? ctx.parsed.y : (ctx.raw !== undefined ? ctx.raw : ctx.parsed);
                                        if (dsLabel.toLowerCase().includes('venta') || dsLabel.includes('€')) {
                                            return dsLabel + ': ' + Number(y || 0).toFixed(2) + ' €';
                                        }
                                        // integer-like values
                                        if (Number.isInteger(Number(y))) return dsLabel + ': ' + Number(y);
                                        return dsLabel + ': ' + Number(y).toFixed(2);
                                    }
                                }
                            }
                        }
                    }
                });
            }
            function makeBarChart(ctx, labels, data, label, color) {
                return new Chart(ctx, { type: 'bar', data: { labels, datasets: [{ label, data, backgroundColor: color || 'rgba(54,162,235,0.6)' }] }, options: { responsive: true, maintainAspectRatio: false } });
            }
            function makePieChart(ctx, labels, data) { return new Chart(ctx, { type: 'pie', data: { labels, datasets: [{ data, backgroundColor: labels.map((_, i) => ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949'][i % 6]) }] }, options: { responsive: true, maintainAspectRatio: false } }); }

            // keep references so charts can be destroyed when reloading
            const charts = {};

            async function loadAll() {
                statusEl.textContent = 'Cargando...';
                const date_from = document.getElementById('date_from').value;
                const date_to = document.getElementById('date_to').value;
                const params = { date_from, date_to };

                try {
                    // revenue summary
                    const rev = await fetchApi('admin/stats/revenue-summary', params);
                    renderRevenueSummary(rev);

                    // sales by day
                    const sbd = await fetchApi('admin/stats/sales-by-day', params);


                    const sbdList = resolveList(sbd, 'sales_by_day') || resolveList(sbd, 'salesByDay') || resolveList(sbd, 'sales_byday') || [];
                    if (sbdList && sbdList.length) {
                        const labels = sbdList.map(x => x.fecha || x.date || '');
                        const values = sbdList.map(x => num(x.totalVentas != null ? x.totalVentas : (x.total != null ? x.total : (x.total_ventas != null ? x.total_ventas : 0))));
                        if (charts.salesByDay) charts.salesByDay.destroy();
                        charts.salesByDay = makeLineChart(document.getElementById('chart-sales-by-day').getContext('2d'), labels, values, 'Ventas (€)', 'rgba(75,192,192,1)');
                    } else {
                        console.debug('sales-by-day: no data found for expected keys', sbd);
                        if (charts.salesByDay) { charts.salesByDay.destroy(); delete charts.salesByDay; }
                    }

                    // users growth
                    const ug = await fetchApi('admin/stats/users-growth', params);
                    if (ug && (ug.users_growth || (ug.data && ug.data.users_growth))) {
                        const list = ug.users_growth || (ug.data && ug.data.users_growth) || [];
                        const labels = list.map(x => x.fecha);
                        const vals = list.map(x => Number(x.cantidadUsuarios || x.count || 0));
                        if (charts.usersGrowth) charts.usersGrowth.destroy();
                        charts.usersGrowth = makeLineChart(document.getElementById('chart-users-growth').getContext('2d'), labels, vals, 'Nuevos usuarios');
                    }

                    // sales by seller
                    const sbs = await fetchApi('admin/stats/sales-by-seller', params);
                    if (sbs && (sbs.sales_by_seller || (sbs.data && sbs.data.sales_by_seller))) {
                        const list = sbs.sales_by_seller || (sbs.data && sbs.data.sales_by_seller) || [];
                        const labels = list.map(x => x.vendedorNombre || x.vendedorNombre || ('V' + x.vendedorId));
                        const vals = list.map(x => Number(x.totalVentas || x.total || 0));
                        if (charts.salesBySeller) charts.salesBySeller.destroy();
                        charts.salesBySeller = makeBarChart(document.getElementById('chart-sales-by-seller').getContext('2d'), labels, vals, 'Ventas por vendedor');
                    }

                    // top products
                    const tp = await fetchApi('admin/stats/top-products', params);
                    if (tp && (tp.top_products || (tp.data && tp.data.top_products))) {
                        const list = tp.top_products || (tp.data && tp.data.top_products) || [];
                        const labels = list.map(x => x.productoNombre || x.productoNombre || ('P' + x.productoId));
                        const vals = list.map(x => Number(x.cantidadVendida || x.qty || 0));
                        if (charts.topProducts) charts.topProducts.destroy();
                        charts.topProducts = makeBarChart(document.getElementById('chart-top-products').getContext('2d'), labels, vals, 'Cantidad vendida');
                    }

                    // orders by status (pie)
                    const obs = await fetchApi('admin/stats/orders-by-status', params);
                    if (obs && (obs.orders_by_status || (obs.data && obs.data.orders_by_status))) {
                        const list = obs.orders_by_status || (obs.data && obs.data.orders_by_status) || [];
                        const labels = list.map(x => x.status);
                        const vals = list.map(x => Number(x.count || 0));
                        if (charts.ordersByStatus) charts.ordersByStatus.destroy();
                        charts.ordersByStatus = makePieChart(document.getElementById('chart-orders-by-status').getContext('2d'), labels, vals);
                    }

                    // orders period (series + comparison)
                    const op = await fetchApi('admin/stats/orders-period', params);
                    // try to resolve series in flexible ways
                    const opSeries = (op && Array.isArray(op.series)) ? op.series : (op && op.data && Array.isArray(op.data.series) ? op.data.series : (op && op.data && op.data.data && Array.isArray(op.data.data.series) ? op.data.data.series : null));
                    if (opSeries && opSeries.length) {
                        const labels = opSeries.map(p => p.label || p.fecha || '');
                        const vals = opSeries.map(p => num(p.count != null ? p.count : (p.cantidad || p.cantidadPedidos != null ? p.cantidadPedidos : 0)));
                        if (charts.ordersPeriod) charts.ordersPeriod.destroy();
                        charts.ordersPeriod = makeLineChart(document.getElementById('chart-orders-period').getContext('2d'), labels, vals, 'Pedidos');

                        // display metrics in dedicated element
                        const metricsEl = document.getElementById('orders-period-metrics');
                        const cur = (op && op.current_total != null) ? op.current_total : (op && op.data && op.data.current_total != null ? op.data.current_total : (op && op.data && op.data.data && op.data.data.current_total != null ? op.data.data.data.current_total : null));
                        const prev = (op && op.previous_total != null) ? op.previous_total : (op && op.data && op.data.previous_total != null ? op.data.previous_total : (op && op.data && op.data.data && op.data.data.previous_total != null ? op.data.data.data.previous_total : null));
                        const pct = (op && op.percent_change != null) ? op.percent_change : (op && op.data && op.data.percent_change != null ? op.data.percent_change : (op && op.data && op.data.data && op.data.data.percent_change != null ? op.data.data.data.percent_change : null));
                        let html = '<strong>Periodo:</strong> ' + (op.granularity || (op.data && op.data.granularity) || '') + '<br/>';
                        if (cur != null) html += `Actual: <strong>${num(cur)}</strong> `;
                        if (prev != null) html += `Anterior: <strong>${num(prev)}</strong> `;
                        if (pct != null) html += `Cambio: <strong>${Number(pct).toFixed(2)}%</strong>`;
                        metricsEl.innerHTML = html;
                    } else {
                        console.debug('orders-period: no series found', op);
                        if (charts.ordersPeriod) { charts.ordersPeriod.destroy(); delete charts.ordersPeriod; }
                        const metricsEl = document.getElementById('orders-period-metrics');
                        if (metricsEl) metricsEl.innerHTML = '<em>No hay datos para el periodo seleccionado.</em>';
                    }

                    // rates -> pie chart (Confirmación, Cierre, Cancelación)
                    const rates = await fetchApi('admin/stats/rates', params);
                    if (rates) {
                        const d = rates.data || rates;
                        function getRateValue(r) { if (r == null) return 0; if (typeof r === 'number') return Number(r); if (r.value != null) return Number(r.value); if (r.rate != null) return Number(r.rate); return 0; }
                        const labels = ['Confirmación', 'Cierre', 'Cancelación'];
                        const vals = [getRateValue(d.confirmation_rate), getRateValue(d.closure_rate), getRateValue(d.cancellation_rate)];
                        if (charts.rates) charts.rates.destroy();
                        charts.rates = makePieChart(document.getElementById('chart-rates').getContext('2d'), labels, vals);
                    }

                    // revenue by segment -> charts for seller/channel/category
                    const rbs = await fetchApi('admin/stats/revenue-by-segment', params);
                    if (rbs) {
                        const d = rbs.data || rbs;
                        // by seller (amounts)
                        if (d.by_seller && d.by_seller.length) {
                            const labels = d.by_seller.map(x => x.vendedorNombre || x.label || ('V' + x.vendedorId));
                            const vals = d.by_seller.map(x => num(x.totalVentas != null ? x.totalVentas : (x.total != null ? x.total : (x.amount != null ? x.amount : 0))));
                            if (charts.revenueBySeller) charts.revenueBySeller.destroy();
                            charts.revenueBySeller = makeBarChart(document.getElementById('chart-revenue-by-seller').getContext('2d'), labels, vals, 'Ingresos (€)');
                        } else {
                            if (charts.revenueBySeller) { charts.revenueBySeller.destroy(); delete charts.revenueBySeller; }
                        }

                        // by channel (counts or totals)
                        if (d.by_channel && d.by_channel.length) {
                            const labels = d.by_channel.map(x => x.label || x.channel || '');
                            const vals = d.by_channel.map(x => num(x.count != null ? x.count : (x.total != null ? x.total : (x.amount != null ? x.amount : 0))));
                            if (charts.revenueByChannel) charts.revenueByChannel.destroy();
                            charts.revenueByChannel = makeBarChart(document.getElementById('chart-revenue-by-channel').getContext('2d'), labels, vals, 'Por canal');
                        } else {
                            if (charts.revenueByChannel) { charts.revenueByChannel.destroy(); delete charts.revenueByChannel; }
                        }

                        // by category (counts or totals)
                        if (d.by_category && d.by_category.length) {
                            const labels = d.by_category.map(x => x.label || x.categoria || '');
                            const vals = d.by_category.map(x => num(x.count != null ? x.count : (x.total != null ? x.total : (x.amount != null ? x.amount : 0))));
                            if (charts.revenueByCategory) charts.revenueByCategory.destroy();
                            charts.revenueByCategory = makeBarChart(document.getElementById('chart-revenue-by-category').getContext('2d'), labels, vals, 'Por categoría');
                        } else {
                            if (charts.revenueByCategory) { charts.revenueByCategory.destroy(); delete charts.revenueByCategory; }
                        }
                    }

                    // top clients -> bar chart
                    const tc = await fetchApi('admin/stats/top-clients', params);
                    if (tc) {
                        const list = tc.top_clients || (tc.data && tc.data.top_clients) || [];
                        if (list && list.length) {
                            const labels = list.map(x => x.label || x.name || '');
                            const vals = list.map(x => num(x.count != null ? x.count : (x.total != null ? x.total : (x.orders != null ? x.orders : 0))));
                            if (charts.topClients) charts.topClients.destroy();
                            charts.topClients = makeBarChart(document.getElementById('chart-top-clients').getContext('2d'), labels, vals, 'Top clientes');
                        } else {
                            if (charts.topClients) { charts.topClients.destroy(); delete charts.topClients; }
                        }
                    }

                    statusEl.textContent = 'Cargado.';
                } catch (err) {
                    statusEl.textContent = 'Error al cargar datos.';
                    console.error(err);
                }
            }

            loadBtn.addEventListener('click', loadAll);

            // quick preset: last 30 days
            (function presetDates() {
                const to = new Date();
                const from = new Date(); from.setDate(to.getDate() - 29);
                function fmt(d) { return d.toISOString().slice(0, 10); }
                document.getElementById('date_from').value = fmt(from);
                document.getElementById('date_to').value = fmt(to);
            })();

            // auto load once
            loadAll();
        })();
    </script>
</body>

</html>