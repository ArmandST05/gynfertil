<?php
class PatientCategoryData
{
	public static $tablenameTreatmentStatus = "treatment_status";
	public static $tablenameCategories = "patient_categories";
	public static $tablenameTreatments = "patient_treatments";
	public static $tablenamePatientCategoryTreatments = "patient_category_treatments";
	public static $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

	public static $tablenamePartners = "patient_procedure_partners";
	public function __construct()
	{
		$this->patient_category_id = "";
		$this->patient_treatment_id = "";
		$this->patient_id = "";
		$this->created_at = date("Y-m-d H:i:s");
	}

	public function getDateMonthFormat($date)
	{
		//Obtiene la fecha de nacimiento con el nombre del mes del paciente
		if ($date) {
			$day = substr($date, 8, 2);
			$month = substr($date, 5, 2);
			$year = substr($date, 0, 4);

			return $day . "/" . self::$months[$month] . "/" . $year;
		} else {
			return "No especificada.";
		}
	}

	public function getPatient()
	{
		return PatientData::getById($this->patient_id);
	}

	public function getTreatmentStatus()
	{
		return PatientCategoryData::getStatusById($this->treatment_status_id);
	}

	public static function getPatientHistoryResume($patientId)
	{
		//Obtiene el histórico de todas las categorías/tratamientos del paciente
		//Pero sólo muestra las iniciales para que sea un resumen
		$categoriesHistory = "";
		$categories = PatientCategoryData::getAllPatientCategories($patientId);

		if (count($categories) > 0) {
			$category = array_shift($categories);
			$categoriesHistory = "<b>" . $category->category_abbreviation . "</b>, ";
			foreach ($categories as $category) {
				$categoriesHistory .= $category->category_abbreviation . ", ";
			}
			$categoriesHistory = substr($categoriesHistory, 0, -2);
		}

		return $categoriesHistory;
	}

	public function add()
	{
		$sql = "insert into " . self::$tablenameCategories . " (name)";
		$sql .= "value (\"$this->name\")";

		return Executor::doit($sql);
	}

	public function update()
	{
		$sql = "update " . self::$tablenameCategories . " set name=\"$this->name\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public function delete()
	{
		$sql = "delete from " . self::$tablenameCategories . " WHERE id=$this->id";
		Executor::doit($sql);
	}

	//CATEGORÍAS
	public static function getAllCategories()
	{
		$sql = "SELECT * FROM " . self::$tablenameCategories . " ORDER BY ordering";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	public function addCategoryByPatient()
	{
		$sql = "INSERT INTO " . self::$tablenamePatientCategoryTreatments . " (patient_category_id,patient_treatment_id,primary_treatment_id,patient_id,treatment_code,treatment_location_id,start_date)";
		$sql .= " value (\"$this->patient_category_id\",\"$this->patient_treatment_id\",\"$this->primary_treatment_id\",\"$this->patient_id\",\"$this->treatment_code\",\"$this->treatment_location_id\",\"$this->start_date\")";
		return Executor::doit($sql);
	}

	public static function getLastCategoryTreatmentPatient()
	{
		$sql = "SELECT * FROM " . self::$tablenamePatientCategoryTreatments . " ORDER BY id DESC LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	public static function getById($id)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".*," . self::$tablenameCategories . ".name AS category_name,
			" . self::$tablenameTreatments . ".name as treatment_name," . self::$tablenameTreatments . ".embryology_procedure_code,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			" . self::$tablenameTreatments . ".is_pregnancy_test  
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameCategories . " ON " . self::$tablenameCategories . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_category_id
			LEFT JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			WHERE " . self::$tablenamePatientCategoryTreatments . ".id = $id ";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	//Obtiene el tratamiento por el código de embriología
	public static function getByCode($code)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".*," . self::$tablenameCategories . ".name AS category_name,
			" . self::$tablenameTreatments . ".name as treatment_name," . self::$tablenameTreatments . ".embryology_procedure_code,
			" . self::$tablenameTreatments . ".is_pregnancy_test  
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameCategories . " ON " . self::$tablenameCategories . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_category_id
			LEFT JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			WHERE " . self::$tablenamePatientCategoryTreatments . ".treatment_code = '$code'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	//Busca los los tratamientos por código de embriología y por nombre del paciente (se utiliza en select del procedimiento, muestra id (id del tratamiento) y text (código de embriología + paciente))
	public static function getByCodeNameSearch($code)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".id AS id,
			CONCAT(" . self::$tablenamePatientCategoryTreatments . ".treatment_code,' - ',patients.name) AS text 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameCategories . " ON " . self::$tablenameCategories . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_category_id
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient AS patients ON " . self::$tablenamePatientCategoryTreatments . ".patient_id = patients.id
			WHERE (" . self::$tablenamePatientCategoryTreatments . ".treatment_code like '%$code%'
			OR patients.name like '%$code%')
			AND " . self::$tablenameTreatments . ".is_embryology_procedure = 1
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	public function updateCategoryTreatmentStatusPatient()
	{
		//Cancelar y finalizar tratamiento actualizando el estatus.
		$sql = "UPDATE " . self::$tablenamePatientCategoryTreatments . " set treatment_status_id=\"$this->treatment_status_id\",end_date='" . date('Y-m-d') . "',note=\"$this->note\" WHERE id = $this->id";

		return Executor::doit($sql);
	}

	public static function updateSubTreatmentStatusPatient($primaryTreatmentId, $treatmentStatusId, $note)
	{
		//Cancelar y finalizar el subtratamiento actualizando el estatus.
		$sql = "UPDATE " . self::$tablenamePatientCategoryTreatments . " set treatment_status_id=\"$treatmentStatusId\",end_date='" . date('Y-m-d') . "',note=\"$note\" 
		WHERE primary_treatment_id = $primaryTreatmentId";
		return Executor::doit($sql);
	}

	public function updateTreatmentPregnancyTestDate()
	{
		//Cancelar y finalizar tratamiento.
		$sql = "update " . self::$tablenamePatientCategoryTreatments . " set pregnancy_test_date=\"$this->pregnancy_test_date\" WHERE id = $this->id";
		return Executor::doit($sql);
	}


	//Guardar resultados del embarazo en tratamiento de fertilidad y finalizar el tratamiento
	public function updateCategoryTreatmentResultPatient()
	{
		$sql = "update " . self::$tablenamePatientCategoryTreatments . " set treatment_status_id='4',pregnancy_test_date='$this->pregnancy_test_date',pregnancy_test_result=\"$this->pregnancy_test_result\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Colocar el estatus como finalizado en todas las categorías/tratamientos anteriores del paciente.
	public static function finishAllCategoryTreatmentsByPatient($patientId)
	{
		$sql = "update " . self::$tablenamePatientCategoryTreatments . " set treatment_status_id='4',end_date='" . date('Y-m-d') . "'
		WHERE patient_id = $patientId AND treatment_status_id = 1";
		return Executor::doit($sql);
	}


	//Obtiene la categoría/tratamiento actual del paciente
	public static function getPatientCategoryDetail($patientId)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".*," . self::$tablenameCategories . ".name AS category_name,
			" . self::$tablenameTreatments . ".name as treatment_name,
			" . self::$tablenameTreatments . ".is_pregnancy_test
			FROM " . self::$tablenamePatientCategoryTreatments . "
			INNER JOIN " . self::$tablenameCategories . " ON " . self::$tablenameCategories . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_category_id
			LEFT JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			WHERE patient_id = '$patientId' 
			AND (treatment_status_id = 1 OR treatment_status_id = 2) 
			AND primary_treatment_id = 0
			ORDER BY id DESC LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	public static function getAllPatientCategories($patientId)
	{
		//Obtiene el histórico de todas las categorías/tratamientos del cliente
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".*,
			" . self::$tablenameCategories . ".name AS category_name,
			" . self::$tablenameCategories . ".abbreviation AS category_abbreviation,
			" . self::$tablenameTreatments . ".name as treatment_name,
			" . self::$tablenameTreatments . ".embryology_procedure_code,
			" . self::$tablenameTreatments . ".is_embryology_procedure,
			" . self::$tablenameTreatments . ".is_pregnancy_test,
			" . self::$tablenameTreatmentStatus . ".name AS status_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format
			FROM " . self::$tablenamePatientCategoryTreatments . "
			INNER JOIN " . self::$tablenameCategories . " ON " . self::$tablenameCategories . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_category_id
			LEFT JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			LEFT JOIN " . self::$tablenameTreatmentStatus . " ON " . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = " . self::$tablenameTreatmentStatus . ".id
			WHERE patient_id = '$patientId' 
			ORDER BY id DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene el total de tratamientos del cliente agrupados por el tipo de tratamiento = Ciclos de tratamientos.
	public static function getTreatmentsAmountByPatient($patientId)
	{
		$sql = "SELECT " . self::$tablenameTreatments . ".name AS treatment_name,
			(SELECT COUNT(id) FROM " . self::$tablenamePatientCategoryTreatments . " 
			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id = " . self::$tablenameTreatments . ".id
			AND " . self::$tablenamePatientCategoryTreatments . ".patient_id = '$patientId') 
			AS total
			FROM " . self::$tablenamePatientCategoryTreatments . "
			INNER JOIN " . self::$tablenameCategories . " ON " . self::$tablenameCategories . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_category_id
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			WHERE patient_id = '$patientId'
			GROUP BY " . self::$tablenameTreatments . ".id
			ORDER BY " . self::$tablenameTreatments . ".name ASC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}


	//Obtiene el total de tratamientos de cierto tipo que ha realizado el paciente antes de cierto id de tratamiento en específico
	//Obtener el ciclo del tratamiento de ese tipo que está realizando.
	public static function getTotalPatientTreatmentsByType($patientId, $treatmentId, $patientTreatmentId)
	{
		$sql = "SELECT COUNT(id) AS total FROM " . self::$tablenamePatientCategoryTreatments . "
			WHERE patient_treatment_id = '$treatmentId'
			AND patient_id = '$patientId'
			AND id <= '$patientTreatmentId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}


	/*-------------ESTATUS DEL TRATAMIENTO----------------------*/
	public static function getStatusById($id)
	{
		$sql = "SELECT * FROM " . self::$tablenameTreatmentStatus . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	/*---------------------TRATAMIENTOS-----------------*/

	//Obtiene el tratamiento por id
	public static function getTreatmentById($id)
	{
		$sql = "SELECT * FROM " . self::$tablenameTreatments . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	public static function getAllTreatments()
	{
		$sql = "SELECT * FROM " . self::$tablenameTreatments . " ORDER BY id";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene el total de tratamientos por tipo para generar un código consecutivo para embriología.
	public static function getAllTreatmentsByType($treatmentId)
	{
		$sql = "SELECT * FROM " . self::$tablenamePatientCategoryTreatments . " 
			WHERE patient_treatment_id = '$treatmentId'
			ORDER BY treatment_code DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene el total de tratamientos por tipo para generar un código consecutivo para embriología.
	public static function getTreatmentsByTypeDates($treatmentId, $startDate, $endDate, $statusId = 0,$isLocalTreatment = 0)
	{
		$sql = "SELECT * FROM " . self::$tablenamePatientCategoryTreatments . " 
			WHERE patient_treatment_id = '$treatmentId' ";
		if (isset($statusId) && $statusId == 1) { //Activos
			$sql .= " AND treatment_status_id = '$statusId' AND start_date <= '$endDate' ";
		} else if (isset($statusId)) { //Finalizados-Cancelados
			$sql .= " AND treatment_status_id = '$statusId' AND end_date >= '$startDate' AND end_date <= '$endDate' ";
		}
		if($isLocalTreatment == 1){
			$sql .= " AND treatment_code IS NOT NULL ";
		}
		$sql .= " ORDER BY treatment_code DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene el total de tratamientos por tipo para generar un código consecutivo para embriología (si es tratamiento de embriología tiene un ).
	public static function getTotalTreatmentsByType($treatmentId)
	{
		$sql = "SELECT COUNT(id) AS total_treatments 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			WHERE patient_treatment_id = '$treatmentId'
			AND treatment_code IS NOT NULL AND treatment_code != ''";

		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	/*---------------------SUB TRATAMIENTOS/EMBRIOLOGÍA--------------- */
	//Obtiene el subtratamiento de un tratamiento en específico
	public static function getSubTreatmentById($primaryTreatmentId)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".*," . self::$tablenameCategories . ".name AS category_name,
			" . self::$tablenameTreatments . ".name as treatment_name," . self::$tablenameTreatments . ".embryology_procedure_code
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameCategories . " ON " . self::$tablenameCategories . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_category_id
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			WHERE " . self::$tablenamePatientCategoryTreatments . ".primary_treatment_id = $primaryTreatmentId 
			LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	/*--------------------PROCEDIMIENTOS DE EMBRIOLOGÍA---------------------*/
	public static function getAllEmbryologyProcedureTreatments()
	{
		//Obtiene todos los tratamientos que han tenido los pacientes (excepto los cancelados status_id(3)) para mostrarlos en la sección de embriología.
		//Los tratamientos no tienen que tener el código de embriología ya que en la sección se registrará para que se generen en orden
			$sql = "SELECT pct.*, t.name AS treatment_name,
				t.embryology_procedure_code
				FROM " . self::$tablenamePatientCategoryTreatments . " pct
				INNER JOIN " . self::$tablenameTreatments . " t ON  t.id = pct.patient_treatment_id
				AND t.is_embryology_procedure = '1' 
				WHERE pct.patient_category_id = '3' 
				AND pct.treatment_status_id != 3
				AND pct.treatment_location_id = 1
				ORDER BY pct.id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	public function updateEmbryologyCode()
	{
		$sql = "UPDATE " . self::$tablenamePatientCategoryTreatments . " SET treatment_code=\"$this->treatment_code\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	/*Actualiza las observaciones del procedimiento de embriología */
	public function updateEmbryologyProcedureObservations()
	{
		$sql = "UPDATE " . self::$tablenamePatientCategoryTreatments . " SET embryology_procedure_observations=\"$this->embryology_procedure_observations\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	//Obtiene los tratamientos a los cuales la sección de embriología les dará seguimiento .
	public static function getEmbryologyProcedureTreatments()
	{
		$sql = "SELECT * FROM " . self::$tablenameTreatments . " 
		WHERE is_embryology_procedure = 1
		ORDER BY name";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	/*--------------------------NOTIFICACIONES DE PRUEBA DE EMBARAZO ----------------------*/
	public static function getFuturePregnancyTestsNotificationsLimit($limit, $date)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".*, 
 			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date,'%d/%m/%Y') as pregnancy_test_date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id
 
			WHERE " . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '2'  
			AND   " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date <= '$date' 
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date DESC,total_notifications ASC
			LIMIT $limit";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene el total de pruebas pendientes de embarazo por fecha.
	public static function getTotalFuturePregnancyTests($date)
	{
		$sql = "SELECT count(" . self::$tablenamePatientCategoryTreatments . ".id) AS total,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id 
			WHERE " . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '2'
			AND   " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date <= '$date'
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date DESC";

		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	//Obtiene el detalle de las pruebas pendientes de embarazo por fecha
	public static function getFuturePregnancyTestsNotifications($date)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".*, 
 			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date,'%d/%m/%Y') as pregnancy_test_date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id
 
			WHERE " . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '2'
			AND   " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date <= '$date'    
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	/*----------------REPORTES POR ESTATUS DE LOS TRATAMIENTOS-------------*/

	//Obtiene los tratamientos activos y que están pendientes de la prueba de embarazo de los pacientes de un rango de edad y en un rango de fechas.
	public static function getActualTreatmentsByDateAge($startDate, $endDate, $startAge, $endAge)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".id, " . self::$tablenamePatientCategoryTreatments . ".patient_id,
			" . self::$tablenamePatientCategoryTreatments . ".treatment_code AS embryology_treatment_code, 
			" . self::$tablenamePatientCategoryTreatments . ".start_date, 
			" . self::$tablenameTreatments . ".name AS treatment_name, " . self::$tablenameTreatments . ".code AS treatment_code, 
			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient ON " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id
			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_category_id = 3
			AND " . self::$tablenamePatientCategoryTreatments . ".start_date >= '$startDate' AND " . self::$tablenamePatientCategoryTreatments . ".start_date <= '$endDate'
			AND (" . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '1' OR " . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '2') 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na,CURDATE()) >= '$startAge' 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na,CURDATE()) <= '$endAge'
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene el detalle de los tratamientos que están activos de cierto tipo y que está pendiente la prueba de embarazo de los pacientes de un rango de edad y en un rango de fechas.
	public static function getActualTreatmentsByDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId)
	{
		$sql = "SELECT " . self::$tablenamePatientCategoryTreatments . ".id, " . self::$tablenamePatientCategoryTreatments . ".patient_id, 
			" . self::$tablenamePatientCategoryTreatments . ".treatment_code AS embryology_treatment_code,
			" . self::$tablenamePatientCategoryTreatments . ".start_date, 
			" . self::$tablenameTreatments . ".name AS treatment_name, " . self::$tablenameTreatments . ".code AS treatment_code, 
			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id

			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_category_id = 3
			AND " . self::$tablenamePatientCategoryTreatments . ".start_date >= '$startDate' AND " . self::$tablenamePatientCategoryTreatments . ".start_date <= '$endDate'
			AND (" . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '1' OR " . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '2') 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na,CURDATE()) >= '$startAge' 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na,CURDATE()) <= '$endAge'
			AND " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id = '$treatmentId' 
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene los tratamientos finalizados dependiendo del resultado de la prueba de embarazo (éxitos y fallos), sólo en aquellos que hubo transferencia de embriones o que es prueba obligatoria.
	public static function getTreatmentsByPregnancyResultDateAge($startDate, $endDate, $startAge, $endAge, $pregnancyResult)
	{
		$sql = "SELECT  " . self::$tablenamePatientCategoryTreatments . ".id, " . self::$tablenamePatientCategoryTreatments . ".patient_id, 
			" . self::$tablenamePatientCategoryTreatments . ".treatment_code AS embryology_treatment_code,
			" . self::$tablenamePatientCategoryTreatments . ".start_date," . self::$tablenamePatientCategoryTreatments . ".end_date,  
			" . self::$tablenameTreatments . ".name AS treatment_name, " . self::$tablenameTreatments . ".code AS treatment_code, 
			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id

			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_category_id = 3
			AND " . self::$tablenamePatientCategoryTreatments . ".start_date >= '$startDate' AND " . self::$tablenamePatientCategoryTreatments . ".start_date <= '$endDate'
			AND (" . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '4' AND " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_result = '$pregnancyResult')   
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) >= '$startAge' 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) <= '$endAge' 
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene los tratamientos finalizados dependiendo del resultado de la prueba de embarazo (éxitos y fallos), sólo en aquellos que hubo transferencia de embriones o que es prueba obligatoria.
	public static function getTreatmentsByPregnancyResultDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId, $pregnancyResult)
	{
		$sql = "SELECT  " . self::$tablenamePatientCategoryTreatments . ".id, " . self::$tablenamePatientCategoryTreatments . ".patient_id, 
			" . self::$tablenamePatientCategoryTreatments . ".treatment_code AS embryology_treatment_code,
			" . self::$tablenamePatientCategoryTreatments . ".start_date, " . self::$tablenamePatientCategoryTreatments . ".end_date,  
			" . self::$tablenameTreatments . ".name AS treatment_name, " . self::$tablenameTreatments . ".code AS treatment_code, 
			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id

			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_category_id = 3
			AND " . self::$tablenamePatientCategoryTreatments . ".start_date >= '$startDate' AND " . self::$tablenamePatientCategoryTreatments . ".start_date <= '$endDate'
			AND (" . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '4' AND " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_result = '$pregnancyResult')   
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) >= '$startAge' 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) <= '$endAge' 
			AND " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id = '$treatmentId' 
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}



	//Obtiene los tratamientos finalizados dependiendo del resultado de la prueba de embarazo (éxitos y fallos), sólo en aquellos que hubo transferencia de embriones o que es prueba obligatoria.
	public static function getNonTransferTreatmentsByDateAge($startDate, $endDate, $startAge, $endAge)
	{
		$sql = "SELECT  " . self::$tablenamePatientCategoryTreatments . ".id, " . self::$tablenamePatientCategoryTreatments . ".patient_id,
			" . self::$tablenamePatientCategoryTreatments . ".treatment_code AS embryology_treatment_code,
			" . self::$tablenamePatientCategoryTreatments . ".start_date," . self::$tablenamePatientCategoryTreatments . ".end_date,  
			" . self::$tablenameTreatments . ".name AS treatment_name, " . self::$tablenameTreatments . ".code AS treatment_code, 
			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id

			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_category_id = 3
			AND " . self::$tablenamePatientCategoryTreatments . ".start_date >= '$startDate' AND " . self::$tablenamePatientCategoryTreatments . ".start_date <= '$endDate'
			AND (" . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '4' AND " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date IS NULL)   
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) >= '$startAge' 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) <= '$endAge' 
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene los tratamientos finalizados dependiendo del resultado de la prueba de embarazo (éxitos y fallos), sólo en aquellos que hubo transferencia de embriones o que es prueba obligatoria.
	public static function getNonTransferTreatmentsByDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId)
	{
		$sql = "SELECT  " . self::$tablenamePatientCategoryTreatments . ".id, " . self::$tablenamePatientCategoryTreatments . ".patient_id, 
			" . self::$tablenamePatientCategoryTreatments . ".treatment_code AS embryology_treatment_code,
			" . self::$tablenamePatientCategoryTreatments . ".start_date, " . self::$tablenamePatientCategoryTreatments . ".end_date,  
			" . self::$tablenameTreatments . ".name AS treatment_name, " . self::$tablenameTreatments . ".code AS treatment_code, 
			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id

			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_category_id = 3
			AND " . self::$tablenamePatientCategoryTreatments . ".start_date >= '$startDate' AND " . self::$tablenamePatientCategoryTreatments . ".start_date <= '$endDate'
			AND (" . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '4' AND " . self::$tablenamePatientCategoryTreatments . ".pregnancy_test_date IS NULL)   
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) >= '$startAge' 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) <= '$endAge' 
			AND " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id = '$treatmentId' 
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene los tratamientos cancelados por fecha y edad
	public static function getCanceledTreatmentsByDateAge($startDate, $endDate, $startAge, $endAge)
	{
		$sql = "SELECT  " . self::$tablenamePatientCategoryTreatments . ".id, " . self::$tablenamePatientCategoryTreatments . ".patient_id, 
			" . self::$tablenamePatientCategoryTreatments . ".treatment_code AS embryology_treatment_code,
			" . self::$tablenamePatientCategoryTreatments . ".start_date, " . self::$tablenamePatientCategoryTreatments . ".end_date,  
			" . self::$tablenameTreatments . ".name AS treatment_name, " . self::$tablenameTreatments . ".code AS treatment_code, 
			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id

			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_category_id = 3
			AND " . self::$tablenamePatientCategoryTreatments . ".start_date >= '$startDate' AND " . self::$tablenamePatientCategoryTreatments . ".start_date <= '$endDate'
			AND " . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '3'  
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) >= '$startAge' 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) <= '$endAge' 
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	//Obtiene los tratamientos cancelados por fecha,edad y un tratamiento específico
	public static function getCanceledTreatmentsByDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId)
	{
		$sql = "SELECT  " . self::$tablenamePatientCategoryTreatments . ".id, " . self::$tablenamePatientCategoryTreatments . ".patient_id, 
			" . self::$tablenamePatientCategoryTreatments . ".treatment_code AS embryology_treatment_code,
			" . self::$tablenamePatientCategoryTreatments . ".start_date, " . self::$tablenamePatientCategoryTreatments . ".end_date,  
			" . self::$tablenameTreatments . ".name AS treatment_name, " . self::$tablenameTreatments . ".code AS treatment_code, 
			pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".start_date,'%d/%m/%Y') as start_date_format,
			CONCAT(ELT(WEEKDAY(" . self::$tablenamePatientCategoryTreatments . ".end_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS end_date_day_name, 
			DATE_FORMAT(" . self::$tablenamePatientCategoryTreatments . ".end_date,'%d/%m/%Y') as end_date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = " . self::$tablenamePatientCategoryTreatments . ".end_date AND patient_id = pacient.id AND notification_module_id = 2) AS total_notifications 
			FROM " . self::$tablenamePatientCategoryTreatments . " 
			INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id
			INNER JOIN pacient on " . self::$tablenamePatientCategoryTreatments . ".patient_id = pacient.id

			WHERE " . self::$tablenamePatientCategoryTreatments . ".patient_category_id = 3
			AND " . self::$tablenamePatientCategoryTreatments . ".start_date >= '$startDate' AND " . self::$tablenamePatientCategoryTreatments . ".start_date <= '$endDate'
			AND " . self::$tablenamePatientCategoryTreatments . ".treatment_status_id = '3'  
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) >= '$startAge' 
			AND TIMESTAMPDIFF(YEAR,pacient.fecha_na," . self::$tablenamePatientCategoryTreatments . ".end_date) <= '$endAge' 
			AND " . self::$tablenamePatientCategoryTreatments . ".patient_treatment_id = '$treatmentId' 
			ORDER BY " . self::$tablenamePatientCategoryTreatments . ".end_date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}
	/*----------------REPORTES POR ESTATUS DE LOS TRATAMIENTOS-------------*/

	/*----------------PAREJA DEL TRATAMIENTO-------------*/
	public function getPartnerData()
	{
		//Se muestra el dato oficial de la pareja del paciente cuando no tiene un registro de paciente independiente.
		$relativeData = new stdClass();
		$relativeData->officialDocumentName = "Dato oficial";
		$relativeData->officialDocumentValue = "No especificado";

		$procedurePartner = PatientProcedurePartnerData::getTreatmentPartner($this->id);
		if ($procedurePartner) {
			$partnerPatientData = PatientData::getById($procedurePartner->partner_id);
			if (isset($procedurePartner->partner_id) && $partnerPatientData) { //La pareja es un paciente registrado
				$relativeData->id = $partnerPatientData->id;
				$relativeData->name = $partnerPatientData->name;
				$relativeData->birthdayFormat = $partnerPatientData->getBirthdayFormat();
				$relativeData->age = $partnerPatientData->getAgeByDate($this->start_date);
				$relativeData->officialDocumentName = $partnerPatientData->getPatientOfficialData()->name;
				$relativeData->officialDocumentValue = $partnerPatientData->getPatientOfficialData()->value;
			} else { //La pareja no está registrada
				$relativeData->id = "";
				$relativeData->name = $procedurePartner->name;
				$relativeData->birthdayFormat = ConfigurationData::getDateMonthFormat($procedurePartner->birthday);
				$relativeData->age = ConfigurationData::getAgeByDate($procedurePartner->birthday, $this->start_date);
				if ($procedurePartner->official_document_id) {
					$officialData = OfficialDocumentData::getById($procedurePartner->official_document_id);
					$relativeData->officialDocumentName = $officialData->name;
					$relativeData->officialDocumentValue = $procedurePartner->official_document_value;
				}
			}
		} else {
			$relativeData->id = "";
			$relativeData->name = "No especificado";
			$relativeData->birthdayFormat  = "No especificada";
			$relativeData->age = "No especificada";
		}

		return $relativeData;
	}

	/*----------------PAREJA DEL TRATAMIENTO-------------*/
}
