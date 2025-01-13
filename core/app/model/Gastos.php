<?php
class CategorySpend {
	public static $tablename = "category_spend";

	public function __construct(){
		$this->title = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->is_public = "0";
		$this->created_at = "NOW()";
	}

	
	public function add(){
		$sql = "insert into ".self::$tablename." (category_id,name,created_at,id_usuario) ";
		$sql .= "value ($this->category_id,\"$this->name\",$this->created_at,\"$this->id_usuario\")";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}


	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",category_id=$this->category_id,id_usuario=\"$this->id_usuario\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategorySpend());
	}

		public static function getAllCatSpend(){
		$sql = "select * from ".self::$tablename."";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategorySpend());
	}

	
}

?>