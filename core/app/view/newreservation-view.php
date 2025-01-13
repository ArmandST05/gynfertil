  <script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>
  <link href="assets/select2.min.css" rel="stylesheet" />
  <script src="assets/select2.min.js"></script>
  <?php
  $pacients = PatientData::getAll();
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
  $reservationAreas = ReservationData::getReservationAreas();

  $asis = MedicData::getAll_asistente();
  foreach ($asis as $key1) {
    $asistente = $key1->id;
  }
  $hoy = date("Y-m-d H:i:s");
  $fecha = isset($_GET["start"])  ? $_GET['start'] :  date('Y-m-d');

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


  <div class="row">
    <div class="col-md-12">
      <h1>Nueva reservación</h1>
      <br>

      <form class="form-horizontal" method="post" action="./?action=addreservation" role="form" id="formNew">

        <div class="form-group">
          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Paciente</label>
            <select name="pacient_id" id="pacient_id" class="form-control" id="combobox" autofocus required>
              <option value="">-- SELECCIONE -- </option>
              <?php foreach ($pacients as $p) : ?>
                <option value="<?php echo $p->id; ?>"><?php echo $p->id . " - " . $p->name ?></option>
              <?php endforeach; ?>

            </select>
          </div>

          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Médico</label>
            <select name="medic_id" class="form-control" required>
              <?php if ($tipo == "su" || $tipo == "sub" || $tipo == "r") {
                echo "<option value=''>-- SELECCIONE --</option>";
              } else {
              }
              ?>
              <?php foreach ($medics as $p) : ?>
                <option value="<?php echo $p->id; ?>"><?php echo $p->id . " - " . $p->name; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Laboratorio</label>

            <select name="laboratorio" class="form-control" required>
              <?php
              echo "<option value=''>-- SELECCIONE --</option>";
              ?>
              <?php foreach ($lab as $l) : ?>
                <option value="<?php echo $l->id; ?>"><?php echo $l->nombre; ?></option>
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

          <div class="col-lg-1">

            <input type="hidden" name="color_letra" id="color_letra" class="" value="1">
          </div>
        </div>

        <div class="form-group">

          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Clave</label>
            <select name="pac_est" class="form-control" required>
              <option value="">-- SELECCIONE --</option>
              <?php foreach ($pac as $pa) : ?>
                <option value="<?php echo $pa->id; ?>"><?php echo $pa->id . " - " . $pa->nombre; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="hidden" value="<?php echo  $_SESSION["user_id"]; ?>" name="user_id" id="user_id">
          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Cita</label>
            <select name="color" class="form-control" required>
              <?php
              echo "<option value=''>-- SELECCIONE --</option>";
              ?>
              <?php foreach ($reservationAreas as $c) : ?>
                <option value="<?php echo $c->id; ?>"><?php echo $c->descripcion; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <div class="col-lg-9">
            <label for="inputEmail1" class="col-lg-1 control-label">Comentarios</label>
            <textarea class="form-control" name="note" placeholder="Nota"></textarea>
          </div>

          <div class="col-lg-3">
            <br>
            <button type="submit" class="btn btn-default" id="btnSubmit">Agregar Cita</button>
          </div>
        </div>
        <input type="hidden" name="asistente" id="asistente" required class="form-control" Value="">

      </form>
      <script type="text/javascript">
        $(document).ready(function() {
          $("#pacient_id").select2({});
        });

        $("#formNew").submit(function() {
          $('#btnSubmit').attr("disabled", true);
          setTimeout(function() {
            $('#btnSubmit').attr("disabled", false)
          }, 1500);
        });
      </script>

    </div>
  </div>
  </body>