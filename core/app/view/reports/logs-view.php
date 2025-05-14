<?php
$startDate = (isset($_GET["startDate"])  ? $_GET['startDate'] : date("Y-m-d", strtotime("-7 days")));
$endDate = (isset($_GET["endDate"])  ? $_GET['endDate'] : date("Y-m-d"));
$branchOfficeId = isset($_GET["branchOfficeId"])  ? $_GET['branchOfficeId'] : 0;
$userId = isset($_GET["userId"])  ? $_GET['userId'] : 0;
$moduleId = isset($_GET["moduleId"])  ? $_GET['moduleId'] : 0;
$actionTypeId = isset($_GET["actionTypeId"])  ? $_GET['actionTypeId'] : 0;

$branchOffices = BranchOfficeData::getAllByStatus(1);
$users = UserData::getAll();
$logModules = LogModuleData::getAll();
$logActionTypes = LogActionTypeData::getAll();

$logs = LogData::getAllByDates($startDate, $endDate, $branchOfficeId, $userId, $moduleId, $actionTypeId);
?>

<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <h1>Reporte de acciones del sistema</h1>
      <form method="GET" action="index.php">
        <input type="hidden" name="view" value="reports/logs">
        <div class="row">
          <div class="col-md-3">
            <label class="control-label">Sucursal:</label>
            <select name="branchOfficeId" class="form-control" required>
              <option value="0">-- TODAS --</option>
              <?php foreach ($branchOffices as $branchOffice) : ?>
                <option value="<?php echo $branchOffice->id; ?>" <?php echo ($branchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="control-label">Usuario:</label>
            <div class="form-group">
              <select name="userId" class="form-control" required>
                <option value="0">-- TODOS --</option>
                <?php foreach ($users as $user) : ?>
                  <option value="<?php echo $user->id; ?>" <?php echo ($userId == $user->id) ? "selected" : "" ?>><?php echo $user->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <label class="control-label">Módulo:</label>
            <div class="form-group">
              <select name="moduleId" class="form-control" required>
                <option value="0">-- TODOS --</option>
                <?php foreach ($logModules as $module) : ?>
                  <option value="<?php echo $module->id; ?>" <?php echo ($moduleId == $module->id) ? "selected" : "" ?>><?php echo $module->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <label class="control-label">Tipo de acción:</label>
            <div class="form-group">
              <select name="actionTypeId" class="form-control" required>
                <option value="0">-- TODAS --</option>
                <?php foreach ($logActionTypes as $actionType) : ?>
                  <option value="<?php echo $actionType->id; ?>" <?php echo ($actionTypeId == $actionType->id) ? "selected" : "" ?>><?php echo $actionType->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <label for="inputEmail1" class="control-label">Desde:</label>
            <input type="date" name="startDate" value="<?php echo $startDate ?>" class="form-control">
          </div>
          <div class="col-md-3">
            <label for="inputEmail1" class="control-label">Hasta:</label>
            <input type="date" name="endDate" value="<?php echo $endDate ?>" class="form-control">
          </div>
          <div class="col-md-2">
            <br>
            <input type="submit" class="btn btn-sm btn-success btn-block" value="Procesar">
          </div>
          <?php if ($userType == "su") : ?>
            <div class="col-md-2">
              <br>
              <input type="button" class="btn btn-sm btn-primary btn-block" value="Exportar" id="btnExport" onclick="addLog(0,7,4,'Se descargó el archivo de Reporte de acciones del sistema')">
            </div>
          <?php endif; ?>
        </div>
        <div class="row">

        </div>
      </form>
    </div>
  </div>
  <br>
  <?php if (count($logs) > 0) : ?>
    <div class="row">
      <div class="col-md-12">
        <?php if (count($logs) > 0) : ?>
          <div class="clearfix"></div>
          <h3>Acciones realizadas</h3>
          <table class="table table-bordered table-hover" id='datosexcel' border='1'>
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Sucursal</th>
                <th>Usuario</th>
                <th>Descripción</th>
                <th>Módulo</th>
                <th>Acción</th>
              </tr>
            </thead>
            <?php
            $totalLogs = 0;
            foreach ($logs as $log) :
              $totalLogs++;
            ?>
              <tr class='success'>
                <td><?php echo $log->created_at_format ?></td>
                <td><?php echo $log->branch_office_name ?></td>
                <td><?php echo $log->user_name ?></td>
                <td><?php echo $log->description ?></td>
                <td><?php echo $log->module_name ?></td>
                <td><?php echo $log->action_type_name ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
          <h4 style="color:#2A8AC4">Total de acciones registradas: <?php echo $totalLogs ?></h4>

        <?php else : ?>
          <p class='alert alert-danger'>No se encontraron registros.</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</section>
<script type="text/javascript">
  $(document).ready(function() {

    $("#userId").select2({});

    $("#btnExport").click(function(e) {
      $("#datosexcel").btechco_excelexport({
        containerid: "datosexcel",
        datatype: $datatype.Table,
        filename: 'Reporte acciones del sistema'
      });

    });
  });
</script>