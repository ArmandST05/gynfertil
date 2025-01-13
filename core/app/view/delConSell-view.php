<?php


$buy = new OperationData();
	
$buy->delConB($_GET["idCon"]);

Core::redir("./index.php?view=sales/edit&id=".$_GET["idSell"]."");

?>