<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}

header('Content-Type: application/json');

$key = isset($_POST['key']) ? $_POST['key'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (empty($key)) {
    echo json_encode(["error" => "API key is missing or invalid"]);
    exit();
}

if ($action == 'add') {
    echo json_encode([
        "order" => 987654
    ]);
    exit();
}

if ($action == 'status') {
    echo json_encode([
        "status" => "Pending",
        "charge" => "0.15",
        "start_count" => "100",
        "remains" => "1000",
        "currency" => "USD"
    ]);
    exit();
}

if ($action == 'balance') {
    echo json_encode([
        "balance" => "1234.56",
        "currency" => "USD"
    ]);
    exit();
}

if ($action == 'services') {
    echo json_encode([
        [
            "service" => 1,
            "name" => "Facebook Followers",
            "type" => "Default",
            "category" => "Facebook",
            "rate" => "0.15",
            "min" => "100",
            "max" => "10000"
        ]
    ]);
    exit();
}

echo json_encode(["error" => "Invalid action"]);
exit();
