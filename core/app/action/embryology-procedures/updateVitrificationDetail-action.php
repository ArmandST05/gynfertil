<?php
if(count($_POST) > 0){
    $vitrificationDetail = EmbryologyProcedureVitrificationData::getByTreatmentId($_POST["patientCategoryTreatmentId"]);

    //Actualizar registro existente
    if(isset($vitrificationDetail)){
        //Actualizar existente
        $vitrificationDetail->date = $_POST["date"];
        $vitrificationDetail->code = $_POST["code"];
        $vitrificationDetail->rod = $_POST["rod"];
        $vitrificationDetail->rod_color = $_POST["rodColor"];
        $vitrificationDetail->device_number = $_POST["deviceNumber"];
        $vitrificationDetail->device_color = $_POST["deviceColor"];
        $vitrificationDetail->basket = $_POST["basket"];
        $vitrificationDetail->tank = $_POST["tank"];

        if($vitrificationDetail->update()) Core::redir("./index.php?view=embryology-procedures/details&treatmentId=". $_POST["patientCategoryTreatmentId"]."");
        else http_response_code(500);
    }
    else{
        //Crear nuevo registro
        $vitrificationDetail = new EmbryologyProcedureVitrificationData();
        $vitrificationDetail->patient_category_treatment_id = $_POST["patientCategoryTreatmentId"];
        $vitrificationDetail->vitrification_type_id = $_POST["vitrificationTypeId"];
        $vitrificationDetail->date = $_POST["date"];
        $vitrificationDetail->code = $_POST["code"];
        $vitrificationDetail->rod = $_POST["rod"];
        $vitrificationDetail->rod_color = $_POST["rodColor"];
        $vitrificationDetail->device_number = $_POST["deviceNumber"];
        $vitrificationDetail->device_color = $_POST["deviceColor"];
        $vitrificationDetail->basket = $_POST["basket"];
        $vitrificationDetail->tank = $_POST["tank"];
        if($vitrificationDetail->addWithAllData()) Core::redir("./index.php?view=embryology-procedures/details&treatmentId=". $_POST["patientCategoryTreatmentId"]."");
        else http_response_code(500);
    }
}
else{
    return http_response_code(500);
}
?>