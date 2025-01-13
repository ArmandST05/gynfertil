<?php
class PatientProcedurePartnerData {
	public static $tablename = "patient_procedure_partners";

	public function __construct(){
		$this->patient_category_treatment_id = "";
		$this->patient_andrology_procedure_id = "";
		$this->partner_id = "";
		$this->name = "";
		$this->birthday = "";
		$this->official_document_id = "";
		$this->official_document_value = "";
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (patient_category_treatment_id,patient_andrology_procedure_id,partner_id,name,birthday,official_document_id,official_document_value) ";
		$sql .= "value (\"$this->patient_category_treatment_id\",\"$this->patient_andrology_procedure_id\",\"$this->partner_id\",\"$this->name\",\"$this->birthday\",\"$this->official_document_id\",\"$this->official_document_value\")";
	
		return Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." SET partner_id=\"$this->partner_id\",name=\"$this->name\",birthday=\"$this->birthday\",official_document_id=\"$this->official_document_id\",official_document_value=\"$this->official_document_value\" 
		WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Obtiene el detalle de la pareja con la que el paciente hizo el procedimiento de embriología.
	public static function getTreatmentPartner($patientTreatmentId)
	{
		$sql = "SELECT * FROM " . self::$tablename . "
					WHERE patient_category_treatment_id = '$patientTreatmentId'
					LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientProcedurePartnerData());
	}
		
	//Obtiene el detalle de la pareja con la que el paciente hizo el procedimiento de andrología.
	public static function getAndrologyProcedurePartner($patientProcedureId)
	{
		$sql = "SELECT * FROM " . self::$tablename . "
					WHERE patient_andrology_procedure_id = '$patientProcedureId'
					LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientProcedurePartnerData());
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PatientProcedurePartnerData());
	}
	
}
