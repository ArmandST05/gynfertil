<?php
$user = UserData::getLoggedIn();
$userType = (isset($user)) ? $user->user_type : null;
$categories = PatientData::getAllCategories();
$companies = CompanyData::getAll();
$medics = MedicData::getAll();
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

?>
<div class="card" style="width:100%; margin-top:20px">
  <div class="card-body">
    <div class="row mb-3">

      <!-- Filtro por sucursal -->
      <div class="col-md-4">
        <label for="filter-branch-office">Sucursal:</label>
        <select id="filter-branch-office" name="branchOfficeId" class="form-control">
          <?php if ($userType == "r" || $userType == "co"): ?>
            <option value="<?= $branchOfficeId ?>"><?= htmlspecialchars($user->getBranchOffice()->name) ?></option>
          <?php else: ?>
            <option value="">Todas</option>
            <?php foreach ($branchOffices as $b): ?>
              <option value="<?= $b->id ?>" <?= ($branchOfficeId == $b->id ? 'selected' : '') ?>>
                <?= htmlspecialchars($b->name) ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <!-- Filtro por médico -->
      <div class="col-md-4">
        <label for="filter-medic">Médico:</label>
        <select id="filter-medic" name="medicId" class="form-control">
          <option value="">Todos</option>
          <?php foreach ($medics as $m): ?>
            <option value="<?= $m->id ?>"><?= htmlspecialchars($m->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Filtro por categoría -->
      <div class="col-md-4">
        <label for="filter-category">Categoría:</label>
        <select id="filter-category" name="categoryId" class="form-control">
          <option value="">Todas</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c->id ?>"><?= htmlspecialchars($c->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Filtro por empresa -->
      <div class="col-md-4 mt-3">
        <label for="filter-company">Empresa:</label>
        <select id="filter-company" name="companyId" class="form-control">
          <option value="">Todas</option>
          <?php foreach ($companies as $comp): ?>
            <option value="<?= $comp->id ?>"><?= htmlspecialchars($comp->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>



<table id="patients-table" class="display nowrap" style="width:100%">
<thead>
  <tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Sucursal</th>
    <th>Tratamiento</th>
    <th>Precio</th>        <!-- ✅ nuevo -->
    <th>Calle</th>         <!-- ✅ nuevo -->
    <th>Número</th>        <!-- ✅ nuevo -->
    <th>Colonia</th>
    <th>Municipio</th>
    <th>Cumpleaños</th>
    <th>Edad</th>
    <th>Sexo</th>
    <th>Celular</th>
    <th>Estatus</th>
    <th>Inicio</th>
    <th>Psicologo</th>
    <th>Motivo consulta</th>
    <th>Nivel educativo</th>
  
  </tr>
</thead>

</table>


    </div>
  </div>
</div>
<script>
var dataTable;

$(document).ready(function () {
  dataTable = $('#patients-table').DataTable({
    language: {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo: "Mostrando _START_ a _END_ de _TOTAL_ registros",
      sInfoFiltered: "(filtrado de _MAX_ registros totales)",
      sLoadingRecords: "Cargando...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior"
      }
    },
    processing: true,
    serverSide: true,
    ordering: false,
    responsive: true,
    scrollX: true,
    dom: '<"datatable-content"t><"datatable-footer"ip>',
pageLength: 5000,  // Muestra 20 por página
lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],  // Opciones para el usuario


   ajax: {
  url: './?action=patients/get-filter',
  type: 'POST',
  dataType: 'json',
  data: function (d) {
    d.categoryId = $('#filter-category').val();
    d.companyId = $('#filter-company').val();
    d.branchOfficeId = $('#filter-branch-office').val();
  }
},
columns: [
  { data: 0 },  // ID paciente
  { data: 1 },  // Nombre
  { data: 2 },  // Sucursal
  { data: 3 },  // Tratamiento
  { data: 4 },  // Precio
  { data: 5 },  // Calle
  { data: 6 },  // Número
  { data: 7 },  // Colonia
  { data: 8 },  // Municipio
  { data: 9 },  // Cumpleaños
  { data: 10 }, // Edad
  { data: 11 }, // Sexo
  { data: 12 }, // Celular
  { data: 13 }, // Estatus
  { data: 14 }, // Inicio
  { data: 15 }, // Médico actual
  { data: 16 }, // Motivo
  { data: 17 }, // Escolaridad
 // { data: 18 }, // Ocupación
 // { data: 19 }, // Médico anterior 1
  //{ data: 20 }, // Médico anterior 2
  //{ data: 21 },  // Fin ✅
  //{ data: 22 },
 // { data: 23 },
 // { data: 24 },
  //{ data: 25 },
 // { data: 26}
]

  });

  $('#filter-category, #filter-branch-office, #filter-medic, filter-company').on('change keyup', function () {
    dataTable.ajax.reload();
  });
});




</script>