<?php
$configurations = ConfigurationData::getAll();
$patientCategory = PatientCategoryData::getById($_POST["patientCategoryTreatmentId"]);

//Guardar los datos de la pareja del paciente para el tratamiento, en caso de que el paciente cambie de pareja en el futuro,mantener el historial.
$patient = PatientData::getById($patientCategory->patient_id);

$treatmentPartner = PatientProcedurePartnerData::getTreatmentPartner($_POST["patientCategoryTreatmentId"]);
$treatmentPartner->patient_category_treatment_id = $_POST["patientCategoryTreatmentId"];
$treatmentPartner->patient_andrology_procedure_id = null;
if ($patient->relative_id) { //Registrar pareja que es paciente
    $treatmentPartner->partner_id = $patient->relative_id;
    $treatmentPartner->name = null;
    $treatmentPartner->birthday = null;
    $treatmentPartner->official_document_id = null;
    $treatmentPartner->official_document_value = null;
} else { //Registrar datos de pareja que aún no es paciente
    $treatmentPartner->partner_id = null;
    $treatmentPartner->name = $patient->relative_name;
    $treatmentPartner->birthday = $patient->relative_birthday;
    $treatmentPartner->official_document_id = $patient->relative_official_document_id;
    $treatmentPartner->official_document_value = $patient->relative_official_document_value;
}
$treatmentPartner->update();

return http_response_code(200);
