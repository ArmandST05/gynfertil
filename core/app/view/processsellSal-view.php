

<?php
$process=true;
if(isset($_SESSION["cartSal"])){
	$cart = $_SESSION["cartSal"];
	

//////////////////////////////////
		if($process==true){
			$sell = new SellData();
			$sell->user_id = $_SESSION["user_id"];

			$sell->total =0;
			$sell->note = $_POST["note"];
          // echo $_SESSION["user_id"];
 		    $s = $sell->add_sal();
		
       
		foreach($cart as  $c){


			 $op = new OperationData();
			 $op->product_id = $c["product_id"] ;
			 $op->operation_type_id=OperationTypeData::getByName("salida")->id;
			 $op->sell_id=$s[1];
			 $op->q= $c["q"];
			 $op->price= 0;
			 

			if(isset($_POST["is_oficial"])){
				$op->is_oficial = 1;
			}

			$add = $op->add();			 		

			unset($_SESSION["cartSal"]);
			setcookie("selled","selled");
		}


		}

		//echo "hola";
		print "<script>window.location='index.php?view=res';</script>";
	}


?>
