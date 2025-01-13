<?php
$product = ProductData::getById($_GET["id"]);
$categories = CategoryData::getAll();

if ($product != null) :
?>
  <div class="row">
    <div class="col-md-8">
      <h1><?php echo $product->name ?> <small>Editar Producto</small></h1>
      <?php if (isset($_COOKIE["prdupd"])) : ?>
        <p class="alert alert-info">La informacion del producto se ha actualizado exitosamente.</p>
      <?php setcookie("prdupd", "", time() - 18600);
      endif; ?>
      <br><br>
      <form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?action=products/update" role="form">

        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Código de barras (Opcional)</label>
          <div class="col-md-8">
            <input type="text" name="barcode" class="form-control" id="barcode" value="<?php echo $product->barcode; ?>" placeholder="Codigo de barras del Producto">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Nombre*</label>
          <div class="col-md-8">
            <input type="text" name="name" class="form-control" id="name" value="<?php echo $product->name; ?>" placeholder="Nombre del Producto">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Presentación*</label>
          <div class="col-md-8">
            <input type="text" name="presentation" class="form-control" id="presentation" value="<?php echo $product->presentation; ?>" placeholder="Presentación del producto">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Marca*</label>
          <div class="col-md-8">
            <input type="text" name="brand" class="form-control" id="brand" value="<?php echo $product->brand; ?>" placeholder="Marca del producto">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Precio de Entrada*</label>
          <div class="col-md-8">
            <input type="text" name="price_in" class="form-control" value="<?php echo $product->price_in; ?>" id="price_in" placeholder="Precio de entrada">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Precio de Salida*</label>
          <div class="col-md-8">
            <input type="text" name="price_out" class="form-control" id="price_out" value="<?php echo $product->price_out; ?>" placeholder="Precio de salida">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Mínima en inventario:</label>
          <div class="col-md-8">
            <input type="text" name="inventary_min" class="form-control" value="<?php echo $product->inventary_min; ?>" id="inputEmail1" placeholder="Minima en Inventario (Default 10)">
          </div>
        </div>

        <div class="form-group">
          <label for="inputEmail1" class="col-lg-3 control-label">Esta activo</label>
          <div class="col-md-8">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="is_active" <?php if ($product->is_active) {
                                                          echo "checked";
                                                        } ?>>
              </label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-lg-offset-3 col-lg-8">
            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
            <button type="submit" class="btn btn-success">Actualizar Producto</button>
          </div>
        </div>
      </form>

      <br><br><br><br><br><br><br><br><br>
    </div>
  </div>
<?php endif; ?>