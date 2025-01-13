<?php
//Se asigna al óvulo/embrión la muestra de semen con la que se inseminó(id del procedimiento de andrología)
if (count($_POST) > 0) {
    $procedureOvule = PatientOvuleData::getProcedureOvuleById($_POST["procedureOvuleId"]);
    $procedureOvule->patient_andrology_procedure_id = $_POST["andrologyProcedureId"];

    if ($procedureOvule->updateProcedureOvuleSemen()) {
        echo json_encode($procedureOvule);
    } else return http_response_code(500);
} else return http_response_code(500);
