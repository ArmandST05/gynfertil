<?php
$user = UserData::getLoggedIn();
$userType = (isset($user)) ? $user->user_type : null;

$categories = PatientData::getAllCategories();
$companies = CompanyData::getAll();

if ($userType == "r") {
	$branchOfficeId = $user->getBranchOffice()->id;
	$branchOffices = [$user->getBranchOffice()];
} else {
	$branchOffices = BranchOfficeData::getAllByStatus(1);
	if ($userType == "co") {
		$branchOfficeId = (isset($_GET["branchOfficeId"])) ? $_GET["branchOfficeId"] : $user->getBranchOffice()->id;
	} else {
		$branchOfficeId = (isset($_GET["branchOfficeId"])) ? $_GET["branchOfficeId"] : 0;
	}
}
if ($branchOfficeId) {
	$medics = MedicData::getAllByBranchOffice($branchOfficeId);
}

$medicId = (isset($_GET["medicId"])) ? $_GET["medicId"] : 0;
$categoryId = (isset($_GET["categoryId"])) ? $_GET["categoryId"] : "all";
$companyId = (isset($_GET["companyId"])) ? $_GET["companyId"] : "all";
?>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-5 pull-right">
				<div class="btn-group">
					<a href="index.php?view=patients/new" class="btn btn-default"><i class="fas fa-user"></i><i class="fas fa-plus"></i> Agregar Paciente</a>
					<?php if ($userType == "su") : ?>
						<a href="index.php?view=reports/patients-excel" target="_blank" class="btn btn-primary" onclick="addLog(0,1,4,'Se descargó el archivo de Respaldo de todos los pacientes')"><i class="fas fa-download"></i> Respaldar todos los pacientes</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<h1>Lista de Pacientes</h1>
		<form method="GET" action="index.php">
			<input type="hidden" name="view" value="patients/index">
			<div class="row">
				<div class="col-md-3">
					<label class="control-label">Sucursal:</label>
					<select name="branchOfficeId" class="form-control" required>
						<?php if ($userType == "su" || $userType == "co") : ?>
							<option value="0">-- TODAS --</option>
						<?php endif; ?>
						<?php foreach ($branchOffices as $branchOffice) : ?>
							<option value="<?php echo $branchOffice->id; ?>" <?php echo ($branchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label">Psicólogo:</label>
					<div class="form-group">
						<select name="medicId" class="form-control" required>
							<option value="0">-- TODOS --</option>
							<?php foreach ($medics as $medic) : ?>
								<option value="<?php echo $medic->id; ?>" <?php echo ($medicId == $medic->id) ? "selected" : "" ?>><?php echo $medic->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<label class="control-label">Categoría:</label>
					<div class="form-group">
						<select name="categoryId" class="form-control" required>
							<option value="all" <?php echo ($categoryId == "all") ? "selected" : "" ?>>-- TODOS --</option>
							<option value="active" <?php echo ($categoryId == "active") ? "selected" : "" ?>>ACTIVOS</option>
							<option value="1" <?php echo ($categoryId == 1) ? "selected" : "" ?>>ACTIVO (NO REINGRESO)</option>
							<option value="4" <?php echo ($categoryId == 4) ? "selected" : "" ?>>ACTIVO (REINGRESO)</option>
							<option value="2" <?php echo ($categoryId == 2) ? "selected" : "" ?>>ALTA</option>
							<option value="3" <?php echo ($categoryId == 3) ? "selected" : "" ?>>INACTIVO</option>
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<label class="control-label">Empresa:</label>
					<div class="form-group">
						<select name="companyId" class="form-control" required>
							<option value="all" <?php echo ($companyId == "all") ? "selected" : "" ?>>-- TODOS --</option>
							<option value="company" <?php echo ($companyId == "company") ? "selected" : "" ?>>SON DE EMPRESA</option>
							<option value="withoutCompany" <?php echo ($companyId == "withoutCompany") ? "selected" : "" ?>>NO SON DE EMPRESA</option>
							<?php foreach ($companies as $company) : ?>
								<option value="<?php echo $company->id ?>" <?php echo ($companyId == $company->id) ? "selected" : "" ?>><?php echo $company->name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<br>
					<input type="submit" class="btn btn-sm btn-primary btn-block" value="Buscar">
				</div>
			</div>
		</form>
		<hr>
		<table id="lookup" class="table table-bordered table-hover" class="display" style="width:100%">
			<thead bgcolor="#eeeeee" align="center">
				<tr>
					<th>Clave</th>
					<th>Nombre completo</th>
					<th>Dirección</th>
					<th>Teléfonos</th>
					<th>Email</th>
					<th>Familiar</th>
					<th>Psicólogo</th>
					<th>Empresa</th>
					<th>Categoría</th>
					<th class="text-center"> Acciones </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

	</div>
</div>

</div>
<!--/.content-->
</div>
<!--/.span9-->
</div>


<!--/.wrapper-->

<script>
	$(document).ready(function() {
		var dataTable = $('#lookup').DataTable({

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

				url: "./?action=patients/get-all", // json datasource
				data: {
					"branchOfficeId": "<?php echo $branchOfficeId ?>",
					"medicId": "<?php echo $medicId ?>",
					"categoryId": "<?php echo $categoryId ?>",
					"companyId": "<?php echo $companyId ?>"
				},
				type: "post", // method  , by default get
				error: function() { // error handling
					$(".lookup-error").html("");
					$("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se han encontrado datos.</th></tr></tbody>');
					$("#lookup_processing").css("display", "none");

				}
			}
		});
	});
</script>