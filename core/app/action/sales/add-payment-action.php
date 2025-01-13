<?php
if(count($_POST)>0){
	 $op = new OperationData();

  $op->idType = $_POST["idTypePay"] ;
  $op->sell_id=$_POST["idSell"];
  $op->fecha = $_POST["date"];
  $op->bank_account_id = $_POST["bankAccountId"];
  $op->is_invoice = $_POST["isInvoice"];

  /*if($_POST["idTypePay"]==2 || $_POST["idTypePay"]==3){
    $op->money=$_POST["money"]*1.015;

    $cort= $_POST["money"] * 0.015;
    $op1 = new OperationData();
    $op1->product_id = 13;
    $op1->operation_type_id=2; // 1 - entrada
    $op1->sell_id= $_POST["idSell"];
    $op1->q= "1";
    $op1->price= $cort;
    $op1->is_oficial = 1;
    $op1->date = $_POST["date"];
    
    $add = $op1->addU();
  }else
  {
    $op->money=$_POST["money"];
  }*/
  $op->money=$_POST["money"];
  
  $op = $op->addPay1();
  $tot=$_POST["total1"];
  $totalGen=$_POST["totalGen2"];
    
  $liq=$tot - $totalGen;

  if($liq==0){
  $status = 1;
  }else{
  $status = 0;
  }
  $op2 = new OperationData();
  $upt = $op2->updatedateFac($_POST["idSell"],$_POST["date"],$_POST["total1"],$status); 


  $op3 = new OperationData();
  $upt2 = $op3->updatedateFacdet($_POST["idSell"],$_POST["date"]);  

  print "<script>window.location='index.php?view=sales/edit&id=".$_POST["idSell"]."';</script>";
}


?>