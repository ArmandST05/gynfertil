<?php
$paymentTypes = PaymentTypeData::getAll();
$sale = OperationData::getById($_GET["id"]);
$patient = PatientData::getById($sale->patient_id);
$details = OperationDetailData::getAllByOperationId($_GET["id"]);
$paymentDetails = OperationPaymentData::getByOperationId($_GET["id"]);
$products = array_merge(ProductData::getAllByTypeId(3), ProductData::getAllByTypeId(4), ProductData::getAllByTypeId(1)); //Insumos,Medicamentos y conceptos ingresos para venta
$total = 0;
$totalPay = 0;

$patientTreatment = TreatmentData::getPatientActualTreatment($sale->patient_id);
$patientTreatmentId = (isset($patientTreatment)) ? $patientTreatment->id : null;
$treatmentName = null;
$treatmentDefaultPrice = null;
if ($patientTreatmentId) {
  $treatmentName = $patientTreatment->treatment_name;
  $treatmentDefaultPrice = $patientTreatment->default_price;
}
if($sale->reservation_id){
  $reservationData = ReservationData::getById($sale->reservation_id);
}
?>
<div class="row">
  <div class="col-md-12">
    <h1>Editar venta</h1>
  </div>
  <?php if($reservationData):?>
  <div class="row">
    <div class="form-group">
      <div class="col-lg-4">
        <label class="control-label">Fecha de cita a cobrar:</label>
        <input type="text" class="form-control" value="<?php echo $reservationData->date_format ?>" readonly>
      </div>
    </div>
  </div>
  <?php endif;?>
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
    </div>
  </div>
  <form method="post" action="index.php?action=sales/add-product" autocomplete="off">
    <div class="form-group">
      <div class="col-lg-3">
        <label for="inputEmail1" class="col-lg-3 control-label">Productos/Conceptos</label>
        <select name="productId" id="productId" class="form-control" onchange="selectProduct(this.value)" autofocus required>
          <option value="0">-- SELECCIONE --</option>
          <?php foreach ($products as $product) : ?>
            <option value="<?php echo $product->id; ?>"><?php echo $product->id . " - " . $product->name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-lg-3">
        <label for="inputEmail1" class="col-lg-3 control-label">Costo</label>
        <input type="text" class="form-control" autofocus name="price" id="price" required placeholder="Costo ...">
      </div>
      <div class="col-lg-2">
        <label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
        <input type="number" class="form-control" value="1" autofocus name="quantity" placeholder="Cantidad" required>
      </div>
      <div class="col-lg-2">
        <br>
        <button type="submit" class="btn btn-primary">Agregar</button>
      </div>
      <input type="hidden" id="saleId" name="saleId" value="<?php echo $_GET["id"] ?>" class="form-control" autocomplete='off'>
    </div>
  </form>
  <table class="table table-bordered table-hover">
    <thead>
      <th style="width:30px;">ID</th>
      <th style="width:250px;">Concepto</th>
      <th style="width:30px;">Costo</th>
      <th style="width:30px;">Cantidad</th>
      <th style="width:30px;">Total</th>
      <th></th>
    </thead>
    <tbody>
      <?php foreach ($details as $detail) :
        $concept = ProductData::getById($detail->product_id);
      ?>
        <tr>
          <td><?php echo $detail->product_id; ?></td>
          <td><?php echo $concept->name; ?></td>
          <td><b>$<?php echo number_format($detail->price, 2); ?></b></td>
          <td><?php echo $detail->quantity; ?></td>
          <td><b>$<?php echo number_format($detail->price * $detail->quantity, 2); ?></b></td>
          <?php $pt = $detail->price * $detail->quantity;
          $total += $pt;
          ?>
          <td style="width:30px;"><a href="index.php?action=sales/delete-product&saleId=<?php echo $_GET["id"] . "&productId=" . $detail->id; ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h2>Resumen</h2>
  <form method="post" action="index.php?action=sales/add-payment" autocomplete="off">
    <div class="form-group">
      <label for="inputEmail1" class="col-lg-2 control-label">Forma de pago</label>
      <div class="col-lg-3">
        <select name="paymentType" class="form-control" required>
          <option value="">-- SELECCIONE --</option>
          <?php foreach ($paymentTypes as $paymentType) : ?>
            <option value="<?php echo $paymentType->id; ?>"><?php echo $paymentType->name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-lg-2">
        <input type="number" name="total" required class="form-control" id="total" placeholder="Total" required>
      </div>
      <div class="col-lg-2">
        <input type="date" name="date" required class="form-control" id="date" placeholder="Fecha" required>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary"><i class=""></i> Agregar</button>
      </div>
    </div>
    <input type="hidden" id="saleId" name="saleId" value="<?php echo $_GET["id"] ?>" class="form-control" autocomplete='off'>
    <input type="hidden" name="totalSale" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
    <input type="hidden" name="totalPayment" value="<?php echo $totalPay ?>" class="form-control">
  </form>
  <div class="row">
    <div class="col-md-6">
      <table class="table table-bordered table-hover">
        <thead>
          <th>ID</th>
          <th>Forma de pago</th>
          <th>Total</th>
          <th></th>
        </thead>
        <tbody>
          <?php foreach ($paymentDetails as $paymentDetail) :
            $paymentData = PaymentTypeData::getById($paymentDetail->payment_type_id);
          ?>
            <tr>
              <td><?php echo $paymentDetail->id; ?></td>
              <td><?php echo  $paymentData->name; ?></td>
              <td><b>$ <?php $tp = $paymentDetail->total;
                        $totalPay += $tp;
                        echo number_format($tp, 2); ?></b></td>
              <td style="width:25px;"><a href="index.php?action=sales/delete-payment&saleId=<?php echo $_GET["id"] . "&paymentId=" . $paymentDetail->id . "&totalSale=" . $total . "&totalPayment=" . $totalPay; ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if ($totalPay > 0) : ?>
        <div class="col-lg-4 col-md-offset-6">
          <input style="text-align:right;" type="text" id="totalGen1" name="totalGen1" value="<?php echo number_format($totalPay, 2) ?>" class="form-control">
        <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
      <table class="table table-bordered">
        <tr>
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

  <form method="post" class="form-horizontal" id="formUpdateSale" action="index.php?action=sales/update">
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
        <textarea class="form-control" name="description" rows="10" cols="50" placeholder="Comentarios"><?php echo $sale->description ?></textarea>
      </div>
      <div class="col-lg-offset-8 col-lg-10">
        <div class="checkbox">
          <label>
            <button class="btn btn-primary"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Finalizar</button>
          </label>
          <input type="hidden" id="totalSale" name="totalSale" value="<?php echo $total ?>" class="form-control" placeholder="Total">
          <input type="hidden" id="totalPayment" name="totalPayment" value="<?php echo $totalPay ?>" class="form-control">
          <input type="hidden" id="discount" name="discount" value="0" class="form-control">
          <input type="hidden" id="saleId" name="saleId" value="<?php echo $_GET["id"] ?>" class="form-control" autocomplete='off'>
        </div>
      </div>
    </div>
  </form>
  <script>
    $(document).ready(function() {
      $("#productId").select2({});
    });

    $("#formUpdateSale").submit(function(e) {
      totalPayment = $("#totalPayment").val();
      if ($("#totalSale").val() <= 0) {
        alert("Ingresa productos a tu venta");
        e.preventDefault();
      } else if (totalPayment > (<?php echo $total; ?> - discount)) {
        alert("No se puede efectuar la operacion verifica tus cantidades");
        e.preventDefault();
      } else {
        if (discount == "") {
          discount = 0;
        }
        /************Validar si se liquidó *************/
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

    function selectProduct(id) {
      $.ajax({
        type: "POST",
        url: "./?action=products/get-price-in",
        data: "id=" + id,

        error: function() {
          alert("Error al consultar el precio del concepto.");
        },
        success: function(data) {
          $("#price").val(data);
        }
      });
    }
  </script>
</div>