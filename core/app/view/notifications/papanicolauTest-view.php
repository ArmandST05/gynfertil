<?php
$fecha1 = date("Y-m-d", strtotime('+7 days'));
$fecha2 = date("Y-m-01");

$futurePaps = ReservationData::getFuturePapsTestsNotifications($fecha1);
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
            <h1>Notificaciones próximos papanicolaou
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Próximos Papanicolau</h3>
                    <div class="pull-right">
                        <a href="./?view=reportsPaps&sd=<?php echo $fecha2 ?>&ed=<?php echo $fecha1 ?>" class='btn btn-primary btn-xs'>Ver Reporte</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <?php if (count($futurePaps) > 0) : ?>
                            <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                                <thead>
                                    <th>Próxima Fecha</th>
                                    <th>Paciente</th>
                                    <th></th>
                                </thead>

                                <?php
                                $tot = 0;
                                $class = "danger";
                                foreach ($futurePaps as $papanicolaou) :
                                    $tot++;
                                ?>
                                    <tr class='<?php echo $class ?>'>
                                        <td><?php echo $papanicolaou->date_format ?></td>
                                        <td><b><?php echo $papanicolaou->patient_name ?></b><br>
                                            <?php echo "Teléfono: " . $papanicolaou->patient_tel ?><br>
                                            <?php echo "Teléfono alternativo: " . $papanicolaou->patient_tel2 ?>
                                        </td>
                                        <td><button onclick="notify('<?php echo $papanicolaou->patient_id ?>','<?php echo $papanicolaou->patient_tel ?>','<?php echo $papanicolaou->patient_tel2 ?>','<?php echo $papanicolaou->date ?>','1')" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-ok"></i> Avisar Paciente</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php else: ?>
                            <label>No tienes notificaciones pendientes.</label>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    <br><br><br><br>
</section>