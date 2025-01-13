<?php



    $buy = new OperationData();
	$idExp = $_GET["idSell"];
	$valor = $_GET["valor"];

	 $buy->updateExpFac($idExp,$valor);	

     print "<script>window.location='index.php?view=sales/index&q=".$idExp."';</script>";



?>