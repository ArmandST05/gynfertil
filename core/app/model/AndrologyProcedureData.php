<?php
class AndrologyProcedureData
{
	public static $tablename = "andrology_procedures";
	public static $tablenamePatientDetails = "patient_andrology_procedures";
	public static $tablenameSectionDetails = "procedure_section_details";
	public static $tablenameDetails = "patient_procedure_details";
	public static $tablenameFiles = "patient_procedure_files";
	public static $tablenameSemenDevices = "andrology_treatment_devices";
	public static $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

	public static $tablenamePartners = "patient_procedure_partners";
	public function __construct()
	{
		$this->andrology_procedure_id = "";
		$this->procedure_code = "";
		$this->patient_id = "";
		$this->date = "";
		$this->observations = "";
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

	public function getMedic()
	{
		return MedicData::getById($this->medic_id);
	}

	public function getPatient()
	{
		return PatientData::getById($this->patient_id);
	}

	/*---------------------PROCEDIMIENTOS DE ANDROLOGÍA----------------------------*/
	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (name)";
		$sql .= "value (\"$this->name\")";

		return Executor::doit($sql);
	}

	public function update()
	{
		$sql = "UPDATE " . self::$tablename . " set name=\"$this->name\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id=$this->id";
		Executor::doit($sql);
	}

	//Obtiene un de procedimientos de andrología por su id
	public static function getById($id)
	{
		$sql = "SELECT " . self::$tablename . ".*
		FROM " . self::$tablename . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new AndrologyProcedureData());
	}

	//Obtiene una lista de procedimientos de andrología disponibles para los pacientes
	public static function getAll()
	{
		$sql = "SELECT * FROM " . self::$tablename . " ORDER BY ordering DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new AndrologyProcedureData());
	}

	//Obtiene una lista de procedimientos de andrología dependiendo del sexo. 
	//Existen procedimientos de andrología que aplican para hombres y otros para mujeres (IIUD)
	public static function getBySex($sexId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " 
		WHERE sex_id = '$sexId' ORDER BY ordering DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new AndrologyProcedureData());
	}

	/*--------------------------PROCEDIMIENTOS DE ANDROLOGÍA POR PACIENTE---------------------------------- */
	public function addProcedureByPatient()
	{
		$sql = "INSERT INTO " . self::$tablenamePatientDetails . " (andrology_procedure_id,primary_procedure_id,medic_id,patient_id,procedure_code,date)";
		$sql .= " value (\"$this->andrology_procedure_id\",\"$this->primary_procedure_id\",\"$this->medic_id\",\"$this->patient_id\",\"$this->procedure_code\",\"$this->date\")";
		return Executor::doit($sql);
	}

	public static function getAllProcedures()
	{
		//Obtiene todos los tratamientos que han tenido los pacientes para mostrarlos en la sección de embriología.
		$sql = "SELECT " . self::$tablenamePatientDetails . ".*, " . self::$tablename . ".name AS procedure_name,
			" . self::$tablename . ".code AS andrology_procedure_code
			FROM " . self::$tablenamePatientDetails . " 
			INNER JOIN " . self::$tablename . " ON  " . self::$tablename . ".id = " . self::$tablenamePatientDetails . ".andrology_procedure_id
			ORDER BY " . self::$tablenamePatientDetails . ".id DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new AndrologyProcedureData());
	}

	public static function getAllProceduresByTypeDates($procedureId, $startDate, $endDate)
	{
		//Obtiene todos los tratamientos que han tenido los pacientes para mostrarlos en la sección de embriología.
		$sql = "SELECT " . self::$tablenamePatientDetails . ".*, " . self::$tablename . ".name AS procedure_name,
			DATE_FORMAT(" . self::$tablenamePatientDetails . ".date,'%d/%m/%Y') AS date_format,
			" . self::$tablename . ".code AS andrology_procedure_code,
			p.name AS patient_name
			FROM " . self::$tablenamePatientDetails . " 
			INNER JOIN " . self::$tablename . " ON  " . self::$tablename . ".id = " . self::$tablenamePatientDetails . ".andrology_procedure_id
			INNER JOIN " . PatientData::$tablename . " p ON p.id = " . self::$tablenamePatientDetails . ".patient_id
			WHERE " . self::$tablenamePatientDetails . ".andrology_procedure_id = '$procedureId'
			AND " . self::$tablenamePatientDetails . ".date >= '$startDate' AND " . self::$tablenamePatientDetails . ".date <= '$endDate'
			ORDER BY " . self::$tablenamePatientDetails . ".date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new AndrologyProcedureData());
	}


	public static function getAllPatientProcedures($patientId)
	{
		//Obtiene todos los tratamientos que han tenido los pacientes para mostrarlos en la sección de embriología.
		$sql = "SELECT " . self::$tablenamePatientDetails . ".*,
			" . self::$tablename . ".name AS name,
			DATE_FORMAT(" . self::$tablenamePatientDetails . ".date,'%d/%m/%Y') as date_format,
			" . self::$tablename . ".code AS andrology_procedure_code
			FROM " . self::$tablenamePatientDetails . " 
			INNER JOIN " . self::$tablename . " ON  " . self::$tablename . ".id = " . self::$tablenamePatientDetails . ".andrology_procedure_id
			WHERE " . self::$tablenamePatientDetails . ".patient_id = '$patientId'
			ORDER BY " . self::$tablenamePatientDetails . ".id DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new AndrologyProcedureData());
	}

	//Obtiene el total de procedimientos por tipo para generar un código consecutivo.
	public static function getTotalProceduresByType($procedureId)
	{
		$sql = "SELECT COUNT(id) AS total 
				FROM " . self::$tablenamePatientDetails . " 
				WHERE andrology_procedure_id = '$procedureId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new AndrologyProcedureData());
	}

	//Obtiene los datos del procedimiento del paciente
	public static function getPatientProcedureById($id)
	{
		$sql = "SELECT " . self::$tablenamePatientDetails . ".*,
			" . self::$tablename . ".code AS andrology_procedure_code,
			DATE_FORMAT(" . self::$tablenamePatientDetails . ".date,'%d/%m/%Y') as date_format,
			" . self::$tablename . ".name AS procedure_name,
			p.name as patient_name,p.donor_id AS patient_donor_id
			FROM " . self::$tablenamePatientDetails . " 
			INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenamePatientDetails . ".andrology_procedure_id
			INNER JOIN " . PatientData::$tablename . " p ON p.id = " . self::$tablenamePatientDetails . ".patient_id
			WHERE " . self::$tablenamePatientDetails . ".id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new AndrologyProcedureData());
	}


	//Busca los los tratamientos por código de embriología y por nombre del paciente (se utiliza en select del procedimiento, muestra id (id del tratamiento) y text (código de embriología + paciente))
	public static function getByCodeNameSearch($code)
	{
		$sql = "SELECT " . self::$tablenamePatientDetails . ".id AS id,
			CONCAT(" . self::$tablenamePatientDetails . ".procedure_code,' - ',patients.name) AS text 
			FROM " . self::$tablenamePatientDetails . " 
			INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenamePatientDetails . ".andrology_procedure_id
			INNER JOIN pacient AS patients ON " . self::$tablenamePatientDetails . ".patient_id = patients.id
			WHERE (" . self::$tablenamePatientDetails . ".procedure_code like '%$code%'
			OR patients.name like '%$code%')
			ORDER BY " . self::$tablenamePatientDetails . ".id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientCategoryData());
	}

	public static function getDetailsByProcedure($procedureId, $patientProcedureId)
	{
		$sql = "SELECT " . self::$tablenameSectionDetails . ".id, " . self::$tablenameDetails . ".value
			FROM " . self::$tablenameSectionDetails . " 
			LEFT JOIN " . self::$tablenameDetails . " ON " . self::$tablenameSectionDetails . ".id = " . self::$tablenameDetails . ".procedure_section_detail_id
			AND " . self::$tablenameDetails . ".patient_andrology_procedure_id = '$patientProcedureId'
			WHERE " . self::$tablenameSectionDetails . ".andrology_procedure_id = '$procedureId'
			ORDER BY " . self::$tablenameSectionDetails . ".id";
		$query = Executor::doit($sql);

		$array = array();
		while ($r = $query[0]->fetch_array()) {
			$array[$r['id']] = $r['value'];
		}
		return $array;
	}

	public function addDetail()
	{
		$sql = "INSERT INTO " . self::$tablenameDetails . " (patient_andrology_procedure_id,procedure_section_detail_id,value) ";
		$sql .= "VALUE (\"$this->patient_andrology_procedure_id\",\"$this->procedure_section_detail_id\",\"$this->value\")";
		return Executor::doit($sql);
	}

	public function updateDetail()
	{
		$sql = "UPDATE " . self::$tablenameDetails . " 
			SET value=\"$this->value\" 
			WHERE procedure_section_detail_id = $this->procedure_section_detail_id 
			AND patient_andrology_procedure_id = $this->patient_andrology_procedure_id";
		return Executor::doit($sql);
	}

	public static function getDetail($patientProcedureId, $detailId)
	{
		//Obtiene si ya se registró cierto detalle del procedimiento
		$sql = "SELECT * FROM " . self::$tablenameDetails . "
				WHERE patient_andrology_procedure_id = '$patientProcedureId'
				AND procedure_section_detail_id = '$detailId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new AndrologyProcedureData());
	}

	//Actualiza el nombre del médico que solicitó el estudio/procedimiento 
	public function updatePatientProcedureMedic()
	{
		$sql = "UPDATE " . self::$tablenamePatientDetails . " SET medic_name=\"$this->medic_name\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Actualiza las observaciones del procedimiento 
	public function updatePatientProcedureObservations()
	{
		$sql = "UPDATE " . self::$tablenamePatientDetails . " SET observations=\"$this->observations\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Actualiza el diagnóstico del procedimiento 
	public function updatePatientProcedureDiagnostic()
	{
		$sql = "UPDATE " . self::$tablenamePatientDetails . " SET diagnostic=\"$this->diagnostic\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//------------------IMÁGENES/ARCHIVOS---------------------
	public function addFile()
	{
		$sql = "INSERT INTO " . self::$tablenameFiles . " (patient_andrology_procedure_id,file_section_id,path) ";
		$sql .= "VALUE (\"$this->patient_andrology_procedure_id\",\"$this->file_section_id\",\"$this->path\")";
		return Executor::doit($sql);
	}

	public static function deleteFilesByProcedureSection($patientProcedureId, $sectionId)
	{
		$sql = "DELETE FROM " . self::$tablenameFiles . " 
		 WHERE patient_andrology_procedure_id = '$patientProcedureId'
		 AND file_section_id = '$sectionId'";
		return Executor::doit($sql);
	}

	public static function getFileByProcedureSectionId($patientProcedureId, $sectionId)
	{
		$sql = "SELECT * FROM " . self::$tablenameFiles . " 
		 WHERE patient_andrology_procedure_id = '$patientProcedureId'
		 AND file_section_id = '$sectionId' LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureData());
	}

	/*---------------------SUB-PROCEDIMIENTOS--------------- */
	//Obtiene el subprocedimiento de un procedimiento en específico
	public static function getSubProcedureById($primaryProcedureId)
	{
		$sql = "SELECT " . self::$tablenamePatientDetails . ".*," . self::$tablename . ".name AS procedure_name
			FROM " . self::$tablenamePatientDetails . " 
			INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenamePatientDetails . ".andrology_procedure_id
			WHERE " . self::$tablenamePatientDetails . ".primary_procedure_id = $primaryProcedureId 
			LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientCategoryData());
	}

	/*-------------------DISPOSITIVOS DE SEMEN UTILIZADOS EN TRATAMIENTOS DE EMBRIOLOGÍA-------------------- */

	//Agregar procedimiento de andrología relacionado a tratamiento de fertilidad, para utilizar x dispositivos de semen.
	public function addDeviceByEmbryologyTreatment()
	{
		$sql = "INSERT INTO " . self::$tablenameSemenDevices . " (andrology_procedure_id,embryology_treatment_id,destination_andrology_procedure_id,quantity)";
		$sql .= "value (\"$this->andrology_procedure_id\",\"$this->embryology_treatment_id\",\"$this->destination_andrology_procedure_id\",\"$this->quantity\")";

		return Executor::doit($sql);
	}

	//Obtiene si el procedimiento de andrología ya se relacionó con el de andrología.
	public static function getDeviceByEmbryologyTreatmentId($id)
	{
		$sql = "SELECT * FROM " . self::$tablenameSemenDevices . " 
				WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new AndrologyProcedureData());
	}


	//Eliminar procedimiento de andrología relacionado a tratamiento de fertilidad, para utilizar x dispositivos de semen.
	public function deleteDeviceByEmbryologyTreatment()
	{
		$sql = "DELETE FROM " . self::$tablenameSemenDevices . " WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Obtiene si el procedimiento de andrología ya se relacionó con el de andrología.
	public static function getSemenProceduresByTreatmentProcedureId($patientCategoryTreatmentId, $patientAndrologyProcedureId)
	{
		$sql = "SELECT " . self::$tablenameSemenDevices . ".*
			FROM " . self::$tablenameSemenDevices . " 
			WHERE " . self::$tablenameSemenDevices . ".embryology_treatment_id = '$patientCategoryTreatmentId'
			AND " . self::$tablenameSemenDevices . ".andrology_procedure_id = '$patientAndrologyProcedureId'
			LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new AndrologyProcedureData());
	}

	//Obtiene el detalle de los procedimientos y cantidad de dispositivos que se utilizaron en los tratamientos de embriología.
	public static function getOriginSemenProceduresByTreatmentId($patientCategoryTreatmentId)
	{
		$sql = "SELECT " . self::$tablenameSemenDevices . ".id,
			" . self::$tablenameSemenDevices . ".andrology_procedure_id,
			" . self::$tablenameSemenDevices . ".quantity,
			" . self::$tablenamePatientDetails . ".procedure_code,
			" . self::$tablenamePatientDetails . ".patient_id,
			" . self::$tablenamePatientDetails . ".id AS patient_procedure_id,
			p.name AS patient_name,p.donor_id AS patient_donor_id
			FROM " . self::$tablenameSemenDevices . " 
			INNER JOIN " . self::$tablenamePatientDetails . " ON " . self::$tablenamePatientDetails . ".id = " . self::$tablenameSemenDevices . ".andrology_procedure_id
			INNER JOIN " . PatientData::$tablename . " p ON p.id = " . self::$tablenamePatientDetails . ".patient_id
			WHERE " . self::$tablenameSemenDevices . ".embryology_treatment_id = '$patientCategoryTreatmentId'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new AndrologyProcedureData());
	}

	//Obtiene el detalle de los procedimientos y cantidad de dispositivos que se utilizaron en cierto tratamiento de andrología.
	//Por ejemplo en IIUD que es un tratamiento de andrología se puede utilizar semen de un SPERMFREEZING
	public static function getOriginSemenProceduresByProcedureId($andrologyProcedureId)
	{
		$sql = "SELECT " . self::$tablenameSemenDevices . ".quantity,
			" . self::$tablenamePatientDetails . ".procedure_code,
			" . self::$tablenamePatientDetails . ".patient_id,
			" . self::$tablenamePatientDetails . ".id AS patient_procedure_id
			FROM " . self::$tablenameSemenDevices . " 
			INNER JOIN " . self::$tablenamePatientDetails . " ON " . self::$tablenamePatientDetails . ".id = " . self::$tablenameSemenDevices . ".andrology_procedure_id
			WHERE " . self::$tablenameSemenDevices . ".destination_andrology_procedure_id = '$andrologyProcedureId'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new AndrologyProcedureData());
	}

	//Obtiene el detalle de los tratamientos de fertilidad que utilizaron dispositivos de semen de un tratamiento en específico.
	public static function getUsedSemenProceduresByProcedureId($patientAndrologyProcedureId)
	{
		$sql = "SELECT pt.treatment_code,pp.procedure_code, " . self::$tablenameSemenDevices . ".*
			FROM " . self::$tablenameSemenDevices . " 
			LEFT JOIN " . PatientCategoryData::$tablenamePatientCategoryTreatments . " pt ON pt.id = " . self::$tablenameSemenDevices . ".embryology_treatment_id
			LEFT JOIN " . self::$tablenamePatientDetails . " pp ON pp.id = " . self::$tablenameSemenDevices . ".destination_andrology_procedure_id
			WHERE " . self::$tablenameSemenDevices . ".andrology_procedure_id = '$patientAndrologyProcedureId'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new AndrologyProcedureData());
	}


	/*----------------PAREJA DEL TRATAMIENTO-------------*/
	//Mostrar el detalle de la pareja con la que se registró el procedimiento
	public function getPartnerData()
	{
		//Se muestra el dato oficial de la pareja del paciente cuando no tiene un registro de paciente independiente.
		$relativeData = new stdClass();
		$relativeData->officialDocumentName = "Dato oficial";
		$relativeData->officialDocumentValue = "No especificado";

		$procedurePartner = PatientProcedurePartnerData::getAndrologyProcedurePartner($this->id);
		if ($procedurePartner) {
			$partnerPatientData = PatientData::getById($procedurePartner->partner_id);
			if (isset($procedurePartner->partner_id) && $partnerPatientData) { //La pareja es un paciente registrado
				$relativeData->id = $partnerPatientData->id;
				$relativeData->name = $partnerPatientData->name;
				$relativeData->birthdayFormat = $partnerPatientData->getBirthdayFormat();
				$relativeData->age = $partnerPatientData->getAgeByDate($this->date);
				$relativeData->officialDocumentName = $partnerPatientData->getPatientOfficialData()->name;
				$relativeData->officialDocumentValue = $partnerPatientData->getPatientOfficialData()->value;
			} else { //La pareja no está registrada
				$relativeData->id = "";
				$relativeData->name = $procedurePartner->name;
				$relativeData->birthdayFormat = ConfigurationData::getDateMonthFormat($procedurePartner->birthday);
				$relativeData->age = ConfigurationData::getAgeByDate($procedurePartner->birthday, $this->date);
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
