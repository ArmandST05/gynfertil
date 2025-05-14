<?php
if(count($_POST)>0){
	$company = new CompanyData();
	$company->name = $_POST["name"];
	$newCompany = $company->add();

	if($newCompany && $newCompany[1]){
		//Registrar log
		$log = new LogData();
		$log->row_id = $newCompany[1];
		$log->branch_office_id = 0;
		$log->user_id = $_SESSION["user_id"];
		$log->module_id = 5;
		$log->action_type_id = 1;
		$log->description = "Se agregó la empresa ".$company->name." con ID:".$newCompany[1];
		$newLog = $log->add();
	}else Core::alert("Ocurrió un error al agregar.");

	print "<script>window.location='index.php?view=companies/index';</script>";
}
?>