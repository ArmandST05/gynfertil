<?php
//Obtiene una lista de los óvulos/embriones válidos por tratamiento.
//No obtiene los que son no viables o los que se transfirieron, tampoco los que se han utilizado en un tratamiento posterior.
if($_GET["endPhaseId"] == 0){
    //Obtiene todos, tanto óvulos embriones
    $ovules = PatientOvuleData::getValidOvulesByProcedureId($_GET["treatmentId"],$_GET["endPhaseId"]);
}else{
    //Filtra por óvulos/embriones
    $ovules = PatientOvuleData::getValidOvulesByEndPhaseProcedureId($_GET["treatmentId"],$_GET["endPhaseId"]);
}
echo json_encode($ovules);
?>