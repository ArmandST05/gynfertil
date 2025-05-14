<?php
class PatientNotificationData {
	public static $tablename = "patient_notifications";

	public function __construct(){
		$this->patient_id = "";
		$this->date = "";
		$this->patient_notification_type_id = "";
	}

	public function getPatient()
	{
		return PatientData::getById($this->patient_id);
	}
	public function getMedic()
	{
		return MedicData::getById($this->medic_id);
	}
	public function getBranchOffice()
	{
		return BranchOfficeData::getById($this->branch_office_id);
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (patient_id,branch_office_id,medic_id,date,patient_notification_type_id) ";
		$sql .= "value (\"$this->patient_id\",\"$this->branch_office_id\",\"$this->medic_id\",\"$this->date\",\"$this->patient_notification_type_id\")";
		return Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set date=\"$this->date\" WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PatientNotificationData());
	}

	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename ." WHERE user_type != 'api' order by name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PatientNotificationData());
	}

	public static function getAllByBranchOfficeDates($startDate,$endDate,$branchOfficeId,$medicId){
		$sql = "SELECT ".self::$tablename ." .*,DATE_FORMAT(date,'%d/%m/%Y') AS format_date 
		FROM ".self::$tablename ." 
		WHERE branch_office_id = '$branchOfficeId' 
		AND date >= '$startDate'
		AND date <= '$endDate' ";
		if($medicId != 0){
			$sql .=" AND medic_id = '$medicId' ";
		}
		$sql .=" ORDER BY date DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0],new PatientNotificationData());
	}

	public static function getByPatientBetweenDates($patientId,$startDate,$endDate){
		$sql = "SELECT n.*,DATE_FORMAT(n.date,'%d/%m/%Y') AS format_date 
		FROM ".self::$tablename ." n 
		WHERE (n.date BETWEEN '$startDate' AND '$endDate')
		AND n.patient_id = '$patientId'
		ORDER BY n.date DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PatientNotificationData());
	}

	public static function deleteById($id){
		$sql = "delete from ".self::$tablename." WHERE id=$id";
		return Executor::doit($sql);
	}

}
