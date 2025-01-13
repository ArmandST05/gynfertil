<?php
class MedicTypeData {
	public static $tablename = "medic_types";

	public function __construct(){
		$this->name = "";
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (name) ";
		$sql .= "value (\"$this->name\")";
		return Executor::doit($sql);
	}

	public function delete(){
		$sql = "DELETE from ".self::$tablename." WHERE id=$this->id";
		Executor::doit($sql);
	}

	public static function deleteById($id){
		$sql = "DELETE from ".self::$tablename." WHERE id=$id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." SET name=\"$this->name\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new MedicTypeData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicTypeData());
	}
}
?>