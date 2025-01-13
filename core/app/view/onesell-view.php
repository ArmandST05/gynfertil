
<div  class="btn-group  pull-right">
 <a class="btn btn-default" href="index.php?view=reports/onesell-word&id=<?php echo $_GET["id"];?>">Imprimir venta</a></li>
</div>

<h1>Resumen de Venta</h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);

$pagos = OperationData::getAllPayBySellId($_GET["id"]);

$total = 0;
$totalPay=0;
?>
<?php
if(isset($_COOKIE["selled"])){
	foreach ($operations as $operation) {
//		print_r($operation);
		$qx = OperationData::getStockByProduct($operation->product_id);
		// print "qx=$qx";
			$p = $operation->getProduct();
		if($p->type=="CONCEPTO" || $p->type=="CONCEPTOEGRE"){

		}
		else if($qx==0){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";			
		}else if($qx<=$p->inventary_min/2){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
		}else if($qx<=$p->inventary_min){
			echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
		}
	}
	setcookie("selled","",time()-18600);
}

?>
<table class="table table-bordered">
<?php if($sell->idPac!=""):
$client = PatientData::getById1($sell->idPac);
?>
<tr>
	<td style="width:150px;">Cliente</td>
	<td><?php echo $client->name?></td>
</tr>

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
	<td>Atendido por</td>
	<td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>
</table>
<br>
<h3>Detalle Venta</h3>
<table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Cantidad</th>
		<th>Producto/Concepto</th>
		<th>Precio Unitario</th>
		<th>Total</th>

	</thead>
<?php
	foreach($operations as $operation){
		$product  = $operation->getProduct();
?>
<tr>
	<td><?php echo $product->id ;?></td>
	<td><?php echo $operation->q ;?></td>
	<td><?php echo $product->name ;?></td>
	<td>$ <?php echo number_format($operation->price,2,".",",") ;?></td>
	<td><b>$ <?php echo number_format($operation->q*$operation->price,2,".",",");$total+=$operation->q*$operation->price;?></b></td>
</tr>
<?php
	}
	?>
</table>

<br>
<h3>Detalle Pago</h3>
<table class="table table-bordered table-hover " style="width:400px;">
	<thead>
		<th style="width:280px;">Forma de Pago</th>
		<th style="width:120px;">Total</th>
		
	</thead>
<?php
	foreach($pagos as $pay){
		$product  = $operation->getProduct();
?>
<tr >
	<td style="width:280px;"><?php echo $pay->name ;?></td>
	<td style="width:120px;"><?php echo number_format($pay->cash,2) ;?></td>
	<?php  $totalPay+=$pay->cash;?>


<?php
	}
	?>
</tr>

</tr>
</table>
<table class="table table-bordered table-hover " style="width:400px;">

<tr><td style="width:280px;"></td>
<td style="width:120px;">
<b><?php echo number_format($totalPay,2) ?></b>
</td>
</table>
<br><br>
<div class="row">
<div class="col-md-4">
<table class="table table-bordered">
	<tr>
		<td><h4>Subtotal:</h4></td>
		<!--<td><h4>$ <?php echo number_format($total *0.84,2,'.',','); ?></h4></td>-->
		<td><h4>$ <?php echo number_format($total,2,'.',','); ?></h4></td>
	</tr>
	<tr>
		<td><h4>Pago:</h4></td>
		<td><h4>$ <?php echo number_format($totalPay,2,'.',','); ?></h4></td>
	</tr>
	<tr>
		<td><h4>Total:</h4></td>
		<td><h4>$ <?php echo number_format($total-	$sell->discount,2,'.',','); ?></h4></td>
	</tr>
	<!--tr>
		<td><h4>Cambio:</h4></td>
		<td><h4>$ <?php echo number_format($totalPay - $total,2,'.',','); ?></h4></td>
	</tr-->
</table>
</div>
</div>
<?php else:?>
	501 Internal Error
<?php endif; ?>