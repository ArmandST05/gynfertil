<?php
class ConfigurationData {
	public static $tablename = "configuration";

	public function __construct(){
		$this->name = "";
		$this->description = "";
		$this->value = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "INSERT INTO ".self::$tablename." (name,description,kind,value) ";
		$sql .= "value (\"$this->name\",\"$this->description\",\"$this->kind\",\"$this->value\")";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "UPDATE ".self::$tablename." set value=\"$this->value\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public static function delete($id){
		$sql = "DELETE FROM ".self::$tablename." WHERE id = $id";
		Executor::doit($sql);
	}

	public static function getAll(){
		//Crear array con el nombre de la configuración para acceder a los datos con mayor facilidad.
		$sql = "SELECT * FROM ".self::$tablename;
		$query = Executor::doit($sql);
		$array = array();
		while($r = $query[0]->fetch_array()){
			$array[$r['name']] = new ConfigurationData();
			$array[$r['name']]->id = $r['id'];
			$array[$r['name']]->name = $r['name'];
			$array[$r['name']]->description = $r['description'];
			$array[$r['name']]->kind = $r['kind'];
			$array[$r['name']]->value = $r['value'];
		}
		return $array;
	}

	public static function getById($id){
		$sql = "SELECT * FROM ".self::$tablename." WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ConfigurationData());
	}

	public static function getByName($name){
		$sql = "SELECT * FROM ".self::$tablename." WHERE name = '$name'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ConfigurationData());
	}

	public static function getLike($q){
		$sql = "SELECT * FROM ".self::$tablename." WHERE name like '%$q%'";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new ConfigurationData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->mail = $r['mail'];
			$array[$cnt]->password = $r['password'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}
}

?>