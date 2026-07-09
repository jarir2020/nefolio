<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}
$title .= $languageArray["resetpassword.title"];

if( $_SESSION["msmbilisim_userlogin"] == 1  || $user["client_type"] == 1 || $settings["resetpass_page"] == 1  ){
  Header("Location:".site_url());
}

if( !route(1) ){

if( $_POST ):

  $captcha        = $_POST['g-recaptcha-response'];
  $googlesecret   = $settings["recaptcha_secret"];
  $captcha_control= file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$googlesecret&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
  $captcha_control= json_decode($captcha_control);
  $user = $_POST["user"];
  $type = $_POST["type"];
  $usernameQuery = $conn->prepare("SELECT * FROM clients WHERE username=:username");
  $usernameQuery->execute(array("username" => $user));
  $emailQuery = $conn->prepare("SELECT * FROM clients WHERE email=:email");
  $emailQuery->execute(array("email" => $user));

    if( empty($user) ):
      $error      = 1;
      $errorText  = $languageArray["error.resetpassword.user.empty"];
    elseif( !$usernameQuery->rowCount() && !$emailQuery->rowCount() ):
      $error      = 1;
      $errorText  = $languageArray["error.resetpassword.user.notmatch"];
    elseif( $settings["recaptcha"] == 2 && $captcha_control->success == false ):
      $error      = 1;
      $errorText  = $languageArray["error.resetpassword.recaptcha"];
    else:
      if($usernameQuery->rowCount()):
          $row = $usernameQuery->fetch(PDO::FETCH_ASSOC);
      else:
          $row = $emailQuery->fetch(PDO::FETCH_ASSOC);
      endif;
      $token   = md5($row["email"].$row["username"].rand(9999,23245332));
      $update = $conn->prepare("UPDATE clients SET passwordreset_token=:pass WHERE client_id=:id ");
      $update->execute(array("id"=>$row["client_id"],"pass"=> $token ));
     $msg ="Hello,<br><br>

We received a request to reset the password for your account on BDclick24. If you made this request, please use the link below to update your password:<br><br>
<a href='" . site_url()."resetpassword/$token' style='display: inline-block; padding: 10px 20px; font-size: 16px; color: #ffffff; background-color: #007bff; text-align: center; text-decoration: none; border-radius: 5px; border: 2px solid #0056b3;'>Reset Password</a><br><br>
Or copy and paste the following URL into your browser:<br>
" . site_url()."resetpassword/$token<br><br>
If you did not request a password reset, please ignore this email or contact our support team for assistance.<br>";  
$to = $row["email"]; 
$subject = "Password Reset"; 
  if($to){ 
 sendMail(["subject"=>$subject,"body"=>$msg,"mail"=>$to]);
    
    $success    = 1;
    $successText= "We've sent the password reset instructions to your email. Don't forget to check your spam folder too.";
} else { 
    $error      = 1;
    $errorText  = $languageArray["error.resetpassword.fail"];
}        
    endif;

endif;
} else {
$templateDir  = "setnewpassword";
$passwordreset_token = route(1);
$user = $conn->prepare("SELECT * FROM clients WHERE passwordreset_token=:id");
$user->execute(array("id"=> route(1) ));
$user = $user->fetch(PDO::FETCH_ASSOC);

$client= $conn->prepare("SELECT * FROM clients WHERE passwordreset_token=:email ");
$client->execute(array("email"=>$passwordreset_token));

if( !$client->rowCount() ):
    Header("Location:".site_url(resetpassword));
endif;
  

if( $_POST ):
    $pass = $_POST["password"];
    $again = $_POST["password_again"];
    $passwordreset_token = route(1);
    
    if($pass != $again):
        $error = 1;
        $errorText = "Passwords not matched";
    else:
        $update = $conn->prepare("UPDATE clients SET password=:pass, passwordreset_token=:token WHERE client_id=:id ");
        $update->execute(array("id"=> $user["client_id"], "token" => "", "pass"=> md5($pass)));

        if( $update ):
            $success = 1;
            $successText = "Successfully Changed";
            echo '<script>setTimeout(function(){window.location="'.site_url().'"},2000)</script>';
        else:
            $error = 1;
            $errorText = $languageArray["error.resetpassword.fail"];
        endif;
    endif;
endif;




													   
													   
}
													   
													   