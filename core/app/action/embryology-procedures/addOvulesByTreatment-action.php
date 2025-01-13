<?php
if (count($_POST) > 0) {
    $addedOvules = [];
    $treatmentId = $_POST["treatmentId"];
    $originTreatmentName = $_POST["originTreatmentName"];
    $sectionId = ((isset($_POST["sectionId"])) ? $_POST["sectionId"]: 1);

    foreach($_POST["ovules"] as $ovule){
        $existingProcedureOvule = PatientOvuleData::validateOvuleByProcedure($treatmentId, $ovule);
        $patientOvule = PatientOvuleData::getById($ovule);

        //Si no se ha agregado al procedimiento, agregar.
        if (!$existingProcedureOvule) {
            $ovuleProcedure = new PatientOvuleData();
            $ovuleProcedure->ovule_status_id = $patientOvule->ovule_status_id;
            $ovuleProcedure->patient_category_treatment_id = $treatmentId;
            $ovuleProcedure->patient_ovule_id = $ovule;
            $ovuleProcedure->section_id = $sectionId;
            $newOvuleProcedure = $ovuleProcedure->addByProcedure();

            if($newOvuleProcedure && $newOvuleProcedure[1]){
                $addedOvules[] = $ovuleProcedure;
            }
        }
    }
    echo json_encode($addedOvules);

    }else return http_response_code(500);
?>