<?php include 'header.php'; ?>
<div class="container-fluid">
            <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
   <ul class="nav nav-tabs">
        <li class="p-b">
         <button class="btn btn-default" data-toggle="modal" data-target="#modalDiv" data-action="payment_new">
         <span class="export-title">Add/Remove Balance</span>
         </button>
      </li>
      
      <li class="pull-right custom-search">
         <form class="form-inline" action="<?php echo site_url("admin/payments/online") ?>" method="get">
            <div class="input-group">
               <input type="text" name="search" class="form-control" value="<?=$search_word?>" placeholder="Search ...">
               <span class="input-group-btn search-select-wrap">
                  <select class="form-control search-select" name="search_type">
                     <option value="username" <?php if( $search_where == "username" ): echo 'selected'; endif; ?> >Username</option>
                  </select>
                  <button type="submit" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
               </span>
            </div>
         </form>
      </li>
      
   </ul>
<div style="overflow-x:scroll">
   <table class="table payments-table">
      <thead>
         <tr>
            <th class="p-l">ID</th>
           <th>User</th>
             <th>Balance</th>
             <th>Amount</th>
             <th>Status</th>
             <th>Details</th>
             <th>Created</th>
            <th></th>
         </tr>
      </thead>
      <form id="changebulkForm" action="<?php echo site_url("admin/payments/online/multi-action") ?>" method="post">
        <tbody>
          <?php foreach($payments as $payment ): ?>
              <tr>
                 <td class="p-l"><?php echo $payment["payment_id"] ?></td>
                 <td><span class="label-id"><?php echo $payment["client_id"] ?></span><?php echo $payment["username"] ?></td>
                 <td><?php echo $payment["client_balance"] ?></td>
                 <td><?php echo $payment["payment_amount"] ?></td>
                 <td>
				  
			<?php if( $payment['payment_status'] == 1 ): ?>
					 Pending
					 
					 <?php endif; ?>
			<?php if( $payment['payment_status'] == 2 ): ?>
<strong style='background-color:red;color:white;'>Failed</strong>
<?php endif; ?>
<?php if( $payment['payment_status'] == 3 ): ?>
<strong style='background-color:green;color:white;'>Completed</strong>
<?php endif; ?>
</td>
<td style="width:30%"><?php echo $payment["method_name"] ?> - <?php if( $payment["payment_mode"] == "Auto" ): ?>Automatic
<?php else: ?>
Manual<?php endif; ?><br>Order ID:<?php echo $payment["payment_extra"] ?> <br>Special Note :- <?php echo $payment["payment_note"] ?></td>
				  
				  
				 
				  
                 <td><?php echo $payment["payment_create_date"] ?></td>
				  
				  
				  
                 <td class="service-block__action">
                   <div class="dropdown pull-right">
                     <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Actions <span class="caret"></span></button>
                     <ul class="dropdown-menu">
                     <?php if( $payment["payment_mode"] == "Auto" ): ?>
                       <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="payment_detail" data-id="<?php echo $payment["payment_id"] ?>">Payment details</a></li>
                     <?php endif; ?>
                       
						 
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
                  <li class="prev"><a href="<?php echo site_url("admin/payments/online/1".$search_link) ?>">&laquo;</a></li>
                 <li class="prev"><a href="<?php echo site_url("admin/payments/online/".$paginationArr["previous"].$search_link) ?>">&lsaquo;</a></li>
                 <?php
                     endif;
                     for ($page=1; $page<=$pageCount; $page++):
                       if( $page >= ($paginationArr['current']-9) and $page <= ($paginationArr['current']+9) ):
                 ?>
                 <li class="<?php if( $page == $paginationArr["current"] ): echo "active"; endif; ?> "><a href="<?php echo site_url("admin/payments/online/".$page.$search_link) ?>"><?=$page?></a></li>
                 <?php endif; endfor;
                       if( $paginationArr["current"] != $paginationArr["count"] ):
                 ?>
                 <li class="next"><a href="<?php echo site_url("admin/payments/online/".$paginationArr["next"].$search_link) ?>" data-page="1">&rsaquo;</a></li>
                 <li class="next"><a href="<?php echo site_url("admin/payments/online/".$paginationArr["next"].$search_link) ?>" data-page="1">&raquo;</a></li>
                 <?php endif; ?>
              </ul>
           </nav>
        </div>
     </div>
   <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
