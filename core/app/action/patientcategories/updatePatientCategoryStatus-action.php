<?php
$patientCategory = PatientCategoryData::getById($_POST["category_treatment_id"]);
$patientCategory->treatment_status_id = $_POST["treatment_status_id"];
$note = (isset($_POST["note"]) ? $_POST["note"]: "");
$patientCategory->note = $note;
$isEmbryoTransfer = (isset($_POST["isEmbryoTransfer"]) ? $_POST["isEmbryoTransfer"]: 0);

//Si el tratamiento finalizó con éxito, guardar la fecha de la notificación de prueba de embarazo si el tratamiento lo requiere.
if($_POST["treatment_status_id"] == 4 && $patientCategory->is_pregnancy_test == 1){
    if($isEmbryoTransfer == 1){
        $patientCategory->pregnancy_test_date = date("Y-m-d", strtotime("+1 week"));
        $patientCategory->updateTreatmentPregnancyTestDate();
    }
}
//Si el tratamiento requiere prueba de embarazo y tiene subtratamientos, los subtratamientos finalizarlos ya que no se realizará prueba de embarazo.
if($_POST["treatment_status_id"] == 2){
    $updateSubTreatments = PatientCategoryData::updateSubTreatmentStatusPatient($_POST["category_treatment_id"],4,$note);
}else if($_POST["treatment_status_id"] == 3 || $_POST["treatment_status_id"] == 4){
    $updateSubTreatments = PatientCategoryData::updateSubTreatmentStatusPatient($_POST["category_treatment_id"],$_POST["treatment_status_id"],$note);
}

if($patientCategory->updateCategoryTreatmentStatusPatient()) echo "success";
else http_response_code(500);
?>