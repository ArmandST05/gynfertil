<?php
$saleDetails = OperationDetailData::getAllByOperationId($_POST["saleId"]);
$sale = OperationData::getById($_POST["saleId"]);
$product = ProductData::getById($_POST["productId"]);
$typeId = $product->type_id;

$numSucc = 0;
$isStock = false;
$errors = array();
$isProductAdded = false;
$index = 0;

//Validar si hay suficiente inventario
if ($typeId == "3" || $typeId == "4") {
	$quantity = OperationDetailData::getStockByBranchOfficeProduct($sale->branch_office_id,$_POST["productId"]);

	if ($_POST["quantity"] <= $quantity) {
		$numSucc++;
		$isStock = true;
	} else {
		$error = array("productId" => $_POST["productId"], "message" => "No hay suficiente cantidad de producto en inventario.");
		$errors[count($errors)] = $error;
	}

} else $isStock = true;//Conceptos ingresos

if ($isStock == false) {
	$_SESSION["editSaleErrors"] = $errors;
	echo '<script>
		window.location="index.php?view=sales/edit&id=' . $_POST["saleId"] . '";
	</script>';
}
else if ($isStock == true) {
	//Si hay suficiente existencia procedemos a ver si el producto no está repetido para agregarlo.
	foreach ($saleDetails as $detail) {
		if ($detail->product_id == $_POST["productId"]) {
			$isProductAdded = true;
			break;
		}
		$index++;
	}
	if ($isProductAdded == true) {
		echo '<script> 
			alert("El producto ya está en la lista");
			window.location="index.php?view=sales/edit&id=' . $_POST["idSell"] . '";
		</script>';
	}else if($isProductAdded == false) {
		$opDetail = new OperationDetailData();
		$opDetail->product_id = $_POST["productId"];
		$opDetail->operation_type_id = 2;
		$opDetail->operation_id = $_POST["saleId"];
		$opDetail->quantity = $_POST["quantity"];
		$opDetail->price = $_POST["price"];
		$opDetail->date = date("Y-m-d H:i:s");
		$add = $opDetail->add();

		//Registrar log
		$log = new LogData();
		$log->row_id = $add[1];
		$log->branch_office_id = $sale->branch_office_id;
		$log->user_id = $_SESSION["user_id"];
		$log->module_id = 8;
		$log->action_type_id = 1;
		$log->description = "Se agregó un producto/concepto a la venta del paciente " . PatientData::getById($sale->patient_id)->name . " del día ".$sale->date .".";
		$newLog = $log->add();
	}
}
print "<script>window.location='index.php?view=sales/edit&id=" . $_POST["saleId"] . "';</script>";
?>