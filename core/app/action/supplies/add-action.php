<?php


if(count($_POST)>0){
  $product = new ProductData();
   $product->name = $_POST["name"];
   $product->minimum_inventory=$_POST["minimumInventory"];
   $product->user_id = $_SESSION["user_id"];

   $prod= $product->addSupply();

  if($_POST["initialInventory"] != "" || $_POST["initialInventory"] != "0"){
    $op = new OperationDetailData();
    $op->product_id = $prod[1] ;
    $op->operation_type_id = 1;//Entrada
    $op->quantity= $_POST["quantity"];
    $op->sale_id="NULL";
    $op->is_oficial = 1;
    $op->price = 0;
    $op->add();
  }
print "<script>window.location='index.php?view=supplies/index';</script>";

}

?>