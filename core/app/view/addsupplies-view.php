<?php


if(count($_POST)>0){
  $product = new ProductData();
   $product->name = $_POST["name"];
   $product->inventary_min=$_POST["inventary_min"];
   $product->user_id = $_SESSION["user_id"];

   $prod= $product->addS();

  if($_POST["q"]!="" || $_POST["q"]!="0"){
 $op = new OperationData();
 $op->product_id = $prod[1] ;
 $op->operation_type_id=OperationTypeData::getByName("entrada")->id;
 $op->q= $_POST["q"];
 $op->sell_id="NULL";
$op->is_oficial=1;
$op->price = 0;
$op->add();
}


print "<script>window.location='index.php?view=supplies';</script>";


}

?>