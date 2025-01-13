<?php

if(count($_POST)>0){
	$con = CategorySpend::getByIdConSpend($_POST["idCon"]);

	$con->name = $_POST["name"];
	$con->cate = $_POST["cate"];
	$con->idCon = $_POST["idCon"];
	$con->updatecatSpend();

print "<script>window.location='index.php?view=conceptspend';</script>";


}


?>