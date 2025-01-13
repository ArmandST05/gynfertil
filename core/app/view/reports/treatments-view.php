<?php
$startDate = isset($_GET["sd"])  ? $_GET['sd'] : date("Y-m-d");
$endDate = isset($_GET["ed"])  ? $_GET['ed'] :  date("Y-m-d");
$startAge = isset($_GET["startAge"])  ? $_GET['startAge'] : 0;
$endAge = isset($_GET["endAge"])  ? $_GET['endAge'] : 100;
$treatmentId = isset($_GET["treatmentId"])  ? $_GET['treatmentId'] : 0;

$treatments = PatientCategoryData::getAllTreatments();

//Filtros
if ($treatmentId == 0) {
    //Obtener información de todos los tratamientos
    $actualTreatments = PatientCategoryData::getActualTreatmentsByDateAge($startDate, $endDate, $startAge, $endAge);
    $successfulTreatments = PatientCategoryData::getTreatmentsByPregnancyResultDateAge($startDate, $endDate, $startAge, $endAge, 1); //Boolean Se embarazó
    $failedTreatments = PatientCategoryData::getTreatmentsByPregnancyResultDateAge($startDate, $endDate, $startAge, $endAge, 0); //Boolean No se embarazó
    $nonTransferTreatments = PatientCategoryData::getNonTransferTreatmentsByDateAge($startDate, $endDate, $startAge, $endAge); //Sin transferencia de embriones
    $canceledTreatments = PatientCategoryData::getCanceledTreatmentsByDateAge($startDate, $endDate, $startAge, $endAge);
} else {
    //Obtener información de un tratamiento específico
    $actualTreatments = PatientCategoryData::getActualTreatmentsByDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId);
    $successfulTreatments = PatientCategoryData::getTreatmentsByPregnancyResultDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId, 1); //Boolean Se embarazó
    $failedTreatments = PatientCategoryData::getTreatmentsByPregnancyResultDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId, 0); //Boolean No se embarazó
    $nonTransferTreatments = PatientCategoryData::getNonTransferTreatmentsByDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId, 0); //Sin transferencia de embriones
    $canceledTreatments = PatientCategoryData::getCanceledTreatmentsByDateAgeTreatment($startDate, $endDate, $startAge, $endAge, $treatmentId);
}

?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let report_dates = "<?php echo $startDate . " - " . $endDate ?>";

        $("#btnExport").click(function(e) {

            $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel",
                datatype: $datatype.Table,
                filename: 'Reporte Tratamientos ' + report_dates
            });

        });

    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1>Reporte Tratamientos</h1>
            <form>
                <input type="hidden" name="view" value="reports/treatments">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="control-label">Desde</label>
                            <input type="date" name="sd" value="<?php echo $startDate ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="control-label">Hasta</label>
                            <input type="date" name="ed" value="<?php echo $endDate ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label">Tratamiento</label>
                            <select name="treatmentId" id="treatmentId" class="form-control" id="combobox">
                                <option value="0">-- TODOS -- </option>
                                <?php foreach ($treatments as $treatment) : ?>
                                    <option value="<?php echo $treatment->id; ?>" <?php echo ($treatment->id == $treatmentId) ? "selected" : "" ?>><?php echo "(" . $treatment->code . ") " . $treatment->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="control-label">Edad Inicial</label>
                            <input type="number" name="startAge" value="<?php echo (isset($_GET["startAge"])) ? $_GET['startAge'] : '0' ?>" min="0" max="100" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="control-label">Edad Final</label>
                            <input type="number" name="endAge" value="<?php echo (isset($_GET["endAge"])) ? $_GET['endAge'] : '100' ?>" min="0" max="100" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <input type="submit" class="col-md-2 btn btn-success btn-block" value="Procesar">
                    </div>
                    <div class="col-md-2">
                        <br>
                        <input type="submit" class="col-md-2 btn btn-primary btn-block" value="Exportar" id="btnExport">
                    </div>
                </div>
            </form>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <?php
            if (count($actualTreatments) > 0) : ?>

                <div class="clearfix"></div>
                <h3>Tratamientos en Progreso</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Tratamiento</th>
                        <th></th>
                    </thead>

                    <?php
                    $totalActual = 0;
                    foreach ($actualTreatments as $actualTreatment) :
                        $totalActual++;
                    ?>
                        <tr>
                            <td><?php echo $actualTreatment->start_date_format ?></td>
                            <td>--</td>
                            <td><?php echo $actualTreatment->patient_name ?></td>
                            <td><?php echo $actualTreatment->getPatient()->getAge() ?></td>
                            <td><?php echo "(" . $actualTreatment->treatment_code . ") " . $actualTreatment->treatment_name ?></td>
                            <td><?php if ($actualTreatment->treatment_status_id  == 2) :
                                    if ($actualTreatment->total_notifications > 0) : ?>
                                        <i class="glyphicon glyphicon-ok"></i>
                                    <?php else : ?>
                                        <button onclick="notify('<?php echo $actualTreatment->patient_id ?>','<?php echo $actualTreatment->patient_tel ?>','<?php echo $actualTreatment->patient_tel2 ?>','<?php echo $actualTreatment->start_date ?>','2')" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-ok"></i> Avisar Prueba Embarazo</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($_SESSION['typeUser'] == "su") : ?>
                                    <a href="index.php?view=pacient_edocuenta&id_paciente=<?php echo $actualTreatment->patient_id . '&name=' . $actualTreatment->patient_name ?>" class="btn btn-success btn-xs"><i class="fas fa-file-invoice"></i> Estado de cuenta</a>
                                <?php endif; ?>
                                <?php if ($actualTreatment->embryology_treatment_code) : ?>
                                    <a target="_blank" href="index.php?view=embryology-procedures/details&treatmentId=<?php echo $actualTreatment->id ?>" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Detalles tratamiento</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total En Progreso: <?php echo $totalActual ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron tratamientos en progreso</p>";
            endif;
            ?>
            <?php
            if (count($successfulTreatments) > 0) : ?>

                <div class="clearfix"></div>
                <h3>Tratamientos con embarazo</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Tratamiento</th>
                        <th></th>
                    </thead>

                    <?php
                    $totalSuccessful = 0;
                    $class = "success";
                    foreach ($successfulTreatments as $successfulTreatment) :
                        $totalSuccessful++;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $successfulTreatment->start_date_format ?></td>
                            <td><?php echo $successfulTreatment->end_date_format ?></td>
                            <td><?php echo $successfulTreatment->patient_name ?></td>
                            <td><?php echo $successfulTreatment->getPatient()->getAgeByDate($successfulTreatment->end_date) ?></td>
                            <td><?php echo "(" . $successfulTreatment->treatment_code . ") " . $successfulTreatment->treatment_name ?></td>
                            <td>
                                <?php if ($_SESSION['typeUser'] == "su") : ?>
                                    <a href="index.php?view=pacient_edocuenta&id_paciente=<?php echo $successfulTreatment->patient_id . '&name=' . $successfulTreatment->patient_name ?>" class="btn btn-success btn-xs"><i class="fas fa-file-invoice"></i> Estado de cuenta</a>
                                <?php endif; ?>
                                <?php if ($successfulTreatment->embryology_treatment_code) : ?>
                                    <a target="_blank" href="index.php?view=embryology-procedures/details&treatmentId=<?php echo $successfulTreatment->id ?>" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Detalles tratamiento</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total con embarazos: <?php echo $totalSuccessful ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron tratamientos exitosos</p>";
            endif;
            ?>
            <?php
            if (count($failedTreatments) > 0) : ?>
                <div class="clearfix"></div>
                <h3>Tratamientos sin embarazo</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Tratamiento</th>
                        <th></th>
                    </thead>

                    <?php
                    $totalFailed = 0;
                    $class = "danger";
                    foreach ($failedTreatments as $failedTreatment) :
                        $totalFailed++;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $failedTreatment->start_date_format ?></td>
                            <td><?php echo $failedTreatment->end_date_format ?></td>
                            <td><?php echo $failedTreatment->patient_name ?></td>
                            <td><?php echo $failedTreatment->getPatient()->getAgeByDate($failedTreatment->end_date) ?></td>
                            <td><?php echo "(" . $failedTreatment->treatment_code . ") " . $failedTreatment->treatment_name ?></td>
                            <td>
                                <?php if ($_SESSION['typeUser'] == "su") : ?>
                                    <a href="index.php?view=pacient_edocuenta&id_paciente=<?php echo $failedTreatment->patient_id . '&name=' . $failedTreatment->patient_name ?>" class="btn btn-success btn-xs"><i class="fas fa-file-invoice"></i> Estado de cuenta</a>
                                <?php endif; ?>
                                <?php if ($failedTreatment->embryology_treatment_code) : ?>
                                    <a target="_blank" href="index.php?view=embryology-procedures/details&treatmentId=<?php echo $failedTreatment->id ?>" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Detalles tratamiento</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total sin embarazo: <?php echo $totalFailed ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron tratamientos fallidos</p>";
            endif;
            ?>
            <?php
            if (count($failedTreatments) > 0) : ?>
                <div class="clearfix"></div>
                <h3>Tratamientos sin transferencia</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Tratamiento</th>
                        <th></th>
                    </thead>

                    <?php
                    $totalNonTransfer = 0;
                    $class = "danger";
                    foreach ($nonTransferTreatments as $nonTransferTreatment) :
                        $totalNonTransfer++;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $nonTransferTreatment->start_date_format ?></td>
                            <td><?php echo $nonTransferTreatment->end_date_format ?></td>
                            <td><?php echo $nonTransferTreatment->patient_name ?></td>
                            <td><?php echo $nonTransferTreatment->getPatient()->getAgeByDate($nonTransferTreatment->end_date) ?></td>
                            <td><?php echo "(" . $nonTransferTreatment->treatment_code . ") " . $nonTransferTreatment->treatment_name ?></td>
                            <td>
                                <?php if ($_SESSION['typeUser'] == "su") : ?>
                                    <a href="index.php?view=pacient_edocuenta&id_paciente=<?php echo $nonTransferTreatment->patient_id . '&name=' . $nonTransferTreatment->patient_name ?>" class="btn btn-success btn-xs"><i class="fas fa-file-invoice"></i> Estado de cuenta</a>
                                <?php endif; ?>
                                <?php if ($nonTransferTreatment->embryology_treatment_code) : ?>
                                    <a target="_blank" href="index.php?view=embryology-procedures/details&treatmentId=<?php echo $nonTransferTreatment->id ?>" class="btn btn-primary btn-xs"><i class="fas fa-eye"></i> Detalles tratamiento</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total sin embarazo: <?php echo $totalNonTransfer ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron tratamientos sin transferencia de embriones</p>";
            endif;
            ?>

            <?php
            if (count($canceledTreatments) > 0) : ?>

                <div class="clearfix"></div>
                <h3>Tratamientos Cancelados</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Tratamiento</th>
                        <th></th>
                    </thead>

                    <?php
                    $totalCanceled = 0;
                    $class = "danger";
                    foreach ($canceledTreatments as $canceledTreatment) :
                        $totalCanceled++;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $canceledTreatment->start_date_format ?></td>
                            <td><?php echo $canceledTreatment->end_date_format ?></td>
                            <td><?php echo $canceledTreatment->patient_name ?></td>
                            <td><?php echo $canceledTreatment->getPatient()->getAgeByDate($canceledTreatment->end_date) ?></td>
                            <td><?php echo "(" . $canceledTreatment->treatment_code . ") " . $canceledTreatment->treatment_name ?></td>
                            <td>
                                <?php if ($_SESSION['typeUser'] == "su") : ?>
                                    <a href="index.php?view=pacient_edocuenta&id_paciente=<?php echo $canceledTreatment->patient_id . '&name=' . $canceledTreatment->patient_name ?>" class="btn btn-success btn-xs"><i class="fas fa-file-invoice"></i> Estado de cuenta</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total Cancelados: <?php echo $totalCanceled ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron tratamientos cancelados</p>";
            endif;
            ?>
        </div>
    </div>

    <br><br><br><br>
</section>