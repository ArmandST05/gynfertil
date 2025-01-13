<?php


$buy = new OperationData();
	
$buy->delConB($_GET["idCon"]);

Core::redir("./index.php?view=buyUpd&id=".$_GET["idBuy"]."&date=".$_GET["date"]."");

?>