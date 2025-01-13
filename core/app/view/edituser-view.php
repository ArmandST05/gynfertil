<?php $user = UserData::getById($_GET["id"]);
$usua = UserData::get_tipo();
//echo md5($user->password);
?>

<div class="row">
  <div class="col-md-12">
  <h1>Editar Usuario</h1>
  <br>
		<form class="form-horizontal" method="post" id="addproduct" action="index.php?view=updateuser" role="form">
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" value="<?php echo $user->name;?>" class="form-control" id="name" placeholder="Nombre">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre de usuario*</label>
    <div class="col-md-6">
      <input type="text" name="username" value="<?php echo $user->username;?>" class="form-control" required id="username" placeholder="Nombre de usuario">
    </div>
  </div>

   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Contrase&ntilde;a</label>
    <div class="col-md-6">
      <input type="password" name="password" class="form-control" id="inputEmail1" placeholder="Contrase&ntilde;a">
<p class="help-block">La contrase&ntilde;a solo se modificará si escribes algo, en caso contrario no se modifica.</p>
    </div>
  </div>


 <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Tipo de usuario</label>
    <div class="col-md-6">
  <div>
    <label>
    <select class="form-control" name="tipo_usuario" id="tipo_usuario">
    <option value="">-- SELECCIONE --</option>      
    <?php foreach($usua as $use):?>
    <option value="<?php echo $use->nombre; ?>" <?php if($user->tipo_usuario==$use->nombre){ echo "selected"; }?>><?php echo $use->descripcion; ?></option>      
    <?php endforeach;?>
    </select>
  </label>
  </div>
    </div>
   </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
    <input type="hidden" name="user_id" value="<?php echo $user->id;?>">
      <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
    </div>
  </div>

</form>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  var tipoo=document.getElementById('tu').value;

  $('#tipo_usuario > option[value=tipoo]').attr('selected', 'selected');
});
</script>