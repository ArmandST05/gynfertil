<?php
/**
* BookMedik
* @author evilnapsis
**/
$na = $_POST['na'];

$cat = CategorySpend::getAllR($na);

$c="";
foreach ($cat as $key) {
	$c=$key->name;
}

	if(!empty($c)){
              echo "<span style='font-weight:bold;color:red;
              color: #fff;
              background-color: #d9534f;
              border-color: #d43f3a;'>El Código de barras ".$na." ya existe </span>";
            }else{
                 
              echo "";
            }


?>