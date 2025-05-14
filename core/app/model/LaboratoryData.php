<?php
class LaboratoryData {
	public static $tablename = "laboratories";
	public function __construct(){
		$this->id = "";
		$this->name = "";
		$this->created_at = "NOW()";
	}

	
	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename." ORDER BY name DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new LaboratoryData());
	}

}

?>