<?php
//Obtiene la categoría de la cita en base a los datos de las citas del paciente.
if (count($_POST) > 0) {
   //1 Primera vez
   //2 Subsecuente
   //3 Reingreso
   $category = 0;
   $patient = ReservationData::getById($_POST["patientId"]);

   $treatments = TreatmentData::getAllPatientTreatments($_POST["patientId"]);
   if ($treatments) {
      $actualTreatment = $treatments[0];
      $totalReservations = $actualTreatment->getTotalReservations()->total;

      if (count($treatments) > 1) { //Tiene varios tratamientos
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
      }
   } else {//No hay tratamientos para el paciente
      $category = 1; //Primera vez
   }
   echo $category;
} else {
   return http_response_code(500);
}
