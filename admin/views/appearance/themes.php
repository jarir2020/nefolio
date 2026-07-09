<?php if( !route(3) ): ?>

<style>.btn-custom {
  font-size: 16px;
  color: white;
  background-color: #ff6f00;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
}

.btn-default {
  font-size: 15px;
  color: white;
  background-color: #0eab00;

  border-radius: 5px;
}


</style>

<div class="col-md-8">
<table class="table">
<thead>
<tr>
<th>Theme</th>
<th>Last modified</th>
<th></th>
</tr>
</thead>
<tbody>

<?php foreach($themes as $theme): ?>
<tr>
<td> <?php echo $theme["theme_name"]; if( $settings["site_theme"] == $theme["theme_dirname"] ): echo ' <span class="badge">Active</span>'; endif; ?> 
</td>
<td> <?php echo $theme["last_modified"]; ?>   <?php if( $settings["site_theme"] == $theme["theme_dirname"] ):
 if( $theme["colour"] == "2" && $theme["theme_dirname"] == "Simplify"):
echo ' 
<div class="dropdown pull-right">
<button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Colour Change <span class="caret"></span></button>
<ul class="dropdown-menu">
<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
<select class="form-control" name="site_theme_alt">                     <option value="Red" >Red</option>
                                                                        <option value="Blue " >Blue</option>
                                                                        <option value="Lime" >Lime</option>
                                                                        <option value="Grapes" >Grapes</option>
                                                                        <option value="Dark" >Dark</option>
																		<option value="Cyan" >Cyan</option>
																		<option value="Coral" >Coral</option>
																		<option value="Green" >Green</option>
																		<option value="Grey" >Grey</option>
																		<option value="Lilac" >Lilac</option>
<option value="Orange">Orange</option>
          </select>
        </div> 
     <center>  
        <button type="submit" class="btn btn-default">Update</button></center>
      </form>
				 </ul>';
endif;
if( $theme["colour"] == "2" && $theme["theme_dirname"] == "Eternity"):
echo ' 
<div class="dropdown pull-right">
<button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Colour Change <span class="caret"></span></button>
<ul class="dropdown-menu">
<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
<select class="form-control" name="site_theme_alt">
<option value="lilac" >Eternity Lilac</option>
<option value="coral" >Eternity Coral</option>
<option value="azure" >Eternity Azure</option>
<option value="grey" >Eternity Grey</option>
<option value="lime" >Eternity Lime</option>
<option value="navy" >Eternity Navy</option>
<option value="pink" >Eternity Pink</option>
<option value="raspberry" >Eternity Raspberry</option>
<option value="cyan" >Eternity Cyan</option>
<option value="purple" >Eternity Purple</option>
</select>
        </div> 
     <center>  
        <button type="submit" class="btn btn-default">Update</button></center>
      </form>
				 </ul>';

endif;
if( $theme["colour"] == "2" && $theme["theme_dirname"] == "pitchy"):
echo ' 
<div class="dropdown pull-right">
<button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Colour Change <span class="caret"></span></button>
<ul class="dropdown-menu">
<form action="" method="post" enctype="multipart/form-data">
<div class="form-group">
<select class="form-control" name="site_theme_alt">
<option value="green">Clementine Green</option>
<option value="parrot">Clementine Parrot</option>
<option value="orange">Clementine Orange</option>
</select>
        </div> 
     <center>  
        <button type="submit" class="btn btn-default">Update</button></center>
      </form>
				 </ul>';
endif;

endif; ?></td>
            <td class="text-right col-md-1">
              <div class="dropdown pull-right">
				  
                <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Options <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <?php if( $settings["site_theme"] != $theme["theme_dirname"] ): ?>
                    <li>
                      <a href="<?php echo site_url('admin/appearance/themes/active/'.$theme["id"]) ?>">
                        Activate
                      </a>
                    </li>
                  <?php endif; ?>
                  <li>
                    <a href="<?php echo site_url('admin/appearance/themes/'.$theme["id"]) ?>">
                    Edit
                    </a>
                  </li>
                </ul>
              </div>
            </td>
			
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>

<div style="border:1px solid lightgrey;margin-top:10px;" class="col-md-12">
<h4>Eternity Theme Settings</h4>
<hr>
<span style="font-weight:bold;">
Summary card background color</span> 
<ul class="list-group">
    <li class="list-group-item <?php
if($settings["summary_card_background_color"] == "theme_colour"){
    echo "active";
}

?>"><a class="nav-link" href="<?php echo site_url("admin/appearance/themes/eternity-settings/theme_colour");?>">Theme colour</a></li>
 <li class="list-group-item  <?php
if($settings["summary_card_background_color"] == "fixed_colour"){
    echo "active";
}

?>"><a class="nav-link" href="<?php echo site_url("admin/appearance/themes/eternity-settings/fixed_colour");?>">Fixed colour</a></li>
</ul>

</div>

</div>
 
 
 
 
<?php elseif( route(3) ):


?>
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading edit-theme-title"><strong><?php echo $theme["theme_name"] ?></strong> edit the theme named</div>

        <div class="row">
          <div class="col-md-3 padding-md-right-null">

            <div class="panel-body edit-theme-body">
              <div class="twig-editor-block">
                <?php





                  $layouts  = [
                    "HTML"=>[
"header.twig","footer.twig","account.twig","addfunds.twig","api.twig","child-panels.twig",
                    "login.twig","neworder.twig","open_ticket.twig","orders.twig","refill.twig","signup.twig",
                    "services.twig","tickets.twig","refer.twig","dripfeeds.twig","subscriptions.twig",
                    "resetpassword.twig","setnewpassword.twig","updates.twig","blog.twig","blogpost.twig",
                    "terms.twig","faq.twig"],
                    "CSS"=>["bootstrap.css","style.css"],
                    "JS"=>["bootstrap.js","script.js"]
                  ];
                foreach ($layouts as $style => $layout):
                  echo '<div class="twig-editor-list-title" data-toggle="collapse" href="#folder_'.$style.'"><span class="fa fa-folder-open"></span>'.$style.'</div><ul class="twig-editor-list collapse in" id="folder_'.$style.'">';
                  foreach ($layouts[$style] as $layout) :
                    if( $lyt == $layout ):
                      $active = ' class="active file-modified" ';
                    else:
                      $active = '';
                    endif;
                    echo '
                      <li '. $active .'><a href="'.site_url('admin/appearance/themes/'.$theme["id"]).'?file='.$layout.'">'.$layout.'</a></li>';
                  endforeach;
                  echo '</ul>';
                endforeach;
              ?>
              </div>

            </div>
          </div>
          <div class="col-md-9 padding-md-left-null edit-theme__block-editor">
            <?php if( !$lyt ): ?>
              <div class="panel-body">
                <div class="row">
                   <div class="col-md-12">
                    <div class="theme-edit-block">
                      <div class="alert alert-info" role="alert">
                       Select a file from the left sidebar to start editing.
                      </div>
                    </div>
                  </div>
                  </div>
              </div>
            <?php else: ?>
                  
                  <div id="fullscreen">

               <div class="panel-body">

                <?php
                $file = fopen($fn, "r");
                $size = filesize($fn);
                $text = fread($file, $size); // -> Kodu okur
                $text = str_replace("<","&lt;",$text);
                $text = str_replace(">","&gt;",$text);
                $text = str_replace('"',"&quot;",$text);
                fclose($file); // -> Kapat覺r
                ?>

                <div class="row">
                    <div class="col-md-8">
                      <strong class="edit-theme-filename"><?=$dir."/".$lyt?></strong>
                        </div>
                        <div class="col-md-4 text-right">
                                    <a class="btn btn-xs btn-default fullScreenButton">
                                        <span class="glyphicon glyphicon-fullscreen"></span>
                                        Edit Full Screen </a>
                                </div>
                  </div>
           

                <form action="<?php echo site_url("admin/appearance/themes/".$theme["id"]."?file=".$lyt) ?>" method="post" class="twig-editor__form">
                  <textarea id="code" name="code" class="codemirror-textarea"><?=$text;?></textarea>
                  <div class="edit-theme-body-buttons text-right">
                      
                    <button class="btn btn-primary click">Save</button>
                  </div>
                </form>

              </div>
            <?php endif; ?>
          </div>
        </div>

    </div>
  </div>


<?php endif; ?>









    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
      <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script>