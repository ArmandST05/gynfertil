<?php
if (count($_POST) > 0) {
	$medic = MedicData::getById($_POST["id"]);
	$medic->professional_license = trim($_POST["professionalLicense"]);
	$medic->study_center = trim($_POST["studyCenter"]);
	$medic->email = trim($_POST["email"]);
	$medic->phone = trim($_POST["phone"]);
	$medic->is_digital_signature = (isset($_POST["isDigitalSignature"])) ? $_POST["isDigitalSignature"]:0;
	$medic->is_fiel_key = (isset($_POST["isFielKey"])) ? $_POST["isFielKey"]:0;
	if ($_POST["fielKeyPassword"] != "") {
		$medic->fiel_key_password = $_POST["fielKeyPassword"];
		$medic->updateFielKeyPassword();
	}
	$medic->updateProfile();

	function formatFileName($string)
	{

		$string = str_replace(".", "", $string);
		$string = str_replace(" ", "", $string);

		//Reemplazamos la A y a
		$string = str_replace(
			array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
			array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
			$string
		);

		//Reemplazamos la E y e
		$string = str_replace(
			array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
			array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
			$string
		);

		//Reemplazamos la I y i
		$string = str_replace(
			array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
			array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
			$string
		);

		//Reemplazamos la O y o
		$string = str_replace(
			array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
			array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
			$string
		);

		//Reemplazamos la U y u
		$string = str_replace(
			array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
			array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
			$string
		);

		//Reemplazamos la N, n, C y c
		$string = str_replace(
			array('Ñ', 'ñ', 'Ç', 'ç'),
			array('N', 'n', 'C', 'c'),
			$string
		);

		return $string;
	}

	//Crear carpeta del médico si no existe
	$path = "storage_data/medics/" . $_POST['id'];
	if (!file_exists($path)) {
		mkdir($path, 0777, true);
	}

	if (isset($_FILES["digitalSignature"]) && ($_FILES["digitalSignature"]["size"] > 0)) {
		$originalFileName = $_FILES["digitalSignature"]["name"];
		$fileName = $_FILES["digitalSignature"]["name"];
		$fileName = formatFileName($fileName);
		// File temp source 
		$fileTemp = $_FILES["digitalSignature"]["tmp_name"];
		$path = pathinfo($originalFileName);
		$ext = $path['extension'];
		$targetFilePath = "storage_data/medics/" . $_POST['id'] . "/" . $fileName . "." . $ext;
		$pathFilenameExtension = $targetFilePath . "." . $ext;

		//Si hay un archivo del mismo nombre, eliminar
		if (file_exists($pathFilenameExtension)) {
			unlink($pathFilenameExtension);
		}
		//Guardar archivo
		if (move_uploaded_file($fileTemp, $targetFilePath)) {
			$medic->digital_signature_path = $fileName. "." . $ext;
			$medic->updateDigitalSignature();
		}
	}
	if (isset($_FILES["fielKey"]) && ($_FILES["fielKey"]["size"] > 0)) {
		$originalFileName = $_FILES["fielKey"]["name"];
		$fileName = $_FILES["fielKey"]["name"];
		$fileName = formatFileName($fileName);
		// File temp source 
		$fileTemp = $_FILES["fielKey"]["tmp_name"];
		$path = pathinfo($originalFileName);
		$ext = $path['extension'];
		$targetFilePath = "storage_data/medics/" . $_POST['id'] . "/" . $fileName . "." . $ext;
		$pathFilenameExtension = $targetFilePath . "." . $ext;

		//Si hay un archivo del mismo nombre, eliminar
		if (file_exists($pathFilenameExtension)) {
			unlink($pathFilenameExtension);
		}
		//Guardar archivo
		if (move_uploaded_file($fileTemp, $targetFilePath)) {
			$medic->fiel_key_path = $fileName. "." . $ext;
			$medic->updateFielKey();
		}
	}
	if (isset($_FILES["fielCertificate"]) && ($_FILES["fielCertificate"]["size"] > 0)) {
		$originalFileName = $_FILES["fielCertificate"]["name"];
		$fileName = $_FILES["fielCertificate"]["name"];
		$fileName = formatFileName($fileName);
		// File temp source 
		$fileTemp = $_FILES["fielCertificate"]["tmp_name"];
		$path = pathinfo($originalFileName);
		$ext = $path['extension'];
		$targetFilePath = "storage_data/medics/" . $_POST['id'] . "/" . $fileName . "." . $ext;
		$pathFilenameExtension = $targetFilePath . "." . $ext;

		//Si hay un archivo del mismo nombre, eliminar
		if (file_exists($pathFilenameExtension)) {
			unlink($pathFilenameExtension);
		}
		//Guardar archivo
		if (move_uploaded_file($fileTemp, $targetFilePath)) {
			$medic->fiel_certificate_path = $fileName. "." . $ext;
			$medic->updateFielCertificate();
		}
	}

	//Registrar log
	$log = new LogData();
	$log->row_id = $medic->id;
	$log->branch_office_id = $medic->branch_office_id;
	$log->user_id = $_SESSION["user_id"];
	$log->module_id = 3;
	$log->action_type_id = 2;
	$log->description = "El psicólogo ".$medic->name." con ID:".$medic->id." actualizó su perfil.";
	$newLog = $log->add();
	print "<script>window.location='index.php?view=configuration/edit-medic-profile';</script>";
}
