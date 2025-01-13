<?php
$originalValue = "";

if(count($_POST) > 0){
    $ovuleDetail = PatientOvuleData::getSectionDetailOvule($_POST["procedureOvuleId"],$_POST["sectionDetailId"]);

    //Actualizar registro existente
    if($ovuleDetail){
        $originalValue = $ovuleDetail->value;
        $ovuleDetail->value = $_POST["value"];
        //Actualizar existente
        if($ovuleDetail->updateSectionDetailOvule()){
            echo json_encode($ovuleDetail);
            addProcedureOvule($originalValue);
        }
        else http_response_code(500);
    }
    else{
        //Crear nuevo registro
        $ovuleDetail = new PatientOvuleData();
        $ovuleDetail->ovule_section_detail_id = $_POST["sectionDetailId"];
        $ovuleDetail->procedure_ovule_id = $_POST["procedureOvuleId"];
        $ovuleDetail->value = $_POST["value"];

        if($ovuleDetail->addSectionDetailOvule()){
            $ovuleDetail = PatientOvuleData::getSectionDetailOvule($_POST["procedureOvuleId"],$_POST["sectionDetailId"]);
            addProcedureOvule($originalValue);
            echo json_encode($ovuleDetail);
        }
        else return http_response_code(500);
    }

}
else{
    return http_response_code(500);
}

function addProcedureOvule($originalValue){
    //En DONOVO (detail-152) se selecciona la receptora del óvulo, por lo tanto se agrega ese óvulo al procedimiento REC/MITXO seleccionado (value).
    if($_POST["sectionDetailId"] == 152){
        //Obtener datos del óvulo del procedimiento
        $procedureOvule = PatientOvuleData::getProcedureOvuleById($_POST["procedureOvuleId"]);

        //Si se asignó el óvulo a una nueva receptora (REC), crear el nuevo registro.
        if($originalValue != $_POST["value"] && $_POST["value"] != ""){
            //Obtener el código REC seleccionado
            $destinationTreatmentData = explode("-",$_POST["value"]);
            $destinationTreatmentCode = trim($destinationTreatmentData[0]);
            //Obtener el id del tratamiento REC y guardar óvulo en ese tratamiento/procedimiento
            $destinationTreatment = PatientCategoryData::getByCode($destinationTreatmentCode);

            //En el caso de tratamiento MIXTO (8),hay 2 tablas de óvulos(secciones),por lo tanto, se debe seleccionar la segunda (REC), la primera es un FIVTE.
            $sectionId = (($destinationTreatment->patient_treatment_id == 8) ? 2: 1);
        
            $ovuleProcedure = new PatientOvuleData();
            $ovuleProcedure->ovule_status_id = 1;//Es un óvulo recién registrado
            $ovuleProcedure->patient_category_treatment_id = $destinationTreatment->id;
            $ovuleProcedure->patient_ovule_id = $procedureOvule->patient_ovule_id;
            $ovuleProcedure->section_id = $sectionId;
            $ovuleProcedure->addByProcedure();
        }
        if($originalValue != ""){
            //Si el óvulo tenía anteriomente otra receptora (REC), eliminar el óvulo de ese tratamiento.
            $originalTreatmentData = explode("-",$originalValue);
            $originalTreatmentCode = trim($originalTreatmentData[0]);
            //Obtener el id del tratamiento original REC
            $originalTreatmentId = PatientCategoryData::getByCode($originalTreatmentCode);
            
            //Eliminar
            $procedureOvule = PatientOvuleData::getProcedureOvuleByPatientOvuleId($originalTreatmentId->id,$procedureOvule->patient_ovule_id);

            if($procedureOvule && $procedureOvule->deleteByOvuleProcedure()){
                //Eliminar detalles de vitrificación.
                $treatmentId = $procedureOvule->patient_category_treatment_id;
                $patientOvuleId = $procedureOvule->patient_ovule_id;
                EmbryologyProcedureVitrificationData::deleteDetailByPatientOvuleId($treatmentId,$patientOvuleId);

                //Si ya no hay detalles de vitrificaciones eliminar también el registro de vitrificación del tratamiento
                $totalDataVitrificationEmbryos = PatientOvuleData::getTotalOvulesByStatusPhaseProcedureId($treatmentId,2,2);
                $vitrificationData = EmbryologyProcedureVitrificationData::getByTreatmentId($treatmentId);
                if ($totalDataVitrificationEmbryos && $vitrificationData && $totalDataVitrificationEmbryos->total == 0 && $vitrificationData->code == "") {
                    EmbryologyProcedureVitrificationData::deleteByTreatmentId($treatmentId);
                }
            }
        }
    }
}
?>
