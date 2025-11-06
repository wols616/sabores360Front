<?php
require __DIR__ . '/../../includes/auth_check.php';
require_role('vendedor');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Vendedor - Productos</title>
    <link rel="stylesheet" href="/Sabores360/assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Productos (Vendedor)</h1>
        <?php $active = 'products';
        require __DIR__ . '/../_navbar.php'; ?>
    </header>

    <main>
        <section>
            <h2>Control de stock</h2>
            <div id="seller-products">Cargando...</div>
        </section>
    </main>

    <script src="/Sabores360/assets/js/common.js"></script>
    <script>
        (async function () {
            const container = document.getElementById('seller-products');
            try {
                const d = await (window.SABORES360 && SABORES360.API ? SABORES360.API.get('seller/products') : (async () => { const res = await fetch((window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE + 'seller/products' : 'http://localhost:8080/api/seller/products', { credentials: 'include' }); const t = await res.text(); try { return JSON.parse(t); } catch (e) { return { success: res.ok, httpStatus: res.status, raw: t }; } })());
                console.debug('seller/products response', d);
                const products = (d && d.products) || (d && d.data && d.data.products) || (d && d.productsList) || (d && d.data && d.data.items) || (Array.isArray(d) ? d : null) || (d && d.data && Array.isArray(d.data) ? d.data : null);
                if (products && Array.isArray(products)) {
                    // helper to normalize image urls (same rules used elsewhere)
                    function normalizeImageUrl(u) {
                        if (!u) return null;
                        if (u === 'undefined' || u === 'null') return null;
                        try {
                            if (u.startsWith('http://') || u.startsWith('https://') || u.startsWith('//')) return u;
                        } catch (e) { }
                        if (u.startsWith('/')) return window.location.origin + u;
                        return window.location.origin + '/' + u;
                    }

                    container.innerHTML = '';
                    products.forEach(p => {
                        const el = document.createElement('div');
                        const stockVal = (p.stock !== undefined && p.stock !== null) ? p.stock : '';
                        // support several possible image fields: url_imagen, urlImagen, imageUrl, image_url, image
                        const rawImg = p.url_imagen || p.urlImagen || p.imageUrl || p.image_url || p.image || null;
                        const imgSrc = normalizeImageUrl(rawImg);
                        const placeholder = '/Sabores360/assets/img/no-image.svg';
                        const imgHtml = `<img src="${imgSrc ? imgSrc : placeholder}" onerror="this.onerror=null;this.src='${placeholder}';" style="max-width:120px;display:block;margin-bottom:6px;">`;
                        el.innerHTML = `${imgHtml}<strong>${p.name}</strong> - Stock: <input data-id="${p.id}" class="stock-input" value="${stockVal}"> <button class="save-stock" data-id="${p.id}">Guardar</button>`;
                        container.appendChild(el);
                    });
                    container.addEventListener('click', async (ev) => {
                        if (ev.target.matches('.save-stock')) {
                            const id = ev.target.getAttribute('data-id');
                            const input = container.querySelector(`.stock-input[data-id="${id}"]`);
                            const val = parseInt(input.value, 10) || 0;
                            try {
                                const res = await (window.SABORES360 && SABORES360.API ? SABORES360.API.post(`seller/products/${id}/stock`, { stock: val }) : (async () => { const r = await fetch((window.SABORES360 && SABORES360.API_BASE) ? SABORES360.API_BASE + `seller/products/${id}/stock` : `http://localhost:8080/api/seller/products/${id}/stock`, { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ stock: val }) }); const t = await r.text(); try { return JSON.parse(t); } catch (e) { return { success: r.ok, httpStatus: r.status, raw: t }; } })());
                                if (res && res.success) {
                                    alert('Stock actualizado');
                                } else {
                                    alert(res && res.message ? res.message : 'Error al actualizar stock');
                                }
                            } catch (err) { alert('Error de red'); }
                        }
                    });
                } else {
                    container.textContent = 'No hay productos.';
                    // show debug hint
                    const dbg = document.createElement('pre'); dbg.style.fontSize = '0.8em'; dbg.style.marginTop = '8px'; dbg.textContent = JSON.stringify(d, null, 2);
                    container.appendChild(dbg);
                }
            } catch (err) { container.textContent = 'Error.'; }
        })();
    </script>
</body>

</html>