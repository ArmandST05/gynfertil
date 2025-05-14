<?php
if(count($_POST)>0){
      $reservation = ReservationData::getById($_POST["reservationId"]);

      if($reservation && ($_SESSION['typeUser'] == "su" || ($reservation))){
         $reservation->reservation_id = $_POST["reservationId"];
         $reservation->status_id = $_POST["statusId"];
         $updatedReservation = $reservation->updateStatus();

         if($updatedReservation[0]){
            $status = ['id' => $reservation->status_id,'name' =>$reservation->getStatus()->name];

            //Registrar log
            $log = new LogData();
            $log->row_id = $reservation->id;
            $log->branch_office_id = $reservation->branch_office_id;
            $log->user_id = $_SESSION["user_id"];
            $log->module_id = 2;
            $log->action_type_id = 2;
            $log->description = "Se actualizó al estatus ".ReservationStatusData::getById($_POST['statusId'])->name." la cita del paciente ".PatientData::getById($reservation->patient_id)->name." con el psicólogo ".MedicData::getById($reservation->medic_id)->name." el día ".$reservation->date_at;
            $newLog = $log->add();
            /*
            $cancelationSale = OperationData::getByReservationProductStatus($reservation->patient_id, 14, "all");

            if($_POST["statusId"] == 4 && $_SESSION["userType"] != "su" && substr($reservation->date_at,0,10) == date("Y-m-d") && !$cancelationSale){//Estatus == Cancelado y no es un administrador y no se le ha generado ninguna venta de cancelación
               //Registrar venta con concepto de Cancelación(14)
               $lastTreatment = TreatmentData::getLastPatientTreatment($reservation->patient_id);

               $salePrice = ($lastTreatment) ? ($lastTreatment->default_price*.5): 0;

               $sale = new OperationData();
               $sale->user_id = $_SESSION["user_id"];
               $sale->total = $salePrice;
               $sale->discount = 0;
               $sale->description = "";
               $sale->date = $reservation->date_at;
               $sale->reservation_id = $_POST["reservationId"];
               $sale->status_id = 0;
               $sale->patient_id = $reservation->patient_id;
               $sale->branch_office_id = $reservation->branch_office_id;
               $newSale = $sale->addSale($reservation->patient_id, $reservation->medic_id);

               if($newSale[0]){
                  //Concept Detail
                  $opDetail = new OperationDetailData();
                  $opDetail->product_id = 14;//Cancelación
                  $opDetail->operation_type_id = 2;
                  $opDetail->operation_id = $newSale[1];
                  $opDetail->quantity = 1;
                  $opDetail->price = $salePrice;
                  $opDetail->date = $reservation->date_at;
                  $add = $opDetail->add();
               }

               //Registrar log
               $log = new LogData();
               $log->row_id = $newSale[1];
               $log->branch_office_id = $reservation->branch_office_id;
               $log->user_id = $_SESSION["user_id"];
               $log->module_id = 8;
               $log->action_type_id = 1;
               $log->description = "Se agregó una nueva venta automática por cancelación de cita del paciente " . PatientData::getById($reservation->patient_id)->name . " el día ".$reservation->date .".";
               $newLog = $log->add();
            }*/
            
            echo json_encode($status);
         }
         else return http_response_code(500);
      }else{
         return http_response_code(500);
      }
}
else{
   return http_response_code(500);
}
