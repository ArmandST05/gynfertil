<script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="assets/select2.min.css" rel="stylesheet" />
<script src="assets/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#idCon").select2({});
  });

  function seleccion(valor) {

    //alert('Se selecciono el valor: ' + valor);
    $.ajax({
      type: "POST",
      url: "./?action=getPrecioBuy",
      data: "valor=" + valor,

      error: function() {
        alert("error petición ajax");
      },
      success: function(data) {

        $("#cost").val(data);

      }
    });
  }
</script>
<div class="row">
  <div class="col-md-12">
    <h1>Gastos</h1>

    <?php
    $date = (!empty($_GET["date"])) ? $_GET["date"] : date("Y-m-d");
    $typePay = ProductData::getTypePayeX();
    $total = 0;
    $totalPay  = 0;

    $concepts = CategorySpend::getCatExpenseb();
    ?>
    <form method="post" action="index.php?view=addbuy" autocomplete="off">
      <div class="row">
        <div class="form-group">
            <div class="col-lg-3">
            <label for="inputEmail1">Fecha gasto:</label>
            <input type="date" name="date" id="date" class="form-control" value="<?php echo $date; ?>">
          </div>
          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Conceptos</label>
            <select name="idCon" id="idCon" class="form-control" onchange="seleccion(this.value)" autofocus required>
              <option value="0">-- SELECCIONE --</option>
              <?php foreach ($concepts as $p) : ?>
                <option value="<?php echo $p->id; ?>"><?php echo $p->id . " - " . $p->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-2">
            <label for="inputEmail1" class="col-lg-3 control-label">Costo</label>
            <input type="number" class="form-control" step="any" autofocus name="cost" id="cost" required placeholder="Costo ...">

          </div>

          <div class="col-lg-2">
            <label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
            <input type="number" class="form-control" value="1" autofocus name="q" placeholder="Cantidad" required>

          </div>
          
          <div class="col-lg-2">
            <br>
              <input type="hidden" name="cad" value="<?php echo $date; ?>" class="form-control">
            <button type="submit" class="btn btn-primary">Agregar</button>
          </div>
        </div>
    </form>
  </div>
</div>

<?php if (isset($_SESSION["buy"])) :

?>
  <h2>Lista</h2>
  <table class="table table-bordered table-hover">
    <thead>
      <th style="width:30px;">ID</th>
      <th style="width:250px;">Concepto</th>
      <th style="width:250px;">Categoría</th>
      <th style="width:30px;">Cantidad</th>
      <th style="width:30px;">Costo</th>
      <th style="width:30px;">Total</th <th>
      </th>
    </thead>
    <?php foreach ($_SESSION["buy"] as $c) :
      $concept = CategorySpend::getByIdCatBuy($c["idCon"]);
    ?>
      <tr>
        <td><?php echo $c["idCon"]; ?></td>
        <td><?php echo $concept->name; ?></td>
        <td><?php echo $concept->nameCat; ?></td>
        <td><?php echo $c["q"]; ?></td>
        <!--td><?php echo $c["cad"]; ?></td-->

        <td><b>$ <?php echo number_format($c["cost"], 2); ?></b></td>
        <td><b>$ <?php echo number_format($c["cost"] * $c["q"], 2); ?></b></td>
        <?php $pt = $c["cost"] * $c["q"];
        $total += $pt; ?>
        <td style="width:30px;"><a href="index.php?view=clearbuy&idCon=<?php echo $concept->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
      </tr>

    <?php endforeach; ?>
  </table>

  <h2>Resumen</h2>

  <form method="post" action="index.php?view=addpaytobuy" autocomplete="off">

    <div class="form-group">
      <label for="inputEmail1" class="col-lg-2 control-label">Forma de pago</label>
      <div class="col-lg-4">
        <select name="idTypePay" class="form-control" required>
          <option value="">-- SELECCIONE --</option>
          <?php foreach ($typePay as $type) : ?>
            <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-lg-3">
        <input type="number" step="any" name="money" required class="form-control" id="money" placeholder="Total">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary"><i class=""></i> Agregar</button>
      </div>
    </div>
  </form>
  <div class="row">
    <div class="col-md-6">
      <table class="table table-bordered">
        <?php if (isset($_SESSION["typePBuy"])) : ?>

          <table class="table table-bordered table-hover">
            <thead>
              <th style="">ID</th>
              <th style="">Forma de pago</th>
              <th style="">Total</th>
              <th></th>
            </thead>

            <?php foreach ($_SESSION["typePBuy"] as $t) :
              $tPay = ProductData::getByIdTypePay($t["idType"]);
            ?>
              <tr>
                <td st><?php echo $t["idType"]; ?></td>
                <td><?php echo  $tPay->name; ?></td>
                <td><b>$ <?php $tp = $t["money"];
                          $totalPay += $tp;
                          echo number_format($tp, 2); ?></b></td>
                <td style="width:25px;"><a href="index.php?view=clearpayBu&idTypePay=<?php echo $tPay->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>

              </tr>

            <?php endforeach; ?>

          <?php endif; ?>

          </table>
          <?php if ($totalPay > 0) :

          ?>
            <div class="col-lg-4 col-md-offset-6">
              <input style="text-align:right;" type="text" id="totalGen1" name="totalGen1" value="<?php echo number_format($totalPay, 2) ?>" class="form-control">
            <?php endif; ?>
            </div>
    </div>


    <div class="col-md-6">
      <table class="table table-bordered">

        <td>
          <p>Total</p>
        </td>
        <td>
          <p><b>$ <?php echo number_format($total, 2); ?></b></p>
        </td>
        </tr>

      </table>
    </div>
  </div>

  <form method="post" class="form-horizontal" id="processbuy" action="index.php?view=processbuy">

    <div class="form-group">
      <div class="col-lg-offset-2 col-lg-10">
        <div class="checkbox">
          <label>
            <input name="is_oficial" type="hidden" value="1">
          </label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-9">
        <textarea class="form-control" name="note" rows="10" cols="50" placeholder="Comentarios"></textarea>
      </div>
      <div class="col-lg-offset-6 col-lg-7">
        <div class="checkbox">
          <label>
            <a href="index.php?view=clearbuyt" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
            <button class="btn btn-lg btn-primary"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Finalizar</button>
          </label>

          <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
          <input type="hidden" id="discount" name="discount" value="0" class="form-control">
          <input type="hidden" id="totalGen" name="totalGen" value="<?php echo $totalPay ?>" class="form-control">
          <input type="hidden" id="dateBuy" name="dateBuy" value="<?php echo $date ?>" class="form-control">

        </div>
      </div>
    </div>
  </form>
  <script>
    $("#processbuy").submit(function(e) {
      $("#dateBuy").val($("#date").val());
      discount = $("#discount").val();
      money = $("#totalGen").val();
      if (money > (<?php echo $total; ?> - discount)) {
        alert("No se puede efectuar la operacion verifica tus cantidades");
        e.preventDefault();
      } else {
        if (discount == "") {
          discount = 0;
        }
        //go = confitotalpagos = $("#totalGen").val();rm("Cambio: $"+(money-(<?php echo $total; ?> ) ) );
        if (go) {} else {
          e.preventDefault();
        }
      }
    });
  </script>
  </div>
  </div>

<?php endif; ?>
<br><br><br><br><br>



</div>