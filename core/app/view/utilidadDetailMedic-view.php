<?php
$hoy = date('Y-m-d');
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1>Detalle Ingresos Personal Reporte de Utilidad</h1>
		</div>
	</div>
	<br>
	<!--- -->
	<div class="row">
		<div class="col-md-12">
			<?php
			$productId = isset($_GET["productId"])  ? $_GET['productId'] : null;
			$medicId = isset($_GET["medicId"])  ? $_GET['medicId'] : null;
			$f1 = isset($_GET["sd"])  ? $_GET['sd'] : null;
			$f2 = isset($_GET["ed"])  ? $_GET['ed'] : null;

			$medic = SellData::getMedic($medicId);
			$sellDetails = SellData::getAllSellDateByMedicProduct($productId,$medicId, $f1, $f2);
			$product = SellData::getProducts($productId);
			?>

			<div class="clearfix"></div>
			<h2 id="uti" name="uti"> </h2>
			<h3>Ingresos <?php echo (date_format(date_create($f1),"d-m-Y")." - ".date_format(date_create($f2),"d-m-Y"))?></h3><br>
			<table class="table table-bordered table-hover" style="width:750px;" id='datosexcel' border='1'>
				<thead>
					<th>Concepto</th>
					<th>Médico</th>
					<th>Fecha</th>
					<th>Paciente</th>
					<th>Cantidad</th>
					<th>Subtotal</th>
				</thead>
				<?php
				$totalSells = 0;
				$totalQuantity = 0;
				foreach ($sellDetails as $detail):
					$totalSells += $detail->total;
					$totalQuantity += $detail->q;
					$patient = PatientData::getById($detail->idPac);
				?><tr class='success'>
						<td><?php echo $product->name ?></td>
						<td><?php echo (isset($medic)) ? $medic->name:'Venta Libre' ?></td>
						<td><?php echo date_format(date_create($detail->created_at),'d-m-Y H:i')?></td>
						<td><?php echo $patient->name ?></td>
						<td><?php echo $detail->q ?> </td>
						<td>$<?php echo number_format($detail->total, 2) ?></td>
					</tr>
				<?php endforeach;
				echo "<tr><td></td><td></td><td></td><td><label>Total:</label></td><td><label>" .$totalQuantity . "</label></td><td class='success'><label>$" . number_format($totalSells, 2) . "</label></td></tr>";
				?>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {});
	</script>