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

function tableExists(PDO $conn, string $table): bool
{
  $stmt = $conn->prepare("SHOW TABLES LIKE :table");
  $stmt->execute(["table" => $table]);
  return (bool) $stmt->fetch(PDO::FETCH_NUM);
}

function createRatesBonusRulesIfMissing(PDO $conn): string
{
  if (tableExists($conn, "rates_bonus_rules")) {
    return "rates_bonus_rules already exists";
  }

  $conn->exec("
    CREATE TABLE `rates_bonus_rules` (
      `id` int NOT NULL AUTO_INCREMENT,
      `range_from` decimal(12,2) NOT NULL DEFAULT 0.00,
      `range_to` decimal(12,2) DEFAULT NULL,
      `bonus_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  ");

  return "Created rates_bonus_rules";
}

function createPaymentsBonusIfMissing(PDO $conn): string
{
  if (tableExists($conn, "payments_bonus")) {
    return "payments_bonus already exists";
  }

  $conn->exec("
    CREATE TABLE `payments_bonus` (
      `bonus_id` int NOT NULL AUTO_INCREMENT,
      `bonus_method` int NOT NULL,
      `bonus_from` decimal(12,2) NOT NULL DEFAULT 0.00,
      `bonus_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
      `bonus_type` tinyint NOT NULL DEFAULT 2,
      PRIMARY KEY (`bonus_id`),
      KEY `bonus_method` (`bonus_method`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  ");

  return "Created payments_bonus";
}

$results = [];
$results[] = addColumnIfMissing($conn, "clients", "ref_code", "TEXT NOT NULL AFTER `currency_type`");
$results[] = addColumnIfMissing($conn, "payments", "payment_update_date", "DATETIME NOT NULL AFTER `payment_create_date`");
$results[] = addColumnIfMissing($conn, "payments", "payment_bank", "INT NOT NULL DEFAULT 0 AFTER `payment_extra`");
$results[] = addColumnIfMissing($conn, "paymentmethods", "methodBonusEnabled", "TINYINT(1) NOT NULL DEFAULT 1 AFTER `methodBonusStartAmount`");
$results[] = addColumnIfMissing($conn, "settings", "dolar_charge", "DOUBLE NOT NULL DEFAULT 132 AFTER `service_list`");
$results[] = createPaymentsBonusIfMissing($conn);
$results[] = createRatesBonusRulesIfMissing($conn);

header("Content-Type: text/plain; charset=utf-8");
echo implode(PHP_EOL, $results) . PHP_EOL;
