<?php

if(count($_POST)>0){
	$branchOffice = BranchOfficeData::getById($_POST["id"]);
	$branchOffice->name = $_POST["name"];
	$branchOffice->update();
	print "<script>window.location='index.php?view=branch-offices/index';</script>";
}
?>