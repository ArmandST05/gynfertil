<div class="row">
	<div class="col-md-12">
<div class="btn-group  pull-right"><a href="index.php?view=newspecialty" class="btn btn-default">Agregar especialidad</a>

</div>
	<h1>Lista de especialidades</h1>
		<div class="clearfix"></div>
<?php

		$users = CategoryMedicData::getAll();
		if(count($users)>0){
			// si hay usuarios
			?>

			<table class="table table-bordered table-hover">
			<thead>
			<th>Nombre</th>
			<th style="width:80px;"></th>
			</thead>
			<?php
			foreach($users as $user){
				?>
				<tr>
				<td><?php echo $user->name." ".$user->lastname; ?></td>
				<td style="width:80px;" class="td-actions">
				<a href="index.php?view=editspe&id=<?php echo $user->id;?>" rel="tooltip" title="Editar" class="btn btn-simple btn-warning btn-xs"><i class='fas fa-pencil-alt'></i></a>
			    <a href="index.php?view=delspecialty&id=<?php echo $user->id;?>" rel="tooltip" title="Eliminar"  onClick='return confirmar()'  class=" btn-simple btn btn-danger btn-xs"><i class='fas fa-times'></i></a></td>
				</tr>
				<?php

			}
?>
</table>
<?php


		}else{
			echo "<p class='alert alert-danger'>No hay Especialidades</p>";
		}


		?>
</table>
</div>
</div>
	</div>
</div>
<script type="text/javascript">
  function confirmar() {
                   var flag = confirm("¿Seguro quedeseas eliminar la especialidad?");
                   if(flag==true){
                       return true;
                   }else{
                       return false;
                 }                        
                }
  </script>