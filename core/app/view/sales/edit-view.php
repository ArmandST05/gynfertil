<?php
$user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
$tipo = "";
$users = UserData::get_tipo_usuario($user);
foreach ($users as $key) {
  $tipo = $key->tipo_usuario;
}

if ($tipo == "su" || $tipo == "sub") {
  $paymentTypes = ProductData::getTypePay();
} else {
  $paymentTypes = ProductData::getTypePayeX();
}
$sale = SellData::getById($_GET["id"]);
$patient = PatientData::getById($sale->idPac);
$det = CategorySpend::getByIdCatBuyId($_GET["id"]);
$payments = ProductData::getTypeSellId($_GET["id"]);
$com = CategorySpend::getComen($_GET["id"]);
$pro = ProductData::getLikeSell();
$total = 0;
$totalPayments = 0;
$bankAccounts = BankAccountData::getAllByStatus(1);
?>
<script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="assets/select2.min.css" rel="stylesheet" />
<script src="assets/select2.min.js"></script>
<script type="text/javascript">
  function seleccion(valor) {
    $.ajax({
      type: "POST",
      url: "./?action=getPrecioSell",
      data: "valor=" + valor,

      error: function() {
        alert("error petición ajax");
      },
      success: function(data) {

        $("#price").val(data);

      }
    });
  }
</script>
<div class="row">
  <div class="col-md-12">
    <h1>Ventas</h1>
  </div>
  <div class="row">
    <div class="col-lg-9">
      <label for="inputEmail1" class="col-lg-3 control-label">Cliente</label>
      <input type="text" class="form-control" value="<?php echo $patient->name ?>" readonly>
    </div>
  </div>
  <form method="post" action="index.php?view=addsellupd" autocomplete="off">
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Medicamento/Conceptos</label>
            <select name="product_id" id="product_id" class="form-control" onchange="seleccion(this.value)" autofocus required>
              <option value="0">-- SELECCIONE --</option>
              <?php foreach ($pro as $p) : ?>
                <option value="<?php echo $p->id; ?>"><?php echo $p->id . " - " . $p->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Costo</label>
            <input type="text" class="form-control" autofocus name="price" id="price" required placeholder="Costo ...">
          </div>

          <div class="col-lg-2">
            <label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
            <input type="number" class="form-control" value="1" autofocus name="q" placeholder="Cantidad" required>
          </div>
          <div class="col-lg-2">
            <br>
            <button type="submit" class="btn btn-primary">Agregar</button>
          </div>
          <input type="hidden" id="idSell" name="idSell" value="<?php echo $_GET["id"] ?>" class="form-control" autocomplete='off'>
        </div>
      </div>
    </div>
  </form>
  <div class="row">
    <div class="col-lg-12">
      <table class="table table-bordered table-hover">
        <thead>
          <th style="width:30px;">ID</th>
          <th style="width:250px;">Concepto</th>
          <th style="width:30px;">Costo</th>
          <th style="width:30px;">Cantidad</th>
          <th style="width:30px;">Total</th>
          <th></th>
        </thead>
        <?php foreach ($det as $c) :
          $concept = CategorySpend::getByIdCatSell($c->product_id);
        ?>
          <tr>
            <td><?php echo $c->product_id; ?></td>
            <td><?php echo $concept->name; ?></td>
            <td><b>$ <?php echo number_format($c->price, 2); ?></b></td>
            <td><?php echo $c->q; ?></td>
            <td><b>$ <?php echo number_format($c->price * $c->q, 2); ?></b></td>
            <?php $pt = $c->price * $c->q;
            $total += $pt; ?>
            <td style="width:30px;"><a href="index.php?view=delConSell&idSell=<?php echo $_GET["id"] . "&idCon=" . $c->id; ?>" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
          </tr>

        <?php endforeach; ?>
      </table>
    </div>
  </div>
  <h3>Resumen</h3>
  <div class="row">
    <label for="inputEmail1" class="col-lg-2 control-label">Nuevo Pago:</label>
  </div>
  <div class="row">
    <form method="POST" action="index.php?action=sales/add-payment" autocomplete="off">
      <div class="form-group">
        <div class="col-lg-2">
          <select name="idTypePay" id="paymentTypeId" class="form-control" required>
            <option value="">-- TIPO PAGO --</option>
            <?php foreach ($paymentTypes as $paymentType) : ?>
              <option value="<?php echo $paymentType->id; ?>"><?php echo $paymentType->id . ": " . $paymentType->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-lg-3" id="divAccountId">
          <select name="bankAccountId" class="form-control">
            <option value="">-- NO. CUENTA --</option>
            <?php foreach ($bankAccounts as $bankAccount) : ?>
              <option value="<?php echo $bankAccount->id; ?>"><?php echo $bankAccount->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-lg-2" id="divIsInvoice">
          <div class="radio">
            <label>
              <input type="radio" name="isInvoice" id="isInvoiceTrue" value="1">
              SÍ facturar
            </label>
          </div>
          <div class="radio">
            <label>
              <input type="radio" name="isInvoice" id="isInvoiceFalse" value="0" checked>
              NO facturar
            </label>
          </div>
        </div>
        <div class="col-lg-2">
          <input type="number" name="money" required class="form-control" id="money" placeholder="Total">
        </div>
        <div class="col-lg-2">
          <input type="date" name="date" required class="form-control" id="date" placeholder="Fecha">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary"><i class=""></i> Agregar</button>
        </div>
      </div>
      <input type="hidden" id="idSell" name="idSell" value="<?php echo $_GET["id"] ?>" class="form-control" autocomplete='off'>
      <input type="hidden" name="total1" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
      <input type="hidden" id="totalGen2" name="totalGen2" value="<?php echo $totalPayments ?>" class="form-control">
    </form>
  </div>
  <br>
  <div class="row">
    <div class="col-md-6">
      <table class="table table-bordered table-hover">
        <thead>
          <th>Id</th>
          <th>Forma de pago</th>
          <th>Total</th>
          <th>No. cuenta</th>
          <th>Factura</th>
          <th>Acciones</th>
        </thead>
        <?php foreach ($payments as $payment) :
          $paymentTypeData = ProductData::getByIdTypePay($payment->idTypePay);
          $bankAccount = BankAccountData::getById($payment->bank_account_id);
          $totalPayments += $payment->cash;
        ?>
          <tr>
            <td><?php echo $payment->idTypePay; ?></td>
            <td><?php echo  $paymentTypeData->name; ?></td>
            <td><b>$<?php echo number_format($payment->cash, 2); ?></b></td>
            <td><?php echo ($bankAccount) ? $bankAccount->name : "NO APLICA" ?></td>
            <td>
              <?php if ($payment->is_invoice == 1) : ?>
                <button type="button" class="btn btn-xs btn-success" onclick="changeIsInvoicePayment(`<?php echo $payment->id ?>`,0)">SÍ FACTURAR</button>
              <?php else : ?>
                <button type="button" class="btn btn-xs btn-danger" onclick="changeIsInvoicePayment(`<?php echo $payment->id ?>`,1)">NO FACTURAR</button>
              <?php endif; ?>
            </td>
            <td style="width:25px;"><a href="index.php?action=sales/delete-payment&idSell=<?php echo $_GET["id"] . "&idP=" . $payment->id . "&total1=" . $total . "&totalGen2=" . $totalPayments; ?>" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i></a></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <table class="table table-bordered">
        <tr>
          <td><b>Total Venta:</b></td>
          <td><b>$ <?php echo number_format($total, 2); ?></b></td>
          <td><b>Pagado:<b></td>
          <td><b>$<?php echo number_format($totalPayments, 2) ?></b></td>
          <td><b>Saldo:</b></td>
          <td><b>$<?php echo ((floatval($total - $totalPayments)) < 0) ? "0.00" : number_format($total - $totalPayments, 2); ?></b></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <form method="POST" class="form-horizontal" id="processsellupt" action="index.php?view=processsellupt">
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
          <div class="col-lg-10">
            <textarea class="form-control" name="note" rows="10" cols="50" placeholder="Comentarios"><?php echo $com->comentarios ?></textarea>
          </div>
          <div class="col-lg-offset-8 col-lg-10">
            <div class="checkbox">
              <label>
                <button class="btn btn-primary"><i class="glyphicon glyphicon-usd"></i> Finalizar Venta</button>
              </label>
              <input type="hidden" name="total" value="<?php echo $total ?>" class="form-control" placeholder="Total">
              <input type="hidden" id="totalGen" name="totalGen" value="<?php echo $totalPayments ?>" class="form-control">
              <input type="hidden" id="discount" name="discount" value="0" class="form-control">
            </div>
            <input type="hidden" id="idSell" name="idSell" value="<?php echo $_GET["id"] ?>" class="form-control" autocomplete='off'>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $("#product_id").select2({});
      $("#divAccountId").hide();
      $("#divIsInvoice").hide();
    });

    $("#processsellupt").submit(function(e) {
      money = $("#totalGen").val();
      total = $("#total").val();
      money = $("#total").val();
      totalGen = $("#totalGen").val();
      if (money > (<?php echo $total; ?> - discount)) {
        alert("No se puede efectuar la operacion verifica tus cantidades");
        e.preventDefault();
      } else {
        if (discount == "") {
          discount = 0;
        }
        /************Valida liquidado *************/
        if (totalGen >= (<?php echo $total; ?>)) {
          go = confirm("Cambio: $" + (totalGen - (<?php echo $total; ?>)) + " Pesos");
        } else {
          go = confirm("Pendiente por pagar: $" + ((<?php echo $total; ?>) - totalGen) + " Pesos");

        }

        if (go) {} else {
          e.preventDefault();
        }
      }
    });

    $("#paymentTypeId").change(function() {
      $("#isInvoiceFalse").prop("checked", true);
      $("#bankAccountId").val("");
      if ($(this).val() == 2 || $(this).val() == 3 || $(this).val() == 10) { //Débito 4-Crédito 5- Transferencia 10
        $("#divAccountId").show();
        $("#divIsInvoice").show();
      } else {
        $("#divAccountId").hide();
        $("#divIsInvoice").hide();
      }
    });

    function changeIsInvoicePayment(paymentId, isInvoice) {
      $.ajax({
        type: "POST",
        url: "./?action=sales/update-payment-invoice",
        data: {
          "paymentId": paymentId,
          "isInvoice": isInvoice
        },
        complete: function(data) {
          window.location.reload();
        }
      });
    }
  </script>
</div>