<?php

// One-off hosting migration helper for the columns introduced in this session.
// Run this once on the hosting environment, then remove it if you do not need it anymore.

$config = require __DIR__ . '/app/config.php';

try {
  $conn = new PDO(
    "mysql:host=" . $config["db"]["host"] . ";dbname=" . $config["db"]["name"] . ";charset=" . $config["db"]["charset"] . ";",
    $config["db"]["user"],
    $config["db"]["pass"],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  die($e->getMessage());
}

function columnExists(PDO $conn, string $table, string $column): bool
{
  $stmt = $conn->prepare("SHOW COLUMNS FROM `$table` LIKE :column");
  $stmt->execute(["column" => $column]);
  return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
}

function addColumnIfMissing(PDO $conn, string $table, string $column, string $definition): string
{
  if (columnExists($conn, $table, $column)) {
    return "$table.$column already exists";
  }

  $conn->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
  return "Added $table.$column";
}

$results = [];
$results[] = addColumnIfMissing($conn, "clients", "ref_code", "TEXT NOT NULL AFTER `currency_type`");
$results[] = addColumnIfMissing($conn, "payments", "payment_update_date", "DATETIME NOT NULL AFTER `payment_create_date`");
$results[] = addColumnIfMissing($conn, "payments", "payment_bank", "INT NOT NULL DEFAULT 0 AFTER `payment_extra`");
$results[] = addColumnIfMissing($conn, "settings", "dolar_charge", "DOUBLE NOT NULL DEFAULT 132 AFTER `service_list`");

header("Content-Type: text/plain; charset=utf-8");
echo implode(PHP_EOL, $results) . PHP_EOL;

