<?php
// Server-side auth check helper for Sabores360
// Usage: require __DIR__ . '/includes/auth_check.php'; then call require_auth() or require_role('admin')

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function get_api_base()
{
    // try environment then default
    if (!empty($_ENV['SABORES_API_BASE']))
        return rtrim($_ENV['SABORES_API_BASE'], '/') . '/';
    return 'http://localhost:8080/api/';
}

function call_api_me_server($token = null)
{
    $url = rtrim(get_api_base(), '/') . '/auth/me';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $headers = ['Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    } else {
        // Forward cookies from client to API call if present
        if (!empty($_COOKIE)) {
            $cookiePairs = [];
            foreach ($_COOKIE as $k => $v) {
                $cookiePairs[] = $k . '=' . $v;
            }
            curl_setopt($ch, CURLOPT_COOKIE, implode('; ', $cookiePairs));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // Allow insecure for local dev if needed
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resp === false)
        return ['success' => false, 'error' => $err, 'code' => $code];
    $json = json_decode($resp, true);
    if ($json === null)
        return ['success' => false, 'error' => 'invalid_json', 'raw' => $resp, 'code' => $code];
    return array_merge(['http_code' => $code], $json);
}

function ensure_session_from_api($token = null)
{
    // If session already has user, return it
    if (!empty($_SESSION['user']))
        return $_SESSION['user'];

    $res = call_api_me_server($token);
    if (!empty($res['success']) && !empty($res['user'])) {
        // normalize minimal user info in session
        $_SESSION['user'] = $res['user'];
        if (!empty($res['user']['role']))
            $_SESSION['user_role'] = $res['user']['role'];
        elseif (!empty($res['user']['role_name']))
            $_SESSION['user_role'] = $res['user']['role_name'];
        return $_SESSION['user'];
    }
    return null;
}

function require_auth()
{
    // If user in session, ok
    if (!empty($_SESSION['user']))
        return true;
    // Try token cookie names commonly used (adjust to your backend)
    $possible = ['auth_token', 'token', 'sabores_token', 'jwt'];
    foreach ($possible as $c) {
        if (!empty($_COOKIE[$c])) {
            $user = ensure_session_from_api($_COOKIE[$c]);
            if ($user)
                return true;
        }
    }
    // Try calling API using forwarded cookies
    $user = ensure_session_from_api(null);
    if ($user)
        return true;

    // Not authenticated — redirect to login
    header('Location: /Sabores360/views/auth/login.php');
    exit;
}

function require_role($roleName)
{
    $roleName = strtolower($roleName);
    // ensure authenticated first
    require_auth();
    $userRole = '';
    if (!empty($_SESSION['user_role']))
        $userRole = strtolower($_SESSION['user_role']);
    // also try user.role
    if (empty($userRole) && !empty($_SESSION['user']['role']))
        $userRole = strtolower($_SESSION['user']['role']);

    // Accept partial match (e.g., 'admin' matches 'administrador')
    if (strpos($userRole, $roleName) !== false)
        return true;

    // allow mapping: if roleName is 'client' accept cliente
    $map = [
        'admin' => ['admin', 'administrador'],
        'vendedor' => ['vendedor', 'seller', 'vend'],
        'seller' => ['vendedor', 'seller', 'vend'],
        'client' => ['cliente', 'client']
    ];
    if (isset($map[$roleName])) {
        foreach ($map[$roleName] as $alias) {
            if (strpos($userRole, $alias) !== false)
                return true;
        }
    }

    // Not authorized — redirect to login (or a 403 page)
    header('Location: /Sabores360/views/auth/login.php');
    exit;
}

?>