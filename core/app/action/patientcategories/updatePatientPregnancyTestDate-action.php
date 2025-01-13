<?php
    //Actualiza la fecha en que se debe de hacer la fecha de embarazo. Se utilizará para las notificaciones.
    $patient_category = PatientCategoryData::getById($_POST["category_treatment_id"]);
    $patient_category->pregnancy_test_date = $_POST["pregnancy_test_date"];

    if($patient_category->updateTreatmentPregnancyTestDate()) echo "success";
    else http_response_code(500);
?>