<?php
   if(count($_POST)>0){
      if($_POST["value"] == 0){//Se marcó no avisado, eliminar registro
         $update = PatientNotificationData::deleteById($_POST["notifiedId"]);
      }
      else{
          //Avisado,agregar registro
         $notification = new PatientNotificationData();
         $notification->patient_id = $_POST["patientId"];
         $notification->branch_office_id = $_POST["branchOfficeId"];
         $notification->medic_id = $_POST["medicId"];
         $notification->date = date("Y-m-d");
         $notification->patient_notification_type_id = 1;//notificación a NO AGENDADOS
         $update = $notification->add();
      }
      if($update && $update[0]){
         //Registrar log
         $log = new LogData();
         $log->row_id = $_POST["patientId"];
         $log->branch_office_id = $_POST["branchOfficeId"];
         $log->user_id = $_SESSION["user_id"];
         $log->module_id = 2;
         $log->action_type_id = 2;
         if($_POST["value"] == 0){
            $log->description = "Se marcó como NO NOTIFICADO al paciente ".PatientData::getById($_POST["patientId"])->name." que está pendiente de agendar.";
         }else{
            $log->description = "Se marcó como NOTIFICADO al paciente ".PatientData::getById($_POST["patientId"])->name." que está pendiente de agendar.";
         }
         $newLog = $log->add();
         return http_response_code(200);
      }
      else{
         return http_response_code(500);
      }
   }
?>