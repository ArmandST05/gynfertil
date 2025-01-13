<?php
$procedureOvule = PatientOvuleData::getProcedureOvuleById($_POST["id"]);
$delete = $procedureOvule->deleteByOvuleProcedure();

if($delete){
    //Eliminar detalles de vitrificación.
    $treatmentId = $procedureOvule->patient_category_treatment_id;
    $patientOvuleId = $procedureOvule->patient_ovule_id;
    EmbryologyProcedureVitrificationData::deleteDetailByPatientOvuleId($treatmentId,$patientOvuleId);

    //Si ya no hay detalles de vitrificaciones eliminar también el registro de vitrificación del tratamiento si el código consecutivo no se ha colocado
    $vitrificationData = EmbryologyProcedureVitrificationData::getByTreatmentId($treatmentId);
    if ($totalDataVitrificationEmbryos->total == 0 && $vitrificationData->code == "") {
        EmbryologyProcedureVitrificationData::deleteByTreatmentId($treatmentId);
    }
    return http_response_code(200);
}
else http_response_code(500);
?>