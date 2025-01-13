<?php
//Eliminar el semen utilizado en un procedimiento, ya que se equivocaron al agregarlo
if (count($_POST) > 0) {
        $deviceAndrologyTreatment = AndrologyProcedureData::getDeviceByEmbryologyTreatmentId($_POST["id"]);
        
        //Eliminar la referencia de todos los óvulos/embriones de ese procedimiento que especificaron esa muestra de semen(procedimiento andrología)
        PatientOvuleData::deleteOvuleProcedureSemenByTreatment($deviceAndrologyTreatment->embryology_treatment_id,$deviceAndrologyTreatment->andrology_procedure_id);

        if ($deviceAndrologyTreatment->deleteDeviceByEmbryologyTreatment()){
            return http_response_code(200);
        }
        else return http_response_code(500);
    }else return http_response_code(500);
?>