<?php
   if(count($_POST)>0){
      $reservation = ReservationData::getById($_POST["reservationId"]);
      $reservation->is_patient_notified = $_POST["value"];
      $updated = $reservation->updateNotifiedPatient();
      if($updated && $updated[0]){
         return http_response_code(200);
         //Registrar log
         $log = new LogData();
         $log->row_id = $reservation->id;
         $log->branch_office_id = $reservation->branch_office_id;
         $log->user_id = $_SESSION["user_id"];
         $log->module_id = 2;
         $log->action_type_id = 2;
         if($_POST["value"] == 0){
            $log->description = "Se marcó como no notificado al paciente ".PatientData::getById($reservation->patient_id)->name." de su cita con el psicólogo ".MedicData::getById($reservation->medic_id)->name." para el día ".$reservation->date_at;
         }else{
            $log->description = "Se marcó como notificado al paciente ".PatientData::getById($reservation->patient_id)->name." de su cita con el psicólogo ".MedicData::getById($reservation->medic_id)->name." para el día ".$reservation->date_at;
         }
         $newLog = $log->add();
      }
      else{
         return http_response_code(500);
      }
   }
?>