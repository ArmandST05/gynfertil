<?php
if(!isset($_SESSION["payments"])){

    /*if($_POST["paymentTypeId"] == 2 || $_POST["paymentTypeId"] == 3){
		//Forma de pago en tarjeta se agrega la comisión
       $money = $_POST["money"]*1.015;
	}else{
	   $money = $_POST["money"];
	}*/
	$money = $_POST["money"];

	$newPayment = array("idType"=>$_POST["paymentTypeId"],"money"=>$money,"bankAccountId"=>$_POST["bankAccountId"],"isInvoice"=>$_POST["isInvoice"]);
	$_SESSION["payments"] = array($newPayment);

    //Forma de pago en tarjeta se agrega la comisión como un nuevo artículo en el detalle de venta.
    /*if($_POST["paymentTypeId"] == 2 || $_POST["paymentTypeId"] == 3){
		$cart = $_SESSION["cart"];//Se obtienen los datos del carrito
		$cort = $_POST["money"] * 0.015;//Se calcula el precio de la comisión.

		$newProduct = array("product_id"=>"13","q"=>"1","price"=>$cort,"type"=>"CONCEPTO");
		$cart[] = $newProduct;
		$_SESSION["cart"] = $cart;//Se actualiza el detalle de venta agregando la comisión
    }	*/
}
else{
	$payments = $_SESSION["payments"];//Pagos agregados

	//Se verifica que no exista el tipo de pago en la lista.
	foreach($payments as $payment){
		if($payment["idType"] == $_POST["paymentTypeId"]){
			$existingPayment = true; 
			break;
			//Si ya se agregó el método de pago redireccionamos y mostramos alerta
			echo '<script> 
					alert("El tipo de pago ya esta en la lista.");
					window.location="index.php?view=sales/new-details&idRes='.$_POST["idRes"].'&id_paciente='.$_POST["id_paciente"].'&idMed='.$_POST["idMed"].'&fecha='.$_POST["fecha"].'";
				</script>';	
		}
	}
	if(!isset($existingPayment) || $existingPayment == false){
		//Agregamos el nuevo tipo de pago ya que no se ha agregado.

		/*if($_POST["paymentTypeId"] == 2 || $_POST["paymentTypeId"] == 3){
			//Forma de pago en tarjeta se establece el valor de la comisión
			$money = $_POST["money"]*1.015;
		}else{
			$money = $_POST["money"];
		}*/
		$money = $_POST["money"];

		$newPayment = array("idType" => $_POST["paymentTypeId"],"money" => $money,"bankAccountId"=>$_POST["bankAccountId"],"isInvoice"=>$_POST["isInvoice"]);
		$payments[] = $newPayment;//Añadir el nuevo pago 
		$_SESSION["payments"] = $payments;
		
		//Forma de pago en tarjeta se agrega la comisión como un nuevo artículo en el detalle de venta.
		/*if($_POST["paymentTypeId"] == 2 || $_POST["paymentTypeId"] == 3){
			$cort = $_POST["money"] * 0.015;//Se calcula el precio de la comisión.
			$cart = $_SESSION["cart"];//Se obtienen los datos del carrito.

			$newProduct = array("product_id"=>"13","q"=>"1","price"=>$cort,"type"=>"CONCEPTO");
			$cart[] = $newProduct;
			$_SESSION["cart"] = $cart;//Se actualiza el detalle de venta agregando la comisión
		}*/
	}
}
print "<script>window.location='index.php?view=sales/new-details&idRes=".$_POST["idRes"]."&id_paciente=".$_POST["id_paciente"]."&idMed=".$_POST["idMed"]."&fecha=".$_POST["fecha"]."';</script>";
?>
