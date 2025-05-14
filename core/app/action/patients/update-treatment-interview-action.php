<?php
if(count($_POST)>0){
	if(isset($_POST["name"])){
		$patient = PatientData::getById($_POST["patientId"]);
		$patient->name = strtoupper(trim($_POST["name"]));
		$patient->birthday = $_POST["birthday"];
		$patient->education_level_id = $_POST["educationLevel"];
		$patient->occupation = (isset($_POST["occupation"])) ? trim($_POST["occupation"]): "";
		$patient->relative_name = (isset($_POST["relative_name"])) ? trim($_POST["relative_name"]):"";
		$patient->street = trim($_POST["street"]);
		$patient->number = $_POST["number"];
		$patient->colony = trim($_POST["colony"]);
		$patient->cellphone = $_POST["cellphone"];
		$patient->homephone = $_POST["homephone"];
		$patient->county_id = trim($_POST["countyId"]);
		$updatedPatient = $patient->updateByInterview();
	}
	else{
		//Entrevista de pareja
		$patient = PatientData::getById($_POST["patientId"]);
		if(!empty(trim($_POST["details"][77])) && !empty(trim($_POST["details"][100]))){
			$patient->name = strtoupper(trim($_POST["details"][77])." | ". trim($_POST["details"][100]));
		}
		$patient->street = trim($_POST["street"]);
		$patient->number = $_POST["number"];
		$patient->colony = trim($_POST["colony"]);
		$patient->county_id = trim($_POST["countyId"]);
		$updatedPatient = $patient->updateByInterview();
	}

	if($updatedPatient){
		//Guardar detalles por entrevista
		//Eliminar detalles existentes del tratamiento
		$deleteDetails = TreatmentData::deleteDetailsByPatientTreatment($_POST["patientTreatmentId"]);
		foreach ($_POST["details"] as $index => $detail) {
			$patientDetail = TreatmentData::getDetailByPatientTreatment($_POST["patientTreatmentId"],$index);

			//Crear nuevo registro
			$patientDetail = new TreatmentData();
			$patientDetail->patient_treatment_id = $_POST["patientTreatmentId"];
			$patientDetail->interview_detail_id = $index;
			if(is_array($detail)){
				$patientDetail->value = arrayToString($detail);
			}
			else{
				$patientDetail->value = strtoupper($detail);
			}
			$patientDetail->addDetailByPatientTreatment();
		}

		//Registrar log
		$log = new LogData();
		$log->row_id = $patient->id;
		$log->branch_office_id = $patient->branch_office_id;
		$log->user_id = $_SESSION["user_id"];
		$log->module_id = 1;
		$log->action_type_id = 2;
		$log->description = "Se actualizaron los datos de la entrevista del paciente ".$patient->name." con ID:".$patient->id;
		$newLog = $log->add();
		print "<script>window.location='index.php?view=patients/medical-record&patientId=".$_POST["patientId"]."';</script>";
	}
	else{
		return http_response_code(500);
	}
}
else{
	return http_response_code(500);
}
function arrayToString($array){
	return implode(",", $array);
}
