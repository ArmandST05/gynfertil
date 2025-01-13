<?php
/**
* BookMedik
* @author evilnapsis
**/

if(count($_POST)>0){
	$user = new CategoryMedicData();
	$user->name = $_POST["name"];
	$user->add();

print "<script>window.location='index.php?view=catmedic';</script>";


}


?>