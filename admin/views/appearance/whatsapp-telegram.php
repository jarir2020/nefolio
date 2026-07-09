
<div class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data">

         

           <div class="form-group">
          <label class="control-label">Whatsapp Button</label>
        
          <select class="form-control" name="whatsappbutton">
            
           	 	  <option value="0" <?= $settings["whatsappbutton"] == 0 ? "selected" : null; ?> >Passive</option>
            <option value="1" <?= $settings["whatsappbutton"] == 1 ? "selected" : null; ?>>Active</option>
														
          </select>
        </div> 
    
       <div class="form-group">
          <label class="control-label">Whatsapp No. { With country code }</label>
          <input type="text" class="form-control" name="whatsappnumber" value="<?=$settings["whatsappnumber"]?>" >
        </div>
        
        <div class="form-group">
          <label class="control-label">Whatsapp Auto Massage</label>
          <input type="text" class="form-control" name="whatsappcolour" value="<?=$settings["whatsappcolour"]?>">
        </div>
        <div class="form-group">
          <label class="control-label">Whatsapp Buttton Position</label>
            <select class="form-control" name="whatsappposition">
            
           	 	  <option value="left" <?= $settings["whatsappposition"] == "left" ? "selected" : null; ?> >Left</option>
            <option value="right" <?= $settings["whatsappposition"] == "right" ? "selected" : null; ?>>Right</option>
														
          </select>
          
          
        </div>
        <hr>
        <hr>
        <div class="form-group">
          <label class="control-label">Theme Background Image</label>
          <input type="text" class="form-control" name="psw_img" value="<?=$settings["theme_bg_img"]?>" >
        </div>
        <hr><hr>
         <div class="form-group">
          <label class="control-label">Telegram Button</label>
        
          <select class="form-control" name="telegrambutton">
            
           	 	  <option value="0" <?= $settings["telegrambutton"] == 0 ? "selected" : null; ?> >Passive</option>
            <option value="1" <?= $settings["telegrambutton"] == 1 ? "selected" : null; ?>>Active</option>
														
          </select>
        </div> 
    
       <div class="form-group">
          <label class="control-label">Telegram Username { Without @ }</label>
          <input type="text" class="form-control" name="telegramusername" value="<?=$settings["telegramusername"]?>" >
        </div>
        
        
        <div class="form-group">
          <label class="control-label">Telegram Button Position</label>
            <select class="form-control" name="telegramposition">
            
           	 	  <option value="left" <?= $settings["telegramposition"] == "left" ? "selected" : null; ?> >Left</option>
            <option value="right" <?= $settings["telegramposition"] == "right" ? "selected" : null; ?>>Right</option>
														
          </select>
          
          
        </div>
        <button type="submit" class="btn btn-primary">Update Settings</button>
      </form>
    </div>
  </div>
</div>
