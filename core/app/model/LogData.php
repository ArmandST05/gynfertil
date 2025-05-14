<?php
class LogData {
	public static $tablename = "logs";

	public function __construct(){
		$this->name = "";
		$this->is_active = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}

	public function getUser(){ 
		return UserData::getById($this->user_id); 
	}

	public function getBranchOffice(){ 
		return BranchOfficeData::getById($this->branch_office_id); 
	}

	public function getModule(){ 
		return LogModuleData::getById($this->log_module_id); 
	}

	public function getActionType(){ 
		return LogActionTypeData::getById($this->action_type_id); 
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (row_id,branch_office_id,user_id,module_id,action_type_id,description) ";
		$sql .= "VALUE (\"$this->row_id\",\"$this->branch_office_id\",\"$this->user_id\",\"$this->module_id\",$this->action_type_id,\"$this->description\")";
		return Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new LogData());
	}

	public static function getAllByDates($startDate,$endDate,$branchOfficeId,$userId,$moduleId,$actionTypeId){
		$startDateTime = $startDate." 00:00:00";
		$endDateTime = $endDate." 23:59:59";

		$sql = "SELECT DATE_FORMAT(l.created_at,'%d/%m/%Y %r') AS created_at_format,
		bo.name AS branch_office_name,u.name AS user_name,lm.name AS module_name,
		lat.name AS action_type_name,l.description
		FROM ".self::$tablename ." l
		LEFT JOIN ".BranchOfficeData::$tablename ." bo ON bo.id = l.branch_office_id
		INNER JOIN ".UserData::$tablename ." u ON u.id = l.user_id
		INNER JOIN ".LogModuleData::$tablename ." lm ON lm.id = l.module_id
		INNER JOIN ".LogActionTypeData::$tablename ." lat ON lat.id = l.action_type_id
		WHERE l.created_at >= '$startDateTime' 
		AND l.created_at <= '$endDateTime' ";
		
		if($branchOfficeId != 0){
			$sql.= " AND l.branch_office_id = '$branchOfficeId' ";
		}
		if($userId != 0){
			$sql.= " AND l.user_id = '$userId' ";
		}
		if($moduleId != 0){
			$sql.= " AND l.module_id = '$moduleId' ";
		}
		if($actionTypeId != 0){
			$sql.= " AND l.action_type_id = '$actionTypeId' ";
		}
		$sql.= " ORDER BY l.created_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new LogData());
	}
}
?>