<?php

if(count($_POST)>0){
	$con = CategorySpend::getByIdConIncome($_POST["idCon"]);

	$con->name = $_POST["name"];
	$con->idCon = $_POST["idCon"];
	$con->updatecatIncome();

print "<script>window.location='index.php?view=concepts';</script>";


}


?>