  <?php $cat = CategorySpend::getByIdCat($_GET["id"]);?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar categoría gastos</h1>
	<br>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" autocomplete='off' id="uptcatspend" action="index.php?view=uptcatspend" role="form">
   
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $cat->name;?>" class="form-control" id="name" placeholder="Nombre">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="idCat" value="<?php echo $cat->id;?>">
      <button type="submit" class="btn btn-primary">Actualizar categoría gastos</button>
    </div>
  </div>
</form>
</div>
</div>
  </div>
</div>
