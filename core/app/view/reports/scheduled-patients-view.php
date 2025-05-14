<?php
//Datos del usuario
$user = UserData::getLoggedIn();
$userId = $user->id;
$userType = $user->user_type;

$reservationStatus = ReservationStatusData::getAll();

if (isset($_GET["sd"])) {
  $startDate = $_GET['sd'];
  $startDateTime = $_GET['sd'] . " 00:00:01";
} else {
  $startDate = null;
  $startDateTime = null;
}
if (isset($_GET["ed"])) {
  $endDate = $_GET['ed'];
  $endDateTime = $_GET['ed'] . " 23:59:59";
} else {
  $endDate = null;
  $endDateTime = null;
}

$branchOfficeId = isset($_GET["branchOfficeId"])  ? $_GET['branchOfficeId'] : 0;
$medicId = isset($_GET["medicId"])  ? $_GET['medicId'] : 0;
$statusId = isset($_GET["statusId"])  ? $_GET['statusId'] : "all"; //Asistencia a la cita
$patientTypeId = isset($_GET["patientTypeId"])  ? $_GET['patientTypeId'] : 0;

//Datos por sucursal
if ($userType == "su" || $userType == "co") {
  $branchOffices = BranchOfficeData::getAllByStatus(1);
  $medics = MedicData::getAllByStatus(1);
} else {
  $branchOffice = $user->getBranchOffice();
  $medics = MedicData::getAllByBranchOffice($branchOffice->id);
  $branchOffices = [$branchOffice];
}

$paymentTypes = PaymentTypeData::getAll();

$title = "Reporte pacientes agendados y por agendar";
$scheduledTitle = "Agendados";
if ($statusId == "notscheduled") {
  $title = "Reporte pacientes no agendados";
} else if ($statusId == "scheduled") {
  $title = "Reporte pacientes agendados (programados, sí asistieron y no asistieron)";
} elseif ($statusId == 1) {
  $title = "Reporte pacientes programados";
  $scheduledTitle = "Agendados (Programados)";
} elseif ($statusId == 2) {
  $title = "Reporte pacientes agendados y sí asistieron";
  $scheduledTitle = "Agendados (Sí asistieron)";
} elseif ($statusId == 3) {
  $title = "Reporte pacientes agendados y no asistieron";
  $scheduledTitle = "Agendados (No asistieron)";
}
if ($startDateTime && $startDateTime) {
  $scheduledPatients = ReservationData::getScheduledPatientsByBranchOffice($branchOfficeId, $startDateTime, $endDateTime, $medicId, $statusId, $patientTypeId);
  $notScheduledPatients = ReservationData::getNotScheduledPatientsByBranchOffice($branchOfficeId, $startDateTime, $endDateTime, $medicId, $patientTypeId);
} else {
  $scheduledPatients = [];
  $notScheduledPatients = [];
}
$totalOnlyScheduled = 0;
$totalAttendedScheduled = 0;
$totalNotAssistScheduled = 0;
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <h1><?php echo $title ?></h1>
      <form method="GET" action="index.php">
        <input type="hidden" name="view" value="reports/scheduled-patients">
        <div class="row">
          <div class="col-md-3">
            <label class="control-label">Sucursal:</label>
            <select name="branchOfficeId" class="form-control" required>
              <option value="0">-- SELECCIONE --</option>
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
            <label class="control-label">Estatus de cita:</label>
            <div class="form-group">
              <select name="statusId" class="form-control" required>
                <option value="all">-- TODOS --</option>
                <option value="notscheduled" <?php echo ($statusId == "notscheduled") ? "selected" : "" ?>>NO AGENDADA</option>
                <option value="scheduled" <?php echo ($statusId == "scheduled") ? "selected" : "" ?>>AGENDADA</option>
                <?php foreach ($reservationStatus as $status) : ?>
                  <option value="<?php echo $status->id; ?>" <?php echo ($statusId == $status->id) ? "selected" : "" ?>><?php echo $status->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <label class="control-label">Tipo de paciente:</label>
            <div class="form-group">
              <select name="patientTypeId" class="form-control" required>
                <option value="0">--TODOS--</option>
                <option value="1" <?php echo ($patientTypeId == 1) ? "selected" : "" ?>>CON TRATAMIENTO</option>
                <option value="2" <?php echo ($patientTypeId == 2) ? "selected" : "" ?>>SIN TRATAMIENTO</option>
                <option value="3" <?php echo ($patientTypeId == 3) ? "selected" : "" ?>>NUEVOS</option>
                <option value="4" <?php echo ($patientTypeId == 4) ? "selected" : "" ?>>REINGRESO</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <label for="inputEmail1" class="control-label">Desde:</label>
            <input type="date" name="sd" value="<?php echo $startDate ?>" class="form-control">
          </div>
          <div class="col-md-3">
            <label for="inputEmail1" class="control-label">Hasta:</label>
            <input type="date" name="ed" value="<?php echo $endDate ?>" class="form-control">
          </div>
          <div class="col-md-2">
            <br>
            <input type="submit" class="btn btn-sm btn-success btn-block" value="Procesar">
          </div>
          <?php if ($userType == "su") : ?>
            <div class="col-md-2">
              <br>
              <input type="submit" class="btn btn-sm btn-primary btn-block" value="Exportar" id="btnExport" onclick="addLog(0,7,4,'Se descargó el archivo de Reporte de pacientes agendados y por agendar')">
            </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
  <br>
  <?php if ((count($scheduledPatients) > 0 || count($notScheduledPatients) > 0) && ($statusId == "all" || $statusId == "scheduled" || $statusId == "notscheduled")) : ?>
    <div class="row">
      <div class="col-lg-6" class="text-center">
        <div style="width:20vw"><canvas id="myChart"></canvas></div>
        <div class="ct-chart .ct-perfect-fifth"></div>
      </div>
      <?php if ($statusId != "notscheduled") : ?>
        <div class="col-lg-6" class="text-center">
          <div style="width:20vw"><canvas id="myChartScheduled"></canvas></div>
          <div class="ct-chart .ct-perfect-fifth"></div>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <div class="row">
    <div class="col-md-12">
      <?php if (count($scheduledPatients) > 0 && ($statusId == "all" || $statusId != "notscheduled")) : ?>
        <div class="clearfix"></div>
        <h3><?php echo $scheduledTitle ?></h3>
        <table class="table table-bordered table-hover" id='datosexcel' border='1'>
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Paciente</th>
              <th>Teléfono</th>
              <th>Psicólogo</th>
              <th>Tratamiento</th>
              <th>Estatus</th>
            </tr>
          </thead>
          <?php
          $totalScheduled = 0;
          foreach ($scheduledPatients as $scheduledPatient) :
            $totalScheduled++;
            $patient = $scheduledPatient->getPatient();
            $patientTreatment = TreatmentData::getPatientTreatmentByDates($patient->id, $startDate, $endDate);

            if ($scheduledPatient->status_id == 1) {
              $totalOnlyScheduled++;
            } else if ($scheduledPatient->status_id == 2) {
              $totalAttendedScheduled++;
            } else if ($scheduledPatient->status_id == 3) {
              $totalNotAssistScheduled++;
            }

          ?>
            <tr class='success'>
              <td><?php echo $scheduledPatient->day_name . " " . $scheduledPatient->date_at ?></td>
              <td><?php echo $patient->name ?></td>
              <td><?php echo $patient->cellphone . "/" . $patient->homephone ?></td>
              <td><?php echo $scheduledPatient->getMedic()->name ?></td>
              <td><?php echo ($patientTreatment) ? $patientTreatment->treatment_name : "" ?></td>
              <td><?php echo $scheduledPatient->getStatus()->name ?>
                <?php if ($statusId == 4) :
                  //Verificar si la cita cancelada ya tiene la venta asociada liquidada.
                  //El producto de cancelación es el #14 COBRO POR CANCELACIÓN
                  $cancelationSale = OperationData::getByReservationProductStatus($scheduledPatient->id, 14, "all");
                ?>
                  <?php if (!$cancelationSale) : ?>
                    <br><a target="_blank" href="index.php?view=sales/new-details&reservationId=<?php echo $scheduledPatient->id ?>&patientId=<?php echo $patient->id ?>&medicId=<?php echo $scheduledPatient->medic_id ?>&date=<?php echo date('Y-m-d H:i:s') ?>" class="btn btn-sm btn-danger"><i class="fas fa-circle-exclamation"></i> PENDIENTE VENTA CANCELACIÓN</a>
                    <?php
                    $generalSale = OperationData::getByReservationProductStatus($scheduledPatient->id, 0, "all");
                    if ($generalSale) : ?>
                      <br><a target="_blank" href="index.php?view=sales/edit&id=<?php echo $generalSale->id ?>" class="btn btn-sm btn-info"><i class="fas fa-circle-exclamation"></i>OTRA VENTA ASOCIADA</a>
                    <?php endif; ?>
                  <?php elseif ($cancelationSale && $cancelationSale->status_id == 0) :
                    $totalPayment = OperationPaymentData::getTotalByOperationId($cancelationSale->id);
                  ?>
                    <br>
                    <button id="btnLiquidateSale<?php echo $cancelationSale->id ?>" onclick="openModalLiquidateSale(<?php echo $cancelationSale->id ?>,<?php echo ($cancelationSale->total - $totalPayment->total) ?>)" class="btn btn-sm btn-warning"><i class="fas fa-circle-exclamation"></i> LIQUIDAR</button>
                  <?php else : ?>
                    <i class="fas fa-check"></i>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
        <h4 style="color:#2A8AC4">Total agendados: <?php echo $totalScheduled ?></h4>

      <?php else : ?>
        <p class='alert alert-danger'>No se encontraron pacientes agendados.</p>
      <?php endif; ?>

      <?php if (count($notScheduledPatients) > 0 && ($statusId == "all" || $statusId == "notscheduled")) : ?>
        <div class="clearfix"></div>
        <h3>No Agendados</h3>
        <table class="table table-bordered table-hover" id='datosexcel' border='1'>
          <thead>
            <tr>
              <th>Fecha registro</th>
              <th>Paciente</th>
              <th>Teléfono</th>
              <th>Psicólogo</th>
              <th>Tratamiento</th>
              <th>Última Cita</th>
            </tr>
          </thead>
          <?php
          $totalNotScheduled = 0;
          foreach ($notScheduledPatients as $notScheduledPatient) :
            $totalNotScheduled++;
            $patient = $notScheduledPatient->getPatient();
            $patientTreatment = TreatmentData::getPatientTreatmentByDates($patient->id, $startDate, $endDate);
            $medicName = (($patientTreatment && $patientTreatment->getMedic()) ? $patientTreatment->getMedic()->name : "");
          ?>
            <tr class='danger'>
              <td><?php echo $patient->getDateFormat($patient->created_at) ?></td>
              <td><?php echo $patient->name ?></td>
              <td><?php echo $patient->cellphone . "/" . $patient->homephone ?></td>
              <td><?php echo $medicName ?></td>
              <td><?php echo ($patientTreatment) ? $patientTreatment->treatment_name : ""  ?></td>
              <td><?php echo ($patient->getLastByPatientId()) ? $patient->getLastByPatientId()->date_format : "" ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
        <h4 style="color:#2A8AC4">Total no agendados: <?php echo $totalNotScheduled ?></h4>
      <?php else : ?>
        <p class='alert alert-danger'>No se encontraron pacientes sin agendar.</p>
      <?php endif; ?>
    </div>
  </div>

  <br><br><br><br>
</section>

<!-- MODAL-->
<div class="modal fade" id="modalLiquidateSale" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Liquidar venta</h4>
        <div class="pull-right">
          <a id="linkEditLiquidateSale" target="_blank" href='' class='btn btn-warning btn-xs'><i class="fas fa-pencil-alt"></i> Editar venta</a>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="col-lg-3">
            <label>Tipo pago</label>
            <select id="paymentTypeLiquidate" class="form-control">
              <?php foreach ($paymentTypes as $paymentType) : ?>
                <option value="<?php echo $paymentType->id; ?>"><?php echo $paymentType->name; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-3">
            <label>Cantidad</label>
            <input type="number" id="totalLiquidate" class="form-control" placeholder="Total" readonly>
          </div>
          <div class="col-lg-3">
            <label>Fecha</label>
            <input type="date" id="dateLiquidate" value="<?php echo date("Y-m-d") ?>" max="<?php echo date("Y-m-d") ?>" class="form-control" placeholder="Fecha">
          </div>
        </div>

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModalLiquidateSale()">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="liquidateSale()">Liquidar</button>
        <input type="hidden" id="saleIdLiquidate" value="0">
      </div>
    </div>
  </div>

</div>
<!-- MODAL-->

<script type="text/javascript">
  $(document).ready(function() {

    $("#btnExport").click(function(e) {
      $("#datosexcel").btechco_excelexport({
        containerid: "datosexcel",
        datatype: $datatype.Table,
        filename: 'Reporte pacientes agendados y por agendar'
      });

    });

    if ((<?php echo count($scheduledPatients) ?> > 0 || <?php echo count($notScheduledPatients) ?> > 0) && ("<?php echo $statusId ?>" == "all" || "<?php echo $statusId ?>" == "scheduled" || "<?php echo $statusId ?>" == "notscheduled")) {
      let chartData = [];

      let totalScheduled = isNaN(parseFloat("<?php echo count($scheduledPatients) ?>")) ? 0 : parseFloat("<?php echo count($scheduledPatients) ?>");
      let totalNotScheduled = isNaN(parseFloat("<?php echo count($notScheduledPatients) ?>")) ? 0 : parseFloat("<?php echo count($notScheduledPatients) ?>");
      chartData.push(totalScheduled);
      chartData.push(totalNotScheduled);

      //CHART LIBRARY
      var ctx = document.getElementById('myChart').getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Agendados', 'No agendados'],
          datasets: [{
            data: chartData,
            backgroundColor: ['rgba(101, 255, 96, 0.2)', 'rgba(251, 183, 172, 0.2)'],
            borderColor: 'rgba(65, 96, 142, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true
        }
      });

      let chartScheduledData = [];

      let totalOnlyScheduled = "<?php echo $totalOnlyScheduled ?>";
      let totalAssistScheduled = "<?php echo $totalAttendedScheduled ?>";
      let totalNotAssistScheduled = "<?php echo $totalNotAssistScheduled ?>";
      chartScheduledData.push(totalOnlyScheduled);
      chartScheduledData.push(totalAssistScheduled);
      chartScheduledData.push(totalNotAssistScheduled);

      //CHART LIBRARY
      var ctx = document.getElementById('myChartScheduled').getContext('2d');
      var myChartScheduled = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Programadas', 'Asistieron', 'No asistieron'],
          datasets: [{
            data: chartScheduledData,
            backgroundColor: ['rgba(172, 201, 239, 0.2)', 'rgba(101, 255, 96, 0.2)', 'rgba(251, 183, 172, 0.2)'],
            borderColor: 'rgba(65, 96, 142, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true
        }
      });
    }

  });
  //CHART LIBRARY
  function openModalLiquidateSale(saleId, total) {

    let linkEditLiquidateSale = './?view=sales/edit&id=' + saleId;
    $("#linkEditLiquidateSale").attr("href", linkEditLiquidateSale);

    $("#paymentTypeLiquidate").val();
    $("#dateLiquidate").val(getActualDateYmd());
    $("#totalLiquidate").val(total);
    $("#saleIdLiquidate").val(saleId);
    $("#modalLiquidateSale").modal("show");
  }

  function closeModalLiquidateSale() {
    $("#linkEditLiquidateSale").attr("href", "");
    $("#dateLiquidate").val(getActualDateYmd());
    $("#totalLiquidate").val(0);
    $("#saleIdLiquidate").val(0);
    $("#modalLiquidateSale").modal("hide");
  }

  function liquidateSale() {
    $.ajax({
      url: "./?action=sales/add-payment-liquidate", // json datasource
      type: "POST", // method, by default get
      data: {
        paymentType: $("#paymentTypeLiquidate").val(),
        date: $("#dateLiquidate").val(),
        total: $("#totalLiquidate").val(),
        saleId: $("#saleIdLiquidate").val()
      },
      success: function(data) {
        Toast.fire({
          icon: 'success',
          title: 'Se liquidó la venta exitosamente.'
        });
        $("#btnLiquidateSale" + $("#saleIdLiquidate").val()).remove();
        closeModalLiquidateSale();
      },
      error: function() { // error handling
        Toast.fire({
          icon: 'error',
          title: 'Error al liquidar la venta. Recarga la página si persiste el error.'
        });
      }
    });
  }
</script>