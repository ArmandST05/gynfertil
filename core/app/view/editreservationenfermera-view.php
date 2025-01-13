<!-- ESTA VISTA LES APARECE A LOS USUARIOS DE TIPO ENFERMERA Y AUDITOR-->
<?php
$reservation = ReservationData::getById($_GET["id"]);
$patient = PatientData::getById($reservation->pacient_id);
$medics = MedicData::getAll_med($reservation->medic_id);
$reservationStatus = PatientData::estatus_paciente();
$laboratories = ReservationData::get_laboratorio($reservation->laboratorio);

if ($_SESSION['typeUser'] == "a" && $_GET["id_paciente"] <= 2) {
  echo "<script> 
             alert('Es una cita de doctor o enfermera')
              window.location.href = './?view=home';
            </script>";
}

if ($reservation->datos != "") {
  $resumen = $reservation->datos;
} else if ($reservation->status_reser == 1) {
  $resumen = "T/A:
PESO:
TALLA:
MOTIVO:";
} else if ($reservation->status_reser == 2) {
  $resumen =
    "T/A: 
PESO:
TALLA: 
MOTIVO: 
LEU: -
PRO:
PH:
SG:
NIT:";
} else if ($reservation->status_reser == 3) {
  $resumen =
    "PESO: 
T/A: 
LEU: 
PRO:
PH:
SG:
NIT: ";
} else {
  $resumen =
    "T/A: 
    PESO: ";
}

//Edad del paciente
$birthdayDay = substr($patient->fecha_na, 8, 2);
$birthdayMonth = substr($patient->fecha_na, 5, 2);
$birthdayYear = substr($patient->fecha_na, 0, 4);
$actualDate = date('Y-m-d');
$diff = abs(strtotime($actualDate) - strtotime($patient->fecha_na));
$age = floor($diff / (365 * 60 * 60 * 24));

$relativeBirthdayDay = substr($patient->relative_birthday, 8, 2);
$relativeBirthdayMonth = substr($patient->relative_birthday, 5, 2);
$relativeBirthdayYear = substr($patient->relative_birthday, 0, 4);
$officialDocuments = OfficialDocumentData::getAll();

$patients = PatientData::getAll();
$months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

?>
<div class="row">
  <div class="col-md-12">

    <div class="card">
      <div class="card-header" data-background-color="blue">
        <h4 class="title">Cita</h4>
      </div>
      <div class="card-content">
        <form class="form-horizontal" role="form" id="" name="guarda" method="post" action="./?action=updatereservationenfermera">
          <div class="box box-primary">
            <div class="box-header">
              <div class="box-tools pull-right">
                <a href="index.php?view=patients/edit&id=<?php echo $patient->id ?>" class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> Editar paciente</a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-3">
                  <img class="profile-user-img img-responsive img-circle" src='<?php echo ($patient->image) ? "storage_data/patients/" . $patient->image : "../../../assets/default_user.jpg" ?>' alt="Foto del paciente">
                </div>
                <div class="col-md-9">
                  <?php
                  foreach ($medics as $m) {
                    $medic = $m->name;
                  }
                  foreach ($laboratories as $key) {
                    $laboratory =  $key->nombre;
                  }

                  echo "<b>Paciente: </b>" . $patient->name . "<br>";
                  echo "<b>Médico: </b>" . $medic . "<br>";
                  echo "<b>Fecha/Hora: </b>" . $reservation->date_at . "<br>";
                  echo "<b>Nota: </b>" . $reservation->note . "<br>";
                  echo "<b>Laboratorio: </b>" .  $laboratory . "<br>";
                  ?>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="inputEmail1" class="col-lg-1 control-label">Clave</label>
                    <div class="col-lg-3">
                      <select name="pac_est" class="form-control" required>
                        <option value="">-- SELECCIONE --</option>
                        <?php foreach ($reservationStatus as $status) : ?>
                          <option value="<?php echo $status->id; ?>" <?php if ($status->id == $reservation->status_reser) {
                                                                    echo "selected";
                                                                  } ?>><?php echo $status->id . " - " . $status->nombre; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail1" class="col-lg-1 control-label">Calle</label>
                    <div class="col-lg-3">
                      <input type="text" name="calle" required class="form-control" id="calle" value="<?php echo $patient->calle ?>" placeholder="Calle">
                    </div>
                    <label for="inputEmail1" class="col-lg-1 control-label">Número</label>
                    <div class="col-lg-2">
                      <input type="text" name="num" required class="form-control" value="<?php echo $patient->num ?>" id="num" placeholder="Número">
                    </div>
                    <label for="inputEmail1" class="col-lg-1 control-label">Colonia</label>
                    <div class="col-lg-4">
                      <input type="text" name="col" required class="form-control" value="<?php echo $patient->col ?>" id="col" placeholder="Colonia">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail1" class="col-lg-1 control-label">Teléfono</label>
                    <div class="col-lg-5">
                      <input type="text" name="tel" required class="form-control" value="<?php echo $patient->tel ?>" id="tel" placeholder="Teléfono">
                    </div>
                    <label for="inputEmail1" class="col-lg-1 control-label">Teléfono alternativo</label>
                    <div class="col-lg-5">
                      <input type="text" name="tel2" required class="form-control" refref id="tel" value="<?php echo $patient->tel2 ?>" placeholder="Tel alternativo">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail1" class="col-lg-1 control-label">Email</label>
                    <div class="col-lg-5">
                      <input type="text" name="email" class="form-control" id="email1" value="<?php echo $patient->email ?>" placeholder="Email">
                    </div>
                    <label for="inputEmail1" class="col-lg-1 control-label">Referida por</label>
                    <div class="col-md-5">
                      <input type="text" name="ref" class="form-control" id="email1" value="<?php echo $patient->ref ?>" placeholder="Referida por">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <div class="col-lg-12">
                      <div class="callout callout-warning">
                        <p>Si el paciente realizará un tratamiento de fertilidad, registra correctamente su FECHA DE NACIMIENTO y un DATO OFICIAL, además de TODOS los datos de su pareja, formará parte de su histórico.</p>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 control-label">Fecha nacimiento paciente:</label>
                    <div class="col-lg-1">
                      <select name="diaini" id="diaini" class="form-control" onChange="calculatePatientBirthday();">
                        <?php
                        for ($i = 1; $i <= 31; $i++) {
                          echo "<option value=" . $i;
                          if ($birthdayDay == $i) {
                            echo " selected='selected'";
                          }
                          echo ">" . $i . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-lg-2">
                      <select name="mesini" id="mesini" class="form-control" onChange="calculatePatientBirthday();">
                        <?php foreach ($months as $index => $month) : ?>
                            <option value="<?php echo $index; ?>" <?php echo ($birthdayMonth == $index) ? "selected" : "" ?>><?php echo $month ?></option>
                          <?php endforeach; ?>
                      </select>
                      
                    </div>
                    <div class="col-lg-2">
                      <select name="anioini" id="anioini" class="form-control" onChange="calculatePatientBirthday();">
                        <?php
                        for ($k = date("Y"); $k >= 1930; $k--) {
                          echo "<option value=" . $k;
                          if ($birthdayYear == $k) {
                            echo " selected='selected'";
                          }
                          echo ">" . $k . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <input type="hidden" name="formfecha" id="formfecha">
                    <label for="inputEmail1" class="col-lg-1 control-label">Edad</label>
                    <div class="col-lg-2">
                      <input type="text" name="age" required class="form-control" id="age" value="<?php echo $patient->edad ?>" placeholder="Edad">
                    </div>
                    <div class="col-lg-1">
                      <label> Años</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="col-lg-2 control-label">Dato oficial del paciente:</label>
                    <div class="col-lg-2">
                      <select name="officialDocumentId" id="officialDocumentId" class="form-control">
                        <?php foreach ($officialDocuments as $officialDocument) : ?>
                          <option value="<?php echo $officialDocument->id ?>" <?php echo ($officialDocument->id == $patient->official_document_id) ? "selected" : "" ?>><?php echo $officialDocument->name ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <input type="text" id="officialDocumentValue" name="officialDocumentValue" value="<?php echo $patient->official_document_value; ?>" class="form-control" placeholder="Dato oficial">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                      <div class="checkbox">
                        <label>
                          <input name="isRelativeRegistered" id="isRelativeRegistered" type="checkbox" value="1" <?php echo ($patient->relative_id != "" && $patient->relative_id != 0) ? "checked" : "" ?>> Tiene pareja registrada
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group" id="divRelativeId">
                    <label for="inputEmail1" class="col-lg-2 control-label">Pareja registrada:</label>
                    <div class="col-lg-6">
                      <select name="relativeId" id="relativeId" class="form-control" id="combobox">
                        <option value="">-- SELECCIONE -- </option>
                        <?php foreach ($patients as $relativePatient) : ?>
                          <option value="<?php echo $relativePatient->id; ?>" <?php echo ($relativePatient->id == $patient->relative_id) ? "selected" : "" ?>><?php echo $relativePatient->id . " - " . $relativePatient->name ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div id="divRelativeName">
                    <div class="form-group">
                      <label for="inputEmail1" class="col-lg-2 control-label">Nombre de la pareja:</label>
                      <div class="col-md-6">
                        <input type="text" name="relativeName" value="<?php echo $patient->relative_name; ?>" class="form-control" id="relativeName" placeholder="Nombre de la pareja" autofocus>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="" class="col-lg-2 control-label">Dato oficial de la pareja:</label>
                      <div class="col-lg-2">
                        <select name="relativeOfficialDocumentId" id="relativeOfficialDocumentId" class="form-control">
                          <?php foreach ($officialDocuments as $officialDocument) : ?>
                            <option value="<?php echo $officialDocument->id ?>" <?php echo ($officialDocument->id == $patient->relative_official_document_id) ? "selected" : "" ?>><?php echo $officialDocument->name ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <input type="text" id="relativeOfficialDocumentValue" name="relativeOfficialDocumentValue" value="<?php echo $patient->relative_official_document_value; ?>" class="form-control" placeholder="Dato oficial">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="" class="col-lg-2 control-label">Fecha nacimiento pareja:</label>
                      <div class="col-lg-1">
                        <select name="birthday_day" id="relativeBirthdayDay" class="form-control" onchange="calculateRelativeBirthday();">
                          <?php for ($i = 01; $i <= 31; $i++) : ?>
                            <option value="<?php echo $i; ?>" <?php echo ($relativeBirthdayDay == $i) ? "selected" : "" ?>><?php echo $i ?></option>
                          <?php endfor; ?>
                        </select>
                      </div>
                      <div class="col-lg-2">
                        <select name="birthday_month" id="relativeBirthdayMonth" class="form-control" onchange="calculateRelativeBirthday();">
                          <?php
                          foreach ($months as $index => $month) : ?>
                            <option value="<?php echo $index; ?>" <?php echo ($relativeBirthdayMonth == $index) ? "selected" : "" ?>><?php echo $month ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-lg-2">
                        <select name="birthday_year" id="relativeBirthdayYear" class="form-control" onchange="calculateRelativeBirthday();">
                          <?php for ($k = date("Y"); $k >= 1930; $k--) : ?>
                            <option value="<?php echo $k; ?>" <?php echo ($relativeBirthdayYear == $k) ? "selected" : "" ?>><?php echo $k ?></option>
                          <?php endfor; ?>
                        </select>
                      </div>
                      <input type="hidden" name="relativeBirthday" id="relativeBirthday">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label for="inputEmail1" class="col-lg-1 control-label">Datos</label>
                    <div class="col-lg-11">
                      <textarea class="form-control" rows="10" name="datos" placeholder="Datos" autofocus="on"><?php echo $resumen ?></textarea>
                      <h7 style="color:#E30F22;font-weight:bold;" class="control-label">*Actualiza los datos haciendo clic en Guardar.</h7>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-offset-11 col-lg-1">
                      <input type="hidden" name="id" value="<?php echo $reservation->id; ?>">
                      <input type="hidden" name="patientId" value="<?php echo $patient->id; ?>">
                      <button type="submit" id="guardar_en" class="btn btn-info" onclick="calculatePatientBirthday()">Guardar</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
  function agregarfecha() {
    var d = new Date();
    var n = d.toISOString().slice(0, 10).split("-").join("/");
    var day = n.slice(8, 10);
    var month = n.slice(5, 7);
    var year = n.slice(0, 4);
    calculatePatientBirthday();
  }

  function calculatePatientBirthday() {
    let day = $('#diaini').val();
    let month = $('#mesini').val();
    let year = $('#anioini').val();

    $('#formfecha').val(year + "/" + month + "/" + day);
  }

  function calculateRelativeBirthday() {
    let day = $('#relativeBirthdayDay').val();
    let month = $('#relativeBirthdayMonth').val();
    let year = $('#relativeBirthdayYear').val();

    $('#relativeBirthday').val(year + "/" + month + "/" + day);
  }

  $(document).ready(function() {
    calculatePatientBirthday();
    $("#menu_enfermera").click(function(e) {
      document.guarda.submit();
    });

    $("#relativeId").select2({});

    if ($('#isRelativeRegistered').prop('checked') == true) {
      $("#divRelativeName").hide();
      $("#divRelativeId").show();
    } else {
      $("#divRelativeName").show();
      $("#divRelativeId").hide();
    }

    $("#isRelative").prop("checked", true);

  });

  $("#isRelativeRegistered").change(function() {
    if (this.checked) {
      //Se se va a seleccionar una pareja que es paciente registrado 
      $("#divRelativeName").hide();
      $("#divRelativeId").show();
    } else {
      $("#divRelativeName").show();
      $("#divRelativeId").hide();
    }
  });
</script>