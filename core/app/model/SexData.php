<?php
class SexData {
	public static $tablename = "sexes";

	public function __construct(){
		$this->name = "";
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SexData());
	}
}

?>