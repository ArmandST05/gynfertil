<?php

      $tot=$_POST["total"];
      $totalGen=$_POST["totalGen"];

             $liq=$tot - $totalGen;

            if($liq<=0){
            $status = 1;
            }else{
            $status = 0;
            }
  $op2 = new OperationData();
  $upt = $op2->updatedateFacFIn($_POST["idSell"],$_POST["total"],$status,$_POST["note"]);  



  print "<script>window.location='index.php?view=onesell&id=".$_POST['idSell']."';</script>";

?>
