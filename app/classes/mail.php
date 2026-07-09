<?php

if (!defined('BASEPATH')) {
    die('Direct access to the script is not allowed');
}
use PHPMailer\PHPMailer\PHPMailer;
$mail = new PHPMailer;
function sendMail($arr)
{
    global $conn, $settings, $mail;

    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = $settings["smtp_server"];
    $mail->SMTPAuth = true;
    $mail->Username = $settings["smtp_user"];
    $mail->Password = $settings["smtp_pass"];
    $mail->SMTPSecure = $settings["smtp_protocol"];
    $mail->Port = $settings["smtp_port"];
    $mail->SetLanguage("tr", "phpmailer/language");
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
    $mail->setFrom($settings["smtp_user"], $settings["site_title"]);

    if (is_array($arr["mail"])) {
        foreach ($arr["mail"] as $goMail) {
            $mail->ClearAddresses();
            $mail->addAddress($goMail);
            $mail->isHTML(true);
            $mail->Subject = $arr["subject"];
            $mail->Body = $arr["body"];
            if (!$mail->send()) {
                return false;
            }
        }
    } else {
        $mail->addAddress($arr["mail"]);
        $mail->isHTML(true);
        $mail->Subject = $arr["subject"];
        $mail->Body = $arr["body"];
        if (!$mail->send()) {
            return false;
        }
    }

    return true;
}
