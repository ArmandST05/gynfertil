<?php
if (count($_POST) > 0) {
    $procedureOvule = PatientOvuleData::getProcedureOvuleById($_POST["procedureOvuleId"]);
    $treatmentId = $procedureOvule->patient_category_treatment_id;
    $patientOvuleId = $procedureOvule->patient_ovule_id;
    $status = substr($_POST["status"], -3, 1);
    $phase = substr($_POST["status"], -1);

    //Actualizar fase del óvulo en general y en el procedimiento
    $patientOvule = PatientOvuleData::getById($patientOvuleId);
    $patientOvule->ovule_status_id = $status;
    $patientOvule->ovule_phase_id = $phase;

    $procedureOvule->end_ovule_status_id = $status;
    $procedureOvule->end_ovule_phase_id = $phase;
    if ($patientOvule->updateStatusPhase() && $procedureOvule->updateEndStatusPhase()) {
        $patientCategoryTreatment = PatientCategoryData::getById($treatmentId);

        $totalDataVitrificationEmbryos = PatientOvuleData::getTotalOvulesByStatusPhaseProcedureId($treatmentId, 2, 2);
        $existingVitrification = EmbryologyProcedureVitrificationData::getByTreatmentId($treatmentId);
        if ($totalDataVitrificationEmbryos->total == 0 && $existingVitrification->code == "") {
            EmbryologyProcedureVitrificationData::deleteByTreatmentId($treatmentId);
            EmbryologyProcedureVitrificationData::deleteDetailsByTreatmentId($treatmentId);
        } else {
            //Crear registro de vitrificación de embriones y detalles si no existe
            if ($phase == 2 && $status == 2) {
                $existingVitrificationDetail = EmbryologyProcedureVitrificationData::validateDetailByTreatmentIdPatientOvule($treatmentId, $patientOvuleId);
                //Si no existe el detalle, lo registramos
                if (!$existingVitrificationDetail) {
                    if ($existingVitrification) {
                        $vitrificationDetail = new EmbryologyProcedureVitrificationData();
                        $vitrificationDetail->patient_embryology_procedure_vitrification_id = $existingVitrification->id;
                        $vitrificationDetail->patient_ovule_id = $patientOvuleId;
                        $vitrificationDetail->addDetail();
                    } else {
                        $vitrification = new EmbryologyProcedureVitrificationData();
                        $vitrification->patient_category_treatment_id = $treatmentId;
                        $vitrification->vitrification_type_id = 2;
                        $newVitrification = $vitrification->add();

                        $vitrificationDetail = new EmbryologyProcedureVitrificationData();
                        $vitrificationDetail->patient_embryology_procedure_vitrification_id = $newVitrification[1];
                        $vitrificationDetail->patient_ovule_id = $patientOvuleId;
                        $vitrificationDetail->addDetail();
                    }
                }
            } else {
                EmbryologyProcedureVitrificationData::deleteDetailByPatientOvuleId($treatmentId, $patientOvuleId);
            }
        }
        echo json_encode($procedureOvule);
    } else http_response_code(500);
} else return http_response_code(500);
