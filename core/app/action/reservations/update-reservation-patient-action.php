<?php
if(count($_POST)>0){
   $dateAt = $_POST["date"]." ".$_POST["timeAt"];
   $repeatedReservation = ReservationData::getMedicRepeatedReservation($_POST["medic"],$dateAt);//Validar cita repetida
   /*$repeatedLaboratory = ReservationData::getRepeatedLaboratory($dateAt,$_POST["laboratory"]);//Validar disponibilidad de consultorio
   
   if($repeatedLaboratory != null && $repeatedLaboratory->id != $_POST["reservationId"]){
      Core::alert("El consultorio ya tiene un psicólogo asignado");
      print "<script>history.back();</script>";
   }
   else{*/
   if($repeatedReservation && $repeatedReservation->id != $_POST["reservationId"]){
      Core::alert("Este psicólogo ya tiene una cita asignada en ese horario.");
      Core::redir("./index.php?view=home&date=". $_POST["date"]."");
   }else{
      //CALCULAR CATEGORÍA DE LA CITA
      //1 Primera vez
      //2 Subsecuente
      //3 Reingreso
      $category = 0;
      $patient = PatientData::getById($_POST["patient"]);

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

      $reservation = ReservationData::getById($_POST["reservationId"]);
      $originalDate = $reservation->date_at;

      $reservation->patient_id = $_POST["patient"];
      $reservation->medic_id = $_POST["medic"];
      $reservation->laboratory_id = $_POST["laboratory"];
      $reservation->category_id = $category;
      $reservation->branch_office_id = $_POST["branchOfficeId"];
      $reservation->area_id = $_POST["area"];
      $reservation->date_at = $dateAt;
      $reservation->date_at_final = $_POST["date"]." ".$_POST["timeAtFinal"];
      $reservation->reason = $_POST["reason"];
      $reservation->user_id =  $_POST["userId"];
      $reservation->updatePatient();

      if($originalDate != $dateAt){
         $reservation->is_patient_notified = 0;
         $reservation->updateNotifiedPatient();
         $description = "Se actualizó la fecha de la cita del paciente ".PatientData::getById($_POST["patient"])->name." del día ".$originalDate." al ".$dateAt." y se marcó como pendiente de notificar al paciente";
      }elseif($originalReservation->medic_id != $reservation->medic_id){
            $description = "Se actualizó el psicólogo de la cita del paciente ".PatientData::getById($_POST["patient"])->name." del día ".$reservation->date_at." con el psicólogo ".MedicData::getById($originalReservation->medic_id)->name." y ahora lo atenderá: ".MedicData::getById($_POST["medic"])->name."";
      
      }else{
         $description = "Se actualizaron los datos de la cita del paciente ".PatientData::getById($_POST["patient"])->name." con el psicólogo ".MedicData::getById($_POST["medic"])->name." para el día ".$reservation->date_at." ";
      }

      //Registrar log
      $log = new LogData();
      $log->row_id = $reservation->id;
      $log->branch_office_id = $reservation->branch_office_id;
      $log->user_id = $_SESSION["user_id"];
      $log->module_id = 2;
      $log->action_type_id = 2;
      $log->description = $description;
      $newLog = $log->add();

      print "<script>window.location='index.php?view=home';</script>";
   }
   //}
}
else Core::alert("Ingresa los datos para guardar la cita");
?>