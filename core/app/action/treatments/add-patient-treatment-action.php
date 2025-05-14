<?php
$userId = $_SESSION['user_id'];
$finishPreviousTreatments = TreatmentData::finishAllTreatmentsByPatient($_POST["patientId"]);
$totalTreatments = TreatmentData::getTotalPatientTreatments($_POST["patientId"])->total;

$patientTreatment = new TreatmentData();
$patientTreatment->treatment_id = $_POST["treatmentId"];
$patientTreatment->patient_id = $_POST["patientId"];
$patientTreatment->medic_id = $_POST["medicId"];
$patientTreatment->default_price = $_POST["defaultPrice"];
$patientTreatment->reason = $_POST["reason"];
$patientTreatment->start_date = date("Y-m-d");

$newTreatment = $patientTreatment->addTreatmentByPatient();
if($newTreatment && $newTreatment[1]){
    //Establecer al paciente como "Activo"
    $patient = PatientData::getById($_POST["patientId"]);
    $patient->category_id = (($totalTreatments == 0) ? 1: 4);//1Activo, 4Reingreso
    if($totalTreatments == 0){
        $patient->category_id = 1; //Activo
    }else{
        //Validar fecha último tratamiento
        //Después de 6 meses se considera como "activo" y no como "activo-reingreso"

        $lastTreatment = TreatmentData::getLastFinishedPatientTreatment($_POST["patientId"]);

        // Convertir las fechas a objetos DateTime
        $actualDateObj = new DateTime(date('Y-m-d'));
        $finalDateObj = new DateTime($lastTreatment->end_date);

        // Calcular la diferencia en meses
        $difference = $finalDateObj->diff($actualDateObj);

        // Obtener la diferencia total en meses
        $differenceMonths = $difference->y * 12 + $difference->m;

        if($differenceMonths >= 6){
            $patient->category_id = 1; //Activo
        }else{
            $patient->category_id = 4; //Reingreso
        }
    }

    $patient->updatePatientCategory();

    $data = [];
    $data["id"] = $newTreatment[1];
    echo json_encode($data);//Regresar el id del tratamiento
}
else http_response_code(500);
