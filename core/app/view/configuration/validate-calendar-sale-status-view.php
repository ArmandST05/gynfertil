<?php 
$start = isset($_GET["start"])  ? $_GET["start"] :  0;
$end = isset($_GET["end"])  ? $_GET["end"] :  1000;
echo("Inicio ".$start." Fin".$end."<br>");
$reservations = ReservationData::getByLimits($start,$end);
foreach($reservations as $reservation){
    ReservationData::updateLastSaleStatus($reservation->id);
    echo("CITA ".$reservation->id."<br>");
}
echo("END");
?>