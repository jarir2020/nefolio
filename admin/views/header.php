<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="big5">

    <base href="<?= site_url() ?>">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width">
    <title>
<?= $settings["site_name"] ?>
    </title>


    <!--<style>
        /* লোডার স্ক্রিন ডিজাইন */
        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* শুধু লোডিং স্পিনার */
        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db; /* আপনি চাইলে কালার কোড পরিবর্তন করতে পারেন */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* স্পিনার ঘোরার এনিমেশন */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* মূল কন্টেন্ট (শুরুতে লুকানো থাকবে) */
        #content {
            display: none;
            text-align: center;
            padding-top: 50px;
            font-family: sans-serif;
        }
    </style>

    <div id="loader-wrapper">
        <div class="loader-spinner"></div>
    </div>

    <script>
        // ৩ সেকেন্ড (৩০০০ মিলিসেকেন্ড) টাইম সেট করা
        window.addEventListener("load", function() {
            setTimeout(function() {
                // লোডার বন্ধ করা
                document.getElementById("loader-wrapper").style.display = "none";
                
                // কন্টেন্ট দেখানো
                document.getElementById("content").style.display = "block";
            }, 50000); 
        });
    </script>-->

    

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">

    <link rel="stylesheet" type="text/css" href="public/admin/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="public/admin/style.css">
    <link rel="stylesheet" type="text/css" href="assets/public/admin/toastDemo.css">
	<link rel="stylesheet" type="text/css" href="assets/public/admin/tooltip.css">
	
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">


    <link rel="stylesheet" type="text/css" href="assets/public/admin/tinytoggle.min.css" rel="stylesheet">
 <link rel="stylesheet" href="assets/public/admin/iziToast.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>
<script src="assets/public/admin/iziToast.min.js"></script>

  <link href="//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/css/bootstrap-select.css">
<link rel="stylesheet" href="https://itsjavi.com/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css">
<link rel="stylesheet" href="assets/css/admin/image-picker.css">
	<link href="assets/css/admin/main.css" type="text/css" rel="stylesheet">
    <style>
 @import url("https://fonts.googleapis.com/css2?family=Sansita+Swashed:wght@600&display=swap");
        #loading {
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
        }

        #loading-image {
            z-index: 100;
        }

        #buy-smm {
            margin: 8px 15px;
            font-size: 15px;
            font-weight: 400;
        }

        #buy-smm a {
            cursor: pointer;
        }

a , body.dark-mode  a{
    color: #6800e8;
    text-decoration: none;
}


.nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {

    color: #fff;
    background-color: #6800e8;
}

.nav-pills>li >a:focus, .nav-pills>li >a:hover {
    color: #fff;
    background-color: #6800e8;
}

.btn-primary  {
   background-color: #6800e8;
}
.text-primary {
    color:#6800e8;
}
.btn-primary:hover, .btn-primary:focus  {

   background-color: #6800e8;

}

body.dark-mode .nav-pills>li.active>a, body.dark-mode .nav-pills>li.active>a:focus, body.dark-mode .nav-pills>li.active>a:hover {
    color: #fff;
    background-color: #6800e8;
}

body .checkAll-holder {
    padding: 4px 7px 3px 5px;
    padding-bottom: 5px !important;
    border: 1px solid #ddd;
    line-height: 0;
    z-index: 2;
    position: relative;
    border-radius: 4px;
    width: 28px;
}
.badge-success {

background-color: #198754!important;
}
.badge-error,.badge-danger{
    background-color: #dc3545!important;
}

.badge-warning {
    background-color: #ffc107!important;
}

.badge-info {
    background-color: #0dcaf0!important;
}
.badge-primary {
    background-color : #0d6efd !important;
}

body.dark-mode .btn-primary {

    background-color: #6800e8;
    border-color: #6800e8;
    color: #fff;
}


body.dark-mode .list-group-item.active, body.dark-mode .list-group-item.active:focus, body.dark-mode .list-group-item.active:hover {
    z-index: 2;
    color: #fff;
    background-color: #6800e8;
    border-color: #6800e8;
}

body.dark-mode ul.thumbnails.image_picker_selector li .thumbnail {
    border: 2px solid rgba(255,255,255,0.3);
}


body.dark-mode ul.thumbnails.image_picker_selector li .thumbnail.selected {
    border: 2px solid #3CCF4E;
}


.thumbnail {
    background-color: transparent;
}


body.dark-mode .btn-primary.focus, body.dark-mode .btn-primary:focus {
    background-color: #6800e8;
    border-color: #6800e8;
    color: #fff;
}





.text-danger {
    color:#D90429;
}

body.dark-mode .btn-danger {
    color: #fff;
    background-color: #D90429;
    border-color: #D90429;
}

.btn-danger {
    background-color: #D90429;
}

.text-success {
    color:#3CCF4E;
}
.btn-success {
    background-color: #3CCF4E;
}

.btn-primary.active, .btn-primary:active, .open>.dropdown-toggle.btn-primary {
    color: #fff;
    background-color: #6800e8;
    border-color: #6800e8;
}



.btn-primary.active.focus, .btn-primary.active:focus, .btn-primary.active:hover, .btn-primary:active.focus, .btn-primary:active:focus, .btn-primary:active:hover, .open>.dropdown-toggle.btn-primary.focus, .open>.dropdown-toggle.btn-primary:focus, .open>.dropdown-toggle.btn-primary:hover {
    color: #fff;
    background-color: #6800e8;
    border-color: #6800e8;
}


body.dark-mode .btn-primary.active.focus, body.dark-mode .btn-primary.active:focus, body.dark-mode .btn-primary.active:hover, body.dark-mode .btn-primary:active.focus, body.dark-mode .btn-primary:active:focus,body.dark-mode .btn-primary:active:hover, body.dark-mode .open>.dropdown-toggle.btn-primary.focus,body.dark-mode .open>.dropdown-toggle.btn-primary:focus,body.dark-mode .open>.dropdown-toggle.btn-primary:hover {
    color: #fff;
    background-color: #6800e8;
    border-color: #6800e8;
}





.hideextra { white-space: nowrap; overflow: scroll;}
   

.info-msg,
.success-msg,
.warning-msg,
.error-msg {
  margin: 10px 0;
  padding: 10px;
  border-radius: 3px 3px 3px 3px;
}
.info-msg {
  color: #059;
  background-color: #BEF;
}
.success-msg {
  color: #270;
  background-color: #DFF2BF;
}
.warning-msg {
  color: #9F6000;
  background-color: #FEEFB3;
}
.error-msg {
  color: #D8000C;
  background-color: #FFBABA;
}

.set-currency {
    font-family: "Sansita Swashed", cursive;
  border-left: 5px solid #FF1D58;
  padding: 10px;
  letter-spacing: 5px;
  font-weight: bold;
  padding-left: 10px;
}

.sansita {
    font-family: "Sansita Swashed", cursive;
}
.set-currency.b-blue {
   border-left: 5px solid #4285F4;
}
.disabledDiv {

    pointer-events: none;

    opacity: 0.4;
}

.select {
 
  padding: 7px 40px 7px 12px;
  width: 100%;
  border: 1px solid #e8eaed;
  border-radius: 5px;
  background: #fff;
  box-shadow: 0 1px 3px -2px #9098a9;
  cursor: pointer;
  font-family: inherit;
  font-size: 16px;
  transition: all 150ms ease;
}


.select:required:invalid {
  color: #5a667f;
}
.select option {
  color: #223254;
}
.select option[value=""][disabled] {
  display: none;
}
.select:focus {
  outline: none;
  border-color: #07f;
  box-shadow: 0 0 0 2px rgba(0,119,255,0.2);
}
.select:hover + svg {
  stroke: #07f;
}

.form-control:focus {

  outline: none;

  border-color: #07f;
  box-shadow: 0 0 0 2px rgba(0,119,255,0.2);
}

.curr_conv {
   border:1px solid lightgrey;
   border-radius:5px;
}
.toggle {
  align-items: center;
  border-radius: 100px;
  display: flex;
  float:right;
  font-weight: 700;
  margin-top:-3px;
}


.toggle__input {
  clip: rect(0 0 0 0);
  clip-path: inset(50%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}
.toggle__input:not([disabled]):active + .toggle-track, .toggle__input:not([disabled]):focus + .toggle-track {
  border: 1px solid transparent;
  box-shadow: 0px 0px 0px 2px #121943;
}
.toggle__input:disabled + .toggle-track {
  cursor: not-allowed;
  opacity: 0.7;
}

.toggle-track {
  background: #fff;
  border: 1px solid #000000;
  border-radius: 100px;
  cursor: pointer;
  display: flex;
  height: 30px;
  margin-right: 12px;
  position: relative;
  width: 60px;
}

.toggle-indicator {
  align-items: center;
  background:#ff4444;
  border-radius: 24px;
  bottom: 2px;
  display: flex;
  height: 24px;
  justify-content: center;
  left: 2px;
  outline: solid 2px transparent;
  position: absolute;
  transition: 0.25s;
  width: 24px;
}


.checkMark {
  fill: #fff;
  height: 20px;
  width: 20px;
  opacity: 0;
  transition: opacity 0.25s ease-in-out;
}



.toggle__input:checked + .toggle-track .toggle-indicator {
  background: #00C851;
  transform: translateX(30px);
}
.toggle__input:checked + .toggle-track .toggle-indicator .checkMark {
  opacity: 1;
  transition: opacity 0.25s ease-in-out;
}


@media screen and (-ms-high-contrast: active) {
  .toggle-track {
    border-radius: 0;
  }
}


.form__group{
  padding:0.5rem;
}
.form__controls{
  margin-top:.2rem;
}
.form__input{
  width:100%;

}
.form__submit{
  margin-top: .5rem;
  width: 10rem;
  height: 3rem;
}

.grid{
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}
.grid__col{
  flex-basis: 50%;
}


.button-1 {
  background-color: #EA4C89;
  border-radius: 8px;
  border-style: none;
  box-sizing: border-box;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 14px;
  font-weight: 500;
  height: 40px;
  line-height: 20px;
  list-style: none;
  margin: 0;
  outline: none;
  padding: 10px 16px;
  position: relative;
  text-align: center;
  text-decoration: none;
  vertical-align: baseline;
  user-select: none;
  -webkit-user-select: none;
}
.add-currency {
    background-color: #439A97;
    float:right;
}





.loader,
.loader:after {
  border-radius: 50%;
  width: 10em;
  height: 10em;
}
.loader {
  font-size: 5px;
  position: relative;
  text-indent: -9999em;
  border-top: 0.7em solid rgba(55, 114, 255, 0.2);
  border-right: 0.7em solid rgba(55, 114, 255, 0.2);
  border-bottom: 0.7em solid rgba(55, 114, 255, 0.2);
  border-left: 0.7em solid rgb(55, 114, 255);
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-animation: load8 1.1s infinite linear;
  animation: load8 0.8s infinite linear;
}

body.dark-mode .loader {
  border-top: 0.7em solid rgba(255, 22, 84, 0.2);

  border-right: 0.7em solid rgba(255, 22, 84, 0.2);

  border-bottom: 0.7em solid rgba(255, 22, 84, 0.2);
  border-left: 0.7em solid rgb(255, 22, 84);
}
@-webkit-keyframes load8 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes load8 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@media screen and (min-width : 906px){
 .navbar-header {
  display:none;
  }
}


.list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover {
    z-index: 2;
    color: #fff;
    background-color: #6800e8;
    border-color: #6800e8;
}
.carousel-inner>.item>a>img, .carousel-inner>.item>img, .img-responsive, .thumbnail a>img, .thumbnail>img {
    display: block;
    max-width: 40px;
    height: 40px;
}
ul.thumbnails.image_picker_selector li .thumbnail {
    padding: 3px;
    border: 2px solid #DDD;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

ul.thumbnails.image_picker_selector li .thumbnail.selected {
    border : 2px solid #3CCF4E;
    border-radius:4px;
    background: none;
}
ul.thumbnails.image_picker_selector li {
    margin: 0px 4px 4px 0px;
}

 .services-table {
    overflow-x: scroll;
    overflow-y: visible;
 }
.service-block__header {
    width:calc(100% + 50px);
}
.service-block__category {
    width:calc(100% + 50px);
}
.category-visibility {
    width: 14px;
    height: 14px;
    border-radius:50%;
    display:inline-block;
    margin-right : 10px;
    cursor:pointer;
}
.category-visibility.category-visible {
    background-color: rgb(0, 128, 1);
    box-shadow: 0 0 0 2px #95c794;
}

.category-visibility.category-invisible {
    background-color: #fe0000;
    box-shadow:0 0 0 2px #f99394;
}
.spinner_2 {
  pointer-event: none;
  -webkit-animation: rotate 1.5s linear infinite;
          animation: rotate 1.5s linear infinite;
  width: 3.2rem;
  height: 3.2rem;
  transition: opacity 0.3s linear 0.15s;
  margin-right : 10px;

}

.spinner_2.large {
    width: 4rem;

  height: 4rem;
}

.spinner_2.medium {
    width: 2.5rem;
  height: 2.5rem;
}

.spinner_2.small {
    width: 1.6rem;
  height: 1.6rem;
}



 .spinner_2 .path_2 {
  stroke: #007bff;
  stroke-linecap: round;
  -webkit-animation: dash_1 1.5s ease-in-out infinite;
          animation: dash_1 1.5s ease-in-out infinite;
}

@-webkit-keyframes rotate {
  100% {
    transform: rotate(360deg);
  }
}

@keyframes rotate {
  100% {
    transform: rotate(360deg);
  }
}
@-webkit-keyframes dash_1 {
  0% {
    stroke-dasharray: 1, 150;
    stroke-dashoffset: 0;
  }
  50% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -35;
  }
  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -124;
  }
}
@keyframes dash_1 {
  0% {
    stroke-dasharray: 1, 150;
    stroke-dashoffset: 0;
  }
  50% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -35;
  }
  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -124;
  }
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: .2em;
}
.spinner-border {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    vertical-align: text-bottom;
    border: .25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    -webkit-animation: spinner-border .75s linear infinite;
    animation: spinner-border .75s linear infinite;
}

@keyframes spinner-border {
100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
}
}


.img-responsive {
    max-width: calc(1em + 10px);
    max-height: calc(1em + 10px);
}

.btn-group, .btn-group-vertical {
    position: relative;
    display: inline-block;
}

.img-responsive {
    max-width: 100%;
    height: auto;
}


</style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div id="loading">
<div class="loader">Loading...</div>
            </div>
        </div>
    </div>


    <body class="<?php if($admin["mode"]=="dark" ): echo "dark-mode" ; endif; ?>">

<nav class="navbar navbar-static-top  navbar-default">
<div class="container-fluid">
<div class="navbar-header">
<img style="margin-bottom:-30px;margin-left:10px;" width="100" height="20" src="


<?=$settings["site_logo"]?>





">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
aria-expanded="false" aria-controls="navbar">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>

</div>
<div id="navbar" class="collapse navbar-collapse">

<ul id="w2" class="nav navbar-nav navbar-left-block">
<?php if ($admin["access"]["admin_access"]  && $_SESSION["msmbilisim_adminlogin"]) : ?>

<li class="<?php if(route(1) == "index"): echo 'active' ; endif; ?>">
<a class="ajax-link"
href="<?php echo site_url("admin") ?>"><b>DASHBOARD</b></a>
</li>

<li class="<?php if (route(1) == "clients") : echo 'active' ; endif; ?>"><a class="ajax-link"
href="<?php echo site_url("admin/clients") ?>"></i><span>
    <b>USERS</b></span></a>
</li>



<li class="" class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
aria-haspopup="true" aria-expanded="false"></i><b>SERVICE</b><span class="caret"></span></a>

<ul class="dropdown-menu dropdown-max-height">

<li class="<?php if (route(1) == "services") : echo 'active' ; endif; ?>"><a
class="ajax-link" href="<?php echo site_url("admin/services") ?>"></i><span> <b>SERVICES</b></span></a></li>
<li class="<?php if (route(1) == "update-prices") : echo 'active' ; endif; ?>"><a
        class="ajax-link" href="<?php echo site_url("admin/update-prices") ?>"></i><span> <b>UPDATE PRICES</b></span></a></li>
<li class="<?php if (route(1) == "bulk") : echo 'active' ; endif; ?>"><a
        class="ajax-link" href="<?php echo site_url("admin/bulk") ?>"></i><span> <b>BULK SERVICES EDIT</b></span></a></li>
<li class="<?php if (route(1) == "bulkc") : echo 'active' ; endif; ?>"><a
        class="ajax-link" href="<?php echo site_url("admin/category-sort") ?>"><span> <b>CATEGORY SORT</b></span></a></li>
<li class="<?php if (route(1) == "synced_logs") : echo 'active' ;endif; ?>"><a
        class="ajax-link" href="<?php echo site_url("admin/synced_logs") ?>"><span><b>SYNCED LOGS</b></span></a></li>


</ul>


<li class="" class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
aria-haspopup="true" aria-expanded="false"></i>
<b>ORDER</b><span class="caret"></span></a>
<ul class="dropdown-menu dropdown-max-height">

<li class="<?php if (route(1) == "orders") : echo 'active' ; endif; ?>"><a
        class="ajax-link" href="<?php echo site_url("admin/orders") ?>"></i><span><b>ORDERS</b></span></a></li>

<li class="<?php if (route(1) == "tasks") : echo 'active' ; endif; ?>"><a

class="ajax-link" href="<?php echo site_url("admin/tasks") ?>"></i><span> <b>TASKS</b></span></a></li>
<?php if(countRow(["table"=>"orders","where"=>["dripfeed"=>2]])>0){   ?>

<li class="<?php if (route(1) == " dripfeed") : echo 'active' ; endif; ?>"><a
        class="ajax-link" href="<?php echo site_url("admin/dripfeed") ?>"><span><b>DRIP-FEEDS</b></span></a></li>
<?php

			}else{
				
			}
			
			?>

<?php if(countRow(["table"=>"orders","where"=>["subscriptions_type"=>2]])>0){

			?>
<li class="<?php if (route(1) == "subscriptions") : echo 'active' ; endif; ?>"></i><span> <b>SUBSCRIPTIONS</b></span></a></li>
                        <?php
				
			}else{
				
			}
			
			?>
</ul>
</li>




<li class="<?php if (route(1) == "payments") : echo 'active' ; endif; ?>"><a class="ajax-link"
href="<?php echo site_url("admin/fund-add-history") ?>"></i><span> <b>PAYMENTS</b></span></a>
</li>





<li class="<?php if (route(1) == "tickets") : echo 'active' ; endif; ?>"><a class="ajax-link"
href="<?php echo site_url("admin/tickets") ?>"><span> <b>TICKETS</b>
<?php if(countRow(["table"=>"tickets","where"=>["client_new"=>2]])>0){
			
	?><span class="badge" style="background-color:#cc9616 ">
    <?=countRow(["table"=>"tickets","where"=>["client_new"=>2]]);?>
</span>
<?php
				
			}else{
				
			}
			
			?>
</span></a>
</li>


<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
aria-expanded="false"></i><b>EXTRA MENU</b><span
class="caret"></span></a>

<ul class="dropdown-menu dropdown-max-height">
<li class="<?php if (route(1) == "referrals") : echo 'active' ; endif; ?>"></li>
<li class="<?php if (route(1) == "payouts") : echo 'active' ; endif; ?>"><a
    class="ajax-link" href="<?php echo site_url("admin/referrals") ?>"></i><span><b>AFFILIATES</b></span></a></li>
<li class="<?php if (route(1) == "broadcasts") : echo 'active' ; endif; ?>"><a
    class="ajax-link" href="<?php echo site_url("admin/broadcasts") ?>"></i><span><b>BROADCASTS</b></span></a>
</li>
<li><a class="ajax-link" href="<?php echo site_url("admin/logs") ?>"><span></i> <b>SITE LOGS</b></span></a>
</li>

<li><a class="ajax-link" href="<?php echo site_url("admin/reports") ?>"><span></i><b>SITE REPORTS</b></span></a>
</li>

<li class="<?php if (route(1) == "earn") : echo 'active' ; endif; ?>"><a class="ajax-link"
    href="<?php echo site_url("admin/earn") ?>"></i><span>
<b>PROMOTION</b></span></a></li>

<li class="<?php if (route(1) == "kuponlar") : echo 'active' ; endif; ?>"><a
    class="ajax-link" href="<?php echo site_url("admin/kuponlar") ?>"><span> <b>COUPON CODE</b></span></a></li>


<li class="<?php if (route(1) == "child-panels") : echo 'active' ; endif; ?>"><a
    class="ajax-link" href="<?php echo site_url("admin/child-panels") ?>"><span> <b>CHILD PANELS</b></span></a></li>

<li class="<?php if (route(1) == "updates") : echo 'active' ; endif; ?>"><a
    class="ajax-link" href="<?php echo site_url("admin/updates") ?>"><span> <b>SERVICE UPDATES</b></span></a></li>

<li class="<?php if( route(1) == "payments" && route(2)=="bank" ): echo 'active' ; endif;
?>"><a href="<?php echo site_url("admin/payments/bank") ?>"></i><b>MANUL PAY NOTIFICAIONS </b><span class="badge" style="background-color: #f0ad4e">
<?=countRow(["table"=>"payments","where"=>["payment_method"=>4,"payment_status"=>1]]);?>
</span></a></li>


</ul>
</li>

<li class="<?php if (route(1) == "appearance") : echo 'active' ; endif; ?>"><a class="ajax-link"
href="<?php echo site_url("admin/appearance") ?>"></i><span>
<b>APPEARANCE</b></span></a>
</li>

<li class="<?php if (route(1) == "settings") : echo 'active' ; endif; ?>"><a class="ajax-link"
href="<?php echo site_url("admin/settings") ?>"></i><span> <b>SETTINGS</b></span></a>
</li>



<li class="<?php if (route(1) == "account") : echo 'active' ; endif; ?>"><a class="ajax-link"
href="<?php echo site_url("admin/account") ?>"></i><span>
    <b>ACCOUNT</b></span></a>
</li>
 <li><a href="#" data-toggle="modal" data-target="#SMMMarkets"><i class="	fa fa-question-circle"></i> <b>CRON</b> </a>  </li>


<li>
<a class="ajax-link" href="admin/logout"><i class="fa fa-sign-out-alt"></i><b>LOGOUT</b></a>

</li>

<?php if ($admin["mode"] == "dark") : ?>

<li><a id="enable-light-mode" href="javascript:void(0)"><i class="fas fa-sun"></i></a></li>

<?php else: ?>

<li><a id="enable-dark-mode" href="javascript:void(0)"><i class="fas fa-moon"></i></a></li>

<?php endif; ?>



<?php endif; ?>



</ul>
                </div>

            </div>
        </nav>
        <div class="modal fade" id="SMMMarkets" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">CRONS & SCRIPT INFO!</h4>
                </div>
                <div class="modal-body">
                    <div class="well">
                        <h4> SMMMarkets V2.6 </h4>
                        For Support Contact Us! <br>
                        <b> WhatsApp: </b> +8801978158369 <br>
                        <b>E-mail: </b> info@SMMMarkets.com <br>
                        <b>E-mail: </b> Mail.Netthos@gmail.com
                    </div>
                    <div class="well">
                        <h4>CRON Addresses & Recommended Times</h4> <br>
                        <div>
                            <code id="cron1">FOR ORDERS</code>
                            <button class="btn btn-info btn-sm copy-btn" onclick="copyText('wget --spider -O - <?=site_url()?>automations/cronjobs/orders_d0ae3bdcc8687cf4afc35beadce9b9da.php >/dev/null 2>&1', this)">Copy</button>
                            <br> Set This Cron On Every 5 Min<br><hr>
                        </div>
                        <div>
                            <code id="cron2">FOR REFILL ORDERS</code>
                            <button class="btn btn-info btn-sm copy-btn" onclick="copyText('wget --spider -O - <?=site_url()?>crons/refill.php >/dev/null 2>&1', this)">Copy</button>
                            <br> Set This Cron On Every 5 Min<br><hr>
                        </div>
                        <div>
                            <code id="cron3">FOR SELLER SYNC</code>
                            <button class="btn btn-info btn-sm copy-btn" onclick="copyText('wget --spider -O - <?=site_url()?>automations/cronjobs/seller-sync_4c0cb8cecbcfb5de188255356814af5f.php >/dev/null 2>&1', this)">Copy</button>
                            <br> Set This Cron On Every 5 Min<br><hr>
                        </div>
                        <div>
                            <code id="cron4">FOR AVERAGE TIME</code>
                            <button class="btn btn-info btn-sm copy-btn" onclick="copyText('wget --spider -O - <?=site_url()?>automations/cronjobs/average_59baa8226b6806d459212876cc69fd6b.php >/dev/null 2>&1', this)">Copy</button>
                            <br> Set This Cron On Every 5 Min<br><hr>
                        </div>
                        <div>
                            <code id="cron5">FOR SUPPORT AUTOREPLY</code>
                            <button class="btn btn-info btn-sm copy-btn" onclick="copyText('wget --spider -O - <?=site_url()?>crons/autoreply.php >/dev/null 2>&1', this)">Copy</button>
                            <br> Set This Cron On Every 5 Min<br><hr>
                        </div>
                        <div>
                            <code id="cron6">FOR DRIPFEED ORDERS</code>
                            <button class="btn btn-info btn-sm copy-btn" onclick="copyText('wget --spider -O - <?=site_url()?>crons/dripfeed.php >/dev/null 2>&1', this)">Copy</button>
                            <br> Set This Cron On Every 5 Min<br><hr>
                        </div>
                        <div>
                            <code id="cron7">FOR PAYMENTS LOG</code>
                            <button class="btn btn-info btn-sm copy-btn" onclick="copyText('wget --spider -O - <?=site_url()?>automations/cronjobs/payments_44a39a7c53c3a177deb043ca6edf4f55.php >/dev/null 2>&1', this)">Copy</button>
                            <br> Set This Cron On Every 5 Min<br><hr>
                        </div>
                        <div>
                            <code id="cron8">FOR AUTOLIKE ORDERS</code>
                            <button class="btn btn-info btn-sm copy-btn" onclick="copyText('wget --spider -O - <?=site_url()?>crons/autolike.php >/dev/null 2>&1', this)">Copy</button>
                            <br> Set This Cron On Every 5 Min<br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    
                </div>
            </div>
        </div>
    </div><style>
        .modal-header, .modal-footer {
            background-color: #f5f5f5;
            border-bottom: 1px solid #e5e5e5;
        }
        .modal-title {
            color: #555;
        }
        .well {
            background-color: #f8f8f8;
        }
        .copy-btn {
            margin-left: 10px;
        }
        .contact-btn {
            margin: 0 5px;
        }
    </style>
    <script>
    function copyText(text, button) {
        // 1. Copy the text provided in the function call
        navigator.clipboard.writeText(text).then(() => {
            // 2. Success: Store original text and update button text temporarily
            const originalText = button.innerText;
            button.innerText = "Copied ✅";
            
            // 3. Revert button text after 1.5 seconds
            setTimeout(() => {
                button.innerText = originalText;
            }, 1500);
            
        }).catch(err => {
            console.error('Could not copy text: ', err);
            alert('Failed to copy the text.');
        });
    }
    </script>