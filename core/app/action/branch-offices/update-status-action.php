<?php
if(count($_GET)>0){
	$branchOffice = BranchOfficeData::getById($_GET["id"]);
	$branchOffice->is_active = (($_GET["isActive"] == 0 || $_GET["isActive"] == 1) ?  $_GET["isActive"]: 1);
	$branchOffice->updateStatus();
	print "<script>window.location='index.php?view=branch-offices/index';</script>";
}
?>