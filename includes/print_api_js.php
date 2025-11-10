<?php
// Echo a small JS snippet to expose the server-side URL_BASE constant to client JS
require_once __DIR__ . '/baseURL.php';
// Safety: ensure URL_BASE is defined
if (!defined('URL_BASE')) {
    define('URL_BASE', 'http://localhost:8080/api/');
}
// Print a JS global that other scripts (e.g., common.js) will consume.
echo "<script>window.SABORES360 = window.SABORES360 || {}; SABORES360.API_BASE = " . json_encode(URL_BASE) . ";</script>\n";

?>