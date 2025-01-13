<?php
$andrologyProcedures = AndrologyProcedureData::getAllProcedures();
?>
<div class="row">
	<div class="col-md-12">
		<h1>Andrología</h1>
		<div class="clearfix"></div>
		<?php if (count($andrologyProcedures) > 0) { ?>
			<table id="treatmentsTable" class="table table-bordered table-hover table-responsive">
				<thead>
					<th>Fecha</th>
					<th>Código</th>
					<th>Procedimiento</th>
					<th>Personal médico</th>
					<th>Paciente</th>
					<th>Pareja</th>
					<th style="width:80px;">Acciones</th>
				</thead>
				<?php
				foreach ($andrologyProcedures as $andrologyProcedure) {
					$patient = $andrologyProcedure->getPatient();
					//Obtener los datos de la pareja asignada en ese procedimiento
					$partner = $andrologyProcedure->getPartnerData()
				?>
					<tr>
						<td><?php echo $andrologyProcedure->getDateMonthFormat($andrologyProcedure->date); ?></td>
						<td><?php echo $andrologyProcedure->procedure_code; ?></td>
						<td><?php echo $andrologyProcedure->procedure_name; ?></td>
						<td><?php echo $andrologyProcedure->getMedic()->name ?></td>
						<td><?php echo $patient->name; ?></td>
						<td><?php echo $partner->name; ?></td>
						<td style="width:80px;">
							<?php if ($_SESSION['typeUser'] == "su" || ($_SESSION['typeUser'] == "an") || ($_SESSION['typeUser'] == "do" && $medicLogged->category_id == 8)) : ?>
								<a href="index.php?view=andrology-procedures/details&procedureId=<?php echo $andrologyProcedure->id; ?>" rel="tooltip" title="Capturar" class="btn btn-simple btn-primary btn-xs"><i class='fas fa-pencil-alt'></i> Capturar</a>
							<?php endif; ?>
							<a href='index.php?view=patients/record&id_paciente=<?php echo $patient->id; ?>' class='btn btn-default btn-xs'> Expediente paciente</a>
							<a target="_blank" href='index.php?view=andrology-procedures/procedure-<?php echo strtolower($andrologyProcedure->andrology_procedure_code) ?>-report&id=<?php echo $andrologyProcedure->id ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-user-md"></i> Reporte</a>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
		<?php
		} else {
			echo "<p class='alert alert-danger'>No hay procedimientos de andrología</p>";
		}
		?>
		</table>
	</div>
</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#treatmentsTable").DataTable({
			"ordering": false,
			language: {
				url: 'plugins/datatables/languages/es-mx.json'
			}
		});
	});
</script>