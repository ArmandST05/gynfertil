<?php
if(isset($_GET["productId"])){
	$cart = $_SESSION["cart"];
	
		$newCart = null;
		foreach($cart as $cartDetail){
			if($cartDetail["id"] != $_GET["productId"]){
				$newCart[]= $cartDetail;
			}
		}
		$_SESSION["cart"] = $newCart;
	}


print "<script>window.location='index.php?view=sales/new-details&reservationId=".$_GET['reservationId']."&patientId=".$_GET['patientId']."&medicId=".$_GET['medicId']."&date=".$_GET['date']."';</script>";

?>