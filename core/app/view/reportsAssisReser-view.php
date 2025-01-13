<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#btnExport").click(function(e) {

            $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel",
                datatype: $datatype.Table,
                filename: 'Reporte Asistencia Citas'
            });

        });

    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1>Reporte Asistencia de Citas </h1>

            <form>
                <input type="hidden" name="view" value="reportsAssisReser">
                <div class="row">

                    <div class="col-md-2">
                        <input type="date" name="sd" value="<?php echo (isset($_GET["sd"])) ? $_GET['sd']: '' ?>" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="ed" value="<?php echo (isset($_GET["ed"])) ? $_GET['ed']: '' ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="name" value="<?php echo (isset($_GET["name"])) ? $_GET['name']: '' ?>" class="form-control" autocomplete="off" placeholder="Nombre del Paciente">
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
    <!--- -->
    <div class="row">
        <div class="col-md-12">
            <?php
            $fecha1 = isset($_GET["sd"])  ? $_GET['sd'] : null;
            $fecha2 = isset($_GET["ed"])  ? $_GET['ed'] : null;
            $name = isset($_GET["name"])  ? $_GET['name'] : null;
            $totalReservations = 0;

            $attended = ReservationData::getAllStatusDatesReser($fecha1, $fecha2, 2, $name);

            if (count($attended) > 0) : ?>

                <div class="clearfix"></div>
                <h3>No Asistieron</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Laboratorio</th>
                        <th>Observaciones</th>
                    </thead>

                    <?php
                    $tot = 0;
                    $t = "danger";
                    foreach ($attended as $reservation) :
                        $totalReservations ++;
                        $tot ++;
                    ?>
                        <tr class='<?php echo $t ?>'>
                            <td><?php echo $reservation->day_name ." ". $reservation->date ?></td>
                            <td><?php echo $reservation->hour ?></td>
                            <td><?php echo $reservation->patient_name ?></td>
                            <td><?php echo $reservation->medic_name ?></td>
                            <td><?php echo $reservation->laboratory_name ?></td>
                            <td><?php echo $reservation->note ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Pacientes que No Asitieron: <?php echo $tot ?></h4>

            <?php
            else :
                echo "<p class='alert alert-danger'>No hay citas a las que no asistieron</p>";
            endif;
            ?>

            <?php
            $fecha1 = isset($_GET["sd"])  ? $_GET['sd'] : null;
            $fecha2 = isset($_GET["ed"])  ? $_GET['ed'] : null;

            $noAttended = ReservationData::getAllStatusDatesReser($fecha1, $fecha2, 1, $name);

            if (count($noAttended) > 0) :
            ?>
                <div class="clearfix"></div>
                <h3>Asistieron</h3>

                <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                    <thead>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Laboratorio</th>
                        <th>Observaciones</th>
                        <th>Notas Consulta</th>
                        <th>Total Venta</th>
                        <th>Forma de Pago</th>
                    </thead>

                    <?php
                    $tot = 0;
                    $t = "success";
                    foreach ($noAttended as $reservation) :
                        $totalReservations ++;
                        $tot ++;
                        $sellPayments = OperationData::getAllBySellPay($reservation->sell_id);
                    ?>
                        <tr class='<?php echo $t ?>'>
                            <td><?php echo $reservation->day_name ." ". $reservation->date ?></td>
                            <td><?php echo $reservation->hour ?></td>
                            <td><?php echo $reservation->patient_name ?></td>
                            <td><?php echo $reservation->medic_name ?></td>
                            <td><?php echo $reservation->laboratory_name ?></td>
                            <td><?php echo $reservation->note ?></td>
                            <td><?php echo ($reservation->reservation_note) ? substr(strip_tags($reservation->reservation_note),0,100).'...':""; ?></td>
                            <td><?php echo ($reservation->sell_total) ? '$'.$reservation->sell_total:""   ?></td>
                            <td><?php 
                                foreach ($sellPayments as $payment) {
                                    echo "$payment->tname: "."$".number_format($payment->cash,2)."<br>";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <h4 style="color:#2A8AC4">Pacientes que asistieron: <?php echo $tot ?></h4>
            <?php
            else :
                echo "<p class='alert alert-danger'>No hay citas a las que asistieron</p>";
            endif;
            ?>
            <h4 style="color:#2A8AC4">Total Citas: <?php echo $totalReservations ?></h4>
        </div>
    </div>

    <br><br><br><br>
</section>