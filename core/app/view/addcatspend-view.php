<?php
/**
* BookMedik
* @author evilnapsis
**/

if(count($_POST)>0){
	$cat = new CategorySpend();
	$name = $_POST["name"];
	$cat->addCategory($name);

print "<script>window.location='index.php?view=catspend';</script>";


}


?>