<?php
/**
* BookMedik
* @author evilnapsis
**/
$cod = $_POST['cod'];

$pro = ProductData::getAllRe($cod);

$barcode="";
foreach ($pro as $key) {
	$barcode=$key->barcode;
}

	if(!empty($pro)){
              echo "<span style='font-weight:bold;color:red;
              color: #fff;
              background-color: #d9534f;
              border-color: #d43f3a;'>El Código de barras ".$cod." ya existe </span>";
            }else{
                 
              echo "";
            }


?>