<?php
class TreatmentDiagnosticData {
	public static $tablename = "treatment_diagnostics";
	public static $tablename_patient_treatments = "patient_treatment_diagnostics";

	public function __construct(){
		$this->name = "";
	}

	public function addDiagnosticTreatment()
	{
		$sql = "INSERT INTO " . self::$tablename_patient_treatments . " (patient_category_treatment_id,treatment_diagnostic_id,description)";
		$sql .= "value (\"$this->patient_category_treatment_id\",\"$this->treatment_diagnostic_id\",\"$this->description\")";

		return Executor::doit($sql);
	}

	public static function deleteAllTreatmentDiagnostics($patient_category_treatment_id)
	{
		$sql = "DELETE FROM " . self::$tablename_patient_treatments . " WHERE patient_category_treatment_id = $patient_category_treatment_id";
		Executor::doit($sql);
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename."
		ORDER BY name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new TreatmentDiagnosticData());
	}

	public static function getByTreatment($patientCategoryTreatmentId){
		$sql = "SELECT ".self::$tablename_patient_treatments.".*, ".self::$tablename.".name 
			FROM ".self::$tablename_patient_treatments." 
			INNER JOIN ".self::$tablename." ON ".self::$tablename.".id =  ".self::$tablename_patient_treatments.".treatment_diagnostic_id
			WHERE patient_category_treatment_id = '$patientCategoryTreatmentId'";
			$query = Executor::doit($sql);
			return Model::many($query[0],new TreatmentDiagnosticData());
	}

	public static function getByTreatmentString($patientCategoryTreatmentId){
		$diagnostics = "";
		$treatmentDiagnostics = self::getByTreatment($patientCategoryTreatmentId);

		foreach($treatmentDiagnostics as $treatmentDiagnostic){
			$diagnosticName = $treatmentDiagnostic->name;
			if($treatmentDiagnostic->treatment_diagnostic_id == 9 && trim($treatmentDiagnostic->description) != ""){
				$diagnosticName .= " (".$treatmentDiagnostic->description.")";
			}
			$diagnostics .= ($diagnosticName.", ");
		}

		return substr($diagnostics,0,-2);
	}

}
?>