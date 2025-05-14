<?php
//Datos del usuario
$user = UserData::getLoggedIn();
$userId = $user->id;
$userType = $user->user_type;

$startDate = (isset($_GET["sd"])  ? $_GET['sd'] : null);
$endDate = (isset($_GET["ed"])  ? $_GET['ed'] : null);
$branchOfficeId = isset($_GET["branchOfficeId"])  ? $_GET['branchOfficeId'] : 0;
$medicId = isset($_GET["medicId"])  ? $_GET['medicId'] : 0;

//Datos por sucursal
if ($userType == "su" || $userType == "co") {
  $branchOffices = BranchOfficeData::getAllByStatus(1);
  $medics = MedicData::getAllByBranchOffice($branchOfficeId);
} else {
  $branchOffice = $user->getBranchOffice();
  $medics = MedicData::getAllByBranchOffice($branchOffice->id);
  $branchOffices = [$branchOffice];
}

//El reporte muestra los datos de los pacientes que se dieron de alta (terminaron tratamiento),baja (cancelaron el tratamiento) y duración del tratamiento
$interviewedPatients = PatientData::getAllByCreatedAt($branchOfficeId, $startDate, $endDate, $medicId); //Todos los pacientes registrados
$finishedTreatments = TreatmentData::getAllByBranchOfficeStatusDates($branchOfficeId, $startDate, $endDate, $medicId, 3); //Finalizados Status = 3
$canceledTreatments = TreatmentData::getAllByBranchOfficeStatusDates($branchOfficeId, $startDate, $endDate, $medicId, 2); //Cancelados Status = 2

?>

<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <h1>Reporte de entrevistas y bajas</h1>
      <form method="GET" action="index.php">
        <input type="hidden" name="view" value="reports/status-patients">
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
            <label for="inputEmail1" class="control-label">Desde:</label>
            <input type="date" name="sd" value="<?php echo (isset($_GET["sd"])  ? $_GET['sd'] : null) ?>" class="form-control">
          </div>
          <div class="col-md-3">
            <label for="inputEmail1" class="control-label">Hasta:</label>
            <input type="date" name="ed" value="<?php echo (isset($_GET["ed"])  ? $_GET['ed'] : null) ?>" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <input type="submit" class="btn btn-sm btn-success btn-block" value="Procesar">
          </div>
          <?php if ($userType == "su") : ?>
            <div class="col-md-2">
              <input type="submit" class="btn btn-sm btn-primary btn-block" value="Exportar" id="btnExport" onclick="addLog(0,7,4,'Se descargó el archivo de Reporte de pacientes altas y bajas')">
            </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
  <br>
  <?php if (count($interviewedPatients) > 0 || (count($finishedTreatments) > 0 || count($canceledTreatments) > 0)) : ?>
    <div class="row">
      <div class="col-lg-6" class="text-center">
        <div style="width:20vw"><canvas id="myChart"></canvas></div>
        <div class="ct-chart .ct-perfect-fifth"></div>
      </div>
    </div>
  <?php endif; ?>
  <div class="row">
    <div class="col-md-12">
      <?php if (count($interviewedPatients) > 0) : ?>
        <div class="clearfix"></div>
        <h3>Pacientes entrevistados</h3>
        <table class="table table-bordered table-hover" id='datosexcel' border='1'>
          <thead>
            <tr>
              <th>Fecha registro</th>
              <th>Paciente</th>
              <th>Psicólogo</th>
              <th>Tratamiento</th>
              <th>Cantidad de sesiones</th>
              <th>Estatus</th>
            </tr>
          </thead>
          <?php
          $totalInterviewed = 0;
          foreach ($interviewedPatients as $interviewedPatient) :
            $totalInterviewed++;
            $treatmentDetails = TreatmentData::getPatientTreatmentByDates($interviewedPatient->id, $startDate, $endDate);
            $medicName = (($treatmentDetails && $treatmentDetails->getMedic()) ? $treatmentDetails->getMedic()->name : "");
            $treatmentName = (($treatmentDetails) ? $treatmentDetails->treatment_name : "");
          ?>
            <tr class='success'>
              <td><?php echo $interviewedPatient->created_at_format ?></td>
              <td><?php echo $interviewedPatient->name ?></td>
              <td><?php echo $medicName ?></td>
              <td><?php echo $treatmentName ?></td>
              <td><?php echo $interviewedPatient->getTotalReservations()->total ?></td>
              <td>Registrado en el sistema</td>
            </tr>
          <?php endforeach; ?>
        </table>
        <h4 style="color:#2A8AC4">Total pacientes entrevistados: <?php echo $totalInterviewed ?></h4>

      <?php else : ?>
        <p class='alert alert-danger'>No se encontraron pacientes entrevistados.</p>
      <?php endif; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php if (count($finishedTreatments) > 0 || count($canceledTreatments) > 0) : ?>
        <div class="clearfix"></div>
        <h3>Pacientes dados de baja (tratamientos finalizados y tratamientos cancelados) </h3>
        <table class="table table-bordered table-hover" id='datosexcel' border='1'>
          <thead>
            <tr>
              <th>Fecha inicio</th>
              <th>Fecha fin</th>
              <th>Paciente</th>
              <th>Psicólogo</th>
              <th>Tratamiento</th>
              <th>Cantidad de sesiones</th>
              <th>Estatus</th>
            </tr>
          </thead>
          <?php
          $totalFinished = 0;
          foreach ($finishedTreatments as $finishedTreatment) :
            $totalFinished++;
            $patient = $finishedTreatment->getPatient();
          ?>
            <tr class='info'>
              <td><?php echo $finishedTreatment->start_date_format ?></td>
              <td><?php echo $finishedTreatment->end_date_format ?></td>
              <td><?php echo $patient->name ?></td>
              <td><?php echo $finishedTreatment->getMedic()->name ?></td>
              <td><?php echo $finishedTreatment->treatment_name ?></td>
              <td><?php echo $finishedTreatment->getTotalReservations()->total ?></td>
              <td>Alta de tratamiento</td>
            </tr>
          <?php endforeach; ?>
          <?php
          $totalCanceled = 0;
          foreach ($canceledTreatments as $canceledTreatment) :
            $totalCanceled++;
            $patient = $canceledTreatment->getPatient();
          ?>
            <tr class='danger'>
              <td><?php echo $canceledTreatment->start_date_format ?></td>
              <td><?php echo $canceledTreatment->end_date_format ?></td>
              <td><?php echo $patient->name ?></td>
              <td><?php echo $canceledTreatment->getMedic()->name ?></td>
              <td><?php echo $canceledTreatment->treatment_name ?></td>
              <td><?php echo $canceledTreatment->getTotalReservations()->total ?></td>
              <td>Baja de tratamiento</td>
            </tr>
          <?php endforeach; ?>
        </table>
        <h4 style="color:#2A8AC4">TOTAL BAJAS: <?php echo $totalFinished + $totalCanceled ?></h4>
        <h4 style="color:#2A8AC4">Total altas de tratamientos: <?php echo $totalFinished ?></h4>
        <h4 style="color:#2A8AC4">Total de cancelados/bajas de tratamiento: <?php echo $totalCanceled ?></h4>

      <?php else : ?>
        <p class='alert alert-danger'>No se encontraron bajas de pacientes.</p>
      <?php endif; ?>
    </div>
  </div>

  <br><br><br><br>
</section>
<script type="text/javascript">
  $(document).ready(function() {

    $("#btnExport").click(function(e) {
      $("#datosexcel").btechco_excelexport({
        containerid: "datosexcel",
        datatype: $datatype.Table,
        filename: 'Reporte pacientes altas y bajas'
      });

    });

    let chartData = [];

    let totalInterviewed = isNaN(parseFloat("<?php echo count($interviewedPatients) ?>")) ? 0 : parseFloat("<?php echo count($interviewedPatients) ?>");
    let totalFinished = isNaN(parseFloat("<?php echo count($finishedTreatments) ?>")) ? 0 : parseFloat("<?php echo count($finishedTreatments) ?>");
    let totalCanceled = isNaN(parseFloat("<?php echo count($canceledTreatments) ?>")) ? 0 : parseFloat("<?php echo count($canceledTreatments) ?>");
    chartData.push(totalInterviewed);
    chartData.push(totalFinished + totalCanceled);

    //CHART LIBRARY
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Entrevistados', 'Bajas'],
        datasets: [{
          data: chartData,
          backgroundColor: ['rgba(101, 255, 96, 0.2)', 'rgba(246, 133, 148, 0.2)'],
          borderColor: 'rgba(65, 96, 142, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true
      }
    });
  });
  //CHART LIBRARY
</script>