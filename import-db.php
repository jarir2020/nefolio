<?php
define("BASEPATH", TRUE);
require __DIR__ . '/vendor/autoload.php';

// Disable time limits and increase memory for large imports
set_time_limit(0);
ini_set('memory_limit', '512M');

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

if (!$db_name || !$db_user) {
    die("Error: DB_DATABASE and DB_USERNAME must be set in your .env file.");
}

echo "<h2>Database Synchronization Tool</h2>";
echo "Connecting to hosting database: <b>" . htmlspecialchars($db_name) . "</b> on <b>" . htmlspecialchars($db_host) . "</b>...<br>";

try {
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✅ Database connection successful!<br><br>";

    $sqlFile = __DIR__ . '/nefolio_dump.sql';
    if (!file_exists($sqlFile)) {
        die("❌ Error: nefolio_dump.sql not found in root directory.");
    }

    echo "Reading nefolio_dump.sql...<br>";
    $fp = fopen($sqlFile, 'r');
    if (!$fp) {
        die("❌ Error: Could not open nefolio_dump.sql");
    }

    echo "Executing SQL statements...<br>";
    $query = '';
    $count = 0;
    $errors = 0;

    // Temporarily disable foreign key checks to prevent ordering issues
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    while (($line = fgets($fp)) !== false) {
        // Skip comments and empty lines
        if (substr(trim($line), 0, 2) == '--' || trim($line) == '' || substr(trim($line), 0, 1) == '#') {
            continue;
        }

        $query .= $line;

        // If the line ends with a semicolon, execute it
        if (substr(trim($line), -1) == ';') {
            try {
                $pdo->exec($query);
                $count++;
            } catch (PDOException $e) {
                $errors++;
                echo "⚠️ Query error on statement $count: " . htmlspecialchars($e->getMessage()) . "<br>";
                echo "<pre style='background:#f8f9fa;padding:10px;border:1px solid #ccc;'>" . htmlspecialchars($query) . "</pre>";
            }
            $query = '';
        }
    }

    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    fclose($fp);

    echo "<br><b>Import completed!</b><br>";
    echo "Executed: $count statements successfully.<br>";
    if ($errors > 0) {
        echo "❌ Warnings/Errors encountered: $errors<br>";
    } else {
        echo "🎉 All queries executed successfully without errors!<br>";
    }

    echo "<br><span style='color:orange'>⚠️ Security Tip: Please delete `import-db.php` and `nefolio_dump.sql` from your hosting server after you verify the sync works!</span><br>";

} catch (PDOException $e) {
    echo "❌ Database connection or system error: " . htmlspecialchars($e->getMessage()) . "<br>";
}
