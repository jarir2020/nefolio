<?php include 'header.php'; ?>
<style>
     .orders-table {
     overflow-y:scroll;
     width:100%;
 }
</style>
<div class="container-fluid">
            <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
   <ul class="nav nav-tabs p-b">
     <li class="<?php if( $status == "all"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders")?>">All Orders <span class="badge" style="background-color: #808080"><?php echo countRow(["table"=>"orders"]) ?></span></a></li>
     <li class="<?php if( $status == "manuel"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/manuel")?>">Manuel Orders<span class="badge" style="background-color: #8b3a3a"><?php if($failCount): echo $failCount; endif; ?></span></a></li>
     <li class="<?php if( $status == "cronpending"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/cronpending")?>">Awaiting <span class="badge" style="background-color: #808080"><?php if($cronpendingcount): echo $cronpendingcount; endif; ?></span></a></li>
     <li class="<?php if( $status == "pending"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/pending")?>">Pending <span class="badge" style="background-color: #808080"><?php if($pendingcount): echo $pendingcount; endif; ?></span></a></li>
               <li class="<?php if( $status == "processing"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/processing")?>">Processing <span class="badge" style="background-color: #808080"><?php if($processingcount): echo $processingcount; endif; ?></span></a></li>
     <li class="<?php if( $status == "inprogress"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/inprogress")?>">Inprogress <span class="badge" style="background-color: #808080"><?php if($inprogresscount): echo $inprogresscount; endif; ?></span></a></li>
     <li class="<?php if( $status == "completed"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/completed")?>">Completed <span class="badge" style="background-color: #808080"><?php if($completedcount): echo $completedcount; endif; ?></span></a></li>
     <li class="<?php if( $status == "partial"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/partial")?>">Partial <span class="badge" style="background-color: #808080"><?php if($partialcount): echo $partialcount; endif; ?></span></a></li>
     <li class="<?php if( $status == "canceled"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/canceled")?>">Canceled <span class="badge" style="background-color: #808080"><?php if($canceledcount): echo $canceledcount; endif; ?></span></a></li>

     <li class="<?php if( $status == "fail"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/fail")?>">Fail <span class="badge" style="background-color: #8b3a3a"><?php if($failCount): echo $failCount; endif; ?></span></a></li>
      <li class="pull-right custom-search">
         <form class="form-inline" action="<?=site_url("admin/orders")?>" method="get">
            <div class="input-group">
               <input type="text" name="search" class="form-control" value="<?=$search_word?>" placeholder="Search">
               <span class="input-group-btn search-select-wrap">
                  <select class="form-control search-select" name="search_type">
                     <option value="order_id" <?php if( $search_where == "order_id" ): echo 'selected'; endif; ?> >Order ID</option>
                     <option value="order_url" <?php if( $search_where == "order_url" ): echo 'selected'; endif; ?> >Order URL</option>
                     <option value="username" <?php if( $search_where == "username" ): echo 'selected'; endif; ?> >Username</option>
                  </select>
                  <button type="submit" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
               </span>
            </div>
         </form>
      </li>
   </ul>
               
                    
<div class="orders-table">
<table class="table" id="dt">
                                <thead>
         <tr>
            <th class="checkAll-th">
               <div class="checkAll-holder">
                  <input type="checkbox" id="checkAll">
                  <input type="hidden" id="checkAllText" value="order">
               </div>
               <div class="action-block">
                  <ul class="action-list">
                     <li><span class="countOrders"></span> Orders Selected</li>
                     <li>
                        <div class="dropdown">
                           <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown"> Bulk Actions <span class="caret"></span></button>
                           <ul class="dropdown-menu">
                              <li>
								  <?php if( $status  ==  "fail" ): ?>
                                 <a class="bulkorder" data-type="resend">Resend</a>
                                <?php endif; ?>
                                 <a class="bulkorder" data-type="pending">Pending</a>
                                 <a class="bulkorder" data-type="inprogress">In Progress</a>
                                 <a class="bulkorder" data-type="completed">Completed</a>
                                 <a class="bulkorder" data-type="canceled">Cancel & Refund</a>
                                 
                              </li>
                           </ul>
                        </div>
                     </li>
                  </ul>
               </div>
            </th>
            <th class="p-l">ID</th>
            <th>User</th>
            <th>Charge</th>
            <th>Profit</th>
            <th class="service-block__service">Link</th>
            <th>Seller</th>
            <th>Start</th>
            <th>Quantity</th>
            <th class="dropdown-th">
              <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" data-active="<?=$_GET["service_id"]?>" type="button" id="serviceList" data-href="admin/orders/counter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Service
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="serviceListContent" style="max-height: 275px; overflow:hidden; overflow-y: scroll">
                </ul>
              </div>
            </th>
            <th>Status</th>
            <th>Remains</th>
            <th width="10%">Date</th>
            <th width="5%" class="dropdown-th">
              <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Mode
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="<?php if( !$_GET["mode"] ): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>">All</a></li>
                    <li class="<?php if( $_GET["mode"] == "manuel" ): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>?mode=manuel">Manual</a></li>
                    <li class="<?php if( $_GET["mode"] == "auto" ): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>?mode=auto">API</a></li>
                </ul>
              </div>
            </th>
            <th></th>
         </tr>
      </thead>
      <form id="changebulkForm" action="<?php echo site_url("admin/orders/multi-action") ?>" method="post">
        <tbody>
          <?php foreach( $orders as $order ): ?>
              <tr>
                 <td><input type="checkbox" class="selectOrder"  name="order[<?php echo $order["order_id"] ?>]" value="1" style="border:1px solid #fff"></td>
                 <td class="p-l">
                  <?php echo $order["order_id"] ?>
                  <?php if( $order["api_orderid"] != 0 ): echo '<div class="label label-api">'.$order["api_orderid"].'</div>'; endif; ?>
                </td>
                 <td><?php echo $order["username"]; if( $order["order_where"] == "api" ): echo ' <span class="label label-api">API</span>'; endif; ?> </td>
                 <td class="service-block__minorder">
                   <div style="width:85px;">
 
<?php echo format_amount_string($settings["site_base_currency"],$order["order_charge"]); ?>
                   
<?php if( $order["service_api"] != 0 ): echo '<div class="service-block__provider-value">'.format_amount_string($settings["site_base_currency"],$order["api_charge"]).'</div>'; endif; ?>
</div>
                 </td>
<td>
 <div style="width:85px;"><?php echo format_amount_string($settings["site_base_currency"],$order["order_profit"]); ?>
 
 </div>
 
<td class="service-block__service">
    <a href="<?php echo $order["order_url"]; ?>" target="_blank" class="btn btn-sm btn-primary" style="margin-right:5px; font-weight:bold; padding:2px 6px;">Open</a>
    
    <button type="button" class="btn btn-sm btn-success" style="margin-right:5px; font-weight:bold; padding:2px 6px;" onclick="copyLink('<?php echo $order["order_url"]; ?>')">Copy</button>
    
    <?php echo $order["order_url"]; ?>
    
    <?php 
    if(empty($order["order_extras"]) || $order["order_extras"] == "[]"){ 
    }else{
        echo ' <a href="#" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDiv" data-action="order_comment" data-id="'.$order["order_id"].'">Comments</a>'; 
    }
    ?>
</td>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).catch(() => {
        console.error('Failed to copy link.');
    });
}
</script>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).catch(() => {
        console.error('Failed to copy link.');
    });
}
</script>

<td><?php echo GET_API_NAME_BY_ID($order["order_api"]);?></td>
                 <td><?php echo $order["order_start"]; ?></td>
                 <td><?php echo $order["order_quantity"]; ?></td>
                 <td width="30%"><span class="label-id"><?php echo $order["service_id"]; ?></span><?php echo $order["service_name"]; ?></td>
                 <td><?php echo  orderStatu($order["order_status"],$order["order_error"],$order["order_detail"]); ?></td>
                 <td><?php if( $order["order_status"] == "completed" && substr($order["order_remains"], 0,1) == "-" ): echo "+".substr($order["order_remains"], 1);  else: echo $order["order_remains"]; endif; ?></td>
                 <td width="10%"><?php echo $order["order_create"]; ?></td>
                 <td width="5%"><?php if( $order["api_service"] == 0 ): echo "Manual"; else: echo "Auto"; endif; ?></td>
                 <td class="service-block__action">
                   <div class="dropdown pull-right">
                     <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Options <span class="caret"></span></button>
                     <ul class="dropdown-menu">
                        <?php if( $order["order_error"] != "-" && $order["service_api"] != 0 ): ?>
                          <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_errors" data-id="<?php echo $order["order_id"] ?>">Order Errors</a></li>
                          <li><a href="<?=site_url("admin/orders/order_resend/".$order["order_id"])?>">Resend Order</a></li>
                        <?php endif; ?>
                        <?php if( $order["order_error"] != "-" ): ?>
                          <li><a href="#" data-toggle="modal" data-target="#modalDiv" data-action="order_custom_resend" data-id="<?php echo $order["order_id"] ?>">Custom Resend</a></li>
                        <?php endif; ?>
                       <?php if( $order["order_error"] == "-" && $order["service_api"] != 0 ): ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_details" data-id="<?php echo $order["order_id"] ?>">Order Details</a></li>
                       <?php endif; ?>
                       <?php if( $order["service_api"] == 0 || $order["order_error"] != "-"  ): ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_orderurl" data-id="<?php echo $order["order_id"] ?>">Edit Order URL</a></li>
                       <?php endif; ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_startcount" data-id="<?php echo $order["order_id"] ?>">Edit Start Count</a></li>
                      
                       <?php if( $order["order_status"] != "partial"): ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_partial" data-id="<?php echo $order["order_id"] ?>">Mark Partial</a></li>
                       <?php endif; ?>
<?php if( $order["refill"]  ==  "0" || $order["refill"]  ==  "2" || $order["status_order"]  ==  "Completed" ): ?>
<li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_refill_activate/".$order["order_id"])?>">Activate Refill Button</a></li>
<?php endif; ?>

                       <li class="dropdown dropdown-submenu">
                          <a href="#" class="dropdown_menu">Update Order Status</a>
                          <ul class="dropdown-menu submenu_drop">
                     
                            <?php if( $order["order_status"]  ==  "pending" || $order["order_status"]  ==  "completed"  || $order["order_status"]  ==  "processing" || $order["order_status"]  ==  "partial" || $order["order_status"]  ==  "fail" ): ?>
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_cancel/".$order["order_id"])?>">Cancel & Refund</a></li>
                            <?php endif; ?>
                 <?php if( $order["order_status"]  ==  "pending" || $order["order_status"]  ==  "inprogress"  || $order["order_status"]  ==  "processing" ): ?>           
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_complete/".$order["order_id"])?>">Complete</a></li>
               <?php endif; ?>             
                            <?php if( $order["order_status"]  ==  "pending"  || $order["order_status"]  ==  "processing"  ): ?>
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_inprogress/".$order["order_id"])?>">In Progress</a></li>
                            <?php endif; ?>
                          </ul>
                        </li>
                     </ul>
                   </div>
                 </td>
              </tr>
            <?php endforeach; ?>
        </tbody>
        <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">
      </form>
   </table></div>
   <?php if( $paginationArr["count"] > 1 ): ?>
     <div class="row">
        <div class="col-sm-8">
           <nav>
              <ul class="pagination">
                <?php if( $paginationArr["current"] != 1 ): ?>
                 <li class="prev"><a href="<?php echo site_url("admin/orders/1/".$status.$search_link) ?>">&laquo;</a></li>
                 <li class="prev"><a href="<?php echo site_url("admin/orders/".$paginationArr["previous"]."/".$status.$search_link) ?>">&lsaquo;</a></li>
                 <?php
                     endif;
                     for ($page=1; $page<=$pageCount; $page++):
                       if( $page >= ($paginationArr['current']-9) and $page <= ($paginationArr['current']+9) ):
                 ?>
                 <li class="<?php if( $page == $paginationArr["current"] ): echo "active"; endif; ?> "><a href="<?php echo site_url("admin/orders/".$page."/".$status.$search_link) ?>"><?=$page?></a></li>
                 <?php endif; endfor;
                       if( $paginationArr["current"] != $paginationArr["count"] ):
                 ?>
                 <li class="next"><a href="<?php echo site_url("admin/orders/".$paginationArr["next"]."/".$status.$search_link) ?>" data-page="1">&rsaquo;</a></li>
                 <li class="next"><a href="<?php echo site_url("admin/orders/".$paginationArr["count"]."/".$status.$search_link) ?>" data-page="1">&raquo;</a></li>
                 <?php endif; ?>
              </ul>
           </nav>
        </div>
        <div class="col-sm-4 pagination-counters">
          <?php echo $count; ?> from within the order <?php echo $where+1 ?>'den <?php if( $where+$to > $count ): echo $count; else: echo $where+$to; endif; ?>'up to
         </div>
     </div>
   <?php endif; ?>
</div>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
   <div class="modal-dialog modal-dialog-center" role="document">
      <div class="modal-content">
         <div class="modal-body text-center">
            <h4>Are you sure you want to update the status?</h4>
            <div align="center">
               <a class="btn btn-primary" href="" id="confirmYes">Yes</a>
               <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'footer.php'; ?>
