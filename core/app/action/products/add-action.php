<?php
if(count($_POST)>0){
  $product = new ProductData();
  $product->barcode = trim($_POST["barcode"]);
  $product->name = trim($_POST["name"]);
  $product->price_in = $_POST["priceIn"];
  $product->price_out = $_POST["priceOut"];
  $product->minimum_inventory = (($_POST["minimumInventory"] != "") ? $_POST["minimumInventory"]:0);
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

if($_POST["initialInventory"] != "" || $_POST["initialInventory"] != "0"){
  $operationDetail = new OperationDetailData();
  $operationDetail->product_id = $addedProduct[1] ;
  $operationDetail->operation_type_id = 1;
  $operationDetail->quantity = $_POST["initialInventory"];
  $operationDetail->operation_id = null;
  $operationDetail->price = $_POST["priceIn"];
  $operationDetail->date = date("Y-m-d");
  $operationDetail->add();
}

print "<script>window.location='index.php?view=products/index';</script>";
}
?>