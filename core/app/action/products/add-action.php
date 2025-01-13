<?php
if(count($_POST)>0){
  $product = new ProductData();
  $product->barcode = $_POST["barcode"];
  $product->name = $_POST["name"];
  $product->price_in = $_POST["price_in"];
  $product->price_out = $_POST["price_out"];
  $product->presentation = $_POST["presentation"];
  $product->brand = $_POST["brand"];

  $inventary_min="\"\"";

  if($_POST["inventary_min"] != ""){ $inventary_min=$_POST["inventary_min"];}
  $product->inventary_min = $inventary_min;
  $product->user_id = $_SESSION["user_id"];
  $addedProduct = $product->add();

  /*if(isset($_FILES["image"])){
    $image = new Upload($_FILES["image"]);
    if($image->uploaded){
      $image->Process("storage/products/");
      if($image->processed){
        $product->image = $image->file_dst_name;
        $prod = $product->add_with_image();
      }
    }else{

  $prod= $product->add();
    }
  }
  else{
  $prod= $product->add();

  }*/

  if($_POST["q"] != "" || $_POST["q"] != "0"){
    $op = new OperationData();
    $op->product_id = $addedProduct[1];
    $op->operation_type_id = 1;//Entrada
    $op->q = $_POST["q"];
    $op->expiration_date = $_POST["expirationDate"];
    $op->lot = $_POST["lot"];
    $op->addInput();
  }

  Core::redir("./index.php?view=inventory/index-medicines");
}
?>