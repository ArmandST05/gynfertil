<?php
class OperationDetailData
{
	public static $tablename = "operation_details";

	public function __construct(){
		$this->name = "";
		$this->product_id = "";
		$this->quantity = "";
		$this->cut_id = "";
		$this->operation_type_id = "";
		$this->created_at = "NOW()";
	}

	public function getProduct()
	{
		return ProductData::getById($this->product_id);
	}

	public function getOperationType()
	{
		return OperationTypeData::getById($this->operation_type_id);
	}

	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (product_id,quantity,operation_type_id,operation_id,created_at,price) ";
		$sql .= "value (\"$this->product_id\",\"$this->quantity\",$this->operation_type_id,$this->operation_id,\"$this->date\",$this->price)";
		return Executor::doit($sql);
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id = $this->id";
		return Executor::doit($sql);
	}
	
	public static function deleteByOperationId($operationId)
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE operation_id = '$operationId'";
		return Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new OperationDetailData());
	}

	/*--------ENTRADAS---- */
	public function addInputInventory()
	{
		$sql = "INSERT INTO " . self::$tablename . " (operation_id,product_id,quantity,operation_type_id,expiration_date) ";
		$sql .= "value ($this->operation_id,$this->product_id,$this->quantity,1,\"$this->expiration_date\")";
		return Executor::doit($sql);
	}

	/*OBTENER POR OPERACIÓN (VENTA,GASTO,ETC) */
	public static function getAllByOperationId($id){
		$sql = "SELECT * FROM " . self::$tablename . " WHERE operation_id='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new OperationDetailData());
	}

	/*---------INVENTARIO ------ */
	public static function getAllByProductId($productId)
	{
		//Obtiene todas las operaciones realizadas de cierto producto
		$sql = "SELECT id,product_id,quantity,price,operation_type_id,operation_id,
			DATE_FORMAT(created_at,'%d-%m-%Y')date,
			created_at,expiration_date  
			FROM " . self::$tablename . " WHERE product_id = $productId 
			ORDER BY created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationDetailData());
	}

	public static function getByOperationTypeBranchOfficeProduct($branchOfficeId,$productId,$operationType)
	{
		//Obtiene el total por tipo de operación y producto.
		//Ejemplo: Total de salidas/entradas de producto x
		$sql = "SELECT SUM(od.quantity) AS total 
			FROM " . self::$tablename . " od
			INNER JOIN " . OperationData::$tablename . " o ON od.operation_id = o.id
			AND o.branch_office_id = '$branchOfficeId'
			WHERE od.product_id = '$productId' 
			AND od.operation_type_id = '$operationType'";

		$query = Executor::doit($sql);
		$total =  Model::one($query[0], new OperationDetailData())->total;
		return $total;
	}


	public static function getStockByBranchOfficeProduct($branchOfficeId,$productId)
	{
		//Obtiene el stock disponible de cierto producto para las ventas
		$stock = 0;
		$totalInputs = self::getByOperationTypeBranchOfficeProduct($branchOfficeId,$productId,1);
		$totalOutputs = self::getByOperationTypeBranchOfficeProduct($branchOfficeId,$productId,2);

		if($totalInputs > 0) $stock = $totalInputs - $totalOutputs;
	
		return $stock;
	}

	/*-----EXPENSES/PURCHASES--- */
	public function addExpense()
	{
		$sql = "INSERT INTO " . self::$tablename . " (product_id,quantity,operation_type_id,operation_id,created_at,price,expiration_date) ";
		$sql .= "value (\"$this->product_id\",\"$this->quantity\",$this->operation_type_id,$this->operation_id,\"$this->date\",$this->price,\"$this->expiration_date\")";
		return Executor::doit($sql);
	}

	public static function updateDate($operationId, $date)
	{
		$sql = "UPDATE " . self::$tablename . " SET created_at = '$date' WHERE operation_id='$operationId'";
		return Executor::doit($sql);
	}
	
	public static function updateTotalStatus($operationId, $total, $statusId)
	{
		$sql = "UPDATE " . self::$tablename . " SET total='$total',status_id='$statusId' WHERE id = '$operationId'";
		return Executor::doit($sql);
	}

	public function update()
	{
		$sql = "UPDATE " . self::$tablename . " set product_id=\"$this->product_id\",quantity=\"$this->quantity\" WHERE id = $this->id";
		Executor::doit($sql);
	}

	public static function getAll()
	{
		$sql = "SELECT * FROM " . self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationDetailData());
	}

	public static function getByOperationId($operationId)
	{
		$sql = "SELECT * FROM ".self::$tablename." 
		WHERE operation_id = '$operationId' ";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationDetailData());
	}

	public static function getAllProductsByOperationId($operationId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE operation_id = '$operationId' ORDER BY created_at DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new OperationDetailData());
	}
}
