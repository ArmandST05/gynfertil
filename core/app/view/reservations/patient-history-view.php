  <?php
	$user = UserData::getLoggedIn();
	$userType = $user->user_type;

	$patient = PatientData::getById($_GET["patientId"]);
	if (!$patient) {
		echo "<script>
				alert('El paciente no existe');
				window.location='index.php?view=patients/index';
			</script>";
	}
	$reservations = ReservationData::getByPatient($_GET["patientId"]);
	$totalReservations = count($reservations);

	?>
  <div class="row">
  	<div class="col-md-12">
  		<div class="card">
  			<div class="card-header" data-background-color="blue">
  				<h1>Historial citas <?php echo $patient->name ?></h1>
  			</div>
  			<div class="card-content">
  				<?php
					if ($totalReservations > 0) :
						// si hay resultados
					?>
  					<table class="table table-bordered table-hover table-responsive" id="table">
  						<h5>
  							<?php if ($totalReservations == 1) echo $totalReservations . " Resultado";
								else echo $totalReservations . " Resultados";
								?></h5>
  						<thead>
  							<th>Fecha/Hora</th>
  							<th>Paciente</th>
  							<th>Teléfono</th>
  							<th>Psicólogo</th>
  							<th>Familiar</th>
  							<th>Estatus</th>
  							<th>Acciones</th>
  						</thead>
  						<?php
							foreach ($reservations as $reservation) :
								$medic = $reservation->getMedic();
							?>
  							<tr>
  								<td><?php echo $reservation->day_name . " " . $reservation->date_at_format; ?></td>
  								<td><?php echo $patient->name; ?></td>
  								<td><?php echo $patient->cellphone; ?></td>
  								<td><?php echo $medic->name; ?></td>
  								<td><?php echo $patient->relative_name; ?></td>
  								<td><?php echo $reservation->getStatus()->name; ?></td>
  								<td style="width:240px;">
  									<?php if ($userType == "su") : ?>
  										<!--<a href="index.php?view=reservations/details&id=<?php echo $reservation->id ?>" class="btn btn-default btn-xs"><i class='fas fa-align-justify'></i> Detalles de la cita</a>-->
  										<a href="index.php?view=reservations/edit-patient&id=<?php echo $reservation->id ?>" class="btn btn-warning btn-xs"><i class='fas fa-pencil-alt'></i> Editar</a>
  										<button id="btnDeleteReservation" onclick="deleteReservation('<?php echo $reservation->id ?>')" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i> Eliminar</button>
  									<?php else : ?>
  										<?php if (date_create_from_format("Y-m-d", substr($reservation->date_at, 0, 10)) > date_create_from_format("Y-m-d",date("Y-m-d"))) : ?>
  											<button id="btnDeleteReservation" onclick="deleteReservation('<?php echo $reservation->id ?>')" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i> Eliminar</button>
  										<?php endif; ?>
  										<?php if (date_create_from_format("Y-m-d", substr($reservation->date_at, 0, 10)) >= date_create_from_format("Y-m-d",date("Y-m-d"))) : ?>
  											<a href="index.php?view=reservations/edit-patient&id=<?php echo $reservation->id ?>" class="btn btn-warning btn-xs"><i class='fas fa-pencil-alt'></i> Editar</a>
  										<?php endif; ?>
  									<?php endif; ?>
  								</td>
  							</tr>
  						<?php endforeach; ?>
  					</table>

  				<?php else : echo "<p class='alert alert-danger'>No se encontraron resultados</p>";
					endif; ?>
  			</div>
  		</div>
  	</div>
  </div>
  <script>
  	function deleteReservation(id) {
  		Swal.fire({
  			title: '¿Estás seguro?',
  			text: "¡No serás capaz de revertir esto!",
  			icon: 'warning',
  			showCancelButton: true,
  			confirmButtonColor: '#3085d6',
  			cancelButtonColor: '#d33',
  			cancelButtonText: 'Cancelar',
  			confirmButtonText: 'Sí, Eliminar'
  		}).then((result) => {
  			if (result.value) {
  				$.ajax({
  					url: "./?action=reservations/delete-reservation", // json datasource
  					type: "POST", // method, by default get
  					data: "id=" + id,
  					success: function() {
  						location.reload();
  					},
  					error: function() { // error handling
  						Swal.fire(
  							'Error',
  							'La cita no se ha podido eliminar.',
  							'error'
  						);
  					}
  				});
  			}
  		})
  	}
  </script>