<div class="row">
	<div class="col-md-12">
	<h1>Reabastecer Inventario</h1>
	<p><b>Buscar producto por nombre o por codigo:</b></p>
		<form>
		<div class="row">
			<div class="col-md-6">
				<input type="hidden" name="view" value="re">
				<input type="text" name="product" class="form-control">
			</div>
			<div class="col-md-3">
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
			</div>
		</div>
		</form>
	</div>

<?php
$totalPay=0;
$typePay = ProductData::getTypePayeX();
 if(isset($_GET["product"])):?>
	<?php
$products = ProductData::getLikeEnt($_GET["product"]);



if(count($products)>0){
	?>
<h3>Resultados de la Busqueda</h3>
<table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>En inventario</th>
		<th>Precio unitario</th>
		<th>Cantidad</th>
		<th style="width:100px;"></th>
	</thead>
	<?php
$products_in_cero=0;
	 foreach($products as $product):
$q= OperationData::getStockByProduct($product->id);
	?>
		<form method="post" action="index.php?view=addtore">
	   <tr class="<?php if($q<=$product->inventary_min){ echo "danger"; }?>">
		<td style="width:80px;"><?php echo $product->id; ?></td>
		<td><?php echo $product->name; ?></td>
		
		<td>
			<?php echo $q; ?>
		</td>


		<td>
		<input type="number" value="<?php echo $product->price_in; ?>" class="form-control" required name="price" required placeholder="Precio ..."></td>
		</td>
		<td>
		<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
		<input type="number" class="form-control" required name="q" autofocus placeholder="Cantidad ..." required></td>
		<td style="width:100px;">
		<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> Agregar</button>
		</td>
	</tr>
	</form>
	<?php endforeach;?>
</table>

	<?php
}
?>
<br><hr>
<hr><br>
<?php else:
?>

<?php endif; ?>

<?php if(isset($_SESSION["errors"])):?>
<h2>Errores</h2>
<p></p>
<table class="table table-bordered table-hover">
<tr class="danger">
	<th>Codigo</th>
	<th>Producto</th>
	<th>Mensaje</th>
</tr>
<?php foreach ($_SESSION["errors"]  as $error):
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
<?php if(isset($_SESSION["reabastecer"])):
$total = 0;
?>
<h2>Lista de Reabastecimiento</h2>
<table class="table table-bordered table-hover">
<thead>
	<th>Codigo</th>
	<th>Producto</th>
	<th>Cantidad</th>
	<th>Precio Unitario</th>
	<th>Precio Total</th>
	<th ></th>
</thead>
<?php foreach($_SESSION["reabastecer"] as $p):
$product = ProductData::getById($p["product_id"]);
?>
<tr >
	<td><?php echo $product->id; ?></td>
	<td><?php echo $product->name; ?></td>
	<td ><?php echo $p["q"]; ?></td>
	<td><b>$ <?php echo number_format($p["precio"]); ?></b></td>
	<td><b>$ <?php  $pt = $p["precio"]*$p["q"]; $total +=$pt; echo number_format($pt); ?></b></td>
	<td style="width:30px;"><a href="index.php?view=clearre&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
</tr>

<?php endforeach; ?>
</table>
<h2>Resumen</h2>
<form method="post" action="index.php?view=addpayM" autocomplete="off">

 <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Forma de pago</label>
    <div class="col-lg-4">
     <select name="idTypePay" class="form-control" required>
    <option value="">-- SELECCIONE --</option>      
    <?php foreach($typePay as $type):?>
    <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>      
    <?php endforeach;?>
    </select>
    </div>
     <div class="col-lg-3">
      <input type="number" name="money" required class="form-control" id="money" placeholder="Total">
    </div>
    <div class="col-md-2">
	 <button type="submit" class="btn btn-primary"><i class=""></i> Agregar</button>
	</div>
  </div>
</form>
  <div class="row">
<div class="col-md-6">



<table class="table table-bordered">

<?php if(isset($_SESSION["typePM"])):

?>

<table class="table table-bordered table-hover">
<thead>
	<th style="">ID</th>
	<th style="">Forma de pago</th>
	<th style="">Total</th>
	<th ></th>
</thead>

<?php foreach($_SESSION["typePM"] as $t):
$tPay = ProductData::getByIdTypePay($t["idType"]);

?>
<tr >
	<td st><?php echo $t["idType"]; ?></td>
	<td><?php echo  $tPay->name;?></td>
	<td><b>$ <?php  $tp = $t["money"]; $totalPay +=$tp; echo number_format($tp); ?></b></td>
	<td style="width:25px;"><a href="index.php?view=clearpayM&idTypePay=<?php echo $tPay->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</table>
<?php if($totalPay>0):

?>
<div class="col-lg-4 col-md-offset-6" >
<input  style="text-align:right;" type="text" id="totalGen1" name="totalGen1" value="<?php echo number_format($totalPay,2) ?>"class="form-control">
<?php endif; ?>
</div>
</div>


<div class="col-md-6">
<table class="table table-bordered">
<tr>
	<td><p>Subtotal</p></td>
	<td><p><b>$ <?php echo number_format($total*.84); ?></b></p></td>
</tr>
<tr>
	<td><p>IVA</p></td>
	<td><p><b>$ <?php echo number_format($total*.16); ?></b></p></td>
</tr>
<tr>
	<td><p>Total</p></td>
	<td><p><b>$ <?php echo number_format($total); ?></b></p></td>
</tr>

</table>
</div>
</div>

<form method="post" class="form-horizontal" id="processre" action="index.php?view=processre">



</table>
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
    <div class="col-lg-offset-2 col-lg-10">
      <div class="checkbox">
        <label>
		<a href="index.php?view=clearre" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
        <button class="btn btn-lg btn-primary"><i class="fa fa-refresh"></i> Procesar Reabastecimiento</button>
          <input type="hidden" id="totalGen" name="totalGen" value="<?php echo $totalPay ?>"class="form-control">
            <input type="hidden" id="total" name="total" value="<?php echo $total ?>"class="form-control">
        </label>
      </div>
    </div>
  </div>
</form>
<script>
	$("#processre").submit(function(e){
		money = $("#totalGen").val();
		if(money<<?php echo $total;?>){
			alert("No se puede efectuar la operacion, agregar pago");
			e.preventDefault();
		}else{
			//go = confirm("Cambio: $"+(money-<?php echo $total;?>));
			if(go){}
				else{e.preventDefault();}
		}
	});
</script>
</div>
</div>

<br><br><br><br><br>
<?php endif; ?>

</div>