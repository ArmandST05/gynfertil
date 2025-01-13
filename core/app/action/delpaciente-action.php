<?php
/**
* BookMedik
* @author evilnapsis
**/
$user = new PatientData();
$user->delById($_GET["id"]);

Core::alert("¡Eliminado exitosamente!");
print "<script>window.location='index.php?view=patients/index';</script>";

?>