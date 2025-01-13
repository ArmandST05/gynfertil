<?php
$det = CategorySpend::getByIdCatBuyId($_POST["idSell"]);
$products = ProductData::getById($_POST["product_id"]);
 $type=$products->type;


///////////////////////////////////////////////////////////////////
		$num_succ = 0;
		$process=false;
		$errors = array();

			if($type=="MEDICAMENTO"){
			$q = OperationData::getStockByProduct($_POST["product_id"]);
		    }else{
		    $q=100;
		    }
//			echo ">>".$q;
			if($_POST["q"]<=$q){
				$num_succ++;
                $process = true;

			}else{
				$error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
				$errors[count($errors)] = $error;
			}

		
///////////////////////////////////////////////////////////////////

if($process==false){
	
$_SESSION["errors1"] = $errors;
	
echo '<script>
	window.location="index.php?view=sales/edit&id='.$_POST["idSell"].'";
</script>';



}

$found = false;
$index=0;


if($type=="MEDICAMENTO"){
$q = OperationData::getStockByProduct($_POST["product_id"]);
}else{
$q=100;
}




$can = true;
if($_POST["q"]<=$q){
}else{
	//$error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
	//$errors[count($errors)] = $error;
	$can=false;
}

if($can==false){
$_SESSION["errors1"] = $errors;
echo '<script>
	window.location="index.php?view=sales/edit&id='.$_POST["idSell"].'";
</script>';
}
?>

<?php
if($can==true){
foreach($det as $c){
	if($c->product_id==$_POST["product_id"]){
		//echo "found";
		$found=true;
		break;
	}
	$index++;

}

if($found==true){
	
	echo '<script> 
			alert("El producto ya esta en la lista");
			window.location="index.php?view=sales/edit&id='.$_POST["idSell"].'";
		</script>
	';
				
}

if($found==false){
 $op = new OperationData();
 $op->product_id = $_POST["product_id"];
 $op->operation_type_id=2; // 1 - entrada
 $op->sell_id= $_POST["idSell"];
 $op->q= $_POST["q"];
 $op->price= $_POST["price"];
 $op->is_oficial = 1;
 $add = $op->add();

}


}

 print "<script>window.location='index.php?view=sales/edit&id=".$_POST["idSell"]."';</script>";

?>