<?php
class UserTypeData {
	public static $tablename = "user_types";

	public function __construct(){
		$this->name = "";
		$this->created_at = "NOW()";
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserTypeData());
	}

	public static function getByName($name){
		$sql = "SELECT * FROM ".self::$tablename." WHERE name = $name";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserTypeData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename ." 
		WHERE is_active = '1' 
		AND is_selectable = '1' ORDER by description";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserTypeData());
	}
}
?>