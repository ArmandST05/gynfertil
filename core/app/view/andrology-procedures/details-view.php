<?php
$andrologyProcedure = AndrologyProcedureData::getPatientProcedureById($_GET["procedureId"]);
$andrologyProcedureId = $_GET["procedureId"];
$patient = $andrologyProcedure->getPatient();

//DATOS OFICIALES DEL PACIENTE Y SU PAREJA
$patientOfficialData = $patient->getPatientOfficialData(); //Cargar dato oficial del paciente (rfc,curp,pasaporte).

$procedureDetails = AndrologyProcedureData::getDetailsByProcedure($andrologyProcedure->andrology_procedure_id, $andrologyProcedureId);
$patientOfficialData = $patient->getPatientOfficialData(); 

//Obtener los datos de la pareja asignada en ese procedimiento
$partner = $andrologyProcedure->getPartnerData();
?>
<link rel="stylesheet" href="plugins/datatables/jquery.dataTables.min.css" />
<style>
  .select2-container--open {
    z-index: 99999999999999 !important;
  }
</style>
<div class="row">
  <div class="col-md-12">
    <h1><?php echo $andrologyProcedure->procedure_code ?></h1>
    <!-- /.box -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Datos del Paciente</h3>
        <div class="pull-right">
          <a target="_blank" href='index.php?view=andrology-procedures/procedure-<?php echo strtolower($andrologyProcedure->andrology_procedure_code) ?>-report&id=<?php echo $andrologyProcedureId ?>' class='btn btn-default btn-xs'><i class="far fa-file-alt"></i><i class="fas fa-user-md"></i> Reporte</a>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="col-md-3">
          <img class="profile-user-img img-responsive img-circle" src='<?php echo ($patient->image) ? "storage_data/patients/" . $patient->image : "../../../assets/default_user.jpg" ?>' alt="Foto del paciente">
        </div>
        <div class="col-md-5">
          <?php
          echo "<b>Nombre: </b>" . $patient->name . "<br>";
          echo "<b>" . $patientOfficialData->name. ": </b>" . $patientOfficialData->value . "<br>";
          echo "<b>Fecha nacimiento: </b>" . $patient->getBirthdayFormat() . "<br>";
          echo "<b>Edad: </b>" . $patient->getAge() . "<br>";
          echo "<b>Dirección: </b>" . $patient->calle . " " . $patient->num . " " . $patient->col . "<br>";
          echo "<b>Teléfono: </b>" . $patient->tel . " <br><b>Teléfono alternativo: </b>" . $patient->tel2 . "<br>";
          ?>
        </div>
        <div class="col-md-4">
          <?php
          echo "<b>Nombre pareja: </b>" . $partner->name . "<br>";
          echo "<b>" . $partner->officialDocumentName. ": </b>" . $partner->officialDocumentValue . "<br>";
          echo "<b>Fecha nacimiento: </b>" . $partner->birthdayFormat . "<br>";
          echo "<b>Edad: </b>" . $partner->age . "<br>";
          ?>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <?php
    include("procedure-" . strtolower($andrologyProcedure->andrology_procedure_code) . "-view.php");
    ?>
  </div>
</div>