<?php
if (isset($_GET["productId"])) :
	$product = ProductData::getById($_GET["productId"]);
	$operations = OperationData::getAllByProductId($product->id);
?>
	<div class="row">
		<div class="col-md-12">
			<div class="btn-group pull-right">
			</div>
			<h1><?php echo $product->name;; ?> <small>Historial</small></h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?php $itotal = OperationData::GetInputQYesF($product->id); ?>
			<div class="jumbotron">
				<center>
					<h2>Entradas</h2>
					<h1><?php echo $itotal; ?></h1>
				</center>
			</div>

			<br>
		</div>

		<div class="col-md-4">
			<?php $total = OperationData::getStockByProduct($product->id); ?>
			<div class="jumbotron">
				<center>
					<h2>Disponibles</h2>
					<h1><?php echo $total; ?></h1>
				</center>
			</div>
			<div class="clearfix"></div>
			<br>
		</div>

		<div class="col-md-4">
			<?php $ototal = -1 * OperationData::GetOutputQYesF($product->id); ?>
			<div class="jumbotron">
				<center>
					<h2>Salidas</h2>
					<h1><?php echo $ototal; ?></h1>
				</center>
			</div>
			<div class="clearfix"></div>
			<br>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php if (count($operations) > 0) : ?>
				<table class="table table-bordered table-hover">
					<thead>
						<th></th>
						<th>Fecha registro</th>
						<th>Cantidad</th>
						<th>Tipo</th>
						<th>Lote</th>
						<th>Fecha expiración</th>

					</thead>
					<?php foreach ($operations as $operation) : ?>
						<tr>
							<td>
								<?php if (($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "sub") && $operation->operation_type_id == 1) : ?>
									<a href="index.php?view=inventory/edit-input-medicine&id=<?php echo $product->id ?>" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
									<a href="index.php?action=inventory/delete-input&id=<?php echo $product->id ?>" class="btn btn-xs btn-danger" onClick="return confirmDelete()"><i class="fa fa-trash"></i></a>
								<?php endif; ?>
							</td>
							<td><?php echo $operation->fecha; ?></td>
							<td><?php echo $operation->q; ?></td>
							<td><?php echo strtoupper($operation->getOperationType()->name); ?></td>
							<td><?php echo $operation->lot; ?></td>
							<td><?php echo ($operation->operation_type_id == 1) ? $operation->expiration_date_format : ""; ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
	</div>
	<script>
		function confirmDelete() {
			var flag = confirm("¿Seguro que deseas eliminar el producto? Esta acción no se puede revertir");
			if (flag == true) {
				return true;
			} else {
				return false;
			}
		}
	</script>

<?php endif; ?>