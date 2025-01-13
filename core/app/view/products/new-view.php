<?php
$categories = CategoryData::getAll();
?>
<script type="text/javascript">
  $(document).ready(function() {

    var consulta
    //hacemos focus
    $("#product_code").focus();
    //comprobamos si se pulsa una tecla
    $("#product_code").keyup(function(e) {
      //obtenemos el texto introducido en el campo
      consulta = $("#product_code").val();
      //hace la búsqueda
      $("#resultado").delay(1000).queue(function(n) {

        $("#resultado").html();

        $.ajax({
          type: "POST",
          url: "./?action=comprobarPro",
          data: "cod=" + consulta,
          dataType: "html",
          error: function() {
            alert("error petición ajax");
          },
          success: function(data) {

            n();
            var r = data;
            $("#resultado").html(data);
            if (r == "") {
              document.getElementById("products").style.display = 'block';

            } else {
              document.getElementById("products").style.display = 'none';

            }

          }
        });

      });

    });

  });

  /*****NAME*******/
  $(document).ready(function() {

    var consulta
    //hacemos focus

    //comprobamos si se pulsa una tecla
    $("#name").keyup(function(e) {
      //obtenemos el texto introducido en el campo
      consulta = $("#name").val();
      //hace la búsqueda
      $("#resultado").delay(1000).queue(function(n) {

        $("#resultado").html();

        $.ajax({
          type: "POST",
          url: "./?action=comprobarProN",
          data: "na=" + consulta,
          dataType: "html",
          error: function() {
            alert("error petición ajax");
          },
          success: function(data) {

            n();
            var r = data;
            $("#resultado").html(data);
            if (r == "") {
              document.getElementById("products").style.display = 'block';
            } else {
              document.getElementById("products").style.display = 'none';
            }

          }
        });

      });

    });

  });
</script>
<div class="row">
  <div class="col-md-12">
    <h1>Nuevo Producto</h1>
    <div id="resultado"></div>
    <br>
    <form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?action=products/add" role="form" autocomplete="off">
      <p class="alert alert-info">El ID (Identificador único) del producto lo asigna el sistema automáticamente.
        <br>El código de barras no es lo mismo al ID (identificador único) y puedes capturarlo o no de acuerdo a tus necesidades.
      </p>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Código de Barras (Opcional)</label>
        <div class="col-md-6">
          <input type="text" name="barcode" id="product_code" class="form-control" id="barcode" placeholder="Codigo de Barras del Producto">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
        <div class="col-md-6">
          <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre del Producto">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Presentación*</label>
        <div class="col-md-6">
          <input type="text" name="presentation" class="form-control" id="presentation" placeholder="Presentación del producto">
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Marca*</label>
        <div class="col-md-6">
          <input type="text" name="brand" class="form-control" id="brand" placeholder="Marca del producto">
        </div>
      </div>
      <div style="display:none;" id="products" name="products">
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Costo*</label>
          <div class="col-md-6">
            <input type="text" name="price_in" required class="form-control" id="price_in" placeholder="Precio de entrada">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Precio de Salida*</label>
          <div class="col-md-6">
            <input type="text" name="price_out" required class="form-control" id="price_out" placeholder="Precio de salida">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Mínima en inventario:</label>
          <div class="col-md-6">
            <input type="text" name="inventary_min" class="form-control" placeholder="Minima en Inventario (Default 10)">
          </div>
        </div>

        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Inventario inicial:</label>
          <div class="col-md-6">
            <input type="text" name="q" class="form-control" placeholder="Inventario inicial">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Lote:</label>
          <div class="col-md-6">
            <input type="text" name="lot" class="form-control" placeholder="Lote">
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Fecha caducidad:</label>
          <div class="col-md-6">
            <input type="date" name="expirationDate" class="form-control" placeholder="Fecha caducidad">
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary">Agregar Producto</button>
          </div>
        </div>
    </form>

  </div>
</div>
</div>
<script>
  $(document).ready(function() {
    $("#product_code").keydown(function(e) {
      if (e.which == 17 || e.which == 74) {
        e.preventDefault();
      } else {
        console.log(e.which);
      }
    })
  });
</script>