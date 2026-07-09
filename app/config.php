<?php
// Load .env file variables first
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match('/^\'(.*)\'$/', $value, $matches)) {
                $value = $matches[1];
            }
            putenv(sprintf('%s=%s', $key, $value));
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Convert APP_SUBFOLDER string/value to boolean
$subfolderEnv = getenv('APP_SUBFOLDER');
if ($subfolderEnv === 'false' || $subfolderEnv === '0') {
    $subfolder = false;
} elseif ($subfolderEnv === 'true' || $subfolderEnv === '1') {
    $subfolder = true;
} else {
    $subfolder = false;
}

define('PATH', realpath('.'));
define('SUBFOLDER', $subfolder);
// Dynamic host checking for local development vs production URL
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = false;
if (!empty($httpHost)) {
    if (strpos($httpHost, 'localhost') !== false || strpos($httpHost, '127.0.0.1') !== false || strpos($httpHost, '0.0.0.0') !== false) {
        $isLocal = true;
    }
}

if ($isLocal) {
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $detectedUrl = $scheme . '://' . $httpHost;
    define('URL', $detectedUrl);
    define('STYLESHEETS_URL', $detectedUrl);
} else {
    define('URL', getenv('APP_URL') ?: 'https://netfollows.com' );
    define('STYLESHEETS_URL', getenv('APP_STYLESHEETS_URL') ?: '//netfollows.com' );
}
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Asia/Dhaka');


ini_set("display_errors", "1");
error_reporting(E_ALL);


return [
  'db' => [
    'name'    =>  getenv('DB_DATABASE') ?: 'netfolio' ,
    'host'    =>  getenv('DB_HOST') ?: 'localhost',
    'user'    =>  getenv('DB_USERNAME') ?: 'root' ,
    'pass'    =>  getenv('DB_PASSWORD') ?: 'your_secure_password',
    'charset' =>  getenv('DB_CHARSET') ?: 'utf8mb4'
  ]
];

?>