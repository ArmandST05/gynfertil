<?php
class MedicData {
	public static $tablename = "medic";

	public function __construct(){
		$this->title = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->is_public = "0";
		$this->created_at = "NOW()";
	}

	public function getCategory(){ return CategoryMedicData::getById($this->category_id); }

	public function getType(){ return MedicTypeData::getById($this->type_id); }

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (category_id,type_id,name,created_at,id_usuario,is_digital_signature,specialty_title) ";
		$sql .= "value ($this->category_id,$this->type_id,\"$this->name\",$this->created_at,\"$this->id_usuario\",$this->is_digital_signature,\"$this->specialty_title\")";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	public function update_active(){
		$sql = "UPDATE ".self::$tablename." set last_active_at=NOW() where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." SET name=\"$this->name\",category_id=$this->category_id,type_id=$this->type_id,id_usuario=\"$this->id_usuario\",is_digital_signature=\"$this->is_digital_signature\",specialty_title=\"$this->specialty_title\" WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." where id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new MedicData());
	}

	public static function getByUserId($id){
		$sql = "SELECT * FROM ".self::$tablename." where id_usuario = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new MedicData());
	}
	
    public static function getAll_pa(){
		$sql = "select * from ".self::$tablename." WHERE name like '%$nom%' order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PatientData());
	}

	public static function getAll_med($id){
	    $sql = "select * from medic WHERE id='$id'";
	    $query = Executor::doit($sql);
	    return Model::many($query[0],new PatientData());
	}

	public static function getAll_med_user($id){
	    $sql = "select * from medic WHERE id_usuario='$id'";
	    $query = Executor::doit($sql);
	    return Model::many($query[0],new PatientData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getlabarotario(){
		$sql = "select * from laboratorios order by id ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getAll_asistente(){
		$sql = "select * from user WHERE tipo_usuario='a' order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getAll_doctor($id_user){
		$sql = "select * from ".self::$tablename." WHERE id_usuario='$id_user' order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getAllActive(){
		$sql = "select * from client where last_active_at>=date_sub(NOW(),interval 3 second)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getAllUnActive(){
		$sql = "select * from client where last_active_at<=date_sub(NOW(),interval 3 second)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public function getUnreads(){ return MessageData::getUnreadsByClientId($this->id); }

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where title like '%$q%' or email like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public function updateDigitalSignature(){
		//Utilizado para actualizar la firma digital
		$sql = "UPDATE ".self::$tablename." SET digital_signature_path=\"$this->digital_signature_path\" WHERE id = $this->id";
		return Executor::doit($sql);
	}
}

?>