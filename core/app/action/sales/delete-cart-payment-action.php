<?php
if(isset($_GET["idTypePay"])){

	$pay=$_SESSION["payments"];

		$npay= null;
		$nx=0;
		foreach($pay as $c){
			if($c["idType"]!=$_GET["idTypePay"]){
				$npay[$nx]= $c;
			}
			$nx++;
		}
		$_SESSION["payments"] = $npay;
	}

print "<script>window.location='index.php?view=sales/new-details&idRes=".$_GET['idRes']."&id_paciente=".$_GET['id_paciente']."&idMed=".$_GET['idMed']."&fecha=".$_GET['fecha']."';</script>";

?>