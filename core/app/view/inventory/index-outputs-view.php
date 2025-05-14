<?php
$user = UserData::getLoggedIn();
$userType = (isset($user)) ? $user->user_type : null;

if ($userType == "r") {
	$searchBranchOfficeId = $user->getBranchOffice()->id;
	$branchOffices = [$user->getBranchOffice()];
} else {
	if ($userType == "co") {
		$searchBranchOfficeId = (isset($_GET["searchBranchOfficeId"])) ? $_GET["searchBranchOfficeId"] : $user->getBranchOffice()->id;
	} else {
		$searchBranchOfficeId = (isset($_GET["searchBranchOfficeId"])) ? $_GET["searchBranchOfficeId"] : 0;
	}
	$branchOffices = BranchOfficeData::getAllByStatus(1);
}
?>
<div class="row">
	<div class="col-md-12">
		<?php if ($searchBranchOfficeId != 0) : ?>
			<div class="btn-group pull-right">
				<a href="index.php?view=inventory/new-output&branchOfficeId=<?php echo $searchBranchOfficeId ?>" class="btn btn-default">Nueva salida</a>
			</div>
		<?php endif; ?>
		<h1><i class='glyphicon glyphicon-shopping-cart'></i> Salidas de medicamentos/productos/insumos</h1>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal" method="GET" enctype="multipart/form-data" action="index.php" role="form">
					<div class="form-group">
						<label for="inputEmail1" class="col-lg-1 control-label">Sucursal</label>
						<div class="col-md-3">
							<select name="searchBranchOfficeId" class="form-control" required>
								<option value="">-- SELECCIONE --</option>
								<?php foreach ($branchOffices as $branchOffice) : ?>
									<option value="<?php echo $branchOffice->id; ?>" <?php echo ($searchBranchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
								<?php endforeach; ?>
							</select>
							<input type="hidden" name="view" value="inventory/index-outputs">
						</div>
						<div class="col-md-1">
							<button type="submit" class="btn btn-primary">Buscar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
		$outputs = OperationData::getByBranchOfficeTypeId($searchBranchOfficeId, 2);
		if (count($outputs) > 0) {
		?>
			<br>
			<table class="table table-bordered table-hover" id="dataTable">
				<thead>
					<th></th>
					<th>Folio</th>
					<th>Cantidad Insumos</th>
					<th>Fecha</th>
					<th></th>
				</thead>
				<?php foreach ($outputs as $output) : ?>
					<tr>
						<td style="width:30px;"><a href="index.php?view=inventory/edit-output&id=<?php echo $output->id; ?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i></a></td>
						<?php
						echo "<td>" . $output->id . "</td><td>";
						$operations = OperationDetailData::getAllProductsByOperationId($output->id);
						echo count($operations);
						?>
						</td>
						<td><?php echo $output->created_at; ?></td>
						<td><?php echo $output->description; ?></td>
						<!--td style="width:30px;"><a href="index.php?action=sales/delete-output&id=<?php echo $output->id; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a></td-->
					</tr>
				<?php endforeach; ?>

			</table>
		<?php
		} else {
		?>
			<div class="jumbotron">
				<h2>No hay datos</h2>
				<p>No se ha realizado ninguna operación.</p>
			</div>
		<?php
		}
		?>
	</div>
</div>
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