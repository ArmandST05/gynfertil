<?php
/**
* BookMedik
* @author evilnapsis
**/
$rx = ReservationData::getRepeated($_POST["pacient_id"],$_POST["medic_id"],$_POST["cita"],$_POST["time_at"],$_POST["laboratorio"]);
$rc = ReservationData::getRepeated_lab($_POST["cita"],$_POST["time_at"],$_POST["laboratorio"]);
$valida_pa = ReservationData::get_validacion_listanegra($_POST["pacient_id"]);

$reservationArea = ReservationData::getReservationAreaById($_POST["color"]);//Area de la reservación, obtener color
$reservationKey = PatientData::getPatientStatusById($_POST["pac_est"]);//Estatus de paciente de la reservación(Clave), obtener color
$medic = MedicData::getById($_POST["medic_id"]);

if($reservationKey && $reservationKey->calendar_color){//Mostrar en un color diferente las citas con Clave
   $calendarColor = $reservationKey->calendar_color;
}else if($medic->calendar_color != ""){
  $calendarColor = $medic->calendar_color;
}else if($reservationArea && $reservationArea->calendar_color){
   $calendarColor = $reservationArea->calendar_color;
}else{
   $calendarColor = "#51b749";
}

if($valida_pa!=null){
   Core::alert("El paciente esta en lista negra");
}

else if($rc!=null){
Core::alert("El laboratorio ya tiene un medico asignado");

}

else if($rx==null){
$r = new ReservationData();

if($_POST["color_letra"]==1){
 $negritas=1;
}else{
 $negritas=0;
}

$fecha =$_POST["cita"]." ".$_POST["time_at"];
$nuevafecha =$_POST["cita"]." ".$_POST["time_at_final"];

$note = trim(str_replace("&nbsp;","",strip_tags($_POST["note"])));
$note = preg_replace('/[^a-zA-Z0-9 ]/m', '',$note);

$r->pacient_id = $_POST["pacient_id"];
$r->medic_id = $_POST["medic_id"];
$r->cita = $_POST["cita"]." ".$_POST["time_at"];
$r->time_at = $_POST["time_at"];
$r->user_id =  $_POST["user_id"];
$r->note = $note;
$r->pac_est = $_POST["pac_est"];
$r->asistente = $_POST["asistente"];
$r->laboratorio = $_POST["laboratorio"];
$r->colorr =  $_POST["color"];
$r->col = $calendarColor;
$r->negritas = $negritas;
$r->nuevafecha = $nuevafecha;
$r->add();
$r->update_usuario();
}else{
Core::alert("Cita Repetida");
}
Core::redir("./index.php?view=home&fecha=". $_POST["cita"]."");
?>