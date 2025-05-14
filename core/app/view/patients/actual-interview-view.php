<?php 
$patientId = $_GET["id"];
$patientTreatment = TreatmentData::getLastPatientTreatment($patientId);

if($patientTreatment){
    Core::redir("./index.php?view=patients/report-interview-".$patientTreatment->getTreatment()->code."&id=".$patientTreatment->id);
}else{
    echo "No hay una entrevista disponible para mostrar";
}
?>