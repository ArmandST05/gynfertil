<div class="btn-group  pull-right">
	<!--<a class="btn btn-default" href="reports/onesale-word.php?id=<?php echo $_GET["id"]; ?>">Imprimir venta</a></li>-->
</div>

<h1>Resumen de Venta</h1>
<?php if (isset($_GET["id"]) && $_GET["id"] != "") : ?>
	<?php
	$sale = OperationData::getById($_GET["id"]);
	$operations = OperationDetailData::getAllProductsByOperationId($_GET["id"]);

	$payments = OperationPaymentData::getByOperationId($_GET["id"]);
	$total = 0;
	$totalPay = 0;
	?>
	<?php
	if (isset($_COOKIE["selled"])) {
		foreach ($operations as $operation) {
			$qx = OperationDetailData::getStockByBranchOfficeProduct($sale->branch_office_id,$operation->product_id);
			$p = $operation->getProduct();
			if ($p->type_id == "1" || $p->type_id == "2") {
			} else if ($qx == 0) {
				echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";
			} else if ($qx <= $p->minimum_inventory / 2) {
				echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
			} else if ($qx <= $p->minimum_inventory) {
				echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
			}
		}
		setcookie("selled", "", time() - 18600);
	}

	?>
	<table class="table table-bordered">
		<?php if ($sale->patient_id != "") :
			$client = $sale->getPatient();
		?>
			<tr>
				<td style="width:150px;">Cliente</td>
				<td><?php echo $client->name ?></td>
			</tr>

		<?php endif; ?>
		<?php if ($sale->user_id != "") :
			$user = $sale->getUser();
		?>
			<tr>
				<td>Atendido por</td>
				<td><?php echo $user->name . " " . $user->lastname; ?></td>
			</tr>
		<?php endif; ?>
	</table>
	<br>
	<h3>Detalle Venta</h3>
	<table class="table table-bordered table-hover">
		<thead>
			<th>Codigo</th>
			<th>Cantidad</th>
			<th>Producto/Concepto</th>
			<th>Precio Unitario</th>
			<th>Total</th>

		</thead>
		<?php
		foreach ($operations as $operation) {
			$product  = $operation->getProduct();
		?>
			<tr>
				<td><?php echo $product->id; ?></td>
				<td><?php echo $operation->quantity; ?></td>
				<td><?php echo $product->name; ?></td>
				<td>$ <?php echo number_format($operation->price, 2, ".", ","); ?></td>
				<td><b>$ <?php echo number_format($operation->quantity * $operation->price, 2, ".", ",");
							$total += $operation->quantity * $operation->price; ?></b></td>
			</tr>
		<?php
		}
		?>
	</table>

	<br>
	<h3>Detalle Pago</h3>
	<table class="table table-bordered table-hover " style="width:400px;">
		<thead>
			<th style="width:280px;">Forma de Pago</th>
			<th style="width:120px;">Total</th>

		</thead>
		<?php
		foreach ($payments as $payment) : ?>
			<tr>
				<td style="width:280px;"><?php echo $payment->getType()->name; ?></td>
				<td style="width:120px;"><?php echo number_format($payment->total, 2); ?></td>
				<?php $totalPay += $payment->total; ?>
			<?php endforeach ?>
			</tr>

			</tr>
	</table>
	<table class="table table-bordered table-hover " style="width:400px;">

		<tr>
			<td style="width:280px;"></td>
			<td style="width:120px;">
				<b><?php echo number_format($totalPay, 2) ?></b>
			</td>
	</table>
	<br><br>
	<div class="row">
		<div class="col-md-4">
			<table class="table table-bordered">
				<tr>
					<td>
						<h4>Pago:</h4>
					</td>
					<td>
						<h4>$ <?php echo number_format($totalPay, 2, '.', ','); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<h4>Total:</h4>
					</td>
					<td>
						<h4>$ <?php echo number_format($total -	$sale->discount, 2, '.', ','); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<h4>Saldo:</h4>
					</td>
					<td>
						<h4>$ <?php echo number_format($total - $totalPay, 2, '.', ','); ?></h4>
					</td>
				</tr>
			</table>
		</div>
	</div>
<?php else : ?>
	501 Internal Error
<?php endif; ?>