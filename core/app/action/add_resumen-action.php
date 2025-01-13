<?php
	date_default_timezone_set("America/Mexico_City");
	$resume = new ReservationData();
	$resume->id_medico = $_POST["id_medico"];
    $resume->id_paciente = $_POST["id_paciente"]; 
	$resume->textarea = $_POST["note"];
	$resume->fecha = date("Y-m-d");
	$id  =$_POST["id_reser"];
	$fecha = $_POST["fecha"];
    $res = $_POST["note"];

	$newResume = $resume->add_resumen($res);

if($newResume && $newResume[1]){
	print "<script>
	window.location='index.php?view=reservations/details&id=".$id."&id_paciente=".$_POST["id_paciente"]."&fecha=".$fecha."';
	alert('Se guardó exitosamente la nota del paciente.');
	</script>";
}else{
	print "<script>
	window.location='index.php?view=reservations/details&id=".$id."&id_paciente=".$_POST["id_paciente"]."&fecha=".$fecha."';
	alert('Ocurrió un error al guardar la nota del paciente.');
	</script>";
}
?>