<?php
$userId = $_SESSION['user_id'];
$andrologyProcedure = new AndrologyProcedureData();

$andrologyProcedure->andrology_procedure_id = $_POST["andrologyProcedureId"];
$andrologyProcedure->primary_procedure_id = 0;
$andrologyProcedure->patient_id = $_POST["patientId"];
$andrologyProcedure->medic_id = $_POST["medicId"];
$andrologyProcedure->date = $_POST["date"];

//ESTABLECER CÓDIGO DE PROCEDIMIENTO
$procedure = AndrologyProcedureData::getById($_POST["andrologyProcedureId"]);
//Obtener el total de procedimientos realizados de ese tipo para el consecutivo
$totalProceduresData = AndrologyProcedureData::getTotalProceduresByType($_POST["andrologyProcedureId"]);
$totalProcedures = ((isset($totalProceduresData) && floatval($totalProceduresData->total) > 0) ? floatval($totalProceduresData->total)+1 : 1);
//Al ser los primeros códigos, rellenar con 0.
//String, lenght,
$total = str_pad($totalProcedures,3,"0",STR_PAD_LEFT);

$procedureCode = $procedure->code.$total;
$andrologyProcedure->procedure_code = $procedureCode;

$newAndrologyProcedure = $andrologyProcedure->addProcedureByPatient();

if($newAndrologyProcedure && $newAndrologyProcedure[1]){
    //Guardar los datos de la pareja del paciente para el procedimiento, en caso de que el paciente cambie de pareja en el futuro,mantener el historial.
    $patient = PatientData::getById($_POST["patientId"]);

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
    
    print "<script>window.history.back();</script>";
}
else  print "<script>alert('Ocurrió un error al guardar el procedimiento');window.history.back();</script>";
