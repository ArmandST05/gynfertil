<?php
if(count($_POST)>0){
  $reservation = ReservationData::getById($_POST["id"]);
  $reservation->user_id = $_POST["userId"];
  $reservation->medic_id = $_POST["medic"];
  $reservation->date_at = $_POST["date"]." ".$_POST["timeAt"];
  $reservation->date_at_final = $_POST["date"]." ".$_POST["timeAtFinal"];
  $reservation->reason = $_POST["reason"];
  $reservation->updateDoctor();

  //Registrar log
  $log = new LogData();
  $log->row_id = $reservation->id;
  $log->branch_office_id = $reservation->branch_office_id;
  $log->user_id = $_SESSION["user_id"];
  $log->module_id = 2;
  $log->action_type_id = 2;
  $log->description = "Se actualizó la cita para el paciente ".PatientData::getById($_POST["user_id"])->name." con el psicólogo ".MedicData::getById($_POST["medic"])->name." para el día ".$reservation->date_at;
  $newLog = $log->add();
  print "<script>window.location='index.php?view=home';</script>";
}
?>