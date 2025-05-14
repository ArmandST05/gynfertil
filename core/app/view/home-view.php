<?php
//Configuración del calendario
$configuration = ConfigurationData::getAll();
$calendar_start_hour = (isset($configuration['calendar_start_hour'])) ? $configuration['calendar_start_hour']->value : "7:00:00";
$calendar_end_hour = (isset($configuration['calendar_end_hour'])) ? $configuration['calendar_end_hour']->value : "22:00:00";

//Calendario por sucursal
$branchOffices = BranchOfficeData::getAllByStatus(1);
$reservationStatus = ReservationStatusData::getAll();;
if ($_SESSION['typeUser'] == "su") {
  $searchBranchOfficeId = (isset($_GET["searchBranchOfficeId"])) ? $_GET["searchBranchOfficeId"] : 0;
} elseif ($_SESSION['typeUser'] == "co") {
  $searchBranchOfficeId = (isset($_GET["searchBranchOfficeId"])) ? $_GET["searchBranchOfficeId"] : UserData::getLoggedIn()->getBranchOffice()->id;
} else {
  $searchBranchOfficeId = UserData::getLoggedIn()->getBranchOffice()->id;
}
$searchMedicId = (isset($_GET["searchMedicId"])) ? $_GET["searchMedicId"] : 0;

/*Mostrar todo el calendario permite elegir un rango de fechas para mostrar
Por defecto, el calendario se muestra desde el mes anterior*/
$filterCalendar = isset($_GET["filter"])  ? $_GET["filter"] : false;

if ($filterCalendar) {
  //Mostrar el calendario en un rango de fechas
  $startDate = isset($_GET["startDate"])  ? $_GET["startDate"] :  date('Y-m-d');
  $endDate = isset($_GET["endDate"])  ? $_GET["endDate"] :  date('Y-m-d');
  $defaultDate = $startDate;

  $startDateTime = $startDate . " 00:00:01";
  $endDateTime = $endDate . " 23:59:59";

  if ($_SESSION['typeUser'] == "do") {
    //Doctores
    $medic = MedicData::getByUserId($_SESSION['user_id']);
    if ($medic) {
      $events = ReservationData::getBetweenDates($startDateTime, $endDateTime, 0, $medic->id);
    } else {
      $events = [];
    }
  } else {
    if ($searchBranchOfficeId != 0) {
      if ($searchMedicId == 0) {
        //Administradores, recepcionistas, enfermeras, auditores
        $events = ReservationData::getBetweenDates($startDateTime, $endDateTime, $searchBranchOfficeId, 0);
      } else {
        $events = ReservationData::getBetweenDates($startDateTime, $endDateTime, 0, $searchMedicId);
      }
    } else $events = [];
  }
} else {
  //Mostrar el calendario a partir de una fecha, por defecto
  $defaultDate = date('Y-m-d');

  $startDate = date('Y') . "-" . date("m", strtotime("-1 month")) . "-01"; //Obtener todas las citas desde el mes anterior

  if (date('m') == 01) {
    $startDate = date('Y', strtotime('-1 year')) . "-" . date('m', strtotime('-1 month')) . "-" . date('01');
  } else {
    $startDate = date('Y') . "-" . date('m', strtotime('-1 month')) . "-" . date('01');
  }

  if ($_SESSION['typeUser'] == "do") {
    //Doctores
    $medic = MedicData::getByUserId($_SESSION['user_id']);
    if ($medic) {
      $events = ReservationData::getByStartDate($startDate, 0, $medic->id);
    } else {
      $events = [];
    }
  } else {
    if ($searchBranchOfficeId != 0) {
      if ($searchMedicId == 0) {
        //Administradores, recepcionistas, enfermeras, auditores
        $events = ReservationData::getByStartDate($startDate, $searchBranchOfficeId, 0);
      } else {
        $events = ReservationData::getByStartDate($startDate, 0, $searchMedicId);
      }
    } else $events = [];
  }
}
?>
<style>
  #delay1 {
    font-size: 24px;
    color: red;
  }

  p {
    color: #000;
  }
</style>
<script>
  var selectedEvent = null;

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        start: 'prev,next today',
        center: 'title',
        end: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      themeSystem: 'standard',
      locale: 'es-us',
      editable: true,
      dayMaxEventRows: true, // allow "more" link when too many events, Era eventLimit en versiones menores a v5
      selectable: true,
      height: 2750, //Original 2750
      expandRows: true,
      navLinks: true,
      initialView: 'timeGridDay',
      slotMinTime: '<?php echo $calendar_start_hour; ?>',
      slotMaxTime: '<?php echo $calendar_end_hour; ?>',
      slotDuration: '00:40:00',
      eventOrderStrict: true,
      eventOrder: 'start,-duration,medicId,allDay',
      eventDisplay: 'block',
      eventDidMount: function(info) {
        // If a description exists add as second line to title
        const a = info.el.getElementsByClassName('fc-event-title');
        a[0].innerHTML = info.event.title;
      },
      select: function(info) {
        var start = (moment(info.start).format('YYYY-MM-DD HH:mm:ss'));

        <?php if ($_SESSION['typeUser'] != "do") : ?>
          document.location.href = "./?view=reservations/new-patient&start=" + start + "";
        <?php endif; ?>
      },
      eventClick: function(info) { 
        selectedEvent = info;//Guardar evento para modificarlo

        info.jsEvent.preventDefault(); // don't let the browser navigate
        var id = (info.event.id);
        var reservationType = (info.event.extendedProps.reservationType);
        var url = "";
        if ("<?php echo $_SESSION['typeUser'] ?>" == "su" || "<?php echo $_SESSION['typeUser'] ?>" == "co" || "<?php echo $_SESSION['typeUser'] ?>" == "r") {
          showReservationModal(id, info);
        } else if ("<?php echo $_SESSION['typeUser'] ?>" == "do") {
          url = "./?view=reservations/details&id=" + id + "";
          window.open(url, "_blank");
        } else if (reservationType == "medic") {
          url = "./?view=reservations/edit-medic&id=" + id + "";
          window.open(url, "_blank");
        } else {
        }
      },
      events: [
        <?php
        foreach ($events as $event) :
          $reason = addcslashes($event->reason, "\n\r");
          $hour = $event->getStartTime();
          $start = explode(" ", $event->date_at);
          if ($start[1] == '00:00:00') {
            $start = $start[0];
          } else {
            $start = $event->date_at;
          }
          $end = $event->date_at_final;

          //Mostrar el nombre del paciente o del doctor dependiendo del tipo de cita.
          if (isset($event->patient_id) && $event->patient_id != null) {
            $userName = $event->patient_name;
            $reservationType = "patient";
            $phone = (($_SESSION['typeUser'] == "do") ? "" : $event->patient_phone);
            $companyPatient = ($event->company_id != "" && $event->company_id != 0) ? "<i class='fa-solid fa-building-user'></i>" : "";

            //Obtener tratamiento activo en las fechas de la cita
          } else {
            $userName = $event->medic_name;
            $phone = "";
            $companyPatient = "";
            $reservationType = "medic";
          }

          $category = $event->reservation_category_name;
          $color = $event->calendar_color;

          $statusName = "";
          if ($event->status_id == 2) {
            $statusName = "<i class='fa-solid fa-check'></i><b>ASISTIÓ</b>";
          } else if ($event->status_id == 3) {
            $statusName = "<i class='fa-solid fa-xmark'></i><b>NO ASISTIÓ</b>";
          } else if ($event->status_id == 4) {
            $statusName = "<i class='fa-solid fa-ban'></i><b>CANCELADA</b>";
          }
          $patientNotified = "<i class='fa-solid fa-phone-slash'></i>"; //No recordado
          if ($event->is_patient_notified == 1) {
            $patientNotified = "<i class='fa-solid fa-phone'></i>"; //Recordado
          }

          $saleStatus = "<i class='fa-solid fa-triangle-exclamation'></i><i class='fa-solid fa-dollar-sign'></i>"; //Venta no generada (Pago no generado)

          if ($event->sale_status_payment == 2) { //La venta está pendiente
            $saleStatus = "<i class='fa-regular fa-clock'></i><i class='fa-solid fa-dollar-sign'></i>"; //Venta no liquidada (Pago pendiente liquidar)
          } else if ($event->sale_status_payment == 3){ //La venta está liquidada
            $saleStatus = "<i class='fa-solid fa-money-bill-1'></i>"; //Venta liquidada (Pago liquidado)
          }


        ?> {
            id: `<?php echo $event->id; ?>`,
            title: `<?php echo $patientNotified . ' ' . $statusName . ' ' . $saleStatus . '<br>' . $companyPatient . $userName . '<br>' . $category . '<br>' . $phone . '<br>' . $reason ?>`,
            start: `<?php echo $start; ?>`,
            end: `<?php echo $end; ?>`,
            backgroundColor: `<?php echo $color; ?>`,
            borderColor: `#9FE1E7`,
            textColor: `#000000`,
            extendedProps: { //info.event.extendedProps
              description: `<?php echo $category . '\n' . $phone . '\n' . $reason ?>`,
              medicId: `<?php echo $event->medic_id; ?>`,
              statusId: `<?php echo $event->status_id; ?>`,
              isPatientNotified: `<?php echo $event->is_patient_notified; ?>`,
              reservationType: `<?php echo $reservationType; ?>`,
              patientId: `<?php echo $event->patient_id; ?>`,
              patientName: `<?php echo $event->patient_name; ?>`,
              medicId: `<?php echo $event->medic_id; ?>`,
              dateAt: `<?php echo $event->date_at; ?>`,
              treatmentCode: `<?php echo $event->treatment_code; ?>`,
              treatmentId: `<?php echo $event->treatment_id; ?>`,
              patientPhone: `<?php echo $event->patient_phone; ?>`
            },
          },

        <?php endforeach; ?>
      ]
    });
    calendar.render();

    $('#selectedDate').on('change', function() {
      var date = $('#selectedDate').val();
      calendar.gotoDate(date);
    });

    function getEventById(id) {
      return calendar.getEventById(id)
    }

    if ("<?php echo $searchBranchOfficeId ?>" != 0) {
      getMedicsByBranchOffice();
    }

    function showReservationModal(id, info) {
      var statusId = (info.event.extendedProps.statusId);
      var isPatientNotified = (info.event.extendedProps.isPatientNotified);
      var reservationType = (info.event.extendedProps.reservationType);
      var patientId = (info.event.extendedProps.patientId);
      var patientName = (info.event.extendedProps.patientName);
      var medicId = (info.event.extendedProps.medicId);
      var dateAt = (info.event.extendedProps.dateAt);
      var treatmentCode = (info.event.extendedProps.treatmentCode);
      var treatmentId = (info.event.extendedProps.treatmentId);
      var patientPhone = (info.event.extendedProps.patientPhone);

      $("#modalReservationId").val(id);
      $("#modalReservation").modal("show");
      $("#patientNameReservation").val(patientName);
      $("#patientPhoneReservation").val(patientPhone);
      $("#statusReservation").val(statusId);

      if (isPatientNotified == 1) {
        $('#patientNotified').prop('checked', true);
      } else {
        $('#patientNotified').prop('checked', false);
      }

      let linkNewSale = './?view=sales/new-details&reservationId=' + id + '&patientId=' + patientId + '&medicId=' + medicId + '&date=' + dateAt + '';
      let linkRescheduleReservation = './?view=reservations/new-patient&reservationId=' + id;
      let linkEditReservation = './?view=reservations/edit-patient&id=' + id;
      let linkDeleteReservation = 'deleteReservation(' + id + ')';
      let linkEditPatient = './?view=patients/edit&id=' + patientId;
      let linkDetailReservation = './?view=reservations/details&id=' + id;
      let linkPatientMedicalRecord = './?view=patients/medical-record&patientId=' + patientId;
      let linkPrintActualInterview = './?view=patients/actual-interview&id=' + patientId + '';

      $("#linkNewSale").attr("href", linkNewSale);
      $("#linkRescheduleReservation").attr("href", linkRescheduleReservation);
      $("#linkEditReservation").attr("href", linkEditReservation);
      $("#linkEditPatient").attr("href", linkEditPatient);
      $("#linkPatientMedicalRecord").attr("href", linkPatientMedicalRecord);
      $("#linkPrintActualInterview").attr("href", linkPrintActualInterview);
      $("#linkDetailReservation").attr("href", linkDetailReservation);

      let dateAtFormat = dateAt.substring(0, 10);
      //BLOQUEAR ACCIONES:
      //Bloquear acciones de la cita si la fecha es anterior para quienes no sean administradores
      $("#statusReservation").attr('disabled', false);
      $("#patientNotified").attr('disabled', false);
      $("#linkEditReservation").attr('disabled', false);
      $("#linkDetailReservation").attr('disabled', false);

      $("#linkDeleteReservation").attr('disabled', false);
      $('#linkDeleteReservation').removeAttr('onclick');
      $('#linkDeleteReservation').attr('onClick', linkDeleteReservation);

      $("#statusReservation option[value='4']").attr('disabled', false);
      if ("<?php echo $_SESSION['typeUser'] ?>" != "su") {
        if (Date.parse(dateAtFormat) < Date.parse(getActualDateYmd())) {
          $('#linkDeleteReservation').removeAttr('onclick');
          $("#statusReservation").attr('disabled', true);
          $("#patientNotified").attr('disabled', true);
          $("#linkEditReservation").attr("href", "");
          $("#linkEditReservation").attr('disabled', true);
          $("#linkDetailReservation").attr("href", "");
          $("#linkDetailReservation").attr('disabled', true);
        }
        if (Date.parse(dateAtFormat) <= Date.parse(getActualDateYmd())) {
          $("#linkDeleteReservation").attr('disabled', true);
        }
        if (Date.parse(dateAtFormat) > Date.parse(getActualDateYmd())) {
          $("#linkDetailReservation").attr('disabled', true);
        }
      }

      //$("#statusReservation option[value='4']").attr('disabled',false);
    }
  });

  function getMedicsByBranchOffice() {
    $("#searchMedicId").empty().append('<option value="0" selected>--TODOS--</option>');
    $.ajax({
      type: "GET",
      url: './?action=medics/get-by-branchoffice',
      dataType: "json",
      data: {
        branchOfficeId: $("#searchBranchOfficeId").val()
      },
      success: function(data) {
        $.each(data, function(key, medic) {
          $("#searchMedicId").append('<option value=' + medic.id + '>' + medic.name + '</option>');
        });
        if ("<?php echo $searchBranchOfficeId ?>" == $("#searchBranchOfficeId").val()) {
          let searchMedicId = "<?php echo $searchMedicId ?>";
          $("#searchMedicId").val(searchMedicId).change();
        }
      },
      error: function(data) {
        /*
        //Ocultar porque causa confusión en conexiones lentas
        /*Swal.fire(
          'Error',
          'Ha ocurrido un error al cargar los psicólogos de la sucursal, recarga la página.',
          'error'
        );*/
      }
    });
  }

  function updateReservationStatus() {
    let reservationId = $("#modalReservationId").val();
    let status = $("#statusReservation").val();
    $.ajax({
      url: "./?action=reservations/update-status-reservation", // json datasource
      type: "POST", // method, by default get
      data: {
        reservationId: reservationId,
        statusId: status
      },
      success: function(data) {
        Toast.fire({
          icon: 'success',
          title: 'Actualizado estatus de la cita.'
        });
      },
      error: function() { // error handling
        Toast.fire({
          icon: 'error',
          title: 'Error al actualizar el Estatus de la Cita.'
        });
      }
    });
  }

  function updatePatientNotified() {
    let reservationId = $("#modalReservationId").val();
    let value = 0;
    if ($('#patientNotified').prop('checked')) {
      value = 1;
    }

    $.ajax({
      url: "./?action=reservations/update-reservation-notified", // json datasource
      type: "POST", // method, by default get
      data: {
        reservationId: reservationId,
        value: value
      },
      success: function(data) {
        /*Toast.fire({
          icon: 'success',
          title: 'Actualizado recordatorio al paciente.'
        });*/
      },
      error: function() { // error handling
        Toast.fire({
          icon: 'error',
          title: 'Error al actualizar el recordatorio al paciente.'
        });
      }
    });
  }

  function deleteReservation(id) {
    Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No serás capaz de revertir esto!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Sí, Eliminar'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: "./?action=reservations/delete-reservation", // json datasource
          type: "POST", // method, by default get
          data: "id=" + id,
          success: function() {
            location.reload();
          },
          error: function() { // error handling
            Swal.fire(
              'Error',
              'La cita no se ha podido eliminar.',
              'error'
            );
          }
        });
      }
    })
  }
</script>

<body>
  <!-- /.box-body -->

  <!-- MODAL-->
  <div class="modal fade" id="modalReservation" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="GET" action="index.php">
          <div class="modal-header">
            <h4 class="modal-title">Detalles de cita</h4>
            <div class="pull-right">
              <a id="linkNewSale" target="_blank" href='' class='btn btn-primary btn-xs'><i class="fas fa-dollar-sign"></i> Realizar Venta</a>
              <a id="linkRescheduleReservation" target="_blank" href='' class='btn btn-default btn-xs'><i class="fas fa-calendar"></i> Reagendar</a>
              <a id="linkEditReservation" href='' class='btn btn-warning btn-xs'><i class="fas fa-pencil-alt"></i> Editar cita</a>
              <a id="linkPatientMedicalRecord" target="_blank" href='' class='btn btn-success btn-xs'><i class="fas fa-file-alt"></i> Expediente paciente</a>
              <a id="linkPrintActualInterview" href='' target="_blank" class='btn btn-default btn-xs'><i class="fas fa-print"></i> Entrevista actual</a>
              <button type="button" id="linkDeleteReservation" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i> Eliminar cita</button>
              <a id="linkEditPatient" href='' target="_blank" class='btn btn-info btn-xs'><i class="fas fa-pencil-alt"></i><i class="fas fa-user"></i> Editar paciente</a>
              <a id="linkDetailReservation" href='' target="_blank" class='btn btn-success btn-xs'><i class="fas fa-eye"></i> Visualizar detalles</a>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-8">
                <label for="inputEmail1" class="col-md-3 control-label">Paciente:</label>
                <input type="text" id="patientNameReservation" class="form-control" readonly>
              </div>
              <div class="col-md-4">
                <label for="inputEmail1" class="col-md-3 control-label">Teléfono:</label>
                <input type="text" id="patientPhoneReservation" class="form-control" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="inputEmail1" class="col-md-3 control-label">Asistencia:</label>
                <select id="statusReservation" class="form-control" onchange="updateReservationStatus()">
                  <?php foreach ($reservationStatus as $status) : ?>
                    <option value="<?php echo $status->id; ?>"><?php echo $status->name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4">
                <br>
                <label><input type="checkbox" id="patientNotified" value="1" onchange="updatePatientNotified()"> Recordatorio a paciente</label>
              </div>
            </div>
            <br>
            <!--<div class="row">
              <div class="col-md-12">
                <label class="bg-primary">Si colocas la cita CANCELADA el mismo día, se generará una venta por cancelación automáticamente.</label>
              </div>
            </div>-->
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <input type="hidden" name="view" value="home">
            <input type="hidden" name="searchBranchOfficeId" value="<?php echo $searchBranchOfficeId ?>">
            <input type="hidden" id="modalReservationId" value="0">
          </div>
        </form>
      </div>
    </div>

  </div>
  <!-- MODAL-->

  <div class="card">
    <div class="card-header" data-background-color="blue">
    </div>
    <div class="card-content table-responsive">
      <div class="row">
        <div class="col-md-6">
          <?php if ($filterCalendar) : ?>
            <div class="callout callout-default">
              <p><i class="fas fa-info-circle"></i> Estás filtrando el calendario por un rango de fechas. Haz clic <a class="bg-primary" href="./index.php?view=home">AQUÍ</a> para regresar al calendario predeterminado.</p>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-md-6">
          <?php if ($_SESSION['typeUser'] != "do") : ?>
            <a href="./index.php?view=reservations/new-patient" class="btn btn-primary btn-xs"><i class='fas fa-user-alt'></i> Nueva cita paciente </a>
            <a href="./index.php?view=reservations/new-medic" class="btn btn-info btn-xs"><i class='fa fa-user-md'></i> Nueva cita doctor</a>
            <?php if ($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "r" || $_SESSION['typeUser'] == "co") : ?>
              <!--<a href="./index.php?view=sales/new" class="btn btn-success btn-xs"><i class='fa fa-dollar-sign'></i> Realizar venta </a>-->
            <?php endif; ?>
            <?php if ($filterCalendar) : ?>
              <a href="./index.php?view=home" class="btn btn-default btn-xs"><i class="far fa-calendar-alt"></i> Calendario predeterminado</a>
            <?php else : ?>
              <a href="./index.php?view=home&filter=true" class="btn btn-default btn-xs"><i class="far fa-calendar-alt"></i> Filtrar calendario</a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <?php if (!$filterCalendar) : ?>
        <div class="row">
          <form class="form-horizontal" method="GET" enctype="multipart/form-data" action="index.php" role="form">
            <?php if ($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "co") : ?>
              <div class="col-md-6">
                <label class="control-label">Sucursal</label>
                <div class="input-group">
                  <select id="searchBranchOfficeId" name="searchBranchOfficeId" class="form-control" required onchange="getMedicsByBranchOffice()">
                    <option value="0" disabled <?php echo ($searchBranchOfficeId == 0) ? "selected" : "" ?>>-- SELECCIONE --</option>
                    <?php foreach ($branchOffices as $branchOffice) : ?>
                      <option value="<?php echo $branchOffice->id; ?>" <?php echo ($searchBranchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i> Buscar</button>
                    <input type="hidden" name="view" value="home">
                  </div>
                </div>
              </div>
            <?php else : ?>
              <input type="hidden" id="searchBranchOfficeId" name="searchBranchOfficeId" value="<?php echo $_SESSION['branchOfficeId'] ?>" required>
            <?php endif; ?>
            <?php if ($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "r" || $_SESSION['typeUser'] == "co") : ?>
              <div class="col-md-6">
                <label class="control-label">Psicólogo</label>
                <div class="input-group">
                  <select id="searchMedicId" name="searchMedicId" class="form-control" required>
                    <option value="0">-- TODOS --</option>
                  </select>
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i> Buscar</button>
                    <input type="hidden" name="view" value="home">
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </form>
        </div>
      <?php endif; ?>
      <br>
      <div class="row">
        <?php if (!$filterCalendar) : ?>
          <div class="col-lg-6">
            <label class="control-label">Seleccionar fecha a mostrar</label>
            <input id="selectedDate" type="date" class="form-control" min="<?php echo $startDate ?>" value="<?php echo $defaultDate ?>" name="selectedDate">
          </div>
        <?php else : ?>
          <form class="form-horizontal" role="form">
            <?php if ($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "co") : ?>
              <div class="col-lg-3">
                <label class="control-label">Sucursal</label>
                <select id="searchBranchOfficeId" name="searchBranchOfficeId" class="form-control" required onchange="getMedicsByBranchOffice()">
                  <option value="0" disabled <?php echo ($searchBranchOfficeId == 0) ? "selected" : "" ?>>-- SELECCIONE --</option>
                  <?php foreach ($branchOffices as $branchOffice) : ?>
                    <option value="<?php echo $branchOffice->id; ?>" <?php echo ($searchBranchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php else : ?>
              <input type="hidden" id="searchBranchOfficeId" name="searchBranchOfficeId" value="<?php echo $_SESSION['branchOfficeId'] ?>" required>
            <?php endif; ?>
            <?php if ($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "r" || $_SESSION['typeUser'] == "co") : ?>
              <div class="col-lg-3">
                <label class="control-label">Psicólogo</label>
                <select id="searchMedicId" name="searchMedicId" class="form-control" required>
                  <option value="0">-- TODOS --</option>
                </select>
              </div>
            <?php endif; ?>
            <div class="col-lg-2">
              <label class="control-label">Mostrar desde</label>
              <input id="startDate" type="date" class="form-control" value="<?php echo $startDate ?>" name="startDate" required>
            </div>
            <div class="col-lg-2">
              <label class="control-label">Mostrar hasta</label>
              <input id="endDate" type="date" class="form-control" value="<?php echo $endDate ?>" name="endDate" required>
            </div>
            <div class="col-lg-2">
              <br>
              <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i> Buscar fechas</button>
              <input type="hidden" name="view" value="home">
              <input type="hidden" name="filter" value="true">
            </div>
          </form>
        <?php endif; ?>
        <?php if ($_SESSION['typeUser'] != "do") : ?>
          <form class="form-horizontal" role="form">
            <div class="col-md-6">
              <label class="control-label">Buscar lista citas por psicólogo/paciente</label>
              <div class="input-group">
                <input type="text" name="search" id="search" value="" class="form-control" placeholder="Escribe el nombre" autocomplete="off" required>
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fas fa-search"></i> Buscar</button>
                  <input type="hidden" name="view" value="reservations/search-history">
                </div>
              </div>
            </div>
          </form>
        <?php endif; ?>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="callout callout-default">
            <p><i class="fas fa-info-circle"></i><b> ÍCONOS DE CITAS:</b> Paciente empresa(<i class='fa-solid fa-building-user'></i>) Sí recordada(<i class='fa-solid fa-phone'></i>) No recordada(<i class='fa-solid fa-phone-slash'></i>)<br>Sí asistió(<i class='fa-solid fa-check'></i>) No asistió(<i class='fa-solid fa-xmark'></i>) Cancelada(<i class='fa-solid fa-ban'></i>) Pago liquidado(<i class="fa-solid fa-money-bill-1"></i>) Pago pendiente por liquidar(<i class="fa-regular fa-clock"></i><i class="fa-solid fa-dollar-sign"></i>)
              <br>Pago no generado(<i class="fa-solid fa-triangle-exclamation"></i><i class="fa-solid fa-dollar-sign"></i>)
            </p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div id="calendar"></div>
        </div>
      </div>
    </div>
  </div>
</body>