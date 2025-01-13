<?php
$patient_pregnancy = PatientPregnancyData::getById($_POST["patient_pregnancy_id"]);
$patient_pregnancy->end_date = date("Y-m-d");
if($patient_pregnancy->finishPregnancy()) echo "success";
else http_response_code(500);
?>