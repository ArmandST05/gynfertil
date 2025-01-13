<div class="row">
	<div class="col-md-12">
<div class="btn-group  pull-right"><a href="index.php?view=newcatspend" class="btn btn-default">Agregar categoría gasto</a>

</div>
	<h1>Lista de categorías gastos</h1>
		<div class="clearfix"></div>
<?php

		$cat = CategorySpend::getAllCatSpend();
		if(count($cat)>0){
			// si hay usuarios
			?>

			<table class="table table-bordered table-hover">
			<thead>
			<th>Nombre</th>
			<th style="width:80px;"></th>
			</thead>
			<?php
			foreach($cat as $cate){
				?>
				<tr>
				<td><?php echo $cate->name; ?></td>
				<td style="width:80px;" class="td-actions">
				<a href="index.php?view=editcatspend&id=<?php echo $cate->id;?>" rel="tooltip" title="Editar" class="btn btn-simple btn-warning btn-xs"><i class='fas fa-pencil-alt'></i></a>
			  	</tr>
				<?php

			}
?>
</table>
<?php


		}else{
			echo "<p class='alert alert-danger'>No hay categorías</p>";
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