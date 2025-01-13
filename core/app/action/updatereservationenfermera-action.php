<?php
/**
* BookMedik
* @author evilnapsis
* @url http://evilnapsis.com/about/
**/

	$user = ReservationData::getById($_POST["id"]);
	$user->id = $_POST["id"];
	$user->datos = $_POST["datos"];
	$user->tel = $_POST["tel"];
	$user->update_asis();

    $patient = PatientData::getById($_POST["patientId"]);
	$patient->calle = $_POST["calle"];
	$patient->num = $_POST["num"];
	$patient->col = str_replace('"',"'",$_POST["col"]);
	$patient->tel = $_POST["tel"];
	$patient->tel2 = $_POST["tel2"];
	$patient->email = $_POST["email"];
	$patient->fecha_na = $_POST["formfecha"];
	$patient->ref = $_POST["ref"];
	$patient->edad = $_POST["age"];

	//GURDAR DATOS DE PAREJA Y DATOS DE OFICIALES DE AMBOS
	$patient->relative_birthday = trim($_POST["relativeBirthday"]);

	//Eliminar cualquier relación registrada previamente que tenga la nueva pareja
	PatientData::deleteRelative($_POST["relativeId"]);
	PatientData::deleteRelative($_POST["patientId"]);

	//Si antes tenía una pareja registrada diferente, quitar la relación.
	if($patient->relative_id != 0 && $patient->relative_id != ""){
		PatientData::deleteRelative($patient->relative_id);
	}
	
	//Si tiene pareja registrada como paciente, agregar la pareja a ambos.
	if(isset($_POST["isRelativeRegistered"]) && $_POST["isRelativeRegistered"] == true){//El true tiene que ser sin comillas o marcaría error en el servidor
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
    $patient->update2();
    //Core::alert("actualizado exitosamente!");
    print "<script>window.location='index.php?view=home';</script>";
 
?>