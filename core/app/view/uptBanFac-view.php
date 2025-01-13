<?php



    $buy = new OperationData();
	$idSell = $_GET["idSell"];
	$ban = $_GET["ban"];

	 $buy->updateBanFac($idSell,$ban);	

     print "<script>window.location='index.php?view=sales/index&q=".$idSell."';</script>";



?>