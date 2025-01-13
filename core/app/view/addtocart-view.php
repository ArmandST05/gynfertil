<?php
$price = $_POST["price"];

$products = ProductData::getById($_POST["product_id"]);
$type = $products->type;

if (!isset($_SESSION["cart"])) {
	$product = array("product_id" => $_POST["product_id"], "q" => $_POST["q"], "price" => $_POST["price"], "type" => $type);
	$_SESSION["cart"] = array($product);

	$cart = $_SESSION["cart"];

	$num_succ = 0;
	$process = false;
	$errors = array();
	foreach ($cart as $c) {

		if ($type == "MEDICAMENTO") {
			$q = OperationData::getStockByProduct($c["product_id"]);
		} else {
			$q = 100;
		}

		if ($c["q"] <= $q) {
			$num_succ++;
		} else {
			$error = array("product_id" => $c["product_id"], "message" => "No hay suficiente cantidad de producto en inventario.");
			$errors[count($errors)] = $error;
		}
	}

	if ($num_succ == count($cart)) {
		$process = true;
	}
	if ($process == false) {
		unset($_SESSION["cart"]);
		$_SESSION["errors"] = $errors;

		echo '<script>
			window.location="index.php?view=sales/new-details&idRes=' . $_POST["idRes"] . '&id_paciente=' . $_POST["idPac"] . '&idMed=' . $_POST["idMed"] . '&fecha=' . $_POST["fecha"] . '";
		</script>';
	}
} else {
	$found = false;
	$cart = $_SESSION["cart"];
	$index = 0;

	if ($type == "MEDICAMENTO") {
		$q = OperationData::getStockByProduct($_POST["product_id"]);
	} else {
		$q = 100;
	}

	$can = true;
	if ($_POST["q"] <= $q) {
	} else {
		$error = array("product_id" => $_POST["product_id"], "message" => "No hay suficiente cantidad de producto en inventario.");
		$errors[count($errors)] = $error;
		$can = false;
	}

	if ($can == false) {
		$_SESSION["errors"] = $errors;
		echo '<script>
				window.location="index.php?view=sales/new-details&idRes=' . $_POST["idRes"] . '&id_paciente=' . $_POST["idPac"] . '&idMed=' . $_POST["idMed"] . '&fecha=' . $_POST["fecha"] . '";
			</script>';
	}

	if ($can == true) {
		foreach ($cart as $c) {
			if ($c["product_id"] == $_POST["product_id"]) {
				$found = true;
				break;
			}
			$index++;
		}

		if ($found == true) {
			echo '<script> 
				alert("Ya está en la lista.");
				window.location="index.php?view=sales/new-details&idRes=' . $_POST["idRes"] . '&id_paciente=' . $_POST["idPac"] . '&idMed=' . $_POST["idMed"] . '&fecha=' . $_POST["fecha"] . '";
			</script>';
		}

		if ($found == false) {
			$nc = count($cart);
			$product = array("product_id" => $_POST["product_id"], "q" => $_POST["q"], "price" => $_POST["price"], "type" => $type);
			$cart[$nc] = $product;

			$_SESSION["cart"] = $cart;
		}
	}
}
print "<script>window.location='index.php?view=sales/new-details&idRes=" . $_POST["idRes"] . "&id_paciente=" . $_POST["idPac"] . "&idMed=" . $_POST["idMed"] . "&fecha=" . $_POST["fecha"] . "';</script>";

?>