<?php
class ProductData {
	public static $tablename = "product";

	public $name;
	public $price_in;
	public $price_out;
	public $category_id;
	public $user_id;
	public $inventary_min;
	public $created_at;
	public $barcode;
	public $id;
	public $image;
	public $is_image;
	public $brand;
	public $presentation;
	public $is_active;

	public function __construct(){
		$this->name = "";
		$this->price_in = "";
		$this->price_out = "";
		$this->created_at = "NOW()";
	}

	public function getCategory(){ return CategoryData::getById($this->category_id);}

	public function add(){
		$sql = "INSERT INTO product (barcode,name,brand,presentation,price_in,price_out,user_id,inventary_min,type,idCat) 
        VALUES ('$this->barcode','$this->name','$this->brand','$this->presentation','$this->price_in','$this->price_out','$this->user_id','$this->inventary_min','MEDICAMENTO',8)";
		return Executor::doit($sql);
	}

	public function addS(){
		$sql = "INSERT INTO product (name,inventary_min,user_id,type,idCat) 
        VALUES ('$this->name','$this->inventary_min','$this->user_id','INSUMOS',9)";
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

	public function update(){
		$sql = "update ".self::$tablename." set barcode=\"$this->barcode\",name=\"$this->name\",brand=\"$this->brand\",presentation=\"$this->presentation\",price_in=\"$this->price_in\",price_out=\"$this->price_out\",inventary_min=\"$this->inventary_min\",is_active=\"$this->is_active\" where id=$this->id";
		Executor::doit($sql);
	}

	public function updateS(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",inventary_min=\"$this->inventary_min\" where id=$this->id";
		Executor::doit($sql);
	}

	public function del_category(){
		$sql = "update ".self::$tablename." set category_id=NULL where id=$this->id";
		Executor::doit($sql);
	}

	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

	public static function getByIdTypePay($id){
	    $sql = "select * from typepayment where id=$id";
	    $query = Executor::doit($sql);
	    return Model::one($query[0],new ProductData());
	}

	public static function getAll($name){
		$sql = "select * from ".self::$tablename." WHERE name like '%$name%'  AND (type='MEDICAMENTO' OR type='INSUMOS') ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllRe($cod){
		$sql = "select * from ".self::$tablename." WHERE barcode='$cod'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllReN($name){
		$sql = "select * from ".self::$tablename." WHERE name='$name'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getPendiente($idPac){
		$sql = "SELECT * FROM sell WHERE `status`='0' AND idPac='$idPac'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getInventoryProducts(){
		$sql = "SELECT * FROM ".self::$tablename." WHERE type='MEDICAMENTO'
		ORDER BY name ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllI(){
		$sql = "select * from ".self::$tablename." WHERE (type='MEDICAMENTO')";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllS($name){
		$sql = "select * from ".self::$tablename." WHERE type='INSUMOS' AND name like '%$name%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id>=$start_from AND type='MEDICAMENTO' limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByPageI($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id>=$start_from AND (type='MEDICAMENTO' OR type='INSUMOS') limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByPageS($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id>=$start_from AND type='INSUMOS' limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where  (type='MEDICAMENTO' OR type='CONCEPTO')  OR (name like '%$p%')";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeSell(){
		$sql = "select * from ".self::$tablename." where  (type='MEDICAMENTO' OR type='CONCEPTO')";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeSalidas(){
		$sql = "select * from ".self::$tablename." where  (type='INSUMOS' OR type='MEDICAMENTO')";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeEnt($p){
		$sql = "select * from ".self::$tablename." where  (type='MEDICAMENTO' OR type='INSUMOS')  OR (name like '%$p%')";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeSal($p){
		$sql = "select * from ".self::$tablename." where (barcode like '%$p%' or name like '%$p%' or id like '%$p%') AND (type='INSUMOS')";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getLikeConcepts($p){
		$sql = "SELECT id,name,'500' price_out FROM conceptsincome WHERE name like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByCategoryId($category_id){
		$sql = "select * from ".self::$tablename." where category_id=$category_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

    public static function getTypePay(){
		$sql = "select * from typepayment";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getTypePayeX(){
		$sql = "select * from typepayment WHERE type='1'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

    public static function getTypePayId($id){
		$sql = "SELECT * FROM pay  WHERE typePay='EGRESOS' AND idSell='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
    
    public static function getTypeSellId($id){
		$sql = "SELECT * FROM pay  WHERE typePay='INGRESOS' AND idSell='$id'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

}
?>