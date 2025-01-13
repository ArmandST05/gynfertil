<?php
$configurations = ConfigurationData::getAll();

$user_id = $_SESSION['user_id'];
$finishPreviousTreatments = PatientCategoryData::finishAllCategoryTreatmentsByPatient($_POST["patient_id"]);
$patientCategory = new PatientCategoryData();

$patientCategory->patient_category_id = $_POST["patient_category_id"];
$patientCategory->patient_treatment_id = null;
$patientCategory->treatment_code = null;
$patientCategory->primary_treatment_id = 0;
$patientCategory->treatment_location_id = 1;//Local
$isPregnancyTest = 0;//Validar si el tratamiento requiere prueba de embarazo

//Si es un tratamiento guardar los datos específicos
if($_POST["patient_category_id"] == 3){
    $patientCategory->patient_treatment_id = $_POST["patient_treatment_id"];

    //ESTABLECER CÓDIGO DE PROCEDIMIENTO SI EL TRATAMIENTO ES DE EMBRIOLOGÍA, SI ESTÁ ACTIVADO EN LA CONFIGURACIÓN EL CREAR LOS CÓDIGOS
    //Y SI EL TRATAMIENTO TENDRÁ SEGUIMIENTO LOCAL(treatment_location_id)
    $treatment = PatientCategoryData::getTreatmentById($_POST["patient_treatment_id"]);
    $isPregnancyTest = $treatment->is_pregnancy_test;
    $patientCategory->treatment_location_id = $_POST["treatment_location_id"];//Local/Externo
    $patientCategory->treatment_code = null;
    
    //El código del tratamiento se asignará posteriormente para que se genere en orden real de captura. Actualización 31/10/2022
}

$patientCategory->patient_id = $_POST["patient_id"];
$patientCategory->start_date = date("Y-m-d");

$newPatientCategory = $patientCategory->addCategoryByPatient();

if($newPatientCategory && $newPatientCategory[1]){
    if($_POST["patient_category_id"] == 3){
        //Guardar los diagnósticos del tratamiento, motivo por el que se realiza
        if($_POST["patient_treatment_diagnostics"]){
            $diagnostics = $_POST["patient_treatment_diagnostics"];
            foreach($diagnostics as $diagnostic){
                $treatmentDiagnostic = new TreatmentDiagnosticData();
                $treatmentDiagnostic->patient_category_treatment_id = $newPatientCategory[1];
                $treatmentDiagnostic->treatment_diagnostic_id = $diagnostic;
                $treatmentDiagnostic->description = (($diagnostic == 9) ? $_POST["patient_treatment_diagnostic_other"]: "");//Si el diagnóstio es otros, colocar detalles
                $treatmentDiagnostic->addDiagnosticTreatment();
            }
        }

        //Guardar los datos de la pareja del paciente para el tratamiento, en caso de que el paciente cambie de pareja en el futuro,mantener el historial.
        $patient = PatientData::getById($_POST["patient_id"]);
    
        $treatmentPartner = new PatientProcedurePartnerData();
        $treatmentPartner->patient_category_treatment_id = $newPatientCategory[1];
        $treatmentPartner->patient_andrology_procedure_id = null;
        if($patient->relative_id){//Registrar pareja que es paciente
            $treatmentPartner->partner_id = $patient->relative_id;
            $treatmentPartner->name = null;
            $treatmentPartner->birthday = null;
            $treatmentPartner->official_document_id = null;
            $treatmentPartner->official_document_value = null;
        }else{//Registrar datos de pareja que aún no es paciente
            $treatmentPartner->partner_id = null;
            $treatmentPartner->name = $patient->relative_name;
            $treatmentPartner->birthday = $patient->relative_birthday;
            $treatmentPartner->official_document_id = $patient->relative_official_document_id;
            $treatmentPartner->official_document_value = $patient->relative_official_document_value;
        }
        $treatmentPartner->add();
    }

    $data = [];
    $data["id"] = $newPatientCategory[1];
    $data["is_pregnancy_test"] = $isPregnancyTest;

    echo json_encode($data);//Regresar el id del tratamiento/categoría
}

else http_response_code(500);
