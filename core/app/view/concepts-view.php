<div class="row">
	<div class="col-md-12">
<div class="btn-group  pull-right"><a href="index.php?view=newconceptsincome" class="btn btn-default">Agregar concepto</a>

</div>
	<h1>Lista de conceptos</h1>
		<div class="clearfix"></div>
<?php

		$con = CategorySpend::getAllConIncome();
		if(count($con)>0){
			// si hay usuarios
			?>

			<table class="table table-bordered table-hover">
			<thead>
			<th>Nombre</th>
			<th>Tipo</th>
			<th style="width:80px;"></th>
			</thead>
			<?php
			foreach($con as $conc){
				?>
				<tr>
				
				<td><?php echo $conc->name; ?></td>
				<td><?php echo $conc->type; ?></td>
				<td style="width:80px;" class="td-actions">
				<a href="index.php?view=editconsincome&id=<?php echo $conc->id;?>&name=<?php echo $conc->name;?>" rel="tooltip" title="Editar" class="btn btn-simple btn-warning btn-xs"><i class='fas fa-pencil-alt'></i></a>
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