<?php
$response = [];
$valor="";
if(count($_POST)>0){
	$patient = new PatientData();
    $result = PatientData::get_registro_paciente($_POST["name"]);
    foreach ($result as $key ) {
      $valor=$key->name;
    }

  if($valor==""){
    $patient->name = strtoupper(trim($_POST["name"]));
    $patient->sex_id = $_POST["sexId"];
    $patient->tel = trim($_POST["tel"]);
    $patient->image = "";
    //Si tiene pareja registrada como paciente, agregar la pareja a ambos.
    if($_POST["isRelativeRegistered"] == "true"){
      $patient->relative_name = "";
      $patient->relative_id = $_POST["relativeId"];
      //Eliminar cualquier relación registrada previamente que tenga la pareja
      PatientData::deleteRelative($_POST["relativeId"]);
    }else{
      $patient->relative_name = strtoupper(trim($_POST["relativeName"]));
      $patient->relative_id = 0;
    }

   if(strlen($_POST['image'])>6){
      $image_data = $_POST["image"];

      $image_array_1 = explode(";", $image_data);
      $image_array_2 = explode(",", $image_array_1[1]);

      $image_data = base64_decode($image_array_2[1]);

      $imageName = time().'.jpg';
      if(file_put_contents("storage_data/patients/".$imageName, $image_data)){
        $patient->image = $imageName;
      }
   }
   $newPatient = $patient->add();
   if($newPatient && $newPatient[1]){
    if(isset($_POST["isRelativeRegistered"]) && $_POST["isRelativeRegistered"] == "true"){//Sin las comillas el true marca error en el servidor
      //Asignar como pareja el paciente creado
      $relative = PatientData::getById($_POST["relativeId"]);
      $relative->relative_id = $newPatient[1];
      $relative->updateRelative();
    }
     return http_response_code(200);
   }else return http_response_code(500);
  }
  else{
    return http_response_code(401);
  }
}
?>