<?php
$startDate = isset($_GET["sd"])  ? $_GET['sd'] : null;
$endDate = isset($_GET["ed"])  ? $_GET['ed'] : null;

$ovuleVitrifications = PatientCategoryData::getTreatmentsByTypeDates(10, $startDate, $endDate, 4,1);
$embryoVitrifications = EmbryologyProcedureVitrificationData::getAllByTypeDates(2, $startDate, $endDate);
$semenVitrifications = AndrologyProcedureData::getAllProceduresByTypeDates(2, $startDate, $endDate);

?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btnExport").click(function(e) {
            let reportDates = "<?php echo $startDate . " - " . $endDate ?>";
            $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel",
                datatype: $datatype.Table,
                filename: 'Banco de Gametos ' + reportDates
            });

        });

    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1>Banco de Gametos</h1>
            <form>
                <input type="hidden" name="view" value="reports/vitrifications">
                <div class="row">
                    <div class="col-md-2">
                        <input type="date" name="sd" value="<?php echo (isset($_GET["sd"])) ? $_GET['sd'] : '' ?>" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="ed" value="<?php echo (isset($_GET["ed"])) ? $_GET['ed'] : '' ?>" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-success btn-block" value="Procesar">
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-primary btn-block" value="Exportar" id="btnExport">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <?php
            if (count($ovuleVitrifications) > 0 && $_SESSION['typeUser'] != "an") : ?>

                <div class="clearfix"></div>
                <h3>Vitrificaciones de óvulos</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Óvulos Congelados</th>
                            <th>Óvulos Utilizados</th>
                            <th>Óvulos Restantes</th>
                        </tr>
                    </thead>
                    <?php
                    $totalOvuleVitrifications = 0;
                    $totalGeneralVitrifiedOvules = 0;
                    $totalGeneralRemainingOvules = 0;
                    $totalGeneralUsedOvules = 0;
                    $class = "success";
                    foreach ($ovuleVitrifications as $ovuleVitrification) :
                        $totalOvuleVitrifications++;
                        $dateData = EmbryologyProcedureData::getDetail($ovuleVitrification->id, 40);
                        $date = ($dateData) ? $dateData->value : "";
                        $totalVitrifiedOvules = PatientOvuleData::getTotalOvulesByStatusPhaseProcedureId($ovuleVitrification->id, 2, 1)->total;
                        $usedOvules = PatientOvuleData::getUsedOvulesByProcedureId($ovuleVitrification->id, 2, 1);
                        $totalUsedOvules = 0;
                        $totalRemainingOvules = 0;
                        $totalGeneralVitrifiedOvules += $totalVitrifiedOvules;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $ovuleVitrification->treatment_code ?></td>
                            <td><?php echo $date ?></td>
                            <td><?php echo $ovuleVitrification->getPatient()->name ?></td>
                            <td><?php echo $totalVitrifiedOvules ?></td>
                            <td><?php foreach ($usedOvules as $usedOvule) {
                                    $totalUsedOvules += $usedOvule->total;
                                    echo $usedOvule->treatment_code . " (" . $usedOvule->total . ")";
                                }
                                if ($totalVitrifiedOvules > 0) $totalRemainingOvules = $totalVitrifiedOvules - $totalUsedOvules;
                                $totalGeneralRemainingOvules += $totalRemainingOvules;
                                $totalGeneralUsedOvules += $totalUsedOvules;
                                ?></td>
                            <td><?php echo $totalRemainingOvules ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tfoot>
                        <tr class='<?php echo $class ?>'>
                            <th class="text-right" colspan="3">TOTALES</th>
                            <th><?php echo $totalGeneralVitrifiedOvules ?></th>
                            <th><?php echo $totalGeneralUsedOvules ?></th>
                            <th><?php echo $totalGeneralRemainingOvules ?></th>
                        </tr>
                    </tfoot>
                </table>
                <h4 style="color:#2A8AC4">Total procedimientos de vitrificación de óvulos: <?php echo $totalOvuleVitrifications ?></h4>
            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron vitrificaciones de óvulos.</p>";
            endif;
            ?>

            <?php
            if (count($embryoVitrifications) > 0 && $_SESSION['typeUser'] != "an") : ?>

                <div class="clearfix"></div>
                <h3>Vitrificaciones de Embriones</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Embriones Congelados</th>
                        <th>Embriones Utilizados</th>
                        <th>Embriones Restantes</th>
                    </thead>

                    <?php
                    $totalEmbryoVitrifications = 0;
                    $totalGeneralVitrifiedEmbryos = 0;
                    $totalGeneralRemainingEmbryos = 0;
                    $totalGeneralUsedEmbryos = 0;

                    $class = "info";
                    foreach ($embryoVitrifications as $embryoVitrification) :
                        $totalEmbryoVitrifications++;
                        $totalVitrifiedEmbryos = floatval(PatientOvuleData::getTotalOvulesByStatusPhaseProcedureId($embryoVitrification->patient_category_treatment_id, 2, 2)->total);
                        $usedEmbryos = PatientOvuleData::getUsedOvulesByProcedureId($embryoVitrification->patient_category_treatment_id, 2, 2);
                        $totalUsedEmbryos = 0;
                        $totalRemainingEmbryos = 0;
                        $totalGeneralVitrifiedEmbryos += $totalVitrifiedEmbryos;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $embryoVitrification->code ?></td>
                            <td><?php echo $embryoVitrification->first_date_format ?></td>
                            <td><?php echo $embryoVitrification->patient_name ?></td>
                            <td><?php echo $embryoVitrification->total ?></td>
                            <td><?php foreach ($usedEmbryos as $usedEmbryo) {
                                    $totalUsedEmbryos += $usedEmbryo->total;
                                    echo $usedEmbryo->treatment_code . " (" . $usedEmbryo->total . ")";
                                }
                                if ($totalVitrifiedEmbryos > 0) $totalRemainingEmbryos = $totalVitrifiedEmbryos - $totalUsedEmbryos;
                                $totalGeneralRemainingEmbryos += $totalRemainingEmbryos;
                                $totalGeneralUsedEmbryos += $totalUsedEmbryos;
                                ?></td>
                            <td><?php echo $totalRemainingEmbryos ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tfoot>
                        <tr class='<?php echo $class ?>'>
                            <th class="text-right" colspan="3">TOTALES</th>
                            <th><?php echo $totalGeneralVitrifiedEmbryos ?></th>
                            <th><?php echo $totalGeneralUsedEmbryos ?></th>
                            <th><?php echo $totalGeneralRemainingEmbryos ?></th>
                        </tr>
                    </tfoot>
                </table>
                <h4 style="color:#2A8AC4">Total procedimientos de vitrificación de embriones: <?php echo $totalEmbryoVitrifications ?></h4>
            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron vitrificaciones de embriones.</p>";
            endif;
            ?>
            <?php
            if (count($semenVitrifications) > 0) : ?>

                <div class="clearfix"></div>
                <h3>Congelaciones de semen</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Dispositivos Congelados</th>
                            <th>Dispositivos Utilizados</th>
                            <th>Dispositivos Restantes</th>
                        </tr>
                    </thead>
                    <?php
                    $totalSemenVitrifications = 0;
                    $totalGeneralVitrifiedSemen = 0;
                    $totalGeneralRemainingSemen = 0;
                    $totalGeneralUsedSemen = 0;
                    $class = "success";
                    foreach ($semenVitrifications as $semenVitrification) :
                        $totalSemenVitrifications++;

                        $totalVitrifiedSemen = AndrologyProcedureData::getDetail($semenVitrification->id, 255);
                        $totalVitrifiedSemen = ($totalVitrifiedSemen) ? $totalVitrifiedSemen->value : 0;

                        $usedSemen = AndrologyProcedureData::getUsedSemenProceduresByProcedureId($semenVitrification->id);
                        $totalUsedSemen = 0;
                        $totalRemainingSemen = 0;
                        $totalGeneralVitrifiedSemen += $totalVitrifiedSemen;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $semenVitrification->procedure_code ?></td>
                            <td><?php echo  $semenVitrification->date_format ?></td>
                            <td><?php echo $semenVitrification->getPatient()->name ?></td>
                            <td><?php echo $totalVitrifiedSemen ?></td>
                            <td><?php foreach ($usedSemen as $usedSemen) {
                                    $totalUsedSemen += $usedSemen->quantity;
                                    $code = (isset($usedSemen->treatment_code) ? $usedSemen->treatment_code : $usedSemen->procedure_code);
                                    echo $code . " (" . $usedSemen->quantity . ")<br>";
                                }
                                if ($totalVitrifiedSemen > 0) $totalRemainingSemen = $totalVitrifiedSemen - $totalUsedSemen;
                                $totalGeneralRemainingSemen += $totalRemainingSemen;
                                $totalGeneralUsedSemen += $totalUsedSemen;
                                ?></td>
                            <td><?php echo $totalRemainingSemen ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tfoot>
                        <tr class='<?php echo $class ?>'>
                            <th class="text-right" colspan="3">TOTALES</th>
                            <th><?php echo $totalGeneralVitrifiedSemen ?></th>
                            <th><?php echo $totalGeneralUsedSemen ?></th>
                            <th><?php echo $totalGeneralRemainingSemen ?></th>
                        </tr>
                    </tfoot>
                </table>
                <h4 style="color:#2A8AC4">Total procedimientos de congelación de semen: <?php echo $totalSemenVitrifications ?></h4>
            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron congelaciones de semen.</p>";
            endif;
            ?>
        </div>
    </div>
    <br>
    <br><br><br><br>
</section>