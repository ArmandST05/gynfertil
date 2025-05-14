<?php
if(count($_POST)>0){
	$patient = PatientData::getById($_POST["user_id"]);
	$patient->name = strtoupper(trim($_POST["name"]));
	$patient->sex_id = $_POST["sex"];
	$patient->curp = strtoupper(trim($_POST["curp"]));
	$patient->relative_name = strtoupper(trim($_POST["relative_name"]));
	$patient->street = strtoupper(trim($_POST["street"]));
	$patient->number = strtoupper($_POST["number"]);
	$patient->colony = strtoupper(trim($_POST["colony"]));
	$patient->cellphone = trim($_POST["cellphone"]);
	$patient->homephone = trim($_POST["homephone"]);
	$patient->email = trim($_POST["email"]);
	$patient->birthday = $_POST["birthday"];
	$patient->referred_by = strtoupper($_POST["referred_by"]);
	$patient->company_id = $_POST["companyId"];
	//$patient->category_id = $_POST["category_id"];
	$patient->observations = strtoupper($_POST["observations"]);
    $patient->branch_office_id = trim($_POST["branchOfficeId"]);
    $patient->county_id = trim($_POST["countyId"]);
    $patient->education_level_id = $_POST["educationLevelId"];
    $patient->occupation = strtoupper(trim($_POST["occupation"]));

	if($patient->image != null){
		$url = "/storage_data/patients/".$patient->image;
		$deleteImage =  getcwd().$url;
		unlink($deleteImage);
	}
	$patient->image = ""; 

	if(strlen($_POST['image'])>6){
		$image_data = $_POST["image"];

		$image_array_1 = explode(";", $image_data);
		$image_array_2 = explode(",", $image_array_1[1]);

		$image_data = base64_decode($image_array_2[1]);
		$imageName = time().'.jpg';

		if(file_put_contents("storage_data/patients/".$imageName, $image_data)){
			$patient->image = $imageName;
		}
	}

	if($patient->update()){
		return http_response_code(200);

		//Registrar log
		$log = new LogData();
		$log->row_id = $patient->id;
		$log->branch_office_id = $patient->branch_office_id;
		$log->user_id = $_SESSION["user_id"];
		$log->module_id = 1;
		$log->action_type_id = 2;
		$log->description = "Se actualizó el paciente ".$patient->name." con ID:".$patient->id;
		$newLog = $log->add();
	}
	else{
		return http_response_code(500);
	}
}
else{
	return http_response_code(500);
}
?>