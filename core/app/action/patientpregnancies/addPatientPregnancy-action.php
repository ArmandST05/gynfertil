<?php
    $pregnancy_detail = new PatientPregnancyData();
    $pregnancy_detail->patient_id = $_POST["patient_id"];
    $pregnancy_detail->patient_category_treatment_id = $_POST["category_treatment_id"];
    $pregnancy_detail->pregnancy_type_id = $_POST["pregnancy_type_id"];
    $pregnancy_detail->start_date = date("Y-m-d");
    $new_pregnancy = $pregnancy_detail->add();

    if($new_pregnancy){
        echo json_encode(array($new_pregnancy[1],$pregnancy_detail->getStartDateFormat()));
    }
    else http_response_code(500);
?>