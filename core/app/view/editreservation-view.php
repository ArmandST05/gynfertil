 <script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>
 <link href="assets/select2.min.css" rel="stylesheet" />
 <script src="assets/select2.min.js"></script>
 <?php
  $reservation = ReservationData::getById($_GET["id"]);

  $id_pac = $_GET["id"];
  $pacients = PatientData::getAll();
  $medics = MedicData::getAll();
  $pac = PatientData::estatus_paciente();

  $patients = PatientData::getAll_todo($_GET['id_paciente']);
  $patient = reset($patients);

  $color = ReservationData::getReservationAreas();

  $lab = MedicData::getlabarotario();

  $dia = substr($reservation->date_at, 8, 2);
  $mes = substr($reservation->date_at, 5, 2);
  $anio = substr($reservation->date_at, 0, 4);

  $hora = date('H:i', strtotime($reservation->time_at));
  $hora2 = date('H:i', strtotime($reservation->date_at_final));

  $D = PatientData::getAll_doc_A($_GET["id_paciente"]);

  if ($D->doctor == 1) {
    echo "<script> 
          window.location.href = './?view=editreservationdoc&id=" . $_GET["id"] . "';
        </script>";
  }
  ?>

 <link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">
 <div class="row">
   <div class="col-md-12">

     <div class="card">
       <div class="card-header" data-background-color="blue">
         <h4 class="title">Modificar Cita</h4>
       </div>
       <a href='index.php?view=patients/edit&id=<?php echo $_GET["id_paciente"] ?>' class='btn btn-info btn-xs'>Modificar Datos Paciente</a>

       <a href='./?action=deletereser&id=<?php echo $id_pac ?>' class='btn btn-danger btn-xs' onClick='return confirmar()'>Eliminar cita</a>
       <a href='./?view=sales/new-details&id_paciente=<?php echo $_GET["id_paciente"] ?>&idMed=<?php echo $reservation->medic_id; ?>&fecha=<?php echo $reservation->date_at ?>' class='btn btn-primary btn-xs'>Realizar Venta</a>

       <script>
         function confirmar() {
           var flag = confirm("¿Seguro quedeseas eliminar?");
           if (flag == true) {
             return true;
           } else {
             return false;
           }
         }
         $(document).ready(function() {
           $("#patientId").select2({});
           $("#alertEditedPatient").hide();
         });

         function selectPatient() {
           $("#patientDetails").hide();
           $("#alertEditedPatient").show();
         }

         function clearPatientDetail() {
           $("#patientName").text("");
           $("#patientAddress").text("");
           $("#patientPhone").text("");
           $("#patientAlternativePhone").text("");
           $("#patientEmail").text("");
           $("#patientBirthday").text("");
           $("#patientAge").text("");
           $("#patientReference").text("");
         }

         $("#formEdit").submit(function() {
           $('#btnSubmit').attr("disabled", true);
           setTimeout(function() {
             $('#btnSubmit').attr("disabled", false)
           }, 1500);
         });
       </script>


       <div class="card-content">
         <div class="box box-primary" id="patientDetails">
           <div class="box-header with-border">
             <h3 class="box-title">Datos del Paciente</h3>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
             <div class="col-md-3">
               <img class="profile-user-img img-responsive img-circle" id="patientImage" src='<?php echo ($patient->image) ? "storage_data/patients/" . $patient->image : "../../../assets/default_user.jpg" ?>' alt="Foto del paciente">
             </div>
             <div class="col-md-9">
               <b>Nombre completo: </b><label id="patientName"><?php echo $patient->name ?></label><br>
               <b>Dirección: </b><label id="patientAddress"><?php echo $patient->calle . " " . $patient->num . " " . $patient->col ?></label><br>
               <b>Teléfono: </b><label id="patientPhone"><?php echo $patient->tel ?></label><br><b>Teléfono alternativo:<label id="patientAlternativePhone"><?php echo $patient->tel2 ?></label><br>
                 <b>Email: </b><label id="patientEmail"><?php echo $patient->email ?></label><br>
                 <b>Fecha nacimiento: </b><label id="patientBirthday"><?php echo $patient->getBirthdayFormat() ?></label><br>
                 <b>Edad: </b><label id="patientAge"><?php echo $patient->getAge() ?></label><br>
                 <b>Referida: </b><label id="patientReference"><?php echo $patient->ref ?></label><br>
             </div>
           </div>
           <!-- /.box-body -->
         </div>
         <form class="form-horizontal" role="form" method="post" action="./?action=updatereservation" id="formEdit">
           <div class="form-group">
             <div class="col-lg-3">
               <label for="inputEmail1" class="col-lg-3 control-label">Paciente</label>
               <select name="pacient_id" id="patientId" class="form-control" onchange="selectPatient()" autofocus required>
                 <option value="">-- SELECCIONE --</option>
                 <?php foreach ($pacients as $p) : ?>
                   <option value="<?php echo $p->id; ?>" <?php if ($p->id == $reservation->pacient_id) {
                                                            echo "selected";
                                                          } ?>><?php echo $p->id . " - " . $p->name; ?></option>
                 <?php endforeach; ?>

               </select>
             </div>

             <div class="col-lg-3">
               <label for="inputEmail1" class="col-lg-3 control-label">Médico</label>
               <select name="medic_id" class="form-control" required>
                 <option value="">-- SELECCIONE --</option>
                 <?php foreach ($medics as $p) : ?>
                   <option value="<?php echo $p->id; ?>" <?php if ($p->id == $reservation->medic_id) {
                                                            echo "selected";
                                                          } ?>><?php echo $p->id . " - " . $p->name; ?></option>
                 <?php endforeach; ?>
               </select>
             </div>

             <div class="col-lg-3">
               <label for="inputEmail1" class="col-lg-3 control-label">Laboratorio</label>

               <select name="lab" class="form-control" required>
                 <option value="">-- SELECCIONE --</option>
                 <?php foreach ($lab as $l) : ?>
                   <option value="<?php echo $l->id; ?>" <?php if ($l->id == $reservation->laboratorio) {
                                                            echo "selected";
                                                          } ?>><?php echo $l->id . " - " . $l->nombre; ?></option>
                 <?php endforeach; ?>
               </select>
             </div>
           </div>

           <div class="form-group">

             <div class="col-lg-3">
               <label for="inputEmail1" class="col-lg-3 control-label">Fecha</label>
               <input type="date" name="cita" id="formfecha" class="form-control" value="<?php echo $anio . "-" . $mes . "-" . $dia ?>">
             </div>


             <div class="clearfix col-lg-3">
               <label for="inputEmail1" class="col-lg-3 control-label">H/ini</label>
               <input type="time" class="form-control" value="<?php echo $hora ?>" name="time_at" id="time_at" class="form-control">
             </div>



             <div class="clearfix col-lg-3">
               <label for="inputEmail1" class="col-lg-3 control-label">H/fin</label>
               <input type="time" class="form-control" value="<?php echo $hora2 ?>" name="time_at_final" id="time_at_final" class="form-control">


             </div>
             <!--label for="inputEmail1" class="col-lg-1 control-label">Negritas</label> <br-->

             <div class="col-lg-1">

               <input type="hidden" name="color_letra" id="color_letra" class="" value="1">
             </div>
           </div>


           <!-----BIEN-->
           <div class="form-group">


             <div class="col-lg-3">
               <label for="inputEmail1" class="col-lg-3 control-label">Clave</label>
               <select name="pac_est" class="form-control" required>
                 <option value="">-- SELECCIONE --</option>
                 <?php foreach ($pac as $pa) : ?>
                   <option value="<?php echo $pa->id; ?>" <?php if ($pa->id == $reservation->status_reser) {
                                                            echo "selected";
                                                          } ?>><?php echo $pa->id . " - " . $pa->nombre; ?></option>
                 <?php endforeach; ?>
               </select>
             </div>
             <input type="hidden" value="<?php echo  $_SESSION["user_id"]; ?>" name="user_id" id="user_id">
             <div class="col-lg-3">
               <label for="inputEmail1" class="col-lg-3 control-label">Cita</label>
               <select name="color" class="form-control" required>
                 <option value="">-- SELECCIONE --</option>
                 <?php foreach ($color as $c) : ?>
                   <option value="<?php echo $c->id; ?>" <?php if ($c->id == $reservation->id_col) {
                                                            echo "selected";
                                                          } ?>><?php echo $c->id . " - " . $c->descripcion; ?></option>
                 <?php endforeach; ?>
               </select>
             </div>
           </div>

           <div class="form-group">
             <div class="col-lg-9">
               <label for="inputEmail1" class="col-lg-1 control-label">Comentarios</label>
               <input class="form-control" name="note" value="<?php echo $reservation->note; ?>" placeholder="Campo para comentarios de hospital, medicos,etc">
             </div>

             <div class="col-lg-2">
               <br>
               <button type="submit" class="btn btn-default" id="btnSubmit">Actualizar</button>
             </div>
           </div>
           <div class="form-group" id="alertEditedPatient">
             <div class="col-lg-12">
               <div class="alert alert-warning">
                 <h5><i class="icon fas fa-exclamation-triangle"></i> Atención!</h5>
                 Has editado el paciente de la cita.
               </div>
             </div>
           </div>
           <input type="hidden" name="asistente" id="asistente" required class="form-control" Value="">
           <input type="hidden" name="id" value="<?php echo $reservation->id; ?>">

         </form>
       </div>
     </div>
   </div>
 </div>