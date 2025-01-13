<?php
if(count($_POST)>0){
	$patient = PatientData::getById($_POST["patientId"]);
	$patient->updateMalePatientAsDonant();
	print "<script>history.back();</script>";
}
?>