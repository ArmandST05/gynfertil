<?php
//upload_max_filesize = 512M (MAXIMUM)-HOSTGATOR PHP.INI CONFIGURATION
$procedureFile = new EmbryologyProcedureData();
$procedureFile->patient_category_treatment_id = $_POST["treatmentId"];
$procedureFile->procedure_ovule_id = $_POST["procedureOvuleId"];
$treatment = PatientCategoryData::getById($_POST["treatmentId"]);
$imageSectionId = $_POST["imageSectionId"];
$procedureFile->file_section_id = $imageSectionId;//Óvulos

if (!empty($_FILES["file"]["name"])) {
    
    $originalFileName = $_FILES["file"]["name"];
    // Image temp source 
    $fileTemp = $_FILES["file"]["tmp_name"];
    $path = pathinfo($originalFileName);
    $ext = $path['extension'];

    if($imageSectionId == 1){
        //Imágenes de óvulos
        $folderPath = "storage_data/ovules/";
        $procedureOvule = PatientOvuleData::getProcedureOvuleById($_POST["procedureOvuleId"]);
        $patientOvule = PatientOvuleData::getById($procedureOvule->patient_ovule_id);
    
        $fileName = $treatment->treatment_code . "-" .$procedureOvule->section_id. "-" . $patientOvule->procedure_code;
    }else{
        //Resultados PGTA,etc.
        $folderPath = "storage_data/embryology_procedures/";
        $fileName = $treatment->treatment_code . "-" . $_POST["nameFile"];
    }

    $targetFilePath = $folderPath . $fileName . "." . $ext;
    EmbryologyProcedureData::deleteFilesByTreatmentOvuleSection($_POST["treatmentId"],$_POST["procedureOvuleId"],$imageSectionId);
    if (file_exists($targetFilePath)) {   
        unlink($targetFilePath);//Eliminar archivo si existe
    } 
    $procedureFile->path = $targetFilePath;

    //Ovule image
    if ($ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "gif") {
        // Comprimos el fichero
        if (compressImage($fileTemp, $targetFilePath, 75)) { 
            $newFile = $procedureFile->addFile();
            if ($newFile && $newFile[1]){
                echo $targetFilePath;
            }
            else return http_response_code(500);
        } else {
            echo ("fail-move");
            return http_response_code(500);
        }
    } else {
        if (move_uploaded_file($fileTemp, $targetFilePath)) {
            if ($procedureFile->addFile()) echo $targetFilePath;
            else return http_response_code(500);
        }
    }
} else return http_response_code(500);

function compressImage($source, $destination, $quality)
{
    // Obtenemos la información de la imagen
    $fileInfo = getimagesize($source);
    $mime = $fileInfo['mime'];
    // Creamos una imagen
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }

    // Guardamos la imagen
    imagejpeg($image, $destination, $quality);

    // Devolvemos la imagen comprimida
    return $destination;
}
