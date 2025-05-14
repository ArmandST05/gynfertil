<?php
if (isset($_GET["id"])) :
	$product = ProductData::getById($_GET["id"]);
	$operations = OperationDetailData::getAllByProductId($product->id);

	$user = UserData::getLoggedIn();
	$userType = (isset($user)) ? $user->user_type : null;

	if ($userType == "r") {
		$branchOfficeId = $user->getBranchOffice()->id;
		$branchOffices = [$user->getBranchOffice()];
	} else {
		if ($userType == "co") {
			$branchOfficeId = (isset($_GET["branchOfficeId"])) ? $_GET["branchOfficeId"] : $user->getBranchOffice()->id;
		} else {
			$branchOfficeId = (isset($_GET["branchOfficeId"])) ? $_GET["branchOfficeId"] : 0;
		}
		$branchOffices = BranchOfficeData::getAllByStatus(1);
	}
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
			<?php
			$totalInputs = OperationDetailData::getByOperationTypeBranchOfficeProduct($branchOfficeId, $product->id, 1);
			?>
			<div class="jumbotron">
				<center>
					<h2>Entradas</h2>
					<h1><?php echo $totalInputs; ?></h1>
				</center>
			</div>
			<br>
		</div>

		<div class="col-md-4">
			<?php
			$totalStock = OperationDetailData::getStockByBranchOfficeProduct($branchOfficeId, $product->id);
			?>
			<div class="jumbotron">
				<center>
					<h2>Disponibles</h2>
					<h1><?php echo $totalStock; ?></h1>
				</center>
			</div>
			<div class="clearfix"></div>
			<br>

		</div>

		<div class="col-md-4">
			<?php
			$totalOutputs = -1 * OperationDetailData::getByOperationTypeBranchOfficeProduct($branchOfficeId, $product->id, 2);
			?>
			<div class="jumbotron">
				<center>
					<h2>Salidas</h2>
					<h1><?php echo $totalOutputs; ?></h1>
				</center>
			</div>


			<div class="clearfix"></div>
			<br>
			<?php
			?>

		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php if (count($operations) > 0) : ?>
				<table class="table table-bordered table-hover" id="dataTable">
					<thead>
						<th>Cantidad</th>
						<th>Tipo</th>
						<th>Fecha</th>

					</thead>
					<?php foreach ($operations as $operation) : ?>
						<tr>
							<td><?php echo $operation->quantity; ?></td>
							<td><?php echo strToUpper($operation->getOperationType()->name); ?></td>
							<td><?php echo $operation->date; ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
	</div>

<?php endif; ?>
<script>
	$(document).ready(function() {
		var dataTable = $('#dataTable').DataTable({
			pageLength: 50,
			ordering: false,
			language: {
				url: 'plugins/datatables/languages/es-mx.json'
			}
		});
	});
</script>