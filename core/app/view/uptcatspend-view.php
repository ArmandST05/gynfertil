<?php

if(count($_POST)>0){
	$cat = CategorySpend::getByIdCat($_POST["idCat"]);
	$cat->name = $_POST["name"];
	$cat->updateCat();
print "<script>window.location='index.php?view=catspend';</script>";


}


?>