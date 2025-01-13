<?php
if(isset($_SESSION["cart"])){
	$cart = $_SESSION["cart"];
	if(count($cart)>0){
		/// antes de proceder con lo que sigue vamos a verificar que:
		// haya existencia de productos
		// si se va a facturar la cantidad a facturar debe ser menor o igual al producto facturado en inventario
		$num_succ = 0;
		$process = false;
		$errors = array();
		foreach($cart as $c){

			$q = OperationData::getStockByProduct($c["product_id"]);

            if($c["type"] == "CONCEPTO"){
            	$num_succ++;
            }
			else if($c["q"] <= $q){
				if(isset($_POST["is_oficial"])){
					$qyf = OperationData::getStockByProduct($c["product_id"]); //Son los productos que puedo facturar
					if($c["q"]<=$qyf){
						$num_succ++;
					}else{
					$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto para facturar en inventario.");					
					$errors[count($errors)] = $error;
					}
				}else{
					// Si llegue hasta aquí y no voy a facturar, entonces se continúa ...
					$num_succ++;
				}
			}else{
				$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
				$errors[count($errors)] = $error;
			}
		}

		if($num_succ == count($cart)){
			$process = true;
		}

		if($process == false){
			$_SESSION["errors"] = $errors;

			echo '<script>
				window.location="index.php?view=sales/new-details&id_paciente='.$_POST["id_paciente"].'&idMed='.$_POST["idMed"].'";
			</script>';
		}

		if($process == true){
			$sell = new SellData();
			$sell->user_id = $_SESSION["user_id"];

			$sell->total = $_POST["total"];
			$sell->discount = $_POST["discount"];
			$sell->note = $_POST["note"];
            
            $tot=$_POST["total"];
 			$totalGen=$_POST["totalGen"];
 			$sell->date =$_POST["date"];

 			if($_POST["idRes"]==""){
 				$sell->idRes=0;
 			}else{
 				$sell->idRes = $_POST["idRes"];
            }
           
            $liq=$tot - $totalGen;

            if($liq <= 0){
            	$sell->status = 1;
            }else{
            	$sell->status = 0;
            }

			if(isset($_POST["id_paciente"]) && $_POST["id_paciente"] != ""){
				$sell->person_id=$_POST["id_paciente"];
				$s = $sell->add_with_client($_POST["id_paciente"],$_POST["idMed"]);
			}

       
			foreach($cart as  $c){
				$op = new OperationData();
				$op->product_id = $c["product_id"] ;
				$op->operation_type_id=OperationTypeData::getByName("salida")->id;
				$op->sell_id=$s[1];
				$op->q = $c["q"];
				$op->price= $c["price"];
				$op->date =$_POST["date"];

				if(isset($_POST["is_oficial"])){
					$op->is_oficial = 1;
				}

				$add = $op->add();			 		

				unset($_SESSION["cart"]);
				setcookie("selled","selled");
			}

			if(isset($_SESSION["payments"])){
				$pay = $_SESSION["payments"];
				$totP = 0;
				$t = 0;
				foreach($pay as  $p){

					$op = new OperationData();
					$op->idType = $p["idType"] ;
					$op->date = $_POST["date"];
					$op->bank_account_id = $p["bankAccountId"];
					$op->is_invoice = $p["isInvoice"];
					if($p["idType"]==1)
					{
						$totP = $_POST["totalGen"] - $_POST["total"];
						$t = $p["money"]-$totP;	
						$op->money = $t;
					}
					else{
						$op->money= $p["money"];
					}

					$op->sell_id = $s[1];
					
					$add = $op->addPay();			 		

					unset($_SESSION["payments"]);
					setcookie("selled","selled");
				}
			}

			print "<script>window.location='index.php?view=onesell&id=$s[1]';</script>";
		}
	}
}
