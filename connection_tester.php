<?php

$host = 'localhost';
$dbname = 'netfollows_netfollows';
$username = 'netfollows_dev';
$password = 'netfollows_dev';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "<h2 style='color:green'>✅ Database connection successful!</h2>";

    $stmt = $pdo->query("SELECT VERSION() AS version");
    $result = $stmt->fetch();

    echo "MySQL Version: " . htmlspecialchars($result['version']);

} catch (PDOException $e) {
    echo "<h2 style='color:red'>❌ Database connection failed!</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}