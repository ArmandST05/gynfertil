<?php
/*Se crea una subcategoría/subtratamiento para el paciente
Por ejemplo:
En FIVTE/DONOVO se requiere vitrificación de embriones entonces se crea el tratamiento o código para VITOVULO,
pero al paciente se le sigue mostrando el código principal de FIVTE/DONOVO no el subcódigo.
Estos subcódigos sólo aparecen en el detalle de embriología
*/

$primaryTreatment = PatientCategoryData::getById($_POST["primaryTreatmentId"]);

$patientCategory = new PatientCategoryData();
$patientCategory->patient_category_id = 3; //Es un tratamiento
$patientCategory->treatment_location_id = 1;//Tratamiento local porque es un sub-tratamiento
//Guardar datos específicos del tratamiento
$patientCategory->patient_treatment_id = $_POST["patientTreatmentId"];
$patientCategory->primary_treatment_id = $_POST["primaryTreatmentId"];

//ESTABLECER CÓDIGO DE PROCEDIMIENTO SI EL TRATAMIENTO ES DE EMBRIOLOGÍA
$treatment = PatientCategoryData::getTreatmentById($_POST["patientTreatmentId"]);
//Obtener el total de procedimientos realizados de ese tipo para el consecutivo
$totalTreatmentsData = PatientCategoryData::getTotalTreatmentsByType($_POST["patientTreatmentId"]);
$totalTreatments = ((isset($totalTreatmentsData) && floatval($totalTreatmentsData->total_treatments) > 0) ? floatval($totalTreatmentsData->total_treatments)+1 : 1);
//Al ser los primeros códigos, rellenar con 0.
//String, lenght,
$total = str_pad($totalTreatments, 3, "0", STR_PAD_LEFT);
$treatmentCode = $treatment->embryology_procedure_code . $total;
$patientCategory->treatment_code = $treatmentCode;

$patientCategory->patient_id = $primaryTreatment->patient_id;
$patientCategory->start_date = date("Y-m-d");
$newPatientCategory = $patientCategory->addCategoryByPatient();//Guardar tratamiento

if ($newPatientCategory && $newPatientCategory[1]) {
    $primaryTreatmentDiagnostics = TreatmentDiagnosticData::getByTreatment($_POST["primaryTreatmentId"]);
    //Guardar los diagnósticos del subtratamiento, motivo por el que se realiza, utilizar los mismos que el procedimiento primario.
    if ($primaryTreatmentDiagnostics) {
        foreach ($primaryTreatmentDiagnostics as $diagnostic) {
            $treatmentDiagnostic = new TreatmentDiagnosticData();
            $treatmentDiagnostic->patient_category_treatment_id = $newPatientCategory[1];
            $treatmentDiagnostic->treatment_diagnostic_id = $diagnostic->treatment_diagnostic_id;
            $treatmentDiagnostic->description = $diagnostic->description;
            $treatmentDiagnostic->addDiagnosticTreatment();
        }
    }

    //SI SE CREA UN SUBÓDIGO DE VITRIFICACIÓN DE ÓVULOS, ENVIAR LOS ÓVULOS VITRIFICADOS DEL TRATAMIENTO PRIMARIO AL SUBCÓDIGO.
    if($_POST["patientTreatmentId"] == 10){
        //Obtiene los óvulos/embriones de acuerdo a su estatus o fase (óvulos/embriones)
        $primaryProcedureOvules = PatientOvuleData::getOvulesByStatusPhaseProcedureId($_POST["primaryTreatmentId"],2,1);
        foreach($primaryProcedureOvules as $primaryProcedureOvule){
            $ovuleProcedure = new PatientOvuleData();
            $ovuleProcedure->ovule_status_id = 1;
            $ovuleProcedure->section_id = 1;
            $ovuleProcedure->patient_category_treatment_id = $newPatientCategory[1];
            $ovuleProcedure->patient_ovule_id = $primaryProcedureOvule->patient_ovule_id;
            $ovuleProcedure->addByProcedure();
        }
    }

    //Guardar los datos de la pareja del paciente para el tratamiento, en caso de que el paciente cambie de pareja en el futuro,mantener el historial.
    $patient = PatientData::getById($primaryTreatment->patient_id);

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

    $data = [];
    $data["id"] = $newPatientCategory[1];
    $data["treatment_code"] = $treatmentCode;

    echo json_encode($data); //Regresar el id del tratamiento/categoría y el código
} else http_response_code(500);
