<?php
if(count($_POST) > 0){
    $procedure = AndrologyProcedureData::getPatientProcedureById($_POST["patientAndrologyProcedureId"]);
    $procedure->observations = $_POST["observations"];
    $procedure->updatePatientProcedureObservations();
    Core::redir("./index.php?view=andrology-procedures/details&procedureId=".$_POST["patientAndrologyProcedureId"]."");
}
else{
    return http_response_code(500);
}
