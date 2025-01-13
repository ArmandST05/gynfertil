<?php
$date = (!empty($_GET["date"])) ? $_GET["date"] : date("Y-m-d");
$typePay = ProductData::getTypePayeX();
$det = CategorySpend::getByIdCatBuyId($_GET["id"]);
$typePdet = ProductData::getTypePayId($_GET["id"]);
$com = CategorySpend::getComen($_GET["id"]);

$total = 0;
$totalPay = 0;
?>
<div class="row">
  <div class="col-md-12">
    <h1>Gastos</h1>


    <?php
    $typePay = ProductData::getTypePayeX();
    $total = 0;
    $totalPay  = 0;

    $concepts = CategorySpend::getCatExpenseb();
    ?>

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


    <form method="post" action="index.php?view=addbuyupd" autocomplete="off">
      <div class="row">
        <div class="form-group">
                      <div class="col-lg-3">
            <label for="inputEmail1">Fecha gasto:</label>
            <input type="date" name="date" id="date" class="form-control" value="<?php echo $date; ?>">
          </div>
          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Medicamento/Conceptos</label>
            <select name="idCon" id="idCon" class="form-control" onchange="seleccion(this.value)" autofocus required>
              <option value="0">-- SELECCIONE --</option>
              <?php foreach ($concepts as $p) : ?>
                <option value="<?php echo $p->id; ?>"><?php echo $p->id . " - " . $p->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-2">
            <label for="inputEmail1" class="col-lg-3 control-label">Costo</label>
            <input type="number" step="any" class="form-control" autofocus name="cost" id="cost" required placeholder="Costo ...">
          </div>
          <div class="col-lg-2">
            <label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
            <input type="number" class="form-control" value="1" autofocus name="q" placeholder="Cantidad" required>
            <input type="hidden" class="form-control" value="<?php echo $_GET["id"] ?>" autofocus name="idBuy">
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

<h2>Lista</h2>
<table class="table table-bordered table-hover">
  <thead>
    <th style="width:30px;">ID</th>
    <th style="width:250px;">Concepto</th>
    <th style="width:250px;">Categoría</th>
    <th style="width:30px;">Costo</th>
    <th style="width:30px;">Cantidad</th>
    <th style="width:30px;">Total</th>
    <th></th>
  </thead>
  <?php foreach ($det as $c) :

    $concept = CategorySpend::getByIdCatBuy($c->product_id);
  ?>
    <tr>
      <td><?php echo $c->product_id; ?></td>
      <td><?php echo $concept->name; ?></td>
      <td><?php echo $concept->nameCat; ?></td>
      <td><b>$ <?php echo number_format($c->price, 2); ?></b></td>
      <td><?php echo $c->q; ?></td>
      <td><b>$ <?php echo number_format($c->price * $c->q, 2); ?></b></td>
      <?php $pt = $c->price * $c->q;
      $total += $pt; ?>
      <td style="width:30px;"><a href="index.php?view=delConBuy&idBuy=<?php echo $_GET["id"] . "&idCon=" . $c->id. "&date=" . $date; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
    </tr>

  <?php endforeach; ?>
</table>

<h2>Resumen</h2>

<form method="post" action="index.php?view=addbuyUpdP" autocomplete="off">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Forma de pago</label>
    <div class="col-lg-3">
      <select name="idTypePay" class="form-control" required>
        <option value="">-- SELECCIONE --</option>
        <?php foreach ($typePay as $type) : ?>
          <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-lg-2">
      <input type="text" name="money" required class="form-control" id="money" placeholder="Total">
    </div>
    <div class="col-lg-2">
      <input type="date" name="date" required class="form-control" id="date" value="<?php echo date("Y-m-d") ?>">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary"><i class=""></i> Agregar</button>
    </div>
  </div>
  <input type="hidden" id="idBuy" name="idBuy" value="<?php echo $_GET["id"] ?>" class="form-control" autocomplete='off'>
  <input type="hidden" name="dateBuy" value="<?php echo $date ?>" class="form-control" autocomplete='off'>


</form>
<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered">



      <table class="table table-bordered table-hover">
        <thead>
          <th style="">ID</th>
          <th style="">Forma de pago</th>
          <th style="">Total</th>
          <th></th>
        </thead>

        <?php foreach ($typePdet as $t) :
          $tPay = ProductData::getByIdTypePay($t->idTypePay);
        ?>
          <tr>
            <td st><?php echo $t->idTypePay; ?></td>
            <td><?php echo  $tPay->name; ?></td>
            <td><b>$ <?php $tp = $t->cash;
                      $totalPay += $tp;
                      echo number_format($tp, 2); ?></b></td>
            <td style="width:25px;"><a href="index.php?view=delPayBuy&idBuy=<?php echo $_GET["id"] . "&idP=" . $t->id. "&date=" . $date; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>

          </tr>

        <?php endforeach; ?>



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

<form method="post" class="form-horizontal" id="processbuyPay" action="index.php?view=processbuyPay">

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
    <div class="col-lg-6">
      <textarea class="form-control" name="note" rows="10" cols="50" placeholder="Comentarios"><?php echo $com->comentarios ?></textarea>
    </div>
    <div class="col-lg-offset-6 col-lg-10">
      <div class="checkbox">
        <label>
          <button class="btn btn-lg btn-primary"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Finalizar</button>
        </label>

        <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
        <input type="hidden" id="totalGen" name="totalGen" value="<?php echo $totalPay ?>" class="form-control">
        <input type="hidden" id="discount" name="discount" value="0" class="form-control">


      </div>
      <input type="hidden" id="idBuy" name="idBuy" value="<?php echo $_GET["id"] ?>" class="form-control" autocomplete='off'>
      <input type="hidden" id="dateBuy" name="dateBuy" value="<?php echo $date ?>" class="form-control">

    </div>
  </div>
</form>
<script>
  $("#processbuyPay").submit(function(e) {
    $("#dateBuy").val($("#date").val());
    money = $("#totalGen").val();
    total = $("#total").val();
    if (money > total) {
      alert("No se puede efectuar la operacion verifica tus cantidades");
      e.preventDefault();
    } else {

      //alert("Hola");
      if (go) {} else {
        e.preventDefault();
      }
    }
  });
</script>


<br><br><br><br><br>


</div>