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
					<input type="hidden" name="view" value="inventory/index-supplies">
				</div>
				<div class="col-md-1">
					<button type="submit" class="btn btn-primary">Buscar</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php if ($searchBranchOfficeId != 0) : ?>
	<div class="row">
		<div class="col-md-12">
			<h1>Inventario Insumos</h1>
			<div class="clearfix"></div>
			<hr>
			<table id="dataTable" class="table table-bordered table-hover">
				<thead bgcolor="#eeeeee" align="center">
					<tr>
						<th>Nombre</th>
						<th>Disponible</th>
						<th>Mínimo</th>
						<th>Tipo</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>
</div>
<!--/.content-->
</div>
<!--/.span9-->
</div>


<!--/.wrapper--><br />
<script>
	$(document).ready(function() {
		var dataTable = $('#dataTable').DataTable({
			"language": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
			"ordering": false,
			"processing": true,
			"serverSide": true,
			"ajax": {
				url: "./?action=inventory/get-supplies", // json datasource
				data: {
					"searchBranchOfficeId": "<?php echo $searchBranchOfficeId ?>"
				},
				type: "post", // method  , by default get
				error: function() { // error handling
					$(".lookup-error").html("");
					$("#dataTable").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se encontró ningún dato.</th></tr></tbody>');
					$("#lookup_processing").css("display", "none");

				}
			}
		});
	});
</script>