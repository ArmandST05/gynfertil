<?php
if (count($_POST) > 0) {
        $existingTreatmentProcedure = AndrologyProcedureData::getSemenProceduresByTreatmentProcedureId($_POST["treatmentId"], $_POST["andrologyProcedureId"]);

        //Si no se ha realizado el procedimiento de andrología al tratamiento, agregar.
        if (!$existingTreatmentProcedure) {
            $semenProcedureTreatment = new AndrologyProcedureData();
            $semenProcedureTreatment->andrology_procedure_id = $_POST["andrologyProcedureId"];
            $semenProcedureTreatment->embryology_treatment_id = $_POST["treatmentId"];
            $semenProcedureTreatment->destination_andrology_procedure_id = $_POST["destinationAndrologyProcedureId"];
            $semenProcedureTreatment->quantity = floatval($_POST["quantity"]);
            $newSemenProcedureTreatment = $semenProcedureTreatment->addDeviceByEmbryologyTreatment();

            if($newSemenProcedureTreatment && $newSemenProcedureTreatment[1]){
                echo $newSemenProcedureTreatment[1];
            }
            else return http_response_code(500);
        }else return http_response_code(500);

    }else return http_response_code(500);
?>