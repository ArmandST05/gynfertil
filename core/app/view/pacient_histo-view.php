<?php
$months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
$contador = 0;
$users = ReservationData::getAll_filter_reservaciones_historial_pacient($_GET["id_paciente"]);
$contador = count($users);

$patient = PatientData::getById($_GET["id_paciente"]);
$ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
$ti_usua = UserData::get_tipo_usuario($ti_user);
foreach ($ti_usua as $key) {
  $tipo = $key->tipo_usuario;
}

//Edad del paciente
$dayn = substr($patient->fecha_na, 8, 2);
$monthn = substr($patient->fecha_na, 5, 2);
$yearn = substr($patient->fecha_na, 0, 4);
$date2 = date('Y-m-d');
$diff = abs(strtotime($date2) - strtotime($patient->fecha_na));
$years = floor($diff / (365 * 60 * 60 * 24));
if ($years == 1) {
  $years = $years . " Año";
} else {
  $years = $years . " Años";
}

if ($patient->fecha_na) {
  $birthday_format = substr($patient->fecha_na, 8, 2) . "/" . $months[substr($patient->fecha_na, 5, 2)] . "/" . substr($patient->fecha_na, 0, 4);
  $actual_date = date('Y-m-d');
  $diff = abs(strtotime($actual_date) - strtotime($patient->fecha_na));
  $patient_age = floor($diff / (365 * 60 * 60 * 24));
  $patient_age = ($patient_age == 1) ? $patient_age . " Año" : $patient_age . " Años";
} else {
  $birthday_format = "No capturada";
  $patient_age = "No capturada";
}

?>
<div class="row">
  <div class="col-md-12">
    <h1>Historial <?php echo $_GET["name"] ?></h1>
    <div class="clearfix"></div>
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Datos del Paciente</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="col-md-3">
          <img class="profile-user-img img-responsive img-circle" src='<?php echo ($patient->image) ? "storage_data/patients/" . $patient->image : "../../../assets/default_user.jpg" ?>' alt="Foto del paciente">
        </div>
        <div class="col-md-9">
          <?php
          echo "<b>Nombre completo: </b>" . $patient->name . "<br>";
          echo "<b>Dirección: </b>" . $patient->calle . " " . $patient->num . " " . $patient->col . "<br>";
          echo "<b>Teléfono: </b>" . $patient->tel . " <br><b>Teléfono alternativo: </b>" . $patient->tel2 . "<br>";
          echo "<b>Email: </b>" . $patient->email . "<br>";
          echo "<b>Fecha nacimiento: </b>" . $birthday_format . "<br>";
          echo "<b>Edad: </b>" . $patient_age . "<br>";
          echo "<b>Referida: </b>" . $patient->ref . "<br>";
          ?>
        </div>
      </div>
      <!-- /.box-body -->
    </div>

    <?php
    if (count($users) > 0) {
      // si hay usuarios
    ?>
      <table class="table table-bordered table-hover">
        <h5>

          <?php if ($contador == 1) {
            echo $contador . " Resultado";
          } else {
            echo $contador . " Resultados";
          } ?>
          <thead>
            <th>Paciente</th>
            <th>Teléfono Paciente</th>
            <th>Médico</th>
            <th>Fecha/Hora</th>
            <th></th>
          </thead>
          <?php
          foreach ($users as $user) {

            $pacient  = $user->getPacient();
            $medic = $user->getMedic();
          ?>
            <tr>
              <td><?php echo $pacient->name; ?></td>
              <td><?php echo $pacient->tel; ?></td>
              <td><?php echo $medic->name; ?></td>
              <td><?php echo $user->nombre_dia . " " . $user->date_at; ?></td>

            </tr>
          <?php

          }
          ?>
      </table>

    <?php



    } else {
      echo "<p class='alert alert-danger'>No se encontrarón resultados</p>";
    }


    ?>
    <br><br><br><br><br><br><br><br><br><br>

  </div>
</div>
</div>
</div>