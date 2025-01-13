<?php
if(count($_POST) > 0){
    $transferDetail = EmbryologyProcedureTransferData::getByTreatmentId($_POST["patientCategoryTreatmentId"]);
    //Actualizar registro existente
    if(isset($transferDetail)){
        $transferDetail->date = $_POST["date"];
        $transferDetail->hour = $_POST["hour"];
        $transferDetail->total = $_POST["total"];
        $transferDetail->quality = $_POST["quality"];
        $transferDetail->embryo_id_details = $_POST["embryoIdDetails"];
        $transferDetail->gynecologist_id = $_POST["gynecologist"];
        $transferDetail->sonographer_id = $_POST["sonographer"];
        $transferDetail->embryologist_id = $_POST["embryologist"];
        $transferDetail->witness_id = $_POST["witness"];
        $transferDetail->estradiol = $_POST["estradiol"];
        $transferDetail->progesterone = $_POST["progesterone"];
        $transferDetail->catheter = $_POST["catheter"];
        $transferDetail->catheter_lot = $_POST["catheterLot"];
        $transferDetail->catheter_expiration = $_POST["catheterExpiration"];
        $transferDetail->syringe = $_POST["syringe"];
        $transferDetail->syringe_lot = $_POST["syringeLot"];
        $transferDetail->syringe_expiration = $_POST["syringeExpiration"];
        $transferDetail->observations = $_POST["observations"];
        //Actualizar existente
        if($transferDetail->update()) Core::redir("./index.php?view=embryology-procedures/details&treatmentId=". $_POST["patientCategoryTreatmentId"]."");
        else http_response_code(500);
    }
    else{
        //Crear nuevo registro
        $transferDetail = new EmbryologyProcedureTransferData();
        $transferDetail->patient_category_treatment_id = $_POST["patientCategoryTreatmentId"];
        $transferDetail->date = $_POST["date"];
        $transferDetail->hour = $_POST["hour"];
        $transferDetail->total = $_POST["total"];
        $transferDetail->quality = $_POST["quality"];   
        $transferDetail->embryo_id_details = $_POST["embryoIdDetails"];
        $transferDetail->gynecologist_id = $_POST["gynecologist"];
        $transferDetail->sonographer_id = $_POST["sonographer"];
        $transferDetail->embryologist_id = $_POST["embryologist"];
        $transferDetail->witness_id = $_POST["witness"];
        $transferDetail->estradiol = $_POST["estradiol"];
        $transferDetail->progesterone = $_POST["progesterone"];
        $transferDetail->catheter = $_POST["catheter"];
        $transferDetail->catheter_lot = $_POST["catheterLot"];
        $transferDetail->catheter_expiration = trim($_POST["catheterExpiration"]);
        $transferDetail->syringe = $_POST["syringe"];
        $transferDetail->syringe_lot = $_POST["syringeLot"];
        $transferDetail->syringe_expiration = $_POST["syringeExpiration"];
        $transferDetail->observations = $_POST["observations"];

        if($transferDetail->add()) Core::redir("./index.php?view=embryology-procedures/details&treatmentId=". $_POST["patientCategoryTreatmentId"]."");
        else http_response_code(500);
    }
}
else{
    return http_response_code(500);
}
