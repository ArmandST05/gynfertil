<?php
$input = OperationData::getById($_GET["id"]);
$product = ProductData::getById($input->product_id);
?>
<div class="row">
  <div class="col-md-12">
    <h1>Entradas</h1>

    <form method="post" action="index.php?action=inventory/add-input" autocomplete="off" -->

      <div class="col-lg-3">
        <label for="inputEmail1" class="col-lg-3 control-label">Medicamento</label>
        <input class="form-control" step="any" id="pro" name="pro" value="<?php echo $product->name; ?>" readonly>
      </div>

      <div class="col-lg-2">
        <label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
        <input type="number" class="form-control" value="" autofocus name="q" id="q" placeholder="Cantidad" required>
      </div>

      <div class="col-lg-3">
        <label for="inputEmail1">Lote</label>
        <input type="text" name="lot" class="form-control" required>
      </div>

      <div class="col-lg-3">
        <label for="inputEmail1">Fecha de caducidad</label>
        <input type="date" name="expirationDate" class="form-control" required>
      </div>

      <div class="form-group">
        <input type="hidden" name="productId" value="<?php echo $product->id; ?>">
      </div>

      <div class="col-lg-2">
        <br>
        <button type="submit" onClick="return confirmInput()" class="btn btn-primary">Guardar</button>
      </div>
    </form>

  </div>
</div>

</div>
</div>


<br><br><br><br><br>

</div>
<script type="text/javascript">
  function confirmInput() {
    var name = $("#pro").val();
    var q = $("#q").val();
    var flag = confirm("¿Seguro que deseas dar entrada del producto " + q + " " + name + "?");
    if (flag == true) {
      return true;
    } else {
      return false;
    }
  }
</script>