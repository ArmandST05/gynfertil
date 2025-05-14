<?php

$medic = MedicData::getById($_GET["id"]);
$medic->del();

//Registrar log
$log = new LogData();
$log->row_id = $medic->id;
$log->branch_office_id = $medic->branch_office_id;
$log->user_id = $_SESSION["user_id"];
$log->module_id = 3;
$log->action_type_id = 3;
$log->description = "Se eliminó el psicólogo ".$medic->name." con ID:".$medic->id;
$newLog = $log->add();

Core::redir("./index.php?view=medics/index");

?>