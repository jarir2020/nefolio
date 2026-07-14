<?php
$form .= '<form method="POST" action="admin/settings/paymentMethods/edit" class="payment-method-editor">';
$form .= '<input type="hidden" name="method_id" value="' . $method["methodId"] . '"/>';
$form .= '<div class="payment-method-editor__hero">';
$form .= '<div class="payment-method-editor__hero-title">Edit payment method</div>';
$form .= '<div class="payment-method-editor__hero-text">Match the payment gateway design, keep all existing save logic, and manage icons without leaving the modal.</div>';
$form .= '</div>';
$form .= '<div class="payment-method-editor__card payment-method-editor__card--compact">';
$form .= '<div class="form-group mb-0"><label class="form-label">Method Name</label><input type="text" name="method_name" class="form-control" value="' . $method["methodVisibleName"] . '"/></div>';
$form .= '</div>';

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

$form .= '<div class="payment-method-editor__card">';
$form .= '<div class="payment-method-editor__card-title">Select Icon</div>';
$form .= '<input type="hidden" name="method_logo" id="payment_method_logo" value="' . htmlspecialchars($selectedMethodLogo, ENT_QUOTES, "UTF-8") . '"/>';
$form .= '<input type="hidden" name="payment_method_deleted_files" id="payment_method_deleted_files" value=""/>';
$currentSelectedFileId = "";
$defaultMethodLogo = site_url("img/admin/payment-methods.svg");
if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
    foreach ($uploadedFiles as $file) {
        if (trim((string) ($file["link"] ?? "")) === $selectedMethodLogo) {
            $currentSelectedFileId = intval($file["id"]);
            break;
        }
    }
}
$form .= '<input type="hidden" name="payment_method_current_file_id" id="payment_method_current_file_id" value="' . htmlspecialchars((string) $currentSelectedFileId, ENT_QUOTES, "UTF-8") . '"/>';
$form .= '<div class="payment-method-logo-preview mb-3">';
$form .= '<button type="button" class="payment-method-logo-delete" onclick="deleteCurrentPaymentMethodLogo()" aria-label="Delete selected icon">&times;</button>';
$form .= '<img id="payment_method_logo_preview" src="' . htmlspecialchars($selectedMethodLogo, ENT_QUOTES, "UTF-8") . '" alt="Payment method icon" onerror="this.onerror=null;this.src=\'' . htmlspecialchars($defaultMethodLogo, ENT_QUOTES, "UTF-8") . '\';">';
$form .= '</div>';
$form .= '<div class="payment-method-logo-grid" id="payment_method_logo_grid" data-default-logo="' . htmlspecialchars($defaultMethodLogo, ENT_QUOTES, "UTF-8") . '">';
if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
    foreach ($uploadedFiles as $file) {
        $fileId = intval($file["id"]);
        $fileLink = trim((string) $file["link"]);
        if ($fileLink === "") {
            continue;
        }

        $isActive = $fileLink === $selectedMethodLogo ? " is-active" : "";
        $form .= '<div class="payment-method-logo-option-wrap" data-file-id="' . $fileId . '" data-file-link="' . htmlspecialchars($fileLink, ENT_QUOTES, "UTF-8") . '">';
        $form .= '<button type="button" class="payment-method-logo-delete" data-file-id="' . $fileId . '" data-file-link="' . htmlspecialchars($fileLink, ENT_QUOTES, "UTF-8") . '" onclick="deletePaymentMethodLogoFile(this)" aria-label="Delete uploaded icon">&times;</button>';
        $form .= '<button type="button" class="payment-method-logo-option' . $isActive . '" data-logo="' . htmlspecialchars($fileLink, ENT_QUOTES, "UTF-8") . '" data-file-id="' . $fileId . '" onclick="selectPaymentMethodLogo(this)">';
        $form .= '<img src="' . htmlspecialchars($fileLink, ENT_QUOTES, "UTF-8") . '" alt="Uploaded icon" onerror="this.onerror=null;this.src=\'' . htmlspecialchars($defaultMethodLogo, ENT_QUOTES, "UTF-8") . '\';">';
        $form .= '</button>';
        $form .= '</div>';
    }
}
$form .= '</div>';
$form .= '<div class="payment-method-logo-help">Pick an uploaded image or upload a new one below. This logo is shown in the add-funds gateway list.</div>';
$form .= '<div class="mt-3"><label class="form-label">Upload New Icon</label><input type="file" class="form-control" id="payment_method_logo_upload" accept="image/*" multiple onchange="uploadPaymentMethodLogo(this)"></div>';
$form .= '</div>';
$form .= '<div class="payment-method-editor__card payment-method-editor__card--stack">';

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

$form .= '<div class="custom-modal-footer"><button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>&nbsp;&nbsp;<button type="submit" data-loading-text="Updating..." class="btn btn-primary">Save Changes</button></div>';
$form .= '</div></form>';
$form .= '<style>
.payment-method-editor{display:flex;flex-direction:column;gap:16px}
.payment-method-editor__hero{padding:0 2px 2px}
.payment-method-editor__hero-title{font-size:26px;font-weight:800;line-height:1.2;color:#111827}
.payment-method-editor__hero-text{margin-top:6px;color:#6b7280;font-size:14px;line-height:1.6}
.payment-method-editor__card{background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:18px;box-shadow:0 10px 30px rgba(15,23,42,.05)}
.payment-method-editor__card--compact{padding:16px 18px}
.payment-method-editor__card--stack{display:flex;flex-direction:column;gap:16px}
.payment-method-editor__card-title{font-size:16px;font-weight:800;color:#111827;margin-bottom:14px}
.payment-method-editor .form-label{font-weight:700;color:#111827;margin-bottom:.45rem}
.payment-method-editor .form-control,.payment-method-editor .form-select,.payment-method-editor .input-group-text{border-color:#d7dce5;border-radius:12px}
.payment-method-editor .input-group .form-control{border-top-right-radius:0;border-bottom-right-radius:0}
.payment-method-editor .input-group .input-group-text{border-top-left-radius:0;border-bottom-left-radius:0}
.payment-method-editor .form-group{margin-bottom:0}
.payment-method-logo-preview{position:relative;min-height:124px;border:1px solid #e5e7eb;border-radius:16px;background:linear-gradient(180deg,#f8fafc 0,#fff 100%);display:flex;align-items:center;justify-content:center;padding:18px}
.payment-method-logo-preview img{max-width:100%;max-height:72px;object-fit:contain}
.payment-method-logo-delete{position:absolute;top:10px;right:10px;width:30px;height:30px;border:none;border-radius:999px;background:#ef4444;color:#fff;font-size:18px;line-height:30px;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(239,68,68,.28);cursor:pointer}
.payment-method-logo-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(118px,1fr));gap:10px;max-height:260px;overflow:auto;padding-right:2px}
.payment-method-logo-option-wrap{position:relative}
.payment-method-logo-option{width:100%;min-height:78px;border:1px solid #e5e7eb;border-radius:14px;background:#fff;padding:10px;display:flex;align-items:center;justify-content:center;transition:all .15s ease}
.payment-method-logo-option img{max-width:100%;max-height:46px;object-fit:contain}
.payment-method-logo-option.is-active{border-color:#2563eb !important;box-shadow:0 0 0 2px rgba(37,99,235,.12)}
.payment-method-logo-option-wrap:hover .payment-method-logo-option{transform:translateY(-1px)}
.payment-method-logo-help{font-size:13px;color:#6b7280;line-height:1.5;margin-top:10px}
.payment-method-editor .custom-modal-footer{display:flex;justify-content:flex-end;gap:12px;padding-top:2px}
.payment-method-editor .custom-modal-footer .btn{min-width:160px}
.payment-method-editor .row.g-2, .payment-method-editor .row.g-3{--bs-gutter-x:1rem;--bs-gutter-y:1rem}
.payment-method-editor .payment-method-editor__card + .payment-method-editor__card{margin-top:-2px}
@media (max-width: 991px){.payment-method-editor__hero-title{font-size:22px}}
@media (max-width: 767px){.payment-method-editor .custom-modal-footer{flex-direction:column}.payment-method-editor .custom-modal-footer .btn{width:100%;min-width:0}.payment-method-logo-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.payment-method-logo-preview{min-height:108px}}
</style>';
