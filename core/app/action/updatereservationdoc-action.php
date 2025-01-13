<?php
/**
* BookMedik
* @author evilnapsis
* @url http://evilnapsis.com/about/
**/
if(count($_POST)>0){

if($_POST["pacient_id"]==1){
$calendarColor="#BCA9F5";}
else{
$calendarColor="#E6E6E6";
}

  $note = trim(str_replace("&nbsp;","",strip_tags($_POST["note"])));
  $note = preg_replace('/[^a-zA-Z0-9 ]/m', '',$note);

  $user = ReservationData::getById($_POST["id"]);
  $user->pacient_id = $_POST["pacient_id"];
  $user->time_at = $_POST["time_at"];
  $user->time_at_final = $_POST["time_at_final"];
  $user->note = $note;
  $user->user_id = $_POST["user_id"];
  $user->cita = $_POST["cita"]." ".$_POST["time_at"];
  $user->cita2 = $_POST["cita"]." ".$_POST["time_at_final"];

	$user->col = $calendarColor;
  $user->id = $_POST["id"];

  
  $user->update_rdoc();
  
//Core::alert("Actualizado exitosamente!");
print "<script>window.location='index.php?view=home';</script>";


}


?>