<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}

  unset($_SESSION["msmbilisim_adminid"]);
  unset($_SESSION["msmbilisim_adminpass"]);
  unset($_SESSION["msmbilisim_userlogin"]);
  setcookie("a_id", $admin["admin_id"], time()-(60*60*24*7), '/', null, null, true );
  setcookie("a_password", $admin["password"], time()-(60*60*24*7), '/', null, null, true );
  setcookie("a_login", 'ok', time()-(60*60*24*7), '/', null, null, true );
  session_destroy();
  header("Location:".site_url('admin'));
