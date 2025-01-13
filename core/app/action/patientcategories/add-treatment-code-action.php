<?php
$configurations = ConfigurationData::getAll();

$user_id = $_SESSION['user_id'];
$patientCategory = PatientCategoryData::getById($_POST["patientCategoryTreatmentId"]);

//Si es un tratamiento actualizar los datos específicos
if($patientCategory->patient_category_id == 3){

    //ESTABLECER CÓDIGO DE PROCEDIMIENTO SI EL TRATAMIENTO ES DE EMBRIOLOGÍA, SI ESTÁ ACTIVADO EN LA CONFIGURACIÓN EL CREAR LOS CÓDIGOS
    //Y SI EL TRATAMIENTO TENDRÁ SEGUIMIENTO LOCAL(treatment_location_id)
    $treatment = PatientCategoryData::getTreatmentById($patientCategory->patient_treatment_id);
    
    if($treatment->is_embryology_procedure == 1 && $configurations["active_embryology_codes"]->value == 1 && $patientCategory->treatment_location_id == 1){
        //Obtener el total de procedimientos realizados de ese tipo para el consecutivo
        $totalTreatmentsData = PatientCategoryData::getTotalTreatmentsByType($patientCategory->patient_treatment_id);
        $totalTreatments = ((isset($totalTreatmentsData) && floatval($totalTreatmentsData->total_treatments) > 0) ? floatval($totalTreatmentsData->total_treatments)+1 : 1);
        //Al ser los primeros códigos, rellenar con 0. //String, lenght,
        $total = str_pad($totalTreatments,3,"0",STR_PAD_LEFT);

        $treatmentCode = $treatment->embryology_procedure_code.$total;
        $patientCategory->treatment_code = $treatmentCode;
    }   
    //Si se registró como el tratamiento "Donadora de Óvulos" o "Donadora de Embriones" por primera vez se registra el id de donador en la tabla pacientes y si se estableció en la configuración
    if(($patientCategory->patient_treatment_id == 12 || $patientCategory->patient_treatment_id == 13) && $configurations["active_embryology_donors"]->value == 1){
        $patient = PatientData::getById($patientCategory->patient_id);
        if($patient->donor_id == null || $patient->donor_id == ""){
            $patient->updatePatientAsDonant();
        }
    }

    $updatePatientCategory = $patientCategory->updateEmbryologyCode();

    if($updatePatientCategory[0]){
        http_response_code(200);
    }else{
        http_response_code(500);
    }
}else{
    http_response_code(500);
}