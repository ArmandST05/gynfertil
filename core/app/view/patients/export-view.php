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
            <option value="1">Selecciona un filtro</option>
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
          <option value="1">Selecciona un filtro</option>
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
          <option value="1">Selecciona un filtro</option>
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
          <option value="1">Selecciona un filtro</option>
          <option value="">Todas</option>
          <?php foreach ($companies as $comp): ?>
            <option value="<?= $comp->id ?>"><?= htmlspecialchars($comp->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

    </div>
  </div>
</div>

  <button id="btn-export-excel" class="btn btn-success">Exportar a Excel</button>

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
      <th>Ocupación</th>
      <th>Psicólogo anterior 1</th>
      <th>Psicólogo anterior 2</th>
      <th>Fin</th>
      <th>Motivo cancelación</th>
      <th>Duración</th>
      <th>Total sesiones</th>
      <th>Última nota</th>
      <th>Empresa</th>
      <th>Observaciones</th>
      <th>Psiquiatra</th>
    </tr>
  </thead>
  <tbody></tbody>
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
<!-- Asegúrate de tener este script incluido en tu HTML -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
// Asegúrate de que DataTables y sus dependencias están correctamente cargadas en tu servidor
// Incluye jQuery, DataTables, y opcionalmente los plugins de botones si deseas exportar

let dataTable;

function cargarPacientes() {
  const filters = {
    branchOfficeId: $('#filter-branch-office').val(),
    medicId: $('#filter-medic').val(),
    categoryId: $('#filter-category').val(),
    companyId: $('#filter-company').val()
  };

  $.ajax({
    url: './?action=patients/get-filter',
    method: 'POST',
    data: filters,
    dataType: 'json',
    beforeSend: function () {
      $('#loading-overlay').show();
    },
    success: function (response) {
      const datos = Array.isArray(response.data) ? response.data : [];

      if ($.fn.dataTable.isDataTable('#patients-table')) {
        dataTable.clear().rows.add(datos).draw();
      } else {
        dataTable = $('#patients-table').DataTable({
          data: datos,
          pageLength: 20,
          responsive: true,
          scrollX: true,
          ordering: false,
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
          dom: 'Bfrtip',
          buttons: [
            {
              text: 'Exportar a Excel',
              action: function () {
                exportarExcel(datos);
              }
            }
          ]
        });
      }
    },
    error: function () {
      $('#patients-table tbody').html('<tr><td colspan="28" class="text-center">Error al obtener los datos</td></tr>');
    },
    complete: function () {
      $('#loading-overlay').hide();
    }
  });
}

function exportarExcel(data) {
  const ws = XLSX.utils.aoa_to_sheet([
    [
      'ID', 'Nombre', 'Sucursal', 'Tratamiento', 'Precio', 'Calle', 'Número', 'Colonia',
      'Municipio', 'Cumpleaños', 'Edad', 'Sexo', 'Celular', 'Estatus', 'Inicio', 'Psicólogo',
      'Motivo consulta', 'Nivel educativo', 'Ocupación', 'Psicólogo anterior 1', 'Psicólogo anterior 2',
      'Fin', 'Motivo Baja', 'Duración', 'Total sesiones', 'Ultima nota', 'Empresa', 'Observaciones', 'Psiquiatra'
    ],
    ...data
  ]);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Pacientes');
  XLSX.writeFile(wb, 'PacientesFiltrados.xlsx');
}

$('#filter-branch-office, #filter-medic, #filter-category, #filter-company').on('change', function () {
  cargarPacientes();
});

$(document).ready(function () {
  cargarPacientes();
});
$('#btn-export-excel').on('click', function () {
  if (dataTable) {
    const allData = dataTable.rows().data().toArray();
    exportarExcel(allData);
  } else {
    alert('No hay datos para exportar.');
  }
});

</script>

