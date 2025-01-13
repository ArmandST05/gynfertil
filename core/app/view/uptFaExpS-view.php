<?php



    $buy = new OperationData();
	$idExp = $_GET["idExp"];
	$noFac = $_GET["noFac"];


	 $buy->updateSellFacs($idExp,$noFac);	

     print "<script>window.location='index.php?view=expenses&q=".$idExp."';</script>";



?>