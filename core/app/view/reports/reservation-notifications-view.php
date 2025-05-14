<?php
//Datos del usuario
$user = UserData::getLoggedIn();
$userId = $user->id;
$userType = $user->user_type;

$startDate = (isset($_GET["startDate"])  ? $_GET['startDate'] : date("Y-m-d"));
$endDate = (isset($_GET["endDate"])  ? $_GET['endDate'] : date("Y-m-d", strtotime("+7 days")));

$branchOfficeId = isset($_GET["branchOfficeId"])  ? $_GET['branchOfficeId'] : 0;
$typeId = (isset($_GET["typeId"])  ? $_GET['typeId'] : 0);
$medicId = (isset($_GET["medicId"])  ? $_GET['medicId'] : 0);

if ($userType == "su" || $userType == "co") {
  $branchOffices = BranchOfficeData::getAllByStatus(1);
  $medics = MedicData::getAllByStatus(1);
} else {
  $branchOffice = $user->getBranchOffice();
  $medics = MedicData::getAllByBranchOffice($branchOffice->id);
  $branchOffices = [$branchOffice];
}

$reservations = ReservationData::getByPatientNotifiedDates($startDate, $endDate, $branchOfficeId, $medicId, $typeId);
$rowClass = "danger";
if ($typeId == 1) {
  $rowClass = "success";
}
?>

<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <h1>Reporte de recordatorios citas</h1>
      <form method="GET" action="index.php">
        <input type="hidden" name="view" value="reports/reservation-notifications">
        <div class="row">
          <div class="col-md-4">
            <label class="control-label">Sucursal:</label>
            <select name="branchOfficeId" class="form-control" required>
              <option value="0">-- SELECCIONE --</option>
              <?php foreach ($branchOffices as $branchOffice) : ?>
                <option value="<?php echo $branchOffice->id; ?>" <?php echo ($branchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="control-label">Tipo:</label>
            <div class="form-group">
              <select name="typeId" class="form-control" required>
                <option value="0" <?php echo ($typeId == 0) ? "selected" : "" ?>>PENDIENTE RECORDATORIO</option>
                <option value="1" <?php echo ($typeId == 1) ? "selected" : "" ?>>RECORDADAS</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
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
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="inputEmail1" class="control-label">Desde:</label>
            <input type="date" name="startDate" value="<?php echo $startDate ?>" class="form-control">
          </div>
          <div class="col-md-4">
            <label for="inputEmail1" class="control-label">Hasta:</label>
            <input type="date" name="endDate" value="<?php echo $endDate ?>" class="form-control">
          </div>
          <div class="col-md-2">
            <br>
            <input type="submit" class="btn btn-sm btn-success btn-block" value="Procesar">
          </div>
          <?php if ($userType == "su") : ?>
            <div class="col-md-2">
              <br>
              <input type="button" class="btn btn-sm btn-primary btn-block" value="Exportar" id="btnExport" onclick="addLog(0,7,4,'Se descargó el archivo de Reporte de recordatorios de citas')">
            </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
  <br>
  <?php if (count($reservations) > 0) : ?>
    <div class="row">
      <div class="col-md-12">
        <?php if (count($reservations) > 0) : ?>
          <div class="clearfix"></div>
          <table class="table table-bordered table-hover" id='datosexcel' border='1'>
            <thead>
              <tr>
                <th>Sucursal</th>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Teléfonos</th>
                <th>Psicólogo</th>
                <th>Categoría</th>
                <th>Estatus</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <?php
            $total = 0;
            foreach ($reservations as $reservation) :
              $totalPatientReservations = ReservationData::getTotalReservationsByPatient($reservation->patient_id, 2);
              $total++;
            ?>
              <tr class="<?php echo $rowClass ?>" id="r-<?php echo $reservation->id ?>">
                <td><?php echo $reservation->branch_office_name ?></td>
                <td><?php echo $reservation->day_name . " " . $reservation->date_at_format ?></td>
                <td><?php echo $reservation->patient_name ?></td>
                <td><?php echo $reservation->patient_cellphone . " <br>" . $reservation->patient_homephone ?></td>
                <td><?php echo $reservation->medic_name ?></td>
                <!--<td><?php echo $reservation->reservation_category_name ?></td>-->
                <td><?php echo ($totalPatientReservations->total > 0) ? "SUBSECUENTE" : "PRIMERA VEZ" ?></td>
                <td><?php echo $reservation->status_name ?></td>
                <td>
                  <label>
                    <input type="checkbox" id="checkboxNotified-<?php echo $reservation->id ?>" value="1" onchange="selectPatientNotified(<?php echo $reservation->id ?>)" <?php echo (($reservation->is_patient_notified == 1) ? "checked" : "") ?>>
                    Recordatorio a paciente
                  </label>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>

        <?php else : ?>
          <p class='alert alert-danger'>No se encontraron registros.</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</section>
<script type="text/javascript">
  $(document).ready(function() {

    $("#btnExport").click(function(e) {
      $("#datosexcel").btechco_excelexport({
        containerid: "datosexcel",
        datatype: $datatype.Table,
        filename: 'Reporte de recordatorios'
      });

    });
  });

  function selectPatientNotified(reservationId) {
    let value = 0;
    if ($('#checkboxNotified-' + reservationId).prop('checked')) {
      value = 1;
    }
    updatePatientNotified(reservationId, value);
  }

  function updatePatientNotified(reservationId, value) {
    $.ajax({
      url: "./?action=reservations/update-reservation-notified", // json datasource
      type: "POST", // method, by default get
      data: {
        reservationId: reservationId,
        value: value
      },
      success: function(data) {
        Toast.fire({
          icon: 'success',
          title: 'Actualizado recordatorio al paciente.'
        });
        $("#r-" + reservationId).remove();
      },
      error: function() { // error handling
        Swal.fire({
          title: '¡Atención!',
          text: 'Error al actualizar el recordatorio al paciente.',
          icon: 'error',
          confirmButtonText: 'Aceptar'
        });
      }
    });
  }
</script>