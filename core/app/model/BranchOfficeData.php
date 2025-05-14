<?php
class BranchOfficeData {
	public static $tablename = "branch_offices";

	public function __construct(){
		$this->name = "";
		$this->is_active = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (name) ";
		$sql .= "VALUE (\"$this->name\")";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." SET name=\"$this->name\" WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function updateStatus(){
		$sql = "UPDATE ".self::$tablename." SET is_active=\"$this->is_active\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * from ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new BranchOfficeData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename ." ORDER BY name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BranchOfficeData());
	}

	public static function getAllByStatus($status){
		$sql = "SELECT * FROM ".self::$tablename ." WHERE is_active = '$status' ORDER BY name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BranchOfficeData());
	}

	public static function deleteById($id){
		$sql = "DELETE from ".self::$tablename." WHERE id=$id";
		Executor::doit($sql);
	}
}
?>