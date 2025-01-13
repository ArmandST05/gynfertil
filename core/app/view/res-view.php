<div class="row">
	<div class="col-md-12">
	<div class="btn-group pull-right">
  <a href="index.php?view=salidas" class="btn btn-default">Salidas de insumos</a>
</div>
<h1><i class='glyphicon glyphicon-shopping-cart'></i> Salidas de insumos</h1>
<div class="clearfix"></div>


<?php
$products = SellData::getSal("");

if(count($products)>0){
	?>
<br>
<table class="table table-bordered table-hover	">
	<thead>
		<th></th>
		<th>Folio</th>
		<th>Can Insumos</th>
		<th>Fecha</th>
		<th></th>
	</thead>
	<?php foreach($products as $sell):?>

	<tr>
		<td style="width:30px;"><a href="index.php?view=Salupd&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>

		

<?php
echo "<td>".$sell->id."</td><td>";

$operations = OperationData::getAllProductsBySellId($sell->id);
echo count($operations);
?>
		</td>
		<td><?php echo $sell->created_at; ?></td>
		<td><?php echo $sell->comentarios; ?></td>
		<!--td style="width:30px;"><a href="index.php?view=delre&id=<?php echo $sell->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a></td-->
	</tr>

<?php endforeach; ?>

</table>


	<?php
}else{
	?>
	<div class="jumbotron">
		<h2>No hay datos</h2>
		<p>No se ha realizado ninguna operacion.</p>
	</div>
	<?php
}

?>
<br><br><br><br><br><br><br><br><br><br>
	</div>
</div>