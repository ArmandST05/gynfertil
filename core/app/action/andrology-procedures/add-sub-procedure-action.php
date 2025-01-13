<?php
/*Se crea un subprocedimiento para el paciente
Por ejemplo:
En BT se requiere congelación de semen, entonces se crea el procedimiento o código para SPERMFREEZING.
*/
$primaryProcedure = AndrologyProcedureData::getPatientProcedureById($_POST["primaryProcedureId"]);

$patientProcedure = new AndrologyProcedureData();
$patientProcedure->andrology_procedure_id = $_POST["andrologyProcedureId"];
$patientProcedure->primary_procedure_id = $_POST["primaryProcedureId"];
$patientProcedure->patient_id = $primaryProcedure->patient_id;
$patientProcedure->date = date("Y-m-d");

//ESTABLECER CÓDIGO DE PROCEDIMIENTO
$procedure = AndrologyProcedureData::getById($_POST["andrologyProcedureId"]);
//Obtener el total de procedimientos realizados de ese tipo para el consecutivo
$totalProceduresData = AndrologyProcedureData::getTotalProceduresByType($_POST["andrologyProcedureId"]);
$totalProcedures = ((isset($totalProceduresData) && floatval($totalProceduresData->total) > 0) ? floatval($totalProceduresData->total)+1 : 1);

//Al ser los primeros códigos, rellenar con 0.
//String, lenght,
$total = str_pad($totalProcedures,3,"0",STR_PAD_LEFT);

$procedureCode = $procedure->code.$total;
$patientProcedure->procedure_code = $procedureCode;

$newAndrologyProcedure = $patientProcedure->addProcedureByPatient();

if ($newAndrologyProcedure && $newAndrologyProcedure[1]) {
    //Guardar los datos de la pareja del paciente para el procedimiento, en caso de que el paciente cambie de pareja en el futuro,mantener el historial.
    $patient = PatientData::getById($primaryProcedure->patient_id);

    $procedurePartner = new PatientProcedurePartnerData();
    $procedurePartner->patient_category_treatment_id = null;
    $procedurePartner->patient_andrology_procedure_id = $newAndrologyProcedure[1];
    if($patient->relative_id){//Registrar pareja que es paciente
        $procedurePartner->partner_id = $patient->relative_id;
        $procedurePartner->name = null;
        $procedurePartner->birthday = null;
        $procedurePartner->official_document_id = null;
        $procedurePartner->official_document_value = null;
    }else{//Registrar datos de pareja que aún no es paciente
        $procedurePartner->partner_id = null;
        $procedurePartner->name = $patient->relative_name;
        $procedurePartner->birthday = $patient->relative_birthday;
        $procedurePartner->official_document_id = $patient->relative_official_document_id;
        $procedurePartner->official_document_value = $patient->relative_official_document_value;
    }
    $procedurePartner->add();
    
    $data = [];
    $data["id"] = $newAndrologyProcedure[1];
    $data["procedureCode"] = $procedureCode;

    echo json_encode($data); //Regresar el id del procedimiento y el código
} else http_response_code(500);
