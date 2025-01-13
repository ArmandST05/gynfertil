<?php
class OfficialDocumentData {
	public static $tablename = "official_documents";

	public function __construct(){
		$this->name = "";
	}
	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new OfficialDocumentData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OfficialDocumentData());
	}
}

?>