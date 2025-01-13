<?php
$embryologyTreatments = PatientCategoryData::getAllEmbryologyProcedureTreatments();
if ($_SESSION['typeUser'] == "do") {
	$user = UserData::getLoggedIn();
	$medicLogged = MedicData::getByUserId($user->id);
}
?>
<div class="row">
	<div class="col-md-12">
		<h1>Embriología</h1>
		<div class="clearfix"></div>
		<?php if (count($embryologyTreatments) > 0) { ?>
			<table id="treatmentsTable" class="table table-bordered table-hover table-responsive">
				<thead>
					<th>Código</th>
					<th>Procedimiento</th>
					<th>Fecha de estimulación</th>
					<th>Paciente</th>
					<th>Pareja</th>
					<th>Estatus</th>
					<th style="width:80px;">Acciones</th>
				</thead>
				<?php
				foreach ($embryologyTreatments as $embryologyTreatment) {
					$patient = $embryologyTreatment->getPatient();
					//Obtener los datos de la pareja asignada en ese procedimiento
					$partner = $embryologyTreatment->getPartnerData();
				?>
					<tr>
						<td>
							<?php if ($embryologyTreatment->treatment_code) : ?>
								<?php echo $embryologyTreatment->treatment_code; ?>
							<?php else : ?>
								<button rel="tooltip" title="Generar código" class="btn btn-simple btn-primary btn-xs" onclick="generateCode('<?php echo $embryologyTreatment->id ?>','<?php echo $patient->name ?>')"> Generar código</button>
							<?php endif; ?>
						</td>
						<td><?php echo $embryologyTreatment->treatment_name; ?></td>
						<td><?php echo $embryologyTreatment->getDateMonthFormat($embryologyTreatment->start_date); ?></td>
						<td><?php echo $patient->name; ?></td>
						<td><?php echo $partner->name; ?></td>
						<td><?php echo $embryologyTreatment->getTreatmentStatus()->name;
							if ($embryologyTreatment->treatment_status_id == 4) {
								if (isset($embryologyTreatment->pregnancy_test_date)) { //Si hubo transferencia
									echo ($embryologyTreatment->pregnancy_test_result == 1) ? " - SE EMBARAZÓ" : " - NO SE EMBARAZÓ";
								} else { //No hubo transferencia
									echo " - SIN TRANSFERENCIA";
								}
							}
							?></td>
						<td style="width:80px;">
							<?php if ($embryologyTreatment->treatment_code) : ?>
								<?php if ($_SESSION['typeUser'] == "su" || ($_SESSION['typeUser'] == "do" && $medicLogged->category_id == 8)) : ?>
									<a href="index.php?view=embryology-procedures/details&treatmentId=<?php echo $embryologyTreatment->id; ?>" rel="tooltip" title="Capturar" class="btn btn-simple btn-primary btn-xs"><i class='fas fa-pencil-alt'></i> Capturar</a>
									<button onclick="updatePartner('<?php echo $embryologyTreatment->id ?>','<?php echo $patient->name ?>')" class="btn btn-simple btn-warning btn-xs">Sincronizar pareja</button>
								<?php endif; ?>
								<a href='index.php?view=patients/record&id_paciente=<?php echo $patient->id; ?>' class='btn btn-default btn-xs'> Expediente paciente</a>
								<a target="_blank" href='index.php?view=embryology-procedures/treatment-<?php echo strtolower($embryologyTreatment->embryology_procedure_code) ?>-patient-report&id=<?php echo $embryologyTreatment->id ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-male"></i>
									Reporte Paciente</a>
								<a target="_blank" href='index.php?view=embryology-procedures/treatment-<?php echo strtolower($embryologyTreatment->embryology_procedure_code) ?>-report&id=<?php echo $embryologyTreatment->id ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-user-md"></i>
									Reporte Médico</a>
							<?php else : ?>
								<?php if ($_SESSION['typeUser'] == "su" || ($_SESSION['typeUser'] == "do" && $medicLogged->category_id == 8)) : ?>
									<button onclick="updatePartner('<?php echo $embryologyTreatment->id ?>','<?php echo $patient->name ?>')" class="btn btn-simple btn-warning btn-xs">Sincronizar pareja</button>
								<?php endif; ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
		<?php
		} else {
			echo "<p class='alert alert-danger'>No hay procedimientos de embriología</p>";
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

	function confirmar() {
		var flag = confirm("¿Seguro que deseas eliminar el procedimiento de embriología?");
		if (flag == true) {
			return true;
		} else {
			return false;
		}
	}

	function generateCode(patientCategoryTreatmentId, patientName) {
		Swal.fire({
			title: '¿Deseas generar el código de embriología para el procedimiento de ' + patientName + '?',
			text: "Esta acción no se podrá revertir.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Aceptar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					type: "POST",
					url: "./?action=patientcategories/add-treatment-code",
					data: {
						patientCategoryTreatmentId: patientCategoryTreatmentId
					},
					success: function(data) {
						window.location.reload();
					},
					error: function() {
						Swal.fire(
							'Error',
							'Ha ocurrido un error al generar el código, intenta nuevamente.',
							'error'
						)
					}
				});
			}
		});
	}

	function updatePartner(patientCategoryTreatmentId, patientName) {
		Swal.fire({
			title: '¿Deseas actualizar los datos de la pareja de ' + patientName + '?',
			text: "Ten cuidado con esta acción, se sobreescribirá la información actual de la pareja.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Aceptar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					type: "POST",
					url: "./?action=patientcategories/update-patient-partner",
					data: {
						patientCategoryTreatmentId: patientCategoryTreatmentId
					},
					success: function(data) {
						window.location.reload();
					},
					error: function() {
						Swal.fire(
							'Error',
							'Ha ocurrido un error al generar el código, intenta nuevamente.',
							'error'
						)
					}
				});
			}
		});
	}
</script>