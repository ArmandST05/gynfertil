<?php
if (count($_POST) > 0) {
    foreach ($_POST["details"] as $index => $detail) {
        //Actualizar nombre del médico en registro de procedimiento de andrología
        if($index == "medicName"){
            $andrologyProcedure = AndrologyProcedureData::getPatientProcedureById($_POST["patientAndrologyProcedureId"]);
            $andrologyProcedure->medic_name = $detail;
            $andrologyProcedure->updatePatientProcedureMedic();
        }else{
            $procedureDetail = AndrologyProcedureData::getDetail($_POST["patientAndrologyProcedureId"], $index);

            //Actualizar registro existente
            if ($procedureDetail) {
                $procedureDetail->value = $detail;
                //Actualizar existente
                $procedureDetail->updateDetail();
            } else {
                //Crear nuevo registro
                $procedureDetail = new AndrologyProcedureData();
                $procedureDetail->patient_andrology_procedure_id = $_POST["patientAndrologyProcedureId"];
                $procedureDetail->procedure_section_detail_id = $index;
                $procedureDetail->value = $detail;
                $procedureDetail->addDetail();
            }
        }
    }
}
echo '<script>window.location="index.php?view=andrology-procedures/details&procedureId=' . $_POST["patientAndrologyProcedureId"] . '"</script>';
?>