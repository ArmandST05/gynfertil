<?php
if(count($_POST)>0){

    $date = date("Y-m-d H:i:s");

    $operation = new OperationData();
    $operation->user_id = $_SESSION["user_id"];
    $operation->branch_office_id = $_POST["branchOfficeId"];;
    $operation->operation_type_id = 1;
    $operation->total = $_POST["quantity"];
    $operation->operation_category_id = 4;
    $operation->created_at = $date;
    $newOperation = $operation->addInputInventory();

    if($newOperation && $newOperation[1]){
        $operationDetail = new OperationDetailData();
        $operationDetail->operation_id = $newOperation[1];
        $operationDetail->product_id = $_POST["id"];
        $operationDetail->operation_type_id = 1;
        $operationDetail->sale_id = 0;
        $operationDetail->quantity = $_POST["quantity"];
        $operationDetail->price = 0;
        $operationDetail->expiration_date = $_POST["expirationDate"];
        $operationDetail->date = $date;
        $add = $operationDetail->addInputInventory();	
    }

    print "<script>window.location='index.php?view=inventory/index-products&searchBranchOfficeId=".$_POST["branchOfficeId"]."';</script>";
}
?>