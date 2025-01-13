<?php
if(isset($_GET["idCon"])){

	$buy=$_SESSION["buy"];

		$nbuy= null;
		$nx=0;
		foreach($buy as $c){
			if($c["idCon"]!=$_GET["idCon"]){
				$nbuy[$nx]= $c;
			}
			$nx++;
		}
		$_SESSION["buy"] = $nbuy;
	}


print "<script>window.location='index.php?view=buy';</script>";

?>