<?php
$dateAt = $_POST["date"]." ".$_POST["timeAt"];
$repeatedReservation = ReservationData::getMedicRepeatedReservation($_POST["medic"],$dateAt);//Validar cita repetida
$patient = PatientData::getById($_POST["patient"]);
/*$repeatedLaboratory = ReservationData::getRepeatedLaboratory($dateAt,$_POST["laboratory"]);//Validar disponibilidad de consultorio/laboratorio
$patientValidate = PatientData::getValidatePatientCategory($_POST["patient"],3);//Validar si el paciente no está inactivo (no tiene ningún tratamiento).
if($patientValidate != null){
    Core::alert("El paciente actualmente no está en ningún tratamiento.");
}
else if($repeatedLaboratory != null){
    Core::alert("El consultorio ya tiene un psicólogo asignado");
}
else */

if($repeatedReservation){
    Core::alert("Este psicólogo ya tiene una cita asignada en ese horario.");
    Core::redir("./index.php?view=home&date=". $_POST["date"]."");
}elseif(!isset($_POST["branchOfficeId"])){
    Core::alert("No se especifió la sucursal en que se guarda la cita, regístrala nuevamente.");
    Core::redir("./index.php?view=home&date=". $_POST["date"]."");
}elseif($patient->category_id == 3){
    Core::alert("El paciente está inactivo, no puedes agendarle citas.");
    Core::redir("./index.php?view=home&date=". $_POST["date"]."");
}elseif($patient->category_id == 2){
    Core::alert("El paciente está dado de alta, no puedes agendarle citas.");
    Core::redir("./index.php?view=home&date=". $_POST["date"]."");
}else{
    //CALCULAR CATEGORÍA DE LA CITA
    //1 Primera vez
    //2 Subsecuente
    //3 Reingreso
   $category = 0;

   $treatments = TreatmentData::getAllPatientTreatments($_POST["patient"],1);
   if ($treatments && count($treatments) > 0) {
      $actualTreatment = $treatments[0];
      $totalReservationsData = $actualTreatment->getTotalReservations();
      $totalReservations = ($totalReservationsData) ? $totalReservationsData->total:0;

      if($patient->category_id == 1){//Activo
        if ($totalReservations > 0) {
            $category = 2; //Subsecuente del primer tratamiento
        } else {
            $category = 1; //Primera vez
        }
      }else if($patient->category_id == 4){//Reingreso
        if ($totalReservations > 0) {
            $category = 2; //Subsecuente del reingreso
         } else {
            $category = 3; //Reingreso primera cita
         }
      }

      /*if (count($treatments) > 1) { //Tiene varios tratamientos
         if ($totalReservations > 0) {
            $category = 2; //Subsecuente del reingreso
         } else {
            $category = 3; //Reingreso primera cita
         }
      } else { //Sólo tiene un tratamiento
         if ($totalReservations > 0) {
            $category = 2; //Subsecuente del primer tratamiento
         } else {
            $category = 1; //Primera vez
         }
      }*/
   } else {//No hay tratamientos para el paciente
      $category = 1; //Primera vez
   }

    $reservation = new ReservationData();
    $reservation->patient_id = $_POST["patient"];
    $reservation->medic_id = $_POST["medic"];
    $reservation->laboratory_id = $_POST["laboratory"];
    $reservation->category_id = $category;
    $reservation->branch_office_id = $_POST["branchOfficeId"];
    $reservation->area_id = $_POST["area"];
    $reservation->date_at = $dateAt;
    $reservation->date_at_final = $_POST["date"]." ".$_POST["timeAtFinal"];
    $reservation->reason = strtoupper(trim($_POST["reason"]));
    $reservation->user_id =  $_POST["userId"];
    $newReservation = $reservation->addPatient();
    if($newReservation && $newReservation[0]){
        //Registrar log
        $log = new LogData();
        $log->row_id = $newReservation[1];
        $log->branch_office_id = $reservation->branch_office_id;
        $log->user_id = $_SESSION["user_id"];
        $log->module_id = 2;
        $log->action_type_id = 1;
        $log->description = "Se agregó una cita para el paciente ".PatientData::getById($_POST["patient"])->name." con el psicólogo ".MedicData::getById($_POST["medic"])->name." para el día ".$reservation->date_at;
        $newLog = $log->add();
        
        Core::redir("./index.php?view=home&date=". $_POST["date"]."");
    }
    else{
        Core::alert("Ocurrió un problema al guardar la cita");
        Core::redir("./index.php?view=home&date=". $_POST["date"]."");
    }

}
