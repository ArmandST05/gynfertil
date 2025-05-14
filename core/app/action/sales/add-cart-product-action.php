<?php
$price = $_POST["price"];
$productData = ProductData::getById($_POST["productId"]);
$branchOfficeId = ReservationData::getById($_POST["reservationId"])->branch_office_id;

//Validar existencia del producto
if ($productData) {
	$typeId = $productData->type_id;
	$typeName = $productData->getType()->name;
	if (!isset($_SESSION["cart"])) {
		//CREAR CARRITO DE COMPRA Y AGREGAR PRODUCTO
		$isStock = false;
		$errors = array();

		//VALIDAR EXISTENCIAS (Medicamentos/Insumos)
		if ($typeId == 3 || $typeId == 4) {
			$stock = OperationDetailData::getStockByBranchOfficeProduct($branchOfficeId,$_POST["productId"]);

			//Si hay stock añadimos, si no registramos error
			if (floatval($_POST["quantity"]) > $stock) {
				$isStock = false;
				$error = array("id" => $_POST["productId"], "message" => "No hay suficiente cantidad de producto en inventario.");
				$errors[count($errors)] = $error;
				$_SESSION["errors"] = $errors;
			} else $isStock = true;
		} else $isStock = true; //Conceptos ingresos

		if ($isStock == true) {
			$product = array("id" => $_POST["productId"], "quantity" => $_POST["quantity"], "price" => $_POST["price"], "typeId" => $typeId, "typeName" => $typeName);
			$_SESSION["cart"] = array($product);
		}
	} else {
		//AGREGAR AL CARRITO
		$isCartAdded = false;
		$cart = $_SESSION["cart"];
		$isStock = false;

		//VALIDAR EXISTENCIAS (Medicamentos/Insumos)
		if ($typeId == 3 || $typeId == 4) {
			$stock = OperationDetailData::getStockByBranchOfficeProduct($branchOfficeId,$_POST["productId"]);
			//Verificamos si hay suficiente stock
			if (floatval($_POST["quantity"]) > $stock) {
				$isStock = false;
				$error = array("id" => $_POST["productId"], "message" => "No hay suficiente cantidad de producto en inventario.");
				$errors[] = $error;
				$_SESSION["errors"] = $errors;
			} else {
				$isStock = true;
			}
		} else $isStock = true; //Conceptos ingresos

		//Si hay suficiente existencia procedemos a ver si el producto no está repetido para agregarlo.
		if ($isStock == true) {
			foreach ($cart as $cartDetail) {
				if ($cartDetail["id"] == $_POST["productId"]) {
					$isCartAdded = true;
					break;
				}
			}

			if ($isCartAdded == true) {
				echo '<script> 
				alert("Ya está en la lista.");
				window.location="index.php?view=sales/new-details&reservationId=' . $_POST["reservationId"] . '&patientId=' . $_POST["patientId"] . '&medicId=' . $_POST["medicId"] . '&date=' . $_POST["date"] . '";
			</script>';
			} else {
				$product = array("id" => $_POST["productId"], "quantity" => $_POST["quantity"], "price" => $_POST["price"], "typeId" => $typeId, "typeName" => $typeName);
				$cart[] = $product;
				$_SESSION["cart"] = $cart;
			}
		}
	}
	print "<script>window.location='index.php?view=sales/new-details&reservationId=" . $_POST["reservationId"] . "&patientId=" . $_POST["patientId"] . "&medicId=" . $_POST["medicId"] . "&date=" . $_POST["date"] . "';</script>";
} else {
	echo '<script> 
		alert("No seleccionaste ningún producto.");
		window.location="index.php?view=sales/new-details&reservationId=' . $_POST["reservationId"] . '&patientId=' . $_POST["patientId"] . '&medicId=' . $_POST["medicId"] . '&date=' . $_POST["date"] . '";
	</script>';
}
