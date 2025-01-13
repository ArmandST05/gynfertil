<?php
if(count($_POST)>0){
    $diagnostics = TreatmentDiagnosticData::getByTreatment($_POST["categoryTreatmentId"]);
    echo json_encode($diagnostics);
}
?>