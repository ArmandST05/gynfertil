<?php
class CategorySpend {
	public static $tablename = "category_spend";

	public function __construct(){
		$this->title = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->is_public = "0";
		$this->created_at = "NOW()";
	}

	
	public function addCategory($name){
		$sql = "insert into ".self::$tablename." (name,type) value ('$name','EGRESOS')";
		Executor::doit($sql);
	}

	public function addConcepts($name,$cate){
		$sql = "insert into  product (name,idCat,type,inventary_min) value ('$name','$cate','CONCEPTOEGRE','0')";
		Executor::doit($sql);
	}

	public function addConceptsIncome($name){
		$sql = "insert into  product (name,type) value ('$name','CONCEPTO')";
		Executor::doit($sql);
	}
	
	public function updateCat(){
		$sql = "update ".self::$tablename." set name=\"$this->name\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getByIdCat($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategorySpend());
	}

	public static function getAllCatSpend(){
		$sql = "select * from category_spend WHERE type='EGRESOS' AND id<>8 AND id<>9";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategorySpend());
	}


	public static function getAllR($name){
		$sql = "select * from category_spend WHERE type='EGRESOS' AND id='$name'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategorySpend());
	}

	public static function getByIdConSpend($id){
		$sql = "SELECT * FROM product WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategorySpend());
	}


	public static function getByIdConIncome($id){
		$sql = "SELECT * FROM product WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategorySpend());
	}

	
	public static function getAllConSpend($name){
		$sql = "select con.id,con.name,cat.name categoria from product con, category_spend cat WHERE con.idCat=cat.id AND con.name like '%$name%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategorySpend());
	}

   public static function getAllByPageSC($start_from,$limit){
   	    $sql = "select con.id,con.name,cat.name categoria from product con, category_spend cat WHERE con.idCat=cat.id AND con.id>=$start_from  limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
	public static function getAllConIncome(){
		$sql = "SELECT id,name,type FROM product WHERE type='CONCEPTO'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategorySpend());
	}

	public static function getAllExpense($name){
		$sql = "SELECT s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,s.status,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha FROM sell s WHERE operation_type_id='1' AND id like '%$name%' ORDER BY id DESC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategorySpend());
	}

	 public static function getAllByPageBuy($start_from,$limit){
   	    $sql = "SELECT s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,s.status,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha FROM sell s WHERE operation_type_id='1' AND s.id>=$start_from order by id DESC limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getCatExpense($cat){
		$sql = "SELECT cs.id,cs.`name`,cat.`name` nameCat,cs.price_in FROM product cs, category_spend cat WHERE cs.idCat=cat.id AND (cs.name like '%$cat%' OR cat.`name` like '%$cat%')";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategorySpend());
	}

	public static function getCatExpenseb(){
		$sql = "SELECT cs.id,cs.`name`,cat.`name` nameCat,cs.price_in FROM product cs, category_spend cat WHERE cs.idCat=cat.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategorySpend());
	}

	public static function getByIdCatBuy($id){
		$sql = "SELECT cs.id,cs.`name`,cat.`name` nameCat FROM product cs, category_spend cat WHERE cs.idCat=cat.id AND cs.id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategorySpend());
	}

	public static function getByIdCatSell($id){
		$sql = "SELECT cs.id,cs.`name` FROM product cs WHERE  cs.id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategorySpend());
	}

	public static function getByIdCatBuyId($id){
		$sql = "SELECT * FROM operation WHERE sell_id='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategorySpend());
	}

	public static function getComen($id){
		$sql = "SELECT comentarios FROM sell WHERE id='$id'";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategorySpend());
	}

	 
	public function updatecatSpend(){
		$sql = "update product set name=\"$this->name\",idCat=\"$this->cate\" where id=$this->idCon";
		

		Executor::doit($sql);
	}

    public function updatecatIncome(){
		$sql = "UPDATE product set name=\"$this->name\" WHERE id=$this->idCon";
		

		Executor::doit($sql);
	}
	

	
}

?>