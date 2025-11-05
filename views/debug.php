<?php
// Debug page to inspect auth state from the browser and test API calls.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Debug - Sabores360</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <main class="container">
        <h1>Debug de autenticación</h1>
        <section>
            <h2>Client state</h2>
            <div id="state">
                <pre id="cookies">Cookies: </pre>
                <pre id="local">LocalStorage auth_token: </pre>
            </div>
        </section>

        <section>
            <h2>API Checks</h2>
            <div>
                <button id="btn-me">GET /api/auth/me</button>
                <pre id="res-me">(no results)</pre>
            </div>
            <div>
                <button id="btn-products">GET /api/client/products</button>
                <pre id="res-products">(no results)</pre>
            </div>
        </section>

        <section>
            <h2>Instructions</h2>
            <ol>
                <li>Asegúrate de estar logueado desde la página de login (usa el flujo normal).</li>
                <li>En esta página haz click en los botones y copia el contenido que aparezca en los cuadros "res-me" y
                    "res-products" y pégalo aquí.</li>
                <li>Abre DevTools &rarr; Network &rarr; selecciona la petición y pega aquí la sección "Request Headers"
                    y el cuerpo de la respuesta (Response).</li>
            </ol>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        document.getElementById('cookies').textContent = 'Cookies: ' + (document.cookie || '(none)');
        document.getElementById('local').textContent = 'LocalStorage auth_token: ' + (localStorage.getItem('auth_token') || '(none)');

        function showResult(elId, r) {
            document.getElementById(elId).textContent = JSON.stringify(r, null, 2);
        }

        document.getElementById('btn-me').addEventListener('click', async () => {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const res = await fetch(base + 'auth/me', { credentials: 'include' });
                const text = await res.text();
                let parsed;
                try { parsed = JSON.parse(text); } catch (e) { parsed = { raw: text }; }
                showResult('res-me', { status: res.status, ok: res.ok, headers: Object.fromEntries(res.headers), body: parsed });
            } catch (err) { showResult('res-me', { error: err.toString() }); }
        });

        document.getElementById('btn-products').addEventListener('click', async () => {
            const base = (window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE : 'http://localhost:8080/api/';
            try {
                const res = await fetch(base + 'client/products', { credentials: 'include' });
                const text = await res.text();
                let parsed;
                try { parsed = JSON.parse(text); } catch (e) { parsed = { raw: text }; }
                showResult('res-products', { status: res.status, ok: res.ok, headers: Object.fromEntries(res.headers), body: parsed });
            } catch (err) { showResult('res-products', { error: err.toString() }); }
        });
    </script>
</body>

</html>