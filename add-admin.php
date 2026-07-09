<?php
define("BASEPATH", TRUE);
require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Load .env configuration from the root
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match('/^\'(.*)\'$/', $value, $matches)) {
                $value = $matches[1];
            }
            putenv(sprintf('%s=%s', $key, $value));
            $_ENV[$key] = $value;
        }
    }
}

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_DATABASE');
$db_user = getenv('DB_USERNAME');
$db_pass = getenv('DB_PASSWORD');

try {
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $username = 'admin';
    $password = 'admin';
    $admin_email = 'admin@netfollows.com';
    $access = '{"admin_access":1,"users":1,"services":1,"update-prices":1,"bulk":1,"synced-logs":1,"orders":1,"subscriptions":1,"dripfeed":1,"tasks":1,"payments":1,"tickets":1,"additionals":1,"referral":1,"broadcast":1,"logs":1,"reports":1,"videop":1,"coupon":1,"child-panels":1,"updates":1,"appearance":1,"themes":1,"new_year":1,"pages":1,"news":1,"meta":1,"blog":1,"menu":1,"inte":1,"language":1,"files":1,"settings":1,"general_settings":1,"providers":1,"payments_settings":1,"bank_accounts":1,"modules":1,"subject":1,"payments_bonus":1,"currency-manager":1,"alert_settings":1,"site_count":1,"manager":1,"super_admin":1}';

    // Check if the user already exists in the database
    $check = $pdo->prepare("SELECT admin_id FROM admins WHERE username = ?");
    $check->execute([$username]);
    $existing = $check->fetch();

    if ($existing) {
        // Update password and ensure super-admin access
        $update = $pdo->prepare("UPDATE admins SET password = ?, access = ?, client_type = '2' WHERE username = ?");
        $update->execute([$password, $access, $username]);
        echo "✅ Admin user '$username' already existed. Password and permissions updated successfully!<br>";
    } else {
        // Insert new super-admin user
        $insert = $pdo->prepare("INSERT INTO admins (admin_type, admin_name, admin_email, username, password, register_date, client_type, access, mode, two_factor) VALUES ('3', 'Admin', ?, ?, ?, NOW(), '2', ?, 'dark', '0')");
        $insert->execute([$admin_email, $username, $password, $access]);
        echo "✅ Admin user '$username' created successfully with super-admin permissions!<br>";
    }

    // Auto delete for security
    unlink(__FILE__);
    echo "⚠️ This script has deleted itself for security purposes.";

} catch (PDOException $e) {
    echo "❌ Error: " . htmlspecialchars($e->getMessage());
}
