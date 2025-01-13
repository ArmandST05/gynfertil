<?php
$medicId = null;
$user = UserData::getLoggedIn();
//Si es un usuario doctor obtener datos para visualizar la categoría
if ($_SESSION['typeUser'] == "do") {
  $medicLogged = MedicData::getByUserId($user->id);
  $medicId = $medicLogged->id;
}

$patientNotes = ReservationData::get_resumen_detalle($_GET["id_paciente"]);
$patientId = $_GET["id_paciente"];
$patient = PatientData::getById($patientId);
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

if ($patient->sex_id == 1) {
  //CATEGORÍAS Y TRATAMIENTOS DE FERTILIDAD PARA MUJERES
  $categories = PatientCategoryData::getAllCategories();
  $treatments = PatientCategoryData::getAllTreatments();
  //Diagnósticos de tratamientos
  $treatmentDiagnostics = TreatmentDiagnosticData::getAll();

  //Obtiene la última categoría/tratamiento del paciente.
  $patientCategoryDetail = PatientCategoryData::getPatientCategoryDetail($patientId);
  $patientCategoryId = (isset($patientCategoryDetail)) ? $patientCategoryDetail->patient_category_id : 0;
  $patientTreatmentId = (isset($patientCategoryDetail)) ? $patientCategoryDetail->patient_treatment_id : 0;
  $categoryTreatmentId = (isset($patientCategoryDetail)) ? $patientCategoryDetail->id : 0;
  $categoryTreatmentStatus = (isset($patientCategoryDetail)) ? $patientCategoryDetail->treatment_status_id : 0;
  $isTreatmentPregnancyTest = (isset($patientCategoryDetail)) ? $patientCategoryDetail->is_pregnancy_test : 0; //Validar si el tratamiento requiere realizar una prueba de embarazo al finalizar
  $categoryPregnancyTestDate = (isset($patientCategoryDetail)) ? $patientCategoryDetail->pregnancy_test_date : 0;
  $treatmentLocationId = (isset($patientCategoryDetail)) ? $patientCategoryDetail->treatment_location_id : 1;
  $patientPregnancyDetail = PatientPregnancyData::getByPatientId($patientId);

  //Obtiene todas las categorías y tratamientos en orden cronológico
  $patientCategoryHistory = PatientCategoryData::getAllPatientCategories($patientId);

  //Obtiene la cantidad de tratamientos que ha tenido el paciente por tipo de tratamiento (ciclos de tratamiento)
  $patientTreatmentsCycles = PatientCategoryData::getTreatmentsAmountByPatient($patientId);
}

//Obtiene los procedimientos de andrología dependiendo del sexo del paciente (IIUD para mujeres)
$andrologyProcedures = AndrologyProcedureData::getBySex($patient->sex_id);
//Obtiene todas las categorías y tratamientos en orden cronológico
$patientAndrologyHistory = AndrologyProcedureData::getAllPatientProcedures($patientId);
?>

<!-- ACCIONES PARA CATEGORÍAS/TRATAMIENTOS/EMBARAZO-->
<script src="core/app/view/patientCategoryScript.js" type="text/javascript"></script>

<div class="row">
  <div class="col-md-12">
    <h1>Expediente paciente</h1>
    <br>
    <input type="hidden" name="id_paciente" value="<?php echo $_GET["id_paciente"] ?>">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Datos del Paciente</h3><br>
        <div class="box-tools pull-left">
          <?php if ($patient->donor_id != null) : ?>
            <button type="button" class="btn btn-primary btn-xs">Donante: <?php echo $patient->donor_id ?></button>
          <?php endif; ?>
        </div>
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
          echo "<b>Fecha Papanicolaou: </b>" . $patient->getLastPapanicolaouTestDate() . "<br>";
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
    <!-- Mostrar categorías y tratamientos para pacientes MUJERES-->
    <?php if ($patient->sex_id == 1 && ($_SESSION['typeUser'] != "a")) : ?>
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Categoría/Tratamientos</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-5">
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
            <div class="col-md-5" id="divPatientTreatment">
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
              <input type="hidden" value="<?php echo $patientId ?>" id="patientId">
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
          <div class="row">
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
          </div>
          <hr>
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
              <li class=""><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">Historial</a></li>
              <li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">Ciclos</a></li>
              <li class="active"><a href="#tab_3-2" data-toggle="tab" aria-expanded="true">Resumen</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane" id="tab_1-1">
                <table id="historyTable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th style="display:none">Id</th>
                      <th>Fecha Inicio</th>
                      <th>Fecha Fin</th>
                      <th>Categoría/Tratamiento</th>
                      <th>Estatus</th>
                      <th>Notas</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($patientCategoryHistory as $category) :
                      $statusName = $category->status_name;
                      //Si se finalizó el tratamiento y se tiene qué mostrar resultado de la prueba de emebarazo
                      if (($category->patient_treatment_id != null || $category->patient_treatment_id != 0)) {
                        if ($category->treatment_status_id == 4) {
                          if (isset($category->pregnancy_test_date)) { //Si hubo transferencia
                            $statusName .= (($category->pregnancy_test_result == 1) ? " - SE EMBARAZÓ" : " - NO SE EMBARAZÓ");
                          } else { //No hubo transferencia
                            $statusName .=  " - SIN TRANSFERENCIA";
                          }
                        }
                      }
                    ?>
                      <tr>
                        <td style="display:none"><?php echo number_format($category->id) ?></td>
                        <td><?php echo $category->start_date_format ?></td>
                        <td><?php echo $category->end_date_format ?></td>
                        <td><?php echo $category->category_name . " " . $category->treatment_name ?></td>
                        <td><?php echo $statusName ?></td>
                        <td><?php echo $category->note ?></td>
                        <td>
                          <?php if (($category->patient_treatment_id != null || $category->patient_treatment_id != 0) && $category->is_embryology_procedure == 1 && $category->treatment_status_id != 3) : ?>
                            <?php if (($_SESSION['typeUser'] == "su") || ($_SESSION['typeUser'] == "an") || ($_SESSION['typeUser'] == "do" && $medicLogged->category_id == 8)) : ?>
                              <a href="index.php?view=embryology-procedures/details&treatmentId=<?php echo $category->id; ?>" rel="tooltip" title="Capturar" class="btn btn-simple btn-primary btn-xs"><i class='fas fa-pencil-alt'></i> Capturar</a>
                            <?php endif; ?>
                            <a target="_blank" href='index.php?view=embryology-procedures/treatment-<?php echo strtolower($category->embryology_procedure_code) ?>-patient-report&id=<?php echo $category->id ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-male"></i>
                              Reporte Paciente</a>
                            <a target="_blank" href='index.php?view=embryology-procedures/treatment-<?php echo strtolower($category->embryology_procedure_code) ?>-report&id=<?php echo $category->id ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-user-md"></i>
                              Reporte Médico</a>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2-2">
                <table id="historyTable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Tratamiento</th>
                      <th>Ciclos</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($patientTreatmentsCycles as $treatment) : ?>
                      <tr>
                        <td><?php echo $treatment->treatment_name ?></td>
                        <td><?php echo $treatment->total ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane active" id="tab_3-2">
                <?php echo PatientCategoryData::getPatientHistoryResume($patient->id) ?>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    <?php endif; ?>
    <!-- Mostrar procedimientos de andrología comunmente para hombres pero también para mujeres en casos específicos-->
    <div class="box box-primary collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title">Procedimientos de Andrología</h3>
        <div class="box-tools pull-left">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
          <?php if ($patient->sex_id == 2 && $patient->donor_id == null) : ?>
            <form class="form-horizontal" role="form" method="POST" action="./?action=patients/add-male-donor" id="formSavePatientAsDonor">
              <button type="button" class="btn btn-primary btn-xs" onclick="savePatientAsDonor()">Registrar como donante</button>
              <input type="hidden" name="patientId" value="<?php echo $patient->id ?>" required>
            </form>
          <?php endif; ?>

        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body" style="display: none;">
        <form class="form-horizontal" role="form" method="POST" action="./?action=andrology-procedures/add-patient" id="formSaveAndrologyProcedure">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-md-4">
                  <label for="inputEmail1">Procedimiento:</label>
                  <select class="form-control" name="andrologyProcedureId" required>
                    <?php foreach ($andrologyProcedures as $andrologyProcedure) : ?>
                      <option value="<?php echo $andrologyProcedure->id ?>"><?php echo $andrologyProcedure->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="inputEmail1">Personal médico:</label>
                  <select class="form-control" id="medicId" name="medicId" required>
                    <?php foreach ($medics as $medic) : ?>
                      <option value="<?php echo $medic->id ?>"><?php echo $medic->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="inputEmail1">Fecha:</label>
                  <input type="date" class="form-control" name="date" value="<?php echo date("Y-m-d") ?>" required>
                </div>
                <div class="col-md-2">
                  <br>
                  <button type="button" class="btn btn-primary btn-xs" onclick="saveAndrologyProcedure()">Agregar</button>
                  <input type="hidden" name="patientId" value="<?php echo $patient->id ?>" required>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                  <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="true">Historial</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1-1">
                    <table id="historyTable" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th style="display:none">Id</th>
                          <th>Fecha</th>
                          <th>Código</th>
                          <th>Procedimiento</th>
                          <th>Personal médico</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($patientAndrologyHistory as $procedure) : ?>
                          <tr>
                            <td style="display:none"><?php echo number_format($procedure->id) ?></td>
                            <td><?php echo $procedure->date_format ?></td>
                            <td><?php echo $procedure->procedure_code ?></td>
                            <td><?php echo $procedure->getMedic()->name ?></td>
                            <td><?php echo $procedure->name ?></td>
                            <td><a target="_blank" href="index.php?view=andrology-procedures/details&procedureId=<?php echo $procedure->id ?>" class="btn btn-primary btn-xs"><i class="fas fa-pencil-alt"></i> Capturar</a>
                              <a target="_blank" href='index.php?view=andrology-procedures/procedure-<?php echo strtolower($procedure->andrology_procedure_code) ?>-report&id=<?php echo $procedure->id ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-user-md"></i> Reporte</a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
            </div>
          </div>
        </form>
      </div>
      <!-- /.box-body -->
    </div>
    <?php if ($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "sub" || ($_SESSION['typeUser'] == "do" && $medicLogged->category_id != 8)) : ?>
      <div class="col-lg-12">
        <?php foreach ($patientNotes as $note) : ?>
          <hr>
          <td><b><button type="submit" class="btn btn-large btn-primary" onClick="mostrarExpediente('<?php echo $note->id ?>')" id="editar">Nota <?php echo $note->fecha ?></button></b><br>
          <?php
          echo $note->resumen;
        endforeach;
          ?>
          <hr>
      </div>
    <?php endif; ?>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).ready(function() {
      $("#medicId").select2({});
    });

    var historyTable = $('#historyTable').DataTable({
      "columnDefs": [{
        "type": "num",
        "targets": 0
      }],
      "ordering": false,
      language: {
        url: 'plugins/datatables/languages/es-mx.json'
      }
    });
  });

  function mostrarExpediente(id) {
    window.close();
    var url = "./?view=editexpediente&id=" + id + "";
    window.open(url, 'popup', 'width=800,height=600, top=100, left=200');
  }

  function saveAndrologyProcedure() {
    Swal.fire({
      title: '¿Deseas registrar este procedimiento al paciente? Asegúrate de seleccionar los datos correctos.',
      text: "Esta acción no se podrá revertir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value == true) {
        $("#formSaveAndrologyProcedure").submit();
      }
    })
  }

  function savePatientAsDonor() {
    Swal.fire({
      title: '¿Deseas registrar este paciente como donante?',
      text: "Esta acción no se podrá revertir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value == true) {
        $("#formSavePatientAsDonor").submit();
      }
    })
  }
</script>