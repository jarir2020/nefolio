
<div class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data">
  
<div class="form-group">
          <label for="" class="control-label">Affiliate System</label>
          <select class="form-control" name="affiliates_status">
         
          <option value="1"  <?= $settings["referral_status"] == 1 ? "selected" : null; ?>>Disabled</option>
          <option value="2" <?= $settings["referral_status"] == 2 ? "selected" : null; ?>>Enabled</option>
          
          </select>
        </div>
  <div class="form-group">
          <label for="" class="control-label">Commission rate, %</label>
          <input type="number" class="form-control" name="commision" value="<?=$settings["referral_commision"]?>">
        </div>
        <div class="form-group">  
          <label for="" class="control-label">Minimum payout</label>
          <input type="number" class="form-control" name="minimum" value="<?=$settings["referral_payout"]?>">
        </div>
        
<hr>
<div class="childpanels-settings">
<div class="form-group">
          <label for="" class="control-label">Child Panel Selling</label>
          <select class="form-control" name="selling">
         
<option value="1"  <?= $settings[""] == 1 ? "selected" : null; ?>>Disabled</option>
          <option value="2" <?= $settings["childpanel_selling"] == 2 ? "selected" : null; ?>>Enabled</option>
          
          </select>
        </div>
        <div class="form-group">
          <label for="" class="control-label">Nameserver 1</label>
          <input type="text" class="form-control" name="ns1" value="<?=$settings["ns1"]?>">
        </div>
        <div class="form-group">  
          <label for="" class="control-label">Nameserver 2</label>
          <input type="text" class="form-control" name="ns2" value="<?=$settings["ns2"]?>">
        </div>

<div class="form-group">
<label for="" class="control-label">Child Panel Price</label>
<input type="text" class="form-control" name="price" value="<?=$settings["childpanel_price"]?>">
</div> 

</div>

<hr>


<div class="form-group">
          <label for="" class="control-label">Free Balance</label>
          <select class="form-control" name="freebalance">
         
                    <option value="1"  <?= $settings["freebalance"] == 1 ? "selected" : null; ?>>Disabled</option>
          <option value="2" <?= $settings["freebalance"] == 2 ? "selected" : null; ?>>Enabled</option>
          
          </select>
        </div>
<div class="form-group">
          <label for="" class="control-label">Free Amount</label>
          <input type="text" class="form-control" name="freeamount" value="<?=$settings["freeamount"]?>">
        </div> 
<hr>
<div class="form-group">
          <label for="" class="control-label">Video Promotion</label>
          <select class="form-control" name="promotion">
         
                    <option value="1"  <?= $settings["promotion"] == 1 ? "selected" : null; ?>>Disabled</option>
          <option value="2" <?= $settings["promotion"] == 2 ? "selected" : null; ?>>Enabled</option>
          
          </select>
        </div>

<div class="form-group">
          <label for="" class="control-label">Updates Logs</label>
          <select class="form-control" name="updates_show">
         
                    <option value="1"  <?= $general["updates_show"] == 1 ? "selected" : null; ?>>Disabled</option>
          <option value="2" <?= $general["updates_show"] == 2 ? "selected" : null; ?>>Enabled</option>
          
          </select>
        </div>


<div class="form-group">
          <label for="" class="control-label">Mass Order</label>
          <select class="form-control" name="massorder">
         
                    <option value="1"  <?= $general["massorder"] == 1 ? "selected" : null; ?>>Disabled</option>
          <option value="2" <?= $general["massorder"] == 2 ? "selected" : null; ?>>Enabled</option>
          
          </select>
        </div>


<hr>
        <center><button type="submit" class="btn btn-primary">Save Changes</button></center>
      </form>
      
    </div>
  </div>
</div>


<div class="col-md-8">
<?php  

$google_login = json_decode($settings["google_login"],true);

?>

<?php if($google_login["purchased"] != "1"){ ?>
<div data-addon="google_login" class="product-card">
  <div class="product-icon"><img src="https://i.postimg.cc/WbnmCB4D/google.png" alt="Product Icon"></div>
  <div class="product-details">
    <h2 class="product-name">Google Login Addon</h2>
    <p class="product-price">TK. 450.00</p>
  </div>
  <button class="buy-button">Buy Now</button>
</div>

<?php } else { ?>
 <div class="settings-emails__block-body">
<table>
<thead>
<tr>
<th class="settings-emails__th-name"></th>
<th class="settings-emails__th-actions"></th>
</tr>
</thead>
<tbody>
<tr class="settings-emails__row">
                    <td>
                        <div class="settings-emails__row-name">Google Login</div>
<div class="settings-emails__row-description">Users will be able to login with their Google Account to the panel.</div>
</td>

<td class="settings-emails__td-actions">  
    <label class="switch">
      <input  data-addon="google_login"  type="checkbox" class="switch-input addon"  <?php echo $google_login["status"] ? "checked" : "";?>>
      <span class="switch-label" data-on="On" data-off="Off"></span>
      <span class="switch-handle"></span>
    </label>

 </td>
</tr>
</tbody>
</table>

<?php } ?>



</div>