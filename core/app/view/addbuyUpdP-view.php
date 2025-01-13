<?php

if(count($_POST)>0){
	 $op = new OperationData();
	 $op2 = new OperationData();
	 $op3 = new OperationData();

    $op->idType = $_POST["idTypePay"] ;
    $op->buyId=$_POST["idBuy"];
    $op->money= $_POST["money"];
    
    $date = $_POST["date"];
    $date = $date." ".date("H:i:s");
    $op->date = date_format(date_create($date),"Y-m-d H:i:s");
    $op->fecha = date_format(date_create($date),"Y-m-d H:i:s");

    
	$op = $op->addPayB();

	$upt = $op2->UpdateDateExp($_POST["idBuy"], $_POST["dateBuy"]);	
    $u = $op3->updatedateFacdetE($_POST["idBuy"], $_POST["dateBuy"]);
    
		

    print "<script>window.location='index.php?view=buyUpd&id=".$_POST["idBuy"]."&date=".$_POST["dateBuy"]."';</script>";


}


?>