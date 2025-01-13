<div class="row">
	<div class="col-md-12">
		<div class="btn-group  pull-right">
			<a href="index.php?view=medics/new" class="btn btn-default"><i class="fas fa-plus"></i> Agregar personal médico</a>

		</div>
		<script type="text/javascript">
			function confirmar() {
				var flag = confirm("¿Seguro que deseas eliminar el doctor(@)?");
				if (flag == true) {
					return true;
				} else {
					return false;
				}
			}
		</script>
		<h1>Lista de Personal Médico</h1>
		<div class="clearfix"></div>

		<?php
		$medics = MedicData::getAll();
		if (count($medics) > 0) {
		?>
			<table class="table table-bordered table-hover">
				<thead>
					<th>Nombre completo</th>
					<th>Especialidad</th>
					<th>Tipo</th>
					<th></th>
				</thead>
				<?php
				foreach ($medics as $medic) : ?>
					<tr>
						<td><?php echo $medic->name ?></td>
						<td><?php echo ($medic->category_id != null) ? $medic->getCategory()->name : "" ?></td>
						<td><?php echo $medic->getType()->name ?></td>
						<td style="width:180px;">
							<a href="index.php?view=medics/edit&id=<?php echo $medic->id; ?>" class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> Editar</a>
							<?php
							/*echo"
				<a href='index.php?view=delmedi&id=$medic->id' class='btn btn-danger btn-xs onClick='return confirmar()'>Eliminar</a>";
                  */ ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php
		} else {
			echo "<p class='alert alert-danger'>No hay medicos</p>";
		}
		?>
	</div>
</div>
</div>
</div>