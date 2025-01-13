<?php
$patientCategory = PatientCategoryData::getById($_POST["category_treatment_id"]);
$patientCategory->pregnancy_test_result = $_POST["pregnancy_test_result"];
$patientCategory->pregnancy_test_date = date("Y-m-d");

$updateSubTreatments = PatientCategoryData::updateSubTreatmentStatusPatient($_POST["category_treatment_id"],4, null);//Finalizar vitrificación

if($patientCategory->updateCategoryTreatmentResultPatient()) {
    if($_POST["pregnancy_test_result"] == 1){
        $pregnancyDetail = new PatientPregnancyData();
        $pregnancyDetail->patient_id = $patientCategory->patient_id;
        $pregnancyDetail->patient_category_treatment_id = $_POST["category_treatment_id"];
        $pregnancyDetail->pregnancy_type_id = 1;
        $pregnancyDetail->start_date = date("Y-m-d");
        $newPregnancy = $pregnancyDetail->add();
        
        echo json_encode(array($newPregnancy[1],$pregnancyDetail->getStartDateFormat()));
    }
    else{
        echo "success";
    }
}
else http_response_code(500);

?>