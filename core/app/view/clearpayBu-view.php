<?php
if(isset($_GET["idTypePay"])){

	$pay=$_SESSION["typePBuy"];

		$npay= null;
		$nx=0;
		foreach($pay as $c){
			if($c["idType"]!=$_GET["idTypePay"]){
				$npay[$nx]= $c;
			}
			$nx++;
		}
		$_SESSION["typePBuy"] = $npay;
	}


print "<script>window.location='index.php?view=buy';</script>";

?>