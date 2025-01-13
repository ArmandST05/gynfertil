<?php
/**
* BookMedik
* @author evilnapsis
**/
$na = $_POST['na'];

$pro = ProductData::getAllReN($na);

$name="";
foreach ($pro as $key) {
	$name=$key->name;
}

	if(!empty($pro)){
              echo "<span style='font-weight:bold;color:red;
              color: #fff;
              background-color: #d9534f;
              border-color: #d43f3a;'>El nombre ".$name." ya existe </span>";
            }else{
                 
              echo "";
            }


?>