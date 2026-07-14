<?php

$automaticMethods = [
    1,
    2,
    3,
    4,
    5,
    6,
    7,
    8,
    9,
    10,
    11,
    12,
    13,
    14,
    15,
    16,
    17,
    18,
    19,
    20,
    21,
    22,
    23,
    24,
    25,
    41,
    42,
    69
];

$allMethods = array_merge($automaticMethods, $manualMethods);

$methodId = intval($_POST["method_id"]);
$methodVisibleName = htmlspecialchars($_POST["method_name"]);
$methodMin = $_POST["method_min"];
$methodMax = intval($_POST["method_max"]);
$methodFee = floatval($_POST["method_fee"]);
$methodBonusPercentage = floatval($_POST["method_bonus"]);
$methodBonusStartAmount = intval($_POST["method_bonus_start_amount"]);
$methodBonusEnabled = isset($_POST["method_bonus_enabled"]) ? intval($_POST["method_bonus_enabled"]) : 1;
$methodShortName = isset($_POST["method_short_name"]) ? trim(htmlspecialchars($_POST["method_short_name"])) : "";
$methodFromCurrency = isset($_POST["methodFromCurrency"]) ? trim(htmlspecialchars($_POST["methodFromCurrency"])) : "";
$dollarRateConversionEnabled = isset($_POST["dollar_rate_conversion_enabled"]) ? intval($_POST["dollar_rate_conversion_enabled"]) : 1;
$methodStatus = in_array($_POST["method_status"], [0, 1]) ? $_POST["method_status"] : 1;
$methodInstructions = htmlspecialchars($_POST["method_instructions"]);
$methodLogo = isset($_POST["method_logo"]) ? trim(htmlspecialchars($_POST["method_logo"])) : "";
$deletedFilesRaw = isset($_POST["payment_method_deleted_files"]) ? trim((string) $_POST["payment_method_deleted_files"]) : "";
$methodInstructions = str_replace("&lt;p&gt;&lt;br&gt;&lt;/p&gt;","",$methodInstructions);
$methodRateRulesPosted = isset($_POST["method_bonus_rules"]) && is_array($_POST["method_bonus_rules"]);
$methodRateRules = [];
if ($methodRateRulesPosted) {
    foreach ($_POST["method_bonus_rules"] as $bonusRule) {
        $rangeFrom = isset($bonusRule["range_from"]) && $bonusRule["range_from"] !== "" ? floatval($bonusRule["range_from"]) : null;
        $bonusPercent = isset($bonusRule["bonus_percent"]) && $bonusRule["bonus_percent"] !== "" ? floatval($bonusRule["bonus_percent"]) : null;
        if ($rangeFrom === null || $bonusPercent === null) {
            continue;
        }

        $methodRateRules[] = [
            "range_from" => $rangeFrom,
            "range_to" => isset($bonusRule["range_to"]) && $bonusRule["range_to"] !== "" ? floatval($bonusRule["range_to"]) : null,
            "bonus_percent" => $bonusPercent,
            "is_active" => isset($bonusRule["is_active"]) ? intval($bonusRule["is_active"]) : 1
        ];
    }
}

$currentMethod = $conn->prepare("SELECT methodExtras FROM paymentmethods WHERE methodId=:id");
$currentMethod->execute(["id" => $methodId]);
$currentMethod = $currentMethod->fetch(PDO::FETCH_ASSOC);

$deletedFileIds = [];
if ($deletedFilesRaw !== "") {
    foreach (explode(",", $deletedFilesRaw) as $deletedFileId) {
        $deletedFileId = intval(trim($deletedFileId));
        if ($deletedFileId > 0) {
            $deletedFileIds[$deletedFileId] = $deletedFileId;
        }
    }
    $deletedFileIds = array_values($deletedFileIds);
}

$deletedFileLinks = [];
if (!empty($deletedFileIds)) {
    $placeholders = implode(",", array_fill(0, count($deletedFileIds), "?"));
    $deletedFiles = $conn->prepare("SELECT id, link FROM files WHERE id IN ($placeholders)");
    $deletedFiles->execute($deletedFileIds);
    $deletedFiles = $deletedFiles->fetchAll(PDO::FETCH_ASSOC);
    foreach ($deletedFiles as $deletedFile) {
        $deletedFileLinks[] = trim((string) ($deletedFile["link"] ?? ""));
    }
}

$defaultMethodLogo = site_url("img/admin/payment-methods.svg");
if ($methodLogo !== "" && in_array($methodLogo, $deletedFileLinks, true)) {
    $methodLogo = $defaultMethodLogo;
}

if (!in_array($methodId, $allMethods)) {
    errorExit("Invalid payment method");
}

if (in_array($methodId, $automaticMethods)) {
    $update = $conn->prepare("UPDATE paymentmethods SET
                          methodVisibleName=:name,
                          methodLogo=:logo,
                          methodShortName=:short_name,
                          methodMin=:min,
                          methodMax=:max,
                          methodFee=:fee,
                          methodBonusPercentage=:bonus,
                          methodBonusStartAmount=:bonus_start_amount,
                          methodBonusEnabled=:bonus_enabled,
                          dollarRateConversionEnabled=:dollar_rate_enabled,
                          methodCurrency=:from_currency,
                          methodStatus=:status,
                          methodInstructions=:instructions
                        WHERE methodId=:id");
    $update->execute([
        "name" => $methodVisibleName,
        "logo" => $methodLogo,
        "short_name" => $methodShortName,
        "min" => $methodMin,
        "max" => $methodMax,
        "fee" => $methodFee,
        "bonus" => $methodBonusPercentage,
        "bonus_start_amount" => $methodBonusStartAmount,
        "bonus_enabled" => $methodBonusEnabled,
        "dollar_rate_enabled" => $dollarRateConversionEnabled,
        "from_currency" => $methodFromCurrency,
        "status" => $methodStatus,
        "instructions" => $methodInstructions,
        "id" => $methodId
    ]);

    $response = [
        "success" => true,
        "message" => "Payment method updated successfully."
    ];

    require_once("editMethodExtras.php");

} else {
    $update = $conn->prepare("UPDATE paymentmethods SET
                          methodVisibleName=:name,
                          methodLogo=:logo,
                          methodShortName=:short_name,
                          methodBonusEnabled=:bonus_enabled,
                          dollarRateConversionEnabled=:dollar_rate_enabled,
                          methodCurrency=:from_currency,
                          methodStatus=:status,
                          methodInstructions=:instructions
                        WHERE methodId=:id");
    $update->execute([
        "name" => $methodVisibleName,
        "logo" => $methodLogo,
        "short_name" => $methodShortName,
        "bonus_enabled" => $methodBonusEnabled,
        "dollar_rate_enabled" => $dollarRateConversionEnabled,
        "from_currency" => $methodFromCurrency,
        "status" => $methodStatus,
        "instructions" => $methodInstructions,
        "id" => $methodId
    ]);

    $response = [
        "success" => true,
        "message" => "Payment method updated successfully."
    ];
}

if ($methodRateRulesPosted && !in_array($methodId, $automaticMethods) && isset($currentMethod["methodExtras"])) {
    $existingExtras = json_decode($currentMethod["methodExtras"], true);
    if (!is_array($existingExtras)) {
        $existingExtras = [];
    }
    $existingExtras["bonus_rules"] = $methodRateRules;
    $updateExtras = $conn->prepare("UPDATE paymentmethods SET methodExtras=:extras WHERE methodId=:id");
    $updateExtras->execute([
        "extras" => json_encode($existingExtras),
        "id" => $methodId
    ]);
}

if (!empty($deletedFileIds)) {
    $deleteFile = $conn->prepare("DELETE FROM files WHERE id=:id");
    $fileLookup = $conn->prepare("SELECT link FROM files WHERE id=:id");

    foreach ($deletedFileIds as $deletedFileId) {
        $fileLookup->execute(["id" => $deletedFileId]);
        $file = $fileLookup->fetch(PDO::FETCH_ASSOC);
        if (!empty($file["link"])) {
            $parsedLink = parse_url($file["link"], PHP_URL_PATH);
            if ($parsedLink) {
                $localPath = rtrim($_SERVER["DOCUMENT_ROOT"], "/") . "/" . ltrim($parsedLink, "/");
                if (file_exists($localPath)) {
                    @unlink($localPath);
                }
            }
        }
        $deleteFile->execute(["id" => $deletedFileId]);
    }
}
