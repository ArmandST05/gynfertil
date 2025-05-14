<?php
$user = UserData::getLoggedIn();
$userType = $user->user_type;

if ($_SESSION['typeUser'] == "pa") {
  $patient = PatientData::getByUserId($user->id);
} else {
  $patientId = $_GET["patientId"];
}

$patient = PatientData::getById($patientId);
$lastVitalSigns = ExplorationExamData::getLastByPatientType($patientId, 1);
$lastVitalSignsArray = array_chunk($lastVitalSigns, 2);
$recordSections = RecordSectionData::getRecordsByPatient($patientId);
$recordSectionsArray = array_chunk($recordSections, 2);
$medics = MedicData::getAll();
$treatments = TreatmentData::getAll();
$reservations = ReservationData::getByPatient($patientId); //Historial de citas
$totalReservations = count($reservations);

$patientTreatment = TreatmentData::getPatientActualTreatment($patientId);
$patientTreatmentId = (isset($patientTreatment)) ? $patientTreatment->id : null;
$treatmentCode = null;
$treatmentId = null;
$medicTreatmentId = null;
$treatmentDefaultPrice = null;
$psychiatrist = null;
if ($patientTreatmentId) {
  $details = TreatmentData::getDetailsByPatientTreatment($patientTreatmentId);
  $treatmentCode = $patientTreatment->treatment_code;
  $treatmentId = $patientTreatment->treatment_id;
  $medicTreatmentId = $patientTreatment->medic_id;
  $treatmentDefaultPrice = $patientTreatment->default_price;
  $psychiatrist = $patientTreatment->psychiatrist;
}
//Obtiene todas las categorías y tratamientos en orden cronológico
$patientTreatmentHistory = TreatmentData::getAllPatientTreatments($patientId);

$educationLevels = EducationLevelData::getAll();
$counties = CountyData::getAll();
?>
<input type="hidden" id="patientId" name="patientId" value="<?php echo $_GET["patientId"] ?>">
<div class="row">
  <div class="col-lg-12">
    <h1>Expediente del paciente</h1>
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Datos del Paciente</h3>
        <div class="pull-right">
          <?php if ($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "r") : ?>
            <a target="_blank" href='./?view=patients/edit&id=<?php echo $_GET["patientId"] ?>' class='btn btn-primary btn-xs'><i class="fas fa-pencil-alt"></i> Editar</a>
          <?php endif; ?>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-3">
            <img class="profile-user-img img-responsive img-circle" src='<?php echo ($patient->image) ? "storage_data/patients/" . $patient->image : "../../../assets/default_user.jpg" ?>' alt="Foto del paciente">
          </div>
          <div class="col-md-9">
            <b>Nombre completo: </b><?php echo $patient->name ?><br>
            <?php if ($userType != "do") : ?>
              <b>Dirección: </b><?php echo $patient->street ?> <?php echo $patient->number ?>
              <?php echo $patient->colony ?><br>
              <b>Teléfono: </b><?php echo $patient->cellphone ?> <br><b>Teléfono alternativo:
              </b><?php echo $patient->homephone ?><br>
              <b>Email: </b><?php echo $patient->email ?><br>
              <b>Fecha nacimiento: </b><?php echo $patient->getBirthdayFormat() ?><br>
              <b>Edad: </b><?php echo $patient->getAge() ?><br>
              <b>Referido por: </b><?php echo $patient->referred_by ?><br>
              <b>Observaciones: </b><br>
              <div class="form-group">
                <div class="col-md-12">
                  <textarea name="observations" class="form-control" id="observations" rows="10" readonly><?php echo $patient->observations ?></textarea>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-9">

          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Tratamientos</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <?php if ($userType != "do") : ?>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="inputEmail1">Tratamiento</label>
                <select class="form-control" id="treatmentId">
                  <?php foreach ($treatments as $treatment) : ?>
                    <option value="<?php echo $treatment->id ?>" <?php echo ($treatmentId == $treatment->id) ? 'selected' : '' ?>><?php echo $treatment->name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Psicólogo:</label>
                <select id="medicTreatment" name="medicTreatment" class="form-control" required>
                  <?php foreach ($medics as $medic) : ?>
                    <option value="<?php echo $medic->id; ?>" <?php echo ($medic->id == $medicTreatmentId) ? "selected" : "" ?>><?php echo $medic->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Precio predeterminado</label>
                <input type="number" min="0" step=".01" name="defaultPrice" value="<?php echo $treatmentDefaultPrice; ?>" class="form-control" id="defaultPrice" placeholder="Precio">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Motivo:</label>
                <textarea class="form-control" id="reason" name="reason"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 col-md-offset-8" id="divSaveTreatment">
              <br>
              <button type="button" id="btnSaveTreatment" class="btn btn-primary btn-xs" onclick="savePatientTreatment()">Guardar Tratamiento</button>
            </div>
          </div>
          <div class="row" id="divTreatmentOptions">
            <div class="col-md-4 col-md-offset-8">
              <input type="hidden" value="<?php echo $patientTreatmentId ?>" id="patientTreatmentId">
              <input type="hidden" value="<?php echo $patientId ?>" id="patientId">
              <button type="button" id="btnCancelTreatment" class="btn btn-danger btn-xs" onclick="cancelTreatment()"><i class="fas fa-times"></i> Baja tratamiento</button>
              <button type="button" id="btnFinishTreatment" class="btn btn-primary btn-xs" onclick="finishTreatment()"><i class="fas fa-check"></i> Alta tratamiento</button>
            </div>
          </div>
        <?php endif; ?>
        <hr>
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
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Duración</th>
                    <th>Tratamiento</th>
                    <th>Psicólogo</th>
                    <th>Precio</th>
                    <th>Motivo tratamiento</th>
                    <th>Estatus</th>
                    <th>Última anotación</th>
                    <th>Psiquiatra</th>
                    <th>Motivo baja</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($patientTreatmentHistory as $index => $treatment) :
                  ?>
                    <tr>
                      <td style="display:none"><?php echo number_format($treatment->id) ?></td>
                      <td><?php echo $treatment->start_date_format ?></td>
                      <td><?php echo $treatment->end_date_format ?></td>
                      <td><?php echo $treatment->getTreatmentDuration() ?></td>
                      <td><?php echo $treatment->treatment_name ?></td>
                      <td><?php echo $treatment->getMedic()->name ?></td>
                      <td>$<?php echo number_format($treatment->default_price, 2) ?></td>
                      <td><?php echo $treatment->reason ?></td>
                      <td><?php echo $treatment->status_name ?></td>
                      <td><?php echo $treatment->last_note ?></td>
                      <td><?php echo $treatment->psychiatrist ?></td>
                      <td><?php echo $treatment->cancellation_reason ?></td>
                      <td>
                        <?php if ($treatment->treatment_id != 5 && $userType != "do") : //Tratamiento desconocido
                        ?>
                          <a target="_blank" href='./?view=patients/report-interview-<?php echo $treatment->treatment_code ?>&id=<?php echo $treatment->id ?>' class='btn btn-default btn-xs'><i class="fas fa-file-alt"></i> Entrevista</a>
                        <?php endif; ?>
                        <?php if ($userType != "do") : ?>
                          <button type="button" class="btn btn-xs btn-primary" onclick="showEditTreatment('<?php echo $index ?>')"><i class="fas fa-pencil-alt"></i> Editar</button>
                        <?php endif; ?>
                        <?php if ($_SESSION['typeUser'] == "su") : ?>
                          <button type="button" class="btn btn-xs btn-danger" onclick="deleteTreatment('<?php echo $treatment->id ?>')"><i class="fas fa-trash"></i> Eliminar</button>
                        <?php endif; ?>
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
      <!-- /.box-body -->
    </div>
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Historial</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <?php
        if ($totalReservations > 0) :
          // si hay resultados
        ?>
          <table class="table table-bordered table-hover table-responsive" id="table">
            <h5>
              <?php if ($totalReservations == 1) echo $totalReservations . " Resultado";
              else echo $totalReservations . " Resultados";
              ?></h5>
            <thead>
              <th>Fecha/Hora</th>
              <th>Paciente</th>
              <?php if ($userType != "do"):?>
              <th>Teléfono</th>
              <?php endif;?>
              <th>Psicólogo</th>
              <th>Familiar</th>
              <th>Estatus</th>
              <th>Acciones</th>
            </thead>
            <?php
            foreach ($reservations as $reservation) :
              $medic = $reservation->getMedic();
            ?>
              <tr>
                <td><?php echo $reservation->day_name . " " . $reservation->date_at_format; ?></td>
                <td><?php echo $patient->name; ?></td>
                <?php if ($userType != "do"):?>
                <td><?php echo $patient->cellphone; ?></td>
                <?php endif; ?>
                <td><?php echo $medic->name; ?></td>
                <td><?php echo $patient->relative_name; ?></td>
                <td><?php echo $reservation->getStatus()->name; ?></td>
                <td style="width:240px;">
                  <?php if ($reservation->status_id == 2) : ?>
                    <a target="_blank" href="index.php?view=reservations/details&id=<?php echo $reservation->id ?>" class="btn btn-default btn-xs"><i class='fas fa-align-justify'></i> Detalles de la cita</a>
                  <?php endif; ?>
                  <?php if ($_SESSION["typeUser"] == "su") : ?>
                    <a href="index.php?view=reservations/edit-patient&id=<?php echo $reservation->id ?>" class="btn btn-warning btn-xs"><i class='fas fa-pencil-alt'></i> Editar</a>
                    <!--<button id="btnDeleteReservation" onclick="deleteReservation('<?php echo $reservation->id ?>')" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i> Eliminar</button>-->
                  <?php else : ?>
                    <?php if ($userType != "do" && date_create_from_format("Y-m-d", substr($reservation->date_at, 0, 10)) > date_create_from_format("Y-m-d", date("Y-m-d"))) : ?>
                      <!--<button id="btnDeleteReservation" onclick="deleteReservation('<?php echo $reservation->id ?>')" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i> Eliminar</button>-->
                    <?php endif; ?>
                    <?php if ($userType != "do" && date_create_from_format("Y-m-d", substr($reservation->date_at, 0, 10)) >= date_create_from_format("Y-m-d", date("Y-m-d"))) : ?>
                      <a href="index.php?view=reservations/edit-patient&id=<?php echo $reservation->id ?>" class="btn btn-warning btn-xs"><i class='fas fa-pencil-alt'></i> Editar</a>
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>

        <?php else : echo "<p class='alert alert-danger'>No se encontraron resultados</p>";
        endif; ?>
      </div>
      <!-- /.box-body -->
    </div>
    <!--MODAL-->
    <div class="modal fade" id="modalEditTreatment">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Editar tratamiento</h4>
          </div>
          <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="index.php?action=treatments/update-patient-treatment" role="form">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="inputEmail1">Fecha de inicio</label>
                      <input type="date" name="startDate" id="startDateEdit" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4" id="divEndDateEdit">
                    <div class="form-group">
                      <label for="inputEmail1">Fecha de fin</label>
                      <input type="date" name="endDate" id="endDateEdit" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="inputEmail1">Tratamiento</label>
                      <select class="form-control" id="treatmentIdEdit" name="treatmentId" required>
                        <option value="5" disabled>NO ESPECIFICADO</option>
                        <?php foreach ($treatments as $treatment) : ?>
                          <option value="<?php echo $treatment->id ?>"><?php echo $treatment->name ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Psicólogo</label>
                      <select id="medicTreatmentEdit" name="medicId" class="form-control" required>
                        <?php foreach ($medics as $medic) : ?>
                          <option value="<?php echo $medic->id; ?>"><?php echo $medic->name; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Precio predeterminado</label>
                      <input type="number" step=".01" name="defaultPrice" id="defaultPriceEdit" class="form-control" placeholder="Precio">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Psiquiatra</label>
                      <input type="text" name="psychiatrist" id="psychiatristEdit" class="form-control" placeholder="Psiquiatra">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Motivo de tratamiento</label>
                      <textarea class="form-control" id="reasonEdit" name="reason"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row" id="divLastNoteEdit">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Última anotación</label>
                      <input type="text" class="form-control" id="lastNoteEdit" name="lastNote">
                    </div>
                  </div>
                </div>
                <div class="row" id="divCancellationReasonEdit">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Motivo de baja</label>
                      <input type="text" class="form-control" id="cancellationReasonEdit" name="cancellationReason">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Actualizar</button>
              <input type="hidden" name="patientTreatmentId" class="form-control" id="patientTreatmentIdEdit">
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--MODAL-->
    <?php if ($userType != "do" && $treatmentCode) {
      include("interview-" . strtolower($treatmentCode) . "-view.php");
    }
    ?>
  </div>
</div>
<script type='text/javascript'>
  $(document).ready(function() {
    $('#modalEditTreatment').modal({
      keyboard: false,
      backdrop: false,
      show: false
    });

    $("#countyId").select2({});

    //TRATAMIENTOS
    let patientTreatmentId = $("#patientTreatmentId").val();
    if (patientTreatmentId) {
      $("#treatmentId").prop("disabled", true);
      $("#medicTreatment").prop("disabled", true);
      $("#defaultPrice").prop("disabled", true);
      $("#reason").prop("disabled", true);

      $("#divTreatmentOptions").show(); //Mostrar opciones de cancelar y finalizar
      $("#divSaveTreatment").hide(); //Guardar nueva categoría
    } else {
      $("#divTreatmentOptions").hide(); //Ocultar opciones de cancelar y finalizar
      $("#divSaveTreatment").show(); //Guardar nueva categoría
    }
  });

  //TRATAMIENTOS DEL PACIENTE
  function savePatientTreatment() {
    $.ajax({
      url: "./?action=treatments/add-patient-treatment",
      type: "POST",
      data: {
        treatmentId: $("#treatmentId").val(),
        medicId: $("#medicTreatment").val(),
        defaultPrice: $("#defaultPrice").val(),
        patientId: $("#patientId").val(),
        reason: $("#reason").val()
      },
      success: function(data) {
        let treatmentData = JSON.parse(data);
        $("#patientTreatmentId").val(treatmentData["id"]);
        $("#divTreatmentOptions").show(); //Mostrar opciones de cancelar y finalizar
        $("#divSaveTreatment").hide();
        $("#patientTreatment").prop("disabled", true);
        $("#treatmentId").prop("disabled", true);
        $("#medicTreatment").prop("disabled", true);
        $("#defaultPrice").prop("disabled", true);
        $("#reason").prop("disabled", true);
        window.location.reload();
      },
      error: function() {
        Swal.fire(
          'Error',
          'Ha ocurrido un error al registrar el tratamiento, recarga la página.',
          'error'
        )
      }
    });
  }

  function cancelTreatment() {
    Swal.fire({
      title: '¿Deseas dar de baja el tratamiento?',
      text: "Esta acción no se podrá revertir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, dar de baja tratamiento',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value == true) {
        Swal.fire({
          title: 'Datos de baja de tratamiento',
          html: '<input id="swal-input1" class="swal2-input" placeholder="Motivo de baja (Requerido)">' +
            '<input id="swal-input2" class="swal2-input" placeholder="Última anotación (Opcional)">' +
            '<input id="swal-input3" class="swal2-input" placeholder="Psiquiatra (Opcional)">',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            $.ajax({
              type: "POST",
              url: "./?action=treatments/update-patient-treatment-status",
              data: {
                patientTreatmentId: $("#patientTreatmentId").val(),
                statusId: 2,
                cancellationReason: document.getElementById('swal-input1').value,
                lastNote: document.getElementById('swal-input2').value,
                psychiatrist: document.getElementById('swal-input3').value,
              },
              error: function() {
                Swal.fire(
                  'Error',
                  'No se pudo dar de baja el tratamiento..',
                  'error'
                )
              },
              success: function(data) {
                $("#treatmentId").prop("disabled", false);
                $("#medicTreatment").prop("disabled", false);
                $("#defaultPrice").prop("disabled", false);
                $("#divTreatmentOptions").hide();
                $("#patientTreatmentId").val(0);
                window.location.reload();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        })
      }
    })
  }

  function finishTreatment() {
    //Cambiar el estatus del tratamiento
    Swal.fire({
      title: '¿Deseas dar de alta el tratamiento?',
      text: "Esta acción no se podrá revertir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, dar de alta el tratamiento',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value == true) {
        Swal.fire({
          title: 'Datos de alta de tratamiento',
          html: '<input id="swal-input2" class="swal2-input" placeholder="Última anotación (Opcional)">' +
            '<input id="swal-input3" class="swal2-input" placeholder="Psiquiatra (Opcional)">',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            $.ajax({
              type: "POST",
              url: "./?action=treatments/update-patient-treatment-status",
              data: {
                patientTreatmentId: $("#patientTreatmentId").val(),
                statusId: 3,
                lastNote: document.getElementById('swal-input2').value,
                psychiatrist: document.getElementById('swal-input3').value,
              },
              error: function() {
                Swal.fire(
                  'Error',
                  'No se pudo dar de alta del tratamiento..',
                  'error'
                )
              },
              success: function(data) {
                $("#treatmentId").prop("disabled", false);
                $("#medicTreatment").prop("disabled", false);
                $("#defaultPrice").prop("disabled", false);
                $("#reason").prop("disabled", true);
                $("#divTreatmentOptions").hide();
                $("#patientTreatmentId").val(0);
                window.location.reload();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        })
      }
    });
  }

  function deleteTreatment(id) {
    Swal.fire({
      title: '¿Deseas eliminar el tratamiento?',
      text: "Esta acción NO se podrá revertir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value == true) {
        $.ajax({
          type: "POST",
          url: "./?action=treatments/delete-patient-treatment",
          data: {
            id: id
          },
          error: function() {
            Swal.fire(
              'Error',
              'No se pudo eliminar el tratamiento..',
              'error'
            )
          },
          success: function(data) {
            window.location.reload();
          }
        });
      }
    })
  }

  function showEditTreatment(indexTreatment) {
    var treatmentsList = <?php echo json_encode($patientTreatmentHistory) ?>;
    if (treatmentsList && treatmentsList[indexTreatment]) {
      var treatment = treatmentsList[indexTreatment];
      $("#patientTreatmentIdEdit").val(treatment['id']);
      $("#startDateEdit").val(treatment['start_date']);
      $("#endDateEdit").val(treatment['end_date']);
      $("#treatmentIdEdit").val(treatment['treatment_id']);
      $("#medicTreatmentEdit").val(treatment['medic_id']);
      $("#defaultPriceEdit").val(treatment['default_price']);
      $("#psychiatristEdit").val(treatment['psychiatrist']);
      $("#reasonEdit").val(treatment['reason']);
      $("#cancellationReasonEdit").val(treatment['cancellation_reason']);
      $("#lastNoteEdit").val(treatment['last_note']);

      if (treatment['status_id'] == 1) { //Activo
        $("#divEndDateEdit").hide();
        $("#divCancellationReasonEdit").hide();
        $("#divLastNoteEdit").hide();
      } else if (treatment['status_id'] == 2) { //Cancelado
        $("#divEndDateEdit").show();
        $("#divCancellationReasonEdit").show();
        $("#divLastNoteEdit").show();
      } else if (treatment['status_id'] == 3) { //Finalizado
        $("#divEndDateEdit").show();
        $("#divCancellationReasonEdit").hide();
        $("#divLastNoteEdit").show();
      }
      $('#modalEditTreatment').modal({
        show: true
      });
    } else {
      Swal.fire(
        'Error',
        'Ocurrió un error al cargar los datos del tratamiento..',
        'error'
      );
    }

  }
</script>