<?php
$reservation = ReservationData::getById($_GET["id"]);

if (!$reservation) {
  echo "<script>
      alert('La cita no existe');
      history.back();
    </script>";
}
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

$patient = PatientData::getById($reservation->patient_id);
$categories = ReservationData::getReservationCategories();
$laboratories = LaboratoryData::getAll();
$areas = ReservationAreaData::getAll();
?>

<div class="row">
  <div class="col-md-12">
    <h1>Editar Cita</h1>
    <br>
    <div class="box box-primary" id="patientDetails">
      <div class="box-header with-border">
        <h3 class="box-title">Datos del Paciente</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="col-md-3">
          <img class="profile-user-img img-responsive img-circle" src='<?php echo ($patient->image) ? "storage_data/patients/" . $patient->image : "../../../assets/default_user.jpg" ?>' alt="Foto del paciente">
        </div>
        <div class="col-md-9">
          <b>Nombre completo: </b><?php echo $patient->name ?><br>
          <b>Dirección: </b><?php echo $patient->street ?> <?php echo $patient->number ?>
          <?php echo $patient->colony ?><br>
          <b>Teléfono: </b><?php echo $patient->cellphone ?> <br><b>Teléfono alternativo:
          </b><?php echo $patient->homephone ?><br>
          <b>Email: </b><?php echo $patient->email ?><br>
          <b>Fecha nacimiento: </b><?php echo $patient->getBirthdayFormat() ?><br>
          <b>Edad: </b><?php echo $patient->getAge() ?><br>
          <b>Referido: </b><?php echo $patient->referred_by ?>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <div class="box box-primary">
      <div class="box-body">
        <form class="form-horizontal" method="POST" action="./?action=reservations/update-reservation-patient" role="form">
          <div class="form-group">
            <div class="col-md-4">
              <label class="control-label">Fecha</label>
              <input type="date" name="date" id="formDate" class="form-control" <?php echo ($userType != "su") ? "min='" . date('Y-m-d') . "'" : "" ?> value="<?php echo $reservation->getDate() ?>">
            </div>
            <div class="col-md-4">
              <label class="control-label">Hora Inicio</label>
              <input type="time" class="form-control" value="<?php echo $reservation->getStartTime() ?>" name="timeAt" id="timeAt" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="control-label">Hora Fin</label>
              <input type="time" class="form-control" value="<?php echo $reservation->getEndTime() ?>" name="timeAtFinal" id="timeAtFinal" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-4">
              <label for="inputEmail1" class="col-md-3 control-label">Paciente</label>
              <select name="patient" id="patient" class="form-control" onchange="selectPatient()" autofocus required>
                <option value="" disabled selected>-- SELECCIONE -- </option>
                <?php foreach ($patients as $patient) : ?>
                  <option value="<?php echo $patient->id; ?>" <?php echo ($reservation->patient_id == $patient->id) ? "selected" : "" ?>><?php echo $patient->id . " - " . $patient->name ?></option>
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
                  <option value="<?php echo $medic->id; ?>" <?php echo ($reservation->medic_id == $medic->id) ? "selected" : "" ?>><?php echo $medic->id . " - " . $medic->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="col-md-3 control-label">Sucursal</label>
              <select name="branchOfficeId" class="form-control" required>
                <?php foreach ($branchOffices as $branchOffice) : ?>
                  <option value="<?php echo $branchOffice->id; ?>" <?php echo ($reservation->branch_office_id == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="col-md-3 control-label">Consultorio</label>
              <select name="laboratory" id="laboratory" class="form-control" required>
                <option value='' disabled selected>-- SELECCIONE --</option>
                <?php foreach ($laboratories as $laboratory) : ?>
                  <option value="<?php echo $laboratory->id; ?>" <?php echo ($reservation->laboratory_id == $laboratory->id) ? "selected" : "" ?>><?php echo $laboratory->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <!--<div class="col-md-4">
              <label class="col-md-3 control-label">Categoría</label>
              <select name="category" id="category" class="form-control" required disabled>
                <option value="" disabled selected>-- SELECCIONE --</option>
                <?php foreach ($categories as $category) : ?>
                  <option value="<?php echo $category->id; ?>" <?php echo ($reservation->category_id == $category->id) ? "selected" : "" ?>><?php echo $category->id . " - " . $category->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>-->
            <div class="col-md-4">
              <label class="col-md-3 control-label">Área</label>
              <select name="area" id="area" class="form-control" required>
                <option value="" disabled selected>-- SELECCIONE --</option>
                <?php foreach ($areas as $area) : ?>
                  <option value="<?php echo $area->id; ?>" <?php echo ($reservation->area_id == $area->id) ? "selected" : "" ?>><?php echo $area->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <label class="control-label">Motivo de la Consulta:</label>
              <textarea class="form-control" name="reason" id="reason" placeholder="Motivo de la Consulta"><?php echo $reservation->reason; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-2 pull-right">
              <button type="submit" class="btn btn-default"> Guardar Cita</button>
              <input type="hidden" value="<?php echo $_SESSION["user_id"]; ?>" name="userId">
              <input type="hidden" value="<?php echo $reservation->id; ?>" name="reservationId">
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
  });

  function selectPatient() {
    $("#patientDetails").hide();
    setCategoryReservation();
  }

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