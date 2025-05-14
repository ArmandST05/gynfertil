<?php
if(count($_POST)>0){
	$company = CompanyData::getById($_POST["id"]);
	$company->name = $_POST["name"];
	
	if(!$company->update()) Core::alert("Ocurrió un error al actualizar.");
	else{
		//Registrar log
		$log = new LogData();
		$log->row_id = $company->id;
		$log->branch_office_id = 0;
		$log->user_id = $_SESSION["user_id"];
		$log->module_id = 5;
		$log->action_type_id = 2;
		$log->description = "Se actualizó la empresa ".$company->name." con ID:".$company->id;
		$newLog = $log->add();
	}
	print "<script>window.location='index.php?view=companies/index';</script>";
}
?>