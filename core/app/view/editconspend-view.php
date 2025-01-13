    <?php 
     $user = CategorySpend::getByIdConSpend($_GET["id"]);
     $categories = CategorySpend::getAllCatSpend();
    
    ?>
<div class="row">
	<div class="col-md-12">
	<h1>Editar Concepto</h1>
	<br>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="updatecatspend" action="index.php?view=updatecatspend" role="form">
     
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name"  value='<?php echo $user->name;?>' autofocus placeholder="Nombre">
    </div>
  </div>

   <div class="form-group">
   <?php if($user->idCat==8){
   echo '<input type="hidden" name="cate" class="form-control" id="cate"  value='.$user->idCat.' autofocus';
   }
else{


   ?>
    <label for="inputEmail1" class="col-lg-2 control-label">Categoría gastos</label>
    <div class="col-md-6">
    <select name="cate" class="form-control">
    <option value="">-- SELECCIONE --</option>      
      <?php foreach($categories as $cat):?>
    <option value="<?php echo $cat->id; ?>" <?php if($user->idCat==$cat->id){ echo "selected"; }?>><?php echo $cat->name; ?></option>      
    <?php endforeach;?>
    </select>
    </div>
   <?php } ?>

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
