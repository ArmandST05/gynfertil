<div class="row">
	<div class="col-md-12">
		<div class="btn-group  pull-right"><a href="index.php?view=companies/new" class="btn btn-default"><i class="fas fa-plus"></i> Agregar Empresa</a>
		</div>
		<h1>Lista de Empresas</h1>
		<div class="clearfix"></div>
		<?php

		$companies = CompanyData::getAll();
		if (count($companies) > 0):?>
			<table class="table table-bordered table-hover">
				<thead>
					<th>Nombre</th>
					<th style="width:80px;"></th>
				</thead>
				<?php
				foreach ($companies as $company) : ?>
					<tr>
						<td><?php echo $company->name ?></td>
						<td style="width:80px;" class="td-actions">
							<a href="index.php?view=companies/edit&id=<?php echo $company->id; ?>" rel="tooltip" title="Editar" class="btn btn-simple btn-warning btn-xs"><i class='fas fa-pencil-alt'></i></a>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			<p class='alert alert-danger'>No hay Empresas registradas.</p>
		<?php endif; ?>
		</table>
	</div>
</div>
</div>
</div>
<script type="text/javascript">
	function confirmar() {
		var flag = confirm("¿Seguro que deseas eliminar la especialidad?");
		if (flag == true) {
			return true;
		} else {
			return false;
		}
	}
</script>