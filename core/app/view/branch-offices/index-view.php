<div class="row">
	<div class="col-md-12">
		<a href="index.php?view=branch-offices/new" class="btn btn-default pull-right"><i class="fas fa-plus"></i> Nueva Sucursal</a>
		<h1>Lista de Sucursales</h1>
		<br>
		<?php

		$branchOffices = BranchOfficeData::getAll();
		if (count($branchOffices) > 0) {
		?>
			<table class="table table-bordered table-hover">
				<thead>
					<th>Nombre</th>
					<th>Activo</th>
					<th></th>
				</thead>
				<?php
				foreach ($branchOffices as $branchOffice) {
				?>
					<tr>
						<td><?php echo $branchOffice->name; ?></td>
						<td>
							<?php if ($branchOffice->is_active) : ?>
								<i class="glyphicon glyphicon-ok"></i>
							<?php endif; ?>
						</td>
						<td style="width:30px;"><a href="index.php?view=branch-offices/edit&id=<?php echo $branchOffice->id; ?>" class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> Editar</a></td>
						<?php if ($branchOffice->is_active) : ?>
							<td style="width:30px;"><a href="index.php?action=branch-offices/update-status&id=<?php echo $branchOffice->id; ?>&isActive=0" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i> Desactivar</a></td>
						<?php else : ?>
							<td style="width:30px;"><a href="index.php?action=branch-offices/update-status&id=<?php echo $branchOffice->id; ?>&isActive=1" class="btn btn-success btn-xs"><i class="fas fa-trash-restore"></i> Activar</a></td>
						<?php endif; ?>
					</tr>
			<?php

				}
				echo "</table>";
			} else {
				echo "<p class='alert alert-danger'>No hay sucursales registradas.</p>";
			}
			?>


	</div>
</div>