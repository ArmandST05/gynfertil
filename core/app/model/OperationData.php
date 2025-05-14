<?php
class OperationData
{
	public static $tablename = "operations";

	public function __construct()
	{
		$this->created_at = "NOW()";
	}

	public function getUser()
	{
		return UserData::getById($this->user_id);
	}
	public function getPatient()
	{
		return PatientData::getById($this->patient_id);
	}

	public function getMedic()
	{
		return MedicData::getById($this->medic_id);
	}

	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (total,discount,user_id,created_at) ";
		$sql .= "value ($this->total,$this->discount,$this->user_id,\"$this->created_at\")";
		return Executor::doit($sql);
	}

	public function addInputInventory()
	{
		$sql = "INSERT INTO " . self::$tablename . " (user_id,branch_office_id,operation_type_id,total,operation_category_id,created_at) ";
		$sql .= "value ($this->user_id,\"$this->branch_office_id\",1,$this->total,'4',\"$this->created_at\")";
		return Executor::doit($sql);
	}

	public function addOutputInventory()
	{
		$sql = "INSERT INTO " . self::$tablename . " (user_id,branch_office_id,operation_type_id,created_at,total,operation_category_id,description) ";
		$sql .= "value ($this->user_id,\"$this->branch_office_id\",2,\"$this->created_at\",$this->total,'2',\"$this->description\")";
		return Executor::doit($sql);
	}

	public static function getBySaleStatusPatient($statusId, $patientId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE `status_id`='$statusId' AND patient_id='$patientId'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getDescription($id)
	{
		$sql = "SELECT description FROM " . self::$tablename . " WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public function updateDescription()
	{
		$sql = "UPDATE  " . self::$tablename . " SET description=\"$this->description\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function updateExpenseDate($operationId, $date)
	{
		$sql = "UPDATE " . self::$tablename . " SET created_at = '$date' WHERE id='$operationId'";
		return Executor::doit($sql);
	}
	public function updateDate()
	{
		$sql = "UPDATE " . self::$tablename . " SET created_at = '$this->created_at' WHERE id='$this->id'";
		return Executor::doit($sql);
	}

	public function updateTotal()
	{
		$sql = "UPDATE " . self::$tablename . " SET total=$this->total WHERE id='$this->id'";
		return Executor::doit($sql);
	}

	public function updateStatus()
	{
		$sql = "UPDATE " . self::$tablename . " SET status_id = '$this->status_id' WHERE id = '$this->id'";
		return Executor::doit($sql);
	}

	public function updateIsInvoice()
	{
		$sql = "UPDATE " . self::$tablename . " SET is_invoice='$this->value' WHERE id='$this->id'";
		return Executor::doit($sql);
	}

	public function updateInvoiceNumber()
	{
		$sql = "UPDATE " . self::$tablename . " SET invoice_number='$this->invoice_number' WHERE id='$this->id'";
		return Executor::doit($sql);
	}

	public function updateExpense()
	{
		$sql = "UPDATE  " . self::$tablename . " set status_id=$this->status_id,total=$this->total,description=\"$this->description\" WHERE id=$this->id";
		Executor::doit($sql);
	}

	public function addSale($patientId, $medicId)
	{
		$sql = "INSERT INTO " . self::$tablename . " (total,discount,user_id,created_at,patient_id,medic_id,status_id,description,reservation_id,operation_type_id,operation_category_id,branch_office_id)";
		$sql .= "VALUE ($this->total,$this->discount,$this->user_id,\"$this->date\",'$patientId','$medicId',$this->status_id,\"$this->description\",$this->reservation_id,2,1,$this->branch_office_id)";
		return Executor::doit($sql);
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id=$this->id";
		return Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id = '$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	public static function getByBranchOfficeTypeId($branchOfficeId, $categoryId)
	{
		$sql = "SELECT id,created_at,description 
		FROM " . self::$tablename . " 
		WHERE operation_category_id = '$categoryId' 
		AND branch_office_id = '$branchOfficeId'
		ORDER BY created_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	/*-----------EXPENSES/PURCHASES----------- */
	public function addExpense()
	{
		$sql = "INSERT INTO  " . self::$tablename . " (total,user_id,status_id,operation_type_id,operation_category_id,description,branch_office_id) ";
		$sql .= "VALUE ($this->total,$this->user_id,$this->status_id,1,3,\"$this->description\",\"$this->branch_office_id\")";
		return Executor::doit($sql);
	}

	public static function getAllExpensesByBranchOfficeDate($branchOfficeId, $date)
	{
		$sql = "SELECT od.product_id,od.quantity,od.price 
			FROM operation_details od,operations s 
			WHERE od.operation_type_id = '1'
			AND s.status_id = '1' 
			AND od.created_at like '$date%' 
			AND s.id = od.operation_id 
			AND s.branch_office_id = '$branchOfficeId'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllExpensesByBranchOfficeDates($branchOfficeId, $startDate,$endDate)
	{
		$startDateTime = $startDate . " 00:00:00";
		$endDateTime = $endDate . " 23:59:59";

		$sql = "SELECT od.product_id,od.quantity,od.price 
			FROM operation_details od,operations s 
			WHERE od.operation_type_id = '1'
			AND s.status_id = '1' 
			AND od.created_at >='$startDateTime' 
			AND od.created_at <='$endDateTime' 
			AND s.id = od.operation_id 
			AND s.branch_office_id = '$branchOfficeId'";

		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	/*-------------------SALES---------------*/
	public static function getAccountStatusByPatient($id)
	{
		$sql = "SELECT id,total,DATE_FORMAT(created_at,'%d/%m/%Y') AS date_format,patient_id,medic_id,status_id,description,
		CONCAT(ELT(WEEKDAY(created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS day_name 
		FROM " . self::$tablename . " 
		WHERE patient_id = '$id' 
		ORDER BY id ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllSalesByBranchOfficeDates($branchOfficeId, $startDate, $endDate, $paymentTypeId, $statusId = "all",$productId="all")
	{
		$startDateTime = $startDate . " 00:00:00";
		$endDateTime = $endDate . " 23:59:59";
		//Obtiene todas las ventas generadas en cierta fecha. No importa si están liquidadas o no.
		$sql = "SELECT s.medic_id,s.status_id,s.description,s.bank,s.invoice_number,s.id,
			s.total,s.created_at,s.is_invoice,p.name AS patient_name,reservation_id,
			CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS day_name, 
			DATE_FORMAT(s.created_at,'%d/%m/%Y') AS date_format  
			FROM  " . self::$tablename . " s, patients p 
			WHERE p.id = s.patient_id 
			AND (s.created_at >= '$startDateTime' AND s.created_at <= '$endDateTime')
			AND operation_type_id = '2' 
			AND s.branch_office_id = '$branchOfficeId' ";
		if ($paymentTypeId != "all" && $paymentTypeId != "0" && $paymentTypeId != "") {
			$sql .= " AND (SELECT opp.id FROM " . OperationPaymentData::$tablename . " opp 
				WHERE opp.operation_id = s.id 
				AND opp.payment_type_id = '$paymentTypeId' LIMIT 1) 
				IS NOT NULL ";
		}
		if ($productId != "all" && $productId != "") {
			$sql .= " AND (SELECT odd.id FROM " . OperationDetailData::$tablename . " odd 
				WHERE odd.operation_id = s.id 
				AND odd.product_id = '$productId' LIMIT 1) 
				IS NOT NULL ";
		}

		if ($statusId != "all") {
			$sql .= " AND s.status_id = '$statusId' ";
		}
		$sql .= " ORDER BY s.id";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}


	public static function getAllSelledProductsByBranchOfficeDate($branchOfficeId, $date)
	{
		//Obtiene el total vendido de los productos por día
		$sql = "SELECT product_id,SUM(quantity) quantity,SUM(price) price,SUM(price*quantity) total,patient_id,medic_id,s.status_id 
		FROM operation_details o,operations s 
		WHERE o.operation_type_id = '2' 
		AND s.status_id = '1' 
		AND o.created_at LIKE '$date%' 
		AND s.id = o.operation_id 
		AND s.branch_office_id = '$branchOfficeId'
		GROUP BY product_id";

		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllMedicSalesByProductDate($branchOfficeId, $productId, $date)
	{
		//Obtiene todos los Psicólogos que han vendido cierto producto 
		//en cierta fecha y la cantidad de producto vendida y la ganancia generada por médico.
		//Las ventas deben de estar liquidadas para mostrarlas.
		$sql = "SELECT product_id,SUM(quantity)quantity,SUM(price) price,patient_id,medic_id 
			FROM operation_details o," . self::$tablename . " s 
			WHERE o.operation_type_id='2' 
			AND s.branch_office_id = '$branchOfficeId'
			AND o.created_at LIKE '$date%' 
			AND s.id=o.operation_id 
			AND product_id='$productId' AND s.status_id='1' 
			GROUP BY product_id,medic_id";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getAllProductSalesByDate($date)
	{
		//Obtiene los totales vendidos por medicamentos en cierta fecha
		$sql = "SELECT product_id,SUM(o.quantity)quantity,(o.price)price,
				SUM(o.price*o.quantity)total
                FROM operation_details o, products p, " . self::$tablename . " s 
				WHERE o.operation_type_id='2' 
                AND o.created_at like '$date%' 
				AND p.id = o.product_id 
				AND p.type_id = 4
                AND s.`status_id`='1' AND  s.id = o.operation_id 
				GROUP BY o.product_id";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	public static function getLastSaleByReservationId($reservationId)
	{
		$sql = "SELECT s.id,s.status_id FROM " . self::$tablename . " s
		WHERE s.id = (SELECT MAX(s_sq.id) FROM " . self::$tablename . " s_sq WHERE s_sq.reservation_id = '$reservationId')
		LIMIT 1";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}

	/*-----------INGRESOS/INPUTS--------- */

	public static function getInputsSales($branchOfficeId, $paymentTypeId, $startDate, $endDate)
	{
		$startDateTime = $startDate . " 00:00:00";
		$endDateTime = $endDate . " 23:59:59";

		$sql = "SELECT p.total total,s.bank,s.is_invoice 
			FROM " . OperationPaymentData::$tablename . " p," . self::$tablename . " s  
			WHERE  p.payment_type_id = '$paymentTypeId' 
			AND s.branch_office_id = '$branchOfficeId'
			AND s.id = p.operation_id 
			AND s.operation_category_id='1' 
			AND (date >= '$startDateTime' AND date <= '$endDateTime')";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	/*-----------SALIDAS/OUTPUTS------------ */
	public static function getOutputsExpenses($branchOfficeId, $paymentTypeId, $startDate, $endDate)
	{
		$startDateTime = $startDate . " 00:00:01";
		$endDateTime = $endDate . " 23:59:59";

		$sql = "SELECT p.total total,s.bank,s.is_invoice 
		FROM " . OperationPaymentData::$tablename . " p," . self::$tablename . " s  
		WHERE  p.payment_type_id = '$paymentTypeId'
			AND s.branch_office_id = '$branchOfficeId'
			AND s.id = p.operation_id
			AND s.operation_category_id = '3' 
			AND (p.date >= '$startDateTime' AND p.date <= '$endDateTime')";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationData());
	}

	/*-------------CASHIER BALANCE/CORTE DE CAJA */
	public static function getByReservationProductStatus($reservationId, $productId = 0, $statusId = "all")
	{
		$sql = "SELECT o.* FROM 
		" . self::$tablename . " o
		INNER JOIN " . OperationDetailData::$tablename . " od ON od.operation_id = o.id
		WHERE o.reservation_id = '$reservationId' ";
		if ($productId != "0") {
			$sql .= " AND od.product_id = '$productId' ";
		}
		if ($statusId != "all") {
			$sql .= " AND o.status_id = '$statusId' ";
		}
		$sql .= " LIMIT 1 ";

		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationData());
	}
}
