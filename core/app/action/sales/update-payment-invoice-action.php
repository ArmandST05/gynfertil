<?php
$payment = new OperationData();
$payment->updatePaymentIsInvoice($_POST["paymentId"],$_POST["isInvoice"]);

//Core::redir("./index.php?view=sales/edit&id=".$_GET["saleId"]."");
?>