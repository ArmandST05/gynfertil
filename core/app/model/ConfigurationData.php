<?php
class ConfigurationData {
	public static $tablename = "configuration";
	public static $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

	public function __construct(){
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->created_at = "NOW()";
	}
	//Métodos generales
		
	public static function getDateFormat($date)
	{
		//Obtiene la fecha de nacimiento con el nombre del mes del paciente
		if ($date) {
			$day = substr($date, 8, 2);
			$month = substr($date, 5, 2);
			$year = substr($date, 0, 4);

			return $day . "/" . $month . "/" . $year;
		} else {
			return "No especificada.";
		}
	}

	public static function getDateMonthFormat($date)
	{
		//Obtiene la fecha de nacimiento con el nombre del mes del paciente
		if ($date && $date != "0000-00-00") {
			$day = substr($date, 8, 2);
			$month = substr($date, 5, 2);
			$year = substr($date, 0, 4);

			return $day . "/" . self::$months[$month] . "/" . $year;
		} else {
			return "No especificada.";
		}
	}

	public static function getAgeByDate($birthday,$date)
	{
		//Calcula la edad del paciente en una fecha determinada
		if ($birthday && $birthday != "0000-00-00") {
			//Edad
			$diff = abs(strtotime($date) - strtotime($birthday));
			$years = floor($diff / (365 * 60 * 60 * 24));
			if ($years == 1) {
				return $years = $years . " Año";
			} else {
				return $years = $years . " Años";
			}
		} else {
			return "No especificada.";
		}
	}

	public static function formatFileName($string)
	{
		$string = str_replace(".", "", $string);
		$string = str_replace(" ", "", $string);

		//Reemplazamos la A y a
		$string = str_replace(
			array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
			array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
			$string
		);

		//Reemplazamos la E y e
		$string = str_replace(
			array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
			array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
			$string
		);

		//Reemplazamos la I y i
		$string = str_replace(
			array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
			array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
			$string
		);

		//Reemplazamos la O y o
		$string = str_replace(
			array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
			array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
			$string
		);

		//Reemplazamos la U y u
		$string = str_replace(
			array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
			array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
			$string
		);

		//Reemplazamos la N, n, C y c
		$string = str_replace(
			array('Ñ', 'ñ', 'Ç', 'ç'),
			array('N', 'n', 'C', 'c'),
			$string
		);

		return $string;
	}


	public function add(){
		$sql = "insert into user (name,lastname,email,password,created_at) ";
		$sql .= "value (\"$this->name\",\"$this->lastname\",\"$this->email\",\"$this->password\",$this->created_at)";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	// partiendo de que ya tenemos creado un objecto ConfigurationData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set val=\"$this->val\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new ConfigurationData();
		while($r = $query[0]->fetch_array()){
			$data->id = $r['id'];
			$data->name = $r['name'];
			$data->lastname = $r['lastname'];
			$data->email = $r['email'];
			$data->password = $r['password'];
			$data->created_at = $r['created_at'];
			$found = $data;
			break;
		}
		return $found;
	}

	public static function getByMail($mail){
		$sql = "select * from ".self::$tablename." where email=\"$mail\"";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new ConfigurationData();
			$array[$cnt]->id = $r['id'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->lastname = $r['lastname'];
			$array[$cnt]->email = $r['email'];
			$array[$cnt]->password = $r['password'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getAll(){
		$sql = "SELECT * FROM ".self::$tablename;
		$query = Executor::doit($sql);
		$array = array();
		while($r = $query[0]->fetch_array()){
			$array[$r['short']] = new ConfigurationData();
			$array[$r['short']]->id = $r['id'];
			$array[$r['short']]->short = $r['short'];
			$array[$r['short']]->name = $r['name'];
			$array[$r['short']]->kind = $r['kind'];
			$array[$r['short']]->value = $r['val'];
		}
		return $array;
	}


	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		$array = array();
		while($r = $query[0]->fetch_array()){
			$array[$r['short']] = new ConfigurationData();
			$array[$r['short']]->id = $r['id'];
			$array[$r['short']]->short = $r['short'];
			$array[$r['short']]->name = $r['name'];
			$array[$r['short']]->kind = $r['kind'];
			$array[$r['short']]->value = $r['val'];
		}
		return $array;
	}


}

?>