<?php
/**
* BookMedik
* @author evilnapsis
* @url http://evilnapsis.com/about/
**/
if(count($_GET)>0){
  $patient = PatientData::getById($_GET["id"]);
  $patient->birthday = $patient->getBirthdayFormat();
  $patient->age = $patient->getAge();

  echo json_encode($patient);
}
?>