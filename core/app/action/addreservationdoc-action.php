<?php
/**
* BookMedik
* @author evilnapsis
**/

$r = new ReservationData();

$note = trim(str_replace("&nbsp;","",strip_tags($_POST["note"])));
$note = preg_replace('/[^a-zA-Z0-9 ]/m', '',$note);

$r->pacient_id = $_POST["pacient_id"];
$r->cita = $_POST["cita"]." ".$_POST["time_at"];
$r->cita2 = $_POST["cita"]." ".$_POST["time_at_final"];
$r->time_at = $_POST["time_at"];
$r->time_at_final = $_POST["time_at_final"];
$r->user_id =  $_POST["user_id"];
$r->note = $note;

if($_POST["pacient_id"]==1){
    $calendarColor="#BCA9F5";}
else{
    $calendarColor="#E6E6E6";
}
$r->col = $calendarColor;

$r->add_doc();
//Core::alert("Agregado exitosamente!");
Core::redir("./index.php?view=home&fecha=".$_POST["cita"]."");
?>