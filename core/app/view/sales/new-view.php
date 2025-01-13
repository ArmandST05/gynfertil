<?php
$pacients = PatientData::getAll();
?>
<script type="text/javascript">
  $(document).ready(function() {
    $("#id_paciente").select2({});
  });
</script>
<script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="assets/select2.min.css" rel="stylesheet" />
<script src="assets/select2.min.js"></script>

<div class="row">
  <div class="col-md-12">

    <h1>Seleccionar cliente</h1>
    <form class="form-horizontal" role="form" method="GET">
      <input type="hidden" name="view" value="sales/new-details">
      <div class="form-group">
        <div class="col-lg-3">
          <label for="inputEmail1" class="col-lg-3 control-label">Paciente</label>
          <select name="id_paciente" id="id_paciente" class="form-control" id="combobox" autofocus required>
            <option value="">-- SELECCIONE --</option>
            <?php foreach ($pacients as $p) : ?>
              <option value="<?php echo $p->id; ?>"><?php echo $p->id . " - " . $p->name ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-lg-3">
          <label for="inputEmail1" class="col-lg-3 control-label">Fecha</label>
          <input id="fecha" type="date" class="form-control" value="" name="fecha" required>

        </div>
        <div class="col-lg-3">
          <br>
          <button type="submit" class="btn btn-primary">Seleccionar</button>
        </div>
      </div>
  </div>

  </form>
</div>
</div>

</div>
<!--/.content-->
</div>
<!--/.span9-->
</div>