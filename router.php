<?php
$uri = urldecode(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));

// If it's a physical file that exists, serve it directly
if ($uri !== '/' && is_file(__DIR__ . $uri)) {
    return false;
}

// If the request path matches /admin, route to root index.php (bypass physical admin/ folder check)
if (preg_match('/^\/admin/', $uri)) {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    require_once __DIR__ . '/index.php';
    exit;
}

// Support relative asset paths in subfolders (e.g. /admin/public/... -> /public/...)
if (preg_match('/(public|css|js|img)\/.+$/', $uri, $matches)) {
    $relativePath = '/' . $matches[0];
    if (is_file(__DIR__ . $relativePath)) {
        $_SERVER['SCRIPT_FILENAME'] = __DIR__ . $relativePath;
        return false;
    }
}

// Fallback to main index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';
