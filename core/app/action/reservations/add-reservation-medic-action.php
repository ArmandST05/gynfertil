<?php
    $reservation = new ReservationData();
    $reservation->user_id =  $_POST["userId"];
    $reservation->medic_id = $_POST["medic"];
    $reservation->date_at = $_POST["date"]." ".$_POST["timeAt"];
    $reservation->date_at_final = $_POST["date"]." ".$_POST["timeAtFinal"];
    $reservation->reason = strtoupper(trim($_POST["reason"]));

    $medic = MedicData::getById($_POST["medic"]);
    $reservation->branch_office_id = $medic->branch_office_id;
    $newReservation = $reservation->addDoctor();

    //Registrar log
    $log = new LogData();
    $log->row_id = $newReservation[1];
    $log->branch_office_id = $reservation->branch_office_id;
    $log->user_id = $_SESSION["user_id"];
    $log->module_id = 2;
    $log->action_type_id = 1;
    $log->description = "Se agregó una cita para el psicólogo ".MedicData::getById($_POST["medic"])->name." para el día ".$reservation->date_at;
    $newLog = $log->add();
    Core::redir("./index.php?view=home&date=".$_POST["date"]."");
?>