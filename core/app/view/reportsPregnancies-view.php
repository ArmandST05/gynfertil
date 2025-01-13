<?php
$startDate = isset($_GET["sd"])  ? $_GET['sd'] : date("Y-m-d");
$endDate = isset($_GET["ed"])  ? $_GET['ed'] : date("Y-m-d");

$treatmentPregnancies = PatientPregnancyData::getPregnanciesByTypeDate($startDate, $endDate,1);
$externalPregnancies = PatientPregnancyData::getPregnanciesByTypeDate($startDate, $endDate,2);
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#btnExport").click(function(e) {
            let report_dates = "<?php echo $startDate." - ".$endDate?>"; 
            $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel",
                datatype: $datatype.Table,
                filename: 'Reporte Embarazos '+report_dates
            });

        });

    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1>Reporte Embarazos</h1>
            <form>
                <input type="hidden" name="view" value="reportsPregnancies">
                <div class="row">

                    <div class="col-md-2">
                        <input type="date" name="sd" value="<?php echo $startDate ?>" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="ed" value="<?php echo $endDate ?>" class="form-control">
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
            if (count($treatmentPregnancies) > 0) : ?>

                <div class="clearfix"></div>
                <h3>Embarazos por Tratamiento</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha de Registro</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Tratamiento</th>
                    </thead>

                    <?php
                    $totalTreatment = 0;
                    $class = "success";
                    foreach ($treatmentPregnancies as $treatmentPregnancy) :
                        $totalTreatment++;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $treatmentPregnancy->start_date_format ?></td>
                            <td><?php echo $treatmentPregnancy->getPatient()->name ?></td>
                            <td><?php echo $treatmentPregnancy->getPatient()->getAgeByDate($treatmentPregnancy->start_date) ?></td>
                            <td><?php echo $treatmentPregnancy->getTreatment()->treatment_name ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total Embarazos Tratamiento: <?php echo $totalTreatment ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron embarazos por tratamiento.</p>";
            endif;
            ?>

            <?php
            if (count($externalPregnancies) > 0) : ?>

                <div class="clearfix"></div>
                <h3>Embarazos Externos</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha Registro</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Tratamiento</th>
                    </thead>

                    <?php
                    $totalExternal = 0;
                    $class = "info";
                    foreach ($externalPregnancies as $externalPregnancy) :
                        $totalExternal++;
                    ?>
                        <tr class='<?php echo $class ?>'>
                            <td><?php echo $externalPregnancy->start_date_format ?></td>
                            <td><?php echo $externalPregnancy->getPatient()->name ?></td>
                            <td><?php echo $externalPregnancy->getPatient()->getAgeByDate($externalPregnancy->start_date) ?></td>
                            <td>NO APLICA</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total Embarazos Externos: <?php echo $totalExternal ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron embarazos externos.</p>";
            endif;
            ?>
        </div>
    </div>

    <br><br><br><br>
</section>