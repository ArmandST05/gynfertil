<?php
$embryologyProcedure = PatientCategoryData::getById($_GET["treatmentId"]);
$embryologyProcedureId = $_GET["treatmentId"];
$patient = $embryologyProcedure->getPatient();
$procedureDetails = EmbryologyProcedureData::getDetailsByProcedure($embryologyProcedure->patient_treatment_id, $embryologyProcedureId);

//OBTENER IMÁGENES DE ÓVULOS DE LA TABLA DE DETALLES
$ovuleImages = EmbryologyProcedureData::getFilesByTreatmentSectionId($embryologyProcedureId, 1);

//OBTENER DATOS DEL FORMATO/TABLA SECCIONES Y CONTENIDO DE TABLA DE ÓVULOS
$sections = PatientOvuleData::getAllSectionsByTreatment($embryologyProcedure->patient_treatment_id, 1);
$sectionDetails = PatientOvuleData::getAllSectionDetailsByTreatment($embryologyProcedure->patient_treatment_id, 1);
$procedureOvules = PatientOvuleData::getOvulesByProcedureSectionId($embryologyProcedureId, 1);

//Diagnósticos de tratamientos
$treatmentDiagnostics = TreatmentDiagnosticData::getByTreatmentString($_GET["treatmentId"]);

//DATOS OFICIALES DEL PACIENTE Y SU PAREJA

$patientOfficialData = $patient->getPatientOfficialData();
//Obtener los datos de la pareja asignada en ese procedimiento
$partner = $embryologyProcedure->getPartnerData();

$transferDetail = EmbryologyProcedureTransferData::getByTreatmentId($_GET["treatmentId"]);
if (!$transferDetail) $transferDetail = new EmbryologyProcedureTransferData();

$embryoVitrificationDetail = EmbryologyProcedureVitrificationData::getByTreatmentId($_GET["treatmentId"]);
if (!$embryoVitrificationDetail) $embryoVitrificationDetail = new EmbryologyProcedureVitrificationData();

//Número de ciclos por tratamiento que ha realizado
$actualCycleData = PatientCategoryData::getTotalPatientTreatmentsByType($embryologyProcedure->patient_id, $embryologyProcedure->patient_treatment_id, $embryologyProcedure->id);
$actualCycle = $actualCycleData->total;

//Obtener los datos de los procedimientos de donde se obtuvo el SEMEN
$originAndrologyProcedures = AndrologyProcedureData::getOriginSemenProceduresByTreatmentId($_GET["treatmentId"]);

$medics = MedicData::getAll();

$isReadonly = "readonly";
if ($_SESSION['typeUser'] == "do") {
    $medic = MedicData::getByUserId($_SESSION["user_id"]);
    if ($medic->category_id == 8) {
        $isReadonly = "";
    }
}

?>
<link rel="stylesheet" href="plugins/datatables/jquery.dataTables.min.css" />
<!-- ACCIONES PARA CATEGORÍAS/TRATAMIENTOS/EMBARAZO-->
<script src="core/app/view/patientCategoryScript.js" type="text/javascript"></script>
<div class="row">
    <div class="col-md-12">
        <h1><?php echo $embryologyProcedure->treatment_code ?></h1>
        <!-- /.box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Datos del Paciente</h3>
                <div class="pull-right">
                    <?php
                    $statusName = $embryologyProcedure->getTreatmentStatus()->name;
                    $statusClass = "btn-primary";
                    if ($embryologyProcedure->treatment_status_id == 4) {
                        if (isset($embryologyProcedure->pregnancy_test_date)) { //Si hubo transferencia
                            if ($embryologyProcedure->pregnancy_test_result == 1) {
                                $statusName .= " - SE EMBARAZÓ";
                            } else {
                                $statusName .= " - NO SE EMBARAZÓ";
                                $statusClass = "btn-danger";
                            }
                        } else { //No hubo transferencia
                            $statusName .= " - SIN TRANSFERENCIA";
                        }
                    }
                    echo '<label class="' . $statusClass . '">' . $statusName . '</label>';
                    ?>
                    <a target="_blank" href='index.php?view=embryology-procedures/treatment-<?php echo strtolower($embryologyProcedure->embryology_procedure_code) ?>-patient-report&id=<?php echo $embryologyProcedureId ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-male"></i>
                        Reporte Paciente</a>
                    <a target="_blank" href='index.php?view=embryology-procedures/treatment-<?php echo strtolower($embryologyProcedure->embryology_procedure_code) ?>-report&id=<?php echo $embryologyProcedureId ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-user-md"></i>
                        Reporte Médico</a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-3">
                    <img class="profile-user-img img-responsive img-circle" src='<?php echo ($patient->image) ? "storage_data/patients/" . $patient->image : "../../../assets/default_user.jpg" ?>' alt="Foto del paciente">
                </div>
                <div class="col-md-5">
                    <?php
                    echo "<b>Nombre: </b>" . $patient->name . "<br>";
                    echo "<b>" . $patientOfficialData->name . ": </b>" . $patientOfficialData->value . "<br>";
                    echo "<b>Fecha nacimiento: </b>" . $patient->getBirthdayFormat() . "<br>";
                    echo "<b>Edad: </b>" . $patient->getAge() . "<br>";
                    echo "<b>Dirección: </b>" . $patient->calle . " " . $patient->num . " " . $patient->col . "<br>";
                    echo "<b>Teléfono: </b>" . $patient->tel . " <br><b>Teléfono alternativo: </b>" . $patient->tel2 . "<br>";
                    ?>
                </div>
                <?php if ($embryologyProcedure->patient_treatment_id != 12) : ?>
                    <div class="col-md-4">
                        <?php
                        echo "<b>Nombre pareja: </b>" . $partner->name . "<br>";
                        echo "<b>" . $partner->officialDocumentName . ": </b>" . $partner->officialDocumentValue . "<br>";
                        echo "<b>Fecha nacimiento: </b>" . $partner->birthdayFormat . "<br>";
                        echo "<b>Edad: </b>" . $partner->age . "<br>";
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <!-- /.box-body -->
        </div>
        <?php
        include("treatment-" . strtolower($embryologyProcedure->embryology_procedure_code) . "-view.php");
        ?>
    </div>
</div>