<?php


$buy = new OperationData();
	
$buy->delConB($_GET["idPro"]);

Core::redir("./index.php?view=Salupd&id=".$_GET["idSell"]."");

?>