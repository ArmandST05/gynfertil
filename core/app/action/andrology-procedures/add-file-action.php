<?php
//upload_max_filesize = 512M (MAXIMUM)-HOSTGATOR PHP.INI CONFIGURATION
$procedureFile = new AndrologyProcedureData();
$procedureFile->patient_andrology_procedure_id = $_POST["andrologyProcedureId"];
$andrologyProcedure = AndrologyProcedureData::getPatientProcedureById($_POST["andrologyProcedureId"]);
$imageSectionId = $_POST["imageSectionId"];
$procedureFile->file_section_id = $imageSectionId;

if (!empty($_FILES["file"]["name"])) {
    
    $originalFileName = $_FILES["file"]["name"];
    // Image temp source 
    $fileTemp = $_FILES["file"]["tmp_name"];
    $path = pathinfo($originalFileName);
    $ext = $path['extension'];

    if($imageSectionId == 3 || $imageSectionId == 4){
        //Imágenes de evidencia andrología,etc.
        $folderPath = "storage_data/andrology_procedures/";
        $fileName = $andrologyProcedure->procedure_code . "-" . $_POST["nameFile"];
    }

    $targetFilePath = $folderPath . $fileName . "." . $ext;
    if (file_exists($targetFilePath)) {   
        AndrologyProcedureData::deleteFilesByProcedureSection($_POST["andrologyProcedureId"],$imageSectionId);
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
