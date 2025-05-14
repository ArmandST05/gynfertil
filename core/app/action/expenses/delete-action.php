<?php
$operation = OperationData::getById($_GET["id"]);

if ($operation->delete()) {
    OperationDetailData::deleteByOperationId($_GET["id"]);
    OperationPaymentData::deleteByOperationId($_GET["id"]);

    //Registrar log
    $log = new LogData();
    $log->row_id = $operation->id;
    $log->branch_office_id = $operation->branch_office_id;
    $log->user_id = $_SESSION["user_id"];
    $log->module_id = 9;
    $log->action_type_id = 3;
    $log->description = "Se eliminó el gasto con ID:".$operation->id;
    $newLog = $log->add();
}
Core::redir("./index.php?view=expenses/index");
