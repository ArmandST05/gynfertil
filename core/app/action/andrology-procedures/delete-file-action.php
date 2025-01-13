<?php
$file = AndrologyProcedureData::getFileByProcedureSectionId($_POST["andrologyProcedureId"],$_POST["imageSectionId"]);
$delete = AndrologyProcedureData::deleteFilesByProcedureSection($_POST["andrologyProcedureId"],$_POST["imageSectionId"]);
if($delete){
    unlink($file->path);//Eliminar archivo si existe
    return http_response_code(200);
}
else return http_response_code(500);
?>