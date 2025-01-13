<?php
class EmbryologyProcedureTransferData {
	public static $tablename = "patient_embryology_procedure_transfers";
	
	public function __construct(){
		$this->id = "";
		$this->date = "";
		$this->hour = "";
		$this->total = "";
		$this->quality = "";
		$this->embryo_id_details  = "";
		$this->gynecologist_id = "";
		$this->sonographer_id = "";
		$this->embryologist_id = "";
		$this->witness_id = "";
		$this->estradiol = "";
		$this->progesterone = "";
		$this->catheter = "";
		$this->catheter_lot = "";
		$this->catheter_expiration = "";
		$this->syringe = "";
		$this->syringe_lot = "";
		$this->syringe_expiration = "";
		$this->observations = "";
	}

	public function add(){
		$sql = "INSERT INTO " . self::$tablename. " (patient_category_treatment_id,date,hour,total,quality,embryo_id_details,gynecologist_id,sonographer_id,embryologist_id,witness_id,estradiol,progesterone,catheter,catheter_lot,catheter_expiration,syringe,syringe_lot,syringe_expiration,observations) ";
		$sql .= "VALUE (\"$this->patient_category_treatment_id\",\"$this->date\",\"$this->hour\",\"$this->total\",\"$this->quality\",\"$this->embryo_id_details\",\"$this->gynecologist_id\",\"$this->sonographer_id\",\"$this->embryologist_id\",\"$this->witness_id\",\"$this->estradiol\",\"$this->progesterone\",\"$this->catheter\",\"$this->catheter_lot\",\"$this->catheter_expiration\",\"$this->syringe\",\"$this->syringe_lot\",\"$this->syringe_expiration\",\"$this->observations\")";
		return Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." 
			SET date=\"$this->date\",hour=\"$this->hour\",total=\"$this->total\",quality=\"$this->quality\",embryo_id_details=\"$this->embryo_id_details\",gynecologist_id=\"$this->gynecologist_id\",
			sonographer_id=\"$this->sonographer_id\",embryologist_id=\"$this->embryologist_id\",witness_id=\"$this->witness_id\",
			estradiol=\"$this->estradiol\",progesterone=\"$this->progesterone\",catheter=\"$this->catheter\",catheter_lot=\"$this->catheter_lot\",catheter_expiration=\"$this->catheter_expiration\",syringe=\"$this->syringe\",syringe_lot=\"$this->syringe_lot\",syringe_expiration=\"$this->syringe_expiration\",observations=\"$this->observations\"
			WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function getByTreatmentId($patientCategoryTreatmentId){
		$sql = "SELECT * FROM " . self::$tablename. "
				WHERE patient_category_treatment_id = '$patientCategoryTreatmentId' LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureTransferData());
	}

	public static function getById($id){
		$sql = "SELECT * FROM " . self::$tablename. " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new EmbryologyProcedureTransferData());
	}

}
