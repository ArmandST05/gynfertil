<?php
//Obtener configuración para validar si se agregará una comisión automática cada vez que se registre un pago de tarjeta
/*$configuration = ConfigurationData::getAll();
$isCardCommission = $configuration["active_card_commission"];
$totalCardCommission = (isset($configuration["card_commission_value"]) ? $configuration["card_commission_value"] : 0);*/
$operation = OperationData::getById($_POST["saleId"]);

if (count($_POST) > 0 || !$operation) {
  $totalSale = $operation->total;
  $totalPayment = OperationPaymentData::getTotalByOperationId($_POST["saleId"])->total;
  $actualPayment = $totalPayment + floatval($_POST["total"]);

  //Validar si la venta se liquidó o no
  $isLiquidated = $totalSale - $actualPayment;
  if ($isLiquidated == 0) $statusId = 1;
  else $statusId = 0;

  //Validar que la venta no esté liquidada
  //Validar si la cantidad a pagar no supera el total de la venta
  //Validar que la cantidad que coloques realmente liquide la venta
  if($operation->status_id == 1 || $actualPayment > $totalSale || $statusId == 0){
    //Cantidad pagada no puede superar el total de la venta
    return http_response_code(500);
  }
  else{
    $paymentDetail = new OperationPaymentData();
    $paymentDetail->payment_type_id = $_POST["paymentType"];
    $paymentDetail->operation_id = $_POST["saleId"];
    $paymentDetail->date = $_POST["date"]." ".date("H:i:s");
    $paymentDetail->total = $_POST["total"];
    $paymentDetail = $paymentDetail->add();

    if($paymentDetail[0]){    //Actualizar datos de la venta (estatus y fecha de creación)
      $operation->status_id = $statusId;
      $operation->updateStatus();
      $operation->created_at = $_POST["date"]." ".date("H:i:s");
      $operation->updateDate();
      $operation->description = strtoupper(trim($_POST["description"]));
      $operation->updateDescription();

      //Actualizar la cita vinculada a la venta para que muestre el último estatus de la venta en el calendario
      if($operation->reservation_id != 0){
        ReservationData::updateLastSaleStatus($operation->reservation_id);
      }

      OperationDetailData::updateDate($_POST["saleId"], ($_POST["date"]." ".date("H:i:s")));

      //Registrar log
      $log = new LogData();
      $log->row_id = $paymentDetail[1];
      $log->branch_office_id = $operation->branch_office_id;
      $log->user_id = $_SESSION["user_id"];
      $log->module_id = 8;
      $log->action_type_id = 1;
      $log->description = "Se liquidó la venta del paciente " . PatientData::getById($operation->patient_id)->name . " del día ".$operation->created_at .".";
      $newLog = $log->add();
      
      return http_response_code(200);
    }else{
      return http_response_code(500);
    }
  }

}else{
  return http_response_code(500);
}
