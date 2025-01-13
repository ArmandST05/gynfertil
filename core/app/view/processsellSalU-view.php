<?php

  $op2 = new OperationData();
  $upt = $op2->UpdateComen($_POST["idSell"],$_POST["note"]);  

  print "<script>window.location='index.php?view=res';</script>";

?>
