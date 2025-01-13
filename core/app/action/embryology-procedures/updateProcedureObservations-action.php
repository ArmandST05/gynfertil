<?php
if(count($_POST) > 0){
    $treatment = PatientCategoryData::getById($_POST["patientCategoryTreatmentId"]);
    $treatment->embryology_procedure_observations = $_POST["observations"];
    $treatment->updateEmbryologyProcedureObservations();
    Core::redir("./index.php?view=embryology-procedures/details&treatmentId=".$_POST["patientCategoryTreatmentId"]."");
}
else{
    return http_response_code(500);
}
