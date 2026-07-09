<?php 
if (!defined('ADDFUNDS')) {
    http_response_code(404);
    die();
}

$amountField = '<div class="form-group">
<label class="control-label">Amount</label>
<input type="number" id="paymentAmount" class="form-control" name="payment_amount" step="0.01" required />
</div>';
$feeField = '<div id="fee_fields"></div>';
$paymentBtn = '<button type="submit" class="btn btn-primary d-block w-100">[text]</button>';

if($selectedMethod == 1){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 2){
    $formData .= '<div class="form-group">
    <label class="control-label">Order ID</label>
    <input type="text" class="form-control" name="payTMOrderId"  required />
    </div>';
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Verify Transaction");
}

if($selectedMethod == 3){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 4){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 5){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 6){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 7){
    $formData .= '<div class="form-group">
    <label class="control-label">Transaction ID</label>
    <input type="text" class="form-control" name="PhonePeTransactionId"  required />
    </div>';
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Verify Transaction");
}

if($selectedMethod == 8){
    $formData .= '<div class="form-group">
    <label class="control-label">Transaction ID</label>
    <input type="text" class="form-control" name="EasypaisaTransactionId"  required />
    </div>';
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Verify Transaction");
}

if($selectedMethod == 9){
    $formData .= '<div class="form-group">
    <label class="control-label">Transaction ID</label>
    <input type="text" class="form-control" name="JazzcashTransactionId"  required />
    </div>';
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Verify Transaction");
}

if($selectedMethod == 10){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}


if($selectedMethod == 11){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 12){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 13){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 14){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 15){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 16){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 17){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 18){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 19){
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 20){ // Uddoktapay
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if ($selectedMethod == 21) { // bKash merchant 
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn, "Start Payment");
}

if($selectedMethod == 22){ // Uddoktapay
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 24){ // binance auto pay 
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if ($selectedMethod == 41) { // Alphapaybd 
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn, "Start Payment");
}

if ($selectedMethod == 42) { // Alphapaybd 
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn, "Start Payment");
}

if($selectedMethod == 43) { // cryptomus
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if ($selectedMethod == 44) {  // Heleket Gateway
    $formData .= $amountField;
    $formData .= $feeField;
    // إذا يحتاج Transaction ID أو Order ID تضيفه هنا
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if($selectedMethod == 45) { // Bybit 
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn,"Start Payment");
}

if ($selectedMethod == 46) { // nexsopay 
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn, "Start Payment");
}

if ($selectedMethod == 69) { // Nagorikpay
    $formData .= $amountField;
    $formData .= $feeField;
    $formData .= replaceText($paymentBtn, "Start Payment");
}

if ($selectedMethod == 99) { // manually
    $formData .= '<div class="form-group mb-3"><label class="control-label">Methods Name</label><input type="text" name="payment_sender" class="form-control" placeholder="Enter the Methods Name" required></div>';
    $formData .= '<div class="form-group mb-3"><label class="control-label">Amount (USD)</label><input type="number" name="payment_amount" class="form-control" step="0.01" placeholder="Enter the amount you sent" required></div>';
    $formData .= '<div class="form-group mb-3"><label class="control-label">Transaction ID</label><input type="text" name="trx_id" class="form-control" placeholder="Enter Transaction ID" required></div>';
    $formData .= replaceText($paymentBtn, "Submit Payment");
}
