<?php
if(count($_POST) > 0){
    //Actualizar columna de vitrificación
    $updateDetail = EmbryologyProcedureVitrificationData::updateDetail($_POST["sectionDetailId"],$_POST["columnName"],$_POST["value"]);
    if($updateDetail) return http_response_code(200);
    else return http_response_code(500);
}
else{
    return http_response_code(500);
}
?>