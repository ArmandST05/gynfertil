<?php
    $reservation = ReservationData::getById($_POST["id"]);
    if($reservation->delete()){
        //Registrar log
        $log = new LogData();
        $log->row_id = $reservation->id;
        $log->branch_office_id = $reservation->branch_office_id;
        $log->user_id = $_SESSION["user_id"];
        $log->module_id = 2;
        $log->action_type_id = 3;
        $log->description = "Se eliminó la cita para el paciente ".PatientData::getById($reservation->patient_id)->name." con el psicólogo ".MedicData::getById($reservation->medic_id)->name." para el día ".$reservation->date_at;
        $newLog = $log->add();
        return $reservation;
    }
    else return http_response_code(500);
?>