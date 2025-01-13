<?php
if(isset($_POST["paymentTypeId"]) && isset($_POST["isInvoice"])){
	
	$payments = $_SESSION["payments"];
	
	foreach($payments as $index =>$payment){

		if($payment["idType"] == $_POST["paymentTypeId"]){
			$_SESSION["payments"][$index]["isInvoice"] = $_POST["isInvoice"];
		}
	}
}

?>