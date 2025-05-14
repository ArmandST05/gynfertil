<?php
class UserData {
	public static $tablename = "users";

	public function __construct(){
		$this->name = "";
		$this->is_active = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}

	public function getUserType(){ 
		return UserTypeData::getByName($this->user_type); 
	}

	public function getBranchOffice(){ 
		return BranchOfficeData::getById($this->branch_office_id); 
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (name,username,password,created_at,user_type,branch_office_id) ";
		$sql .= "value (\"$this->name\",\"$this->username\",\"$this->password\",$this->created_at,\"$this->user_type\",\"$this->branch_office_id\")";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",username=\"$this->username\",user_type=\"$this->user_type\",branch_office_id=\"$this->branch_office_id\",is_active=\"$this->is_active\" WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function updatePassword(){
		$sql = "update ".self::$tablename." set password=\"$this->password\" where id = $this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getLoggedIn(){
		//Obtener datos del usuario logueado en el sistema
		$id = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getByMail($mail){
		$sql = "select * from ".self::$tablename." WHERE user_type != 'api' AND email = \"$mail\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function validateApi($username,$authenticationToken){
		$sql = "SELECT * FROM ".self::$tablename." 
		WHERE username = '$username' AND authentication_token = '$authenticationToken'
		LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new UserData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename ." WHERE user_type != 'api' order by is_active,name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getUnassigned(){
		//Obtiene los usuarios que no se han asignado a ningún médico.
		$sql = "select ".self::$tablename .".* from ".self::$tablename ." 
		left join medics on ".self::$tablename .".id = medics.user_id 
		AND medics.is_active = 1
		WHERE user_type != 'api' AND medics.id is null 
		AND  ".self::$tablename .".is_active = 1
		order by ".self::$tablename .".name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." WHERE user_type != 'api' AND name like '%$q%'
		AND  ".self::$tablename .".is_active = 1 ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new UserData());
	}

	public static function deleteById($id){
		$sql = "delete from ".self::$tablename." WHERE id=$id";
		Executor::doit($sql);
	}
	public function changeStatusById(){
		$sql = "update ".self::$tablename." set is_active=\"$this->status_id\" where id=$this->id";
		Executor::doit($sql);
	}
}
?>