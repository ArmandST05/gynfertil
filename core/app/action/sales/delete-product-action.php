<?php
$product = OperationDetailData::getById($_GET["productId"]);	
$product->delete();
Core::redir("./index.php?view=sales/edit&id=".$_GET["saleId"]."");
?>