<?php
if(count($_POST)>0){
	$category = new CategoryMedicData();
	$category->name = $_POST["name"];
	$newCategory = $category->add();

	if($newCategory && $newCategory[1]) {
		//Registrar log
		$log = new LogData();
		$log->row_id = $newCategory[1];
		$log->branch_office_id = 0;
		$log->user_id = $_SESSION["user_id"];
		$log->module_id = 4;
		$log->action_type_id = 1;
		$log->description = "Se agregó la especialidad para psicólogos ".$category->name." con ID:".$newCategory[1];
		$newLog = $log->add();

	}else{Core::alert("Ocurrió un error al agregar.");}

	print "<script>window.location='index.php?view=medic-categories/index';</script>";
}
