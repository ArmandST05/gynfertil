<?php
class EmbryologyProcedureVitrificationData
{
	public static $tablename = "patient_embryology_procedure_vitrifications";
	public static $tablenameDetails = "patient_embryology_procedure_vitrification_details";
	public static $tablenamePatientOvules = "patient_ovules";

	public function __construct()
	{
		$this->id = "";
		$this->date = "";
		$this->code = "";
		$this->rod = "";
		$this->rod_color = "";
		$this->device_number  = "";
		$this->device_color = "";
		$this->basket = "";
		$this->tank = "";
	}

	public function getTreatment()
	{
		return PatientCategoryData::getTreatmentById($this->patient_category_treatment_id);
	}
	public function getPatientOvule()
	{
		return PatientOvuleData::getById($this->patient_ovule_id);
	}

	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (patient_category_treatment_id,vitrification_type_id) ";
		$sql .= "VALUE (\"$this->patient_category_treatment_id\",\"$this->vitrification_type_id\")";
		return Executor::doit($sql);
	}

	public function addWithAllData()
	{
		$sql = "INSERT INTO " . self::$tablename . " (patient_category_treatment_id,vitrification_type_id,date,code,rod,rod_color,device_number,device_color,basket,tank) ";
		$sql .= "VALUE (\"$this->patient_category_treatment_id\",\"$this->vitrification_type_id\",\"$this->date\",\"$this->code\",\"$this->rod\",\"$this->rod_color\",\"$this->device_number\",\"$this->device_color\",\"$this->basket\",\"$this->tank\")";
		return Executor::doit($sql);
	}

	public function update()
	{
		$sql = "UPDATE " . self::$tablename . " 
			SET code=\"$this->code\",date=\"$this->date\",rod=\"$this->rod\",rod_color=\"$this->rod_color\",device_number=\"$this->device_number\",device_color=\"$this->device_color\",basket=\"$this->basket\",tank=\"$this->tank\"
			WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function updateCode()
	{
		$sql = "UPDATE " . self::$tablename . " SET code=\"$this->code\"
			WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function deleteByTreatmentId($treatmentId)
	{
		$sql = "DELETE FROM " . self::$tablename . " 
		WHERE " . self::$tablename . ".patient_category_treatment_id = $treatmentId";
		Executor::doit($sql);
	}

	public static function getByTreatmentId($patientCategoryTreatmentId)
	{
		$sql = "SELECT * FROM " . self::$tablename . "
				WHERE patient_category_treatment_id = '$patientCategoryTreatmentId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureVitrificationData());
	}

	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . "
				WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureVitrificationData());
	}

	public static function getByCode($code)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE code = '$code'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureVitrificationData());
	}

	public static function getAllCodesByType($vitrificationType = 2)
	{
		$sql = "SELECT * FROM " . self::$tablename . "
				WHERE vitrification_type_id = '$vitrificationType'
				AND code != ''
				ORDER BY code";
		$query = Executor::doit($sql);
		return Model::many($query[0], new EmbryologyProcedureVitrificationData());
	}

	public static function getAllByTypeDates($vitrificationType = 2,$startDate,$endDate)
	{
		//Ya que un registro puede tener varias fechas de congelación, se toma la primera guardada.
		$sql = "SELECT " . self::$tablename . ".*,
				p.name as patient_name,
				(SELECT COUNT(id) FROM " . self::$tablenameDetails . " 
				WHERE " . self::$tablenameDetails . ".patient_embryology_procedure_vitrification_id = " . self::$tablename . ".id) AS total,
				DATE_FORMAT(first_detail.date,'%d/%m/%Y') as first_date_format
				FROM " . self::$tablename . " 
				INNER JOIN ". PatientCategoryData::$tablenamePatientCategoryTreatments ." t ON t.id = " . self::$tablename . ".patient_category_treatment_id
				INNER JOIN ". PatientData::$tablename ." p ON p.id = t.patient_id
				INNER JOIN " . self::$tablenameDetails . " first_detail ON first_detail.id = (SELECT id FROM " . self::$tablenameDetails . " td
				WHERE td.patient_embryology_procedure_vitrification_id = " . self::$tablename . ".id ORDER BY date ASC LIMIT 1)
				WHERE vitrification_type_id = '$vitrificationType'
				AND code != ''
				AND first_detail.date >= '$startDate' AND first_detail.date <= '$endDate'
				ORDER BY first_detail.date";
		$query = Executor::doit($sql);
		return Model::many($query[0], new EmbryologyProcedureVitrificationData());
	}


	/*-------ÓVULO/EMBRIÓN DETALLES----- */
	public function addDetail()
	{
		$sql = "INSERT INTO " . self::$tablenameDetails . " (patient_embryology_procedure_vitrification_id,patient_ovule_id) ";
		$sql .= "VALUE (\"$this->patient_embryology_procedure_vitrification_id\",\"$this->patient_ovule_id\")";
		return Executor::doit($sql);
	}

	public static function updateDetail($id, $columnName, $value)
	{
		$sql = "UPDATE " . self::$tablenameDetails . " SET $columnName=\"$value\" WHERE id = $id";
		return Executor::doit($sql);
	}

	public static function deleteDetailByPatientOvuleId($treatmentId, $patientOvuleId)
	{
		$sql = "DELETE " . self::$tablenameDetails . "  FROM " . self::$tablenameDetails . " 
		INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameDetails . ".patient_embryology_procedure_vitrification_id
		WHERE " . self::$tablename . ".patient_category_treatment_id = $treatmentId
		AND " . self::$tablenameDetails . ".patient_ovule_id = $patientOvuleId";
		Executor::doit($sql);
	}

	public static function deleteDetailsByTreatmentId($treatmentId)
	{
		$sql = "DELETE " . self::$tablenameDetails . "  FROM " . self::$tablenameDetails . " 
		INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameDetails . ".patient_embryology_procedure_vitrification_id
		WHERE " . self::$tablename . ".patient_category_treatment_id = $treatmentId";
		Executor::doit($sql);
	}

	public static function getDetailsByTreatmentId($patientCategoryTreatmentId)
	{
		$sql = "SELECT " . self::$tablenameDetails . ".* 
			FROM " . self::$tablenameDetails . "	
			INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameDetails . ".patient_embryology_procedure_vitrification_id
			INNER JOIN " . self::$tablenamePatientOvules . " ON " . self::$tablenamePatientOvules . ".id = " . self::$tablenameDetails . ".patient_ovule_id
			WHERE " . self::$tablename . ".patient_category_treatment_id = '$patientCategoryTreatmentId'
			ORDER BY " . self::$tablenameDetails . ".date," . self::$tablenamePatientOvules . ".procedure_code ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new EmbryologyProcedureVitrificationData());
	}

	public static function validateDetailByTreatmentIdPatientOvule($patientCategoryTreatmentId,$patientOvuleId)
	{
		$sql = "SELECT " . self::$tablenameDetails . ".* 
			FROM " . self::$tablenameDetails . "
			INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameDetails . ".patient_embryology_procedure_vitrification_id
			INNER JOIN " . self::$tablenamePatientOvules . " ON " . self::$tablenamePatientOvules . ".id = " . self::$tablenameDetails . ".patient_ovule_id
			WHERE " . self::$tablename . ".patient_category_treatment_id = '$patientCategoryTreatmentId'
			AND " . self::$tablenameDetails . ".patient_ovule_id = '$patientOvuleId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureVitrificationData());
	}

		//Obtiene el total de códigos generados por tipo de vitrificación.
		public static function getTotalVitrificationsByType($vitrificationType)
		{
			$sql = "SELECT COUNT(id) AS total 
				FROM " . self::$tablename . " 
				WHERE " . self::$tablename . ".code != '' AND vitrification_type_id = '$vitrificationType'";
	
			$query = Executor::doit($sql);
			return Model::one($query[0], new PatientCategoryData());
		}
	
}
