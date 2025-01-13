<?php
if(isset($_GET["product_id"])){
	$cart = $_SESSION["cart"];
	
		$ncart = null;
		$nx = 0;
		foreach($cart as $c){
			if($c["product_id"] != $_GET["product_id"]){
				$ncart[$nx]= $c;
			}
			$nx++;
		}
		$_SESSION["cart"] = $ncart;
	}


print "<script>window.location='index.php?view=sales/new-details&idRes=".$_GET['idRes']."&id_paciente=".$_GET['id_paciente']."&idMed=".$_GET['idMed']."&fecha=".$_GET['fecha']."';</script>";

?>