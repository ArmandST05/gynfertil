<?php


$buy = new OperationData();
	
$buy->delPayB($_GET["idP"]);

Core::redir("./index.php?view=buyUpd&id=".$_GET["idBuy"]."");

?>