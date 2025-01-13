<?php
$ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
$ti_usua = UserData::get_tipo_usuario($ti_user);
$me = MedicData::getByUserId($ti_user);

foreach ($ti_usua as $key) {
  $tipo2 = $key->tipo_usuario;
}

$fecha = isset($_GET["fecha"])  ? $_GET['fecha'] :  date('Y-m-d');

if ($tipo2 == "do") {
  $id_user = $me->id;
}

?>
<style>
  #delay1 {

    font-size: 24px;
    color: red;
  }
  p {
    color: #000;
  }
</style>

<body onload="mostrar();">
  <input type="hidden" onclick="mostrar();">
  <div class="row">
    <div class="col-md-16">
      <div class="card">
        <div class="card-header" data-background-color="blue">
          <h4 class="title">Seleccionar fechas</h4>
        </div>
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <input type="hidden" name="view" value="hometodo2">
            <div class="col-lg-2">
              <label for="" class="col-lg-3 control-label">Selecciona fecha1</label>
              <input id="fecha1" type="date" class="form-control" value="<?php echo $fecha ?>" name="fecha1">
            </div>
            <div class="col-lg-2">
              <div class="col-lg-4">
              </div>
              <label for="" class="col-lg-3 control-label">Selecciona fecha2</label>
              <input id="fecha2" type="date" class="form-control" value="<?php echo $fecha ?>" name="fecha2">
            </div>
            <div class="col-lg-2">

              <button type="submit" id="guardar_en" class="btn btn-info">Buscar</button>
            </div>
        </form>
      </div>

      <div class="card-content table-responsive">
        <div id="calendar"></div>


      </div>
    </div>
  </div>
  </div>