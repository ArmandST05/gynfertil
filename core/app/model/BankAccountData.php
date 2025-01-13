<?php
class BankAccountData {
	public static $tablename = "bank_accounts";

	public function __construct(){
		$this->name = "";
	}

	public function getBank()
	{
		return BankData::getById($this->bank_id);
	}

	public function getCompanyAccount()
	{
		return CompanyAccountData::getById($this->company_account_id);
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (name) ";
		$sql .= "value (\"$this->name\")";
		Executor::doit($sql);
	}

	public function delete(){
		$sql = "DELETE FROM ".self::$tablename." WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." set name=\"$this->name\",is_active=\"$this->is_active\" WHERE id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new BankAccountData());
	}

	public static function getAllByStatus($statusId){
		$sql = "SELECT * FROM ".self::$tablename." WHERE is_active = '$statusId'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BankAccountData());
	}

	public static function getAllByCompanyAccount($companyAccountId){
		$sql = "SELECT * FROM ".self::$tablename." 
		WHERE company_account_id = '$companyAccountId'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BankAccountData());
	}

	public static function getLike($q){
		$sql = "SELECT * FROM ".self::$tablename." WHERE name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BankAccountData());
	}
}
