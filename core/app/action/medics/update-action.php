<?php
	if(count($_POST)>0){
		$medic = MedicData::getById($_POST["id"]);
		$medic->name = strtoupper(trim($_POST["name"]));
		$medic->professional_license = trim($_POST["professional_license"]);
		$medic->education_level_id = $_POST["educationLevel"];
		$medic->study_center = trim($_POST["study_center"]);
		$medic->email = trim($_POST["email"]);
		$medic->phone = trim($_POST["phone"]);
		$medic->address = trim($_POST["address"]);
		$medic->category_id = $_POST["category_id"];	
		$medic->branch_office_id = $_POST["branch_office_id"];
		$medic->user_id = $_POST["user_id"];
		$medic->calendar_color = $_POST["calendar_color"];
		//$medic->is_active = $_POST["is_active"];
		$medic->update();

		//Registrar log
		$log = new LogData();
		$log->row_id = $medic->id;
		$log->branch_office_id = $medic->branch_office_id;
		$log->user_id = $_SESSION["user_id"];
		$log->module_id = 3;
		$log->action_type_id = 2;
		$log->description = "Se actualizó el psicólogo ".$medic->name." con ID:".$medic->id;
		$newLog = $log->add();

		print "<script>window.location='index.php?view=medics/index';</script>";
	}
?>