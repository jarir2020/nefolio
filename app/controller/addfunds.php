<?php
if (!defined('BASEPATH')) {
    die('Direct access to the script is not allowed');
}
define("ADDFUNDS", TRUE);
$title .= " Add Funds";

if ($_SESSION["msmbilisim_userlogin"] != 1 || $user["client_type"] == 1) {
    header("Location:" . site_url('logout'));
    exit;
}

if ($settings["email_confirmation"] == 1 && $user["email_type"] == 1) {
    header("Location:" . site_url('confirm_email'));
    exit;
}

/* =========================
   GET  – LOAD DATA
   ========================= */
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $normalizeMethodLogo = function ($logo) {
        $logo = trim((string) $logo);
        if ($logo === "") {
            return site_url("img/admin/payment-methods.svg");
        }

        if (preg_match('#^https?://#i', $logo)) {
            return $logo;
        }

        if (strpos($logo, "/") === 0) {
            return site_url(ltrim($logo, "/"));
        }

        return site_url(ltrim($logo, "/"));
    };

    // All active payment methods
    $paymentMethods = $conn->prepare(
        "SELECT * FROM paymentmethods WHERE methodStatus=:status ORDER BY methodPosition ASC"
    );
    $paymentMethods->execute(["status" => 1]);

    $methodsList = [];
    $paymentMethodsJSON = json_encode([], JSON_UNESCAPED_UNICODE);

    if ($paymentMethods->rowCount()) {
        $paymentMethods = $paymentMethods->fetchAll(PDO::FETCH_ASSOC);
        $defaultMethodLogo = site_url("img/admin/payment-methods.svg");

        for ($i = 0; $i < count($paymentMethods); $i++) {
            $methodExtras = json_decode($paymentMethods[$i]["methodExtras"], true);
            $exchangeRate = isset($methodExtras["exchange_rate"]) ? $methodExtras["exchange_rate"] : null;
            $bonusRules = isset($methodExtras["bonus_rules"]) && is_array($methodExtras["bonus_rules"]) ? $methodExtras["bonus_rules"] : [];
            $quickAmounts = isset($methodExtras["quick_amounts"]) && is_array($methodExtras["quick_amounts"])
                ? $methodExtras["quick_amounts"]
                : null;
            $methodLogo = trim((string) $paymentMethods[$i]["methodLogo"]);
            $methodsList[] = [
                "id"            => $paymentMethods[$i]["methodId"],
                "position"      => $paymentMethods[$i]["methodPosition"],
                "name"          => $paymentMethods[$i]["methodVisibleName"],
                "short_name"    => $paymentMethods[$i]["methodShortName"] ?? "",
                "logo"          => $normalizeMethodLogo($methodLogo !== "" ? $methodLogo : $defaultMethodLogo),
                "callback"      => $paymentMethods[$i]["methodCallback"],
                "currency"      => $paymentMethods[$i]["methodCurrency"],
                "min_amount"    => $paymentMethods[$i]["methodMin"],
                "max_amount"    => $paymentMethods[$i]["methodMax"],
                "exchange_rate" => $exchangeRate,
                "bonus_percentage" => $paymentMethods[$i]["methodBonusPercentage"],
                "bonus_start_amount" => $paymentMethods[$i]["methodBonusStartAmount"],
                "bonus_enabled" => isset($paymentMethods[$i]["methodBonusEnabled"]) ? intval($paymentMethods[$i]["methodBonusEnabled"]) : 1,
                "dollar_rate_conversion_enabled" => isset($paymentMethods[$i]["dollarRateConversionEnabled"]) ? intval($paymentMethods[$i]["dollarRateConversionEnabled"]) : 1,
                "bonus_rules"   => $bonusRules,
                "instructions"  => trim(htmlspecialchars_decode($paymentMethods[$i]["methodInstructions"])),
                "fee"           => $paymentMethods[$i]["methodFee"],
                "quick_amounts" => $quickAmounts,
            ];
        }
        // grouped by id for JS
        $paymentMethodsJSON = json_encode(array_group_by($methodsList, "id"), JSON_UNESCAPED_UNICODE);
    } else {
        $methodsList[] = [
            "id"   => 0,
            "name" => "No payment gateway activated",
        ];
        $paymentMethodsJSON = json_encode(array_group_by($methodsList, "id"), JSON_UNESCAPED_UNICODE);
    }

    // For payment history
    $methodNames = $conn->prepare("SELECT methodId, methodVisibleName FROM paymentmethods");
    $methodNames->execute();
    $methodNames = $methodNames->fetchAll(PDO::FETCH_ASSOC);
    $methodNames = array_group_by($methodNames, "methodId");

    $transactions = $conn->prepare("
        SELECT payment_id, payment_create_date, payment_method, payment_amount 
        FROM payments 
        WHERE payment_status=:status && payment_delivery=:delivery && client_id=:id 
        ORDER BY payment_id DESC
    ");
    $transactions->execute([
        "status"   => 3,
        "delivery" => 2,
        "id"       => $user["client_id"],
    ]);
    $transactions = $transactions->fetchAll(PDO::FETCH_ASSOC);

    $paymentHistory = [];
    for ($i = 0; $i < count($transactions); $i++) {
        $paymentHistory[] = [
            "id"     => $transactions[$i]["payment_id"],
            "date"   => $transactions[$i]["payment_create_date"],
            "name"   => $methodNames[$transactions[$i]["payment_method"]][0]["methodVisibleName"],
            "amount" => format_amount_string(
                $user["currency_type"],
                from_to(
                    $currencies_array,
                    $settings["site_base_currency"],
                    $user["currency_type"],
                    $transactions[$i]["payment_amount"]
                )
            ),
        ];
    }

    // Here you probably pass $methodsList, $paymentMethodsJSON, $paymentHistory to Twig
}

/* =========================
   AJAX – GET FORM
   ========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "getForm") {
    $formData = "";
    $selectedMethod = $_POST["selectedMethod"];

    // this file should fill $formData
    include("addfunds/getForm.php");

    $response = [
        "success" => true,
        "content" => $formData,
    ];

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

/* =========================
   POST – PROCESS PAYMENT
   ========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST" && (!isset($_POST["action"]) || $_POST["action"] != "getForm")) {

    $methodId = intval($_POST["payment_type"] ?? 0);

    $method = $conn->prepare("SELECT * FROM paymentmethods WHERE methodId=:id AND methodStatus=:status");
    $method->execute([
        "id"     => $methodId,
        "status" => 1,
    ]);

    if (!$method->rowCount()) {
        errorExit("Select a valid payment method.");
    }

    $method                = $method->fetch(PDO::FETCH_ASSOC);
    $methodId              = $method["methodId"];
    $methodMin             = number_format($method["methodMin"], 2, '.', '');
    $methodMax             = number_format($method["methodMax"], 2, '.', '');
    $methodCurrency        = $method["methodCurrency"];
    $methodCurrencySymbol  = $currencies_array[$methodCurrency][0]["currency_symbol"] ?: $methodCurrency;
    $methodCallback        = $method["methodCallback"];
    $methodExtras          = json_decode($method["methodExtras"], true);
    $paymentFee            = $method["methodFee"];
    $paymentBonus          = $method["methodBonusPercentage"];
    $paymentBonusStartAmount = $method["methodBonusStartAmount"];

    $paymentAmount = floatval($_POST["payment_amount"] ?? 0);

    if ($paymentAmount < $methodMin) {
        errorExit("Minimum amount : $methodCurrencySymbol $methodMin");
    }
    if ($paymentAmount > $methodMax) {
        errorExit("Maximum amount : $methodCurrencySymbol $methodMax");
    }

    $response = [];

    // Normal gateways
    if ($method["methodId"] == 1)  { require("addfunds/Initiators/payTMCheckout.php"); }
    if ($method["methodId"] == 2)  { require("addfunds/Initiators/payTMMerchant.php"); }
    if ($method["methodId"] == 3)  { require("addfunds/Initiators/perfectMoney.php"); }
    if ($method["methodId"] == 4)  { require("addfunds/Initiators/coinbaseCommerce.php"); }
    if ($method["methodId"] == 5)  { require("addfunds/Initiators/kashier.php"); }
    if ($method["methodId"] == 6)  { require("addfunds/Initiators/razorPay.php"); }
    if ($method["methodId"] == 7)  { require("addfunds/Initiators/phonepe.php"); }
    if ($method["methodId"] == 8)  { require("addfunds/Initiators/easypaisa.php"); }
    if ($method["methodId"] == 9)  { require("addfunds/Initiators/jazzcash.php"); }
    if ($method["methodId"] == 10) { require("addfunds/Initiators/instamojo.php"); }
    if ($method["methodId"] == 11) { require("addfunds/Initiators/cashmaal.php"); }
    if ($method["methodId"] == 12) { require("addfunds/Initiators/alipay.php"); }
    if ($method["methodId"] == 13) { require("addfunds/Initiators/payU.php"); }
    if ($method["methodId"] == 14) { require("addfunds/Initiators/upiapi.php"); }
    if ($method["methodId"] == 15) { require("addfunds/Initiators/opay.php"); }
    if ($method["methodId"] == 16) { require("addfunds/Initiators/flutterwave.php"); }
    if ($method["methodId"] == 17) { require("addfunds/Initiators/stripe.php"); }
    if ($method["methodId"] == 18) { require("addfunds/Initiators/payeer.php"); }
    if ($method["methodId"] == 19) { require("addfunds/Initiators/sfspay.php"); }
    if ($method["methodId"] == 20) { require("addfunds/Initiators/uddoktapay.php");}
    if ($method["methodId"] == 21) { require("addfunds/Initiators/bkash.php"); }
    if ($method["methodId"] == 22) { require("addfunds/Initiators/uddoktapayint.php");}
    if ($method["methodId"] == 24) { require("addfunds/Initiators/binance.php"); exit;}
    if ($method["methodId"] == 41) { require("addfunds/Initiators/alphapaybd.php"); }
    if ($method["methodId"] == 42) { require("addfunds/Initiators/alphapaybd.php"); }
    if ($method["methodId"] == 43) { require("addfunds/Initiators/cryptomus.php"); }
    if ($method["methodId"] == 44) { require("addfunds/Initiators/heleket.php"); }
    if ($method["methodId"] == 45) { require("addfunds/Initiators/bybit.php"); }
    if ($method["methodId"] == 46) { require("addfunds/Initiators/nexsopay.php"); }
    if ($method["methodId"] == 69) { require("addfunds/Initiators/nagorikpay.php"); }
    if ($method["methodId"] == 99) { require("addfunds/Initiators/manual_payment.php"); }
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
