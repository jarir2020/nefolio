<?php include 'header.php'; ?>
 <div class="container container-md"> <div class="row"><div class="col-md-12">
                  <ul class="nav nav-tabs"> <a href="/admin/tickets" class="details_backButton btn btn-link"><span>‹</span> Back</a><li class="pull-right custom-search">
         <form class="form-inline" action="<?=site_url("admin/orders")?>" method="get" target="_blank">
            <div class="input-group">
               <input type="text" name="search" class="form-control" value="<?=$search_word?>" placeholder="Search ...">
               <span class="input-group-btn search-select-wrap">
                  <select class="form-control search-select" name="search_type">
                     <option value="order_id" <?php if( $search_where == "order_id" ): echo 'selected'; endif; ?> >ID</option>
                  </select>
                  <button type="submit" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
               </span>
            </div>
         </form>
      </li></ul> 
 </div> </div>
   <div class="row">
       <div class="col-md-12">
        <div class="ticket-header__title border-radius-top">
        <div class="row">
            <div class="col-md-12">
                <div class="ticket-header-title">
					 <?php foreach($ticket as $tickets ): ?>
       

		   <?php echo $ticketMessage[0]["subject"]; echo ' <span class="service-block__provider-value">';
               if($tickets["support_new"] == 2){
                                           echo'  <i class="fa fa-eye-slash"></i> Not Seen Yet';
                                           }elseif ($tickets["support_new"] == 1){
                                           echo'  <i class="fa fa-eye"></i> seen';
                                           } ?></span><?php endforeach; ?>
               <?php if( $ticketMessage[0]["canmessage"] == 1 ): ?>
               <span class="badge"><i class="fa fa-lock"></i> Support request locked, User can't reply.</span>
               <?php endif; ?>
                    <div class="ticket-header-id">ID: <?=$ticketMessage[0]["ticket_id"]?></div>
             </div>
            </div>
        </div>
    </div>

          <div class="row">
               <div class="col-md-12">
                   
                  <div class="ticket-header__textarea">
                     <div class="row">
                        <div class="col-md-12">
                           <form action="<?php echo site_url("admin/tickets/read/".$ticketMessage[0]["ticket_id"]) ?>" method="post">
                              <div class="col-md-12">
                                 <div class="ticket-message-submit">
                                    <textarea name="message" id="" cols="30" rows="5" class="form-control ticket-edit__textarea"></textarea>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <button class="btn btn-primary click" type="submit">Submit</button>
                                 <div class="btn-group">
                                   <?php if( $ticketMessage[0]["client_new"] == 1 ): ?>
                                     <a href="<?php echo site_url("admin/tickets/unread/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Okunmadı olarak işaretle">Mark as Unread</a>
                                   <?php endif; if( $ticketMessage[0]["canmessage"] == 2 ): ?>
                                     <a href="<?php echo site_url("admin/tickets/lock/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Destek talebini kilitle">Lock Ticket</a>
                                   <?php else: ?>
                                     <a href="<?php echo site_url("admin/tickets/unlock/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Destek talebi kilidini aç">Unlock Ticket</a>
                                   <?php endif; if( $ticketMessage[0]["status"] != "closed" ): ?>
                                     <a href="<?php echo site_url("admin/tickets/close/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Destek talebi kapat">Close Ticket</a>
                                   <?php endif; ?>
</div>
                              </div>
                           </form>
							 
                            </div></div>
                           
                            </div></div></div>
                        
 <div class="row">
            <div class="col-md-12">
                          <div class="ticket-body">
                           <div class="ticket-message__container">
    <?php foreach($ticketMessage as $message): if( $message["support"] == 2 ): ?>
                              <div class="ticket-message__block ticket-message__support">
                                 <div class="ticket-message">
                                    <div class="ticket-message__title">
                                       <div class="row">
                                           <div class="col-sm-6">
                                             <strong>Support Team</strong>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="ticket-message__title-date">
                                          <?=$message["time"]?>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="ticket-message__text"><?=$message["message"]?></div>
                                 </div>   
<div class="ticket-message__footer">
<div class="btn-group ticket-btn-group" role="group" aria-label="...">
<a data-toggle="modal" data-target="#modalDiv" data-action="edit_ticket" data-id="<?=$message['id']?>">Edit</a> • <a href="<?php echo site_url("admin/tickets/remove/".$ticketMessage[0]["id"]) ?>">Delete</a>
    
</div></div>
                                             

                                  
                         </div>
                              <?php else: ?>
                                           <div class="ticket-message__block ticket-message__client">
                                 <div class="ticket-message">
                                    <div class="ticket-message__title">
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <strong><?=$message["username"]?></strong>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="ticket-message__title-date">
                                                <?=$message["time"]?>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="ticket-message__text"><?=str_replace("</script>","</ script >",str_replace("<script>","< script >",$message["message"]))?></div>
                                 </div>
                              </div>
                              <?php endif; endforeach; ?>
                           </div>
                        
                        </div>
                     </div>
                  </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include 'footer.php'; ?>
