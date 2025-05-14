<?php
//Datos del usuario
$user = UserData::getLoggedIn();
$userId = $user->id;
$userType = $user->user_type;

$startDate = (isset($_GET["startDate"])  ? $_GET['startDate'] : date("Y-m-d"));
$endDate = (isset($_GET["endDate"])  ? $_GET['endDate'] : date("Y-m-d", strtotime("+7 days")));

$startDateTime = $startDate . " 00:00:01";
$endDateTime = $endDate . " 23:59:59";

$branchOfficeId = isset($_GET["branchOfficeId"])  ? $_GET['branchOfficeId'] : 0;
$typeId = (isset($_GET["typeId"])  ? $_GET['typeId'] : 1);
$medicId = (isset($_GET["medicId"])  ? $_GET['medicId'] : 0);

if ($userType == "su" || $userType == "co") {
  $branchOffices = BranchOfficeData::getAllByStatus(1);
  $medics = MedicData::getAllByStatus(1);
} else {
  $branchOffice = $user->getBranchOffice();
  $medics = MedicData::getAllByBranchOffice($branchOffice->id);
  $branchOffices = [$branchOffice];
}

if ($typeId == 1) { //PENDIENTE RECORDATORIO
  $rowClass = "danger";
  $patientTypeId = 0; //Todos los tipos de pacientes
  $patients = ReservationData::getNotScheduledPatientsByBranchOffice($branchOfficeId, $startDateTime, $endDateTime, $medicId, $patientTypeId, 1);
} else { //RECORDADAS
  $rowClass = "success";
  $patients = PatientNotificationData::getAllByBranchOfficeDates($startDate, $endDate, $branchOfficeId, $medicId);
}

?>

<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    $("#btnExport").click(function(e) {
      $("#datosexcel").btechco_excelexport({
        containerid: "datosexcel",
        datatype: $datatype.Table,
        filename: 'Reporte de recordatorios pacientes no agendados'
      });

    });
  });

  function updatePatientNotified(patientId, branchOfficeId, medicId, notifiedId = 0) {
    let value = 0;
    if ($('#checkboxNotified-' + patientId).prop('checked')) {
      value = 1;
    }

    $.ajax({
      url: "./?action=patients/update-not-scheduled-patient-notified", // json datasource
      type: "POST", // method, by default get
      data: {
        patientId: patientId,
        branchOfficeId: branchOfficeId,
        medicId: medicId,
        notifiedId: notifiedId,
        value: value
      },
      success: function(data) {
        Toast.fire({
          icon: 'success',
          title: 'Actualizado recordatorio al paciente.'
        });
        $("#r-" + patientId).remove();
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

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <h1>Reporte de recordatorios pacientes no agendados</h1>
      <form method="GET" action="index.php">
        <input type="hidden" name="view" value="reports/not-scheduled-patients-notifications">
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
                <option value="1" <?php echo ($typeId == 1) ? "selected" : "" ?>>PENDIENTE RECORDATORIO</option>
                <option value="2" <?php echo ($typeId == 2) ? "selected" : "" ?>>RECORDADAS</option>
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
              <input type="button" class="btn btn-sm btn-primary btn-block" value="Exportar" id="btnExport" onclick="addLog(0,7,4,'Se descargó el archivo de Reporte de recordatorios a pacientes no agendados')">
            </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
  <br>
  <?php if (count($patients) > 0) : ?>
    <div class="row">
      <div class="col-md-12">
        <?php if (count($patients) > 0) : ?>
          <div class="clearfix"></div>
          <?php if ($typeId == 1) : ?>
            <table class="table table-bordered table-hover" id='datosexcel' border='1'>
              <thead>
                <tr>
                  <th>Sucursal</th>
                  <th>Paciente</th>
                  <th>Teléfonos</th>
                  <th>Psicólogo</th>
                  <th>Categoría</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <?php
              $total = 0;
              foreach ($patients as $patient) :
                $patientData = PatientData::getById($patient->patient_id);
                $patientTreatment = TreatmentData::getPatientTreatmentByDates($patient->patient_id, $startDate, $endDate);
                if ($patientTreatment && $patientTreatment->getMedic()) {
                  $medic = $patientTreatment->getMedic();
                  $medicName = $medic->name;
                  $medicId = $medic->id;
                } else {
                  $medicName = "";
                  $medicId = "";
                }

              ?>
                <tr class="<?php echo $rowClass ?>" id="r-<?php echo $patient->patient_id ?>">
                  <td><?php echo $patientData->getBranchOffice()->name ?></td>
                  <td><?php echo $patientData->name ?></td>
                  <td><?php echo $patientData->cellphone . " <br>" . $patientData->homephone ?></td>
                  <td><?php echo $medicName ?></td>
                  <td><?php echo ($patientData->getTotalReservations()->total > 0) ? "SUBSECUENTE" : "PRIMERA VEZ" ?></td>
                  <td>
                    <label>
                      <input type="checkbox" id="checkboxNotified-<?php echo $patient->patient_id ?>" value="1" onchange="updatePatientNotified(<?php echo $patientData->id ?>,<?php echo $patientData->branch_office_id ?>,<?php echo $medicId ?>,0)" <?php echo (($typeId == 2) ? "checked" : "") ?>>
                      Recordatorio a paciente
                    </label>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>
          <?php else : ?>
            <table class="table table-bordered table-hover" id='datosexcel' border='1'>
              <thead>
                <tr>
                  <th>Sucursal</th>
                  <th>Fecha recordatorio</th>
                  <th>Paciente</th>
                  <th>Teléfonos</th>
                  <th>Psicólogo</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <?php
              $total = 0;
              foreach ($patients as $patient) :
                $patientData = PatientData::getById($patient->patient_id);
              ?>
                <tr class="<?php echo $rowClass ?>" id="r-<?php echo $patient->patient_id ?>">
                  <td><?php echo $patient->getBranchOffice()->name ?></td>
                  <td><?php echo $patient->format_date ?></td>
                  <td><?php echo $patientData->name ?></td>
                  <td><?php echo $patientData->cellphone . " <br>" . $patientData->homephone ?></td>
                  <td><?php echo $patient->getMedic()->name ?></td>
                  <td>
                    <label>
                      <input type="checkbox" id="checkboxNotified-<?php echo $patient->patient_id ?>" value="1" onchange="updatePatientNotified(<?php echo $patient->patient_id ?>,<?php echo $patient->branch_office_id ?>,<?php echo $patient->medic_id ?>,<?php echo $patient->id ?>)" <?php echo (($typeId == 2) ? "checked" : "") ?>>
                      Recordatorio a paciente
                    </label>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>
          <?php endif; ?>

        <?php else : ?>
          <p class='alert alert-danger'>No se encontraron registros.</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</section>