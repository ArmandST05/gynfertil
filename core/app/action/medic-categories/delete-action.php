<?php
$category = CategoryMedicData::getById($_GET["id"]);

if($category->delete()){
    Core::alert("¡Eliminado exitosamente!");
    //Registrar log
    $log = new LogData();
    $log->row_id = $category->id;
    $log->branch_office_id = 0;
    $log->user_id = $_SESSION["user_id"];
    $log->module_id = 4;
    $log->action_type_id = 3;
    $log->description = "Se eliminó la especialidad para psicólogos ".$category->name." con ID:".$category->id;
    $newLog = $log->add();
}
else Core::alert("Ocurrió un error al eliminar.");
Core::redir("./index.php?view=medic-categories/index");
?>