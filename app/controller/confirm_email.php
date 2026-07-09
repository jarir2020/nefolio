<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}

$title .= "Confirm Email";

// User session validation
if($_SESSION["msmbilisim_userlogin"] != 1 || $user["client_type"] == 1){
    Header("Location:".site_url('logout'));
    exit();
}

// Email confirmation check
if($settings["email_confirmation"] == 1 && $user["email_type"] == 1) {
    // Uncomment this line if redirection is needed when email is not confirmed
    // Header("Location:".site_url(''));
}

if(route(1) == "resend") {
    $email = route(2);
    
    $stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = :id");
    $stmt->execute(array("id" => $user["client_id"]));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $code = $user['apikey'];
    $max = $user["resend_max"] + 3;
    
    // Update resend_max field
    $update = $conn->prepare("UPDATE clients SET resend_max = :max WHERE client_id = :id");
    $update->execute(array("max" => $max, "id" => $user["client_id"]));
    
    // Send confirmation email
    $message = "Please confirm your email address. Click the link below: " . site_url() . "confirm_email/activate/$code";
    $site_name = site_url();
    $to = $user['email'];
    $from = $settings["smtp_user"];
    $subject = 'Email Confirmation';
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
    $headers .= "From: $site_name <$from>" . "\r\n";
    $headers .= "Cc: $from" . "\r\n";
    $headers .= "Bcc: $from" . "\r\n";
    
    $send = sendMail($to, $subject, $message);
    
    if($send) {
        $success = 1;
        $successText = "A confirmation email has been sent";
        echo '<script>setTimeout(function(){window.location="'.site_url().'"},2000)</script>';
    } else {
        $error = 1;
        $errorText = "Failed to send confirmation email";
    }
}

if(route(1) == "activate") {
    $code = route(2);
    
    // Update email_type to mark the email as confirmed
    $update = $conn->prepare("UPDATE clients SET email_type = :type WHERE apikey = :key");
    $update->execute(array("type" => 2, "key" => $code));
    
    Header("Location:".site_url(''));
    exit();
}

if($_POST) {
    foreach ($_POST as $key => $value) {
        $_SESSION["data"][$key] = $value;
    }

    $pass = $_POST["password"];
    $newemail = $_POST["newemail"];
    
    if(empty($newemail)) {
        $error = 1;
        $errorText = "Please enter a valid email";
    } elseif(empty($pass)) {
        $error = 1;
        $errorText = "Please enter your password";
    } elseif(userdata_check("email", $newemail)) {
        $error = 1;
        $errorText = "Email is already registered";
    } elseif(!userlogin_check($user["username"], $pass)) {
        $error = 1;
        $errorText = "Incorrect password";
    } else {
        // Update email in the database
        $update = $conn->prepare("UPDATE clients SET email = :email, change_email = :mail WHERE client_id = :id");
        $update->execute(array("id" => $user["client_id"], "mail" => 1, "email" => $newemail));
        
        // Log the email change
        $mail = $user["email"];
        $insert2 = $conn->prepare("INSERT INTO client_report SET client_id = :c_id, action = :action, report_ip = :ip, report_date = :date");
        $insert2->execute(array(
            "c_id" => $user["client_id"],
            "action" => "User changed email from $mail to $newemail.",
            "ip" => GetIP(),
            "date" => date("Y-m-d H:i:s")
        ));
        
        // Send confirmation email to the new address
        $code = $user['apikey'];
        $msg = "Please confirm your email address. Click the link below: " . site_url() . "confirm_email/activate/$code";
        $send = mail($newemail, "Email Confirmation", $msg);
        
        if($update && $send) {
            $success = 1;
            $successText = "A confirmation email has been sent";
            echo '<script>setTimeout(function(){window.location="'.site_url().'"},2000)</script>';
        } else {
            $error = 1;
            $errorText = "Failed to update email or send confirmation";
        }
    }
}

// Handle subscription actions (pause, resume, stop)
if(route(1) == "pause") {
    handleSubscription($conn, $user["client_id"], "paused", "active", "expired");
} elseif(route(1) == "resume") {
    handleSubscription($conn, $user["client_id"], "active", "paused");
} elseif(route(1) == "stop") {
    handleSubscription($conn, $user["client_id"], "canceled", "paused", "active");
}

function handleSubscription($conn, $client_id, $new_status, ...$current_statuses) {
    $order_id = route(2);
    $statuses = implode("' || subscriptions_status='", $current_statuses);
    
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = :id AND (subscriptions_status = :status1 OR subscriptions_status = :status2)");
    $stmt->execute(array("id" => $order_id, "status1" => $statuses[0], "status2" => $statuses[1]));
    
    if($stmt->rowCount()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $update = $conn->prepare("UPDATE orders SET subscriptions_status = :status WHERE order_id = :id");
        $update->execute(array("id" => $order_id, "status" => $new_status));
        
        $insert = $conn->prepare("INSERT INTO client_report SET client_id = :c_id, action = :action, report_ip = :ip, report_date = :date");
        $insert->execute(array(
            "c_id" => $client_id,
            "action" => "Subscription status changed to $new_status for order #".$row["order_id"],
            "ip" => GetIP(),
            "date" => date("Y-m-d H:i:s")
        ));
    }
    Header("Location:".site_url('subscriptions'));
    exit();
}