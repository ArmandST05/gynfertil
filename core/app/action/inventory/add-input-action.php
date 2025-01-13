<?php
if(count($_POST)>0){
    $op = new OperationData();
    $op->product_id = $_POST["productId"];
    $op->operation_type_id = 1;//Entrada
    $op->q = $_POST["q"];
    $op->expiration_date = $_POST["expirationDate"];
    $op->lot = $_POST["lot"];
    $add = $op->addInput();		

    Core::redir("./index.php?view=inventory/index-medicines");
}
?>