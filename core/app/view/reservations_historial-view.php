  <?php
$nom=isset($_GET["bus"])  ? $_GET['bus'] : null ;
$pacients = PatientData::getAll();
$medics = MedicData::getAll();
$ti_user=isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null ;
$ti_usua =UserData::get_tipo_usuario($ti_user);
        foreach ($ti_usua as $key) {
           $tipo=$key->tipo_usuario;
         }
        ?>
<div class="row">
	<div class="col-md-12">
<div class="btn-group pull-right">
<script src="assets/jquery.searchable-1.0.0.min.js"></script>
<script type="text/javascript">

$(function () {
    $( '#table' ).searchable({
        striped: true,
        oddRow: { 'background-color': '#f5f5f5' },
        evenRow: { 'background-color': '#fff' },
        searchType: 'fuzzy'
    });
    
    $( '#searchable-container' ).searchable({
        searchField: '#container-search',
        selector: '.row',
        childSelector: '.col-xs-4',
        show: function( elem ) {
            elem.slideDown(100);
        },
        hide: function( elem ) {
            elem.slideUp( 100 );
        }
    })
});
</script>
</div>
<div class="card">
  <div class="card-header" data-background-color="blue">
      <h4 class="title"></h4>
  </div>
  <div class="card-content table-responsive">
  <form class="form-horizontal" role="form">
<input type="hidden" name="view" value="reservations">
      
  <div class="form-group">
   
    <div class="col-lg-2">
    </div>

  </div>
</form>
		<?php
	
		$users = ReservationData::getAll_filter_reservaciones_historial($nom);

		$contador=count($users);
		
		if(count($users)>0){
			// si hay usuarios
			?>
			<table class="table table-bordered table-hover" id="table">
			<h5>
                    
				<?php if($contador==1){
				 echo $contador." Resultado"; }
				 else{ echo $contador." Resultados"; 
				} ?>  </h5>
			<thead>
			<th>Paciente</th>
			<th>Teléfono Paciente</th>
			<th>Médico</th>
			<th>Esposo</th>
			<th>Fecha/Hora</th>
			<th></th>
			</thead>
			<?php
			foreach($users as $user){
				if($tipo=="su" || $tipo=="sub"){
				$pacient  = $user->getPacient();
				$medic = $user->getMedic();
				?>
				<tr>
				<td><?php echo $pacient->name; ?></td>
				<td><?php echo $pacient->tel; ?></td>
				<td><?php echo $medic->name; ?></td>
				<td><?php echo $pacient->relative_name; ?></td>
				<td><?php echo $user->nombre_dia." ".$user->date_at ; ?></td>
				<td style="width:240px;">
				<a href="index.php?view=editreservationenfermera&id=<?php echo $user->id?>" class="btn btn-default btn-xs">Cita</a>
				<a href="index.php?view=editreservation&id=<?php echo $user->id."&id_paciente=".$pacient->id;?>" class="btn btn-warning btn-xs">Editar</a>
				<a href="index.php?action=delreservation&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a>
				</td>
				</tr>
				<?php

			}else if($tipo=="a"){
				$pacient  = $user->getPacient();
				$medic = $user->getMedic();
				?>
				<tr>
				<td><?php echo $pacient->name; ?></td>
				<td><?php echo $pacient->tel; ?></td>
				<td><?php echo $medic->name; ?></td>
				<td><?php echo $pacient->relative_name; ?></td>
				<td><?php echo $user->nombre_dia." ".$user->date_at ; ?></td>
				<td style="width:100px;">
				<a href="index.php?view=editreservationenfermera&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Cita</a>
				</td>
				</tr>
				<?php
			}

			else{
				$pacient  = $user->getPacient();
				$medic = $user->getMedic();
				?>
				<tr>
				<td><?php echo $pacient->name; ?></td>
				<td><?php echo $pacient->tel; ?></td>
				<td><?php echo $medic->name; ?></td>
				<td><?php echo $pacient->relative_name; ?></td>
			    <td><?php echo $user->nombre_dia." ".$user->date_at ; ?></td>
				<td style="width:180px;">
				<a href="index.php?view=editreservation&id=<?php echo $user->id;?>" class="btn btn-warning btn-xs">Editar</a>
				<a href="index.php?action=delreservation&id=<?php echo $user->id;?>" class="btn btn-danger btn-xs">Eliminar</a>
			
				</td>
				</tr>
				<?php
			}

		}
			?>
			</table>
			
			<?php



		}else{
			echo "<p class='alert alert-danger'>No se encontrarón resultados</p>";
		}


		?>


	
			</div>
			</div>
		

	</div>
</div>