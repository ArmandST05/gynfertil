<?php
$patientTreatment = TreatmentData::getPatientTreatmentById($_POST["patientTreatmentId"]);
$patientTreatment->status_id = $_POST["statusId"];
$patientTreatment->cancellation_reason = (isset($_POST["cancellationReason"]) ? $_POST["cancellationReason"]: "");
$patientTreatment->last_note = (isset($_POST["lastNote"]) ? $_POST["lastNote"]: "");
$patientTreatment->psychiatrist =  (isset($_POST["psychiatrist"]) ? $_POST["psychiatrist"]: "");

if($patientTreatment->updatePatientTreatmentStatus()){
    $patient = PatientData::getById($patientTreatment->patient_id);
    if($_POST["statusId"] == 2){//Baja
        //Cancelado
        $patient->category_id = 3;//Inactivo
    }else{
        $patient->category_id = 2;//Alta
    }
    $patient->updatePatientCategory();
    echo "success";
}
else http_response_code(500);
?>