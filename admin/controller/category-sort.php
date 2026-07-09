<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}

if ($admin["access"]["update-prices"] != 1):
header("Location:" . site_url("admin"));
exit();
endif;

$categories = $conn->prepare("SELECT * FROM categories ORDER BY category_line ASC");
$categories->execute();
$categories = $categories->fetchAll(PDO::FETCH_ASSOC);
$list .= "";
for($i = 0;$i < count($categories);$i++){
 $list .= "<li data-category-id=\"".$categories[$i]["category_id"]."\" class=\"list-group-item\"><span class=\"category-sort-handle\">=</span>".$categories[$i]["category_name"]."</li>";
 
}
if($_POST){
$action = $_POST["action"];
if($action == "sort_category"){
$category_list_array = json_decode(base64_decode($_POST["category_list"]), true);
array_unshift($category_list_array,"");
unset($category_list_array[0]);
foreach($category_list_array as $index => $category_id){
  $update_position = $conn->prepare("UPDATE categories SET category_line=:category_line WHERE category_id=:category_id");
  $update_position->execute(array(
   "category_line" => $index,
   "category_id" => $category_id
  ));
}
exit();
}
}

require admin_view("category-sort");
?>