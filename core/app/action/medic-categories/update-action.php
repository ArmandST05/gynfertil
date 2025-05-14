<?php
if(count($_POST)>0){
	$category = CategoryMedicData::getById($_POST["id"]);
	$category->name = $_POST["name"];
	
	if(!$category->update()){
		Core::alert("Ocurrió un error al actualizar.");
	}else{
		//Registrar log
		$log = new LogData();
		$log->row_id = $category->id;
		$log->branch_office_id = 0;
		$log->user_id = $_SESSION["user_id"];
		$log->module_id = 4;
		$log->action_type_id = 2;
		$log->description = "Se actualizó la especialidad para psicólogos ".$category->name." con ID:".$category->id;
		$newLog = $log->add();
	}
	print "<script>window.location='index.php?view=medic-categories/index';</script>";
}
?>