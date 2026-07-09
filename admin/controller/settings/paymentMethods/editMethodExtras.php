<?php
$methodExtras = [];
if ($methodId == 1) {
    $merchantId = htmlspecialchars($_POST["merchantId"]);
    $merchantKey = htmlspecialchars($_POST["merchantKey"]);
    $methodExtras = [
        "merchantId" => $merchantId,
        "merchantKey" => $merchantKey
    ];
}

if ($methodId == 2) {
    $merchantId = htmlspecialchars($_POST["merchantId"]);
    $methodExtras = [
        "merchantId" => $merchantId
    ];
}

if ($methodId == 3) {
    $accountNumber = htmlspecialchars($_POST["accountNumber"]);
    $alternatePassPhrase = htmlspecialchars($_POST["alternatePassPhrase"]);
    $methodExtras = [
        "accountNumber" => $accountNumber,
        "alternatePassPhrase" => $alternatePassPhrase
    ];
}

if ($methodId == 4) {
    $APIKey = htmlspecialchars($_POST["APIKey"]);
    $methodExtras = [
        "APIKey" => $APIKey
    ];
}

if ($methodId == 5) {
    $MID = htmlspecialchars($_POST["MID"]);
    $APIKey = htmlspecialchars($_POST["APIKey"]);
    $mode = htmlspecialchars($_POST["mode"]);
    $methodExtras = [
        "MID" => $MID,
        "APIKey" => $APIKey,
        "mode" => $mode
    ];
}

if ($methodId == 6) {
    $APIPublicKey = htmlspecialchars($_POST["APIPublicKey"]);
    $APISecretKey = htmlspecialchars($_POST["APISecretKey"]);
    $methodExtras = [
        "APIPublicKey" => $APIPublicKey,
        "APISecretKey" => $APISecretKey
    ];
}

if ($methodId == 7) {
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = htmlspecialchars($_POST["password"]);
    $methodExtras = [
        "email" => $email,
        "password" => $password
    ];
}

if ($methodId == 8) {
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = htmlspecialchars($_POST["password"]);
    $senderEmail = htmlspecialchars($_POST["senderEmail"]);
    $emailSubject = htmlspecialchars($_POST["emailSubject"]);
    $methodExtras = [
        "email" => $email,
        "password" => $password,
        "senderEmail" => $senderEmail,
        "emailSubject" => $emailSubject
    ];
}

if ($methodId == 9) {
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = htmlspecialchars($_POST["password"]);
    $senderEmail = htmlspecialchars($_POST["senderEmail"]);
    $emailSubject = htmlspecialchars($_POST["emailSubject"]);
    $methodExtras = [
        "email" => $email,
        "password" => $password,
        "senderEmail" => $senderEmail,
        "emailSubject" => $emailSubject
    ];
}

if ($methodId == 10) {
    $APIKey = htmlspecialchars($_POST["APIKey"]);
    $authToken = htmlspecialchars($_POST["authToken"]);
    $methodExtras = [
        "APIKey" => $APIKey,
        "authToken" => $authToken
    ];
}

if ($methodId == 11) {
    $webId = htmlspecialchars($_POST["webId"]);
    $methodExtras = [
        "webId" => $webId,
    ];
}

if ($methodId == 12) {
    $partnerId = htmlspecialchars($_POST["partnerId"]);
    $privateKey = htmlspecialchars($_POST["privateKey"]);
    $methodExtras = [
        "partnerId" => $partnerId,
        "privateKey" => $privateKey
    ];
}

if ($methodId == 13) {
    $merchantKey = htmlspecialchars($_POST["merchantKey"]);
    $merchantSalt = htmlspecialchars($_POST["merchantSalt"]);
    $methodExtras = [
        "merchantKey" => $merchantKey,
        "merchantSalt" => $merchantSalt
    ];
}

if ($methodId == 14) {
    $productionAPIToken = htmlspecialchars($_POST["productionAPIToken"]);
    $productionAPISecretKey = htmlspecialchars($_POST["productionAPISecretKey"]);
    $methodExtras = [
        "productionAPIToken" => $productionAPIToken,
        "productionAPISecretKey" => $productionAPISecretKey
    ];
}

if ($methodId == 15) {
    $merchantId = htmlspecialchars($_POST["merchantId"]);
    $publicKey = htmlspecialchars($_POST["publicKey"]);
    $secretKey = htmlspecialchars($_POST["secretKey"]);
    $methodExtras = [
        "merchantId" => $merchantId,
        "publicKey" => $publicKey,
        "secretKey" => $secretKey
    ];
}

if ($methodId == 16) {
    $secretKey = htmlspecialchars($_POST["secretKey"]);
    $methodExtras = [
        "secretKey" => $secretKey
    ];
}

if ($methodId == 17) {
    $publishableKey = htmlspecialchars($_POST["publishableKey"]);
    $secretKey = htmlspecialchars($_POST["secretKey"]);
    $methodExtras = [
        "publishableKey" => $publishableKey,
        "secretKey" => $secretKey
    ];
}

if ($methodId == 18) {
    $shopId = htmlspecialchars($_POST["shopId"]);
    $secretKey = htmlspecialchars($_POST["secretKey"]);
    $methodExtras = [
        "shopId" => $shopId,
        "secretKey" => $secretKey
    ];
}

if ($methodId == 20) { // Uddoktapay Bangladeshi Payment
    $apiKey = htmlspecialchars($_POST["api_key"]);
    $apiUrl = htmlspecialchars($_POST["api_url"]);
    $exchangeRate = htmlspecialchars($_POST["exchange_rate"]);
    $methodExtras = [
        "api_key" => $apiKey,
        "api_url" => $apiUrl,
        "exchange_rate" => $exchangeRate
    ];
}

if ($methodId == 21) { // bKash Merchant Payment
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    $app_key = htmlspecialchars($_POST["app_key"]);
    $app_secret_key = htmlspecialchars($_POST["app_secret_key"]);
    $exchange_rate = htmlspecialchars($_POST["exchange_rate"]);
    $methodExtras = [
        "username" => $username,
        "password" => $password,
        "app_key" => $app_key,
        "app_secret_key" => $app_secret_key,
         "exchange_rate" => $exchange_rate
    ];
}

if ($methodId == 22) { // Uddoktapay Global Payment
    $apiKey = htmlspecialchars($_POST["api_key"]);
    $apiUrl = htmlspecialchars($_POST["api_url"]);
    $methodExtras = [
        "api_key" => $apiKey,
        "api_url" => $apiUrl
    ];
}

if ($methodId == 24) { // binance auto Pay
    $binance_pay_secret = htmlspecialchars($_POST["binance_pay_secret"]);
    $binance_pay_key = htmlspecialchars($_POST["binance_pay_key"]);
    $id= htmlspecialchars($_POST["id"]);
    $qr= htmlspecialchars($_POST["qr"]);
    $methodExtras = [
        "binance_pay_secret" => $binance_pay_secret,
        "binance_pay_key" => $binance_pay_key,
        "id" => $id,
        "qr" => $qr,
    ];
}

if ($methodId == 41) { // Alphapaybd Bangladeshi Payment
    $apiKey = htmlspecialchars($_POST["api_key"]);
    $exchangeRate = htmlspecialchars($_POST["exchange_rate"]);
    $methodExtras = [
        "api_key" => $apiKey,
        "exchange_rate" => $exchangeRate
    ];
}

if ($methodId == 42) { // Alphapaybd Binance
    $apiKey = htmlspecialchars($_POST["api_key"]);
    $exchangeRate = htmlspecialchars($_POST["exchange_rate"]);
    $methodExtras = [
        "api_key" => $apiKey,
        "exchange_rate" => $exchangeRate
    ];
}

if ($methodId == 69) { // Nagorikpay
    $apiKey = htmlspecialchars($_POST["api_key"]);
    $exchangeRate = htmlspecialchars($_POST["exchange_rate"]);
    $methodExtras = [
        "api_key" => $apiKey,
        "exchange_rate" => $exchangeRate
    ];
}


if ($methodId == 70) {
    $api_url = htmlspecialchars($_POST["api_url"]);
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    $client_id = htmlspecialchars($_POST["client_id"]);
    $client_secret = htmlspecialchars($_POST["client_secret"]);
    $merchant_wallet = htmlspecialchars($_POST["merchant_wallet"]);

    $methodExtras = [
        "api_url" => $api_url,
        "username" => $username,
        "password" => $password,
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "merchant_wallet" => $merchant_wallet,
    ];
}

if ($methodId == 88) {
    $apiKey = htmlspecialchars($_POST["api_key"]);
    $secret_key = htmlspecialchars($_POST["secret_key"]);
    $domain = htmlspecialchars($_POST["domain"]);
    $exchangeRate = htmlspecialchars($_POST["exchange_rate"]);

    $methodExtras = [
        "api_key" => $apiKey,
        "secret_key" => $secret_key,
        "domain" => $domain,
        "exchange_rate" => $exchangeRate
    ];
}

$methodExtras = json_encode($methodExtras);
$update = $conn->prepare("UPDATE paymentmethods SET methodExtras=:extras WHERE methodId=:id");
$update->execute([
    "extras" => $methodExtras,
    "id" => $methodId
]);