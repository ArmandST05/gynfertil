<?php
class UserData {
	public static $tablename = "user";

	public function __construct(){
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (name,username,password,created_at,tipo_usuario) ";
		$sql .= "value (\"$this->name\",\"$this->username\",\"$this->password\",$this->created_at,\"$this->tipo_usuario\")";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",username=\"$this->username\",tipo_usuario=\"$this->tipo_usuario\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_passwd(){
		$sql = "update ".self::$tablename." set password=\"$this->password\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());

	}

	public static function getByMail($mail){
		$sql = "select * from ".self::$tablename." where email=\"$mail\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getLoggedIn(){
		//Obtener datos del usuario logueado en el sistema
		$id = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
		$sql = "SELECT * FROM ".self::$tablename." where id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function get_tipo(){
		$sql = "select * from tipo_usuario order by id ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function get_tipo_usuario($id){
		$sql = "select * FROM user WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getUserType($id){
		$sql = "SELECT * FROM user WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function get_tipo_usuario1($id){
		$sql = "select tipo_usuario,m.id id_me from user u, medic m WHERE u.id='$id' AND m.id_usuario=u.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());

	}


}
