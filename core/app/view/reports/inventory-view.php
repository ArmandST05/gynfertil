<?php
$products = ProductData::getInventoryProducts();
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>

<div class="row">
  <div class="pull-right col-md-3">
    <input type="submit" class="btn btn-primary btn-block" value="Exportar" id="btnExport">
  </div>
  <div class="col-md-12">
    <!--div class="btn-group  pull-right">
	<a href="index.php?view=re" class="btn btn-default">Entradas de Medicamentos e insumos</a>
</div-->

    <h1>Inventario Medicamento</h1>
    <div class="clearfix"></div>
    <div class="col-md-8">
    </div>

    <hr>
    <table id='datosexcel' border='1' class="table table-bordered table-hover">
      <thead bgcolor="#eeeeee" align="center">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Disponible</th>
          <!--th>Mínimo</th-->
          <th>Tipo</th>
          <th>Fecha caducidad</th>

        </tr>
      </thead>
      <?php
      foreach ($products as $product) {
        $actualQuantity = OperationData::getStockByProduct($product->id);
        $expirationDates = OperationData::getAllExpirationDatesByProduct($product->id);
        $totalSaleData = OperationData::getTotalSalesByProduct($product->id);
        $totalSale = ($totalSaleData) ? $totalSaleData->q : 0;
      ?>
        <tr>
          <td><?php echo $product->id ?></td>
          <td><?php echo $product->name ?></td>
          <td><?php echo $actualQuantity ?></td>
          <!--td><?php echo $product->inventary_min ?></td-->
          <td><?php echo $product->type ?></td>

          <td>
            <?php
            $sumq = 0;
            $sumac = 0;
            $res = 0;
            $can = 0;

            //Ciclar fechas de productos
            foreach ($expirationDates as $expirationDate) {
              $dateNow = date('Ym');

              $month = ["00" => "PRUE", "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

              $sumq = $sumq + $expirationDate->q; //Sumatoria de Cantidad del prodcuto en todas las fechas 

              //Sumatoria de cantidad es menor o igual a cantidad de la fecha ciclada
              if ($sumq <= $expirationDate->q) {
                //Calcular inventario actual, entradas - salidas
                $can = $expirationDate->q - $totalSale;

                if ($can > 0) {
                  //Hay inventario
                  if ($expirationDate->difM > 3)
                    echo "<span style='color:#000000;'><b> " . $can . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  &nbsp&nbsp&nbsp" . $expirationDate->difM . " Meses</span><br>";
                  else //Diferencia negativa caducado o casi
                    echo "<span style='color:#C14600;'><b> " . $can . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  &nbsp&nbsp&nbsp" . $expirationDate->difM . " Meses</span><br>";
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
                    if ($expirationDate->difM > 3)
                      //No caducado
                      echo "<span style='color:#000000;'><b> " . $canT . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  &nbsp&nbsp&nbsp" . $expirationDate->difM . " Meses</span><br>";
                    else
                      //Caducado
                      echo "<span style='color:#C14600;'><b> " . $canT . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  &nbsp&nbsp&nbsp" . $expirationDate->difM . " Meses</span><br>";
                  }
                } else {
                  if ($expirationDate->difM > 3) //No caducado
                    echo "<span style='color:#000000;'><b> " . $expirationDate->q . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  &nbsp&nbsp&nbsp" . $expirationDate->difM . " Meses</span><br>";
                  else
                    //Caducado
                    echo "<span style='color:#C14600;'><b> " . $expirationDate->q . " </b> " . $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  &nbsp&nbsp&nbsp" . $expirationDate->difM . " Meses</span><br>";
                }
              }
            } ?>
          </td>
        </tr>
        <?php
        ?>

      <?php
      }
      ?>
    </table>
  </div>
</div>
</div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btnExport").click(function(e) {
      $("#datosexcel").btechco_excelexport({
        containerid: "datosexcel",
        datatype: $datatype.Table,
        filename: 'Reporte inventario'
      });
    });
  });
</script>