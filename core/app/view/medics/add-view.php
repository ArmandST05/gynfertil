<?php
if(count($_POST)>0){
	$medic = new MedicData();
	$category_id = "NULL";
	if($_POST["categoryId"]!=""){ $category_id = $_POST["categoryId"]; }
	$medic->type_id = $_POST["typeId"];
	$medic->name = $_POST["name"];
	$medic->category_id = $category_id;
    $medic->id_usuario = $_POST["userId"];
	$medic->specialty_title = $_POST["specialty_title"];
	$medic->is_digital_signature = (isset($_POST["isDigitalSignature"])) ? $_POST["isDigitalSignature"]:0;
	$newMedic = $medic->add();

	if($newMedic && $newMedic[1]){
		//Crear carpeta del médico si no existe
		$path = "storage_data/medics/" . $newMedic[1];
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}

		if (isset($_FILES["digitalSignature"]) && ($_FILES["digitalSignature"]["size"] > 0)) {
			$originalFileName = $_FILES["digitalSignature"]["name"];
			$fileName = "digital-sign";//$_FILES["digitalSignature"]["tmp_name"]
			//$fileName = ConfigurationData::formatFileName($fileName);
			// File temp source 
			$fileTemp = $_FILES["digitalSignature"]["tmp_name"];
			$path = pathinfo($originalFileName);
			$ext = "png";//$path['extension'];
			$targetFilePath = "storage_data/medics/" . $newMedic[1] . "/" . $fileName . "." . $ext;
			$pathFilenameExtension = $targetFilePath . "." . $ext;

			//Si hay un archivo del mismo nombre, eliminar
			if (file_exists($pathFilenameExtension)) {
				unlink($pathFilenameExtension);
			}
			//Guardar archivo
			if (move_uploaded_file($fileTemp, $targetFilePath)) {
				$medic = MedicData::getById($newMedic[1]);
				$medic->digital_signature_path = $fileName. "." . $ext;
				$medic->updateDigitalSignature();
			}
		}
	}

 print "<script>window.location='index.php?view=medics/index';</script>";
}
