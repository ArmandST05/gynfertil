<?php
if(count($_POST)>0){
	$branchOffice = new BranchOfficeData();
	$branchOffice->name = $_POST["name"];
	$branchOffice->add();

print "<script>window.location='index.php?view=branch-offices/index';</script>";
}
?>