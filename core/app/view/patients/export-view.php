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
    <div class="row">

      <!-- Filtro por sucursal -->
      <div class="col-md-3">
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
      <div class="col-md-3">
        <label for="filter-medic">Médico:</label>
        <select id="filter-medic" name="medicId" class="form-control">
          <option value="">Todos</option>
          <?php foreach ($medics as $m): ?>
            <option value="<?= $m->id ?>"><?= htmlspecialchars($m->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Filtro por categoría -->
      <div class="col-md-3">
        <label for="filter-category">Categoría:</label>
        <select id="filter-category" name="categoryId" class="form-control">
          <option value="">Todas</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c->id ?>"><?= htmlspecialchars($c->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Filtro por empresa -->
      <div class="col-md-3">
        <label for="filter-company">Empresa:</label>
        <select id="filter-company" name="companyId" class="form-control">
          <option value="">Todas</option>
          <?php foreach ($companies as $comp): ?>
            <option value="<?= $comp->id ?>"><?= htmlspecialchars($comp->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

    </div>
  </div>
</div>


<table id="patients-table" class="display nowrap" style="width:100%">
<thead>
  <tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Sucursal</th>
    <th>Tratamiento</th>
    <th>Precio</th>
    <th>Calle</th>
    <th>Número</th>
    <th>Colonia</th>
    <th>Municipio</th>
    <th>Cumpleaños</th>
    <th>Edad</th>
    <th>Sexo</th>
    <th>Celular</th>
    <th>Estatus</th>
    <th>Inicio</th>
    <th>Psicólogo</th>
    <th>Motivo consulta</th>
    <th>Nivel educativo</th>
    <th>Ocupación</th>             <!-- data[18] -->
    <th>Psicólogo anterior 1</th>  <!-- data[19] -->
    <th>Psicólogo anterior 2</th>  <!-- data[20] -->
    <th>Fin</th>                   <!-- data[21] -->
    <th>Motivo cancelación</th>   <!-- data[22] -->
    <th>Duración</th>             <!-- data[23] -->
    <th>Total sesiones</th>       <!-- data[24] -->
    <th>Última nota</th>          <!-- data[25] -->
    <th>Empresa</th>              <!-- data[26] -->
    <th>Observaciones</th>        <!-- data[27] -->
  </tr>
</thead>


</table>


    </div>
  </div>
</div>
<div id="loading-overlay" style="
  position: fixed;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255,255,255,0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 20px;
  color: #333;
  z-index: 9999;
  display: none;
">
  Cargando datos...
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
    pageLength: 50,
    lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
    ajax: {
      url: './?action=patients/get-filter',
      type: 'POST',
      dataType: 'json',
      data: function (d) {
        d.branchOfficeId = $('#filter-branch-office').val();
        d.medicId = $('#filter-medic').val();
        d.categoryId = $('#filter-category').val();
        d.companyId = $('#filter-company').val();
      },
      beforeSend: function () {
        $('#loading-overlay').show();
      },
      complete: function () {
        $('#loading-overlay').hide();
      }
    },
    columns: [
      { data: 0 }, { data: 1 }, { data: 2 }, { data: 3 }, { data: 4 },
      { data: 5 }, { data: 6 }, { data: 7 }, { data: 8 }, { data: 9 },
      { data: 10 }, { data: 11 }, { data: 12 }, { data: 13 }, { data: 14 },
      { data: 15 }, { data: 16 }, { data: 17 }, { data: 18 }, { data: 19 },
      { data: 20 }, { data: 21 }, { data: 22 }, { data: 23 }, { data: 24 },
      { data: 25 }, { data: 26 }, { data: 27 }
    ]
  });

  $('#filter-branch-office, #filter-medic, #filter-category, #filter-company').on('change', function () {
    dataTable.ajax.reload();
  });
});




</script>