<?php
// logout.php - clears local PHP session and attempts to call backend logout endpoint
if (session_status() === PHP_SESSION_NONE)
    session_start();

// Try to call backend logout so server-side session/cookie invalidated
function get_api_base()
{
    if (!empty($_ENV['SABORES_API_BASE']))
        return rtrim($_ENV['SABORES_API_BASE'], '/') . '/';
    return 'http://localhost:8080/api/';
}

$api = get_api_base();
$ch = curl_init(rtrim($api, '/') . '/auth/logout');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
// forward cookies
if (!empty($_COOKIE)) {
    $pairs = [];
    foreach ($_COOKIE as $k => $v)
        $pairs[] = $k . '=' . $v;
    curl_setopt($ch, CURLOPT_COOKIE, implode('; ', $pairs));
}
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
curl_close($ch);

// Clear PHP session
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}
session_destroy();

// Remove common token cookies if present
$possible = ['auth_token', 'token', 'sabores_token', 'jwt'];
foreach ($possible as $c) {
    if (isset($_COOKIE[$c]))
        setcookie($c, '', time() - 3600, '/');
}

// Redirect to login
header('Location: /Sabores360/views/auth/login.php');
exit;
?>