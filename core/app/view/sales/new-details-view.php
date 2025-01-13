<?php
$id_paciente = isset($_GET["id_paciente"])  ? $_GET['id_paciente'] : "";
$idMed = isset($_GET["idMed"])  ? $_GET['idMed'] : "";
$idRes = isset($_GET["idRes"])  ? $_GET['idRes'] : "0";
$date = isset($_GET["fecha"])  ? $_GET['fecha'] : "";
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
$pro = ProductData::getLikeSell();

$pen = ProductData::getPendiente($_GET["id_paciente"]);

$total = 0;
$totalPayments = 0;
$patient = PatientData::getById($id_paciente);

$bankAccounts = BankAccountData::getAllByStatus(1);
?>
<script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="assets/select2.min.css" rel="stylesheet" />
<script src="assets/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#product_id").select2({});
  });

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
    <form method="post" action="index.php?view=addtocart" autocomplete="off">
      <div class="row">
        <div class="col-lg-9">
          <label for="inputEmail1" class="col-lg-3 control-label">Paciente</label>
          <input type="text" class="form-control" value="<?php echo $patient->name ?>" readonly>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <?php if (!empty($pen)) { ?>
            <p class="alert alert-warning">
            <?php
            foreach ($pen as $p) {
              echo "Pendiente por pagar, " . "<a href='./?view=sales/edit&id=$p->id'>LIQUIDAR</a><br>";
            }
          }
            ?></p>
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
              <input type="number" step="any" class="form-control" autofocus name="price" id="price" required placeholder="Costo ...">
            </div>

            <div class="col-lg-2">
              <label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
              <input type="number" class="form-control" value="1" autofocus name="q" placeholder="Cantidad" required>
            </div>

            <div class="col-lg-2">
              <br>
              <button type="submit" class="btn btn-primary">Agregar</button>
            </div>
            <input type="hidden" id="idRes" name="idRes" value="<?php echo $idRes ?>" class="form-control">
            <input type="hidden" id="idPac" name="idPac" value="<?php echo $id_paciente ?>" class="form-control">
            <input type="hidden" id="idMed" name="idMed" value="<?php echo $idMed ?>" class="form-control">
            <input type="hidden" id="fecha" name="fecha" value="<?php echo $date ?>" class="form-control">
        </div>

      </div>
  </div>
  </form>

  <?php if (isset($_SESSION["errors"])) : ?>
    <h2>Errores</h2>
    <p></p>
    <table class="table table-bordered table-hover">
      <tr class="danger">
        <th>Código</th>
        <th>Producto</th>
        <th>Mensaje</th>
      </tr>
      <?php foreach ($_SESSION["errors"]  as $error) :
        $product = ProductData::getById($error["product_id"]);
      ?>
        <tr class="danger">
          <td><?php echo $product->id; ?></td>
          <td><?php echo $product->name; ?></td>
          <td><b><?php echo $error["message"]; ?></b></td>
        </tr>

      <?php endforeach; ?>
    </table>
  <?php
    unset($_SESSION["errors"]);
  endif; ?>


  <!--- Carrito de compras :) -->
  <?php if (isset($_SESSION["cart"])) :
  ?>
    <div class="row">
      <div class="col-lg-12">
        <h3>Lista de venta</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <table class="table table-bordered table-hover">
          <thead>
            <th style="width:30px;">ID</th>
            <th style="width:250px;">Producto/Concepto</th>
            <th style="width:250px;">Tipo</th>
            <th style="width:30px;">Cantidad</th>
            <th style="width:85px;">Precio Unitario</th>
            <th style="width:100px;">Precio Total</th>
            <th></th>
          </thead>
          <?php foreach ($_SESSION["cart"] as $p) :
            $product = ProductData::getById($p["product_id"]);
          ?>
            <tr>
              <td st><?php echo $product->id; ?></td>
              <td><?php echo $product->name; ?></td>
              <td><?php echo $p["type"]; ?></td>
              <td><?php echo $p["q"]; ?></td>
              <td><b>$ <?php echo number_format($p["price"], 2); ?></b></td>
              <td><b>$ <?php $pt = $p["price"] * $p["q"];
                        $total += $pt;
                        echo number_format($pt, 2); ?></b></td>
              <td style="width:30px;"><a href="index.php?view=clearcart1&idRes=<?php echo $idRes ?>&product_id=<?php echo $product->id; ?>&id_paciente=<?php echo $id_paciente; ?>&idMed=<?php echo $idMed; ?>&fecha=<?php echo $date; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
            </tr>

          <?php endforeach; ?>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <h3>Resumen</h3>
      </div>
    </div>
    <div class="row">
      <label for="inputEmail1" class="col-lg-2 control-label">Nuevo Pago:</label>
    </div>
    <div class="row">
      <form method="POST" action="index.php?action=sales/add-cart-payment" autocomplete="off">
        <div class="form-group">
          <div class="col-lg-2">
            <select name="paymentTypeId" id="paymentTypeId" class="form-control" required>
              <option value="">-- TIPO PAGO --</option>
              <?php foreach ($paymentTypes as $type) : ?>
                <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-3" id="divAccountId">
            <select name="bankAccountId" class="form-control">
              <option value="0">-- NO. CUENTA --</option>
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
            <input type="text" name="money" required class="form-control" id="money" placeholder="Total">
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-primary"><i class=""></i> Agregar</button>
          </div>
          <input type="hidden" id="id_paciente" name="id_paciente" value="<?php echo $id_paciente ?>" class="form-control">
          <input type="hidden" id="idMed" name="idMed" value="<?php echo $idMed ?>" class="form-control">
          <input type="hidden" id="idRes" name="idRes" value="<?php echo $idRes ?>" class="form-control">
          <input type="hidden" id="fecha" name="fecha" value="<?php echo $date ?>" class="form-control">
        </div>
      </form>
    </div>
    <br>
    <div class="row">
      <div class="col-md-12">
        <?php if (isset($_SESSION["payments"])) : ?>
          <table class="table table-bordered table-hover">
            <thead>
              <th>Forma de pago</th>
              <th>Total</th>
              <th>No. cuenta</th>
              <th>Factura</th>
              <th>Acciones</th>
            </thead>
            <?php foreach ($_SESSION["payments"] as $payment) :
              $paymentType = ProductData::getByIdTypePay($payment["idType"]);
              $totalPayments += $payment["money"];
              $bankAccount = BankAccountData::getById($payment["bankAccountId"]);
            ?>
              <tr>
                <td><?php echo  $paymentType->name; ?></td>
                <td><b>$ <?php echo number_format($payment["money"], 2); ?></b></td>
                <td><?php echo ($bankAccount) ? $bankAccount->name : "NO APLICA" ?></td>
                <td>
                <?php if($payment["isInvoice"] == 1):?>
                  <button type="button" class="btn btn-xs btn-success" onclick="changeIsInvoicePayment(`<?php echo $payment['idType']?>`,0)">SÍ FACTURAR</button>
                <?php else:?>
                  <button type="button" class="btn btn-xs btn-danger" onclick="changeIsInvoicePayment(`<?php echo $payment['idType']?>`,1)">NO FACTURAR</button>
                <?php endif;?>
                </td>
                <td style="width:50px;" id="row<?php echo $payment["idType"] ?>">
                  <a href="index.php?action=sales/delete-cart-payment&idRes=<?php echo $idRes ?>&idTypePay=<?php echo $paymentType->id; ?>&id_paciente=<?php echo $id_paciente; ?>&idMed=<?php echo $idMed ?>&fecha=<?php echo $date ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php endif; ?>
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
        <form method="POST" class="form-horizontal" id="processSale" action="index.php?action=sales/add">
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
              <textarea class="form-control" name="note" rows="10" cols="50" placeholder="Comentarios"></textarea>
            </div>
            <div class="col-lg-offset-6 col-lg-10">
              <div class="checkbox">
                <label>
                  <a href="index.php?view=clearcart&idRes=<?php echo $idRes ?>&id_paciente=<?php echo $id_paciente ?>&idMed=<?php echo $idMed ?>&fecha=<?php echo $date ?>" class="btn btn-danger "><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
                  <button class="btn btn-primary"><i class="glyphicon glyphicon-usd"></i> Finalizar Venta</button>
                </label>
                <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
                <input type="hidden" id="id_paciente" name="id_paciente" value="<?php echo $id_paciente ?>" class="form-control">
                <input type="hidden" id="idMed" name="idMed" value="<?php echo $idMed ?>" class="form-control">
                <input type="hidden" id="idRes" name="idRes" value="<?php echo $idRes ?>" class="form-control">
                <input type="hidden" id="discount" name="discount" value="0" class="form-control">
                <input type="hidden" id="totalGen" name="totalGen" value="<?php echo $totalPayments ?>" class="form-control">
                <input type="hidden" id="fecha" name="date" value="<?php echo $date ?>" class="form-control">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        $("#divAccountId").hide();
        $("#divIsInvoice").hide();
      });

      $("#processSale").submit(function(e) {
        discount = $("#discount").val();
        money = $("#total").val();
        totalGen = $("#totalGen").val();

        if (money > (<?php echo $total; ?> - discount)) {
          alert("No se puede efectuar la operación, verifica tus cantidades");
          e.preventDefault();
        } else {
          if (discount == "") {
            discount = 0;
          }
          /************ Validar liquidado *************/
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

      function changeIsInvoicePayment(paymentTypeId, isInvoice) {
        $.ajax({
          type: "POST",
          url: "./?action=sales/update-cart-payment-invoice",
          data: {
            "paymentTypeId":paymentTypeId,
            "isInvoice":isInvoice
          },
          complete: function(data) {
            window.location.reload();
          }
        });
      }
    </script>

  <?php endif; ?>

</div>