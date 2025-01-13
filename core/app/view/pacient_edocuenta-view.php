<div class="row">
  <div class="col-md-12">
<?php 
$pacients = PatientData::getAll();
$medics = MedicData::getAll();
$ti_user=isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null ;
$ti_usua =UserData::get_tipo_usuario($ti_user);
foreach ($ti_usua as $key) {
         $tipo=$key->tipo_usuario;
        }
?>
<h1>Estado de cuenta <?php echo $_GET["name"] ?></h1>
   <div class="clearfix"></div>


   <?php
    $contador=0;
    $users = ReservationData::getEdoCuenta($_GET["id_paciente"]);

    $contador=count($users);
    
    if(count($users)>0){
      // si hay usuarios
      ?>
     <table class="table table-bordered table-hover">
      <h5>
                    
        <?php if($contador==1){
         echo $contador." Resultado"; }
         else{ echo $contador." Resultados"; 
        } ?> 
      <thead>
      <th>Paciente</th>
      <th>Teléfono Paciente</th>
      <th>Médico</th>
      <th>Fecha/Hora</th>
      <th>Conceptos</th>
      <th>Total</th>   
      <th>Forma de pago</th>
      <th>Estatus</th>
      <th></th>
      </thead>
      <?php
      foreach($users as $user){
      
        $pacient  = $user->getPacient();
        $medic = $user->getMedic();
        $typeP = OperationData::getAllBySellPay($user->id);
        ?>
        <tr>
        <td><?php echo $pacient->name; ?></td>
        <td><?php echo $pacient->tel; ?></td>
        <td><?php echo $medic->name; ?></td>
        <td><?php echo $user->nombre_dia." ".$user->created_at ; ?></td>
        <td><?php  
    $typeC = OperationData::getAllByConcepts($user->id);
    foreach ($typeC as $key2) {
    $P = OperationData::getnamePro($key2->product_id);
    
    echo $key2->q." ".$P->name."<br>";
    if($P->type== "MEDICAMENTO"){
      //echo "entre";
      $med += $P->price_out * $key2->q;
    }
    } ?></td>
        <td><?php echo $user->total; ?></td>
        <td><?php   
        foreach ($typeP as $key) {
                     
                  echo "$key->tname: ".number_format($key->cash,2)."<br>";

                  if($key->tname=="T. DEBITO" || $key->tname=="T. CREDITO")
                  {

                    $tPa="Santander";
                    
                  }else if ($key->tname=="TRANSFERENCIA" || $key->tname=="CHEQUES" || $key->tname=="INST. VIDA" || $key->tname=="SEGUROS" || $key->tname=="STAR MED" || $key->tname=="OTROS"){
                     $tBa="Banco";
                  }else{
                      $tPa="NA";
                  }
                } ?></td>
        <td><?php 
        if($user->status==1){
                 echo '<td><b class="success">PAGADA</b></td>';
                 }else{
                echo '<td><b class="">PENDIENTE</b></td>';
                 } ?></td>
        </tr>
        <?php

    }
      ?>
      </table>
      
      <?php



    }else{
      echo "<p class='alert alert-danger'>No se encontrarón resultados</p>";
    }


    ?>
<br><br><br><br><br><br><br><br><br><br>

</div>
</div>
  </div>
</div>