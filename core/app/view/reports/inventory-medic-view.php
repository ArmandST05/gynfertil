<?php
$products = ProductData::getInventoryProducts();
//PRIORIDADES DE COLORES
//(1) #FF0000 POR CADUCAR 3 MESES
//(2) #FFFF00 PRÓXIMOS A CADUCAR 4 Y 5 MESES
//(3) #70AD47 CADUCIDAD SUPERIOR A 6 MESES
$expirationArrayColors = [1 => "#FF0000", 2 => "#FFFF00", 3 => "#70AD47"];
$month = ["00" => " ", "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

?>
<div class="row">
  <div class="pull-right col-md-3">
    <a target="_blank" href="index.php?view=reports/inventory-medic-excel" class="btn btn-primary btn-block"><i class="fas fa-file"></i> Exportar Excel</a>
  </div>
  <div class="col-md-12">
    <h1>Inventario Medicamento Doctoras</h1>
    <hr>
    <table border='1' class="table table-bordered table-hover">
      <thead bgcolor="#eeeeee" align="center">
        <tr>
          <th colspan="2">INDICADORES</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td bgcolor="#FF0000"></td>
          <td>POR CADUCAR 3 MESES</td>
        </tr>
        <tr>
          <td bgcolor="#FFFF00"></td>
          <td>PRÓXIMOS A CADUCAR 4 Y 5 MESES</td>
        </tr>
        <tr>
          <td bgcolor="#ED7D31"></td>
          <td>MEDICAMENTOS SIN MOVIMIENTO EN EL MES(VENTA MENOR A 5 PRODUCTOS)</td>
        </tr>
        <tr>
          <td bgcolor="#70AD47"></td>
          <td>CADUCIDAD SUPERIOR A 6 MESES</td>
        </tr>
      </tbody>
    </table>
    <hr>
    <table id='tableExcelData' border='1' class="table table-bordered table-hover stripe">
      <thead bgcolor="#eeeeee" align="center">
        <tr>
          <th>Precio</th>
          <th>Marca</th>
          <th>Nombre</th>
          <th>Presentación</th>
          <th>Caducidad</th>
          <th>Piezas</th>
          <th>Ventas mes</th>
        </tr>
      </thead>
      <?php
      foreach ($products as $product) :
        $stock = OperationData::getStockByProduct($product->id);
        $expirationDates = OperationData::getAllExpirationDatesByProduct($product->id);
        $totalSaleData = OperationData::getTotalSalesByProduct($product->id);
        $totalSale = ($totalSaleData) ? $totalSaleData->q : 0;

        //Obtiene las ventas realizadas del producto en el último mes
        $totalSalesLMData = OperationData::getTotalSalesByProductDates($product->id,(date("Y-m-d",strtotime("-1 months"))),date("Y-m-d"));
        $totalSalesLM = ($totalSalesLMData) ? $totalSalesLMData->total : 0;
        if($totalSalesLM <= 4){//Hubo ventas mínimas
          $colorTdSales = "#ED7D31";
        }else{//Sí hubo ventas
          $colorTdSales = "";
        }

        //FECHAS DE CADUCIDAD DE PRODUCTOS
        $stringExpirationDates = "";
        $expirationPriorityColor = ""; 
        $expirationKeysProduct = []; //Array de las prioridades de fecha de caducidad del producto
        $sumq = 0;
        $sumac = 0;
        $res = 0;
        $can = 0;

        //Ciclar fechas de caducidad de productos
        foreach ($expirationDates as $expirationDate) {
          $dateNow = date('Ym');
          $sumq = $sumq + $expirationDate->q; //Sumatoria de Cantidad del prodcuto en todas las fechas 

          //Sumatoria de cantidad es menor o igual a cantidad de la fecha ciclada
          if ($sumq <= $expirationDate->q) {
            //Calcular inventario actual, entradas - salidas
            $can = $expirationDate->q - $totalSale;

            if ($can > 0) {
              //Hay inventario
              $spanColor = "#000000";
              if ($expirationDate->difM >= 6) {
                $expirationKeysProduct[] = 3;//Prioridad
              }else if ($expirationDate->difM  == 4 || $expirationDate->difM == 5) {
                $expirationKeysProduct[] = 2;//Prioridad
              }else if($expirationDate->difM  <= 3){//Caducado o casi
                //$spanColor = "#C14600";
                $expirationKeysProduct[] = 1;//Prioridad
              }
              $stringExpirationDates .= "<span style='color:".$spanColor.";'><b> " . $can . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes] ." (".$expirationDate->lot.") " . $expirationDate->difM . " Meses</span><br>";
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
                $spanColor = "#000000";
                if ($expirationDate->difM >= 6) {
                  $expirationKeysProduct[] = 3;//Prioridad
                }else if ($expirationDate->difM  == 4 || $expirationDate->difM == 5) {
                  $expirationKeysProduct[] = 2;//Prioridad
                }else if($expirationDate->difM  <= 3){//Caducado o casi
                  //$spanColor = "#C14600";
                  $expirationKeysProduct[] = 1;//Prioridad
                }
                $stringExpirationDates .= "<span style='color:".$spanColor.";'><b> " . $canT . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes]." (".$expirationDate->lot.") " . $expirationDate->difM . " Meses</span><br>";
              }
            } else {
              $spanColor = "#000000";
              if ($expirationDate->difM >= 6) {
                $expirationKeysProduct[] = 3;//Prioridad
              }else if ($expirationDate->difM  == 4 || $expirationDate->difM == 5) {
                $expirationKeysProduct[] = 2;//Prioridad
              }else if($expirationDate->difM  <= 3){//Caducado o casi
                //$spanColor = "#C14600";
                $expirationKeysProduct[] = 1;//Prioridad
              }
              $stringExpirationDates .= "<span style='color:".$spanColor.";'><b> " . $expirationDate->q . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes]." (".$expirationDate->lot.") " . $expirationDate->difM . " Meses</span><br>";
            }
          }
        }
        //Definir el color de la celda de fechas de expiración, se selecciona el de mayor prioridad
        if($expirationKeysProduct){
          $expirationPriorityColor = $expirationArrayColors[min($expirationKeysProduct)];
        }else{
          $expirationPriorityColor = "#FFFFFF";
        }
      ?>
        <tr>
          <td>$<?php echo number_format($product->price_out, 2) ?></td>
          <td bgcolor="<?php echo $colorTdSales?>"><?php echo $product->brand ?></td>
          <td bgcolor="<?php echo $colorTdSales?>"><?php echo $product->name ?></td>
          <td bgcolor="<?php echo $colorTdSales?>"><?php echo $product->presentation ?></td>
          <td bgcolor="<?php echo $expirationPriorityColor ?>"><?php echo $stringExpirationDates; ?></td>
          <td bgcolor="<?php echo $colorTdSales?>"><?php echo $stock ?></td>
          <td bgcolor="<?php echo $colorTdSales?>"><?php echo $totalSalesLM ?></td>
        </tr>
      <?php endforeach; ?>
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
      "pageLength": 100,
      "ordering": false,
      "responsive": true,
      "scrollX": true
    });
  });
</script>