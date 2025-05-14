<?php
class MedicData {
	public static $tablename = "medics";

	public $category_id;
	public $branch_office_id;
	public $user_id;
	public $name;
	public $email;
	public $phone;
	public $address;
	public $education_level_id;
	public $professional_license;
	public $study_center;
	public $calendar_color;
	public $id;
	public $is_digital_signature;
	public $digital_signature_path;
	public $fiel_key_path;
	public $fiel_certificate_path;
	public $fiel_key_password;
	public $created_at;
	public $is_fiel_key;
	public $is_active;

	public function __construct(){
		$this->created_at = "NOW()";
	}

	//public function getUnreads(){ return MessageData::getUnreadsByClientId($this->id); }

	public function getCategory(){ 
		return CategoryMedicData::getById($this->category_id); 
	}

	public function getBranchOffice(){ 
		return BranchOfficeData::getById($this->branch_office_id); 
	}

	public function getUser(){ 
		return UserData::getById($this->user_id); 
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (category_id,branch_office_id,name,email,phone,address,education_level_id,professional_license,study_center,user_id,calendar_color) ";
		$sql .= "value ($this->category_id,$this->branch_office_id,\"$this->name\",\"$this->email\",\"$this->phone\",\"$this->address\",\"$this->education_level_id\",\"$this->professional_license\",\"$this->study_center\",\"$this->user_id\",\"$this->calendar_color\")";
		return Executor::doit($sql);
	}

	public function update_active(){
		$sql = "update ".self::$tablename." set last_active_at=NOW() WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." set name=\"$this->name\",education_level_id=\"$this->education_level_id\",professional_license=\"$this->professional_license\",study_center=\"$this->study_center\",email=\"$this->email\",phone=\"$this->phone\",address=\"$this->address\",category_id=$this->category_id,branch_office_id=$this->branch_office_id,user_id=\"$this->user_id\",calendar_color=\"$this->calendar_color\",is_active=\"$this->is_active\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updateProfile(){
		//Utilizado para que el médico edite sus datos
		$sql = "UPDATE ".self::$tablename." SET professional_license=\"$this->professional_license\",study_center=\"$this->study_center\",email=\"$this->email\",phone=\"$this->phone\",is_digital_signature=\"$this->is_digital_signature\",is_fiel_key=\"$this->is_fiel_key\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updateDigitalSignature(){
		//Utilizado para actualizar la firma digital
		$sql = "UPDATE ".self::$tablename." SET digital_signature_path=\"$this->digital_signature_path\" WHERE id = $this->id";
		Executor::doit($sql);
	}
	
	public function updateFielKey(){
		//Utilizado para actualizar la clave fiel (SAT)
		$sql = "UPDATE ".self::$tablename." SET fiel_key_path=\"$this->fiel_key_path\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public function updateFielKeyPassword(){
		//Utilizado para actualizar la contraseña de la clave fiel (SAT)
		$sql = "UPDATE ".self::$tablename." SET fiel_key_password=\"$this->fiel_key_password\" WHERE id = $this->id";
		Executor::doit($sql);
	}
	
	public function updateFielCertificate(){
		//Utilizado para actualizar el certificado de fiel (SAT)
		$sql = "UPDATE ".self::$tablename." SET fiel_certificate_path=\"$this->fiel_certificate_path\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new MedicData());
	}

	public static function getByUserId($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE user_id = '$id' LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new MedicData());
	}

    public static function getAll_pa(){
		$nom = "";
		$sql = "SELECT * FROM ".self::$tablename." WHERE name like '%$nom%' order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PatientData());
	}

	public static function getAll_med($id){
	    $sql = "SELECT * FROM medic WHERE id='$id'";
	    $query = Executor::doit($sql);
	    return Model::many($query[0],new PatientData());
	}

	public static function getAll_med_user($id){
	    $sql = "SELECT * FROM ".self::$tablename." WHERE id_usuario='$id'";
	    $query = Executor::doit($sql);
	    return Model::many($query[0],new PatientData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename." WHERE is_active = 1 ORDER BY name";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getAllByBranchOffice($branchOfficeId){
		$sql = "SELECT * FROM ".self::$tablename." WHERE branch_office_id = '$branchOfficeId' AND is_active= 1 ORDER BY name";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getAllByStatus($statusId){
		$sql = "SELECT * FROM ".self::$tablename." WHERE is_active = '$statusId' ORDER BY name";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getAllActive(){
		$sql = "SELECT * FROM client WHERE last_active_at>=date_sub(NOW(),interval 3 second)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getAllUnActive(){
		$sql = "SELECT * FROM client WHERE last_active_at<=date_sub(NOW(),interval 3 second)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getLike($q){
		$sql = "SELECT * FROM ".self::$tablename." WHERE title like '%$q%' or email like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function delById($id){
		$sql = "delete FROM ".self::$tablename." WHERE id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete FROM ".self::$tablename." WHERE id=$this->id";
		Executor::doit($sql);
	}
}

?>