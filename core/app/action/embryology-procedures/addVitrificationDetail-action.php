<?php
if(count($_POST) > 0){
    $vitrificationDetail = EmbryologyProcedureVitrificationData::getByCode($_POST["code"]);

    if(!isset($vitrificationDetail)){
        $vitrificationDetail = new EmbryologyProcedureVitrificationData();
            
        $vitrificationDetail->patient_category_treatment_id = $_POST["patientCategoryTreatmentId"];
        $vitrificationDetail->vitrification_type_id = $_POST["typeId"];
        $vitrificationDetail->code = $_POST["code"];
        $vitrificationDetail->date = $_POST["date"];
        $vitrificationDetail->rod = $_POST["rod"];
        $vitrificationDetail->rod_color = $_POST["rodColor"];
        $vitrificationDetail->device_number = $_POST["deviceNumber"];
        $vitrificationDetail->device_color = $_POST["deviceColor"];
        $vitrificationDetail->basket = $_POST["basket"];
        $vitrificationDetail->tank = $_POST["tank"];
        $vitrificationDetail->add();
    }
    else http_response_code(500);
}
else return http_response_code(500);
?>