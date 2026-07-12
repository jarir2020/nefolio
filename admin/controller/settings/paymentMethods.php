<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if ($_GET["action"] == "getData") {
        $paymentMethods = $conn->prepare("SELECT methodId, methodLogo, methodVisibleName, methodMin, methodMax, methodStatus FROM paymentmethods ORDER BY methodPosition ASC");
        $paymentMethods->execute();
        $paymentMethods = $paymentMethods->fetchAll(PDO::FETCH_ASSOC);
        $defaultMethodLogo = site_url("img/admin/payment-methods.svg");
        $cachePaymentMethodLogo = function ($logo) {
            $logo = trim((string) $logo);
            if ($logo === "") {
                return "";
            }

            if (!preg_match('#^https?://#i', $logo)) {
                return $logo;
            }

            $cacheDir = $_SERVER["DOCUMENT_ROOT"] . "/img/files/payment-method-logos";
            if (!is_dir($cacheDir)) {
                @mkdir($cacheDir, 0775, true);
            }

            $parsedUrl = parse_url($logo);
            $extension = "png";
            if (!empty($parsedUrl["path"])) {
                $pathInfo = pathinfo($parsedUrl["path"]);
                if (!empty($pathInfo["extension"]) && preg_match('/^[a-z0-9]+$/i', $pathInfo["extension"])) {
                    $extension = strtolower($pathInfo["extension"]);
                }
            }

            $cacheFileName = md5($logo) . "." . $extension;
            $cacheFilePath = $cacheDir . "/" . $cacheFileName;
            $cacheWebPath = "/img/files/payment-method-logos/" . $cacheFileName;

            if (!file_exists($cacheFilePath)) {
                $remoteData = @file_get_contents($logo);
                if ($remoteData !== false && $remoteData !== "") {
                    @file_put_contents($cacheFilePath, $remoteData);
                } else {
                    return $logo;
                }
            }

            return $cacheWebPath;
        };
        $normalizeMethodLogo = function ($logo) use ($cachePaymentMethodLogo) {
            $logo = trim((string) $logo);
            if ($logo === "") {
                return site_url("img/admin/payment-methods.svg");
            }

            if (preg_match('#^https?://#i', $logo) || strpos($logo, "/") === 0) {
                return $cachePaymentMethodLogo($logo);
            }

            return site_url(ltrim($logo, "/"));
        };
        $methods = [];
        for ($i = 0; $i < count($paymentMethods); $i++) {
            $methodLogo = trim((string) $paymentMethods[$i]["methodLogo"]);
            $methodExtras = json_decode($paymentMethods[$i]["methodExtras"], true);
            $bonusRules = isset($methodExtras["bonus_rules"]) && is_array($methodExtras["bonus_rules"]) ? $methodExtras["bonus_rules"] : [];
            $methods[] = [
                "id" => $paymentMethods[$i]["methodId"],
                "name" => $paymentMethods[$i]["methodVisibleName"],
                "logo" => $normalizeMethodLogo($methodLogo !== "" ? $methodLogo : $defaultMethodLogo),
                "min" => $paymentMethods[$i]["methodMin"],
                "max" => $paymentMethods[$i]["methodMax"],
                "status" => $paymentMethods[$i]["methodStatus"],
                "bonus_rules" => $bonusRules
            ];
        }
        header("Content-Type: application/json");
        echo json_encode($methods);
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $manualMethods = [
        100,
        101,
        102,
        103,
        104,
        105,
        106,
        107,
        108,
        109
    ];

    if (route(3) == "getForm") {
        $methodId = intval($_POST["methodId"]);
        $response = [];
        $method = $conn->prepare("SELECT * FROM paymentmethods WHERE methodId=:id");
        $method->execute([
            "id" => $methodId
        ]);

        if ($method->rowCount()) {
            $method = $method->fetch(PDO::FETCH_ASSOC);
            $methodExtras = json_decode($method["methodExtras"], 1);
            $uploadedFiles = $conn->prepare("SELECT id, link FROM files ORDER BY date DESC");
            $uploadedFiles->execute();
            $uploadedFiles = $uploadedFiles->fetchAll(PDO::FETCH_ASSOC);
            require_once("paymentMethods/getForm.php");
            $response = [
                "success" => true,
                "content" => $form
            ];

            header("Content-Type: application/json");
            echo json_encode($response);

        } else {
            errorExit("This payment method doesn't exist.");
        }

    }
    if (route(3) == "edit") {
        $response = [];
        require_once("paymentMethods/edit.php");

        echo json_encode($response);
    }

    if (route(3) == "activate") {
        $update = $conn->prepare("UPDATE paymentmethods SET methodStatus=:status WHERE methodId=:id");
        $update->execute([
            "status" => 1,
            "id" => intval($_POST["methodId"])
        ]);
    }
    if (route(3) == "deactivate") {
        $update = $conn->prepare("UPDATE paymentmethods SET methodStatus=:status WHERE methodId=:id");
        $update->execute([
            "status" => 0,
            "id" => intval($_POST["methodId"])
        ]);
    }

    if (route(3) == "sort") {
        $sortData = json_decode(base64_decode($_POST["sortData"]), 1);
        for ($i = 0; $i < count($sortData); $i++) {
            $methodPos = $i + 1;
            $methodId = intval($sortData[$i]);
            $update = $conn->prepare("UPDATE paymentmethods SET methodPosition=:position WHERE methodId=:id");
            $update->execute([
                "position" => $methodPos,
                "id" => $methodId
            ]);
        }
    }

    exit;
}
