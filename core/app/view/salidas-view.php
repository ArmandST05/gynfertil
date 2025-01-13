<?php 

$typePay = ProductData::getTypePay();
$total = 0;
$totalPay=0;
$pro = ProductData::getLikeSalidas();
 ?>
<script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="assets/select2.min.css" rel="stylesheet" />
<script src="assets/select2.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
    $("#product_id").select2({
   });
 });

  
 </script>


<div class="row">
	<div class="col-md-12">
	<h1>Salidas</h1>

	 <form method="post" action="index.php?view=addtocartSal" autocomplete="off">
		<div class="row">
		

	<div class="form-group">

   <div class="col-lg-3">
  <label for="inputEmail1" class="col-lg-3 control-label">Medicamento/Conceptos</label>
<select name="product_id"  id="product_id" class="form-control" onchange="seleccion(this.value)" autofocus required >
<option value="0">-- SELECCIONE --</option>
  <?php foreach($pro as $p):?>
    <option value="<?php echo $p->id; ?>"><?php echo $p->id." - ".$p->name ?></option>
  <?php endforeach; ?>
</select>
    </div>
 
  <div class="col-lg-2">
   <label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
   <input type="number"class="form-control" value="1" autofocus name="q" placeholder="Cantidad"  required>
     
 </div>

 <div class="col-lg-2">
 <br>
   <button type="submit" class="btn btn-primary">Agregar</button>   
 </div>
     

     	</div>
		</div>
		</div>
</form>

	
<?php if(isset($_SESSION["errorsSal"])):?>
<h2>Errores</h2>
<p></p>
<table class="table table-bordered table-hover">
<tr class="danger">
	<th>Codigo</th>
	<th>Producto</th>
	<th>Mensaje</th>
</tr>
<?php foreach ($_SESSION["errorsSal"]  as $error):
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
unset($_SESSION["errorsSal"]);
 endif; ?>


<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["cartSal"])):

?>
<h2>Lista de venta</h2>
<table class="table table-bordered table-hover" style="width:675px;">
<thead>
	<th style="width:30px;">ID</th>
	<th style="width:250px;">Producto/Concepto</th>
	<th style="width:250px;">Tipo</th>
	<th style="width:30px;">Cantidad</th>
	
	<th ></th>
</thead>
<?php foreach($_SESSION["cartSal"] as $p):
$product = ProductData::getById($p["product_id"]);
?>
<tr >
	<td st><?php echo $product->id; ?></td>
	<td><?php echo $product->name; ?></td>
	<td><?php echo $p["type"]; ?></td>
	<td><?php echo $p["q"]; ?></td>
	<td style="width:30px;"><a href="index.php?view=clearcartSal&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
</tr>

<?php endforeach; ?>
</table>

<h2>Comentarios/anotaciones</h2>
<div class="form-group">
   
  </div>
 

<form method="post" class="form-horizontal" id="processsellSal" action="index.php?view=processsellSal">


<div class="form-group">
<div class="col-lg-6">
    <textarea class="form-control" name="note" rows="10" cols="50" placeholder="Comentarios"></textarea>
    </div>
    <div class="col-lg-10">
      <label>
      <br>
		<a href="index.php?view=clearcartSal" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
        <button class="btn btn-lg btn-primary"></i> Dar salida</button>
        </label>

    <input type="hidden" name="total" value="0" class="form-control" placeholder="Total">
	<input type="hidden" id="discount" name="discount" value="0"class="form-control">
    <input type="hidden" id="totalGen" name="totalGen" value="0"class="form-control">
    
    </div>
  </div>
</form>
<?php endif; ?>
<br><br><br><br><br>


</div>