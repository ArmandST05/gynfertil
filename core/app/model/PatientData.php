<?php
class PatientData
{
	public static $tablename = "pacient";
	public static $tablenameSexes = "sexes";
	public static $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

	public function __construct()
	{
		$this->title = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->is_public = "0";
		$this->created_at = "NOW()";
	}
	public function getMedic()
	{
		return MedicData::getById($this->medic_id);
	}

	public function getSex()
	{
		return PatientData::getSexById($this->sex_id);
	}

	public function getType()
	{
		return MedicTypeData::getById($this->type_id);
	}

	public function getLastPapanicolaouTestDate()
	{
		$papanicolaouTest =  ReservationData::getLastPapsTestByPatient($this->id);
		if ($papanicolaouTest) {
			return $papanicolaouTest->date_format;
		} else {
			return 'No se ha realizado.';
		}
	}

	public function getCate()
	{
		return MedicData::getById($this->medic_id);
	}

	public function getAge()
	{
		if ($this->fecha_na && $this->fecha_na != "0000-00-00") {
			//Edad del paciente
			$date = date('Y-m-d');
			$diff = abs(strtotime($date) - strtotime($this->fecha_na));
			$years = floor($diff / (365 * 60 * 60 * 24));
			if ($years == 1) {
				return $years = $years . " Año";
			} else {
				return $years = $years . " Años";
			}
		} else {
			return "No especificada.";
		}
	}

	public function getRelativeAge()
	{
		if ($this->relative_birthday && $this->relative_birthday != "0000-00-00") {
			//Edad del paciente
			$date = date('Y-m-d');
			$diff = abs(strtotime($date) - strtotime($this->relative_birthday));
			$years = floor($diff / (365 * 60 * 60 * 24));
			if ($years == 1) {
				return $years = $years . " Año";
			} else {
				return $years = $years . " Años";
			}
		} else {
			return "No especificada.";
		}
	}

	public function getAgeByDate($date)
	{
		//Calcula la edad del paciente en una fecha determinada
		if ($this->fecha_na && $this->fecha_na != "0000-00-00") {
			//Edad del paciente
			$diff = abs(strtotime($date) - strtotime($this->fecha_na));
			$years = floor($diff / (365 * 60 * 60 * 24));
			if ($years == 1) {
				return $years = $years . " Año";
			} else {
				return $years = $years . " Años";
			}
		} else {
			return "No especificada.";
		}
	}

	public function getRelativeAgeByDate($date)
	{
		//Calcula la edad de la pareja del paciente en una fecha determinada
		if ($this->relative_birthday && $this->relative_birthday != "0000-00-00") {
			//Edad del paciente
			$diff = abs(strtotime($date) - strtotime($this->relative_birthday));
			$years = floor($diff / (365 * 60 * 60 * 24));
			if ($years == 1) {
				return $years = $years . " Año";
			} else {
				return $years = $years . " Años";
			}
		} else {
			return "No especificada.";
		}
	}

	public function getBirthdayFormat()
	{
		//Obtiene la fecha de nacimiento con el nombre del mes del paciente
		if ($this->fecha_na && $this->fecha_na != "0000-00-00") {
			$day = substr($this->fecha_na, 8, 2);
			$month = substr($this->fecha_na, 5, 2);
			$year = substr($this->fecha_na, 0, 4);

			return $day . "/" . self::$months[$month] . "/" . $year;
		} else {
			return "No especificada.";
		}
	}

	public function getRelativeBirthdayFormat()
	{
		//Obtiene la fecha de nacimiento con el nombre del mes de la pareja del paciente
		if ($this->relative_birthday && $this->relative_birthday != "0000-00-00") {
			$day = substr($this->relative_birthday, 8, 2);
			$month = substr($this->relative_birthday, 5, 2);
			$year = substr($this->relative_birthday, 0, 4);

			return $day . "/" . self::$months[$month] . "/" . $year;
		} else {
			return "No especificada.";
		}
	}

	public function getPatientOfficialData()
	{
		//Se muestra el dato oficial del paciente.
		$officialDocument = new stdClass();
		$officialDocumentData = OfficialDocumentData::getById($this->official_document_id);
		if ($officialDocumentData) {
			$officialDocument->id = $officialDocumentData->id;
			$officialDocument->name = $officialDocumentData->name;
			$officialDocument->value = $this->official_document_value;
		} else {
			$officialDocument->id = "";
			$officialDocument->name = "Dato oficial";
			$officialDocument->value = "No asignado";
		}

		return $officialDocument;
	}

	public function getRelativeOfficialData()
	{

		//Se muestra el dato oficial de la pareja del paciente cuando no tiene un registro de paciente independiente.
		$officialDocument = new stdClass();
		$officialDocumentData = OfficialDocumentData::getById($this->relative_official_document_id);
		if ($officialDocumentData) {
			$officialDocument->id = $officialDocumentData->id;
			$officialDocument->name = $officialDocumentData->name;
			$officialDocument->value = $this->relative_official_document_value;
		} else {
			$officialDocument->id = "";
			$officialDocument->name = "Dato oficial";
			$officialDocument->value = "No asignado";
		}

		return $officialDocument;
	}

	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (name,sex_id,relative_id,relative_name,tel,created_at,status,image) ";
		$sql .= "value (\"$this->name\",\"$this->sex_id\",\"$this->relative_id\",\"$this->relative_name\",\"$this->tel\",$this->created_at,'2',\"$this->image\")";
		return Executor::doit($sql);
	}

	public static function delById($id)
	{
		$sql = "delete from " . self::$tablename . " where id='$id'";
		Executor::doit($sql);
	}
	public function del()
	{
		$sql = "delete from " . self::$tablename . " where id=$this->id";
		Executor::doit($sql);
	}
	// partiendo de que ya tenemos creado un objecto PatientData previamente utilizamos el contexto
	public function update_active()
	{
		$sql = "update " . self::$tablename . " set last_active_at=NOW() where id=$this->id";
		Executor::doit($sql);
	}

	public static function getAllByPage($start_from, $limit)
	{
		$sql = "select * from " . self::$tablename . " where id>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public function update()
	{
		$sql = "UPDATE " . self::$tablename . " set name=\"$this->name\",sex_id=\"$this->sex_id\",relative_id=\"$this->relative_id\",relative_name=\"$this->relative_name\",calle=\"$this->calle\",num=\"$this->num\",num=\"$this->num\",col=\"$this->col\",tel=\"$this->tel\",tel2=\"$this->tel2\",email=\"$this->email\",fecha_na=\"$this->fecha_na\",relative_birthday=\"$this->relative_birthday\",ref=\"$this->ref\",status=\"$this->estatus\",official_document_id=\"$this->official_document_id\",official_document_value=\"$this->official_document_value\",relative_official_document_id=\"$this->relative_official_document_id\",relative_official_document_value=\"$this->relative_official_document_value\",image=\"$this->image\" where id=$this->id";
		return Executor::doit($sql);
	}

	public function update2()
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",sex_id=\"$this->sex_id\",relative_name=\"$this->relative_name\",calle=\"$this->calle\",num=\"$this->num\",num=\"$this->num\",col=\"$this->col\",tel=\"$this->tel\",tel2=\"$this->tel2\",email=\"$this->email\",fecha_na=\"$this->fecha_na\",ref=\"$this->ref\",edad=\"$this->edad\",
		relative_id=\"$this->relative_id\",relative_name=\"$this->relative_name\",relative_birthday=\"$this->relative_birthday\",official_document_id=\"$this->official_document_id\",official_document_value=\"$this->official_document_value\",relative_official_document_id=\"$this->relative_official_document_id\",relative_official_document_value=\"$this->relative_official_document_value\"
		WHERE id=$this->id";
		return Executor::doit($sql);
	}

	//Actualiza la pareja de un paciente
	public function updateRelative()
	{
		$sql = "UPDATE " . self::$tablename . " SET relative_id=\"$this->relative_id\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Elimina la pareja de los pacientes que la tengan asignada, para que no existan duplicados.
	public static function deleteRelative($relativeId)
	{
		$sql = "UPDATE " . self::$tablename . " SET relative_id = 0 WHERE relative_id = '$relativeId'";
		return Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT " . self::$tablename . ".*,
		DATE_FORMAT(" . self::$tablename . ".fecha_na,'%d/%m/%Y') as birthday_format,
		DATE_FORMAT(" . self::$tablename . ".relative_birthday,'%d/%m/%Y') as relative_birthday_format 
		FROM " . self::$tablename . " where id = $id AND doctor='0'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}

	public static function getById1($id)
	{
		$sql = "select * from " . self::$tablename . " where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}

	public static function getAllByName($nom)
	{
		$sql = "select * from " . self::$tablename . " WHERE name like '%$nom%' AND doctor='0' order by created_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAll_tipo($tipo)
	{
		$sql = "select * from " . self::$tablename . " WHERE tipo_usuario='$tipo'  ORDER BY DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function estatus_paciente()
	{
		$sql = "select * from status_pacient WHERE id>0 order by id ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	
	public static function getPatientStatusById($id)
	{
		$sql = "SELECT * FROM status_pacient WHERE id = '$id' ";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}


	public static function getAll()
	{
		//Obtener todos los pacientes reales doctor= 0
		$sql = "select * from " . self::$tablename . " WHERE doctor='0' order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAllMedics()
	{
		$sql = "select * from " . self::$tablename . "  WHERE doctor='1'  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAll_doc_A($id)
	{
		$sql = "SELECT doctor from " . self::$tablename . " WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}

	public static function get_estatus()
	{
		$sql = "select * from  tipos_status";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function get_registro_paciente($name)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE name='$name'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAll_todo($id)
	{
		$sql = "select " . self::$tablename . ".*,
		DATE_FORMAT(" . self::$tablename . ".fecha_na,'%d/%m/%Y') as birthday_format,
		DATE_FORMAT(" . self::$tablename . ".relative_birthday,'%d/%m/%Y') as relative_birthday_format 
		FROM " . self::$tablename . " WHERE id='$id' AND doctor='0'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAllActive()
	{
		$sql = "select * from client where last_active_at>=date_sub(NOW(),interval 3 second)";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAllUnActive()
	{
		$sql = "select * from client where last_active_at<=date_sub(NOW(),interval 3 second)";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getLike($q)
	{
		$sql = "select * from " . self::$tablename . " where title like '%$q%' or email like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	/*----------------DONANTES-----------------*/

	public static function getTotalDonantsBySex($sexId)
	{
		$sql = "SELECT COUNT(id) AS total 
		FROM " . self::$tablename . " 
		WHERE donor_id != '' AND sex_id = '$sexId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}

	//Establecer paciente MUJER como donante de óvulos
	public function updatePatientAsDonant()
	{
		$totalDonants = self::getTotalDonantsBySex(1)->total;
		$donantId = floatval($totalDonants) + 1;
		$donantId = str_pad($donantId, 3, "0", STR_PAD_LEFT);
		$sql = "UPDATE " . self::$tablename . " set donor_id = 'OVODON$donantId'  WHERE id = $this->id";
		Executor::doit($sql);
	}

	//Establecer paciente HOMBRE como donante de semen
	public function updateMalePatientAsDonant()
	{
		$totalDonants = self::getTotalDonantsBySex(2)->total;
		$donantId = floatval($totalDonants) + 1;
		$donantId = str_pad($donantId, 3, "0", STR_PAD_LEFT);
		$sql = "UPDATE " . self::$tablename . " set donor_id = 'SPERMDON$donantId'  WHERE id = $this->id";
		Executor::doit($sql);
	}
	/*--------------SEXOS---------------- */
	public static function getAllSexes()
	{
		$sql = "SELECT * FROM " . self::$tablenameSexes . "";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}
	public static function getSexById($id)
	{
		$sql = "SELECT * FROM " . self::$tablenameSexes . " WHERE id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}
}
