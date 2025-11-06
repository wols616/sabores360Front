<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Reportes</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
    <style>
        .controls {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 12px
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        .card {
            padding: 12px;
            border: 1px solid #e6e6e6;
            border-radius: 6px;
            background: #fff
        }

        canvas {
            width: 100% !important;
            height: 240px !important
        }
    </style>
</head>

<body>
    <header>
        <h1>Reportes</h1>
        <?php $active = 'reportes';
        require __DIR__ . '/_admin_nav.php'; ?>
    </header>

    <main>
        <section>
            <div class="controls">
                <label>Desde <input type="date" id="date_from"></label>
                <label>Hasta <input type="date" id="date_to"></label>
                <button id="loadBtn">Cargar</button>
                <button id="exportPdfBtn">Exportar PDF</button>
                <div id="status" style="margin-left:12px;color:#666"></div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <h4>Ventas por día</h4>
                    <canvas id="r-chart-sales-by-day"></canvas>
                </div>
                <div class="card">
                    <h4>Ventas por vendedor</h4>
                    <canvas id="r-chart-sales-by-seller"></canvas>
                </div>
                <div class="card">
                    <h4>Pedidos por estado</h4>
                    <canvas id="r-chart-orders-by-status"></canvas>
                </div>
                <div class="card">
                    <h4>Top productos</h4>
                    <canvas id="r-chart-top-products"></canvas>
                </div>
            </div>

            <div style="margin-top:16px" class="card" id="text-reports">
                <h3>Resumen y métricas (texto)</h3>
                <div id="revenue-summary-txt">Cargando resumen...</div>
                <div id="rates-txt" style="margin-top:8px">Cargando tasas...</div>
                <div id="revenue-segment-txt" style="margin-top:8px">Cargando desglose...</div>
                <div id="top-clients-txt" style="margin-top:8px">Cargando top clientes...</div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            const statusEl = document.getElementById('status');
            const loadBtn = document.getElementById('loadBtn');
            const exportBtn = document.getElementById('exportPdfBtn');

            function qs(params) { const esc = encodeURIComponent; return Object.keys(params).filter(k => params[k]).map(k => esc(k) + '=' + esc(params[k])).join('&'); }
            async function fetchApi(endpoint, params) {
                if (window.SABORES360 && SABORES360.API) return await SABORES360.API.get(endpoint + (params ? ('?' + qs(params)) : ''));
                const q = params ? ('?' + qs(params)) : '';
                const r = await fetch(base + endpoint + q, { credentials: 'include' });
                const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, raw: t }; }
            }

            function num(v) { const n = Number(v); return Number.isFinite(n) ? n : 0; }

            function makeLineChart(ctx, labels, data, label, color) { return new Chart(ctx, { type: 'line', data: { labels, datasets: [{ label, data, borderColor: color || 'rgba(75,192,192,1)', backgroundColor: 'rgba(0,0,0,0)', fill: false, pointRadius: 3, pointHoverRadius: 6 }] }, options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { tooltip: { callbacks: { title: (items) => items && items.length ? items[0].label : '', label: (ctx) => { const y = (ctx.parsed && ctx.parsed.y !== undefined) ? ctx.parsed.y : ctx.raw; return (ctx.dataset.label || '') + ': ' + (Number.isInteger(Number(y)) ? Number(y) : Number(y).toFixed(2)); } } } } } }); }
            function makeBarChart(ctx, labels, data, label) { return new Chart(ctx, { type: 'bar', data: { labels, datasets: [{ label, data, backgroundColor: labels.map((_, i) => ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f'][i % 5]) }] }, options: { responsive: true, maintainAspectRatio: false } }); }
            function makePieChart(ctx, labels, data) { return new Chart(ctx, { type: 'pie', data: { labels, datasets: [{ data, backgroundColor: labels.map((_, i) => ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f'][i % 5]) }] }, options: { responsive: true, maintainAspectRatio: false } }); }

            const charts = {};

            async function loadReports() {
                statusEl.textContent = 'Cargando...';
                const date_from = document.getElementById('date_from').value;
                const date_to = document.getElementById('date_to').value;
                const params = { date_from, date_to };

                try {
                    // sales by day
                    const sbd = await fetchApi('admin/stats/sales-by-day', params);
                    const list = (sbd && (sbd.data && sbd.data.sales_by_day)) ? sbd.data.sales_by_day : (sbd && sbd.sales_by_day) ? sbd.sales_by_day : [];
                    if (list && list.length) { const labels = list.map(x => x.fecha || x.date || ''); const vals = list.map(x => num(x.totalVentas != null ? x.totalVentas : (x.total != null ? x.total : 0))); if (charts.sbd) charts.sbd.destroy(); charts.sbd = makeLineChart(document.getElementById('r-chart-sales-by-day').getContext('2d'), labels, vals, 'Ventas (€)'); }

                    // sales by seller
                    const sbs = await fetchApi('admin/stats/sales-by-seller', params);
                    const sbsList = (sbs && (sbs.data && sbs.data.sales_by_seller)) ? sbs.data.sales_by_seller : (sbs && sbs.sales_by_seller) ? sbs.sales_by_seller : [];
                    if (sbsList && sbsList.length) { const labels = sbsList.map(x => x.vendedorNombre || x.label || ('V' + x.vendedorId)); const vals = sbsList.map(x => num(x.totalVentas != null ? x.totalVentas : (x.total != null ? x.total : 0))); if (charts.sbs) charts.sbs.destroy(); charts.sbs = makeBarChart(document.getElementById('r-chart-sales-by-seller').getContext('2d'), labels, vals, 'Ventas por vendedor'); }

                    // orders by status
                    const obs = await fetchApi('admin/stats/orders-by-status', params);
                    const obsList = (obs && (obs.data && obs.data.orders_by_status)) ? obs.data.orders_by_status : (obs && obs.orders_by_status) ? obs.orders_by_status : [];
                    if (obsList && obsList.length) { const labels = obsList.map(x => x.status); const vals = obsList.map(x => num(x.count)); if (charts.obs) charts.obs.destroy(); charts.obs = makePieChart(document.getElementById('r-chart-orders-by-status').getContext('2d'), labels, vals); }

                    // top products
                    const tp = await fetchApi('admin/stats/top-products', params);
                    const tpList = (tp && (tp.data && tp.data.top_products)) ? tp.data.top_products : (tp && tp.top_products) ? tp.top_products : [];
                    if (tpList && tpList.length) { const labels = tpList.map(x => x.productoNombre || x.label || ('P' + x.productoId)); const vals = tpList.map(x => num(x.cantidadVendida != null ? x.cantidadVendida : (x.qty != null ? x.qty : 0))); if (charts.tp) charts.tp.destroy(); charts.tp = makeBarChart(document.getElementById('r-chart-top-products').getContext('2d'), labels, vals, 'Top productos'); }

                    // textual reports
                    const rev = await fetchApi('admin/stats/revenue-summary', params);
                    const revEl = document.getElementById('revenue-summary-txt');
                    if (rev) { const d = rev.data || rev; revEl.textContent = 'Ingresos actuales: ' + (Number(d.current_revenue || d.currentRevenue || 0).toFixed(2)) + ' €'; }

                    const rates = await fetchApi('admin/stats/rates', params);
                    const ratesEl = document.getElementById('rates-txt');
                    if (rates) { const d = rates.data || rates; ratesEl.innerHTML = `<strong>Confirmación:</strong> ${((d.confirmation_rate && d.confirmation_rate.value != null) ? d.confirmation_rate.value : (d.confirmation_rate || 0))}%<br/><strong>Cierre:</strong> ${((d.closure_rate && d.closure_rate.value != null) ? d.closure_rate.value : (d.closure_rate || 0))}%<br/><strong>Cancelación:</strong> ${((d.cancellation_rate && d.cancellation_rate.value != null) ? d.cancellation_rate.value : (d.cancellation_rate || 0))}%`; }

                    const rbs = await fetchApi('admin/stats/revenue-by-segment', params);
                    const rbsEl = document.getElementById('revenue-segment-txt');
                    if (rbs) { const d = rbs.data || rbs; let html = ''; if (d.by_seller && d.by_seller.length) html += '<h4>Por vendedor</h4><ul>' + d.by_seller.map(x => `<li>${x.vendedorNombre || x.label}: ${Number(x.totalVentas || x.total || 0).toFixed(2)}€</li>`).join('') + '</ul>'; if (d.by_channel && d.by_channel.length) html += '<h4>Por canal</h4><ul>' + d.by_channel.map(x => `<li>${x.label}: ${x.count || 0}</li>`).join('') + '</ul>'; if (d.by_category && d.by_category.length) html += '<h4>Por categoría</h4><ul>' + d.by_category.map(x => `<li>${x.label}: ${x.count || 0}</li>`).join('') + '</ul>'; rbsEl.innerHTML = html; }

                    const tc = await fetchApi('admin/stats/top-clients', params);
                    const tcEl = document.getElementById('top-clients-txt');
                    if (tc) { const list = tc.top_clients || (tc.data && tc.data.top_clients) || []; tcEl.innerHTML = '<ol>' + list.map(x => `<li>${x.label || x.name || ''}: ${x.count || 0}</li>`).join('') + '</ol>'; }

                    statusEl.textContent = 'Cargado.';
                } catch (err) { statusEl.textContent = 'Error al cargar datos.'; console.error(err); }
            }

            // Export sections (charts + textual report) to PDF
            async function exportPdf() {
                exportBtn.disabled = true; exportBtn.textContent = 'Generando PDF...';
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('p', 'mm', 'a4');
                    const margin = 10; const pageWidth = doc.internal.pageSize.getWidth(); const pageHeight = doc.internal.pageSize.getHeight();

                    // elements to capture: header area (controls) + each chart card + textual report
                    const toCapture = [];
                    // capture the header + controls area
                    toCapture.push(document.querySelector('header'));
                    // capture chart cards
                    toCapture.push(document.getElementById('r-chart-sales-by-day').parentNode);
                    toCapture.push(document.getElementById('r-chart-sales-by-seller').parentNode);
                    toCapture.push(document.getElementById('r-chart-orders-by-status').parentNode);
                    toCapture.push(document.getElementById('r-chart-top-products').parentNode);
                    // textual
                    toCapture.push(document.getElementById('text-reports'));

                    let first = true;
                    for (const el of toCapture) {
                        if (!el) continue;
                        // ensure chart canvases are visible/updated
                        const canvas = el.querySelector('canvas');
                        if (canvas) await new Promise(r => setTimeout(r, 200));
                        const canvasImg = await html2canvas(el, { scale: 2 });
                        const imgData = canvasImg.toDataURL('image/jpeg', 0.95);
                        const imgProps = doc.getImageProperties(imgData);
                        const pdfWidth = pageWidth - margin * 2;
                        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                        if (!first) doc.addPage();
                        doc.addImage(imgData, 'JPEG', margin, margin, pdfWidth, pdfHeight);
                        first = false;
                    }

                    doc.save('reportes.pdf');
                } catch (e) { console.error(e); alert('Error generando PDF: ' + e.message); }
                finally { exportBtn.disabled = false; exportBtn.textContent = 'Exportar PDF'; }
            }

            loadBtn.addEventListener('click', loadReports);
            exportBtn.addEventListener('click', exportPdf);

            // preset dates and auto load
            (function preset() { const to = new Date(); const from = new Date(); from.setDate(to.getDate() - 29); function f(d) { return d.toISOString().slice(0, 10); } document.getElementById('date_from').value = f(from); document.getElementById('date_to').value = f(to); })();
            loadReports();
        })();
    </script>
</body>

</html>