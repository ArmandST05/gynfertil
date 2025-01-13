<?php
 unset($_SESSION["cart"]);
 unset($_SESSION["payments"]);

print "<script>window.location='index.php?view=sales/new-details&idRes=".$_GET['idRes']."&id_paciente=".$_GET['id_paciente']."&idMed=".$_GET['idMed']."&fecha=".$_GET['fecha']."';</script>";

?>