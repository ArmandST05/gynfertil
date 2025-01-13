<?php
$process="true";
if(isset($_SESSION["buy"])){
	
	$buy = $_SESSION["buy"];

//////////////////////////////////
		if($process==true){
			$sell = new SellData();
			$sell->user_id = $_SESSION["user_id"];

			$sell->total = $_POST["total"];
			$tot=$_POST["total"];
			$totalGen=$_POST["totalGen"];
			$date = $_POST["dateBuy"];
			$date = $date." ".date("H:i:s");
			$sell->date = date_format(date_create($date),"Y-m-d H:i:s");
        
            $liq=$tot - $totalGen;

            if($liq==0){
            $sell->status = 1;
            }else{
            $sell->status = 0;
            }
            $sell->note = $_POST["note"];
			

 			$s = $sell->addBuyS();
	        
		foreach($buy as  $c){


			 $op = new OperationData();

			 $op->product_id = $c["idCon"] ;
			 $op->operation_type_id=1; // 1 - entrada
			 $op->sell_id=$s[1];
			 $op->q= $c["q"] ;
             $op->price= $c["cost"];
			 $op->date = date_format(date_create($date),"Y-m-d H:i:s");
 
             $op->cad= $c["cad"];
			
             $add = $op->addCompras();		 		

			unset($_SESSION["buy"]);
			setcookie("selled","selled");
		}

		if(isset($_SESSION["typePBuy"])){
		 $pay = $_SESSION["typePBuy"];


		 foreach($pay as  $p){

			 $op = new OperationData();
			 $op->idType = $p["idType"] ;
			 $op->buyId=$s[1];
			 $op->money= $p["money"];
			 $op->date = date_format(date_create($date),"Y-m-d H:i:s");

			 $add = $op->addPayB();			 		

			unset($_SESSION["typePBuy"]);
			setcookie("selled","selled");
		}
	}


print "<script>window.location='index.php?view=expenses';</script>";
		}
	}
?>