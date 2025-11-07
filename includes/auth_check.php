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

    // If the client has provided a preferred API base via cookie (set by SABORES360.API on the client), prefer it
    if (!empty($_COOKIE['SABORES_API_BASE'])) {
        return rtrim($_COOKIE['SABORES_API_BASE'], '/') . '/';
    }

    // If we've detected and cached an API base earlier in this session, reuse it
    if (!empty($_SESSION['api_base'])) {
        return rtrim($_SESSION['api_base'], '/') . '/';
    }

    // Default fallback (legacy): try 8080 first
    return 'http://localhost:8080/api/';
}

/**
 * Try calling the API /auth/me using a specific base URL (used for autodetection)
 * Returns the parsed response array on success, or null on failure.
 */
function call_api_me_with_base($base, $token = null)
{
    $url = rtrim($base, '/') . '/auth/me';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $headers = ['Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    } else {
        if (!empty($_COOKIE)) {
            $cookiePairs = [];
            foreach ($_COOKIE as $k => $v) {
                $cookiePairs[] = $k . '=' . $v;
            }
            curl_setopt($ch, CURLOPT_COOKIE, implode('; ', $cookiePairs));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resp === false)
        return null;
    $json = json_decode($resp, true);
    if ($json === null)
        return null;
    return array_merge(['http_code' => $code], $json);
}

/**
 * Attempt to autodetect a working API base by probing common ports and the current host.
 * Caches the discovered base in session to avoid repeated probes.
 */
function detect_and_cache_api_base()
{
    // If already detected, reuse
    if (!empty($_SESSION['api_base']))
        return $_SESSION['api_base'];

    $candidates = [];

    // If running behind a proxy or with HOST header, respect the host
    $host = 'localhost';
    if (!empty($_SERVER['HTTP_HOST'])) {
        // strip possible port
        $host = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']);
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    // 1) common env fallback values
    $candidates[] = $scheme . '://' . $host . ':8000/api/';
    $candidates[] = $scheme . '://' . $host . ':8080/api/';

    // 2) localhost explicit (in case HTTP_HOST is different)
    $candidates[] = 'http://localhost:8000/api/';
    $candidates[] = 'http://localhost:8080/api/';

    // 3) last-resort: default from get_api_base() (could be 8080)
    $candidates[] = 'http://localhost:8080/api/';

    foreach ($candidates as $cand) {
        $res = call_api_me_with_base($cand, null);
        if ($res && !empty($res['success'])) {
            // cache and return
            $_SESSION['api_base'] = rtrim($cand, '/') . '/';
            return $_SESSION['api_base'];
        }
    }

    return null;
}

function call_api_me_server($token = null)
{
    // Use current API base; if it fails, try autodetection once
    $base = get_api_base();
    $url = rtrim($base, '/') . '/auth/me';
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

    if ($resp === false) {
        // try autodetection if not yet cached
        $detected = detect_and_cache_api_base();
        if ($detected && $detected !== $base) {
            return call_api_me_with_base($detected, $token);
        }
        return ['success' => false, 'error' => $err, 'code' => $code];
    }

    $json = json_decode($resp, true);
    if ($json === null) {
        // try autodetection
        $detected = detect_and_cache_api_base();
        if ($detected && $detected !== $base) {
            return call_api_me_with_base($detected, $token);
        }
        return ['success' => false, 'error' => 'invalid_json', 'raw' => $resp, 'code' => $code];
    }

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