
<?php 
$reservation = ReservationData::getById($_GET["id"]);
$pacients = PatientData::getAllMedics();
$medics = MedicData::getAll();
$pac = PatientData::estatus_paciente();

$color = ReservationData::getReservationAreas();


$lab = MedicData::getlabarotario();

  $dia = substr($reservation->date_at, 8 , 2);
  $mes = substr($reservation->date_at, 5 , 2);
  $anio = substr($reservation->date_at,0,4);

  
    $hora = date('H:i', strtotime($reservation->time_at));
    $hora2 = date('H:i', strtotime($reservation->date_at_final));



?>


<link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">
<div class="row">
	<div class="col-md-12">

<div class="card">
  <div class="card-header" data-background-color="blue">
      <h4 class="title">Modificar Cita</h4>
  </div>
     <a href='./?action=deletereser&id=<?php echo $_GET["id"]?>' class='btn btn-danger btn-xs' onClick='return confirmar();' >Eliminar cita</a>
     <script>
                    function confirmar() {
                        var flag = confirm("¿Seguro quedeseas eliminar?");
                        if(flag==true){
                          return true;
                        }else{
                          return false;
                        }
                    }
                </script>
  <div class="card-content table-responsive">
<form class="form-horizontal" role="form" method="post" action="./?action=updatereservationdoc">
  <div class="form-group">
  
     <div class="col-lg-4">
<label for="inputEmail1" class="col-lg-3 control-label">Paciente</label>
<select name="pacient_id" class="form-control" id="combobox" required>
<option value="">-- SELECCIONE --</option>
  <?php foreach($pacients as $p):?>
    <option value="<?php echo $p->id; ?>" <?php if($p->id==$reservation->pacient_id){ echo "selected"; }?>><?php echo $p->id." - ".$p->name; ?></option>
  <?php endforeach; ?>
</select>
    </div>
  </div>

 
  <div class="form-group ">
     <div class="col-lg-3">
 <label for="inputEmail1" class="col-lg-3 control-label">Fecha</label>
      <input type="date" name="cita" id="formfecha"  class="form-control" value="<?php echo $anio."-".$mes."-".$dia ?>" >
     </div>
       
 <input type="hidden" value="<?php echo  $_SESSION["user_id"]; ?>" name="user_id" id="user_id" >


       <div class="clearfix col-lg-3">
      <label for="inputEmail1" class="col-lg-3 control-label">H/ini</label>
       <input type="time" class="form-control"  value="<?php echo $hora ?>"  name="time_at" id="time_at" class="form-control">
    </div>

 
   
     <div class="clearfix col-lg-3">
      <label for="inputEmail1" class="col-lg-3 control-label">H/fin</label>
                <input type="time" class="form-control"  value="<?php echo $hora2 ?>"  name="time_at_final" id="time_at_final" class="form-control">
               
      </div>  
    </div>
   
   
  <div class="form-group">
   
    <div class="col-lg-9">
     <label for="inputEmail1" class="col-lg-1 control-label">Nota</label>
    <textarea class="form-control" name="note" placeholder="Nota"><?php echo $reservation->note;?></textarea>
    </div></div>
     <div class="form-group">
    <div class="col-lg-2">

    <input type="hidden" name="id" value="<?php echo $reservation->id; ?>">
      <button type="submit" class="btn btn-default" onclick="calcularfecha()">Actualizar Cita</button>
    </div>
   </div></div>
</form>

</div>
</div>
	</div>
</div>