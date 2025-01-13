<?php



    $buy = new OperationData();
	$idExp = $_GET["idExp"];
	$ban = $_GET["ban"];

	 $buy->updateBanFac($idExp,$ban);	

     print "<script>window.location='index.php?view=expenses&q=".$idExp."';</script>";



?>