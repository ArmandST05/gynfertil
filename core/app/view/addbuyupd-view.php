<?php

if(count($_POST)>0){
 
 $op = new OperationData();
 $op->product_id = $_POST["idCon"];
 $op->operation_type_id=1; // 1 - entrada
 $op->sell_id= $_POST["idBuy"];
 $op->q= $_POST["q"];
 $op->price= $_POST["cost"];
 $op->is_oficial = 1;
 $op->date = date("Y-m-d H:i:s"); 
 $op->cad= $_POST["cad"];
 $add = $op->addCompras();		

print "<script>window.location='index.php?view=buyUpd&id=".$_POST['idBuy']."&date=".$_POST['date']."';</script>";


}


?>