<?php
class OperationData
{
	public static $tablename = "operation";
	public static $tablenamePayments = "pay";
	public static $tablenamePaymentTypes = "typepayment";
	
	public $product_id;
	public $q;
	public $cut_id;
	public $operation_type_id;
	public $created_at;
	public $sell_id;
	public $date;
	public $price;
	public $name;
	public $id;
	public $idType;
	public $money;
	public $bank_account_id;
	public $is_invoice;
	public $expiration_date;
	public $lot;
	public $cad;
	public $buyId;

	public function __construct()
	{
		$this->name = "";
		$this->product_id = "";
		$this->q = "";
		$this->cut_id = "";
		$this->operation_type_id = "";
		$this->created_at =  date("Y-m-d H:i:s");
		$this->date = date("Y-m-d H:i:s");
	}

	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (product_id,q,operation_type_id,sell_id,created_at,price) ";
		$sql .= "value (\"$this->product_id\",\"$this->q\",$this->operation_type_id,$this->sell_id,\"$this->date\",$this->price)";
		return Executor::doit($sql);
	}

	public function addInput()
	{
		$sql = "INSERT INTO " . self::$tablename . " (product_id,q,operation_type_id,dateExpiry,lot) ";
		$sql .= "VALUE ($this->product_id,$this->q,1,\"$this->expiration_date\",\"$this->lot\")";
		return Executor::doit($sql);
	}

	public function addCompras()
	{
		$sql = "insert into " . self::$tablename . " (product_id,q,operation_type_id,sell_id,created_at,price,dateExpiry) ";
		$sql .= "value (\"$this->product_id\",\"$this->q\",$this->operation_type_id,$this->sell_id,\"$this->date\",$this->price,\"$this->cad\")";
		return Executor::doit($sql);
	}

	public function addU()
	{
		$sql = "insert into " . self::$tablename . " (product_id,q,operation_type_id,sell_id,created_at,price) ";
		$sql .= "value (\"$this->product_id\",\"$this->q\",$this->operation_type_id,$this->sell_id,$this->date,$this->price)";
		return Executor::doit($sql);
	}

	public static function delById($id)
	{
		$sql = "delete from " . self::$tablename . " where id=$id";
		Executor::doit($sql);
	}
	public function del()
	{
		$sql = "delete from " . self::$tablename . " where id=$this->id";
		Executor::doit($sql);
	}

	public function delConB($id)
	{
		$sql = "delete from " . self::$tablename . " where id=$id";
		Executor::doit($sql);
	}

	public function UpdateDateExp($idB, $fecha)
	{
		$sql = "UPDATE sell SET created_at='$fecha' WHERE id='$idB'";
		Executor::doit($sql);
	}

	public function updatedateFacdetE($idB, $fecha)
	{
		$sql = "UPDATE " . self::$tablename . " SET created_at='$fecha' WHERE sell_id='$idB'";
		Executor::doit($sql);
	}


	public function updatedateFac($idSell, $date, $total, $status)
	{
		$sql = "UPDATE sell SET created_at='$date',total='$total',status='$status' WHERE id='$idSell'";
		Executor::doit($sql);
	}

	public function updatedateFac1($idSell, $total, $status)
	{
		$sql = "UPDATE sell SET total='$total',status='$status' WHERE id='$idSell'";
		Executor::doit($sql);
	}

	public function updatedateFacFin($idSell, $total, $status, $com)
	{
		$sql = "UPDATE sell SET total='$total',status='$status',comentarios='$com' WHERE id='$idSell'";
		Executor::doit($sql);
	}

	public function updatedateFacdet($idSell, $date)
	{
		$sql = "UPDATE " . self::$tablename . " SET created_at='$date' WHERE sell_id='$idSell'";
		Executor::doit($sql);
	}

	public function updateExpFac($idExp, $valor)
	{
		$sql = "UPDATE sell SET fac='$valor' WHERE id='$idExp'";
		Executor::doit($sql);
	}

	public function updateBanFac($idSell, $ban)
	{
		$sql = "UPDATE sell SET banco='$ban' WHERE id='$idSell'";
		Executor::doit($sql);
	}

	public function updateSellFacs($idSell, $noFac)
	{
		$sql = "UPDATE sell SET noFac='$noFac' WHERE id='$idSell'";
		Executor::doit($sql);
	}


	public function UpdateComen($id, $nota)
	{
		$sql = "UPDATE sell SET comentarios='$nota' WHERE id='$id'";
		Executor::doit($sql);
	}

	// partiendo de que ya tenemos creado un objecto OperationData previamente utilizamos el contexto
	public function update()
	{
		$sql = "update " . self::$tablename . " set product_id=\"$this->product_id\",q=\"$this->q\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "select * from " . self::$tablename . " where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getAll()
	{
		$sql = "select * from " . self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getConceptsId($id)
	{
		$sql = "SELECT  GROUP_CONCAT(o.q,' ',p.name)con 
		FROM " . self::$tablename . " o, " . ProductData::$tablename . " p 
		WHERE o.sell_id='$id' AND p.id=o.product_id GROUP BY o.sell_id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getAllByDateOfficial($start, $end)
	{
		$sql = "select * from " . self::$tablename . " WHERE date(created_at) >= \"$start\" 
		AND date(created_at) <= \"$end\" order by created_at desc";
		if ($start == $end) {
			$sql = "select * from " . self::$tablename . " where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllByDateOfficialBP($product, $start, $end)
	{
		$sql = "select * from " . self::$tablename . " where date(created_at) >= \"$start\" 
		AND date(created_at) <= \"$end\" and product_id=$product order by created_at desc";
		if ($start == $end) {
			$sql = "select * from " . self::$tablename . " where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public function getProduct()
	{
		return ProductData::getById($this->product_id);
	}
	public function getOperationtype()
	{
		return OperationTypeData::getById($this->operation_type_id);
	}

	public static function getStockByProduct($productId)
	{
		$quantity = 0;
		$operations = self::getAllByProductId($productId);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;

		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$quantity += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$quantity += (-$operation->q);
			}
		}

		return $quantity;
	}

	public static function getStockByProductDate($productId,$date)
	{
		$quantity = 0;
		$operations = self::getAllByProductMaxDate($productId,$date);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;

		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$quantity += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$quantity += (-$operation->q);
			}
		}

		return $quantity;
	}

	public static function getTotalInputsByProductDates($productId,$startDate,$endDate)
	{
		$startDateTime = $startDate." 00:00:00";
		$endDateTime = $endDate." 23:59:59";

		//Obtiene el total de ventas realizadas por producto
		$sql = "SELECT product_id,SUM(q) total FROM " . self::$tablename . " 
			WHERE operation_type_id='1' 
			AND product_id = '$productId' 
			AND created_at >= '$startDateTime'
			AND created_at <= '$endDateTime'
			GROUP BY product_id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getAllByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " WHERE product_id=$product_id and cut_id=$cut_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllByProductId($productId)
	{
		$sql = "select id,product_id,q,price,operation_type_id,sell_id,
		DATE_FORMAT(created_at,'%d-%m-%Y')fecha,created_at,dateExpiry,DATE_FORMAT(dateExpiry,'%d-%m-%Y')expiration_date_format,lot
		FROM " . self::$tablename . " where product_id=$productId  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllByProductMaxDate($productId,$date)
	{
		$maxdateTime = $date." 23:59:59";

		$sql = "SELECT id,product_id,q,price,operation_type_id,sell_id,
			DATE_FORMAT(created_at,'%d-%m-%Y')fecha,created_at,dateExpiry,lot
			FROM " . self::$tablename . " 
			WHERE product_id='$productId' AND created_at <= '$maxdateTime'
			ORDER BY created_at desc";

		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllExpirationDatesByProduct($productId)
	{
		//Obtiene todas las fechas de expiración(lotes de produto)
		$sql = "SELECT product_id,SUM(q)q,DATE_FORMAT(dateExpiry,'%d/%m/%Y')as dateExpiry,DATE_FORMAT(dateExpiry,'%m')as mes,
			DATE_FORMAT(dateExpiry,'%Y')as exp, DATE_FORMAT(dateExpiry,'%Y%m')as con,timestampdiff(month,curdate(),dateExpiry) difM,
			lot 
			FROM " . self::$tablename . " 
			WHERE operation_type_id='1' AND product_id='$productId'
			GROUP BY product_id,dateExpiry ORDER BY con ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getExpirationDatesByProductMaxDate($productId,$date)
	{
		//Obtiene todas las fechas de expiración(lotes de produto) registradas antes de cierta fecha 
		$maxdateTime = $date." 23:59:59";

		$sql = "SELECT product_id,SUM(q)q,DATE_FORMAT(dateExpiry,'%d/%m/%Y')as dateExpiry,DATE_FORMAT(dateExpiry,'%m')as mes,
			DATE_FORMAT(dateExpiry,'%Y')as exp, DATE_FORMAT(dateExpiry,'%Y%m')as con,timestampdiff(month,curdate(),dateExpiry) difM,
			lot 
			FROM " . self::$tablename . " 
			WHERE operation_type_id='1' AND product_id='$productId'
			AND created_at <= '$maxdateTime'
			GROUP BY product_id,dateExpiry ORDER BY con ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getTotalSalesByProduct($productId)
	{
		//Obtiene el total de ventas realizadas por producto
		$sql = "SELECT product_id,SUM(q) q FROM " . self::$tablename . " 
			WHERE operation_type_id='2' 
			AND product_id='$productId' GROUP BY product_id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getTotalSalesByProductMaxDate($productId,$date)
	{
		$maxdateTime = $date." 23:59:59";

		//Obtiene el total de ventas realizadas por producto hasta una fecha determinada
		$sql = "SELECT product_id,SUM(q) q FROM " . self::$tablename . " 
			WHERE operation_type_id='2' 
			AND created_at <= '$maxdateTime'
			AND product_id='$productId' GROUP BY product_id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getTotalSalesByProductDates($productId,$startDate,$endDate)
	{
		//No incluir todas las salidas, si no solamente las ventas.
		$startDateTime = $startDate." 00:00:00";
		$endDateTime = $endDate." 23:59:59";

		//Obtiene el total de ventas realizadas por producto en fechas específicas
		$sql = "SELECT o.product_id,SUM(o.q) total FROM " . self::$tablename . " o
			INNER JOIN  " . SellData::$tablename . " s ON o.sell_id = s.id
			AND s.idPac IS NOT NULL AND s.idPac != 0
			WHERE o.operation_type_id = '2' AND o.product_id='$productId' 
			AND o.created_at >= '$startDateTime' AND o.created_at <= '$endDateTime'
			GROUP BY product_id";

		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getAllProductsBySellId($sell_id)
	{
		$sql = "select * from " . self::$tablename . " where sell_id=$sell_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllByConcepts($sell_id)
	{
		$sql = "SELECT product_id,q,price FROM " . self::$tablename . " WHERE sell_id='$sell_id'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getnamePro($id)
	{
		$sql = "SELECT name,type,price_out FROM " . ProductData::$tablename . " WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getOutputQ($product_id, $cut_id)
	{
		$q = 0;
		$operations = self::getOutputByProductIdCutId($product_id, $cut_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQYesF($product_id)
	{
		$q = 0;
		$operations = self::getOutputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		return $q;
	}

	public static function getInputQYesF($product_id)
	{
		$q = 0;
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			}
		}
		return $q;
	}

	public static function getOutputByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getOutputByProductId($product_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and operation_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getInputQ($product_id, $cut_id)
	{
		$q = 0;
		$operations = self::getInputByProductId($product_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach ($operations as $operation) {
			if ($operation->operation_type_id == $input_id) {
				$q += $operation->q;
			} else if ($operation->operation_type_id == $output_id) {
				$q += (-$operation->q);
			}
		}
		return $q;
	}

	public static function getInputByProductIdCutId($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getInputByProductId($productId)
	{
		$sql = "select * from " . self::$tablename . " 
		WHERE product_id=$productId 
		AND operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getInputByProductIdCutIdYesF($product_id, $cut_id)
	{
		$sql = "select * from " . self::$tablename . " where product_id=$product_id and cut_id=$cut_id and operation_type_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	//------------------------PAYMENTS-------------------------------------

	public function addPay()
	{
		$sql = "insert into " . self::$tablenamePayments . " (idTypePay,idSell,cash,typePay,date,bank_account_id,is_invoice) ";
		$sql .= "value ($this->idType,$this->sell_id,$this->money,'INGRESOS',\"$this->date\",\"$this->bank_account_id\",\"$this->is_invoice\")";
		return Executor::doit($sql);
	}

	public function addPay1()
	{
		$sql = "insert into " . self::$tablenamePayments . " (idTypePay,idSell,cash,typePay,date,bank_account_id,is_invoice) ";
		$sql .= "value ($this->idType,$this->sell_id,$this->money,'INGRESOS',\"$this->fecha\",\"$this->bank_account_id\",\"$this->is_invoice\")";
		return Executor::doit($sql);
	}

	public function addPayB()
	{
		$sql = "insert into " . self::$tablenamePayments . " (idTypePay,idSell,cash,typePay,date) ";
		$sql .= "value ($this->idType,$this->buyId,$this->money,'EGRESOS','$this->date')";
		return Executor::doit($sql);
	}

	public function addPayB1()
	{
		$sql = "insert into " . self::$tablenamePayments . " (idTypePay,idSell,cash,typePay,date) ";
		$sql .= "value ($this->idType,$this->buyId,$this->money,'EGRESOS',\"$this->fecha\")";
		return Executor::doit($sql);
	}

	public function delPayB($id)
	{
		$sql = "delete from " . self::$tablenamePayments . " where id=$id";
		Executor::doit($sql);
	}

	public static function getAllBySellPay($sell_id)
	{
		$sql = "SELECT t.name tname,p.cash from " . self::$tablenamePayments . " p, typepayment t where p.idSell='$sell_id' AND p.idTypePay=t.id";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllBySellPayCon($sell_id)
	{
		$sql = "SELECT  GROUP_CONCAT(t.name,': ',p.cash)tpay from " . self::$tablenamePayments . " p, typepayment t where p.idSell='$sell_id' AND p.idTypePay=t.id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getAllBySellPayE($sell_id)
	{
		$sql = "SELECT SUM(p.cash)cash from " . self::$tablenamePayments . " p where p.idSell='$sell_id' group by idSell";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllPayBySellId($sell_id)
	{
		$sql = "select p.cash, t.name from " . self::$tablenamePayments . " p, typepayment t where idSell=$sell_id AND t.id=p.idTypePay";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	//Actualiza una operación (pago) identificando si se utiliza factura o no
	public function updatePaymentIsInvoice($id, $isInvoice)
	{
		$sql = "UPDATE " . self::$tablenamePayments . " SET is_invoice = '$isInvoice' WHERE id = '$id'";
		return Executor::doit($sql);
	}

}
