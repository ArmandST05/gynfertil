<?php
class ReservationData
{
	public static $tablename = "reservations";
	public static $notesTablename = "patient_notes";
	public static $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

	public static $tablenamePatientTreatments = "patient_treatments";

	public function __construct()
	{
		$this->created_at = date("Y-m-d H:i:s");
	}

	public function getPatient()
	{
		return PatientData::getById($this->patient_id);
	}
	public function getMedic()
	{
		return MedicData::getById($this->medic_id);
	}
	public function getLaboratory()
	{
		return LaboratoryData::getById($this->laboratory_id);
	}
	public function getStatus()
	{
		return ReservationStatusData::getById($this->status_id);
	}
	public function getCategory()
	{
		return ReservationCategoryData::getById($this->category_id);
	}
	public function getArea()
	{
		return ReservationAreaData::getById($this->area_id);
	}

	public function getReservationDateFormat()
	{
		$day = substr($this->date_at, 8, 2);
		$month = substr($this->date_at, 5, 2);
		$year = substr($this->date_at, 0, 4);

		return $day . "/" . self::$months[$month] . "/" . $year;
	}

	public function getDate()
	{
		$date = substr($this->date_at, 0, 10);
		return $date;
	}

	public function getStartTime()
	{
		$time = substr($this->date_at, 11, 5);
		return $time;
	}

	public function getEndTime()
	{
		$time = substr($this->date_at_final, 11, 5);
		return $time;
	}

	public static function getById($id)
	{
		$sql = "SELECT " . self::$tablename . ".*,DATE_FORMAT(date_at,'%d/%m/%Y') AS date_format 
		FROM " . self::$tablename . " WHERE " . self::$tablename . ".id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public function addPatient()
	{
		$sql = "insert into " . self::$tablename . " (patient_id,medic_id,date_at,date_at_final,user_id,reason,category_id,branch_office_id,area_id,laboratory_id)";
		$sql .= "value (\"$this->patient_id\",\"$this->medic_id\",\"$this->date_at\",\"$this->date_at_final\",\"$this->user_id\",\"$this->reason\",\"$this->category_id\",\"$this->branch_office_id\",\"$this->area_id\",\"$this->laboratory_id\")";
		return Executor::doit($sql);
	}

	public function updatePatient()
	{
		$sql = "UPDATE " . self::$tablename . " set patient_id=\"$this->patient_id\",medic_id=\"$this->medic_id\",date_at=\"$this->date_at\",date_at_final=\"$this->date_at_final\",reason=\"$this->reason\",laboratory_id=\"$this->laboratory_id\",category_id=\"$this->category_id\",branch_office_id=\"$this->branch_office_id\",area_id=\"$this->area_id\" WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public function addDoctor()
	{
		$sql = "INSERT INTO " . self::$tablename . " (medic_id,user_id,date_at,date_at_final,reason,branch_office_id)";
		$sql .= "value (\"$this->medic_id\",\"$this->user_id\",\"$this->date_at\",\"$this->date_at_final\",\"$this->reason\",\"$this->branch_office_id\")";
		return Executor::doit($sql);
	}

	public function updateDoctor()
	{
		$sql = "UPDATE " . self::$tablename . " set medic_id=\"$this->medic_id\",user_id=\"$this->user_id\",date_at=\"$this->date_at\",date_at_final=\"$this->date_at_final\",reason=\"$this->reason\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updateStatus()
	{
		$sql = "UPDATE " . self::$tablename . " set status_id=\"$this->status_id\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updateNotifiedPatient()
	{
		$sql = "UPDATE " . self::$tablename . " set is_patient_notified=\"$this->is_patient_notified\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updateReason()
	{
		$sql = "UPDATE " . self::$tablename . " set reason=\"$this->reason\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updatePatientObservations()
	{
		$sql = "UPDATE " . self::$tablename . " set patient_observations=\"$this->patient_observations\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updateDiagnosticObservations()
	{
		$sql = "UPDATE " . self::$tablename . " set diagnostic_observations=\"$this->diagnostic_observations\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updateTreatmentObservations()
	{
		$sql = "UPDATE " . self::$tablename . " set treatment_observations=\"$this->treatment_observations\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function getRepeatedReservation($patientId, $medicId, $dateAt, $laboratoryId)
	{
		//Valida si ya se fijó la cita del paciente con ese doctor, ese día y en ese consultorio
		$sql = "SELECT * FROM " . self::$tablename . " WHERE patient_id = '$patientId' AND medic_id = '$medicId' AND date_at=\"$dateAt\" AND laboratory_id=\"$laboratoryId\"";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public static function getMedicRepeatedReservation($medicId, $dateAt)
	{
		//Valida si ya se fijó la cita del paciente con ese doctor, ese día y en ese consultorio
		$sql = "SELECT * FROM " . self::$tablename . " WHERE medic_id = '$medicId' AND date_at=\"$dateAt\"";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public static function getRepeatedLaboratory($dateAt, $laboratoryId)
	{
		//Valida si el laboratorio/consultorio ya está ocupado en ese horario.
		$sql = "SELECT * FROM " . self::$tablename . " WHERE date_at=\"$dateAt\" AND laboratory_id=\"$laboratoryId\"";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public static function getByMail($mail)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE mail=\"$mail\"";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public static function getEvery($fecha1)
	{
		$sql = "SELECT  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,date_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas  FROM reservation r WHERE date_at>='$fecha1' order by date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getEvery_doctor($id_user, $fecha1)
	{
		$sql = "SELECT  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,date_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas FROM reservation r WHERE medic_id='$id_user' AND date_at>='$fecha1' order by date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getEvery_au($fecha1)
	{
		$sql = "SELECT  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,date_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas FROM reservation r WHERE negritas='1' AND date_at>='$fecha1' order by date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getByStartDate($startDate, $branchOfficeId = 0,$medicId = 0)
	{
		//Obtiene TODAS las citas a partir de una fecha
		//Se muestra al administrador y recepcionista
		//El formato de la fecha es datetime
		$sql = "SELECT r.id,r.patient_id,r.medic_id,r.date_at,r.date_at_final,rc.name AS reservation_category_name,
					p.company_id,r.status_id,r.is_patient_notified,r.sale_status_payment,
					DATE_FORMAT(r.date_at,'%Y-%m-%d') AS date_at_format,
					TRIM(p.name) AS patient_name, TRIM(p.cellphone) AS patient_phone,
					m.name AS medic_name,r.user_id,r.reason,r.category_id,
					m.calendar_color
					FROM " . self::$tablename . " r
					INNER JOIN " . MedicData::$tablename . " m ON r.medic_id = m.id
					LEFT JOIN " . ReservationCategoryData::$tablename . " rc ON r.category_id = rc.id
					LEFT JOIN " . PatientData::$tablename . " p ON r.patient_id = p.id
					WHERE r.date_at >= '$startDate' ";
					if ($branchOfficeId != 0) {
						$sql .= " AND r.branch_office_id = '$branchOfficeId' ";
					}
					if ($medicId != 0) {
						$sql .= " AND r.medic_id = '$medicId' ";
					}
				$sql.= " ORDER BY date_at ASC ";

		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getBetweenDates($startDate, $endDate, $branchOfficeId = 0,$medicId = 0)
	{
		//Obtiene TODAS las citas entre las fechas seleccionadas
		//Se muestra al administrador y recepcionista
		//El formato de la fecha es datetime
		$sql = "SELECT r.id,r.patient_id,r.medic_id,r.date_at,date_at_final,rc.name AS reservation_category_name,
					p.company_id,r.status_id,r.is_patient_notified,r.sale_status_payment,
					DATE_FORMAT(r.date_at,'%Y-%m-%d') AS date_at_format,
					TRIM(p.name) AS patient_name, TRIM(p.cellphone) AS patient_phone,
					m.name AS medic_name,r.user_id,r.reason,r.category_id,
					m.calendar_color
					FROM " . self::$tablename . " r
					INNER JOIN " . MedicData::$tablename . " m ON r.medic_id = m.id
					LEFT JOIN " . ReservationCategoryData::$tablename . " rc ON r.category_id = rc.id
					LEFT JOIN " . PatientData::$tablename . " p ON r.patient_id = p.id
					WHERE r.date_at >='$startDate'
					AND r.date_at <= '$endDate' ";
					
		if ($branchOfficeId != 0) {
			$sql .= " AND r.branch_office_id = '$branchOfficeId' ";
		}
		if ($medicId != 0) {
			$sql .= " AND r.medic_id = '$medicId' ";
		}
		$sql .= " ORDER BY date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getByPersonName($name)
	{
		//Obtiene las citas cuando coincida el nombre buscado con el de un médico, paciente o el familiar de un paciente
		$sql = "SELECT CONCAT(ELT(WEEKDAY(r.date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')) AS day_name,
			DATE_FORMAT(r.date_at,'%d/%m/%Y %H:%i') AS date_at,r.id,r.patient_id,r.medic_id,r.user_id,
			m.name AS medic_name,p.name AS patient_name,p.cellphone AS patient_phone, p.relative_name AS relative_name
			FROM " . self::$tablename . " r,medics m,patients p 
			WHERE r.medic_id = m.id 
			AND p.id = r.patient_id 
			AND (m.`name` like '%$name%' OR p.`name` like '%$name%' OR p.`relative_name` like '%$name%') 
			ORDER BY r.date_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getByPatient($patientId)
	{
		$sql = "SELECT CONCAT(ELT(WEEKDAY(r.date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')) AS day_name,
			DATE_FORMAT(r.date_at,'%d/%m/%Y %H:%i')as date_at_format,
			r.id,r.patient_id,r.medic_id,r.user_id,r.status_id,r.date_at
			FROM " . self::$tablename . " r 
			WHERE r.patient_id = '$patientId'
			ORDER BY r.date_at DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getByLimits($start,$end)
	{
		$sql = "SELECT * FROM " . self::$tablename . " r 
			LIMIT $start,$end ";

		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	//Obtiene todas las citas de pacientes en un rango de fechas dependiendo de si se ha notificado al paciente o no
	public static function getByPatientNotifiedDates($startDate, $endDate, $branchOfficeId, $medicId, $notifiedTypeId)
	{
		$startDateTime = $startDate . " 00:00:01";
		$endDateTime = $endDate . " 23:59:59";

		$sql = "SELECT CONCAT(ELT(WEEKDAY(r.date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')) AS day_name,
			DATE_FORMAT(r.date_at,'%d/%m/%Y %H:%i %p')as date_at_format,rc.name AS reservation_category_name,
			r.id,r.patient_id,r.medic_id,r.user_id,r.status_id,r.is_patient_notified,
			p.name AS patient_name,m.name AS medic_name,bo.name AS branch_office_name,rs.name AS status_name,
			p.cellphone AS patient_cellphone,p.homephone AS patient_homephone
			FROM " . self::$tablename . " r 
			INNER JOIN " . BranchOfficeData::$tablename . " bo ON bo.id = r.branch_office_id
			INNER JOIN " . PatientData::$tablename . " p ON p.id = r.patient_id
			INNER JOIN " . MedicData::$tablename . " m ON m.id = r.medic_id
			INNER JOIN " . ReservationStatusData::$tablename . " rs ON rs.id = r.status_id
			INNER JOIN " . ReservationCategoryData::$tablename . " rc ON rc.id = r.category_id
			WHERE r.date_at >= '$startDateTime' AND  r.date_at <= '$endDateTime'
			AND r.branch_office_id = '$branchOfficeId'
			AND r.is_patient_notified = '$notifiedTypeId' ";
		if ($medicId != 0) {
			$sql .= " AND r.medic_id = '$medicId' ";
		}
		$sql .= " ORDER BY r.date_at ASC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	//Obtiene la última cita a la que el paciente asistió (status_id = 2)
	public static function getLastByPatientId($patientId)
	{
		$sql = "SELECT " . self::$tablename . ".*,
			DATE_FORMAT(date_at,'%d/%m/%Y') AS date_format 
			FROM " . self::$tablename . " 
			WHERE " . self::$tablename . ".patient_id = $patientId
			AND status_id = 2
			ORDER BY id DESC";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	//Obtiene la primera cita del paciente entre fechas
	public static function getFirstByDatesStatusPatientId($patientId,$statusId,$startDate,$endDate)
	{
		$startDateTime = $startDate." 00:00:00";
		if($endDate == null || $endDate == "0000-00-00"){
			$endDate = date("Y-m-d",strtotime("+1 year"));
		}
		$endDateTime = $endDate." 23:59:59";

		$sql = "SELECT " . self::$tablename . ".*,
			DATE_FORMAT(date_at,'%d/%m/%Y') AS date_format 
			FROM " . self::$tablename . " 
			WHERE " . self::$tablename . ".patient_id = '$patientId' 
			AND date_at >= '$startDateTime' AND date_at <= '$endDateTime' 
			AND status_id = '$statusId'
			ORDER BY id ASC LIMIT 1";
			
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public static function getPatientsNotFirstInterviewByBranchOffice($branchOfficeId, $startDateTime, $endDateTime)
	{
		$sql = "SELECT p.*,p.id AS patient_id
			FROM patients p
			WHERE p.created_at >= '$startDateTime' AND p.created_at <= '$endDateTime'
			AND (SELECT COUNT(id) total FROM " . self::$tablename . " r WHERE r.patient_id = p.id) < 1
			AND p.branch_office_id = '$branchOfficeId'
			ORDER BY created_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getPatientsFirstInterviewByBranchOffice($branchOfficeId, $startDateTime, $endDateTime)
	{
		$sql = "SELECT p.*,p.id AS patient_id
			FROM patients p
			WHERE p.created_at >= '$startDateTime' AND p.created_at <= '$endDateTime'
			AND (SELECT COUNT(id) total FROM " . self::$tablename . " r WHERE r.patient_id = p.id) > 0
			AND p.branch_office_id = '$branchOfficeId'
			ORDER BY created_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getTotalReservationsByPatient($patientId, $statusId)
	{
		$sql = "SELECT COUNT(id) AS total 
			FROM " . self::$tablename . "
			WHERE patient_id = '$patientId'
			AND status_id = '$statusId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public static function getTotalReservationsByPatientDates($patientId, $startDateTime, $endDateTime, $statusId)
	{
		$sql = "SELECT COUNT(id) AS total 
			FROM " . self::$tablename . "
			WHERE patient_id = '$patientId'
			AND date_at >= '$startDateTime' 
			AND date_at_final <= '$endDateTime'
			AND status_id = '$statusId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new ReservationData());
	}

	public static function getAllByMedicId($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE medic_id = '$id' order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getLike($q)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE title like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new ReservationData());
	}

	public static function getReservationCategories()
	{
		$sql = "SELECT id,name,description FROM " . ReservationCategoryData::$tablename . "";
		$query = Executor::doit($sql);
		return Model::many($query[0], new UserData());
	}

	public static function getReservationCategoryById($id)
	{
		$sql = "SELECT id,name,description FROM " . ReservationCategoryData::$tablename . " WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new UserData());
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function deleteAllFutureByPatientId($patientId)
	{
		$actualDate = date("Y-m-d H:i:s");
		$sql = "DELETE FROM " . self::$tablename . " 
		WHERE patient_id = $patientId 
		AND date_at >= '$actualDate'";
		return Executor::doit($sql);
	}

	//-----------------------REPORTES CITAS AGENDADAS reports/scheduled-patients-------------------------
	//***********AGENDADOS*************
	public static function getScheduledPatientsByBranchOffice($branchOfficeId, $startDateTime, $endDateTime, $medicId, $statusId, $patientTypeId)
	{
		$startDate = substr($startDateTime, 0, 10);
		$endDate = substr($endDateTime, 0, 10);

		$sql = "SELECT CONCAT(ELT(WEEKDAY(r.date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')) AS day_name,
			DATE_FORMAT(r.date_at,'%d/%m/%Y %H:%i') AS date_at,
			r.id,r.patient_id,r.medic_id,r.user_id,r.status_id
			FROM " . self::$tablename . " r 
			INNER JOIN " . PatientData::$tablename . " p ON p.id = r.patient_id
			WHERE r.date_at >= '$startDateTime'
			AND r.date_at <= '$endDateTime'
			AND r.branch_office_id = '$branchOfficeId' ";
		if ($medicId != 0) $sql .= " AND r.medic_id = '$medicId' "; //Obtener de un estatus en específico
		if ($statusId != 0 && $statusId != "all" && $statusId != "scheduled" && $statusId != "notscheduled") $sql .= " AND r.status_id = '$statusId' "; //Obtener de un estatus en específico
		//Si $patientTypeId == 0 incluir a los pacientes independientemente si tienen tratamiento o no en las fechas seleccionadas, no hay ningún filtro
		if ($patientTypeId == 1) {
			$sql .= " AND (SELECT " . TreatmentData::$tablenamePatientTreatments . ".id FROM " . TreatmentData::$tablenamePatientTreatments . " 
			WHERE  " . TreatmentData::$tablenamePatientTreatments . ".patient_id = r.patient_id
			AND ( 
				(" . TreatmentData::$tablenamePatientTreatments . ".status_id = 1 AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate')
				OR (" . TreatmentData::$tablenamePatientTreatments . ".status_id != 1 AND (" . self::$tablenamePatientTreatments . ".end_date < '$startDate') AND 
					(
						('$startDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
						OR ('$endDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
					)
				)
			)
			ORDER BY " . TreatmentData::$tablenamePatientTreatments . ".id DESC LIMIT 1
		) IS NOT NULL "; //Sólo pacientes con tratamiento en las fechas seleccionados
		}
		if ($patientTypeId == 2) {
			$sql .= " AND (SELECT " . TreatmentData::$tablenamePatientTreatments . ".id FROM " . TreatmentData::$tablenamePatientTreatments . " 
				WHERE  " . TreatmentData::$tablenamePatientTreatments . ".patient_id = r.patient_id
				AND ( 					
					(" . TreatmentData::$tablenamePatientTreatments . ".status_id = 1 AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate')
					OR (" . TreatmentData::$tablenamePatientTreatments . ".status_id != 1 AND (" . self::$tablenamePatientTreatments . ".end_date < '$startDate') AND
						(
							('$startDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
							OR ('$endDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
						)
					)
				)
				ORDER BY " . TreatmentData::$tablenamePatientTreatments . ".id DESC LIMIT 1
				) IS NULL "; //Sólo pacientes que no tienen ningún tratamiento en las fechas seleccionadas
		}
		if ($patientTypeId == 3) {
			//Sólo pacientes COMPLETAMENTE NUEVOS, antes de las fechas seleccionadas, tienen sólo un tratamiento o es su primer tratamiento
			
			$sql .= " AND (p.created_at BETWEEN '$startDateTime' AND '$endDateTime') ";
		}
		if ($patientTypeId == 4) {
			$sql .= " AND (SELECT COUNT(" . TreatmentData::$tablenamePatientTreatments . ".id) FROM " . TreatmentData::$tablenamePatientTreatments . " 
			WHERE  " . TreatmentData::$tablenamePatientTreatments . ".patient_id = r.patient_id
			AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate'
			) > 1 "; //Sólo pacientes que son REINGRESO, que tienen más de un tratamiento en las fechas seleccionadas
		}
		$sql .= " ORDER BY date_at DESC";
		$query = Executor::doit($sql);

		return Model::many($query[0], new ReservationData());
	}

	public static function getNotScheduledPatientsByBranchOffice($branchOfficeId, $startDateTime, $endDateTime, $medicId, $patientTypeId,$pendingNotification = 0)
	{
		$startDate = substr($startDateTime, 0, 10);
		$endDate = substr($endDateTime, 0, 10);

		//No se pueden obtener los pacientes "nuevos" de un médico en específico, porque si son nuevos es porque no se les asignó ningún médico.
		if ($medicId != 0 && ($patientTypeId == 2 || $patientTypeId == 3)) {
			return [];
		} else {
			$sql = "SELECT p.id AS patient_id,r.id
				FROM " . PatientData::$tablename . " p
				LEFT JOIN " . self::$tablename . " r ON r.patient_id = p.id
				AND r.date_at >= '$startDateTime' AND r.date_at <= '$endDateTime'
				WHERE r.id IS NULL
				AND p.branch_office_id = '$branchOfficeId' ";
			if ($patientTypeId == 0 || $patientTypeId == 1) {
				$sql .= " AND (SELECT " . TreatmentData::$tablenamePatientTreatments . ".id FROM " . TreatmentData::$tablenamePatientTreatments . " 
					WHERE  " . TreatmentData::$tablenamePatientTreatments . ".patient_id = p.id
					AND (
						(" . TreatmentData::$tablenamePatientTreatments . ".status_id = 1 AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate')
						OR (" . TreatmentData::$tablenamePatientTreatments . ".status_id != 1 AND (" . self::$tablenamePatientTreatments . ".end_date < '$startDate') AND 
							(
								('$startDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
								OR ('$endDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
							)
						)
					) ";
				if ($medicId != 0) {
					$sql .= " AND " . TreatmentData::$tablenamePatientTreatments . ".medic_id = '$medicId' ";
				}
				if ($pendingNotification == 1) {
					$sql .= " AND (SELECT n.id
						FROM ".PatientNotificationData::$tablename ." n 
						WHERE (n.date BETWEEN '$startDate' AND '$endDate')
						AND n.patient_id = p.id LIMIT 1) IS NULL ";
				}
				$sql .= " ORDER BY " . TreatmentData::$tablenamePatientTreatments . ".id DESC LIMIT 1
					) IS NOT NULL "; //Sólo pacientes con tratamiento en las fechas seleccionados
			}
			if ($patientTypeId == 2) {
				$sql .= " AND (SELECT " . TreatmentData::$tablenamePatientTreatments . ".id FROM " . TreatmentData::$tablenamePatientTreatments . " 
				WHERE  " . TreatmentData::$tablenamePatientTreatments . ".patient_id = p.id
				AND ( 
					(" . TreatmentData::$tablenamePatientTreatments . ".status_id = 1 AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate')
					OR (" . TreatmentData::$tablenamePatientTreatments . ".status_id != 1 AND (" . self::$tablenamePatientTreatments . ".end_date < '$startDate') AND 
						(
							('$startDate' BETWEEN CAST(" . TreatmentData::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . TreatmentData::$tablenamePatientTreatments . ".end_date AS DATE))
							OR ('$endDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
						)
					)
				)
				ORDER BY " . TreatmentData::$tablenamePatientTreatments . ".id DESC LIMIT 1
				) IS NULL "; //Sólo pacientes que no tienen ningún tratamiento registrado en esas fechas
			}
			if ($patientTypeId == 3) {
				$sql .= " AND (p.created_at BETWEEN '$startDateTime' AND '$endDateTime') ";
			}
			if ($patientTypeId == 4) {
				//SÓLO PACIENTES DE REINGRESO, tienen qué tener un tratamiento activo en las fechas seleccionadas, y que sea su segundo tratamiento o más.
				$sql .= " AND (SELECT COUNT(" . TreatmentData::$tablenamePatientTreatments . ".id) FROM " . TreatmentData::$tablenamePatientTreatments . " 
				WHERE  " . TreatmentData::$tablenamePatientTreatments . ".patient_id = r.patient_id
				AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate'
				) > 1 "; //Sólo pacientes que son REINGRESO, que tienen más de un tratamiento en las fechas seleccionadas

				$sql .= " AND (SELECT " . TreatmentData::$tablenamePatientTreatments . ".id FROM " . TreatmentData::$tablenamePatientTreatments . " 
					WHERE  " . TreatmentData::$tablenamePatientTreatments . ".patient_id = p.id
					AND (
						(" . TreatmentData::$tablenamePatientTreatments . ".status_id = 1 AND " . TreatmentData::$tablenamePatientTreatments . ".start_date <= '$endDate')
						OR (" . TreatmentData::$tablenamePatientTreatments . ".status_id != 1 AND 
							(
								('$startDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
								OR ('$endDate' BETWEEN CAST(" . self::$tablenamePatientTreatments . ".start_date AS DATE) AND CAST(" . self::$tablenamePatientTreatments . ".end_date AS DATE))
							)
						)
					) ";
				if ($medicId != 0) {
					$sql .= " AND " . TreatmentData::$tablenamePatientTreatments . ".medic_id = '$medicId' ";
				}
				$sql .= " ORDER BY " . TreatmentData::$tablenamePatientTreatments . ".id DESC LIMIT 1
					) IS NOT NULL "; //Sólo pacientes con tratamiento en las fechas seleccionados
			}
			$sql .= " GROUP BY p.id
				ORDER BY date_at DESC";
			$query = Executor::doit($sql);
			return Model::many($query[0], new ReservationData());
		}
	}

	public static function updateLastSaleStatus($reservationId)
	{
		//Actualizar estatus de la última venta en la cita vinculada para mostrar correctamente la información en el calendario.
		$lastSale = OperationData::getLastSaleByReservationId($reservationId);
		$status = 1;//Venta no generada
		if(isset($lastSale)){
			if($lastSale->status_id == 0){
				$status = 2;//Pago pendiente liquidar
			}elseif($lastSale->status_id == 1){
				$status = 3;//Pago liquidado
			}
		}

		$sql = "UPDATE " . self::$tablename . " set sale_status_payment=\"$status\" WHERE id='$reservationId'";
		return Executor::doit($sql);
	}

}
