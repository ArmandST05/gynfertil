<?php
if(count($_POST)>0){
	$product = ProductData::getById($_POST["product_id"]);
	$product->barcode = $_POST["barcode"];
	$product->name = $_POST["name"];
	$product->price_in = $_POST["price_in"];
	$product->price_out = $_POST["price_out"];
  	$product->inventary_min = $_POST["inventary_min"];
	$product->presentation = $_POST["presentation"];
  	$product->brand = $_POST["brand"];
	$is_active=0;
	if(isset($_POST["is_active"])){ $is_active=1;}

	$product->is_active=$is_active;
	$product->user_id = $_SESSION["user_id"];
	$product->update();
	
	setcookie("prdupd","true");
	Core::redir("./index.php?view=products/edit&id=".$_POST["product_id"]);
}
?>