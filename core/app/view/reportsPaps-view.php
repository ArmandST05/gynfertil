<?php
$fecha1 = isset($_GET["sd"])  ? $_GET['sd'] : null;
$fecha2 = isset($_GET["ed"])  ? $_GET['ed'] : null;

$futurePaps = ReservationData::getAllFuturePapsTests($fecha1, $fecha2);
$executedPaps = ReservationData::getAllExecutedPapsTests($fecha1, $fecha2);
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#btnExport").click(function(e) {

            $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel",
                datatype: $datatype.Table,
                filename: 'Reporte Papanicolaou'
            });

        });

    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1>Reporte Papanicolaou</h1>
            <form>
                <input type="hidden" name="view" value="reportsPaps">
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
            if (count($futurePaps) > 0) : ?>

                <div class="clearfix"></div>
                <h3>Próximos Papanicolaou</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Próxima Fecha</th>
                        <th>Paciente</th>
                        <th>Avisado</th>
                    </thead>

                    <?php
                    $tot = 0;
                    $t = "danger";
                    foreach ($futurePaps as $papanicolaou) :
                        $tot++;
                    ?>
                        <tr class='<?php echo $t ?>'>
                            <td><?php echo $papanicolaou->date_format ?></td>
                            <td><?php echo $papanicolaou->patient_name ?></td>
                            <td><?php if ($papanicolaou->total_notifications > 0) : ?>
                                    <i class="glyphicon glyphicon-ok"></i>
                                <?php else : ?>
                                    <button onclick="notify('<?php echo $papanicolaou->patient_id ?>','<?php echo $papanicolaou->patient_tel ?>','<?php echo $papanicolaou->patient_tel2 ?>','<?php echo $papanicolaou->date ?>','1')" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-ok"></i> Avisar Paciente</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total de Pacientes: <?php echo $tot ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron resultados</p>";
            endif;
            ?>

            <?php
            if (count($executedPaps) > 0) : ?>

                <div class="clearfix"></div>
                <h3>Papanicolaou Realizados</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha Papanicolau</th>
                        <th>Paciente</th>
                    </thead>

                    <?php
                    $tot = 0;
                    $t = "success";
                    foreach ($executedPaps as $papanicolaou) :
                        $tot++;
                    ?>
                        <tr class='<?php echo $t ?>'>
                            <td><?php echo $papanicolaou->date_format ?></td>
                            <td><?php echo $papanicolaou->patient_name ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Total de Pacientes: <?php echo $tot ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No se encontraron resultados</p>";
            endif;
            ?>
        </div>
    </div>

    <br><br><br><br>
</section>