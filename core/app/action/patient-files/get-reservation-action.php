<?php
$reservationId = $_GET["reservationId"];
$patientId = $_GET["patientId"];

$files = PatientData::getAllFilesByPatientReservation($patientId, $reservationId);


foreach ($files as $file){
    echo '<div class="col-lg-4">
        <a href="storage_data/files/' . $file->path.'" target="__blank" class="btn btn-default btn-sm"><i class="fas fa-eye"></i> '.$file->path.'</a><button class="btn btn-sm btn-danger" onclick="deleteFile('.$file->id.')"><i class="fas fa-trash"></i></button>
    </div>';
}
?>
