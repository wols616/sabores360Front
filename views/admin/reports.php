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
</head>

<body>
    <header>
        <h1>Reportes</h1>
        <?php $active = 'reports';
        require __DIR__ . '/_admin_nav.php'; ?>
    </header>

    <main>
        <section>
            <h2>Reportes de ventas</h2>
            <form id="reports-form">
                <label>Desde: <input type="date" name="date_from"></label>
                <label>Hasta: <input type="date" name="date_to"></label>
                <button type="submit">Generar</button>
            </form>
            <div id="reports-result">Sin datos</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (function () {
            const form = document.getElementById('reports-form');
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(form);
                const params = new URLSearchParams();
                if (fd.get('date_from')) params.append('date_from', fd.get('date_from'));
                if (fd.get('date_to')) params.append('date_to', fd.get('date_to'));
                const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
                try {
                    const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('admin/reports?' + params.toString()) : (async () => { const res = await fetch(base + 'admin/reports?' + params.toString(), { credentials: 'include' }); return res.json(); })());
                    if (d && d.success) {
                        document.getElementById('reports-result').textContent = JSON.stringify(d.data || d, null, 2);
                    } else document.getElementById('reports-result').textContent = 'No hay resultados.';
                } catch (err) { document.getElementById('reports-result').textContent = 'Error al generar reportes.'; }
            });
        })();
    </script>
</body>

</html>