<?php
if(count($_POST) > 0){
    $procedure = AndrologyProcedureData::getPatientProcedureById($_POST["patientAndrologyProcedureId"]);
    $procedure->diagnostic = $_POST["diagnostic"];
    $procedure->updatePatientProcedureDiagnostic();
    Core::redir("./index.php?view=andrology-procedures/details&procedureId=".$_POST["patientAndrologyProcedureId"]."");
}
else{
    return http_response_code(500);
}
