<?php
$patients = PatientData::getAll("all");
$user = UserData::getLoggedIn();
$userType = $user->user_type;

if ($userType == "su" || $userType == "co") {
  $medics = MedicData::getAll();
  $branchOffices = BranchOfficeData::getAllByStatus(1);
  $patients = PatientData::getAll("active");
} else {
  $branchOffice = UserData::getLoggedIn()->getBranchOffice();
  $patients = PatientData::getAllByBranchOffice($branchOffice->id, "active");
  $branchOffices = [$branchOffice];

  if ($userType == "do") {
    $medics = [MedicData::getByUserId($_SESSION["user_id"])];
  }
  if ($userType == "r") {
    $medics = MedicData::getAllByBranchOffice($branchOffice->id, "active");
  }
}

$categories = ReservationData::getReservationCategories();
$laboratories = LaboratoryData::getAll();
$areas = ReservationAreaData::getAll();

$dateTime = isset($_GET["start"])  ? $_GET['start'] :  date("Y-m-d H:i:s");
$reservationId = isset($_GET["reservationId"])  ? $_GET['reservationId'] : null; //Precargar datos de una cita anterior
if ($reservationId && ReservationData::getById($reservationId)) {
  $previousReservation = ReservationData::getById($reservationId);
  $dateTime = $previousReservation->date_at;
  $patientId = $previousReservation->patient_id;
  $medicId = $previousReservation->medic_id;
  $branchOfficeId = $previousReservation->branch_office_id;
  $laboratoryId = $previousReservation->laboratory_id;
  $categoryId = $previousReservation->category_id;
  $areaId = $previousReservation->area_id;
  $reason = $previousReservation->reason;
} else {
  $patientId = null;
  $medicId = null;
  $branchOfficeId = null;
  $laboratoryId = null;
  $categoryId = null;
  $areaId = null;
  $reason = null;
}

$date = date('Y-m-d', strtotime($dateTime));
$timeAt = date('H:i', strtotime($dateTime));
$timeAtFinal = strtotime('+40 minute',  strtotime($dateTime));
$timeAtFinal = date('H:i', $timeAtFinal);

?>
<div class="row">
  <div class="col-md-12">
    <h1>Nueva Cita</h1>
    <br>
    <div class="box box-primary">
      <div class="box-body">
        <form class="form-horizontal" method="POST" action="./?action=reservations/add-reservation-patient" role="form">
          <div class="form-group">
            <div class="col-lg-4">
              <label for="inputEmail1" class="control-label">Fecha</label>
              <input type="date" name="date" id="formDate" class="form-control" <?php echo ($userType != "su") ? "min='" . date('Y-m-d') . "'" : "" ?> value="<?php echo $date ?>">
            </div>
            <div class="clearfix col-lg-4">
              <label for="inputEmail1" class="control-label">Hora Inicio</label>
              <input type="time" class="form-control" value="<?php echo $timeAt ?>" name="timeAt" id="timeAt" class="form-control">
            </div>
            <div class="clearfix col-lg-4">
              <label for="inputEmail1" class="control-label">Hora Fin</label>
              <input type="time" class="form-control" value="<?php echo $timeAtFinal ?>" name="timeAtFinal" id="timeAtFinal" class="form-control">
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-4">
              <label for="inputEmail1" class="col-md-3 control-label">Paciente</label>
              <select name="patient" id="patient" class="form-control" onchange="setCategoryReservation()" autofocus required>
                <option value="" disabled selected>-- SELECCIONE -- </option>
                <?php foreach ($patients as $patient) : ?>
                  <option value="<?php echo $patient->id; ?>" <?php echo ($patientId == $patient->id) ? "selected" : "" ?>><?php echo $patient->id . " - " . $patient->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="col-md-3 control-label">Psicólogo</label>
              <select name="medic" id="medic" class="form-control" required>
                <?php if ($userType == "su" || $userType == "r") : ?>
                  <option value="" disabled selected>-- SELECCIONE --</option>
                <?php endif; ?>
                <?php foreach ($medics as $medic) : ?>
                  <option value="<?php echo $medic->id; ?>" <?php echo ($medicId == $medic->id) ? "selected" : "" ?>><?php echo $medic->id . " - " . $medic->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="col-md-3 control-label">Sucursal</label>
              <select name="branchOfficeId" class="form-control" required>
                <?php foreach ($branchOffices as $branchOffice) : ?>
                  <option value="<?php echo $branchOffice->id; ?>" <?php echo ($branchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="col-md-3 control-label">Consultorio</label>
              <select name="laboratory" id="laboratory" class="form-control" required>
                <option value='' disabled selected>-- SELECCIONE --</option>
                <?php foreach ($laboratories as $laboratory) : ?>
                  <option value="<?php echo $laboratory->id; ?>" <?php echo ($laboratoryId == $laboratory->id) ? "selected" : "" ?>><?php echo $laboratory->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <!--  <div class="col-md-4">
              <label class="col-md-3 control-label">Categoría</label>
              <select name="category" id="category" class="form-control" disabled>
                <option value="" disabled selected>-- SELECCIONE --</option>
                <?php foreach ($categories as $category) : ?>
                  <option value="<?php echo $category->id; ?>" <?php echo ($categoryId == $category->id) ? "selected" : "" ?>><?php echo $category->id . " - " . $category->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>-->
            <div class="col-md-4">
              <label class="col-md-3 control-label">Área</label>
              <select name="area" id="area" class="form-control" required>
                <option value="" disabled selected>-- SELECCIONE --</option>
                <?php foreach ($areas as $area) : ?>
                  <option value="<?php echo $area->id; ?>" <?php echo ($areaId == $area->id) ? "selected" : "" ?>><?php echo $area->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-12">
              <label for="inputEmail1" class="control-label">Motivo de la Consulta:</label>
              <textarea class="form-control" name="reason" id="reason" placeholder="Motivo de la Consulta"><?php echo ($reason) ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-2 pull-right">
              <button type="submit" class="btn btn-default">Guardar Cita</button>
              <input type="hidden" value="<?php echo $_SESSION["user_id"]; ?>" name="userId" id="userId">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
<script type="text/javascript">
  $(document).ready(function() {
    $("#patient").select2({});
    $("#medic").select2({});
    $("#laboratory").select2({});
    $("#category").select2({});
    $("#area").select2({});
    setCategoryReservation("<?php echo $patientId ?>");
  });

  function setCategoryReservation(patientId) {
    $.ajax({
      type: "POST",
      url: './?action=reservations/get-category-reservation',
      //dataType: "json",
      data: {
        patientId: $("#patient").val()
      },
      success: function(data) {
        $("#category").val(data).change();
      },
      error: function(data) {}
    });
  }
</script>