<?php
/**
* BookMedik
* @author evilnapsis
* @url http://evilnapsis.com/about/
**/
   if(count($_POST)>0){
      $reservation = ReservationData::getById($_POST["reservation_id"]);
      $reservation->papanicolaou_test = $_POST["papanicolaou_test"];
      if($reservation->update_reservation_papanicolaou_test()){
         return $reservation;
      }
      else{
         return http_response_code(500);
      }
   }
?>