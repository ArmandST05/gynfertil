<?php
if(isset($_GET["product_id"])){
	$cart=$_SESSION["cartSal"];
	if(count($cart)==1){
	 unset($_SESSION["cartSal"]);
	}else{
		$ncart = null;
		$nx=0;
		foreach($cart as $c){
			if($c["product_id"]!=$_GET["product_id"]){
				$ncart[$nx]= $c;
			}
			$nx++;
		}
		$_SESSION["cartSal"] = $ncart;
	}

}else{
 unset($_SESSION["cartSal"]);
}

print "<script>window.location='index.php?view=salidas';</script>";

?>