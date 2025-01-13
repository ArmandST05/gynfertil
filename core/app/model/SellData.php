<?php
class SellData {
	public static $tablename = "sell";
	public static $tablenamePayments = "pay";
	public static $tablenamePaymentTypes = "typepayment";

	public function __construct(){
		$this->created_at = "NOW()";
	}

	public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (total,discount,user_id,created_at) ";
		$sql .= "value ($this->total,$this->discount,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_re(){
		$sql = "insert into ".self::$tablename." (user_id,operation_type_id,created_at,total) ";
		$sql .= "value ($this->user_id,1,$this->created_at,$this->total)";
		return Executor::doit($sql);
	}

	public function add_sal(){
		$sql = "insert into ".self::$tablename." (user_id,operation_type_id,created_at,total,sal,comentarios) ";
		$sql .= "value ($this->user_id,1,$this->created_at,$this->total,'1',\"$this->note\")";
		return Executor::doit($sql);
	}

	public function updatePaymentInvoiceNumber(){
		$sql = "UPDATE ".self::$tablenamePayments." set invoice_number=\"$this->invoice_number\" WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public static function getPaymentById($id){
		$sql = "SELECT * FROM ".self::$tablenamePayments." WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}

	public static function getAllSell($nom){
		$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name`,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s, pacient p WHERE p.id=s.idPac AND (s.id='$nom' OR p.name like '%$nom%')  AND operation_type_id='2' order by id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	 public static function getAllByPageSell($start_from,$limit){
   	    $sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name`,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s, pacient p WHERE p.id=s.idPac  AND operation_type_id='2' AND  s.id>=$start_from order by id DESC limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllSellFechas($f1,$f2){
		$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name`,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s, pacient p WHERE p.id=s.idPac AND DATE_FORMAT(s.created_at,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(s.created_at,'%Y-%m-%d') <= '$f2' AND s.status=1 AND operation_type_id='2' order by id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllExpFechas($f1,$f2){
		$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s WHERE  DATE_FORMAT(s.created_at,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(s.created_at,'%Y-%m-%d') <= '$f2' AND s.status=1 AND operation_type_id='1' order by id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllSellFechasP($f1,$f2){
		$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name`,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s, pacient p WHERE p.id=s.idPac AND DATE_FORMAT(s.created_at,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(s.created_at,'%Y-%m-%d') <= '$f2' AND s.status=0  AND operation_type_id='2' order by id DESC";
	    $query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getAllSellFechasCir($f1,$f2,$name){
		//$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name`,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s, pacient p WHERE p.id=s.idPac AND s.created_at >= '$f1' AND s.created_at <= '$f2' AND s.status=1 AND operation_type_id='2' order by id DESC";
		$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name`,r.note, CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha
        FROM sell s, pacient p, reservation r WHERE p.id=s.idPac 
        AND s.idReser=r.id AND note like '%$name%'
        AND DATE_FORMAT(s.created_at,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(s.created_at,'%Y-%m-%d') <= '$f2' AND s.status=1 AND operation_type_id='2' order by id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getAllSellFechasPCir($f1,$f2,$name){
		//$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name`,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s, pacient p WHERE p.id=s.idPac AND s.created_at >= '$f1' AND s.created_at <= '$f2' AND s.status=0  AND operation_type_id='2' order by id DESC";
		$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name`,r.note, CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha
        FROM sell s, pacient p, reservation r WHERE p.id=s.idPac  
        AND s.idReser=r.id AND note like '%$name%'
        AND DATE_FORMAT(s.created_at,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(s.created_at,'%Y-%m-%d') <= '$f2' AND s.status=0 AND operation_type_id='2' order by id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllExpFechasP($f1,$f2){
		$sql = "SELECT s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s WHERE  DATE_FORMAT(s.created_at,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(s.created_at,'%Y-%m-%d') <= '$f2' AND s.status=0  AND operation_type_id='1' order by id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

    public static function getCat(){
		$sql = "SELECT id,name FROM product WHERE type='CONCEPTO'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	 public static function getProducts($id){
		$sql = "SELECT id,name FROM product WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}

    public static function getMedic($id){
		$sql = "SELECT name FROM medic WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}
	
   
    public static function getIngresos($id,$fecha){
		$sql = "SELECT p.cash total,s.banco,s.fac FROM pay p,sell s  WHERE  p.idTypePay='$id' AND s.id=p.idSell AND typePay='INGRESOS' AND date like '%$fecha%' ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	 public static function getIngresosUt($id,$f1,$f2){
		$sql = "SELECT p.cash total,s.banco,s.fac FROM pay p,sell s  WHERE  p.idTypePay='$id' AND s.id=p.idSell AND typePay='INGRESOS' AND DATE_FORMAT(date,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(date ,'%Y-%m-%d') <= '$f2'  ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	 public static function getEgresosE($id,$fecha){
		$sql = "SELECT p.cash total,s.banco,s.fac FROM pay p,sell s  WHERE  p.idTypePay='$id' AND s.id=p.idSell AND typePay='EGRESOS' AND date like '%$fecha%' ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	 public static function getEgresosEUt($id,$f1,$f2){
		$sql = "SELECT p.cash total,s.banco,s.fac FROM pay p,sell s  WHERE  p.idTypePay='$id' AND s.id=p.idSell AND typePay='EGRESOS' AND DATE_FORMAT(date,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(date ,'%Y-%m-%d') <= '$f2' ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getTypepayment(){
		$sql = "SELECT * FROM typepayment";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

		public static function getAll_docCor($id){
		$sql = "SELECT name from medic WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PatientData());
	}


	public static function getTypepaymentE(){
		$sql = "SELECT * FROM typepayment WHERE type='1'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllSellDate($fecha){
		$sql = "SELECT product_id,SUM(q)q,SUM(price)price,SUM(price*q)tot,idPac,idMedic,s.status FROM operation o,sell s WHERE o.operation_type_id='2' AND s.status='1' AND o.created_at like '%$fecha%' AND s.id=o.sell_id  GROUP BY product_id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllCorteAll($fecha){
		$sql = "SELECT s.idMedic,s.status,s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,p.`name` ,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha  FROM sell s, pacient p WHERE p.id=s.idPac AND s.created_at like '%$fecha%' AND operation_type_id='2'  Order by s.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllCorteAll2($sell){
		$sql = "SELECT t.`name`,p.cash FROM pay p, typepayment t WHERE t.id=p.idTypePay AND idSell='$sell'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


    public static function getAllSellDateUt($fecha,$fecha2){
		$sql = "SELECT product_id,SUM(q)q,SUM(price)price,idPac,idMedic,s.status FROM operation o,sell s WHERE o.operation_type_id='2' AND s.status='1' AND DATE_FORMAT(o.created_at,'%Y-%m-%d') >= '$fecha' AND  DATE_FORMAT(o.created_at,'%Y-%m-%d') <= '$fecha2' AND s.id=o.sell_id  GROUP BY product_id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllBuyDate($fecha){
		$sql = "SELECT product_id,q,price FROM operation o,sell s WHERE o.operation_type_id='1'  AND s.status='1' AND o.created_at like '%$fecha%' AND s.id=o.sell_id ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getAllBuyDateUt($fecha,$fecha2){
		$sql = "SELECT product_id,q,price FROM operation o,sell s WHERE o.operation_type_id='1'  AND s.status='1' AND DATE_FORMAT(o.created_at,'%Y-%m-%d') >= '$fecha' AND  DATE_FORMAT(o.created_at,'%Y-%m-%d') <= '$fecha2'  AND s.id=o.sell_id ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllSellDateR($id,$fecha){
		$sql = "SELECT product_id,SUM(q)q,SUM(price)price,idPac,idMedic FROM operation o,sell s WHERE o.operation_type_id='2' AND o.created_at like '%$fecha%' AND s.id=o.sell_id AND product_id='$id' AND s.status='1' GROUP BY product_id,idMedic";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}


	public static function getAllSellDateRUt($id,$f1,$f2){
		$sql = "SELECT product_id,SUM(q)q,SUM(price)price,SUM(o.price*o.q)total,idPac,idMedic FROM operation o,sell s WHERE o.operation_type_id='2' AND DATE_FORMAT(o.created_at,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(o.created_at,'%Y-%m-%d') <= '$f2'   AND s.status='1' AND s.id=o.sell_id AND product_id='$id' GROUP BY product_id,idMedic";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllSellDateByMedicProduct($productId,$medicId,$f1,$f2){
		$sql = "SELECT s.idPac,s.created_at,o.price,o.q,(o.price*o.q)total
				FROM operation o,sell s 
				WHERE o.operation_type_id='2' 
				AND DATE_FORMAT(o.created_at,'%Y-%m-%d') >= '$f1' 
				AND  DATE_FORMAT(o.created_at,'%Y-%m-%d') <= '$f2' 
				AND s.status='1' 
				AND s.id=o.sell_id 
				AND product_id='$productId'
				AND idMedic = '$medicId'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCatePro($id){
		$sql = "SELECT idCat FROM product WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}

	public static function getdetMed($f1,$f2){
		$sql = "SELECT DATE_FORMAT(o.created_at,'%Y-%m-%d'),product_id,SUM(q)q,SUM(o.price)price,SUM(o.price*o.q)total
                FROM operation o, product p, sell s WHERE o.operation_type_id='2' 
                AND DATE_FORMAT(s.created_at,'%Y-%m-%d') >= '$f1' AND  DATE_FORMAT(s.created_at,'%Y-%m-%d') <= '$f2'
                AND p.id=o.product_id AND p.idCat=8
                AND s.`status`='1' AND  s.id=o.sell_id GROUP BY o.product_id,p.idCat";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getDetSellMedicineId($productId,$f1,$f2){
		$sql = "SELECT DATE_FORMAT(s.created_at,'%Y-%m-%d'),
				s.created_at,o.price,o.q,
				o.product_id,
				(o.price*o.q)total,s.idPac
                FROM operation o, product p, sell s 
				WHERE o.operation_type_id='2' AND DATE_FORMAT(s.created_at,'%Y-%m-%d') >= '$f1' 
				AND DATE_FORMAT(s.created_at,'%Y-%m-%d') <= '$f2'
                AND p.id=o.product_id 
				AND p.idCat=8
				AND o.product_id = '$productId'
                AND s.`status`='1' 
				AND s.id=o.sell_id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getdetMedD($f1){
		$sql = "SELECT product_id,SUM(o.q)q,(o.price)price,SUM(o.price*o.q)total
                FROM operation o, product p, sell s WHERE o.operation_type_id='2' 
                AND o.created_at like '%$f1%' AND p.id=o.product_id AND p.idCat=8
                AND s.`status`='1' AND  s.id=o.sell_id GROUP BY o.product_id,p.idCat";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

    public static function getNameCat($id){
		$sql = "SELECT name FROM category_spend WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByPageS($start_from,$limit){
		$sql = "SELECT s.id,s.total,s.created_at,s.fac,p.`name` FROM sell s, pacient p WHERE p.id=s.idPac  AND s.id>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public function addBuyS(){
		$sql = "insert into sell (total,user_id,status,operation_type_id,comentarios,created_at) ";
		$sql .= "value ($this->total,$this->user_id,$this->status,1,\"$this->note\",\"$this->date\")";
		return Executor::doit($sql);
	}

	public function UpdBuyS(){
		$sql = "update sell set status=$this->status,total=$this->tot,comentarios=\"$this->note\",created_at=\"$this->date\" where id=$this->idBuy";
		Executor::doit($sql);
	}


	public function add_with_client($idPac,$idMed){
		$sql = "insert into ".self::$tablename." (total,discount,user_id,created_at,idPac,idMedic,status,comentarios,idReser)";
		$sql .= "value ($this->total,$this->discount,$this->user_id,\"$this->date\",'$idPac','$idMed',$this->status,\"$this->note\",$this->idRes)";
				return Executor::doit($sql);
	}

	public function add_with_sal(){
		$sql = "insert into ".self::$tablename." (total,discount,user_id,created_at,idPac,idMedic,status,comentarios,sal) ";
		$sql .= "value ('0','0',$this->user_id,$this->created_at,'0','0','1',\"$this->note\",'1')";
				return Executor::doit($sql);
	}

	public function add_re_with_client(){
		$sql = "insert into ".self::$tablename." (person_id,operation_type_id,user_id,created_at) ";
		$sql .= "value ($this->person_id,1,$this->user_id,$this->created_at)";
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

	public function delPay(){
		$sql = "delete from pay where idSell=$this->id";
		Executor::doit($sql);
	}

	public function update_box(){
		$sql = "update ".self::$tablename." set box_id=$this->box_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		 $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}

	public static function getSells(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsUnBoxed(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id is NULL order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getByBoxId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getRes(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSal($id){
		$sql = "SELECT id,created_at,comentarios FROM sell WHERE sal='1' AND id like '%$id%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id<=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function getAllByDateOp($start,$end,$op){
  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}
	public static function getAllByDateBCOp($clientid,$start,$end,$op){
 $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and client_id=$clientid  and operation_type_id=$op order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}


	//------------------------PAYMENTS-------------------------------------
	public static function getAllPaymentsByDatesTypeBankAccountInvoice($startDate,$endDate,$paymentTypeId,$bankAccountId,$isInvoice)
	{
		$startDateTime = $startDate." 00:00:00";
		$endDateTime = $endDate." 23:59:59";

		$sql = "SELECT p.*,p.cash AS total, DATE_FORMAT(p.date,'%d/%m/%Y') AS date_format, 
			DATE_FORMAT(p.date,'%r') AS hour_format,
			pt.name AS payment_type_name,
			" . PatientData::$tablename . ".name AS patient_name
			FROM " . self::$tablenamePayments . " p
			INNER JOIN " . self::$tablenamePaymentTypes . " pt ON p.idTypePay = pt.id
			INNER JOIN " . self::$tablename . " s ON p.idSell =  s.id
			INNER JOIN " . PatientData::$tablename . " ON " . PatientData::$tablename . ".id =  s.idPac
			WHERE p.typePay = 'INGRESOS' 
			AND p.date >= '$startDateTime' 
			AND p.date <= '$endDateTime' ";

			if($isInvoice != "all"){
				$sql .= " AND p.is_invoice = '$isInvoice' ";
			}
			if($paymentTypeId == "cards"){
				$sql .= " AND (p.idTypePay = 2 OR p.idTypePay = 3) ";//Tarjeta débito (2),tarjeta crédito (3)
			}elseif($paymentTypeId != 0){
				$sql .= " AND p.idTypePay = '$paymentTypeId' ";
			}
			if($bankAccountId != 0){
				$sql .= " AND p.bank_account_id = '$bankAccountId' ";
			}
			$sql .= " ORDER BY p.date ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

}
