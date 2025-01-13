    <script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link href="assets/select2.min.css" rel="stylesheet" />
    <script src="assets/select2.min.js"></script>
    <?php
    $pacients = PatientData::getAllMedics();
    $ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
    $ti_usua = UserData::get_tipo_usuario($ti_user);

    foreach ($ti_usua as $key) {
      $tipo = $key->tipo_usuario;
    }

    if ($tipo == "do") {
      $medics = MedicData::getAll_doctor($_SESSION["user_id"]);
    } else {
      $medics = MedicData::getAll();
    }
    $pac = PatientData::estatus_paciente();

    $lab = MedicData::getlabarotario();
    $color = ReservationData::getReservationAreas();

    $asis = MedicData::getAll_asistente();
    foreach ($asis as $key1) {
      $asistente = $key1->id;
    }

    if (!empty($fecha)) {
      $start = date('Y-m-d', strtotime($fecha));

      $hora = date('H:i', strtotime($fecha));

      $hora2 = strtotime('+30 minute',  strtotime($fecha));
      $nueva_hora = date('H:i', $hora2);
    } else {
      $start = date('Y-m-d');
      $hora = date('H:i');
      $hora2 = strtotime('+30 minute',  strtotime($hora));
      $nueva_hora = date('H:i', $hora2);
      //$nueva_hora = date('H:i');


    }
    ?>
    <style type="text/css">
      .navbar h3 {
        color: #f5f5f5;
        margin-top: 14px;
      }

      .hljs-pre {
        background: #f8f8f8;
        padding: 3px;
      }

      .footer {
        border-top: 1px solid #eee;
        margin-top: 40px;
        padding: 40px 0;
      }

      .input-group {
        width: 110px;
        margin-bottom: 10px;
      }

      .pull-center {
        margin-left: auto;
        margin-right: auto;
      }

      @media (min-width: 768px) {
        .container {
          max-width: 730px;
        }
      }

      @media (max-width: 767px) {
        .pull-center {
          float: right;
        }
      }
    </style>
    <script type="text/javascript">
      $(document).ready(function() {
        $("#pacient_id").select2({});
      });
    </script>


    <link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">

    <div class="row">
      <div class="col-md-12">

        <div class="card">
          <div class="card-header" data-background-color="blue">
            <h4 class="title">Nueva Cita Doctor</h4>
          </div>
          <div class="card-content table-responsive">
            <form class="form-horizontal" role="form" method="POST" action="./?action=addreservationdoc">


              <div class="form-group">

                <div class="col-lg-4">
                  <label for="inputEmail1" class="col-lg-3 control-label">Doctor</label>
                  <select name="pacient_id" id="pacient_id" class="form-control" id="combobox" autofocus required>
                    <option value="">-- SELECCIONE -- </option>
                    <?php foreach ($pacients as $p) : ?>
                      <option value="<?php echo $p->id; ?>"><?php echo $p->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

              </div>

              <div class="form-group">
                <div class="col-lg-3">

                  <label for="inputEmail1" class="col-lg-3 control-label">Fecha</label>
                  <input type="date" name="cita" id="formfecha" class="form-control" value="<?php echo $start ?>">
                </div>


                <div class="clearfix col-lg-3">
                  <label for="inputEmail1" class="col-lg-3 control-label">H/ini</label>
                  <input type="time" class="form-control" value="<?php echo $hora ?>" name="time_at" id="time_at" class="form-control">
                </div>



                <div class="clearfix col-lg-3">
                  <label for="inputEmail1" class="col-lg-3 control-label">H/fin</label>
                  <input type="time" class="form-control" value="<?php echo $nueva_hora ?>" name="time_at_final" id="time_at_final" class="form-control">


                </div>
              </div>
              <input type="hidden" name="asistente" id="asistente" required class="form-control" Value="<?php echo $asistente ?>">
              <input type="hidden" value="<?php echo  $_SESSION["user_id"]; ?>" name="user_id" id="user_id">

              <div class="form-group">

                <div class="col-lg-9">
                  <label for="inputEmail1" class="col-lg-1 control-label">Nota</label>
                  <textarea class="form-control" name="note" placeholder="Nota"></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-4">
                  <button type="submit" class="btn btn-default" onclick="calcularfecha()">Agregar Cita</button>
                </div>
              </div>
            </form>
            <script type="text/javascript">

            </script>


          </div>
        </div>
      </div>
    </div>