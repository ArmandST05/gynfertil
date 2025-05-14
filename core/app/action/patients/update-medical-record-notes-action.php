<?php
if(count($_POST)>0){
      $patient = PatientData::getById($_POST["patientId"]);

      if($patient){
         $patient->notes = $_POST["notes"];
         if($patient->updateNotes()) {
            return http_response_code(200);
            //Registrar log
            $log = new LogData();
            $log->row_id = $patient->id;
            $log->branch_office_id = $patient->branch_office_id;
            $log->user_id = $_SESSION["user_id"];
            $log->module_id = 1;
            $log->action_type_id = 2;
            $log->description = "Se actualizaron las notas del paciente ".$patient->name." con ID:".$patient->id;
            $newLog = $log->add();
         }
         else return http_response_code(500);
      } 
}
else{
   return http_response_code(500);
}
?>