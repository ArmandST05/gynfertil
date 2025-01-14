<?php
class Database {
	public static $db;
	public static $con;
	public static $bdd;

	function __construct(){
  		$this->user="mvillalpando_gynfertil";$this->pass="doctor12#techno0";$this->host="localhost";$this->ddbb="mvillalpando_doctor_gynfertil";
	}
	
	function connect(){
		$con = new mysqli($this->host,$this->user,$this->pass,$this->ddbb);
		$con->query("set sql_mode=''");
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}	

	function connectPdo(){
		return new PDO('mysql:host='.$this->host.';dbname='.$this->ddbb.';charset=utf8',$this->user,$this->pass);
		/*
		try
		{
			$bdd = new PDO('mysql:host=192.185.5.139;dbname=mvillalp_doctor_gynfertil_test;charset=utf8','mvillalp_master','technoAdmin$20');
		}
		catch(Exception $e)
		{
			die('Erreur : '.$e->getMessage());
		}
		*/
	}

	public static function getConPdo(){
		self::$db = new Database();
		self::$bdd = self::$db->connectPdo();
		return self::$bdd;
	}	
}
