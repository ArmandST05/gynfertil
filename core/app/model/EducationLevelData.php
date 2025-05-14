<?php
class EducationLevelData {
	public static $tablename = "education_levels";
	public function __construct(){
		$this->name = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "INSERT into ".self::$tablename." (name) ";
		$sql .= "value ($this->name)";
		return Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." set name=\"$this->name\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new EducationLevelData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename." ORDER BY ordering";
		$query = Executor::doit($sql);
		return Model::many($query[0],new EducationLevelData());
	}

	public function delete(){
		$sql = "DELETE FROM ".self::$tablename." WHERE id=$this->id";
		return Executor::doit($sql);
	}
}

?>