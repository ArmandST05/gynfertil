<?php
class LogActionTypeData {
	public static $tablename = "log_action_types";

	public function __construct(){
		$this->name = "";
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new LogModuleData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename ." ORDER BY id ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new LogModuleData());
	}
}
?>