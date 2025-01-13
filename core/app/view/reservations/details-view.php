<?php
$reservation = ReservationData::getById($_GET["id"]);
$ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
$ti_usua = UserData::get_tipo_usuario1($ti_user);
$patient_id = $_GET["id_paciente"];

foreach ($ti_usua as $key) {
  $tipo = $key->tipo_usuario;
  $id_me = $key->id_me;
}
$patientNotes = ReservationData::get_resumen_detalle($patient_id);
$reservationActualNote = ReservationData::get_primer_resumen_cita($patient_id, $reservation->medic_id, substr($reservation->date_at, 0, 10)); //Obtener la nota de la cita en caso de que exista,sólo primer resultado.

//CONSULTA SI SON MÉDICOS REGISTRADOS COMO PACIENTES, Y REDIRECCIONA AL DETALLE DE LA CITA DOCTOR
if ($patient_id == 2 || $patient_id == 1 || $patient_id == 2041 || $patient_id == 2042 || $patient_id == 2043 || $patient_id == 2044 || $patient_id == 2045) {
  echo "<script> 
            window.location.href = './?view=editreservationdoc&id=" . $_GET["id"] . "';
          </script>";
}
$patients = PatientData::getAll_todo($patient_id);
$patient = reset($patients);
$tipo = $patient->tipo;
$medics = MedicData::getAll();

//DATOS OFICIALES DEL PACIENTE Y SU PAREJA
$patientOfficialData = $patient->getPatientOfficialData(); //Cargar dato oficial del paciente (rfc,curp,pasaporte).

//Si el paciente tiene una pareja registrada como paciente, obtener los datos de ahí, si no, los datos capturados.
if ($patient->relative_id) {
  $relative = PatientData::getById($patient->relative_id);
  $relativeName = $relative->name;
  $relativeBirthday = ($relative->birthday_format != "00/00/0000") ? $relative->getBirthdayFormat() : "No especificada";
  $relativeAge = $relative->getAge();
  $relativeOfficialData = $relative->getPatientOfficialData(); //Cargar dato oficial de pareja del paciente (rfc,curp,pasaporte).
} else {
  $relativeName = $patient->relative_name;
  $relativeBirthday = ($patient->relative_birthday_format != "00/00/0000") ? $patient->getRelativeBirthdayFormat() : "No especificada";
  $relativeAge = $patient->getRelativeAge();
  $relativeOfficialData = $patient->getRelativeOfficialData(); //Cargar dato oficial de pareja del paciente (rfc,curp,pasaporte).
}

//CATEGORÍAS Y TRATAMIENTOS
$categories = PatientCategoryData::getAllCategories();
$treatments = PatientCategoryData::getAllTreatments();
//Diagnósticos de tratamientos
$treatmentDiagnostics = TreatmentDiagnosticData::getAll();

$patientCategoryDetail = PatientCategoryData::getPatientCategoryDetail($patient_id);
$patientCategoryId = (isset($patientCategoryDetail)) ? $patientCategoryDetail->patient_category_id : 0; //Categoría
$patientTreatmentId = (isset($patientCategoryDetail)) ? $patientCategoryDetail->patient_treatment_id : 0; //Tratamiento
$categoryTreatmentId = (isset($patientCategoryDetail)) ? $patientCategoryDetail->id : 0; //Registro de categoría/tratamiento del paciente
$categoryTreatmentStatus = (isset($patientCategoryDetail)) ? $patientCategoryDetail->treatment_status_id : 0; ////Estatus de categoría/tratamiento del paciente
$isTreatmentPregnancyTest = (isset($patientCategoryDetail)) ? $patientCategoryDetail->is_pregnancy_test : 0; //Validar si el tratamiento requiere realizar una prueba de embarazo al finalizar
$categoryPregnancyTestDate = (isset($patientCategoryDetail)) ? $patientCategoryDetail->pregnancy_test_date : 0;
$treatmentLocationId = (isset($patientCategoryDetail)) ? $patientCategoryDetail->treatment_location_id : 1;
$patientPregnancyDetail = PatientPregnancyData::getByPatientId($patient_id);
?>
<!-- ACCIONES PARA CATEGORÍAS/TRATAMIENTOS/EMBARAZO-->
<script src="core/app/view/patientCategoryScript.js" type="text/javascript"></script>
<div class="row">
  <div class="col-md-12">
    <!-- /.box -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Datos del Paciente</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="col-md-3">
          <img class="profile-user-img img-responsive img-circle" src='<?php echo ($patient->image) ? "storage_data/patients/" . $patient->image : "../../../assets/default_user.jpg" ?>' alt="Foto del paciente">
        </div>
        <div class="col-md-5">
          <?php
          echo "<b>Nombre completo: </b>" . $patient->name . "<br>";
          echo "<b>" . $patientOfficialData->name . ": </b>" . $patientOfficialData->value . "<br>";
          echo "<b>Fecha nacimiento: </b>" . $patient->getBirthdayFormat() . "<br>";
          echo "<b>Edad: </b>" . $patient->getAge() . "<br>";
          echo "<b>Dirección: </b>" . $patient->calle . " " . $patient->num . " " . $patient->col . "<br>";
          echo "<b>Teléfono: </b>" . $patient->tel . " <br><b>Teléfono alternativo: </b>" . $patient->tel2 . "<br>";
          echo "<b>Email: </b>" . $patient->email . "<br>";
          echo "<b>Referida: </b>" . $patient->ref . "<br>";
          ?>
        </div>
        <div class="col-md-4">
          <?php
          echo "<b>Nombre pareja: </b>" . $relativeName . "<br>";
          echo "<b>" . $relativeOfficialData->name . ": </b>" . $relativeOfficialData->value . "<br>";
          echo "<b>Fecha nacimiento: </b>" . $relativeBirthday . "<br>";
          echo "<b>Edad: </b>" . $relativeAge . "<br>";
          ?>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Datos de la Cita</h3>
        <div class="pull-right">
          <a href='./?action=deletereser&id=<?php echo $_GET["id"] ?>' class='btn btn-danger btn-xs' onClick='return confirmar();'><i class="fas fa-trash"></i> Eliminar cita</a>
          <a href='./?view=sales/new-details&idRes=<?php echo $_GET["id"] ?>&id_paciente=<?php echo $patient_id ?>&idMed=<?php echo $reservation->medic_id; ?>&fecha=<?php echo $reservation->date_at; ?>' class='btn btn-primary btn-xs'><i class="fas fa-dollar-sign"></i> Realizar Venta</a>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <b>Fecha Cita: </b><?php echo $reservation->getReservationDateFormat(); ?><br>
            <b>Doctor: </b><?php echo $reservation->medic_name ?><br>
            <b>Hora: </b><?php echo $reservation->time_at ?><br>
            <b>Nota: </b><?php echo $reservation->note ?><br>
            <b>Datos: </b><?php echo $reservation->datos ?>
          </div>
        </div>
        <!-- Mostrar categorías y tratamientos para pacientes MUJERES-->
        <?php if ($patient->sex_id == 1) : ?>
          <hr>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="inputEmail1">Categoría</label>
                <select class="form-control" id="patientCategory" <?php echo ($patientCategoryId == 3) ? 'disabled' : '' ?> onchange="selectCategory()">
                  <option value="0" <?php echo ($patientCategoryId == 0) ? 'selected' : '' ?>>NO CLASIFICADO</option>
                  <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category->id ?>" <?php echo ($patientCategoryId == $category->id) ? 'selected' : '' ?>><?php echo $category->name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6" id="divPatientTreatment">
              <div class="form-group">
                <label for="inputEmail1">Tratamiento</label>
                <select class="form-control" id="patientTreatment" <?php echo ($patientCategoryId == 3) ? 'disabled' : '' ?>>
                  <?php foreach ($treatments as $treatment) : ?>
                    <option value="<?php echo $treatment->id ?>" <?php echo ($patientTreatmentId == $treatment->id) ? 'selected' : '' ?>><?php echo "(" . $treatment->code . ") " . $treatment->name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php if ($patient->fecha_na == "" || $patient->fecha_na == "0000-00-00") : ?>
                <div class="callout callout-info">
                  <p>Es necesario que el paciente tenga registrada su fecha de nacimiento. Afectará los reportes de tratamientos.</p>
                </div>
              <?php endif; ?>
            </div>
            <div id="divPatientTreatmentDiagnostic">
              <div class="col-md-5">
                <div class="form-group">
                  <label for="inputEmail1">Diagnóstico</label>
                  <select class="form-control" id="patientTreatmentDiagnostic">
                    <?php foreach ($treatmentDiagnostics as $diagnostic) : ?>
                      <option value="<?php echo $diagnostic->id ?>"><?php echo $diagnostic->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-5" id="divPatientTreatmentDiagnosticOther">
                <div class="form-group">
                  <label for="inputEmail1">Otro diagnóstico:</label>
                  <input type="text" class="form-control" id="patientTreatmentDiagnosticOther">
                </div>
              </div>
            </div>
            <div class="col-md-5" id="divPatientTreatmentLocation">
              <label for="inputEmail1"> Ubicación: <button class="btn btn-danger btn-xs"><i class="fas fa-exclamation"></i> Importante</button></label>
              <div class="form-group">
                <div class="radio">
                  <label>
                    <input type="radio" name="treatmentLocation" value="1" <?php echo ($treatmentLocationId == 1) ? "checked" : "" ?> default>
                    LOCAL (SE CREARÁ CÓDIGO DE EMBRIOLOGÍA SI CORRESPONDE)
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="treatmentLocation" value="2" <?php echo ($treatmentLocationId == 2) ? "checked" : "" ?>>
                    EXTERNO
                  </label>
                </div>
              </div>
            </div>
            <div class="col-md-2" id="patientCategorySave">
              <br>
              <button type="button" id="btnSaveCategory" class="btn btn-primary btn-xs" onclick="savePatientCategory()">Guardar Categoría</button>
            </div>
          </div>
          <div class="row" id="divTreatmentOptions">
            <div class="col-md-4 col-md-offset-8">
              <input type="hidden" value="<?php echo $patientCategoryId ?>" id="patientCategoryId">
              <input type="hidden" value="<?php echo $categoryTreatmentId ?>" id="categoryTreatmentId">
              <input type="hidden" value="<?php echo $categoryTreatmentStatus ?>" id="categoryTreatmentStatusId">
              <input type="hidden" value="<?php echo $isTreatmentPregnancyTest ?>" id="isTreatmentPregnancyTest">
              <input type="hidden" value="<?php echo $patient_id ?>" id="patientId">
              <input type="hidden" value="<?php echo $patient->fecha_na ?>" id="patientBirthday">
              <button type="button" id="btnCancelTreatment" class="btn btn-danger btn-xs" onclick="cancelTreatment()"><i class="fas fa-times"></i> Cancelar Tratamiento</button>
              <button type="button" id="btnFinishTreatment" class="btn btn-primary btn-xs" onclick="finishTreatment()"><i class="fas fa-check"></i> Finalizar Tratamiento</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3" id="divPregnancyTestDate">
              <div class="form-group">
                <label for="inputEmail1">Fecha notificación prueba embarazo</label>
                <input type="date" id="pregnancyTestDate" class="form-control" min="<?php echo date('Y-m-d') ?>" value="<?php echo $categoryPregnancyTestDate ?>" onchange="selectPregnancyTestDate()"></input>
              </div>
            </div>
            <div class="col-md-2">
              <br>
              <button type="button" id="btnSavePregnancyTestDate" class="btn btn-primary btn-xs" onclick="savePregnancyTestDate()"><i class="fas fa-check"></i>Guardar Fecha</button>
            </div>
            <div class="col-md-5" id="divPregnancyOptions">
              <button type="button" id="btnPregnancyTest" class="btn btn-primary btn-xs" onclick="showPregnancyResult()"><i class="fas fa-vial"></i> Resultado de Prueba de Embarazo</button>
              <div id="divPregnancyResultOptions">
                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="pregnancy_result" value="1" checked>
                      Embarazo Exitoso
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="pregnancy_result" value="0">
                      No se Embarazó
                    </label>
                  </div>
                </div>
                <button type="button" id="btnCancelTreatment" class="btn btn-danger btn-xs" onclick="hidePregnancyResult()"><i class="fas fa-times"></i> Cancelar</button>
                <button type="button" id="btnStartPregnancyTestTreatment" class="btn btn-primary btn-xs" onclick="savePregnancyResult()"><i class="fas fa-check"></i> Guardar</button>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <hr>
        <div class="row">
          <div class="col-md-3">
            <label for="inputEmail1"> Asistencia a la Cita: </label>
            <div class="form-group">
              <div class="radio">
                <label>
                  <input type="radio" name="iniciar_cita" value="noasistio" <?php echo ($reservation->status_reservation_id == 2) ? "checked" : "" ?>>
                  No asistió
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="iniciar_cita" value="asistio" <?php echo ($reservation->status_reservation_id == 1) ? "checked" : "" ?>>
                  Asistió
                </label>
              </div>
            </div>
          </div>
          <!-- Mostrar categorías y tratamientos para pacientes MUJERES-->
          <?php if ($patient->sex_id == 1) : ?>
            <div class="col-md-3" id="paps_test_data">
              <label for="inputEmail1">Papanicolaou:</label>
              <div class="form-group">
                <div class="radio">
                  <label>
                    <input type="radio" name="paps_test" value="paps" <?php echo ($reservation->papanicolaou_test == 1) ? "checked" : "" ?>>
                    Se realizó
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="paps_test" value="nopaps" <?php echo ($reservation->papanicolaou_test == 0) ? "checked" : "" ?>>
                    No se realizó
                  </label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Embarazo:</label><br>
                <div id="divPregnancyDetail">
                  <label id="lblPregnancyDetail">EMBARAZO <?php echo ($patientPregnancyDetail && $patientPregnancyDetail->pregnancy_type_id == 1) ? 'POR TRATAMIENTO ' . $patientPregnancyDetail->getTreatment()->treatment_name : 'EXTERNO'; ?> REGISTRADO EL <?php echo ($patientPregnancyDetail) ? $patientPregnancyDetail->getStartDateFormat() : '-'; ?> </label><br>
                  <button type="button" id="btnFinishPregnancy" class="btn btn-primary btn-xs" onclick="finishPregnancy()"><i class="fas fa-check"></i> Finalizar Embarazo</button>
                  <input type="hidden" id="patientPregnancyId" value="<?php echo ($patientPregnancyDetail) ? $patientPregnancyDetail->id : '' ?>">
                </div>
                <div id="divExternalPregnancyDetail">
                  <input type="checkbox" id="externalPregnancy" onclick="showExternalPregnancyOptions()">Marcar como Embarazo Externo
                  <br>
                  <button type="button" id="btnSaveExternalPregnancy" class="btn btn-primary btn-xs" onclick="saveExternalPregnancy()"><i class="fas fa-check"></i> Guardar</button>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <hr>
        <div class="row" id="reservation_note">
          <div class="col-md-12">
            <form class="form-horizontal" role="form" method="post" action="./?action=add_resumen">
              <input type="hidden" name="id_medico" value="<?php echo $reservation->medic_id ?>">
              <input type="hidden" name="id_paciente" value="<?php echo $patient_id ?>">
              <input type="hidden" name="id_reser" value="<?php echo  $_GET["id"] ?>">
              <input type="hidden" name="fecha" value="<?php echo  $_GET["fecha"] ?>">
              <label for="inputEmail1" class="control-label">Nota Paciente</label>
              <div class="col-lg-12">
                <textarea class="form-control" id="note" name="note" rows="10"></textarea>
                <button type="submit" id="btnSaveNote" class="btn btn-default">GUARDAR</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Historial</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <?php foreach ($patientNotes as $note) {
              $idEx = $note->id;
              $date = $note->fecha;
              echo "<hr>";
              echo '<b><button type="submit" class="btn btn-large btn-primary" onClick="showRecord(' . $idEx . ')" id="editar">Nota ' . $date . '</button></b>
              <br>';
              echo $note->resumen;
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    //ASISTENCIA
    let status_reservation = "<?php echo $reservation->status_reservation_id; ?>";
    if (status_reservation == 1) {
      //Asistió
      $("#reservation_note").show(); //Mostrar nota si asistió
      $("#paps_test_data").show(); //Mostrar campos de Papanicolaou si asistió
      let reservation_note = '<?php echo json_encode($reservationActualNote); ?>';

      if (reservation_note != 'null' && reservation_note != null && reservation_note != "") {
        //Si ya tiene una nota no permitirle agregar nueva, que edite la existente en la parte inferior.
        $("#btnSaveNote").attr('disabled', true);
        $('.nicEdit-main').attr('contenteditable', 'false');
        $('.nicEdit-panel').hide();
      }
    } else {
      //No asistió o pendiente de definir
      $("#reservation_note").hide();
      $("#paps_test_data").hide();
    }

  });

  function confirmar() {
    var flag = confirm("¿¿Seguro quedeseas eliminar?");
    if (flag == true) {
      return true;
    } else {
      return false;
    }
  }

  function showRecord(id) {
    window.close();
    var url = "./?view=editexpediente&id=" + id + "";
    window.open(url, 'popup', 'width=800,height=600, top=100, left=200');
  }

  //CAMBIO DE ASISTENCIA
  $('input[type=radio][name=iniciar_cita]').change(function() {

    if (this.value == 'asistio') {
      $.ajax({
        url: "./?action=updatereservationstatus",
        type: "POST",
        data: {
          reservation_id: <?php echo $reservation->id ?>,
          status_id: 1
        },
        success: function() {
          $("#reservation_note").show();
          $("#paps_test_data").show();
        },
        error: function() {
          $('input[type=radio][name=iniciar_cita][value="noasistio"]').prop('checked', true).change();
          alert("Ha ocurrido un error al registrar la asitencia.");
        }
      });
    } else if (this.value == 'noasistio') {
      var note_content = $("div.nicEdit-main").html().trim(); //Get Textarea content

      if (note_content !== "" && note_content != "<br>") {
        alert("Borra la nota si el paciente no asistió.");
        $('input[type=radio][name=iniciar_cita][value="asistio"]').prop('checked', true).change();
      } else {
        //Actualizar estatus en BD
        $.ajax({
          url: "./?action=updatereservationstatus",
          type: "POST",
          data: {
            reservation_id: <?php echo $reservation->id ?>,
            status_id: 2
          },
          success: function() {
            $("#reservation_note").hide();
            $('input[type=radio][name=paps_test][value="nopaps"]').prop('checked', true).change();
            $("#paps_test_data").hide();
          },
          error: function() {
            $('input[type=radio][name=iniciar_cita][value="asistio"]').prop('checked', true).change();
            alert("Ha ocurrido un error al registrar la asitencia.");
          }
        });
      }
    }
  });

  //PAPANICOLAU
  $('input[type=radio][name=paps_test]').change(function() {

    if (this.value == 'paps') {
      $.ajax({
        url: "./?action=updatereservationpapstest",
        type: "POST",
        data: {
          reservation_id: <?php echo $reservation->id ?>,
          papanicolaou_test: 1,
        },
        success: function() {

        },
        error: function() {
          $('input[type=radio][name=paps_test][value="nopaps"]').prop('checked', true).change();
          alert("Ha ocurrido un error al registrar el dato de Papanicolaou.");
        }
      });
    } else if (this.value == 'nopaps') {
      //Actualizar estatus en BD
      $.ajax({
        url: "./?action=updatereservationpapstest",
        type: "POST",
        data: {
          reservation_id: <?php echo $reservation->id ?>,
          papanicolaou_test: 0
        },
        success: function() {

        },
        error: function() {
          $('input[type=radio][name=paps_test][value="paps"]').prop('checked', true).change();
          alert("Ha ocurrido un error al registrar el dato de Papanicolaou.");
        }
      });

    }
  });
</script>