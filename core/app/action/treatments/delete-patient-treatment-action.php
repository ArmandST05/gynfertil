<?php
$treatment = TreatmentData::getPatientTreatmentById($_POST["id"]);
if($treatment->deletePatientTreatment()) {
    TreatmentData::deleteDetailsByPatientTreatment($treatment->id);
    return http_response_code(200);
}
else return http_response_code(500);
?>