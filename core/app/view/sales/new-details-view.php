<?php
$patientId = isset($_GET["patientId"])  ? $_GET['patientId'] : "";
$medicId = isset($_GET["medicId"])  ? $_GET['medicId'] : "";
$reservationId = isset($_GET["reservationId"])  ? $_GET['reservationId'] : "0";
$date = isset($_GET["date"])  ? $_GET['date'] : "";

$user = UserData::getLoggedIn();
$userType = $user->user_type;

$patient = PatientData::getById($patientId);
$paymentTypes = PaymentTypeData::getAll();
$products = array_merge(ProductData::getAllByTypeId(3), ProductData::getAllByTypeId(4), ProductData::getAllByTypeId(1)); //Insumos, Medicamentos y conceptos ingresos para venta
$pendingSales = OperationData::getBySaleStatusPatient(0, $patientId);

$total = 0;
$totalPayment = 0;

$patientTreatment = TreatmentData::getPatientActualTreatment($patientId);
$patientTreatmentId = (isset($patientTreatment)) ? $patientTreatment->id : null;
$treatmentName = null;
$treatmentDefaultPrice = null;
if ($patientTreatmentId) {
  $treatmentName = $patientTreatment->treatment_name;
  $treatmentDefaultPrice = $patientTreatment->default_price;
}
if ($reservationId) {
  $reservationData = ReservationData::getById($reservationId);
}
?>
<div class="row">
  <div class="col-md-12">
    <h1>Nueva Venta</h1>
    <div class="row">

      <div class="form-group">
        <?php if (!empty($pendingSales)) { ?>
          <p class="alert alert-warning">

          <?php
          foreach ($pendingSales as $p) {
            echo "Pendiente por pagar, " . "<a href='./?view=sales/edit&id=$p->id'>LIQUIDAR</a><br>";
          }
        }
          ?></p>
      </div>
    </div>
    <?php if ($reservationData) : ?>
      <div class="row">
        <div class="form-group">
          <div class="col-lg-4">
            <label class="control-label">Fecha de cita a cobrar:</label>
            <input type="text" class="form-control" value="<?php echo $reservationData->date_format ?>" readonly>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <div class="row">
      <div class="form-group">
        <div class="col-lg-4">
          <label class="control-label">Cliente:</label>
          <input type="text" class="form-control" autofocus name="cliente" id="cliente" required placeholder="Cliente" value="<?php echo $patient->name ?>" readonly>
        </div>
        <div class="col-lg-2">
          <label class="control-label">Tratamiento actual:</label>
          <input type="text" class="form-control" autofocus name="treatmentName" id="treatmentName" required placeholder="Tratamiento actual" value="<?php echo $treatmentName ?>" readonly>
        </div>
        <div class="col-lg-2">
          <label class="control-label">Precio predeterminado:</label>
          <input type="text" class="form-control" autofocus name="defaultPrice" id="defaultPrice" required placeholder="Precio predeterminado" value="<?php echo $treatmentDefaultPrice ?>" readonly>
        </div>
        <div class="col-lg-2">
          <label class="control-label">Fecha:</label>
          <input type="date" class="form-control" name="date" id="date" max="<?php echo date("Y-m-d") ?>" value="<?php echo substr($date, 0, 10) ?>" onchange="updateSaleDate()">
        </div>
      </div>
    </div>
    <form method="post" action="index.php?action=sales/add-cart-product" autocomplete="off">
      <div class="row">
        <div class="form-group">
          <div class="col-lg-4">
            <label for="inputEmail1" class="col-lg-3 control-label">Medicamento/Conceptos:</label>
            <select name="productId" id="productId" class="form-control" onchange="selectProduct(this.value)" autofocus required>
              <option value="0">-- SELECCIONE --</option>
              <?php foreach ($products as $p) : ?>
                <option value="<?php echo $p->id; ?>"><?php echo $p->id . " - " . $p->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-3">
            <label class="control-label">Costo:</label>
            <div class="input-group">
              <span class="input-group-addon">$</span>
              <input type="number" step="any" class="form-control" autofocus name="price" id="price" required placeholder="Costo">
            </div>
          </div>
          <div class="col-lg-3">
            <label for="inputEmail1" class="col-lg-3 control-label">Cantidad:</label>
            <input type="number" class="form-control" value="1" autofocus name="quantity" placeholder="Cantidad" required>
          </div>
          <div class="col-lg-2">
            <br>
            <button type="submit" class="btn btn-primary">Agregar</button>
          </div>
          <input type="hidden" name="reservationId" value="<?php echo $reservationId ?>" class="form-control">
          <input type="hidden" name="patientId" value="<?php echo $patientId ?>" class="form-control">
          <input type="hidden" name="medicId" value="<?php echo $medicId ?>" class="form-control">
          <input type="hidden" name="date" value="<?php echo $date ?>" class="form-control">
        </div>
      </div>
    </form>
    <div class="row">
      <div class="col-md-12">
        <?php if (isset($_SESSION["errors"])) : ?>
          <h3>Errores</h3>
          <p></p>
          <table class="table table-bordered table-hover">
            <tr class="danger">
              <th>Código</th>
              <th>Producto</th>
              <th>Mensaje</th>
            </tr>
            <?php foreach ($_SESSION["errors"]  as $error) :
              $product = ProductData::getById($error["id"]);
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

        <!--- Carrito de compras -->
        <?php if (isset($_SESSION["cart"])) : ?>
          <h3>Lista de venta</h3>
          <table class="table table-bordered table-hover">
            <thead>
              <th style="width:30px;">Id</th>
              <th style="width:250px;">Producto/Concepto</th>
              <th style="width:250px;">Tipo</th>
              <th style="width:30px;">Cantidad</th>
              <th style="width:85px;">Precio Unitario</th>
              <th style="width:100px;">Total</th>
              <th></th>
            </thead>
            <?php foreach ($_SESSION["cart"] as $productCart) :
              $product = ProductData::getById($productCart["id"]);
            ?>
              <tr>
                <td st><?php echo $product->id; ?></td>
                <td><?php echo $product->name; ?></td>
                <td><?php echo $productCart["typeName"]; ?></td>
                <td><?php echo $productCart["quantity"]; ?></td>
                <td><b>$<?php echo number_format($productCart["price"], 2); ?></b></td>
                <td><b>$<?php $totalProduct = $productCart["price"] * $productCart["quantity"];
                        $total += $totalProduct;
                        echo number_format($totalProduct, 2); ?>
                  </b>
                </td>
                <td style="width:30px;">
                  <a href="index.php?action=sales/delete-cart-product&reservationId=<?php echo $reservationId ?>&productId=<?php echo $product->id; ?>&patientId=<?php echo $patientId; ?>&medicId=<?php echo $medicId; ?>&date=<?php echo $date; ?>" class="btn btn-sm btn-danger">
                    <i class="glyphicon glyphicon-remove"></i> Cancelar
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
          <hr>

          <h3>Pagos</h3>
          <form method="POST" action="index.php?action=sales/add-cart-payment" autocomplete="off">
            <div class="form-group">
              <label for="inputEmail1" class="col-lg-2 control-label">Forma de pago:</label>
              <div class="col-lg-4">
                <select name="paymentType" class="form-control" required>
                  <option value="">-- SELECCIONE --</option>
                  <?php foreach ($paymentTypes as $paymentType) : ?>
                    <option value="<?php echo $paymentType->id; ?>"><?php echo $paymentType->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-lg-3">
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input type="number" name="quantity" step="any" required class="form-control" id="quantity" placeholder="Total">
                </div>
              </div>
              <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa plus"></i> Agregar pago</button>
              </div>
            </div>
            <input type="hidden" id="patientId" name="patientId" value="<?php echo $patientId ?>" class="form-control">
            <input type="hidden" id="medicId" name="medicId" value="<?php echo $medicId ?>" class="form-control">
            <input type="hidden" id="reservationId" name="reservationId" value="<?php echo $reservationId ?>" class="form-control">
            <input type="hidden" id="date" name="date" value="<?php echo $date ?>" class="form-control">
            <input type="hidden" id="totalSale" name="totalSale" value="<?php echo $total ?>" class="form-control">
          </form>
          <div class="row">
            <div class="col-md-6">
              <table class="table table-bordered">

                <?php if (isset($_SESSION["payments"])) : ?>
                  <table class="table table-bordered table-hover">
                    <thead>
                      <th>Id</th>
                      <th>Forma de pago</th>
                      <th>Total</th>
                      <th></th>
                    </thead>
                    <?php foreach ($_SESSION["payments"] as $payment) :
                      $paymentData = PaymentTypeData::getById($payment["id"]);
                    ?>
                      <tr>
                        <td><?php echo $payment["id"]; ?></td>
                        <td><?php echo  $paymentData->name; ?></td>
                        <td><b>$ <?php $quantity = $payment["quantity"];
                                  $totalPayment += $quantity;
                                  echo number_format($quantity, 2); ?></b></td>
                        <td style="width:25px;"><a href="index.php?action=sales/delete-cart-payment&reservationId=<?php echo $reservationId ?>&paymentTypeId=<?php echo $paymentData->id; ?>&patientId=<?php echo $patientId; ?>&medicId=<?php echo $medicId ?>&date=<?php echo $date ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>
                      </tr>

                    <?php endforeach; ?>

                  </table>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <td><b>Total Venta</b></td>
                  <td><b>$ <?php echo number_format($total, 2); ?></b></td>
                </tr>
                <tr>
                  <td><b>Pagado<b></td>
                  <td><b>$<?php echo number_format($totalPayment, 2) ?></b></td>
                </tr>
                <tr>
                  <td><b>Saldo</b></td>
                  <td><b>$<?php echo ((floatval($total - $totalPayment)) < 0) ? "0.00" : number_format($total - $totalPayment, 2); ?></b></td>
                </tr>
              </table>
            </div>
          </div>

          <form method="POST" class="form-horizontal" id="saveSale" action="index.php?action=sales/add">

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
                <textarea class="form-control" name="description" rows="10" cols="50" placeholder="Comentarios"></textarea>
              </div>
              <div class="col-lg-offset-6 col-lg-10">
                <div class="checkbox">
                  <label>
                    <a href="index.php?action=sales/delete-all-cart&reservationId=<?php echo $reservationId ?>&patientId=<?php echo $patientId ?>&medicId=<?php echo $medicId ?>&date=<?php echo $date ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar venta</a>
                    <button class="btn btn-primary"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Finalizar venta</button>
                  </label>

                  <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
                  <input type="hidden" id="patientId" name="patientId" value="<?php echo $patientId ?>" class="form-control">
                  <input type="hidden" id="medicId" name="medicId" value="<?php echo $medicId ?>" class="form-control">
                  <input type="hidden" id="reservationId" name="reservationId" value="<?php echo $reservationId ?>" class="form-control">
                  <input type="hidden" id="discount" name="discount" value="0" class="form-control">
                  <input type="hidden" id="totalPayment" name="totalPayment" value="<?php echo $totalPayment ?>" class="form-control">
                  <input type="hidden" id="date" name="date" value="<?php echo $date ?>" class="form-control">
                </div>
              </div>
            </div>
          </form>
          <script type="text/javascript">
            $("#saveSale").submit(function(e) {
              discount = $("#discount").val();
              money = $("#totalSale").val();
              totalPayment = $("#totalPayment").val();
              if (money <= 0) {
                alert("Ingresa productos a tu venta");
                e.preventDefault();
              } else if (money > (<?php echo $total; ?> - discount)) {
                alert("No se puede efectuar la operacion verifica tus cantidades");
                e.preventDefault();
              } else {
                if (discount == "") discount = 0;
                //Validar si se liquida
                if (totalPayment >= (<?php echo $total; ?>)) {
                  go = confirm("Cambio: $" + (totalPayment - (<?php echo $total; ?>)) + " Pesos");
                } else {
                  go = confirm("Pendiente por pagar: $" + ((<?php echo $total; ?>) - totalPayment) + " Pesos");

                }

                if (go) {} else {
                  e.preventDefault();
                }
              }
            });
          </script>

        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
<script type="text/javascript">
  $(document).ready(function() {
    $("#productId").select2({});
  });

  function selectProduct(value) {
    $.ajax({
      type: "POST",
      url: "./?action=products/get-product-price",
      data: "valor=" + value,

      error: function() {
        alert("Ocurrió un error al obtener el precio.");
      },
      success: function(data) {
        $("#price").val(data);
      }
    });
  }

  function updateSaleDate() {
    let actualDate = new Date();
    let actualHour = actualDate.getUTCHours() + ":" + actualDate.getUTCMinutes() + ":" + actualDate.getUTCSeconds();
    window.location.href = "index.php?view=sales/new-details&reservationId=" + "<?php echo $reservationId ?>" + "&patientId=" + "<?php echo $patientId ?>" + "&medicId=" + "<?php echo $medicId ?>" + "&date=" + $("#date").val() + " " + actualHour + "";
  }
</script>