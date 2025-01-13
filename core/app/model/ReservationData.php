<?php
class ReservationData {
	public static $tablename = "reservation";
	public static $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

	public function __construct(){
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->password = "";
		$this->created_at = date("Y-m-d H:i:s");
	}

	public function getPacient(){ return PatientData::getById($this->pacient_id); }
	public function getMedic(){ return MedicData::getById($this->medic_id); }
	public function getStatus(){ return StatusData::getById($this->status_id); }
	public function getPayment(){ return PaymentData::getById($this->payment_id); }
	public function getPac(){ return PatientData::getById1($this->pacient_id); }


	public function getReservationDateFormat(){
		$day = substr($this->date_at, 8, 2);
		$month = substr($this->date_at, 5, 2);
		$year = substr($this->date_at, 0, 4);

		return $day . "/" .self::$months[$month] . "/" . $year; 
	}

	public function add(){
		//Estatus reservación por defecto: 3 (Por definir). El médico posteriormente especifica 1(Asistió) o 2(No asistió)
		//Papanicolaou Prueb: 0 (Boolean No se realizó). El médico posteriormente especifica 1(Boolean Se realizó)
		$sql = "insert into reservation (pacient_id,medic_id,date_at,time_at,user_id,note,created_at,status_reser,asistente,laboratorio,status_reservation_id,papanicolaou_test,color,id_col,date_at_final,negritas)";
		$sql .= "value (\"$this->pacient_id\",\"$this->medic_id\",\"$this->cita\",\"$this->time_at\",\"$this->user_id\",\"$this->note\",\"$this->created_at\",\"$this->pac_est\",\"$this->asistente\",\"$this->laboratorio\",'3','0',\"$this->col\",\"$this->colorr\",\"$this->nuevafecha\",\"$this->negritas\")";
		
		return Executor::doit($sql);
	}

	public function add_doc(){
		$sql = "insert into reservation (pacient_id,date_at,time_at,time_at_final,user_id,note,created_at,color,date_at_final,status_reser)";
		$sql .= "value (\"$this->pacient_id\",\"$this->cita\",\"$this->time_at\",\"$this->time_at_final\",\"$this->user_id\",\"$this->note\",\"$this->created_at\",\"$this->col\",\"$this->cita2\",'0')";
		return Executor::doit($sql);
	}

	public function add_resumen($res){
		$sql = "INSERT INTO resumen_pacient (id_pacient,id_medic,resumen,fecha) VALUES (\"$this->id_paciente\",\"$this->id_medico\",'$res',\"$this->fecha\")";
		//$sql .= "value (\"$this->id_paciente\",\"$this->id_medico\",'$res',NOW())";
		return Executor::doit($sql);
	}

	public function upt_resumen($res){
		$sql = "UPDATE resumen_pacient SET resumen='$res' WHERE id=$this->id_reser";
		//$sql .= "value (\"$this->id_paciente\",\"$this->id_medico\",'$res',NOW())";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto ReservationData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set pacient_id=\"$this->pacient_id\",medic_id=\"$this->medic_id\",date_at=\"$this->date_at\",time_at=\"$this->time_at\",note=\"$this->note\",status_reser=\"$this->pac_est\",laboratorio=\"$this->lab\",color=\"$this->col\",id_col=\"$this->colorr\",date_at_final=\"$this->nuevafecha\",negritas=\"$this->negritas\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_reservation_status(){
		$sql = "update ".self::$tablename." set status_reservation_id=\"$this->status_reservation_id\" where id=$this->id";
		return Executor::doit($sql);
	}

	public function update_reservation_papanicolaou_test(){
		$sql = "update ".self::$tablename." set papanicolaou_test=\"$this->papanicolaou_test\" where id=$this->id";
		return Executor::doit($sql);
	}

	public function update_rdoc(){
		$sql = "update ".self::$tablename." set pacient_id=\"$this->pacient_id\",time_at=\"$this->time_at\",date_at=\"$this->cita\",time_at_final=\"$this->time_at_final\",note=\"$this->note\",color=\"$this->col\",date_at_final=\"$this->cita2\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_asis(){
		$sql = "update ".self::$tablename." set datos=\"$this->datos\",tel=\"$this->tel\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update_usuario(){
		$sql = "update pacient set tipo_usuario=\"$this->pac_est\" where id=$this->pacient_id";
		Executor::doit($sql);
	}

	public function update_resumen($res,$id_pac){
		$sql = "update resumen_pacient set resumen='$res' where id_pacient='$id_pac' ";
		Executor::doit($sql);
	}
	
	public function update_resumen2($res,$id_pac){
		$sql = "update resumen_pacient set resumen='$res' where  id_pacient='$id_pac' ";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select ".self::$tablename.".*, medic.name as medic_name from ".self::$tablename." 
		left join medic on ".self::$tablename.".medic_id = medic.id
		where ".self::$tablename.".id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function getLastPapsTestByPatient($patient_id){
		$sql = "select ".self::$tablename.".*, DATE_FORMAT(date_at, '%d/%m/%Y') date_format, medic.name as medic_name from ".self::$tablename." 
		left join medic on ".self::$tablename.".medic_id = medic.id
		where ".self::$tablename.".pacient_id = $patient_id
		and ".self::$tablename.".papanicolaou_test = '1'  
		and ".self::$tablename.".status_reservation_id = '1'  
		order by ".self::$tablename.".date_at DESC limit 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function get_laboratorio($id){
		$sql = "select * from laboratorios where id='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function get_resumen($id_pacient){
		$sql = "select id,id_pacient,id_medic medic_id,resumen, fecha from resumen_pacient where id_pacient='$id_pacient'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	
	}

	public static function get_resumen_detalle($id_pacient){
		$sql = "select DATE_FORMAT(fecha, '%d/%m/%Y')fecha,id,resumen,id_medic,id_pacient from resumen_pacient where id_pacient='$id_pacient' AND resumen<>'<br>'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function get_primer_resumen_cita($id_patient,$id_medic,$date){
		$sql = "select DATE_FORMAT(fecha, '%d/%m/%Y')fecha,id,resumen,id_medic,id_pacient from resumen_pacient where id_pacient='$id_patient' AND id_medic='$id_medic' AND fecha like '$date%' AND resumen<>'<br>' LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	
	}

		public static function get_resumen_expediente($id){
		$sql = "select * from resumen_pacient where id='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	
	}

	public static function get_resumen_doctor($id_pacient,$id_user){
		$sql = "select id,id_pacient,id_medic medic_id,resumen, fecha from resumen_pacient where id_pacient='$id_pacient' AND id_medic='$id_user'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	
	}

	public static function get_resumen_doctor_de($id_pacient,$id_user){
		$sql = "select id,id_pacient,id_medic medic_id,resumen, fecha from resumen_pacient where id_pacient='$id_pacient' AND id_medic='$id_user'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	
	}

	public static function get_resumen_doc($id_pacient,$id_user,$cat){
		$sql = "select r.id,id_pacient,id_medic medic_id,resumen, fecha,name from resumen_pacient r, medic m where r.id_pacient='$id_pacient' AND (m.category_id='1' OR m.category_id='4' OR m.category_id='6') AND id_medic<>'$id_user' AND m.id=r.id_medic";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	
	}

    public static function get_resumen_doc1($id_pacient,$id_user,$cat){
		$sql = "select r.id,id_pacient,id_medic medic_id,resumen, fecha,name from resumen_pacient r, medic m where r.id_pacient='$id_pacient' AND (m.category_id='1' OR m.category_id='6') AND id_medic<>'$id_user' AND m.id=r.id_medic";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	
	}

	 public static function get_resumen_doc2($id_pacient,$id_user,$cat){
		$sql = "select r.id,id_pacient,id_medic medic_id,resumen, fecha,name from resumen_pacient r, medic m where r.id_pacient='$id_pacient' AND (m.category_id='1' OR m.category_id='4') AND id_medic<>'$id_user' AND m.id=r.id_medic";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	
	}

	public static function getRepeated($pacient_id,$medic_id,$date_at,$time_at,$lab){
		$sql = "select * from ".self::$tablename." where pacient_id=$pacient_id and medic_id=$medic_id and date_at=\"$date_at\" and time_at=\"$time_at\" AND laboratorio=\"$lab\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function getRepeated_lab($date_at,$time_at,$lab){
		$sql = "select * from ".self::$tablename." where date_at=\"$date_at\" and time_at=\"$time_at\" AND laboratorio=\"$lab\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function get_validacion_listanegra($id_cliente){
		$sql = "select * from pacient where id=\"$id_cliente\" and status=3";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function getByMail($mail){
		$sql = "select * from ".self::$tablename." where mail=\"$mail\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function getEvery($fecha1){
		$sql = "select  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,date_at_final,r.time_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas  from reservation r WHERE date_at>='$fecha1' order by date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getEvery_doctor($id_user,$fecha1){
		$sql = "select  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,r.time_at_final,date_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas from reservation r where medic_id='$id_user' AND date_at>='$fecha1' order by date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getEvery_au($fecha1){
	    $sql = "select  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,r.time_at_final,date_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas from reservation r where negritas='1' AND date_at>='$fecha1' order by date_at ASC";
	    $query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename." where date(date_at)>=date(NOW()) order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getAll_filter(){
		$sql = "select * from ".self::$tablename." where date(date_at)>=date(NOW()) order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getAll_filter_reservaciones($na){
		$sql = "select  r.id,r.pacient_id,r.medic_id,r.date_at,r.time_at,r.user_id,r.note,m.name from reservation r,medic m,pacient p where date(date_at)>=date(NOW()) AND r.medic_id=m.id AND p.id=r.pacient_id AND (m.`name` like '%$na%' OR p.`name` like '%$na%') order by date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getAll_filter_reservaciones_historial($na){
	    $sql = "select CONCAT(ELT(WEEKDAY(r.date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia,DATE_FORMAT(r.date_at,'%d/%m/%Y %H:%i')as date_at,r.id,r.pacient_id,r.medic_id,r.time_at,r.user_id,r.note,m.name from reservation r,medic m,pacient p where r.medic_id=m.id AND p.id=r.pacient_id AND (m.`name` like '%$na%' OR p.`name` like '%$na%' OR p.`relative_name` like '%$na%') order by date_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}


public static function getAll_filter_reservaciones_historial_pacient($na){
	    $sql = "select CONCAT(ELT(WEEKDAY(r.date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia,DATE_FORMAT(r.date_at,'%d/%m/%Y %H:%i')as date_at,r.id,r.pacient_id,r.medic_id,r.time_at,r.user_id,r.note from reservation r,pacient p where  p.id=r.pacient_id AND (r.pacient_id='$na') order by id ASC";
	    $query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getEdoCuenta($id){
	    $sql = "SELECT id,total,cash,DATE_FORMAT(created_at,'%d/%m/%Y')as created_at,idPac as pacient_id,idMedic medic_id, `status`,comentarios,CONCAT(ELT(WEEKDAY(created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia FROM sell WHERE idPac='$id' order by id ASC";
	    $query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}



	public static function getAll_filter_reservaciones_doctor($na,$id_user){
		$sql = "select  r.id,r.pacient_id,r.medic_id,r.date_at,r.time_at,r.user_id,r.note,m.name,m.id_usuario  from reservation r,medic m,pacient p where date(date_at)>=date(NOW()) AND r.medic_id=m.id AND p.id=r.pacient_id AND m.id_usuario='$id_user'  AND (m.`name` like '%$na%' OR p.`name` like '%$na%') order by date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

   
	public static function getAll_reser_doctor(){
		$sql = "select  * FROM reservation  WHERE date(date_at)>=date(NOW()) AND id_col=0 order by date_at ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}
	

	public static function getAllPendings(){
		$sql = "select * from ".self::$tablename." where date(date_at)>=date(NOW()) and status_id=1 and payment_id=1 order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}


	public static function getAllByPacientId($id){
		$sql = "select * from ".self::$tablename." where pacient_id=$id order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getAllByMedicId($id){
		$sql = "select * from ".self::$tablename." where medic_id=$id order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getAllStatusDatesReser($fecha1,$fecha2,$status,$name){
		$sql = "SELECT ".self::$tablename.".*, medic.name as medic_name, pacient.name AS patient_name,laboratorios.nombre as laboratory_name,
			sell.id as sell_id, sell.total as sell_total,
			CONCAT(ELT(WEEKDAY(".self::$tablename.".date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS day_name, 
			DATE_FORMAT(".self::$tablename.".date_at,'%d/%m/%Y') as date,
			CONCAT(DATE_FORMAT(".self::$tablename.".date_at,'%h:%i %p'),' - ',DATE_FORMAT(".self::$tablename.".date_at_final,'%h:%i %p')) as hour,
			(SELECT resumen FROM resumen_pacient WHERE ".self::$tablename.".pacient_id = resumen_pacient.id_pacient AND ".self::$tablename.".medic_id = resumen_pacient.id_medic AND resumen_pacient.fecha = DATE_FORMAT(".self::$tablename.".date_at,'%Y-%m-%d') LIMIT 1) AS reservation_note
			FROM ".self::$tablename." 
			LEFT JOIN medic on ".self::$tablename.".medic_id = medic.id
			LEFT JOIN pacient on ".self::$tablename.".pacient_id = pacient.id
			LEFT JOIN laboratorios on ".self::$tablename.".laboratorio = laboratorios.id
			LEFT JOIN sell ON reservation.id = sell.idReser AND sell.id = (SELECT MIN(id) FROM sell WHERE ".self::$tablename.".id = sell.idReser)
 
			WHERE pacient.name like '%$name%'
			AND DATE_FORMAT(".self::$tablename.".date_at,'%Y-%m-%d') >= '$fecha1' AND  DATE_FORMAT(".self::$tablename.".date_at,'%Y-%m-%d') <= '$fecha2' 
			AND ".self::$tablename.".status_reservation_id = '$status' 
			ORDER BY ".self::$tablename.".date_at DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getBySQL($sql){
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getOld(){
		$sql = "select * from ".self::$tablename." where date(date_at)<date(NOW()) order by date_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}
	
	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where title like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function get_clientes_cal(){
		$sql = "select id,TRIM(name)name,TRIM(tel)tel FROM pacient";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function get_clientes_calID($id){
		$sql = "select id,TRIM(name)name,TRIM(tel)tel FROM pacient WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function get_tipo_cal(){
		$sql = "select id,descripcion FROM status_pacient";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function get_tipo_calID($id){
		$sql = "select id,descripcion FROM status_pacient WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	//PAPANICOLAU TEST
	public static function getAllFuturePapsTests($fecha1,$fecha2){
		$sql = "SELECT ".self::$tablename.".*, medic.name as medic_name,
			pacient.id AS patient_id, pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(".self::$tablename.".date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS day_name, 
			DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR) ,'%Y-%m-%d') as date,
			DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR) ,'%d/%m/%Y') as date_format,
			(SELECT COUNT(id) FROM notifications WHERE next_date = DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d')) AS total_notifications
			FROM ".self::$tablename." 
			LEFT JOIN medic on ".self::$tablename.".medic_id = medic.id
			LEFT JOIN pacient on ".self::$tablename.".pacient_id = pacient.id
 
			WHERE DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d') >= '$fecha1' AND DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d') <= '$fecha2' 
			AND ".self::$tablename.".papanicolaou_test = '1'  
			AND ".self::$tablename.".status_reservation_id = '1'  
			ORDER BY ".self::$tablename.".date_at DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getAllExecutedPapsTests($fecha1,$fecha2){
		$sql = "SELECT ".self::$tablename.".*, medic.name as medic_name, 
			pacient.id AS patient_id, pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(".self::$tablename.".date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS day_name, 
			DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR) ,'%Y-%m-%d') as date,
			DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR) ,'%d/%m/%Y') as date_format
			FROM ".self::$tablename." 
			LEFT JOIN medic on ".self::$tablename.".medic_id = medic.id
			LEFT JOIN pacient on ".self::$tablename.".pacient_id = pacient.id
 
			WHERE DATE_FORMAT(".self::$tablename.".date_at,'%Y-%m-%d') >= '$fecha1' AND DATE_FORMAT(".self::$tablename.".date_at,'%Y-%m-%d') <= '$fecha2' 
			AND ".self::$tablename.".papanicolaou_test = '1'  
			AND ".self::$tablename.".status_reservation_id = '1'  
			ORDER BY ".self::$tablename.".date_at DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	//PAPANICOLAU TEST NOTIFICATIONS

	public static function getTotalFuturePapsTests($fecha1){
		$sql = "SELECT count(".self::$tablename.".id) AS total
			FROM ".self::$tablename." 
			LEFT JOIN medic on ".self::$tablename.".medic_id = medic.id
			INNER JOIN pacient on ".self::$tablename.".pacient_id = pacient.id

			WHERE DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d') <= '$fecha1' 
			AND (SELECT COUNT(id) FROM notifications WHERE next_date = DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d')
			AND patient_id = pacient.id AND notification_module_id = 1) = 0
			AND ".self::$tablename.".papanicolaou_test = '1'  
			AND ".self::$tablename.".status_reservation_id = '1'  
			ORDER BY ".self::$tablename.".date_at DESC";

		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	public static function getFuturePapsTestsNotifications($fecha1){
		$sql = "SELECT ".self::$tablename.".*, medic.name as medic_name,
			pacient.id AS patient_id, pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(".self::$tablename.".date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS day_name, 
			DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR) ,'%Y-%m-%d') as date,
			DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR) ,'%d/%m/%Y') as date_format
			FROM ".self::$tablename." 
			LEFT JOIN medic on ".self::$tablename.".medic_id = medic.id
			INNER JOIN pacient on ".self::$tablename.".pacient_id = pacient.id
 
			WHERE DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d') <= '$fecha1' 
			AND (SELECT COUNT(id) FROM notifications WHERE next_date = DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d')
			AND patient_id = pacient.id AND notification_module_id = 1) = 0
			AND ".self::$tablename.".papanicolaou_test = '1'  
			AND ".self::$tablename.".status_reservation_id = '1'  
			ORDER BY ".self::$tablename.".date_at DESC";

		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	public static function getFuturePapsTestsNotificationsLimit($fecha1,$limit){
		$sql = "SELECT ".self::$tablename.".*, medic.name as medic_name, 
			pacient.id AS patient_id, pacient.name AS patient_name,
			CONCAT(pacient.calle,' ',pacient.num,' ',pacient.col) as address, 
			pacient.tel AS patient_tel, pacient.tel2 AS patient_tel2,
			CONCAT(ELT(WEEKDAY(".self::$tablename.".date_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS day_name, 
			DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR) ,'%Y-%m-%d') as date,
			DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR) ,'%d/%m/%Y') as date_format
			FROM ".self::$tablename." 
			LEFT JOIN medic on ".self::$tablename.".medic_id = medic.id
			INNER JOIN pacient on ".self::$tablename.".pacient_id = pacient.id
 
			WHERE DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d') <= '$fecha1' 
			AND (SELECT COUNT(id) FROM notifications WHERE next_date = DATE_FORMAT(DATE_ADD(".self::$tablename.".date_at, INTERVAL 1 YEAR),'%Y-%m-%d')
			AND patient_id = pacient.id AND notification_module_id = 1) = 0
			AND ".self::$tablename.".papanicolaou_test = '1'  
			AND ".self::$tablename.".status_reservation_id = '1'  
			ORDER BY ".self::$tablename.".date_at DESC
			LIMIT $limit";

		$query = Executor::doit($sql);
		return Model::many($query[0],new ReservationData());
	}

	//----------------RESERVATION AREAS-----------
	public static function getReservationAreas(){
		$sql = "SELECT * FROM reservation_areas";
		$query = Executor::doit($sql);
		return Model::many($query[0],new MedicData());
	}

	public static function getReservationAreaById($id){
		$sql = "SELECT * FROM reservation_areas WHERE id = '$id' ";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReservationData());
	}

	//NOTIFICATIONS
	public function add_notification(){
		$sql = "insert into notifications (patient_id,next_date,date,hour,notification_module_id,user_id)";
		$sql .= "value (\"$this->patient_id\",\"$this->next_date\",\"$this->date\",\"$this->hour\",\"$this->notification_module_id\",\"$this->user_id\")";
		return Executor::doit($sql);
	}
}
