<?php

if(!isset($_SESSION["buy"])){

	$type_pay = array("idCon"=>$_POST["idCon"],"cost"=>$_POST["cost"],"q"=>$_POST["q"],"cad"=>$_POST["cad"]);
	$_SESSION["buy"] = array($type_pay);
	$typeBuy = $_SESSION["buy"];

	
}

else{
	$index=0;
	$typeBuy = $_SESSION["buy"];

foreach($typeBuy as $c){
	if($c["idCon"]==$_POST["idCon"]){
		//echo "found";
		$found=true;
		break;
	}
	$index++;

}

if($found==true){
	
	echo '<script> 
	    alert("El producto ya esta en la lista");
		window.location="index.php?view=buy&date="'.$_POST["date"].'";
		</script>
	';
					
}

if($found==false){
    $nc = count($typeBuy);
	$type_buy = array("idCon"=>$_POST["idCon"],"cost"=>$_POST["cost"],"q"=>$_POST["q"],"cad"=>$_POST["cad"]);
	$typeBuy[$nc] = $type_buy;
    //print_r($cart);
	$_SESSION["buy"] = $typeBuy;

   
	 

}


}
 print "<script>window.location='index.php?view=buy&date=".$_POST['date']."';</script>";

?>