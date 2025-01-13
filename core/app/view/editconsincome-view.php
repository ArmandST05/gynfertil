
<div class="row">
	<div class="col-md-12">
	<h1>Editar Concepto</h1>
	<br>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="updatecatincome" action="index.php?view=updatecatincome" role="form">
     
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name"  value='<?php echo $_GET["name"];?>' autofocus placeholder="Nombre" autocomplete='Off'>
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="idCon" value="<?php echo $_GET["id"]?>">
      <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
  </div>
</form>
</div>
</div>
  </div>
</div>
