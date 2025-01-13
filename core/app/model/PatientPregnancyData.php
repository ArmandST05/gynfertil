<?php
class PatientPregnancyData {
	public static $tablename = "patient_pregnancies";
	public static $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

	public function __construct(){
		$this->created_at = date("Y-m-d H:i:s");
	}

	public function getStartDateFormat(){
		$day = substr($this->start_date, 8, 2);
		$month = substr($this->start_date, 5, 2);
		$year = substr($this->start_date, 0, 4);

		return $day . "/" .self::$months[$month] . "/" . $year; 
	}

	public function getEndDateFormat(){
		$day = substr($this->end_date, 8, 2);
		$month = substr($this->end_date, 5, 2);
		$year = substr($this->end_date, 0, 4);

		return $day . "/" .self::$months[$month] . "/" . $year; 
	}

	public function getPatient(){ return PatientData::getById($this->patient_id); }
	public function getTreatment(){ return PatientCategoryData::getById($this->patient_category_treatment_id); }

	public function add(){
		$sql = "insert into ".self::$tablename." (patient_id,patient_category_treatment_id,pregnancy_type_id,start_date)";
		$sql .= "value (\"$this->patient_id\",\"$this->patient_category_treatment_id\",\"$this->pregnancy_type_id\",\"$this->start_date\")";
		return Executor::doit($sql);
	}

	public function finishPregnancy(){
		$sql = "update ".self::$tablename." set end_date=\"$this->end_date\",is_active='0' where id = $this->id";
		return Executor::doit($sql);
	}

	public function delete(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PatientPregnancyData());
	}
	
	public static function getByPatientId($patient_id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE patient_id = '$patient_id' AND is_active = 1 
		ORDER BY id DESC LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PatientPregnancyData());
	}


	public static function getPregnanciesByTypeDate($fecha1,$fecha2,$pregnancy_type_id){
		$sql = "SELECT ".self::$tablename.".*, 
		CONCAT(ELT(WEEKDAY(" . self::$tablename . ".start_date) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS start_date_day_name,
		DATE_FORMAT(" . self::$tablename . ".start_date,'%d/%m/%Y') as start_date_format
		FROM ".self::$tablename." 
		WHERE start_date >= '$fecha1' AND start_date <= '$fecha2' AND pregnancy_type_id = '$pregnancy_type_id'
		ORDER BY ".self::$tablename.".start_date DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PatientPregnancyData());

	}

}
