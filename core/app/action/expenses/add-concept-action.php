<?php
date_default_timezone_set('America/Mexico_City');
if(count($_POST)>0){
$newDate = ($_POST["expirationDate"]." ".date("H:i:s"));
 $op = new OperationDetailData();
 $op->product_id = $_POST["conceptId"];
 $op->operation_type_id=1; // 1 - entrada
 $op->operation_id=$_POST["expenseId"];
 $op->quantity= $_POST["quantity"];
 $op->price= $_POST["cost"];
 $op->date = $newDate;
 $op->expiration_date= $newDate;
 $add = $op->addExpense();	
 
 OperationDetailData::updateDate($_POST["expenseId"],$newDate);
 OperationData::updateExpenseDate($_POST["expenseId"],$newDate);

print "<script>window.location='index.php?view=expenses/edit&id=".$_POST["expenseId"]."';</script>";
}


?>