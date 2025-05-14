<?php
class TreatmentData
{
	public static $tablename = "treatments";
	public static $tablenamePatientTreatments = "patient_treatments";
	public static $tablenameStatus = "treatment_status";

	public static $tablenamePatientInterviewDetails = "patient_interview_details";
	public static $tablenameInterviewDetails = "interview_details";

	public function __construct()
	{
		$this->name = "";
	}
	public function getPatient()
	{
		return PatientData::getById($this->patient_id);
	}

	public function getMedic()
	{
		return MedicData::getById($this->medic_id);
	}

	public function getTreatment()
	{
		return TreatmentData::getById($this->treatment_id);
	}

	public function getTotalReservations()
	{
		//Mostrar las consultas que se han realizado en ese tratamiento status = 2 (Asistió)
		$startDateTime = $this->start_date . " 00:00:00";
		if($this->end_date && $this->end_date != "0000-00-00"){//Si el tratamiento ya se acabó obtener hasta la fecha de fin del tratamiento
			$endDateTime = $this->end_date . " 23:59:59";
		}else{//Si el tratamiento no ha terminado, obtener hasta la fecha actual
			$endDateTime = date("Y-m-d") . " 23:59:59";
		}
		return ReservationData::getTotalReservationsByPatientDates($this->patient_id, $startDateTime, $endDateTime, 2);
	}

	public function getTreatmentDuration()
	{
		if($this->start_date == "0000-00-00" || $this->end_date == "0000-00-00"){
			$treatmentDuration = "Revisa las fechas del tratamiento";
		}else{
			$startDateTime = new DateTime($this->start_date);
			if(isset($this->end_date) && $this->end_date != "0000-00-00"){
				$endDateTime = new DateTime($this->end_date);
			}else{
				$endDateTime = new DateTime("now");
			}
			$interval = $startDateTime->diff($endDateTime);
			$treatmentDuration =  floor(($interval->format('%a') / 7)) . ' semanas con '
				. ($interval->format('%a') % 7) . ' días';
		}
		return $treatmentDuration;
	}


	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new TreatmentData());
	}

	public static function getAll()
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE is_active = 1 ORDER BY name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new TreatmentData());
	}

	//Obtiene la categoría/tratamiento actual del paciente
	public static function getPatientActualTreatment($patientId)
	{
		$sql = "SELECT " . self::$tablenamePatientTreatments . ".*,
			" . self::$tablename . ".code as treatment_code,
			" . self::$tablename . ".name as treatment_name
			FROM " . self::$tablenamePatientTreatments . "
			INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenamePatientTreatments . ".treatment_id
			WHERE patient_id = '$patientId' 
			AND status_id = 1
			ORDER BY id DESC LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new TreatmentData());
	}

	public static function getAllPatientTreatments($patientId, $limit = 0)
	{
		//Obtiene el histórico de todas las categorías/tratamientos del cliente,se puede obtener sólo cierta cantidad definiendo el límite
		$sql = "SELECT " . self::$tablenamePatientTreatments . ".*,
			" . self::$tablename . ".name as treatment_name,
			" . self::$tablename . ".code as treatment_code,
			" . self::$tablenameStatus . ".name AS status_name, 
			DATE_FORMAT(" . self::$tablenamePatientTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			DATE_FORMAT(" . self::$tablenamePatientTreatments . ".end_date,'%d/%m/%Y') as end_date_format
			FROM " . self::$tablenamePatientTreatments . "
			INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenamePatientTreatments . ".treatment_id
			INNER JOIN " . self::$tablenameStatus . " ON " . self::$tablenameStatus . ".id = " . self::$tablenamePatientTreatments . ".status_id
			WHERE patient_id = '$patientId' 
			ORDER BY start_date DESC ";
		if ($limit != 0) {
			$sql .= " LIMIT $limit";
		}
		$query = Executor::doit($sql);
		return Model::many($query[0], new TreatmentData());
	}

	public static function getTotalPatientTreatments($patientId)
	{
		//Obtiene el histórico de todas las categorías/tratamientos del cliente,se puede obtener sólo cierta cantidad definiendo el límite
		$sql = "SELECT COUNT(" . self::$tablenamePatientTreatments . ".id) AS total
			FROM " . self::$tablenamePatientTreatments . "
			WHERE patient_id = '$patientId' ";
		$query = Executor::doit($sql);
		return Model::one($query[0], new TreatmentData());
	}

	public static function getPatientTreatmentById($patientTreatmentId)
	{
		$sql = "SELECT " . self::$tablenamePatientTreatments . ".*,
			" . self::$tablename . ".name as treatment_name," . self::$tablename . ".code AS treatment_code,
			DATE_FORMAT(" . self::$tablenamePatientTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			DATE_FORMAT(" . self::$tablenamePatientTreatments . ".end_date,'%d/%m/%Y') as end_date_format
			FROM " . self::$tablenamePatientTreatments . " 
			INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenamePatientTreatments . ".treatment_id
			WHERE " . self::$tablenamePatientTreatments . ".id = $patientTreatmentId";
		$query = Executor::doit($sql);
		return Model::one($query[0], new TreatmentData());
	}
	
	//Obtener el tratamiento activo de un paciente en un rango de fechas
	public static function getPatientTreatmentByDates($patientId, $startDate, $endDate)
	{
		$sql = "SELECT " . self::$tablenamePatientTreatments . ".*,
			" . self::$tablename . ".name as treatment_name," . self::$tablename . ".code AS treatment_code,
			DATE_FORMAT(" . self::$tablenamePatientTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			DATE_FORMAT(" . self::$tablenamePatientTreatments . ".end_date,'%d/%m/%Y') as end_date_format
			FROM " . self::$tablenamePatientTreatments . " 
			LEFT JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenamePatientTreatments . ".treatment_id
			WHERE  " . self::$tablenamePatientTreatments . ".patient_id = '$patientId'
			AND (
					(" . self::$tablenamePatientTreatments . ".status_id = 1 AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate')
					OR ( 
						" . self::$tablenamePatientTreatments . ".status_id != 1 AND 
						(
							('$startDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
							OR ('$endDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
						)
					)
			) ORDER BY " . self::$tablenamePatientTreatments . ".start_date LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new TreatmentData());
	}

	//Obtener los tratamientos por sucursal dependiendo de su estatus.
	public static function getAllByBranchOfficeStatusDates($branchOfficeId, $startDate, $endDate, $medicId, $statusId)
	{
		$sql = "SELECT pt.*,
			" . self::$tablename . ".name as treatment_name," . self::$tablename . ".code AS treatment_code,
			DATE_FORMAT(pt.start_date,'%d/%m/%Y') as start_date_format,
			DATE_FORMAT(pt.end_date,'%d/%m/%Y') as end_date_format
			FROM " . self::$tablenamePatientTreatments . " pt
			LEFT JOIN " . self::$tablename . " ON " . self::$tablename . ".id = pt.treatment_id
			INNER JOIN " . PatientData::$tablename . " p ON p.id = pt.patient_id
			WHERE p.branch_office_id = '$branchOfficeId' AND pt.status_id = '$statusId' ";
		if ($medicId != 0) {
			$sql .= " AND medic_id = '$medicId' ";
		}
		if ($statusId == 1) { //Activos
			$sql .= " AND pt.start_date <= '$endDate' ";
		} else { //Finalizados-Cancelados
			$sql .= " AND pt.end_date >= '$startDate' AND pt.end_date <= '$endDate' ";
		}
		$sql .= " ORDER BY pt.start_date DESC ";

		$query = Executor::doit($sql);
		return Model::many($query[0], new TreatmentData());
	}

	//Colocar el estatus como finalizado en todas las categorías/tratamientos anteriores del paciente.
	public static function finishAllTreatmentsByPatient($patientId)
	{
		$sql = "update " . self::$tablenamePatientTreatments . " set status_id='4',end_date='" . date('Y-m-d') . "'
			WHERE patient_id = $patientId AND status_id = 1";
		return Executor::doit($sql);
	}

	public function addTreatmentByPatient()
	{
		$sql = "INSERT INTO " . self::$tablenamePatientTreatments . " (treatment_id,patient_id,medic_id,start_date,default_price,reason)";
		$sql .= " value (\"$this->treatment_id\",\"$this->patient_id\",\"$this->medic_id\",\"$this->start_date\",\"$this->default_price\",\"$this->reason\")";
		return Executor::doit($sql);
	}

	//Cancelar y finalizar tratamiento actualizando el estatus.
	public function updatePatientTreatmentStatus()
	{
		$sql = "UPDATE " . self::$tablenamePatientTreatments . " set status_id=\"$this->status_id\",end_date='" . date('Y-m-d') . "',cancellation_reason=\"$this->cancellation_reason\",
		last_note=\"$this->last_note\",psychiatrist=\"$this->psychiatrist\"
		WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Actualizar la fecha de fin de un tratamiento.
	public function updatePatientTreatmentEndDate()
	{
		$sql = "UPDATE " . self::$tablenamePatientTreatments . " SET end_date=\"$this->end_date\" WHERE id = $this->id";
		return Executor::doit($sql);
	}


	//Actualizar los datos de un tratamiento del paciente
	public function updatePatientTreatment()
	{
		$sql = "UPDATE " . self::$tablenamePatientTreatments . " 
				SET treatment_id=\"$this->treatment_id\",medic_id=\"$this->medic_id\",default_price=\"$this->default_price\",
				start_date=\"$this->start_date\",reason=\"$this->reason\",cancellation_reason=\"$this->cancellation_reason\",
				psychiatrist=\"$this->psychiatrist\",last_note=\"$this->last_note\"
				WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function deletePatientTreatment()
	{
		$sql = "DELETE FROM " . self::$tablenamePatientTreatments . " 
			WHERE id = \"$this->id\" ";
		return Executor::doit($sql);
	}

	public static function getLastPatientTreatment($patientId)
	{
		//Obtiene si ya se registró cierto detalle del procedimiento
		$sql = "SELECT " . self::$tablenamePatientTreatments . ".*,m.name AS medic_name 
				FROM " . self::$tablenamePatientTreatments . "
				LEFT JOIN medics m ON " . self::$tablenamePatientTreatments . ".medic_id = m.id
				WHERE patient_id = '$patientId'
				ORDER BY start_date DESC,id DESC LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new TreatmentData());
	}

	public static function getLastFinishedPatientTreatment($patientId)
	{
		//Obtiene si ya se registró cierto detalle del procedimiento
		$sql = "SELECT " . self::$tablenamePatientTreatments . ".*,m.name AS medic_name 
				FROM " . self::$tablenamePatientTreatments . "
				LEFT JOIN medics m ON " . self::$tablenamePatientTreatments . ".medic_id = m.id
				WHERE patient_id = '$patientId'
				AND (status_id = 2 OR status_id = 3)
				ORDER BY start_date DESC,id DESC LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new TreatmentData());
	}

	/*----------DATOS EN ENTREVISTAS---- */
	public static function getDetailsByPatientTreatment($patientTreatmentId)
	{
		$sql = "SELECT " . self::$tablenameInterviewDetails . ".id, " . self::$tablenamePatientInterviewDetails . ".value
			FROM " . self::$tablenameInterviewDetails . " 
			LEFT JOIN " . self::$tablenamePatientInterviewDetails . " ON " . self::$tablenameInterviewDetails . ".id = " . self::$tablenamePatientInterviewDetails . ".interview_detail_id
			AND " . self::$tablenamePatientInterviewDetails . ".patient_treatment_id = '$patientTreatmentId'
			ORDER BY " . self::$tablenameInterviewDetails . ".id";
		$query = Executor::doit($sql);

		$array = array();
		while ($r = $query[0]->fetch_array()) {
			$array[$r['id']] = $r['value'];
		}
		return $array;
	}

	public static function getDetailByPatientTreatment($patientTreatmentId, $detailId)
	{
		//Obtiene si ya se registró cierto detalle del procedimiento
		$sql = "SELECT * FROM " . self::$tablenamePatientInterviewDetails . "
				WHERE patient_treatment_id = '$patientTreatmentId'
				AND interview_detail_id = '$detailId'";

		$query = Executor::doit($sql);
		return Model::one($query[0], new TreatmentData());
	}

	public function addDetailByPatientTreatment()
	{
		$sql = "INSERT INTO " . self::$tablenamePatientInterviewDetails . " (patient_treatment_id,interview_detail_id,value) ";
		$sql .= "VALUE (\"$this->patient_treatment_id\",\"$this->interview_detail_id\",\"$this->value\")";
		return Executor::doit($sql);
	}

	public function updateDetailByPatientTreatment()
	{
		$sql = "UPDATE " . self::$tablenamePatientInterviewDetails . " 
			SET value=\"$this->value\" 
			WHERE patient_treatment_id = $this->patient_treatment_id
			AND interview_detail_id = $this->interview_detail_id";
		return Executor::doit($sql);
	}

	public static function deleteDetailsByPatientTreatment($patientTreatmentId)
	{
		$sql = "DELETE FROM " . self::$tablenamePatientInterviewDetails . " 
		WHERE patient_treatment_id = '$patientTreatmentId'";
		return Executor::doit($sql);
	}
}
