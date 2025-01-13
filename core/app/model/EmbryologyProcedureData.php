<?php
class EmbryologyProcedureData {
	public static $tablenameDetails = "patient_procedure_details";
	public static $tablename_patient_ovules= "patient_ovules";
	public static $tablenameSectionDetails = "procedure_section_details";
	public static $tablenameFiles = "patient_procedure_files";
	
	public function __construct(){
		$this->id = "";
	}
	
	public function getProcedureOvule(){return PatientOvuleData::getProcedureOvuleById($this->procedure_ovule_id);}//Obtiene los datos del óvulo estando en los procedimientos

	public function addDetail(){
		$sql = "INSERT INTO " . self::$tablenameDetails. " (patient_category_treatment_id,procedure_section_detail_id,value) ";
		$sql .= "VALUE (\"$this->patient_category_treatment_id\",\"$this->procedure_section_detail_id\",\"$this->value\")";
		return Executor::doit($sql);
	}

	public function updateDetail(){
		$sql = "UPDATE ".self::$tablenameDetails." 
			SET value=\"$this->value\" 
			WHERE procedure_section_detail_id = $this->procedure_section_detail_id 
			AND patient_category_treatment_id = $this->patient_category_treatment_id";
		return Executor::doit($sql);
	}

	public static function getDetail($patientCategoryTreatmentId,$detailId){
		//Obtiene si ya se registró cierto detalle del procedimiento
		$sql = "SELECT * FROM " . self::$tablenameDetails. "
				WHERE patient_category_treatment_id = '$patientCategoryTreatmentId'
				AND procedure_section_detail_id = '$detailId'";

		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureData());
	}

	public static function getDetailsByProcedure($treatmentId,$procedureId){
		$sql = "SELECT " . self::$tablenameSectionDetails. ".id, " . self::$tablenameDetails. ".value
			FROM " . self::$tablenameSectionDetails. " 
			LEFT JOIN " . self::$tablenameDetails. " ON " . self::$tablenameSectionDetails. ".id = " . self::$tablenameDetails. ".procedure_section_detail_id
			AND " . self::$tablenameDetails. ".patient_category_treatment_id = '$procedureId'
			WHERE " . self::$tablenameSectionDetails. ".treatment_id = '$treatmentId'
			ORDER BY " . self::$tablenameSectionDetails. ".id";
		$query = Executor::doit($sql);

		$array = array();
		while($r = $query[0]->fetch_array()){
			$array[$r['id']] = $r['value'];
		}
		return $array;
	}

	/*------------IMÁGENES/ARCHIVOS----------- */

	public function addFile()
	{
		$sql = "INSERT INTO " . self::$tablenameFiles . " (patient_category_treatment_id,procedure_ovule_id,file_section_id,path) ";
		$sql .= "VALUE (\"$this->patient_category_treatment_id\",\"$this->procedure_ovule_id\",\"$this->file_section_id\",\"$this->path\")";
		return Executor::doit($sql);
	}

	public static function deleteFilesByTreatmentOvuleSection($treatmentId,$procedureOvuleId,$sectionId){
		$sql = "DELETE FROM ".self::$tablenameFiles." 
		WHERE patient_category_treatment_id = '$treatmentId'
		AND procedure_ovule_id = '$procedureOvuleId'
		AND file_section_id = '$sectionId'";
		return Executor::doit($sql);
	}

	//Obtiene la imagen de un óvulo de un tratamiento
	public static function getFileByTreatmentOvuleSectionId($treatmentId,$procedureOvuleId,$sectionId)
	{
		$sql = "SELECT * FROM " . self::$tablenameFiles . " 
		WHERE patient_category_treatment_id = '$treatmentId'
		AND procedure_ovule_id = '$procedureOvuleId'
		AND file_section_id = '$sectionId' LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureData());
	}

	//Obtiene todas las imágenes de óvulos de un tratamiento
	public static function getFilesByTreatmentSectionId($treatmentId,$sectionId)
	{
		$sql = "SELECT f.*,po.procedure_code,pop.section_id AS procedure_section_id FROM " . self::$tablenameFiles . " f 
		LEFT JOIN ". PatientOvuleData::$tablenameProcedure . " pop ON f.procedure_ovule_id = pop.id
		LEFT JOIN ". PatientOvuleData::$tablename . " po ON pop.patient_ovule_id = po.id
		WHERE f.patient_category_treatment_id = '$treatmentId'
		AND f.procedure_ovule_id != ''
		AND f.file_section_id = '$sectionId'
		ORDER BY pop.section_id,po.procedure_code ASC ";
		$query = Executor::doit($sql);
		return Model::many($query[0], new EmbryologyProcedureData());
	}
	
	/*-----------
	---------CONTENIDO DEL PROCEDIMIENTO (INPUTS) ------------------------*/
	public static function getAllDetailsByProcedureType($procedure_type){
		//Obtiene todos los títulos de las sub columnas de las secciones para la tabla
		$sql = "SELECT " . self::$tablenameSectionDetails . ".* 
			FROM " . self::$tablenameSectionDetails . "
			WHERE " . self::$tablenameSectionDetails . ".status = 1
			ORDER BY " . self::$tablenameSectionDetails . ".section_id," . self::$tablenameSectionDetails . ".row_id," . self::$tablenameSectionDetails . ".ordering ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new EmbryologyProcedureData());
	}

}
