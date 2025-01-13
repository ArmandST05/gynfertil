<?php
/*Se crea una código consecutivo de vitrificación de embriones (2)
*/
//Obtener el total de vitrificaciones realizadas de ese tipo para el consecutivo
$totalVitrificationsData = EmbryologyProcedureVitrificationData::getTotalVitrificationsByType($_POST["vitrificationTypeId"]);
$totalVitrifications = ((isset($totalVitrificationsData) && floatval($totalVitrificationsData->total) > 0) ? floatval($totalVitrificationsData->total)+1 : 1);
//Al ser los primeros códigos, rellenar con 0.
//String, lenght,
$total = str_pad($totalVitrifications, 3, "0", STR_PAD_LEFT);

$vitrificationCode = "VITEMBRIO" . $total;
$vitrificationDetail = EmbryologyProcedureVitrificationData::getByTreatmentId($_POST["patientCategoryTreatmentId"]);

//Actualizar registro existente
if (isset($vitrificationDetail)) {
    //Actualizar existente
    $vitrificationDetail->code = $vitrificationCode;
    if ($vitrificationDetail->updateCode()) {
        $data = [];
        $data["code"] = $vitrificationCode;
        echo json_encode($data); //Regresar el código
    } else http_response_code(500);
} else {
    //Crear nuevo registro
    $vitrification = new EmbryologyProcedureVitrificationData();
    $vitrification->patient_category_treatment_id = $_POST["patientCategoryTreatmentId"];
    $vitrification->vitrification_type_id = 2;
    $newVitrification = $vitrification->add();

    if ($newVitrification && $newVitrification[0]) {
        $vitrificationData = EmbryologyProcedureVitrificationData::getById($newVitrification[1]);
        $vitrificationData->code = $vitrificationCode;
        $vitrificationData->updateCode();

        $data = [];
        $data["code"] = $vitrificationCode;
        echo json_encode($data); //Regresar el código
    } else http_response_code(500);
}
