<?php
$branchOffice = BranchOfficeData::getById($_GET["id"]);
?>

<div class="row">
  <div class="col-md-12">
    <h1>Editar Sucursal</h1>
    <br>
    <form class="form-horizontal" method="post" action="index.php?action=branch-offices/update" role="form">
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
        <div class="col-md-6">
          <input type="text" name="name" value="<?php echo $branchOffice->name; ?>" class="form-control" id="name" placeholder="Nombre">
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <input type="hidden" name="id" value="<?php echo $branchOffice->id; ?>">
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </div>

    </form>
  </div>
</div>
<script type="text/javascript">
</script>