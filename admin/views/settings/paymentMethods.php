<?php
$paymentMethodsList = $paymentMethodsList ?? [];
$defaultMethodLogo = site_url("img/admin/payment-methods.svg");
?>
<div class="container-fluid margin-top-container">
    <div class="row mb-3">
        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" id="payment_methods_search" class="form-control" placeholder="Search payment methods...">
            </div>
        </div>
    </div>

    <div id="paymentMethods" class="row g-3 page-content">
        <?php if (!empty($paymentMethodsList) && is_array($paymentMethodsList)): ?>
            <?php foreach ($paymentMethodsList as $index => $method): ?>
                <?php
                $methodId = intval($method["methodId"]);
                $methodName = trim((string) ($method["methodVisibleName"] ?? $method["methodName"] ?? "Payment Method"));
                $methodLogo = trim((string) ($method["methodLogo"] ?? ""));
                if ($methodLogo === "") {
                    $methodLogo = $defaultMethodLogo;
                }
                $methodMin = $method["methodMin"] ?? "";
                $methodMax = $method["methodMax"] ?? "";
                $methodStatus = intval($method["methodStatus"] ?? 0);
                $circle = $methodStatus === 1 ? "green-circle" : "red-circle";
                ?>
                <div class="payment-card col-12 col-md-6 col-xl-4" data-method-id="<?= $methodId ?>" data-method-position="<?= $index + 1 ?>">
                    <div data-method-id="<?= $methodId ?>" class="method-status <?= $circle ?>"></div>
                    <div class="method-sort-handle">=</div>
                    <div class="method-logo">
                        <img width="120" height="30" src="<?= htmlspecialchars($methodLogo, ENT_QUOTES, "UTF-8") ?>" onerror="this.onerror=null;this.src='<?= htmlspecialchars($defaultMethodLogo, ENT_QUOTES, "UTF-8") ?>';" alt="method Logo">
                    </div>
                    <div class="method_name"><?= htmlspecialchars($methodName, ENT_QUOTES, "UTF-8") ?></div>
                    <div class="method_min_max">
                        <span class="min"><?= htmlspecialchars((string) $methodMin, ENT_QUOTES, "UTF-8") ?></span>-<span class="max"><?= htmlspecialchars((string) $methodMax, ENT_QUOTES, "UTF-8") ?></span>
                    </div>
                    <div class="vertical-line"></div>
                    <div class="actions">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-form="edit_payment_method" data-method-id="<?= $methodId ?>" data-bs-target="#staticBackdropModal">Edit</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info mb-0">No payment methods found.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
