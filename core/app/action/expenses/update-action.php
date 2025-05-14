<?php
$operation = OperationData::getById($_POST["expenseId"]);

$tot = $_POST["total"];
$totalGen = $_POST["totalGen"];
$liq = $tot - $totalGen;

if ($liq == 0) {
    $operation->status_id = 1;
} else {
    $operation->status_id = 0;
}
$operation->total = $_POST["total"];
$operation->description = $_POST["description"];
$updated = $operation->updateExpense();

//Registrar log
$log = new LogData();
$log->row_id = $_POST["expenseId"];
$log->branch_office_id = $operation->branch_office_id;
$log->user_id = $_SESSION["user_id"];
$log->module_id = 9;
$log->action_type_id = 2;
$log->description = "Se editó el gasto con ID ".$operation->id;
$newLog = $log->add();

print "<script>window.location='index.php?view=expenses/index';</script>";
