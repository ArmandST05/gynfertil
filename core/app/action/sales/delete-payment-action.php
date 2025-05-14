<?php
$payment = OperationPaymentData::getById($_GET["paymentId"]);
$payment->delete();

$totalSale = $_GET["totalSale"];
$totalPayment = $_GET["totalPayment"];

$isLiquidated = $totalSale - $totalPayment;

if ($isLiquidated <= 0) $statusId = 1;
else $statusId = 0;

OperationDetailData::updateTotalStatus($_GET["saleId"], $_GET["totalSale"], $statusId);
$sale = OperationData::getById($_GET["saleId"]);
//Actualizar la cita vinculada a la venta para que muestre el último estatus de la venta en el calendario
if($sale->reservation_id != 0){
    ReservationData::updateLastSaleStatus($sale->reservation_id);
}

Core::redir("./index.php?view=sales/edit&id=" . $_GET["saleId"] . "");
