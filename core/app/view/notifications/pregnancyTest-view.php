<?php
$fecha1 = date("Y-m-d", strtotime('+7 days'));
$fecha2 = date("Y-m-01");

$user_id = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;

$user = UserData::getUserType($user_id);
$user_type = $user->tipo_usuario;

$futurePregnancyTests = PatientCategoryData::getFuturePregnancyTestsNotifications(date("Y-m-d"));
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#btnExport").click(function(e) {
            $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel",
                datatype: $datatype.Table,
                filename: 'Reporte Pruebas de Embarazo'
            });

        });

    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1>Notificaciones pruebas de embarazo
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Pruebas de embarazo pendientes</h3>
                    <div class="pull-right">

                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <?php if (count($futurePregnancyTests) > 0) : ?>
                            <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                                <thead>
                                    <th>Fecha prueba de embarazo</th>
                                    <th>Paciente</th>
                                    <th></th>
                                </thead>

                                <?php
                                $tot = 0;
                                foreach ($futurePregnancyTests as $pregnancyTest) :
                                    if ($pregnancyTest->total_notifications == 0) $class = "danger";
                                    else $class = "success";
                                    $tot++;
                                ?>
                                    <tr class='<?php echo $class ?>'>
                                        <td><?php echo $pregnancyTest->pregnancy_test_date_format ?></td>
                                        <td><b><?php echo $pregnancyTest->patient_name ?></b><br>
                                            <?php echo "Teléfono: " . $pregnancyTest->patient_tel ?><br>
                                            <?php echo "Teléfono alternativo: " . $pregnancyTest->patient_tel2 ?>
                                        </td>
                                        <td><?php if ($pregnancyTest->total_notifications == 0) : ?>
                                                    <button onclick="notify('<?php echo $pregnancyTest->patient_id ?>','<?php echo $pregnancyTest->patient_tel ?>','<?php echo $pregnancyTest->patient_tel2 ?>','<?php echo $pregnancyTest->end_date ?>','2')" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-ok"></i> Avisar Paciente</button>
                                                <?php else : ?>
                                                    <i class="fas fa-check"> Avisado
                                                    <?php endif; ?>
                        
                                            <br>
                                            <?php if($user_type == "su" || $user_type == "sub" || $user_type == "do"): ?>
                                            <div id="divPregnancyOptions<?php echo $pregnancyTest->id; ?>">
                                                <button type="button" id="btnPregnancyTest" class="btn btn-primary btn-xs" onclick="showPregnancyResult('<?php echo $pregnancyTest->id ?>')"><i class="fas fa-vial"></i> Resultado de Prueba</button>
                                                <div id="divPregnancyResultOptions<?php echo $pregnancyTest->id; ?>" style="display:none">
                                                    <div class="form-group">
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="pregnancy_result<?php echo $pregnancyTest->id; ?>" value="1" checked>
                                                                Embarazo Exitoso
                                                            </label>
                                                        </div>
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="pregnancy_result<?php echo $pregnancyTest->id; ?>" value="0">
                                                                No se Embarazó
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <button type="button" id="btnCancelTreatment" class="btn btn-danger btn-xs" onclick="hidePregnancyResult('<?php echo $pregnancyTest->id ?>')"><i class="fas fa-times"></i> Cancelar</button>
                                                    <button type="button" id="btnStartPregnancyTestTreatment" class="btn btn-primary btn-xs" onclick="savePregnancyResult('<?php echo $pregnancyTest->id ?>')"><i class="fas fa-check"></i> Guardar</button>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php else : ?>
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
<script>
    $(document).ready(function() {});

    //EMBARAZOS - RESULTADO DE TRATAMIENTO

    function showPregnancyResult(id) {
        //Mostrar las opciones de embarazo
        $("#divPregnancyResultOptions" + id).show();
        $("#btnPregnancyTest" + id).hide();
    }

    function hidePregnancyResult(id) {
        //Mostrar las opciones de embarazo
        $("#divPregnancyResultOptions" + id).hide();
        $("#btnPregnancyTest" + id).show();
    }

    function savePregnancyResult(id) {
        //Cambiar el estatus y marcar como en pruebas de embarazo
        Swal.fire({
            title: '¿Deseas guardar los resultados de la prueba de embarazo?',
            text: "Esta acción no se podrá revertir.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: "POST",
                    url: "./?action=patientcategories/updatePatientTreatmentResult",
                    data: {
                        category_treatment_id: id,
                        pregnancy_test_result: $('input[type=radio][name=pregnancy_result' + id + ']:checked').val()
                    },
                    error: function() {
                        Swal.fire(
                            'Error',
                            'No se pudo guardar el resultado del tratamiento...',
                            'error'
                        )
                    },
                    success: function(data) {
                        $("#divPregnancyOptions").hide();
                        location.reload();
                    }
                });
            }
        })
    }
</script>