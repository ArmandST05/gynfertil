<?php



    $buy = new OperationData();
	$idSell = $_GET["idSell"];
	$noFac = $_GET["noFac"];


	 $buy->updateSellFacs($idSell,$noFac);	

     print "<script>window.location='index.php?view=sales/index&q=".$idSell."';</script>";



?>