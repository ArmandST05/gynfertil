<?php
if(isset($_GET["paymentTypeId"])){

	$pay=$_SESSION["payments"];

		$npay= null;
		$nx=0;
		foreach($pay as $c){
			if($c["id"]!=$_GET["paymentTypeId"]){
				$npay[$nx]= $c;
			}
			$nx++;
		}
		$_SESSION["payments"] = $npay;
	}

print "<script>window.location='index.php?view=sales/new-details&reservationId=".$_GET['reservationId']."&patientId=".$_GET['patientId']."&medicId=".$_GET['medicId']."&date=".$_GET['date']."';</script>";

?>