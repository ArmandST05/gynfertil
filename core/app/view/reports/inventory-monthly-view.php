<?php
setlocale(LC_TIME, 'es_ES.UTF-8');
$months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

$actualDate = date('Y-m-d');
$searchMonth = (isset($_GET["searchMonth"])) ? $_GET["searchMonth"] : date('m');
$searchYear = (isset($_GET["searchYear"])) ? $_GET["searchYear"] : date('Y');

$startDate = date($searchYear . "-" . $searchMonth . "-01");
$endDate = date("Y-m-t", strtotime($startDate));

$previousStartDate = date_create($startDate)->modify('-1 day')->format('Y-m-d');

$interval = new DateInterval('P1D');
$dateRange = new DatePeriod(date_create($startDate), $interval, date_create($endDate)->modify('+1 day'));

$products = ProductData::getInventoryProducts();
$totalProducts = count($products);

$maxLotNumber = 0; //Número máximo de fechas o lotes que tienen los productos, al determinar el mayor número es la cantidad de filas que se crearán
//OBTENER DATOS DE LOS PRODUCTOS (FECHAS DE CADUCIDAD) EN EL MES QUE SE CONSULTA EL REPORTE
foreach ($products as $product) {
  $productMaxLotNumber = 0;

  //Calcular stock de los productos y fechas de caducidad
  $stockActualMonth = OperationData::getStockByProductDate($product->id, $endDate); //Stock actual al final del mes
  $stockInitialMonth = OperationData::getStockByProductDate($product->id, $previousStartDate); //Stock con el que se inició el mes
  $totalInputsMonthData = OperationData::getTotalInputsByProductDates($product->id, $startDate, $endDate); //Stock añadido en el transcurso del mes
  $totalInputsMonth = ($totalInputsMonthData) ? $totalInputsMonthData->total : 0;

  $expirationDates = OperationData::getExpirationDatesByProductMaxDate($product->id, $endDate);
  $totalSaleData = OperationData::getTotalSalesByProductMaxDate($product->id, $endDate);
  $totalSale = ($totalSaleData) ? $totalSaleData->q : 0;

  //Variables a utilizar en la tabla
  $product->stock = $stockActualMonth;
  $product->initial_stock = $stockInitialMonth;
  $product->added_month_stock = $totalInputsMonth;
  $product->total_sale = $totalSale;
  $product->lots = [];
  $product->week_sales = 0;
  $product->month_sales = 0;
  $product->week_inputs = 0;
  $product->month_inputs = 0;

  //FECHAS DE CADUCIDAD DE PRODUCTOS
  $sumq = 0;
  $sumac = 0;
  $res = 0;
  $can = 0;

  //Ciclar fechas de caducidad de productos
  foreach ($expirationDates as $expirationDate) {
    $dateNow = date('Ym');
    $sumq = $sumq + $expirationDate->q; //Sumatoria de Cantidad del producto en todas las fechas 

    //Sumatoria de cantidad es menor o igual a cantidad de la fecha ciclada
    if ($sumq <= $expirationDate->q) {
      //Calcular inventario actual, entradas - salidas
      $can = $expirationDate->q - $totalSale;

      if ($can > 0) {
        //Hay inventario
        $product->lots[] = array("lot" => $expirationDate->lot, "date" => $expirationDate->dateExpiry, "quantity" => $can);
        $productMaxLotNumber++;
      } else {
        //No hay en inventario
        $res = $res + $expirationDate->q;
        $r = $res - $totalSale;
      }
    } else if ($sumq >= $expirationDate->q) {
      //Sumatoria de cantidad es mayor o igual a cantidad de la fecha
      $sumac = $sumac + $expirationDate->q;
      $canT = $sumq - $totalSale;

      $tot = $sumq - $sumac;
      if ($canT <= $expirationDate->q) {
        if ($canT > 0) {
          //Hay inventario
          $product->lots[] = array("lot" => $expirationDate->lot, "date" => $expirationDate->dateExpiry, "quantity" => $canT);
          $productMaxLotNumber++;
        }
      } else {
        $product->lots[] = array("lot" => $expirationDate->lot, "date" => $expirationDate->dateExpiry, "quantity" => $expirationDate->q);
        $productMaxLotNumber++;
      }
    }
  }

  //Validar número de fechas/lotes
  if ($productMaxLotNumber > $maxLotNumber) {
    $maxLotNumber = $productMaxLotNumber;
  }
}
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<style>
  .minimum-content {
    font-size: 11px;
  }

  th.table-title {
    background-color: "#C55A11";
  }
</style>
<div class="row">
  <div class="col-md-12">
    <h1>Inventario Medicamentos Mensual</h1>
    <form>
      <input type="hidden" name="view" value="reports/inventory-monthly">
      <div class="row">
        <div class="col-md-2">
          <label>Mes</label>
          <select class="form-control" name="searchMonth">
            <?php foreach ($months as $indexMonth => $month) : ?>
              <option value="<?php echo $indexMonth ?>" <?php echo ($searchMonth == $indexMonth) ? "selected" : "" ?>><?php echo $month ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label>Año</label>
          <select class="form-control" name="searchYear">
            <?php for ($y = date("Y"); $y >= 2018; $y--) : ?>
              <option value="<?php echo $y; ?>" <?php echo ($searchYear == $y) ? "selected" : "" ?>>
                <?php echo $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-md-2">
          <br>
          <input type="submit" class="btn btn-success btn-block" value="Procesar">
        </div>
        <div class="col-md-2">
          <br>
          <a target="_blank" href="index.php?view=reports/inventory-monthly-excel&searchMonth=<?php echo $searchMonth ?>&searchYear=<?php echo $searchYear ?>" class="btn btn-primary btn-block"><i class="fas fa-file"></i> Exportar Excel</a>
        </div>
      </div>
    </form>
    <hr>
    <table id='tableExcelData' border='1' class="stripe row-border table table-bordered table-hover display compact" style="text-align: center;">
      <thead bgcolor="#eeeeee">
        <!--Lotes y fechas de caducidad -->
        <tr>
          <th colspan="2" rowspan="<?php echo (($maxLotNumber * 2) + 2) ?>" bgcolor="#93C47D"></th>
          <?php for ($i = 1; $i <= $totalProducts; $i++) : ?>
            <th bgcolor="#C55A11">PIEZAS</th>
            <th bgcolor="#C55A11">DETALLES</th>
          <?php endfor; ?>
        </tr>
        <?php for($i = 0; $i < $maxLotNumber;$i++):?>
        <tr>
          <?php foreach ($products as $product) :
          $productLotDate = "FECHA";
          $productLotQuantity = "";

          $lotsArray = $product->lots;
          if(isset($lotsArray[$i])){
            $productLot = $lotsArray[$i]['lot'];
            $productLotDate = $lotsArray[$i]['date'];
            $productLotQuantity = $lotsArray[$i]['quantity'];
          }
          ?>
            <td rowspan="2" bgcolor="#D9EAD3"><?php echo $productLotQuantity ?></td>
            <td class="minimum-content" bgcolor="#93C47D"><?php echo $productLotDate ?></td>
          <?php endforeach; ?>
        </tr>
        <tr>
          <?php foreach ($products as $product) : 
            $lotsArray = $product->lots;
            $productLot = "LOTE";
            if(isset($lotsArray[$i])){
              $productLot = $lotsArray[$i]['lot'];
            }
          ?>
            <td class="minimum-content" bgcolor="#93C47D"><?php echo $productLot ?></td>
          <?php endforeach; ?>
        </tr>
        <?php endfor;?>
        <!--Lista productos-->
        <tr>
          <?php foreach ($products as $product) : ?>
            <th colspan="2" bgcolor="#C55A11"><?php echo $product->name ?></th>
          <?php endforeach; ?>
        </tr>
        <tr>
          <th rowspan="4" bgcolor="#D9EAD3">Fecha</th>
          <th bgcolor="#93C47D">Inicio de mes</th>
          <?php foreach ($products as $product) : ?>
            <th bgcolor="#93C47D"><?php echo $product->initial_stock ?></th>
            <th rowspan="4" bgcolor="#D9EAD3">VENTA</th>
          <?php endforeach; ?>
        </tr>
        <tr>
          <th bgcolor="#FF9900">Total ingreso</th>
          <?php foreach ($products as $product) : ?>
            <th bgcolor="#FF9900"><?php echo $product->added_month_stock ?></th>
          <?php endforeach; ?>
        </tr>
        <tr>
          <th bgcolor="#FFE598">Existencias</th>
          <?php foreach ($products as $product) : ?>
            <th bgcolor="#FFE598"><?php echo $product->stock ?></th>
          <?php endforeach; ?>
        </tr>
        <tr>
          <th bgcolor="#000000"></th>
          <?php foreach ($products as $product) : ?>
            <th bgcolor="#9CC2E5">INGRESOS</th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($dateRange as $date) :
          $dayNumber = $date->format('N');
          $dateFormat = $date->format('Y-m-d');
        ?>
          <tr>
            <td bgcolor="#93C47D"><?php echo $date->format('d-m'); ?>
            <td bgcolor="#93C47D"></td>
            <?php foreach ($products as $product) : ?>
              <?php
              //Obtener venta del día
              $daySalesData = OperationData::getTotalSalesByProductDates($product->id, $dateFormat,  $dateFormat);
              $totalDaySales = ($daySalesData) ? $daySalesData->total : 0;

              if ($dayNumber == 1) { //Si es lunes, reiniciar subtotal semanal
                $product->week_sales = 0;
              }
              //Agregar subtotal a objeto de productos comenzar al inicio de una semana y mostrar total al final de la semana
              $product->week_sales += $totalDaySales;
              $product->month_sales += $totalDaySales;

              //Obtener entrada de producto por día
              $totalInputsDayData = OperationData::getTotalInputsByProductDates($product->id, $dateFormat, $dateFormat); //Stock añadido en el transcurso del mes
              $totalInputsDay = ($totalInputsDayData) ? $totalInputsDayData->total : 0;

              $product->week_inputs += $totalInputsDay;
              $product->month_inputs += $totalDaySales;
              ?>
              <td bgcolor="#93C47D"><?php echo $totalInputsDay ?></td>
              <td bgcolor="#93C47D"><?php echo $totalDaySales ?></td>
            <?php endforeach; ?>
          </tr>
          <?php if ($dayNumber == 7 || $dateFormat == $endDate) : ?>
            <tr>
              <td bgcolor="#FFD966"></td>
              <td bgcolor="#FFD966"></td>
              <?php foreach ($products as $product) : ?>
                <td bgcolor="#FFD966"><?php echo ""; ?></td>
                <td bgcolor="#FFD966"><?php echo $product->week_sales; ?></td>
              <?php endforeach; ?>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
        <tr>
          <td></td>
          <td></td>
          <?php foreach ($products as $product) : ?>
            <td><?php echo ""; ?></td>
            <td><?php echo $product->month_sales; ?></td>
          <?php endforeach; ?>
        </tr>
      <tbody>
    </table>
  </div>
</div>
</div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    var dataTable = $('#tableExcelData').DataTable({
      "language": {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
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
      "pageLength": 50,
      "ordering": false,
      "responsive": true,
      "scrollX": true,
      "scrollY": '200px',
      "scrollCollapse": true,
      "paging": false,
      "searching": false,
      fixedColumns: {
        left: 3,
      }
    });

    $("#btnExport").click(function(e) {
      $("#tableExcelData").btechco_excelexport({
        containerid: "tableExcelData",
        datatype: $datatype.Table,
        filename: 'Reporte inventario'
      });
    });
  });
</script>