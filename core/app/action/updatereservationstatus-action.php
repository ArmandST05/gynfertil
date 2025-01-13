<?php
/**
* BookMedik
* @author evilnapsis
* @url http://evilnapsis.com/about/
**/
   if(count($_POST)>0){
      $reservation = ReservationData::getById($_POST["reservation_id"]);
      $reservation->status_reservation_id = $_POST["status_id"];
      if($reservation->update_reservation_status()){
         return $reservation;
      }
      else{
         return http_response_code(500);
      }
   }
?>