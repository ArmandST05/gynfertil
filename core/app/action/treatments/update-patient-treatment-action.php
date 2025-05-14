<?php
$userId = $_SESSION['user_id'];
$patientTreatment = TreatmentData::getPatientTreatmentById($_POST["patientTreatmentId"]);

$patientTreatment->treatment_id = $_POST["treatmentId"];
$patientTreatment->medic_id = $_POST["medicId"];
$patientTreatment->default_price = $_POST["defaultPrice"];
$patientTreatment->start_date = $_POST["startDate"];
$patientTreatment->reason = $_POST["reason"];
$patientTreatment->cancellation_reason = $_POST["cancellationReason"];
$patientTreatment->psychiatrist = $_POST["psychiatrist"];
$patientTreatment->last_note = $_POST["lastNote"];

if(!$patientTreatment->updatePatientTreatment()){
    Core::alert("Los datos no se pudieron actualizar");
}else{
    if($patientTreatment->status_id == 2 || $patientTreatment->status_id == 3){
        $patientTreatment->end_date = $_POST["endDate"];
        $patientTreatment->updatePatientTreatmentEndDate();
    }
}
Core::redir("./index.php?view=patients/medical-record&patientId=".$patientTreatment->patient_id);
