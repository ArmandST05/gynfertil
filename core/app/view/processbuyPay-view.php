<?php


			$sell = new SellData();
			
			$tot=$_POST["total"];
			$totalGen=$_POST["totalGen"];
 		    $liq=$tot - $totalGen;

            if($liq==0){
            $sell->status = 1;
            }else{
            $sell->status = 0;
            }
            $sell->idBuy = $_POST["idBuy"];
            $sell->tot = $_POST["total"];
            $sell->note = $_POST["note"];
            $date = $_POST["dateBuy"];
			$date = $date." ".date("H:i:s");
			$sell->date = date_format(date_create($date),"Y-m-d H:i:s");
			$s = $sell->UpdBuyS();

print "<script>window.location='index.php?view=expenses';</script>";




?>
