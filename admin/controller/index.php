<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}
if( $admin["access"]["admin_access"] != 1  ){

    header("Location:".site_url("admin"));

    exit();
}
  
  $clients = $conn->prepare("SELECT * FROM clients ORDER BY client_id DESC LIMIT 500");
  $clients->execute(array());
  $clients = $clients->fetchAll(PDO::FETCH_ASSOC);



$failCount      = $conn->prepare("SELECT * FROM orders WHERE orders.dripfeed='1' && orders.subscriptions_type='1' && order_error!=:error ");
  $failCount     -> execute(array("error"=>"-"));
  $failCount      = $failCount->rowCount();
$todayCount      = $conn->prepare("SELECT * FROM orders WHERE last_check=:error ");
  $todayCount     -> execute(array("error"=> date("Y-m-d") ));
  $todayCount     = $todayCount->rowCount();

  

require admin_view('index');