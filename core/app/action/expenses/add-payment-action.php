<?php
if (count($_POST) > 0) {
    $payment = new OperationPaymentData();
    $payment->payment_type_id = $_POST["paymentTypeId"];
    $payment->operation_id = $_POST["expenseId"];
    $payment->operation_type_id = 1;
    $payment->total = $_POST["total"];
    $payment->date = $_POST["date"];
    $addedPayment = $payment->add();

    if ($addedPayment && $addedPayment[0]) {
        //Registrar log
        $operation = OperationData::getById($_POST["expenseId"]);
        $log = new LogData();
        $log->row_id = $addedPayment[1];
        $log->branch_office_id = $operation->branch_office_id;
        $log->user_id = $_SESSION["user_id"];
        $log->module_id = 9;
        $log->action_type_id = 1;
        $log->description = "Se agregó un nuevo pago al gasto con ID " . $addedOperation[1];
        $newLog = $log->add();
    }

    /*$operation = OperationData::getById($_POST["expenseId"]);
    $operation->created_at = $_POST["date"];
    $operation->updateDate();*/

    OperationDetailData::updateDate($_POST["expenseId"], $_POST["date"]);

    print "<script>window.location='index.php?view=expenses/edit&id=" . $_POST["expenseId"] . "';</script>";
}
