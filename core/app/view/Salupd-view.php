<?php 
 

$det = CategorySpend::getByIdCatBuyId($_GET["id"]);
$com = CategorySpend::getComen($_GET["id"]);
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
	<p><b>Buscar por Concepto/Categoría:</b></p>
			<div class="row">
			
	 <form method="post" action="index.php?view=addsalupd" autocomplete="off">
		<div class="row">
		

	<div class="form-group">

   <div class="col-lg-3">
  <label for="inputEmail1" class="col-lg-3 control-label">Medicamento/Conceptos</label>
<select name="product_id"  id="product_id" class="form-control" autofocus required >
<option value="0">-- SELECCIONE --</option>
  <?php foreach($pro as $p):?>
    <option value="<?php echo $p->id; ?>"><?php echo $p->id." - ".$p->name ?></option>
  <?php endforeach; ?>
</select>
    </div>
 
  <div class="col-lg-2">
   <label for="inputEmail1" class="col-lg-3 control-label">Cantidad</label>
   <input type="number"class="form-control" value="1" autofocus name="q" placeholder="Cantidad"  required>
   <input type="hidden" id="idSell" name="idSell" value="<?php echo $_GET["id"] ?>" class="form-control"  autocomplete='off'>
	
 </div>

 <div class="col-lg-2">
 <br>
   <button type="submit" class="btn btn-primary">Agregar</button>   
 </div>
     

     	</div>
		</div>
		</div>
</form>



<h2>Lista</h2>
<table class="table table-bordered table-hover">
<thead>
	<th style="width:30px;">ID</th>
	<th style="width:250px;">Concepto</th>
	<th style="width:250px;">Categoría</th>
	<th style="width:30px;">Cantidad</th>

	<th ></th>
</thead>
<?php foreach($det as $c):

$concept = CategorySpend::getByIdCatBuy($c->product_id);
?>
<tr >
	<td><?php echo $c->product_id; ?></td>
	<td><?php echo $concept->name; ?></td>
	<td><?php echo $concept->nameCat; ?></td>
	<td><?php echo $c->q; ?></td>
	<td style="width:30px;"><a href="index.php?view=delConSal&idSell=<?php echo $_GET["id"]."&idPro=".$c->id;?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
</tr>

<?php endforeach; ?>
</table>


<form method="post" class="form-horizontal" id="processsellSal" action="index.php?view=processsellSalU">


<div class="form-group">
<div class="col-lg-6">
    <textarea class="form-control" name="note" rows="10" cols="50" placeholder="Comentarios"><?php echo $com->comentarios ?></textarea>
    </div>
    <div class="col-lg-offset-4 col-lg-10">
      <div class="checkbox">
        <label>
		 <button class="btn btn-lg btn-primary"><i class="glyphicon glyphicon-edit"></i>  Actualizar </i></button>
        </label>

    <input type="hidden" name="total" value="0" class="form-control" placeholder="Total">
	<input type="hidden" id="totalGen" name="totalGen" value="0"class="form-control">
    <input type="hidden" id="discount" name="discount" value="0"class="form-control">
   

      </div>
    <input type="hidden" id="idSell" name="idSell" value="<?php echo $_GET["id"] ?>" class="form-control"  autocomplete='off'>

    </div>
  </div>
</form>


<br><br><br><br><br>


</div>