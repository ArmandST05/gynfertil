<?php
/**
* BookMedik
* @author evilnapsis
**/

if(count($_POST)>0){
	$cat = new CategorySpend();
	$name = $_POST["name"];
	$cate = $_POST["cate"];
	$cat->addConcepts($name,$cate);

print "<script>window.location='index.php?view=conceptspend';</script>";


}


?>