<?php
/**
* BookMedik
* @author evilnapsis
* @url http://evilnapsis.com/about/
**/
if(count($_POST)>0){
   $reservationArea = ReservationData::getReservationAreaById($_POST["color"]);//Area de la reservación, obtener color
   $reservationKey = PatientData::getPatientStatusById($_POST["pac_est"]);//Estatus de paciente de la reservación(Clave), obtener color
   $medic = MedicData::getById($_POST["medic_id"]);

   if($reservationKey && $reservationKey->calendar_color){//Mostrar en un color diferente las citas con Clave
      $calendarColor = $reservationKey->calendar_color;
   }elseif($medic->calendar_color != ""){
      $calendarColor = $medic->calendar_color;
   }else if($reservationArea && $reservationArea->calendar_color){
      $calendarColor = $reservationArea->calendar_color;
   }else{
      $calendarColor = "#51b749";
   }

   $fecha =$_POST["cita"]." ".$_POST["time_at"];
   $nuevafecha =$_POST["cita"]." ".$_POST["time_at_final"];

   $reservation = ReservationData::getById($_POST["id"]);

   if($_POST["color_letra"]==1){
   $negritas=1;
   }else{
   $negritas=0;
   }

   $note = trim(str_replace("&nbsp;","",strip_tags($_POST["note"])));
   $note = preg_replace('/[^a-zA-Z0-9 ]/m', '',$note);

	$reservation->pacient_id = $_POST["pacient_id"];
	$reservation->medic_id = $_POST["medic_id"];
	$reservation->date_at = $_POST["cita"]." ".$_POST["time_at"];
	$reservation->time_at = $_POST["time_at"];
   $reservation->note = $note;
	$reservation->pac_est = $_POST["pac_est"];
   $reservation->lab = $_POST["lab"];
   $reservation->colorr =  $_POST["color"];
   $reservation->col = $calendarColor;
   $reservation->nuevafecha = $nuevafecha;
   $reservation->negritas = $negritas;
	$reservation->update();
   $reservation->update_usuario();

   //Core::alert("Actualizado exitosamente!");
   print "<script>window.location='index.php?view=home';</script>";

}
?>