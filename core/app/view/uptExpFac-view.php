<?php



    $buy = new OperationData();
	$idExp = $_GET["idExp"];
	$valor = $_GET["valor"];

	 $buy->updateExpFac($idExp,$valor);	

     print "<script>window.location='index.php?view=expenses&q=".$idExp."';</script>";



?>