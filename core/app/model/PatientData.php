<?php
class PatientData
{
	public static $tablename = "patients";
	public static $tablenameFiles = "patient_files";
	public static $tablenameCategories = "patient_categories";
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

	public function getAge()
	{
		if ($this->birthday && $this->birthday != 0000 - 00 - 00) {
			//Edad del paciente
			$date2 = date('Y-m-d');
			$diff = abs(strtotime($date2) - strtotime($this->birthday));
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

	public function getAgeByBirthdayDate($birthday)
	{
		if ($birthday && $birthday != 0000 - 00 - 00) {
			//Edad del paciente
			$date2 = date('Y-m-d');
			$diff = abs(strtotime($date2) - strtotime($birthday));
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
		if ($this->birthday && $this->birthday != 0000 - 00 - 00) {
			//Edad del paciente
			$diff = abs(strtotime($date) - strtotime($this->birthday));
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
		if ($this->birthday && $this->birthday != 0000 - 00 - 00) {
			$day = substr($this->birthday, 8, 2);
			$month = substr($this->birthday, 5, 2);
			$year = substr($this->birthday, 0, 4);

			return $day . "/" . self::$months[$month] . "/" . $year;
		} else {
			return "No especificada.";
		}
	}

	public function getDateFormat($date)
	{
		//Obtiene la fecha de nacimiento con el nombre del mes del paciente
		if ($date && $date != 0000 - 00 - 00) {
			$day = substr($date, 8, 2);
			$month = substr($date, 5, 2);
			$year = substr($date, 0, 4);

			return $day . "/" . self::$months[$month] . "/" . $year;
		} else {
			return "No especificada.";
		}
	}

	public function getMedic()
	{
		return MedicData::getById($this->medic_id);
	}

	public function getSex()
	{
		return SexData::getById($this->sex_id);
	}

	public function getEducationLevel()
	{
		return EducationLevelData::getById($this->education_level_id);
	}

	public function getCounty()
	{
		return CountyData::getById($this->county_id);
	}

	public function getLastByPatientId()
	{
		return ReservationData::getLastByPatientId($this->id);
	}

	public function getLastTreatment()
	{
		return TreatmentData::getLastPatientTreatment($this->id);
	}

	public function getCategory()
	{
		return PatientData::getCategoryById($this->category_id);
	}

	public function getCompany()
	{
		return CompanyData::getById($this->company_id);
	}

	public function getBranchOffice()
	{
		return BranchOfficeData::getById($this->branch_office_id);
	}

	public function getTotalReservations()
	{
		//Mostrar las consultas que se han realizado en ese tratamiento status = 2 (Asistió)
		return ReservationData::getTotalReservationsByPatient($this->id, 2);
	}


	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (name,sex_id,curp,street,number,colony,county_id,cellphone,homephone,email,birthday,referred_by,relative_name,category_id,branch_office_id,observations,company_id,education_level_id,occupation,image) ";
		$sql .= "value (\"$this->name\",\"$this->sex_id\",\"$this->curp\",\"$this->street\",\"$this->number\",\"$this->colony\",\"$this->county_id\",\"$this->cellphone\",\"$this->homephone\",\"$this->email\",\"$this->birthday\",\"$this->referred_by\",\"$this->relative_name\",\"$this->category_id\",\"$this->branch_office_id\",\"$this->observations\",\"$this->company_id\",\"$this->education_level_id\",\"$this->occupation\",\"$this->image\")";
		return Executor::doit($sql);
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id = $this->id";
		Executor::doit($sql);
	}

	public function update_active()
	{
		$sql = "update " . self::$tablename . " set last_active_at=NOW() WHERE id=$this->id";
		Executor::doit($sql);
	}

	public static function getAllByPage($start_FROM, $limit)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id>=$start_FROM limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public function update()
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",sex_id=\"$this->sex_id\",curp=\"$this->curp\",relative_name=\"$this->relative_name\",street=\"$this->street\",number=\"$this->number\",colony=\"$this->colony\",county_id=\"$this->county_id\",cellphone=\"$this->cellphone\",homephone=\"$this->homephone\",email=\"$this->email\",birthday=\"$this->birthday\",referred_by=\"$this->referred_by\",relative_name=\"$this->relative_name\",branch_office_id=\"$this->branch_office_id\",observations=\"$this->observations\",company_id=\"$this->company_id\",education_level_id=\"$this->education_level_id\",occupation=\"$this->occupation\",image=\"$this->image\" WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public function updatePatientCategory()
	{
		$sql = "UPDATE " . self::$tablename . " SET category_id = $this->category_id WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function updateByInterview()
	{
		$sql = "UPDATE " . self::$tablename . " SET name=\"$this->name\",relative_name=\"$this->relative_name\",street=\"$this->street\",number=\"$this->number\",colony=\"$this->colony\",county_id=\"$this->county_id\",cellphone=\"$this->cellphone\",homephone=\"$this->homephone\",birthday=\"$this->birthday\",education_level_id=\"$this->education_level_id\",occupation=\"$this->occupation\" WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public function updateNotes()
	{
		$sql = "UPDATE " . self::$tablename . " set notes=\"$this->notes\" WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public function updateAssistant($colonia)
	{
		$sql = "update " . self::$tablename . " set name=\"$this->name\",curp=\"$this->curp\",relative_name=\"$this->relative_name\",street=\"$this->street\",number=\"$this->number\",colony=\"$this->colony\",cellphone=\"$this->cellphone\",homephone=\"$this->homephone\",email=\"$this->email\",birthday=\"$this->birthday\",referred_by=\"$this->referred_by\" WHERE id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT *,DATE_FORMAT(birthday,'%d/%m/%Y') AS birthday_format FROM " . self::$tablename . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}

	public static function getByName($name)
	{
		$sql = "SELECT *,DATE_FORMAT(birthday,'%d/%m/%Y') AS birthday_format FROM " . self::$tablename . " WHERE name = '$name' LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}

	public static function getAll($categoryId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " ";
		if($categoryId == "active"){
			$sql.= " WHERE category_id != 3 ";
		}
		$sql.= " ORDER BY id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAllByCreatedAt($branchOfficeId, $startDate, $endDate, $medicId = 0)
	{
		$startDateTime = $startDate . " 00:00:01";
		$endDateTime = $endDate . " 23:59:59";
		$sql = "SELECT " . self::$tablename . ".*,DATE_FORMAT(" . self::$tablename . ".created_at,'%d/%m/%Y') AS created_at_format 
			FROM " . self::$tablename . " 
			WHERE branch_office_id = '$branchOfficeId'
			AND created_at >= '$startDateTime'
			AND created_at <= '$endDateTime' ";
		if ($medicId != 0) {
			$sql .= " AND (SELECT " . TreatmentData::$tablenamePatientTreatments . ".id FROM " . TreatmentData::$tablenamePatientTreatments . " 
					WHERE  " . TreatmentData::$tablenamePatientTreatments . ".patient_id = " . self::$tablename . " .id
					AND (
						(" . TreatmentData::$tablenamePatientTreatments . ".status_id = 1 AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate')
						OR (" . TreatmentData::$tablenamePatientTreatments . ".status_id != 1 AND 
							(
								('$startDate' BETWEEN CAST(" . TreatmentData::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . TreatmentData::$tablenamePatientTreatments . ".end_date AS DATE))
								OR ('$endDate' BETWEEN CAST(" . TreatmentData::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . TreatmentData::$tablenamePatientTreatments . ".end_date AS DATE))
							)
						)
					) AND " . TreatmentData::$tablenamePatientTreatments . ".medic_id = '$medicId' ";
			$sql .= " ORDER BY " . TreatmentData::$tablenamePatientTreatments . ".id DESC LIMIT 1
					) IS NOT NULL "; //Sólo pacientes con tratamiento de cierto psicólogo en las fechas seleccionados
		}
		$sql .= " ORDER BY name ASC ";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAllByBranchOffice($branchOfficeId,$categoryId = "all")
	{
		$sql = "SELECT * FROM " . self::$tablename . " 
		WHERE branch_office_id = '$branchOfficeId' ";
		if($categoryId == "active"){
			$sql.= " AND category_id != 3 ";
		}
		$sql.= " ORDER BY id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getPatientCategories()
	{
		$sql = "SELECT * FROM  patient_categories";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function get_registro_patientse($name)
	{
		$sql = "SELECT * FROM `patients` WHERE name='$name'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getValidatePatientCategory($patient_id, $category_id)
	{
		//Valida si un paciente está en cierta clasificación colocada por la clínica.
		//Por ejemplo buscar si paciente está inactivo para no darle consulta.
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id=\"$patient_id\" and category_id = \"$category_id\"";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public static function getAllActive()
	{
		$sql = "SELECT * FROM client WHERE last_active_at>=date_sub(NOW(),interval 3 second)";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getAllUnActive()
	{
		$sql = "SELECT * FROM client WHERE last_active_at<=date_sub(NOW(),interval 3 second)";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getLike($q)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE title like '%$q%' or email like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getEmailsByPatientBirthdayMonth($date)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE DATE_FORMAT(birthday,'%m-%d') = '$date' AND email != ''";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getByUserId($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " 
		WHERE user_id = '$id' LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new MedicData());
	}

	/*-----------------FILES-------------------- */
	public function addFile()
	{
		$sql = "INSERT INTO " . self::$tablenameFiles . " (patient_id,reservation_id,path) ";
		$sql .= "VALUE (\"$this->patient_id\",\"$this->reservation_id\",\"$this->path\")";
		return Executor::doit($sql);
	}

	public function deleteFile()
	{
		$sql = "DELETE FROM " . self::$tablenameFiles . " WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function getFileById($id)
	{
		$sql = "SELECT * FROM " . self::$tablenameFiles . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}

	public static function getAllFilesByPatientReservation($patientId, $reservationId)
	{
		$sql = "SELECT * FROM " . self::$tablenameFiles . " WHERE patient_id = '$patientId' AND reservation_id = '$reservationId'
		ORDER BY created_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	//-------------CATEGORÍAS----------------
	public static function getAllCategories()
	{
		$sql = "SELECT * FROM " . self::$tablenameCategories . " ";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientData());
	}

	public static function getCategoryById($id)
	{
		$sql = "SELECT * FROM " . self::$tablenameCategories . " WHERE id ='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientData());
	}

	public static function getAllExport()
{
    $sql = "SELECT 
                patients.id AS patient_id,
                patients.name AS patient_name,
                patients.street,
                patients.number,
                patients.colony,
                counties.name AS county_name,
                patients.cellphone,
                patients.homephone,
                patients.email,
                patients.relative_name,
                medics.name AS medic_name,
                companies.name AS company_name,
                patient_categories.name AS patient_category_name
            FROM patients
            LEFT JOIN counties ON patients.county_id = counties.id
            LEFT JOIN patient_categories ON patients.category_id = patient_categories.id
            LEFT JOIN companies ON patients.company_id = companies.id
            LEFT JOIN (
                SELECT patient_treatments.patient_id, patient_treatments.medic_id
                FROM patient_treatments
                INNER JOIN (
                    SELECT patient_id, MAX(start_date) AS max_date
                    FROM patient_treatments
                    GROUP BY patient_id
                ) latest ON patient_treatments.patient_id = latest.patient_id 
                    AND patient_treatments.start_date = latest.max_date
            ) latest_treatment ON patients.id = latest_treatment.patient_id
            LEFT JOIN medics ON latest_treatment.medic_id = medics.id
            ORDER BY patients.id DESC";

    $query = Executor::doit($sql);
    return Model::many($query[0], new PatientData());
}

}
