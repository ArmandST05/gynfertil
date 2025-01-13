<?php

if(count($_POST)>0){
	$product = ProductData::getById($_POST["product_id"]);

	$product->name = $_POST["name"];
    $product->inventary_min = $_POST["inventary_min"];

	$product->user_id = $_SESSION["user_id"];
	$product->updateS();

	
	//setcookie("prdupd","true");
	print "<script>window.location='index.php?view=supplies';</script>";


}


?>