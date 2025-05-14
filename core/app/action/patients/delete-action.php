<?php
$patient = PatientData::getById($_GET["id"]);
$patient->delete();
//Eliminar citas posteriores
$deleteFutureReservations = ReservationData::deleteAllFutureByPatientId($_GET["id"]);

//Registrar log
$log = new LogData();
$log->row_id = $patient->id;
$log->branch_office_id = $patient->branch_office_id;
$log->user_id = $_SESSION["user_id"];
$log->module_id = 1;
$log->action_type_id = 3;
$log->description = "Se eliminó el paciente ".$patient->name." con ID:".$patient->id;
$newLog = $log->add();

Core::alert("¡Eliminado exitosamente!");
print "<script>window.location='index.php?view=patients/index';</script>";
?>