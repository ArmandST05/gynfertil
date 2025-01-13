<?php
$file = EmbryologyProcedureData::getFileByTreatmentOvuleSectionId($_POST["treatmentId"],$_POST["procedureOvuleId"],$_POST["imageSectionId"]);
$delete = EmbryologyProcedureData::deleteFilesByTreatmentOvuleSection($_POST["treatmentId"],$_POST["procedureOvuleId"],$_POST["imageSectionId"]);
if($delete){
    unlink($file->path);//Eliminar archivo si existe
    return http_response_code(200);
}
else return http_response_code(500);
?>