<?php
  $totalSale = floatval($_POST["totalSale"]);
  $totalPayment = floatval($_POST["totalPayment"]);

  $pendingPayment = $totalSale - $totalPayment;
  if($pendingPayment <= 0) $statusId = 1;
  else $statusId = 0;
  
    $operation = OperationData::getById($_POST["saleId"]);
    $operation->total = $_POST["totalSale"];
    $operation->updateTotal($_POST["saleId"],$_POST["totalSale"]);  
    $operation->status_id = $statusId;
    $operation->updateStatus();  
    $operation->description = $_POST["description"];
    $operation->updateDescription();  

    //Actualizar la cita vinculada a la venta para que muestre el último estatus de la venta en el calendario
    if($operation->reservation_id != 0){
      ReservationData::updateLastSaleStatus($operation->reservation_id);
    }

    //Registrar log
    $log = new LogData();
    $log->row_id = $_POST["saleId"];
    $log->branch_office_id = $operation->branch_office_id;
    $log->user_id = $_SESSION["user_id"];
    $log->module_id = 8;
    $log->action_type_id = 2;
    $log->description = "Se actualizó la venta del paciente " . PatientData::getById($operation->patient_id)->name . " del día ".$operation->created_at .".";
    $newLog = $log->add();

  print "<script>window.location='index.php?view=sales/index';</script>";

?>
