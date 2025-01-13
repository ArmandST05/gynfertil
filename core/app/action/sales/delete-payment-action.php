<?php
$payment = new OperationData();
$payment->delPayB($_GET["idP"]);

$tot=$_GET["total1"];
$totalGen=$_GET["totalGen2"];
      
$liq=$tot - $totalGen;

if($liq<=0){
$status = 1;
}else{
$status = 0;
}
  $op2 = new OperationData();
  $upt = $op2->updatedateFac1($_GET["idSell"],$_GET["total1"],$status);

Core::redir("./index.php?view=sales/edit&id=".$_GET["idSell"]."");
?>