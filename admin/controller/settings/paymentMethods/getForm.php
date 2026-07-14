<?php
$form .= '<form method="POST" action="admin/settings/paymentMethods/edit">';
$form .= '<input type="hidden" name="method_id" value="' . $method["methodId"] . '"/>';
$form .= '<div class="form-group mb-3"><label class="form-label">Method Name</label>
<input type="text"  name="method_name" class="form-control" value="' . $method["methodVisibleName"] . '"/></div>';

$selectedMethodLogo = trim((string) $method["methodLogo"]);
if ($selectedMethodLogo === "") {
    $selectedMethodLogo = site_url("img/admin/payment-methods.svg");
}

$bonusRules = [];
if (!empty($methodExtras["bonus_rules"]) && is_array($methodExtras["bonus_rules"])) {
    $bonusRules = $methodExtras["bonus_rules"];
} else {
    $globalBonusRules = $conn->prepare("SELECT * FROM rates_bonus_rules ORDER BY range_from ASC, id ASC");
    $globalBonusRules->execute();
    $bonusRules = $globalBonusRules->fetchAll(PDO::FETCH_ASSOC);
}

if (!count($bonusRules)) {
    $bonusRules = [
        [
            "range_from" => 0,
            "range_to" => 99,
            "bonus_percent" => 0,
            "is_active" => 1
        ]
    ];
}

$form .= '<div class="form-group mb-3"><label class="form-label">Icon</label>';
$form .= '<input type="hidden" name="method_logo" id="payment_method_logo" value="' . htmlspecialchars($selectedMethodLogo, ENT_QUOTES, "UTF-8") . '"/>';
$form .= '<div class="payment-method-logo-preview mb-3"><img id="payment_method_logo_preview" src="' . htmlspecialchars($selectedMethodLogo, ENT_QUOTES, "UTF-8") . '" alt="Payment method icon" style="max-width: 160px; max-height: 52px; object-fit: contain;"></div>';
$form .= '<div class="row g-2" id="payment_method_logo_grid">';
if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
    foreach ($uploadedFiles as $file) {
        $fileLink = trim((string) $file["link"]);
        if ($fileLink === "") {
            continue;
        }

        $isActive = $fileLink === $selectedMethodLogo ? " is-active" : "";
        $form .= '<div class="col-6 col-md-4 col-lg-3">';
        $form .= '<button type="button" class="payment-method-logo-option' . $isActive . '" data-logo="' . htmlspecialchars($fileLink, ENT_QUOTES, "UTF-8") . '" onclick="selectPaymentMethodLogo(this)" style="width:100%;min-height:74px;border:1px solid #d8dee9;border-radius:12px;background:#fff;padding:10px;display:flex;align-items:center;justify-content:center;transition:all .15s ease;">';
        $form .= '<img src="' . htmlspecialchars($fileLink, ENT_QUOTES, "UTF-8") . '" alt="Uploaded icon" style="max-width:100%;max-height:48px;object-fit:contain;">';
        $form .= '</button>';
        $form .= '</div>';
    }
}
$form .= '</div>';
$form .= '<div class="form-text mt-2">Pick an uploaded image or upload a new one below.</div>';
$form .= '<div class="mt-3"><label class="form-label">Upload New Icon</label><input type="file" class="form-control" id="payment_method_logo_upload" accept="image/*" onchange="uploadPaymentMethodLogo(this)"></div>';
$form .= '<style>.payment-method-logo-option.is-active{border-color:#0d6efd !important;box-shadow:0 0 0 2px rgba(13,110,253,.15);}</style>';
$form .= '</div>';

$methodShortName = htmlspecialchars($method["methodShortName"] ?? "", ENT_QUOTES, "UTF-8");
$form .= '<div class="form-group mb-3"><label class="form-label">ShortName</label>
<input type="text" name="method_short_name" class="form-control" value="' . $methodShortName . '" placeholder="e.g. bKash, Nagad, Binance"/></div>';

if (!in_array($method["methodId"], $manualMethods)) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Minimum Amount</label>
<input type="text"  name="method_min" class="form-control" value="' . $method["methodMin"] . '"/></div>';

    $form .= '<div class="form-group mb-3"><label class="form-label">Maximum Amount</label>
<input type="text"  name="method_max" class="form-control" value="' . $method["methodMax"] . '"/></div>';


    $form .= '<div class="form-group mb-3"><label class="form-label">Fee Percentage</label><div class="input-group">
<input type="number" class="form-control" name="method_fee" step="0.01" value="' . $method["methodFee"] . '">
<span class="input-group-text"><i class="bi bi-percent"></i></span>
</div></div>';

$form .= '<div class="form-group mb-3"><label class="form-label">Bonus Percentage</label><div class="input-group">
<input type="number" class="form-control" name="method_bonus" step="0.01" value="' . $method["methodBonusPercentage"] . '">
<span class="input-group-text"><i class="bi bi-percent"></i></span>
</div></div>';

$form .= '<div class="form-group mb-3"><label class="form-label">Gateway Rate Settings</label><div class="table-responsive"><table class="table table-bordered align-middle method-rates-table"><thead><tr><th style="width: 22%">Range From</th><th style="width: 22%">Range To</th><th style="width: 22%">Bonus %</th><th style="width: 14%">Active</th><th style="width: 20%" class="text-end">Action</th></tr></thead><tbody id="method_bonus_rules_body">';
foreach ($bonusRules as $index => $bonusRule) {
    $rangeFrom = isset($bonusRule["range_from"]) ? htmlspecialchars($bonusRule["range_from"]) : "";
    $rangeTo = isset($bonusRule["range_to"]) && $bonusRule["range_to"] !== null ? htmlspecialchars($bonusRule["range_to"]) : "";
    $bonusPercent = isset($bonusRule["bonus_percent"]) ? htmlspecialchars($bonusRule["bonus_percent"]) : "";
    $isActive = !isset($bonusRule["is_active"]) || intval($bonusRule["is_active"]) === 1;
    $form .= '<tr class="method-rate-row">';
    $form .= '<td><input type="number" step="0.01" min="0" class="form-control" name="method_bonus_rules[' . $index . '][range_from]" value="' . $rangeFrom . '"></td>';
    $form .= '<td><input type="number" step="0.01" min="0" class="form-control" name="method_bonus_rules[' . $index . '][range_to]" value="' . $rangeTo . '" placeholder="Optional"></td>';
    $form .= '<td><input type="number" step="0.01" min="0" class="form-control" name="method_bonus_rules[' . $index . '][bonus_percent]" value="' . $bonusPercent . '"></td>';
    $form .= '<td><select class="form-select" name="method_bonus_rules[' . $index . '][is_active]"><option value="1"' . ($isActive ? ' selected' : '') . '>Active</option><option value="0"' . (!$isActive ? ' selected' : '') . '>Inactive</option></select></td>';
    $form .= '<td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm" data-rate-row-remove="1">Remove</button></td>';
    $form .= '</tr>';
}
$form .= '</tbody></table></div>';
$form .= '<div class="d-flex gap-2 flex-wrap mt-3"><button type="button" class="btn btn-outline-secondary btn-sm" id="addMethodRateRow">Add Row</button><span class="text-muted small align-self-center">These rules apply only to this payment method.</span></div></div>';

$form .= '<div class="form-group mb-3"><label class="form-label">Bonus Start Amount</label>
<input type="number"  name="method_bonus_start_amount" class="form-control" value="' . $method["methodBonusStartAmount"] . '"/></div>';
}

$form .= '<div class="form-group mb-3"><label class="form-label">Bonus Enabled</label><select name="method_bonus_enabled" class="form-select">';
$form .= '<option value="1"';
if (!isset($method["methodBonusEnabled"]) || $method["methodBonusEnabled"] == "1") {
    $form .= ' selected';
}
$form .= '>Enabled</option>';
$form .= '<option value="0"';
if (isset($method["methodBonusEnabled"]) && $method["methodBonusEnabled"] == "0") {
    $form .= ' selected';
}
$form .= '>Disabled</option>';
$form .= '</select></div>';

$dollarRateConversionEnabled = isset($method["dollarRateConversionEnabled"]) ? intval($method["dollarRateConversionEnabled"]) : 1;
$form .= '<div class="form-group mb-3"><label class="form-label">Dollar Rate Conversion</label><select name="dollar_rate_conversion_enabled" class="form-select">';
$form .= '<option value="1"' . ($dollarRateConversionEnabled === 1 ? ' selected' : '') . '>Enabled</option>';
$form .= '<option value="0"' . ($dollarRateConversionEnabled === 0 ? ' selected' : '') . '>Disabled</option>';
$form .= '</select></div>';

// --- Currency mapping, exchange rate, and quick amounts (automatic methods only) ---
if (!in_array($method["methodId"], $manualMethods)) {
    $currencies = get_currencies_array("enabled");
    $fromCurrency = htmlspecialchars($method["methodCurrency"] ?? "", ENT_QUOTES, "UTF-8");
    $toCurrency = htmlspecialchars($methodExtras["to_currency"] ?? "", ENT_QUOTES, "UTF-8");

    // From Currency
    $form .= '<div class="form-group mb-3"><label class="form-label">From Currency</label>';
    $form .= '<select name="methodFromCurrency" class="form-select">';
    $form .= '<option value="">-- Select --</option>';
    foreach ($currencies as $code => $currencyData) {
        $selected = $code === $fromCurrency ? ' selected' : '';
        $form .= '<option value="' . htmlspecialchars($code, ENT_QUOTES, "UTF-8") . '"' . $selected . '>' . htmlspecialchars($code, ENT_QUOTES, "UTF-8") . '</option>';
    }
    $form .= '</select></div>';

    // To Currency
    $form .= '<div class="form-group mb-3"><label class="form-label">To Currency</label>';
    $form .= '<select name="methodToCurrency" class="form-select">';
    $form .= '<option value="">-- Select --</option>';
    foreach ($currencies as $code => $currencyData) {
        $selected = $code === $toCurrency ? ' selected' : '';
        $form .= '<option value="' . htmlspecialchars($code, ENT_QUOTES, "UTF-8") . '"' . $selected . '>' . htmlspecialchars($code, ENT_QUOTES, "UTF-8") . '</option>';
    }
    $form .= '</select></div>';

    // Exchange Rate
    $exchangeRate = htmlspecialchars($methodExtras["exchange_rate"] ?? "", ENT_QUOTES, "UTF-8");
    $form .= '<div class="form-group mb-3"><label class="form-label">Exchange Rate <small class="text-muted">[1 FromCurrency = ? ToCurrency]</small></label>
    <input type="text" name="method_exchange_rate" class="form-control" value="' . $exchangeRate . '" placeholder="e.g. 120"/></div>';

    // Quick Amount Buttons
    $quickAmounts = isset($methodExtras["quick_amounts"]) && is_array($methodExtras["quick_amounts"])
        ? implode(", ", $methodExtras["quick_amounts"])
        : "1, 5, 10, 20, 50, 100, 200, 300, 500, 1000, 2000, 3000, 5000, 10000, 50000";
    $form .= '<div class="form-group mb-3"><label class="form-label">Quick Amount Presets</label>
    <input type="text" name="method_quick_amounts" class="form-control" value="' . htmlspecialchars($quickAmounts, ENT_QUOTES, "UTF-8") . '" placeholder="e.g. 10, 50, 100, 500"/>
    <div class="form-text">Comma-separated deposit amounts shown as one-click buttons on checkout. Leave empty for defaults.</div></div>';
}

$form .= '<div class="form-group mb-3"><label class="form-label">Status</label><select name="method_status" class="form-select">';
$form .= '<option value="1"';
if ($method["methodStatus"] == "1") {
    $form .= ' selected';
}
$form .= '>Active</option>';

$form .= '<option value="0"';
if ($method["methodStatus"] == "0") {
    $form .= ' selected';
}
$form .= '>Inactive</option>';
$form .= '</select></div>';

$form .= '<div class="form-group mb-3"><label class="form-label">Instructions</label><div id="editor" name="method_instructions" class="extraContents">' . htmlspecialchars_decode($method["methodInstructions"]) . '</div></div>';

if ($method["methodId"] == 1) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Merchant ID</label>
    <input type="text"  name="merchantId" class="form-control" value="' . $methodExtras["merchantId"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Merchant Key</label>
    <input type="text"  name="merchantKey" class="form-control" value="' . $methodExtras["merchantKey"] . '"/></div>';
}

if ($method["methodId"] == 2) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Merchant ID</label>
    <input type="text"  name="merchantId" class="form-control" value="' . $methodExtras["merchantId"] . '"/></div>';
}

if ($method["methodId"] == 3) {
    $form .= '<div class="form-group mb-3"><label class="form-label">USD ID</label>
    <input type="text"  name="accountNumber" class="form-control" value="' . $methodExtras["accountNumber"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Alternate Pass Phrase</label>
    <input type="text"  name="alternatePassPhrase" class="form-control" value="' . $methodExtras["alternatePassPhrase"] . '"/></div>';
}

if ($method["methodId"] == 4) {
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="APIKey" class="form-control" value="' . $methodExtras["APIKey"] . '"/></div>';
}

if ($method["methodId"] == 5) {
    $form .= '<div class="form-group mb-3"><label class="form-label">MID</label>
    <input type="text"  name="MID" class="form-control" value="' . $methodExtras["MID"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="APIKey" class="form-control" value="' . $methodExtras["APIKey"] . '"/></div>';

    $form .= '<div class="form-group mb-3"><label class="form-label">Mode</label><select name="mode" class="form-select">';
    $form .= '<option value="live"';
    if ($method["methodStatus"] == "live") {
        $form .= ' selected';
    }
    $form .= '>Live</option>';

    $form .= '<option value="test"';
    if ($method["methodStatus"] == "test") {
        $form .= ' selected';
    }
    $form .= '>Test</option>';
    $form .= '</select></div>';
}

if ($method["methodId"] == 6) {
    $form .= '<div class="form-group mb-3"><label class="form-label">API Public Key</label>
    <input type="text"  name="APIPublicKey" class="form-control" value="' . $methodExtras["APIPublicKey"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">API Secret Key</label>
    <input type="text"  name="APISecretKey" class="form-control" value="' . $methodExtras["APISecretKey"] . '"/></div>';
}

if ($method["methodId"] == 7) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Gmail Address</label>
    <input type="text"  name="email" class="form-control" value="' . $methodExtras["email"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">App Password</label>
    <input type="text"  name="password" class="form-control" value="' . $methodExtras["password"] . '"/></div>';
}

if ($method["methodId"] == 8) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Gmail Address</label>
    <input type="text"  name="email" class="form-control" value="' . $methodExtras["email"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">App Password</label>
    <input type="text"  name="password" class="form-control" value="' . $methodExtras["password"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Sender Email</label>
    <input type="text"  name="senderEmail" class="form-control" value="' . $methodExtras["senderEmail"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Email Subject</label>
    <input type="text"  name="emailSubject" class="form-control" value="' . $methodExtras["emailSubject"] . '"/></div>';
}

if ($method["methodId"] == 9) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Gmail Address</label>
    <input type="text"  name="email" class="form-control" value="' . $methodExtras["email"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">App Password</label>
    <input type="text"  name="password" class="form-control" value="' . $methodExtras["password"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Sender Email</label>
    <input type="text"  name="senderEmail" class="form-control" value="' . $methodExtras["senderEmail"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Email Subject</label>
    <input type="text"  name="emailSubject" class="form-control" value="' . $methodExtras["emailSubject"] . '"/></div>';
}

if ($method["methodId"] == 10) {
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="APIKey" class="form-control" value="' . $methodExtras["APIKey"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Auth Token</label>
    <input type="text"  name="authToken" class="form-control" value="' . $methodExtras["authToken"] . '"/></div>';
}

if ($method["methodId"] == 11) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Web ID</label>
    <input type="text"  name="webId" class="form-control" value="' . $methodExtras["webId"] . '"/></div>';
}

if ($method["methodId"] == 12) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Application Partner ID</label>
    <input type="text"  name="partnerId" class="form-control" value="' . $methodExtras["partnerId"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Application Private Key</label>
    <input type="text"  name="privateKey" class="form-control" value="' . $methodExtras["privateKey"] . '"/></div>';
}

if ($method["methodId"] == 13) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Merchant Key</label>
    <input type="text"  name="merchantKey" class="form-control" value="' . $methodExtras["merchantKey"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Merchant Salt</label>
    <input type="text"  name="merchantSalt" class="form-control" value="' . $methodExtras["merchantSalt"] . '"/></div>';
}

if ($method["methodId"] == 14) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Production API Token</label>
    <input type="text"  name="productionAPIToken" class="form-control" value="' . $methodExtras["productionAPIToken"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Production Secret Key</label>
    <input type="text"  name="productionAPISecretKey" class="form-control" value="' . $methodExtras["productionAPISecretKey"] . '"/></div>';
}

if ($method["methodId"] == 15) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Merchant ID</label>
    <input type="text"  name="merchantId" class="form-control" value="' . $methodExtras["merchantId"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Public Key</label>
    <input type="text"  name="publicKey" class="form-control" value="' . $methodExtras["publicKey"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Secret Key</label>
    <input type="text"  name="secretKey" class="form-control" value="' . $methodExtras["secretKey"] . '"/></div>';
}

if ($method["methodId"] == 16) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Secret Key</label>
    <input type="text"  name="secretKey" class="form-control" value="' . $methodExtras["secretKey"] . '"/></div>';
}

if ($method["methodId"] == 17) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Publishable Key</label>
    <input type="text"  name="publishableKey" class="form-control" value="' . $methodExtras["publishableKey"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Secret Key</label>
    <input type="text"  name="secretKey" class="form-control" value="' . $methodExtras["secretKey"] . '"/></div>';
}

if ($method["methodId"] == 18) {
    $form .= '<div class="form-group mb-3"><label class="form-label">Shop ID</label>
    <input type="text"  name="shopId" class="form-control" value="' . $methodExtras["shopId"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Secret Key</label>
    <input type="text"  name="secretKey" class="form-control" value="' . $methodExtras["secretKey"] . '"/></div>';
}

if ($method["methodId"] == 19) {
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="api_key" class="form-control" value="' . $methodExtras["api_key"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">API URL</label>
    <input type="text"  name="api_url" class="form-control" value="' . $methodExtras["api_url"] . '"/></div>';
}

if ($method["methodId"] == 20) { // Uddoktapay Bangladeshi Payment
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="api_key" class="form-control" value="' . $methodExtras["api_key"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">API URL</label>
    <input type="text"  name="api_url" class="form-control" value="' . $methodExtras["api_url"] . '"/></div>';
}

if($method["methodId"] == 21) { // bKash Merchant Payment
    $form .= '<div class="form-group mb-3"><label class="form-label">Username</label>
    <input type="text"  name="username" class="form-control" value="' . $methodExtras["username"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">Password</label>
    <input type="text"  name="password" class="form-control" value="' . $methodExtras["password"] . '"/></div>';
       $form .= '<div class="form-group mb-3"><label class="form-label">App Key</label>
    <input type="text"  name="app_key" class="form-control" value="' . $methodExtras["app_key"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">App Secret</label>
    <input type="text"  name="app_secret_key" class="form-control" value="' . $methodExtras["app_secret_key"] . '"/></div>';
}

if ($method["methodId"] == 22) { // Uddoktapay Global Payment
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="api_key" class="form-control" value="' . $methodExtras["api_key"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">API URL</label>
    <input type="text"  name="api_url" class="form-control" value="' . $methodExtras["api_url"] . '"/></div>';
}

if($method["methodId"] == 24) { // Binance auto pay 
    $form .= '<div class="form-group mb-3"><label class="form-label">Secret Key</label>
    <input type="text"  name="binance_pay_secret" class="form-control" value="' . $methodExtras["binance_pay_secret"] . '"/></div>';
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="binance_pay_key" class="form-control" value="' . $methodExtras["binance_pay_key"] . '"/></div>';
       $form .= '<div class="form-group mb-3"><label class="form-label">QR URL</label>
    <input type="text"  name="qr" class="form-control" value="' . $methodExtras["qr"] . '"/></div>';
     $form .= '<div class="form-group mb-3"><label class="form-label">Binance Id</label>
    <input type="text"  name="id" class="form-control" value="' . $methodExtras["id"] . '"/></div>';
}

if ($method["methodId"] == 41) { // Alphapaybd Bangladeshi Payment
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="api_key" class="form-control" value="' . $methodExtras["api_key"] . '"/></div>';
																					
																											  
}

if ($method["methodId"] == 42) { // Alphapaybd Banince auto Pay
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="api_key" class="form-control" value="' . $methodExtras["api_key"] . '"/></div>';
																					
																											  
}

if ($method["methodId"] == 69) { // Nagorikpay
    $form .= '<div class="form-group mb-3"><label class="form-label">API Key</label>
    <input type="text"  name="api_key" class="form-control" value="' . $methodExtras["api_key"] . '"/></div>';
																					
																											  
}

$form .= '<div class="custom-modal-footer"><button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>&nbsp;&nbsp;<button type="submit" data-loading-text="Updating..." class="btn btn-primary">Save Changes</button></div></form>';
