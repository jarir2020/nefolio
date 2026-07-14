<?php

// One-off hosting helper to fetch SMS gateway settings from the database.
// Intended for deploy/ops use.

$config = require __DIR__ . '/app/config.php';

try {
  $conn = new PDO(
    "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"] . ";charset=" . $config["db"]["charset"] . ";",
    $config["db"]["user"],
    $config["db"]["pass"],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  http_response_code(500);
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode([
    "success" => false,
    "message" => $e->getMessage(),
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

$stmt = $conn->prepare("SELECT sms_provider, sms_title, sms_user, sms_pass, sms_validate FROM settings WHERE id = :id LIMIT 1");
$stmt->execute(["id" => 1]);
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

header("Content-Type: application/json; charset=utf-8");

if (!$settings) {
  http_response_code(404);
  echo json_encode([
    "success" => false,
    "message" => "Settings row not found",
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

echo json_encode([
  "success" => true,
  "data" => [
    "sms_provider" => $settings["sms_provider"],
    "sms_title" => $settings["sms_title"],
    "sms_user" => $settings["sms_user"],
    "sms_pass" => $settings["sms_pass"],
    "sms_validate" => $settings["sms_validate"],
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
