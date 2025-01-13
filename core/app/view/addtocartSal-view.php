<?php

 $products = ProductData::getById($_POST["product_id"]);
 $type=$products->type;



if(!isset($_SESSION["cartSal"])){


	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"],"type"=>$type);
	$_SESSION["cartSal"] = array($product);
     
    
	$cart = $_SESSION["cartSal"];
	  
 

///////////////////////////////////////////////////////////////////
		$num_succ = 0;
		$process=false;
		$errors = array();
		foreach($cart as $c){

		
			$q = OperationData::getStockByProduct($c["product_id"]);
		  
//			echo ">>".$q;
			if($c["q"]<=$q){
				$num_succ++;


			}else{
				$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
				$errors[count($errors)] = $error;
			}

		}
///////////////////////////////////////////////////////////////////

if($num_succ==count($cart)){
	$process = true;
}
if($process==false){
	unset($_SESSION["cartSal"]);
$_SESSION["errorsSal"] = $errors;
	
echo '<script>
	window.location="index.php?view=salidas";
</script>';

}




}else {

$found = false;
$cart = $_SESSION["cartSal"];
$index=0;


$q = OperationData::getStockByProduct($_POST["product_id"]);


$can = true;
if($_POST["q"]<=$q){
}else{
	$error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
	$errors[count($errors)] = $error;
	$can=false;
}

if($can==false){
$_SESSION["errorsSal"] = $errors;
echo '<script>
	window.location="index.php?view=salidas";
</script>';
}
?>

<?php
if($can==true){
foreach($cart as $c){
	if($c["product_id"]==$_POST["product_id"]){
		//echo "found";
		$found=true;
		break;
	}
	$index++;

}

if($found==true){
	
	echo '<script> 
			alert("El producto ya esta en la lista");
			window.location="index.php?view=salidas";
		</script>
	';
				
}

if($found==false){
    $nc = count($cart);
	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"],"type"=>$type);
	$cart[$nc] = $product;
    //print_r($cart);
	$_SESSION["cartSal"] = $cart;

    //echo "entre2";
	 

}


}
}
 print "<script>window.location='index.php?view=salidas';</script>";

?>