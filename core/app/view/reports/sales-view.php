<?php
$startDate = (isset($_GET["startDate"])  ? $_GET['startDate'] : date("Y-m-d", strtotime("-7 days")));
$endDate = (isset($_GET["endDate"])  ? $_GET['endDate'] : date("Y-m-d"));
$branchOfficeId = (isset($_GET["branchOfficeId"]))  ? $_GET['branchOfficeId'] : 0;
$statusId = (isset($_GET["statusId"]))  ? $_GET['statusId'] : "all";

$paymentTypeId = isset($_GET["paymentTypeId"])  ? $_GET['paymentTypeId'] : "all";
$productId = isset($_GET["productId"])  ? $_GET['productId'] : "all";

$branchOffices = BranchOfficeData::getAllByStatus(1);
$paymentTypes = PaymentTypeData::getAll();
$products = array_merge(ProductData::getAllByTypeId(3), ProductData::getAllByTypeId(4), ProductData::getAllByTypeId(1)); //Insumos, Medicamentos y conceptos ingresos para venta

if ($_SESSION["typeUser"] == "r" || $_SESSION["typeUser"] == "co") { //Coordinador y recepcionista
  $startDate = date("Y-m-d");
  $endDate = date("Y-m-d");

  if ($statusId == "0") { //Si son todas las pendientes, obtener desde un año anterior
    $startDate = date("Y-m-d", strtotime("-364 days"));
    $sales = OperationData::getAllSalesByBranchOfficeDates($branchOfficeId, $startDate, $endDate, $paymentTypeId, $statusId, $productId);
  } elseif ($statusId == "all") { //Si son todas las ventas (mostrar las pendientes de un año anterior y las pagadas solamente del día de hoy)
    $pendingSales = OperationData::getAllSalesByBranchOfficeDates($branchOfficeId, date("Y-m-d", strtotime("-364 days")), $endDate, $paymentTypeId, "0", $productId);
    $liquidatedSales = OperationData::getAllSalesByBranchOfficeDates($branchOfficeId, $startDate, $endDate, $paymentTypeId, "1", $productId);
    $sales = array_merge($pendingSales, $liquidatedSales);
  } else {
    $sales = OperationData::getAllSalesByBranchOfficeDates($branchOfficeId, $startDate, $endDate, $paymentTypeId, $statusId, $productId);
  }
} else { //Administrador
  $sales = OperationData::getAllSalesByBranchOfficeDates($branchOfficeId, $startDate, $endDate, $paymentTypeId, $statusId, $productId);
}
?>

<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <h1>Reporte ventas</h1>
      <form method="GET" action="index.php">
        <input type="hidden" name="view" value="reports/sales">
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
          <?php if ($_SESSION["typeUser"] == "su") : ?>
            <div class="col-md-3">
              <label for="inputEmail1" class="control-label">Desde:</label>
              <input type="date" name="startDate" value="<?php echo $startDate ?>" class="form-control">
            </div>
            <div class="col-md-3">
              <label for="inputEmail1" class="control-label">Hasta:</label>
              <input type="date" name="endDate" value="<?php echo $endDate ?>" class="form-control">
            </div>
          <?php endif; ?>
          <div class="col-md-3">
            <label class="control-label">Estatus:</label>
            <select name="statusId" class="form-control" required>
              <option value="all" <?php echo ($statusId == "all") ? "selected" : "" ?>>-- TODAS --</option>
              <option value="0" <?php echo ($statusId == "0") ? "selected" : "" ?>>PENDIENTE</option>
              <option value="1" <?php echo ($statusId == "1") ? "selected" : "" ?>>PAGADO</option>
            </select>
          </div>
          <div class="col-md-3">
            <label>Tipo pago</label>
            <select name="paymentTypeId" class="form-control">
              <option value="all">-- TODOS --</option>
              <?php foreach ($paymentTypes as $paymentType) : ?>
                <option value="<?php echo $paymentType->id; ?>" <?php echo ($paymentTypeId == $paymentType->id) ? "selected" : "" ?>><?php echo $paymentType->name; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-6">
            <label for="inputEmail1" class="col-lg-3 control-label">Producto/Concepto:</label>
            <select name="productId" id="productId" class="form-control" required>
              <option value="all">-- TODOS --</option>
              <?php foreach ($products as $product) : ?>
                <option value="<?php echo $product->id; ?>" <?php echo ($productId == $product->id) ? 'selected' : '' ?>><?php echo $product->id . " - " . $product->name ?></option>
              <?php endforeach; ?>
            </select>
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
  <?php if (count($sales) > 0) : ?>
    <div class="row">
      <div class="col-md-12">
        <?php if (count($sales) > 0) : ?>
          <div class="clearfix"></div>
          <table class="table table-bordered table-hover" id='datosexcel' border='1'>
            <thead>
              <tr>
                <th>Folio</th>
                <th>Fecha venta</th>
                <th>Fecha cita</th>
                <th>Paciente</th>
                <th>Psicólogo</th>
                <th>Total</th>
                <th>Comentarios</th>
                <th>Pagado</th>
                <th>Estatus</th>
                <th>Liquidar</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <?php
            $totalSales = 0;
            foreach ($sales as $sale) :
              $reservation = ReservationData::getById($sale->reservation_id);
              if ($reservation) {
                $medic = MedicData::getById($reservation->medic_id);
              } else {
                $medic = null;
              }
              $totalPayment = OperationPaymentData::getTotalByOperationId($sale->id);
              $totalSales++;
              if ($sale->status_id == 1) {
                $statusName = "PAGADO";
                $rowClass = "success";
              } else {
                $statusName = "PENDIENTE";
                $rowClass = "danger";
              }
            ?>
              <tr class='<?php echo $rowClass ?>' id="tr-<?php echo $sale->id ?>">
                <td><?php echo $sale->id ?><br>
                  <?php if ($_SESSION['typeUser'] == "su" || $sale->status_id == 1) : ?>
                    <a target="_blank" href="index.php?view=sales/edit&id=<?php echo $sale->id ?>" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
                  <?php endif; ?>
                </td>
                <td><?php echo $sale->date_format ?></td>
                <td><?php echo ($reservation) ? $reservation->date_format : "" ?></td>
                <td><?php echo $sale->patient_name ?></td>
                <td><?php echo ($medic) ? $medic->name : "" ?></td>
                <td>$<?php echo number_format($sale->total, 2) ?></td>
                <td>
                  <div class="col-lg-3">
                    <textarea class="form-control form-control-sm" id="descriptionRow<?php echo $sale->id ?>" rows="4" <?php echo ($sale->status_id == 1) ? "disabled" : "" ?>><?php echo trim($sale->description) ?></textarea>
                  </div>
                </td>
                <td>$<?php echo number_format($totalPayment->total, 2) ?></td>
                <td><?php echo ($sale->status_id) ? "PAGADO" : "PENDIENTE" ?></td>
                <td>
                  <?php if ($sale->status_id == 0) : ?>
                    <div class="row">
                      <div class="col-lg-3">
                        <select id="paymentTypeLiquidateRow<?php echo $sale->id ?>" class="form-control form-control-sm paymentTypeLiquidateRow">
                          <?php foreach ($paymentTypes as $paymentType) : ?>
                            <option value="<?php echo $paymentType->id; ?>"><?php echo $paymentType->name; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-3">
                        <input type="number" class="form-control form-control-sm" id="totalLiquidateRow<?php echo $sale->id ?>" placeholder="Total" value="<?php echo ($sale->total - $totalPayment->total) ?>" readonly>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-3">
                        <input type="date" class="form-control form-control-sm" id="dateLiquidateRow<?php echo $sale->id ?>" placeholder="Fecha" value="<?php echo date('Y-m-d') ?>" max="<?php echo date('Y-m-d') ?>">
                      </div>
                    </div>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($sale->status_id == 0) : ?>
                    <button type="button" onclick="liquidateSale(<?php echo $sale->id ?>)" class="btn btn-xs btn-success"><i class="fa fa-dollar-sign"></i>Liquidar</button>
                    <!--<a target="_blank" href="index.php?action=sales/delete&id=<?php echo $sale->id ?>" class="btn btn-xs btn-danger" onClick="return confirmDelete()"><i class="glyphicon glyphicon-trash"></i></a>-->
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
          <h4 style="color:#2A8AC4">Cantidad ventas: <?php echo $totalSales ?></h4>

        <?php else : ?>
          <p class='alert alert-danger'>No se encontraron registros.</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

</section>
<script type="text/javascript">
  $(document).ready(function() {
    $("#productId").select2({});

    $(".paymentTypeLiquidateRow").val(4).change(); //Seleccionar transacción por defecto

    var dataTable = $('#datosexcel').DataTable({
      "language": {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "sSorting": false,

        "bSortable": false,
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        },

        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      },

      "ordering": true,
      "order": [
        [0, 'desc']
      ],
      "pageLength": 50
    });

    $("#userId").select2({});

    $("#btnExport").click(function(e) {
      $("#datosexcel").btechco_excelexport({
        containerid: "datosexcel",
        datatype: $datatype.Table,
        filename: 'Reporte ventas'
      });

    });

    function confirmDelete() {
      var flag = confirm("¿Seguro que deseas eliminar la venta?");
      if (flag == true) {
        return true;
      } else {
        return false;
      }
    }
  });

  function liquidateSale(saleId) {
    $.ajax({
      url: "./?action=sales/add-payment-liquidate", // json datasource
      type: "POST", // method, by default get
      data: {
        paymentType: $("#paymentTypeLiquidateRow" + saleId).val(),
        date: $("#dateLiquidateRow" + saleId).val(),
        total: $("#totalLiquidateRow" + saleId).val(),
        description: $("#descriptionRow" + saleId).val(),
        saleId: saleId
      },
      success: function(data) {
        Toast.fire({
          icon: 'success',
          title: 'Se liquidó la venta exitosamente.'
        });
        $("#tr-" + saleId).remove();
      },
      error: function() { // error handling
        Toast.fire({
          icon: 'error',
          title: 'Error al liquidar la venta. Recarga la página si persiste el error.'
        });
      }
    });
  }
</script>