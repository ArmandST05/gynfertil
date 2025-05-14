<?php
if(count($_POST)>0){
	$patient = new PatientData();
  //Validar si el nombre del paciente está registrado
  $isRegistered = PatientData::getByName(trim($_POST["name"]));

  if(!$isRegistered){
    $patient->name = strtoupper(trim($_POST["name"]));
    $patient->sex_id = $_POST["sex"];
    $patient->curp = $_POST["curp"];
    $patient->street = strtoupper(trim($_POST["street"]));
    $patient->number = strtoupper(trim($_POST["number"]));
    $patient->colony = strtoupper(trim($_POST["colony"]));
    $patient->cellphone = trim($_POST["cellphone"]);
    $patient->homephone = trim($_POST["homephone"]);
    $patient->email = trim($_POST["email"]);
    $patient->birthday = ($_POST["birthday"]) ? $_POST["birthday"]: null;
    $patient->referred_by = strtoupper(trim($_POST["referred_by"]));
    $patient->relative_name = strtoupper(trim($_POST["relative_name"]));
    $patient->company_id = $_POST["companyId"];
    $patient->category_id = 1;//ACTIVO
    $patient->observations = strtoupper($_POST["observations"]);
    $patient->county_id = trim($_POST["countyId"]);
    $patient->education_level_id = $_POST["educationLevelId"];
    $patient->occupation = trim($_POST["occupation"]);
    $patient->image = "";
    
    //Asignar sucursal dependiendo del usuario que inició sesión
    $user = UserData::getLoggedIn();
    $userType = $user->user_type;

    if($userType == "su" || $userType == "co"){
      $patient->branch_office_id = $_POST["branchOfficeId"];
    }else{
      $patient->branch_office_id = $user->branch_office_id;
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
      //Registrar tratamiento:
        $patientTreatment = new TreatmentData();
        $patientTreatment->treatment_id = $_POST["treatmentId"];
        $patientTreatment->patient_id = $newPatient[1];
        $patientTreatment->medic_id = $_POST["medicId"];
        $patientTreatment->default_price = $_POST["defaultPrice"];
        $patientTreatment->reason = $_POST["reason"];
        $patientTreatment->start_date = date("Y-m-d");

        $newTreatment = $patientTreatment->addTreatmentByPatient();

        //Registrar log
        $log = new LogData();
        $log->row_id = $newPatient[1];
        $log->branch_office_id = $patient->branch_office_id;
        $log->user_id = $_SESSION["user_id"];
        $log->module_id = 1;
        $log->action_type_id = 1;
        $log->description = "Se agregó el paciente ".$patient->name." con ID:".$newPatient[1];
        $newLog = $log->add();

        $treatmentDetail = TreatmentData::getById($_POST["treatmentId"]);
        $data = [];
        $data["code"] = $treatmentDetail->code;
        $data["id"] = $newTreatment[1];
      
        echo json_encode($data);

    }else{
      return http_response_code(500);
    }
  }
  else{
    return http_response_code(500);
    Core::alert("Paciente: ".$_POST["name"]." ya está registrado");
    //print "<script>window.location='index.php?view=patients/index';</script>";
  }

}
