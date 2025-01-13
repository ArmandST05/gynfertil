<?php
$clients = PersonData::getClients();
$hoy = date('Y-m-d');
?>
<?php if (isset($_GET["sd"])) {
  $fecha = $_GET["sd"];
} else {
  $fecha = $hoy;
}

$ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
$ti_usua = UserData::get_tipo_usuario($ti_user);

foreach ($ti_usua as $key) {
  $tipo = $key->tipo_usuario;
}
?>

<section class="content">
  <div class="row">
    <div class="col-md-12">
    </div>
    <div class="col-md-12">
      <form>
        <input type="hidden" name="view" value="sellcorteAsis">
        <div class="row">
          <div class="col-md-3">
            <input type="date" name="sd" value="<?php if (isset($_GET["sd"])) {
                                                  echo $_GET["sd"];
                                                } else {
                                                  echo $hoy;
                                                } ?>" class="form-control">
          </div>

          <div class="col-md-2">
            <input type="submit" class="btn btn-success btn-block" value="Procesar">
          </div>
          <div class="col-md-2">
            <a class="btn btn-primary btn-block" href="index.php?view=reports/corte-word&id=<?php echo $fecha; ?>">Imprimir Corte</a></li>
          </div>

          <?php if ($tipo == "su" || $tipo == "sub") {  ?>
            <div class="col-md-2">
              <input type="submit" class="btn btn-primary btn-block" value="Exportar Excel" id="btnExport">

            </div>
          <?php } ?>
        </div>

      </form>

    </div>
  </div>
  <br>
  <div class="row">

    <div class="col-md-12">


      <?php


      $corteA = SellData::getAllCorteAll($fecha);


      $ConEgre = SellData::getAllBuyDate($fecha);

      $ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
      $ti_usua = UserData::get_tipo_usuario($ti_user);
      ?>

      <div class="clearfix"></div>

      <h1>Cortes del día <?php if (isset($_GET["sd"])) {
                            echo $_GET["sd"];
                          } else {
                            echo $hoy;
                          } ?> </h1>
      <h3>Ingresos</h3><br>
      <table id="datosexcel" border='1' class="table table-bordered table-hover">
        <thead>
          <th>Folio</th>
          <th>Médico</th>
          <th>Nombre del paciente</th>
          <th>Comentarios</th>
          <th>Estatus</th>
          <th>Conceptos</th>
          <th>Forma de pago</th>
          <th>Total</th>

        </thead>

        <?php
        $tot = 0;
        $typeP = 0;
        $totalMedicines = 0;
        $ef = 0;
        $tc = 0;
        $td = 0;
        $ch = 0;
        $totalTransfers = 0;
        foreach ($corteA as $cor) {
          $tot += $cor->total;
          $medic = SellData::getAll_docCor($cor->idMedic);
          $medicName = ($medic) ? $medic->name : "";

          echo "
        <tr class='success'>
        <td>$cor->id</td>
                <td>$medicName</td>
                <td>$cor->name</td>
                <td>$cor->comentarios</td>";
          if ($cor->status == 1) {
            echo "<td>PAGADA</td>";
          } else {
            echo "<td>PENDIENTE</td>";
          }
          echo "<td>";
          $typeC = OperationData::getAllByConcepts($cor->id);
          foreach ($typeC as $key2) {
            $P = OperationData::getnamePro($key2->product_id);

            echo $key2->q . " " . $P->name . "<br>";
            
            if ($P->type == "MEDICAMENTO") {
            
              $totalMedicines += $key2->price * $key2->q;
            }
          }

          echo "</td>";

          echo "<td>";
          $typeP = OperationData::getAllBySellPay($cor->id);
          foreach ($typeP as $key) {

            echo $key->tname . ": " . number_format($key->cash, 2), "<br>";

            if ($key->tname == "EFECTIVO") {
              $ef += $key->cash;
            } else if ($key->tname == "T. DEBITO") {
              $td += $key->cash;
            } else if ($key->tname == "T. CREDITO") {
              $tc += $key->cash;
            } else if ($key->tname == "TRANSFERENCIA") {
              $totalTransfers += $key->cash;
            } else if ($key->tname == "CHEQUES") {
              $ch += $key->cash;
            }
          }

          echo "</td>
                <td>" . number_format($cor->total, 2) . "</td>
                </tr>";
        }



        echo "<tr><td></td><td><label>TOTAL GENERAL:</label></td><td></td><td></td><td></td><td></td><td></td><td class='success'><label>" . number_format($tot, 2) . "</label></td></tr>";
        ?>


      </table>

      <h3>Gastos</h3><br>
      <table class="table table-bordered table-hover" border='1' id="datosexcel" style="width:750px;">
        <thead>
          <th>Conceptos</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Total</th>

        </thead>

        <?php
        $totE = 0;
        foreach ($ConEgre as $conE) {
          $totE += $conE->price * $conE->q;
          $pro = SellData::getProducts($conE->product_id);
          echo "
        <tr class='danger'>
        <td>$pro->name</td>
        <td>$conE->price</td>
                <td>$conE->q</td>
                <td>" . number_format($conE->price * $conE->q, 2) . "</td>
        ";


          echo "</tr>";
        }

        echo "<tr><td><label>Total:</label></td><td></td><td></td><td class='danger'><label>" . number_format($totE, 2) . "</label></td></tr>";
        ?>


      </table>


      <?php

      echo "<table id='datosexcel' border='1' class='table table-bordered table-hover'>
<tr>
<td><b>EFECTIVO: " . number_format($ef, 2) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    SALIDAS: " . number_format($totE, 2) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    ENTREGA: " . number_format($ef - $totE, 2) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   MEDICAMENTO: " . number_format($totalMedicines, 2) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    TARJETA: " . number_format($tc + $td, 2) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TRANSFERENCIAS: " . number_format($totalTransfers, 2) . "</td>
</tr>
</table>";

      ?>
      <br><br><br><br><br><br><br><br><br><br>
    </div>
  </div>
  <script src="assets/jquery.btechco.excelexport.js"></script>
  <script src="assets/jquery.base64.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {

      $("#btnExport").click(function(e) {

        $("#datosexcel").btechco_excelexport({
          containerid: "datosexcel",
          datatype: $datatype.Table,
          filename: 'Cortes Asistente'
        });

      });

    });
  </script>