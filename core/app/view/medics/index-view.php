<?php
$branchOffices = BranchOfficeData::getAllByStatus(1);
$searchBranchOfficeId = (isset($_GET["searchBranchOfficeId"])) ? $_GET["searchBranchOfficeId"] : 0;
if ($searchBranchOfficeId != 0) {
	$medics = MedicData::getAllByBranchOffice($searchBranchOfficeId);
}
?>
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
					<input type="hidden" name="view" value="medics/index">
				</div>
				<div class="col-md-1">
					<button type="submit" class="btn btn-primary">Buscar</button>
				</div>
				<div class="col-md-7">
					<div class="btn-group  pull-right">
						<a href="index.php?view=medics/new" class="btn btn-default"><i class="fas fa-user-md"></i><i class="fas fa-plus"></i> Agregar Psicólogo</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<script type="text/javascript">
			function confirmar() {
				var flag = confirm("¿Seguro que deseas eliminar el psicólogo?");
				if (flag == true) {
					return true;
				} else {
					return false;
				}
			}
		</script>
		<h1>Lista de Psicólogos</h1>
		<div class="clearfix"></div>

		<?php
		if (isset($medics) && count($medics) > 0) {
		?>
			<table class="table table-bordered table-hover">
				<thead>
					<th></th>
					<th>Nombre completo</th>
					<th>Área</th>
					<th>Sucursal</th>
					<th>Activo</th>
					<th></th>
				</thead>
				<?php foreach ($medics as $medic) : ?>
					<tr>
						<td style="background-color:<?php echo $medic->calendar_color ?>; width:2px;"></td>
						<td><?php echo $medic->name ?></td>
						<td><?php if ($medic->category_id != null) {
								echo $medic->getCategory()->name;
							} ?></td>
						<td><?php echo $medic->getBranchOffice()->name ?></td>
						<td>
							<?php if ($medic->is_active) : ?>
								<i class="glyphicon glyphicon-ok"></i>
							<?php endif; ?>
						</td>
						<td style="width:180px;">
							<a href="index.php?view=medics/edit&id=<?php echo $medic->id; ?>" class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> Editar</a>
							<?php if ($_SESSION["typeUser"] == "su") : ?>
								<button id="btnDeactivateMedic" onclick="deactivateMedic('<?php echo $medic->id ?>')" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i> Eliminar</button>
							<?php endif; ?>
						</td>
					</tr>
					<?php
					?>

			<?php endforeach;
			} else {
				echo "<p class='alert alert-danger'>No hay psicólogos registrados</p>";
			}
			?>
			</table>
	</div>
</div>
</div>
</div>
<script>
	function deactivateMedic(id) {
		Swal.fire({
			title: '¿Estás seguro de eliminar al psicólogo?',
			text: "¡No serás capaz de revertir esto!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Sí, Eliminar'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: "./?action=medics/deactivate", // json datasource
					type: "POST", // method, by default get
					data: {
						"id": id,
					},
					success: function() {
						location.reload();
					},
					error: function() { // error handling
						Swal.fire(
							'Error',
							'El psicólogo no se ha podido eliminar.',
							'error'
						);
					}
				});
			}
		})
	}
</script>