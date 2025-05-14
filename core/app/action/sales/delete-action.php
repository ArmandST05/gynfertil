<?php
$sale = OperationData::getById($_GET["id"]);
$operations = OperationDetailData::getAllProductsByOperationId($_GET["id"]);

foreach ($operations as $operation) {
	$operation->delete();
}
$sale->delete();
OperationPaymentData::deleteByOperationId($_GET["id"]);

//Actualizar la cita vinculada a la venta para que muestre el último estatus de la venta en el calendario
if($sale->reservation_id != 0){
	ReservationData::updateLastSaleStatus($sale->reservation_id);
}

//Registrar log
$log = new LogData();
$log->row_id = $_GET["saleId"];
$log->branch_office_id = $sale->branch_office_id;
$log->user_id = $_SESSION["user_id"];
$log->module_id = 8;
$log->action_type_id = 3;
$log->description = "Se eliminó la venta del paciente " . PatientData::getById($sale->patient_id)->name . " del día ".$sale->date .".";
$newLog = $log->add();
Core::redir("./index.php?view=sales/index");
?>