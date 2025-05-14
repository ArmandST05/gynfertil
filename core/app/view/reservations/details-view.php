<?php
$user = UserData::getLoggedIn();
$userType = $user->user_type;

$reservation = ReservationData::getById($_GET["id"]);
$reservationId = $reservation->id;
$reservationStatus = ReservationStatusData::getAll();

if (!isset($reservation)) {
    echo "<script> 
          alert('La cita seleccionada no existe');
          window.location.href = './?view=home';
        </script>";
}
if (!$reservation->patient_id) {
    //Redireccionar agenda del doctor
    echo "<script> 
        window.location.href = './?view=reservations/edit-medic&id=" . $_GET["id"] . "';
    </script>";
}

$vitalSigns = ExplorationExamData::getAllByTypeReservation($reservationId, 1);
$vitalSignsArray = array_chunk($vitalSigns, 2);
$physicalExams = ExplorationExamData::getAllByTypeReservation($reservationId, 2);
$topographicalExams = ExplorationExamData::getAllByTypeReservation($reservationId, 3);
$reservationDiagnostics = DiagnosticData::getAllByReservationId($reservationId);
$reservationMedicines = MedicineData::getAllByReservationId($reservationId);
$reservationTreatment = TreatmentData::getPatientTreatmentByDates($reservation->patient_id, substr($reservation->date_at, 0, 10), substr($reservation->date_at_final, 0, 10));

$reservationNumberData = ReservationData::getTotalReservationsByPatientDates($reservation->patient_id, substr($reservationTreatment->start_date, 0, 10) . " 00:00:00", substr($reservation->date_at_final, 0, 10) . " 23:59:59", 2);
$reservationNumber = ($reservationNumberData && $reservationNumberData->total > 0) ? $reservationNumberData->total : 1;
$patient = $reservation->getPatient();
$files = PatientData::getAllFilesByPatientReservation($patient->id, $reservationId);
$months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
$reservationDateFormat = substr($reservation->date_at, 8, 2) . "/" . $months[substr($reservation->date_at, 5, 2)] . "/" . substr($reservation->date_at, 0, 4);

$inputStatus = ($userType == "su" || (substr($reservation->date_at, 0, 10) >= date("Y-m-d"))) ? "" : "disabled";
?>
<style>
    .select2-container {
        z-index: 99999999999999;
    }
</style>
<div class="row">
    <input type="hidden" id="reservationId" value="<?php echo $reservationId ?>">
    <input type="hidden" id="patientId" value="<?php echo $patient->id ?>">
    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Datos del Paciente</h3>
                <div class="pull-right">
                    <a target="_blank" href='./?view=patients/medical-record&patientId=<?php echo $reservation->patient_id ?>' class='btn btn-default btn-xs'><i class="fas fa-file-alt"></i> Expediente del paciente</a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
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
                        <b>Referido: </b><?php echo $patient->referred_by ?>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Datos de la Cita</h3>
                <div class="pull-right">
                    <?php if ($userType != "do") : ?>
                        <a href='./?view=sales/new-details&reservationId=<?php echo $_GET["id"] ?>&patientId=<?php echo $reservation->patient_id ?>&medicId=<?php echo $reservation->medic_id; ?>&date=<?php echo $reservation->date_at; ?>' class='btn btn-primary btn-xs'><i class="fas fa-dollar-sign"></i> Realizar Venta</a>
                    <?php endif; ?>
                    <?php if ($userType == "su" || ($userType != "do" && substr($reservation->date_at, 0, 10) >= date("Y-m-d"))) : ?>
                        <a href='./?view=reservations/new-patient&reservationId=<?php echo $_GET["id"] ?>' class='btn btn-default btn-xs'><i class="fas fa-calendar"></i> Reagendar</a>
                        <a href='./?view=reservations/edit-patient&id=<?php echo $reservation->id ?>' class='btn btn-warning btn-xs'><i class="fas fa-pencil-alt"></i> Editar</a>
                        <!--<button id="btnCancelReservation" class='btn btn-secondary btn-xs'><i class="fas fa-ban"></i>Cancelar</button>-->
                        <button id="btnDeleteReservation" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i> Eliminar</button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <b>No. Sesión: </b><?php echo $reservationNumber ?>
                    </div>
                    <div class="col-md-3">
                        <b>Fecha: </b><?php echo $reservationDateFormat; ?>
                    </div>
                    <div class="col-md-3">
                        <b>Hora: </b><?php echo $reservation->getStartTime() . " - " . $reservation->getEndTime()  ?>
                    </div>
                    <div class="col-md-3">
                        <label for="inputEmail1">Estatus:</label>
                        <label id="reservation_status_name"><?php echo $reservation->getStatus()->name ?></label>
                        <input type="hidden" id="status_id" value="<?php echo $reservation->status_id ?>"></input>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <b>Psicólogo: </b><?php echo $reservationTreatment->getMedic()->name ?>
                    </div>
                    <div class="col-md-3">
                        <b>Cédula: </b><?php echo $reservationTreatment->getMedic()->professional_license ?>
                    </div>
                    <div class="col-md-3">
                        <b>Atendido por: </b><?php echo $reservation->getMedic()->name ?>
                    </div>
                    <div class="col-md-3">
                        <b>Cédula: </b><?php echo $reservation->getMedic()->professional_license ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <b>Área: </b><?php echo $reservation->getArea()->name  ?>
                    </div>
                    <div class="col-md-3">
                        <b>Categoría: </b><?php echo $reservation->getCategory()->name; ?>
                    </div>
                    <div class="col-md-3">
                        <b>Consultorio: </b><?php echo $reservation->getLaboratory()->name ?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <label for="inputEmail1">Tema o Motivo de la Consulta:</label>
                        <textarea class="form-control" id="reason" name="reason" placeholder="Motivo de la Consulta" required <?php echo $inputStatus; ?>><?php echo $reservation->reason; ?></textarea>
                    </div>
                </div>
                <?php if ($userType != "do") : ?>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="inputEmail1" class="col-md-3 control-label">Asistencia:</label>
                            <select name="statusReservation" id="statusReservation" class="form-control" onchange="selectReservationStatus()" autofocus required <?php echo $inputStatus; ?>>
                                <?php foreach ($reservationStatus as $status) : ?>
                                    <option value="<?php echo $status->id; ?>" <?php echo ($reservation->status_id == $status->id) ? "selected" : "" ?>><?php echo $status->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if ($userType != "do") : ?>
                            <div class="col-md-4">
                                <br>
                                <label><input type="checkbox" name="patientNotified" id="patientNotified" value="1" onchange="selectPatientNotified()" <?php echo ($reservation->is_patient_notified == 1) ? "checked" : "" ?>> Recordatorio a paciente</label>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <br>
                <?php if ($reservation->status_id != 2 && ($userType == "su" || $userType == "do")) : ?>
                    <div class="row">
                        <div class="col-md-2 pull-right">
                            <button id="btnStartConsultation" onclick="updateReservationStatus(2)" class='btn btn-primary btn-xs'>Comenzar Consulta <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($userType == "su" || $userType == "do") : ?>
            <div id="medicalConsultationDetails">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(S) Subjetivo - Observaciones del paciente</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <textarea class="form-control" id="patientObservations" name="patientObservations" placeholder="Observaciones del paciente"><?php echo $reservation->patient_observations; ?></textarea>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(O) Objetivo - Signos Vitales</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php foreach ($vitalSignsArray as $vitalSigns) : ?>
                            <div class="row">
                                <?php foreach ($vitalSigns as $vitalSign) : ?>
                                    <div class="col-md-3">
                                        <?php echo $vitalSign->name ?>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" id="explorationExam<?php echo $vitalSign->id ?>" value="<?php echo $vitalSign->value ?>" placeholder="<?php echo $vitalSign->name ?>" onkeyup="updateVitalSign('<?php echo $vitalSign->id ?>')" autofocus>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="box box-details box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(O) Objetivo - Examen Físico</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <select name="physicalExam" id="physicalExam" class="form-control" id="combobox" autofocus required onchange="addPhysicalExam()">
                                    <option value="" disabled selected>-- SELECCIONE -- </option>
                                    <?php foreach ($physicalExams as $physicalExam) : ?>
                                        <option value="<?php echo $physicalExam->id; ?>"><?php echo $physicalExam->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row" id="divPhysicalExamsDetail">

                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(O) Objetivo - Exploración Topográfica</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <select name="topographicalExam" id="topographicalExam" class="form-control" id="combobox" autofocus required onchange="addTopographicalExam()">
                                    <option value="" disabled selected>-- SELECCIONE -- </option>
                                    <?php foreach ($topographicalExams as $topographicalExam) : ?>
                                        <option value="<?php echo $topographicalExam->id; ?>"><?php echo $topographicalExam->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row" id="divTopographicalExamsDetail">

                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(O) Objetivo - Archivos y Exámenes de Laboratorio</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-2 pull-right">
                                <button class="btn btn-sm btn-primary" onclick="addFile()"><i class="fas fa-upload"></i> Subir archivo</button>
                            </div>
                        </div>
                        <br>
                        <div class="row" id="divFiles">
                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(A) Análisis - Diagnósticos</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="selectDiagnostics" id="selectDiagnostics" class="form-control" required onchange="selectDiagnostic(this.value)">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <tbody id="diagnosticsTable">
                                        <?php foreach ($reservationDiagnostics as $diagnostic) : ?>
                                            <tr id="d<?php echo $diagnostic->reservation_detail_id ?>">
                                                <td><?php echo $diagnostic->catalog_key . " | " . $diagnostic->name ?></td>
                                                <td>
                                                    <input class="form-control" type="text" id="<?php echo 'dv' . $diagnostic->reservation_detail_id ?>" value="<?php echo $diagnostic->value; ?>" onkeyup="updateDiagnostic('<?php echo $diagnostic->reservation_detail_id ?>')"></input>
                                                </td>
                                                <td><button class='btn btn-danger btn-xs' onclick="deleteDiagnostic('<?php echo $diagnostic->reservation_detail_id ?>')"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(A) Análisis - Observaciones de diagnósticos</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <textarea class="form-control" id="diagnosticObservations" name="diagnosticObservations" placeholder="Observaciones de diagnósticos"><?php echo $reservation->diagnostic_observations; ?></textarea>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(A) Plan - Observaciones de tratamiento o plan</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <textarea class="form-control" id="treatmentObservations" name="treatmentObservations" placeholder="Observaciones de tratamiento o plan"><?php echo $reservation->treatment_observations; ?></textarea>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">(P) Plan - Receta de Medicamentos</h3>
                        <div class="box-tools pull-right">
                            <a href='./?view=reservations/report-prescription&id=<?php echo $reservationId ?>' target="__blank" class='btn btn-primary btn-xs'><i class="fas fa-file-medical"></i> Receta Médica</a>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="selectMedicines" id="selectMedicines" class="form-control" required onchange="selectMedicine(this.value)">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <th width="250px;">Medicamento</th>
                                        <th width="120px;">Tomar</th>
                                        <th width="200px;">Frecuencia</th>
                                        <th width="120px;">Duración</th>
                                        <th>Notas</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="medicinesTable">
                                        <?php foreach ($reservationMedicines as $medicine) : ?>
                                            <tr id="m<?php echo $medicine->reservation_detail_id ?>">
                                                <td><label><?php echo $medicine->generic_name . "|" . $medicine->pharmaceutical_form ?></label><?php echo " <br>" . $medicine->concentration . "<br>" . $medicine->presentation ?></td>
                                                <td>
                                                    <input class="form-control" type="text" id="<?php echo 'mquantity' . $medicine->reservation_detail_id ?>" value="<?php echo $medicine->quantity; ?>" onkeyup="updateMedicine('<?php echo $medicine->reservation_detail_id; ?>','quantity')"></input>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" id="<?php echo 'mfrequency' . $medicine->reservation_detail_id ?>" value="<?php echo $medicine->frequency; ?>" onkeyup="updateMedicine('<?php echo $medicine->reservation_detail_id; ?>','frequency')"></input>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" id="<?php echo 'mduration' . $medicine->reservation_detail_id ?>" value="<?php echo $medicine->duration; ?>" onkeyup="updateMedicine('<?php echo $medicine->reservation_detail_id; ?>','duration')"></input>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" id="<?php echo 'mdescription' . $medicine->reservation_detail_id ?>" value="<?php echo $medicine->description; ?>" onkeyup="updateMedicine('<?php echo $medicine->reservation_detail_id; ?>','description')"></input>
                                                </td>
                                                <td><button class='btn btn-danger btn-xs' onclick="deleteMedicine('<?php echo $medicine->reservation_detail_id ?>')"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000
    });

    //var reasonNicEditor = new nicEditor().panelInstance('reason');
    var patientObservationsNicEditor = new nicEditor().panelInstance('patientObservations');
    var diagnosticObservationsNicEditor = new nicEditor().panelInstance('diagnosticObservations');
    var treatmentObservationsNicEditor = new nicEditor().panelInstance('treatmentObservations');

    $(document).ready(function() {

        $("#physicalExam").select2({});
        $("#topographicalExam").select2({});

        $('#selectDiagnostics').select2({
            placeholder: "Escribe el nombre o clave del diagnóstico",
            minimumInputLength: 3,
            ajax: {
                url: "./?action=diagnostics/get-search", // json datasource
                type: 'GET',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    }
                }
            }
        });

        $('#selectMedicines').select2({
            language: "es",
            placeholder: "Escribe el nombre del medicamento",
            minimumInputLength: 3,
            ajax: {
                url: "./?action=medicines/get-search", // json datasource
                type: 'GET',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    }
                }
            }
        });

        validateConsultationDetails();
        showExplorationExams(2);
        showExplorationExams(3);

        //Inicializar motivo como editor personalizado
        $("#reason").keyup(function() {
            updateReason();
        });

        document.getElementById('patientObservations').parentElement.onkeydown = function() {
            updatePatientObservations();
        }
        document.getElementById('diagnosticObservations').parentElement.onkeydown = function() {
            updateDiagnosticObservations();
        }
        document.getElementById('treatmentObservations').parentElement.onkeydown = function() {
            updateTreatmentObservations();
        }

        showFiles(); //Mostrar archivos subidos
    });

    function validateConsultationDetails() {
        //Muestra y oculta los detalles de la consulta
        //Añade el color al estatus de la cita
        $("#reservation_status_name").removeClass(); //Borra todas las clases

        if ($("#status_id").val() == "2") {
            //Asistió paciente
            $("#medicalConsultationDetails").show();
            $("#reservation_status_name").addClass("btn-primary");
        } else if ($("#status_id").val() == "3") {
            //Cancelado
            $("#reservation_status_name").addClass("btn-danger");
            $("#medicalConsultationDetails").hide();
        } else {
            $("#medicalConsultationDetails").hide();
        }
    }

    /*-----------------RESERVATION OPTIONS-------------*/
    $("#btnDeleteReservation").click(function() {
        Swal.fire({
            title: '¿Estás seguro de eliminar la cita?',
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
                    data: "id=" + "<?php echo $reservationId; ?>",
                    success: function() {
                        window.location = 'index.php?view=home';
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
    });

    $("#btnCancelReservation").click(function() {
        Swal.fire({
            title: '¿Estás seguro de cancelar la cita?',
            text: "Al cancelar la cita indicas que el paciente no asistó.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, Cancelar'
        }).then((result) => {
            if (result.value) {
                updateReservationStatus(3);
            }
        })
    });

    /*---------AÑADIR ARCHIVOS----------*/
    function addFile() {
        Swal.fire({
            title: 'Archivo',
            input: 'file',
            inputAttributes: {
                'accept': '/*',
                'aria-label': 'Selecciona el archivo',
            },
            onBeforeOpen: () => {
                $(".swal2-file").change(function() {
                    var reader = new FileReader();
                    reader.readAsDataURL(this.files[0]);
                });
            },

            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                if (!value) {
                    return '¡Selecciona un archivo!'
                }
            },
            preConfirm: (value) => {
                var formData = new FormData();
                formData.append('patientId', $("#patientId").val());
                formData.append('reservationId', $("#reservationId").val());

                var file = $('.swal2-file')[0].files[0];
                formData.append("files", file);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "./?action=patient-files/add",
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    error: function() {
                        Swal.fire(
                            '¡Oops!',
                            'El archivo no se ha podido guardar.',
                            'error'
                        )
                    },
                    success: function(data) {
                        showFiles();
                    }
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    }


    function deleteFile(id) {
        $.ajax({
            url: "./?action=patient-files/delete-reservation",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                Toast.fire({
                    icon: 'success',
                    title: 'Archivo eliminado.'
                });
                showFiles();
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al eliminar el archivo.'
                });
            }
        });
    }

    function showFiles() {
        $.ajax({
            url: "./?action=patient-files/get-reservation",
            type: "GET",
            data: {
                reservationId: $("#reservationId").val(),
                patientId: $("#patientId").val(),
            },
            success: function(data) {
                $("#divFiles div").remove()
                $("#divFiles").append(data);
            }
        });
    }

    /*---------------RESERVATION REASON----------------- */

    function updateReason() {
        var reason = $("#reason").val();
        $.ajax({
            url: "./?action=reservations/update-reason-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationId: "<?php echo $reservationId; ?>",
                reason: reason
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Actualizado Motivo de la Cita.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar el motivo de la cita.'
                });
            }
        });
    }

    /*---------------RESERVATION PATIENT OBSERVATIONS----------------- */

    function updatePatientObservations() {
        let patientObservations = nicEditors.findEditor('patientObservations').getContent();
        $.ajax({
            url: "./?action=reservations/update-patient-observations", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationId: "<?php echo $reservationId; ?>",
                patientObservations: patientObservations
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Actualizadas observaciones del paciente.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar las observaciones del paciente.'
                });
            }
        });
    }

    /*---------------RESERVATION DIAGNOSTIC OBSERVATIONS----------------- */

    function updateDiagnosticObservations() {
        let diagnosticObservations = nicEditors.findEditor('diagnosticObservations').getContent();
        $.ajax({
            url: "./?action=reservations/update-diagnostic-observations", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationId: "<?php echo $reservationId; ?>",
                diagnosticObservations: diagnosticObservations
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Actualizadas observaciones de diagnósticos.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar las observaciones de diagnósticos.'
                });
            }
        });
    }

    /*---------------RESERVATION TREATMENT OBSERVATIONS----------------- */

    function updateTreatmentObservations() {
        let treatmentObservations = nicEditors.findEditor('treatmentObservations').getContent();
        $.ajax({
            url: "./?action=reservations/update-treatment-observations", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationId: "<?php echo $reservationId; ?>",
                treatmentObservations: treatmentObservations
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Actualizadas observaciones de tratamiento/plan.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar las observaciones de tratamiento/plan.'
                });
            }
        });
    }

    /*--------------VITAL SIGNS---------------- */
    function updateVitalSign(id) {
        //Se creó una función sólo para los signos vitales
        let value = $('#explorationExam' + id).val();

        $.ajax({
            url: "./?action=exploration-exams/update-vital-sign-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                explorationExamId: id,
                reservationId: $("#reservationId").val(),
                value: value
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Información Actualizada.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar la información.'
                });
            }
        });
    }


    /*--------------EXPLORATION EXAMS----------*/
    function showExplorationExams(explorationExamTypeId) {
        $.ajax({
            url: "./?action=exploration-exams/get-reservation",
            type: "GET",
            data: {
                reservationId: $("#reservationId").val(),
                explorationExamTypeId: explorationExamTypeId,
            },
            success: function(data) {
                if (explorationExamTypeId == 2) {
                    $("#divPhysicalExamsDetail div").remove()
                    $("#divPhysicalExamsDetail").append(data);
                } else if (explorationExamTypeId == 3) {
                    $("#divTopographicalExamsDetail div").remove()
                    $("#divTopographicalExamsDetail").append(data);
                }
            }
        });
    }

    function updateExplorationExam(id) {
        //let value = nicEditors.findEditor('explorationExam' + id).getContent();
        let value = $('#explorationExam' + id).val();

        $.ajax({
            url: "./?action=exploration-exams/update-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                id: id,
                value: value
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Información Actualizada.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar la información.'
                });
            }
        });
    }

    function deleteExplorationExam(id, explorationExamTypeId) {
        $.ajax({
            url: "./?action=exploration-exams/delete-reservation",
            type: "POST",
            data: {
                id: id
            },
            success: function(data) {
                Toast.fire({
                    icon: 'success',
                    title: 'Información eliminada.'
                });
                showExplorationExams(explorationExamTypeId);
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al eliminar la información.'
                });
            }
        });
    }

    function addPhysicalExam() {
        $.ajax({
            url: "./?action=exploration-exams/add-reservation",
            type: "POST",
            data: {
                reservationId: $("#reservationId").val(),
                explorationExamId: $("#physicalExam").val(),
            },
            success: function(data) {
                Toast.fire({
                    icon: 'success',
                    title: 'Examen físico agregado.'
                });
                showExplorationExams(2);
            }
        });
    }

    function addTopographicalExam() {
        $.ajax({
            url: "./?action=exploration-exams/add-reservation",
            type: "POST",
            data: {
                reservationId: $("#reservationId").val(),
                explorationExamId: $("#topographicalExam").val(),
            },
            success: function(data) {
                Toast.fire({
                    icon: 'success',
                    title: 'Examen topográfico agregado.'
                });
                showExplorationExams(3);
            }
        });
    }
    /*--------------EXPLORATION EXAMS----------*/

    function updateReservationStatus(status) {
        $.ajax({
            url: "./?action=reservations/update-status-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationId: "<?php echo $reservationId; ?>",
                statusId: status
            },
            success: function(data) {
                Toast.fire({
                    icon: 'success',
                    title: 'Actualizado Estatus de la Cita.'
                });
                let datos = JSON.parse(data);

                $("#reservation_status_name").text(datos[
                    'name']); //Actualizar nombre del estatus de la reservación
                $("#status_id").val(datos['id']); //Actualizar estatus id de la reservación
                validateConsultationDetails();
                if (status == 2) {
                    $("#btnStartConsultation").hide();
                }
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar el Estatus de la Cita.'
                });
            }
        });
    }

    /*--------------DIAGNOSTICS----------*/
    function selectDiagnostic(diagnosticData) {
        let diagnostic = diagnosticData.split("|");
        let diagnosticId = diagnostic[0];
        let diagnosticCatalogKey = diagnostic[1];
        let diagnosticName = diagnostic[2];

        $.ajax({
            url: "./?action=reservations/add-diagnostic-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationId: "<?php echo $reservationId; ?>",
                diagnosticId: diagnosticId,
            },
            success: function(data) {
                Toast.fire({
                    icon: 'success',
                    title: 'Diagnóstico agregado.'
                });
                let trTable = "<tr id='d" + data + "'><td>" + diagnosticCatalogKey + " | " + diagnosticName + "</td>";
                trTable += "<td><input class='form-control' type='text' id='dv" + data + "' value='' onkeyup='updateDiagnostic(" + data + ")'></input></td>";
                trTable += "<td><button class='btn btn-danger btn-xs' onclick='deleteDiagnostic(" + data + ")'><i class='fas fa-trash'></i></button></td></tr>";

                $("#diagnosticsTable").append(trTable);

            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al añadir el diagnóstico.'
                });
            }
        });
    }

    function updateDiagnostic(id) {
        let diagnosticValue = $('#dv' + id).val();
        $.ajax({
            url: "./?action=reservations/update-diagnostic-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                id: id,
                value: diagnosticValue
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Información Actualizada.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar la información.'
                });
            }
        });
    }

    function deleteDiagnostic(reservationDiagnosticId) {
        $.ajax({
            url: "./?action=reservations/delete-diagnostic-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationDiagnosticId: reservationDiagnosticId
            },
            success: function() {
                $("#d" + reservationDiagnosticId).remove();
                Toast.fire({
                    icon: 'success',
                    title: 'Diagnóstico eliminado.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al eliminar el diagnóstico.'
                });
            }
        });
    }

    /*--------------MEDICINES----------*/
    function selectMedicine(medicineData) {
        let medicine = medicineData.split("|");
        let medicineId = medicine[0];
        let medicineGenericName = medicine[1];
        let medicinePharmaceuticalForm = medicine[2];
        let medicineConcentration = medicine[3];
        let medicinePresentation = medicine[4];

        $.ajax({
            url: "./?action=reservations/add-medicine-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationId: "<?php echo $reservationId; ?>",
                medicineId: medicineId,
            },
            success: function(data) {
                Toast.fire({
                    icon: 'success',
                    title: 'Medicamento agregado.'
                });
                let trTable = "<tr id='m" + data + "'><td><label>" + medicineGenericName + "|" + medicinePharmaceuticalForm + "</label><br>" + medicineConcentration + "<br>" + medicinePresentation + "</td>";
                trTable += '<td><input class="form-control" type="text" id="mquantity' + data + '" onkeyup="updateMedicine(' + data + ',`quantity`)"></input></td>';
                trTable += '<td><input class="form-control" type="text" id="mfrequency' + data + '" onkeyup="updateMedicine(' + data + ',`frequency`)"></input></td>';
                trTable += '<td><input class="form-control" type="text" id="mduration' + data + '" onkeyup="updateMedicine(' + data + ',`duration`)"></input></td>';
                trTable += '<td><input class="form-control" type="text" id="mdescription' + data + '" onkeyup="updateMedicine(' + data + ',`description`)"></input></td>';
                trTable += "<td><button class='btn btn-danger btn-xs' onclick='deleteMedicine(" + data + ")'><i class='fas fa-trash'></i></button></td></tr>";

                $("#medicinesTable").append(trTable);

            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al añadir el medicamento.'
                });
            }
        });
    }

    function updateMedicine(id, column) {
        let medicineValue = $('#m' + column + id).val();
        $.ajax({
            url: "./?action=reservations/update-medicine-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                id: id,
                column: column,
                value: medicineValue
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Información Actualizada.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar la información.'
                });
            }
        });
    }

    function deleteMedicine(reservationMedicineId) {
        $.ajax({
            url: "./?action=reservations/delete-medicine-reservation", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationMedicineId: reservationMedicineId
            },
            success: function() {
                $("#m" + reservationMedicineId).remove();
                Toast.fire({
                    icon: 'success',
                    title: 'Medicamento eliminado.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al eliminar el medicamento.'
                });
            }
        });
    }


    /*PSYCHOLOGY PROCEDURES */
    function selectReservationStatus() {
        updateReservationStatus($("#statusReservation").val());
    }

    function selectPatientNotified() {
        let value = 0;
        if ($('#patientNotified').prop('checked')) {
            value = 1;
        }
        updatePatientNotified(value);
    }

    function updatePatientNotified(value) {
        $.ajax({
            url: "./?action=reservations/update-reservation-notified", // json datasource
            type: "POST", // method, by default get
            data: {
                reservationId: "<?php echo $reservationId; ?>",
                value: value
            },
            success: function(data) {
                Toast.fire({
                    icon: 'success',
                    title: 'Actualizado recordatorio al paciente.'
                });
            },
            error: function() { // error handling
                Toast.fire({
                    icon: 'error',
                    title: 'Error al actualizar el recordatorio al paciente.'
                });
            }
        });
    }
</script>