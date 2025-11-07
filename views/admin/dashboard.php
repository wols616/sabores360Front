<?php
// Protected admin dashboard - require admin role
require __DIR__ . '/../../includes/auth_check.php';
require_role('admin');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Dashboard | Sabores360</title>
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

        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.2);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--orange-primary);
            text-shadow: 1px 1px 2px rgba(255, 107, 53, 0.1);
        }

        .stats-icon {
            font-size: 3rem;
            opacity: 0.2;
            color: var(--orange-primary);
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
        }

        .table-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.1);
        }

        .table-modern thead {
            background: linear-gradient(45deg, var(--orange-primary), var(--orange-secondary));
            color: white;
        }

        .page-header {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.2);
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
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <?php $active = 'dashboard';
        require __DIR__ . '/_admin_nav.php'; ?>

        <div class="page-header text-center">
            <h1 class="mb-2">
                <i class="bi bi-speedometer2"></i> Dashboard Administrativo
            </h1>
            <p class="mb-0 opacity-75">Panel de control y estadísticas generales</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4" id="stats-cards">
            <div class="col-md-3 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center position-relative">
                        <div class="stats-icon position-absolute top-0 end-0 p-3">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <h5 class="card-title text-muted">Pedidos</h5>
                        <div class="stats-number" id="orders-count">-</div>
                        <small class="text-muted">Total pedidos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center position-relative">
                        <div class="stats-icon position-absolute top-0 end-0 p-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5 class="card-title text-muted">Usuarios</h5>
                        <div class="stats-number" id="users-count">-</div>
                        <small class="text-muted">Total usuarios</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center position-relative">
                        <div class="stats-icon position-absolute top-0 end-0 p-3">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5 class="card-title text-muted">Productos</h5>
                        <div class="stats-number" id="products-count">-</div>
                        <small class="text-muted">Total productos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center position-relative">
                        <div class="stats-icon position-absolute top-0 end-0 p-3">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <h5 class="card-title text-muted">Bajo Stock</h5>
                        <div class="stats-number text-warning" id="low-stock-count">-</div>
                        <small class="text-muted">Productos críticos</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card chart-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up text-orange"></i> Ventas del Día Actual
                        </h5>
                        <small class="text-muted" id="today-date"></small>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card chart-card">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge text-orange"></i> Ventas por Vendedor
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoriesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="row">
            <div class="col-12">
                <div class="card table-modern">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Pedidos Recientes
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="recent-orders">
                            <div class="text-center p-4">
                                <div class="spinner-border text-orange" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        let salesChart, categoriesChart;

        // Initialize charts
        function initCharts() {
            // Sales Today Chart (Line)
            const salesTodayCtx = document.getElementById('salesChart').getContext('2d');
            salesChart = new Chart(salesTodayCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Ventas del día (€)',
                        data: [],
                        borderColor: '#ff6b35',
                        backgroundColor: 'rgba(255, 107, 53, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ff6b35',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    const y = ctx.parsed ? ctx.parsed.y : ctx.raw;
                                    return 'Ventas: ' + Number(y || 0).toFixed(2) + ' €';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 107, 53, 0.1)'
                            },
                            ticks: {
                                color: '#666',
                                callback: function (value) {
                                    return value + ' €';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#666'
                            }
                        }
                    }
                }
            });

            // Sales by Seller Chart (Bar)
            const salesBySellerCtx = document.getElementById('categoriesChart').getContext('2d');
            categoriesChart = new Chart(salesBySellerCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Ventas por vendedor (€)',
                        data: [],
                        backgroundColor: [
                            '#ff6b35',
                            '#ff8c42',
                            '#ffad73',
                            '#ffd1a9',
                            '#e55a2b',
                            '#ff9966'
                        ],
                        borderWidth: 0,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    const y = ctx.parsed ? ctx.parsed.y : ctx.raw;
                                    return 'Ventas: ' + Number(y || 0).toFixed(2) + ' €';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 107, 53, 0.1)'
                            },
                            ticks: {
                                color: '#666',
                                callback: function (value) {
                                    return value + ' €';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#666',
                                maxRotation: 45
                            }
                        }
                    }
                }
            });
        }

        // Helper functions from stats.php
        function resolveList(resp, key) {
            if (!resp) return null;
            if (Array.isArray(resp[key])) return resp[key];
            if (resp.data && Array.isArray(resp.data[key])) return resp.data[key];
            if (resp.data && resp.data.data && Array.isArray(resp.data.data[key])) return resp.data.data[key];
            if (resp.result && Array.isArray(resp.result[key])) return resp.result[key];
            return null;
        }

        function num(v) {
            const n = Number(v);
            return Number.isFinite(n) ? n : 0;
        }

        // Load dashboard data
        async function loadDashboard() {
            try {
                // Get today's date for sales query
                const today = new Date().toISOString().slice(0, 10);
                document.getElementById('today-date').textContent = new Date().toLocaleDateString('es-ES', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                // Load basic dashboard stats
                const response = await SABORES360.API.get('admin/dashboard');
                if (response.success) {
                    const data = response.data || response;

                    // Update stats cards
                    document.getElementById('orders-count').textContent = data.orders_count || data.ordersCount || 0;
                    document.getElementById('users-count').textContent = data.users_count || data.usersCount || 0;
                    document.getElementById('products-count').textContent = data.products_count || data.productsCount || 0;
                    document.getElementById('low-stock-count').textContent = data.low_stock_count || data.lowStockCount || 0;

                    // Render recent orders (ensure most recent first by ID desc)
                    let recentOrders = data.recent_orders || data.recentOrders || [];
                    if (Array.isArray(recentOrders)) {
                        recentOrders.sort((a, b) => (parseInt(b.id, 10) || 0) - (parseInt(a.id, 10) || 0));
                    }
                    renderRecentOrders(recentOrders);
                }

                // Load today's sales data (from stats endpoint)
                const salesParams = { date_from: today, date_to: today };
                const salesResponse = await SABORES360.API.get('admin/stats/sales-by-day?' + new URLSearchParams(salesParams));

                if (salesResponse.success) {
                    const salesList = resolveList(salesResponse, 'sales_by_day') ||
                        resolveList(salesResponse, 'salesByDay') ||
                        resolveList(salesResponse, 'sales_byday') || [];

                    if (salesList && salesList.length) {
                        const labels = salesList.map(x => x.fecha || x.date || '');
                        const values = salesList.map(x => num(x.totalVentas != null ? x.totalVentas :
                            (x.total != null ? x.total :
                                (x.total_ventas != null ? x.total_ventas : 0))));

                        salesChart.data.labels = labels;
                        salesChart.data.datasets[0].data = values;
                        salesChart.update();
                    } else {
                        // No sales data for today
                        salesChart.data.labels = [today];
                        salesChart.data.datasets[0].data = [0];
                        salesChart.update();
                    }
                }

                // Load sales by seller data
                const sellerResponse = await SABORES360.API.get('admin/stats/sales-by-seller?' + new URLSearchParams(salesParams));

                if (sellerResponse.success) {
                    const sellerData = sellerResponse.sales_by_seller ||
                        (sellerResponse.data && sellerResponse.data.sales_by_seller) || [];

                    if (sellerData && sellerData.length) {
                        const labels = sellerData.map(x => x.vendedorNombre || x.vendedorNombre || ('Vendedor ' + x.vendedorId));
                        const values = sellerData.map(x => num(x.totalVentas || x.total || 0));

                        categoriesChart.data.labels = labels;
                        categoriesChart.data.datasets[0].data = values;
                        categoriesChart.update();
                    } else {
                        // No seller data
                        categoriesChart.data.labels = ['Sin datos'];
                        categoriesChart.data.datasets[0].data = [0];
                        categoriesChart.update();
                    }
                }

            } catch (err) {
                console.error('Dashboard error:', err);
                // Show error in recent orders section
                document.getElementById('recent-orders').innerHTML =
                    '<div class="alert alert-warning m-3"><i class="bi bi-exclamation-triangle"></i> Error al cargar los datos del dashboard</div>';
            }
        }

        // Render recent orders table
        function renderRecentOrders(orders) {
            const container = document.getElementById('recent-orders');

            if (!orders || orders.length === 0) {
                container.innerHTML = '<div class="text-center p-4 text-muted">No hay pedidos recientes</div>';
                return;
            }

            let html = `
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Pago</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            orders.forEach(order => {
                const client = order.client ?
                    (order.client.name ? `${order.client.name}` : order.client.email) :
                    'Cliente';
                const total = order.totalAmount || order.total_amount || order.total || '0';
                const status = order.status || order.state || 'Pendiente';
                const payment = order.paymentMethod || order.payment_method || 'N/A';
                const date = order.createdAt || order.created_at || order.date || '';

                // Status badge color
                let statusClass = 'bg-secondary';
                switch (status.toLowerCase()) {
                    case 'pendiente': statusClass = 'bg-warning'; break;
                    case 'confirmado': statusClass = 'bg-info'; break;
                    case 'en preparación': statusClass = 'bg-primary'; break;
                    case 'en camino': statusClass = 'bg-success'; break;
                    case 'entregado': statusClass = 'bg-success'; break;
                    case 'cancelado': statusClass = 'bg-danger'; break;
                }

                html += `
                    <tr>
                        <td><strong>#${order.id}</strong></td>
                        <td>
                            <div>${client}</div>
                            ${order.client && order.client.email ? `<small class="text-muted">${order.client.email}</small>` : ''}
                        </td>
                        <td><strong>$${total}</strong></td>
                        <td><span class="badge ${statusClass}">${status}</span></td>
                        <td>${payment}</td>
                        <td><small>${date}</small></td>
                    </tr>
                `;
            });

            html += '</tbody></table></div>';
            container.innerHTML = html;
        }

        // View order function (placeholder)
        function viewOrder(orderId) {
            // TODO: Navigate to order details or open modal
            console.log('View order:', orderId);
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function () {
            initCharts();
            loadDashboard();
        });
    </script>
</body>

</html>