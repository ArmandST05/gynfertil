<?php

if(count($_POST)>0){
	$user = ExpenseCategoryData::getById($_POST["user_id"]);
	$user->name = $_POST["name"];
	$user->update();
print "<script>window.location='index.php?view=categories';</script>";


}


?>