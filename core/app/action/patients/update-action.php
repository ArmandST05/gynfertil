<?php
if(count($_POST)>0){

	$patient = PatientData::getById($_POST["patientId"]);
	$patient->name = strtoupper(trim($_POST["name"]));
	$patient->sex_id = $_POST["sexId"];
	$patient->calle = trim($_POST["calle"]);
	$patient->num = trim($_POST["num"]);
	$patient->col = trim($_POST["col"]);
	$patient->tel = trim($_POST["tel"]);
	$patient->tel2 = trim($_POST["tel2"]);
	$patient->email = trim($_POST["email"]);
	$patient->fecha_na = trim($_POST["birthday"]);
	$patient->ref = trim($_POST["ref"]);
	$patient->estatus = trim($_POST["estatus"]);

	$patient->relative_birthday = trim($_POST["relativeBirthday"]);

	//Eliminar cualquier relación registrada previamente que tenga la nueva pareja
	PatientData::deleteRelative($_POST["relativeId"]);
	PatientData::deleteRelative($_POST["patientId"]);

	//Si antes tenía una pareja registrada diferente, quitar la relación.
	if($patient->relative_id != 0 && $patient->relative_id != ""){
		PatientData::deleteRelative($patient->relative_id);
	}
	
	//Si tiene pareja registrada como paciente, agregar la pareja a ambos.
	if(isset($_POST["isRelativeRegistered"]) && $_POST["isRelativeRegistered"] == "true"){//Sin las comillas el true marca error en el servidor
		$patient->relative_name = "";
		$patient->relative_id = $_POST["relativeId"];

		//Asignar las parejas.
		$relative = PatientData::getById($_POST["relativeId"]);
		$relative->relative_id = $_POST["patientId"];
		$relative->updateRelative();
	}else{
		$patient->relative_name = strtoupper(trim($_POST["relativeName"]));
		$patient->relative_id = 0;
	}
	
	//Dato oficial del paciente rfc/curp/pasaporte, guardar sólo el que se seleccionó
	$patient->official_document_id = $_POST["officialDocumentId"];
	$patient->official_document_value = trim($_POST["officialDocumentValue"]);

	//Dato oficial de la pareja del paciente rfc/curp/pasaporte, guardar sólo el que se seleccionó
	$patient->relative_official_document_id = $_POST["relativeOfficialDocumentId"];
	$patient->relative_official_document_value = trim($_POST["relativeOfficialDocumentValue"]);

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
	}else{
		return http_response_code(500);
	}
//Core::alert("Actualizado exitosamente!");
//print "<script>window.location='index.php?view=patients/index&q=". $_POST["name"]."';</script>";

}
?>