<?php
if(count($_POST)>0){
    $user = UserData::getLoggedIn();
    //Registrar log
    $log = new LogData();
    $log->row_id = $_POST["rowId"];
    $log->branch_office_id = $user->branch_office_id;
    $log->user_id = $_SESSION["user_id"];
    $log->module_id = $_POST["moduleId"];
    $log->action_type_id = $_POST["actionTypeId"];
    $log->description = $_POST["description"];
    $newLog = $log->add();

  if($newLog && $newLog[1]){
    return http_response_code(200);
  }else{
    return http_response_code(500);
  }
}
else{
  return http_response_code(500);
}
?>
