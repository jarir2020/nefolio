<?php
define("BASEPATH", TRUE);
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/init.php';

// Enable all error reporting for testing
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    echo "<h2>Testing DB Queries on Hosting</h2>";
    
    echo "Fetching clients... ";
    $clients = $conn->prepare("SELECT client_id, username FROM clients");
    $clients->execute();
    $clients = $clients->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($clients) . " clients.<br>";
    $clients = array_group_by($clients, "client_id");

    echo "Fetching payments... ";
    $payments = $conn->prepare("SELECT payment_id, client_id, client_balance, payment_amount, payment_method, payment_status, payment_delivery, payment_note, payment_mode, payment_extra, payment_create_date FROM payments ORDER BY payment_id DESC");
    $payments->execute();
    $payments = $payments->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($payments) . " payments.<br>";

    echo "Fetching payment methods... ";
    $methods = $conn->prepare("SELECT methodId,methodVisibleName FROM paymentmethods");
    $methods->execute();
    $methods = $methods->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($methods) . " payment methods.<br>";
    $methods = array_group_by($methods, "methodId");

    echo "✅ Database connection and queries executed successfully.<br>";
} catch (Exception $e) {
    echo "❌ Error occurred: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
} catch (Error $e) {
    echo "❌ Engine Error occurred: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
