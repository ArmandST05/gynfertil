<?php
$clients = PersonData::getClients();
$hoy = date('Y-m-d');
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		$("#btnExport").click(function(e) {

			$("#datosexcel").btechco_excelexport({
				containerid: "datosexcel",
				datatype: $datatype.Table,
				filename: 'Corte'
			});

		});

	});
</script>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1>Cortes del día <?php if (isset($_GET["sd"])) {
									echo $_GET["sd"];
								} else {
									echo $hoy;
								} ?> </h1>


			<form>
				<input type="hidden" name="view" value="sellcorte">
				<div class="row">
					<div class="col-md-3">
						<input type="date" name="sd" value="<?php if (isset($_GET["sd"])) {
																echo $_GET["sd"];
															} else {
																echo $hoy;
															} ?>" class="form-control">
					</div>

					<div class="col-md-3">
						<input type="submit" class="btn btn-success btn-block" value="Procesar">
					</div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-primary btn-block" value="Exportar" id="btnExport">

					</div>
				</div>

			</form>

		</div>
	</div>
	<br><!--- -->
	<div class="row">

		<div class="col-md-12">

			<?php if (isset($_GET["sd"])) {
				$fecha = $_GET["sd"];
			} else {
				$fecha = $hoy;
			} ?>
			<?php


			$ConIng = SellData::getAllSellDate($fecha);
			$ConEgre = SellData::getAllBuyDate($fecha);

			$ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
			$ti_usua = UserData::get_tipo_usuario($ti_user);





			?>

			<div class="clearfix"></div>
			<h2 id="uti" name="uti"> </h2>
			<h3>Ingresos</h3><br>
			<table class="table table-bordered table-hover" id='datosexcel' style="width:750px;">
				<thead>
					<th>Conceptos</th>
					<th>Cantidad</th>
					<th>Total</th>
					<th>Resumen</th>
				</thead>

				<?php
				$tot = 0;
				foreach ($ConIng as $con) {
					$cat = SellData::getCatePro($con->product_id);
					$res = SellData::getAllSellDateR($con->product_id, $fecha);
					$pro = SellData::getProducts($con->product_id);
					//$res = SellData::getAllBuyDateR($con->id,$fecha);
					if ($cat->idCat <> 8) {
						$tot += $con->price;
						echo "
				<tr class='success'>
				<td>$pro->name</td>
                <td>$con->q</td>
                <td>" . number_format($con->price, 2) . "</td>
                <td>
				";

						foreach ($res as $key1) {

							$med = SellData::getMedic($key1->idMedic);
							echo "$med->name <label>Can: </label> $key1->q <label>Total: </label> " . number_format($key1->price, 2) . "<br>";
						}

						echo "</td></tr>";
					}
				}
				/****Nuevo*************/


				$Cat = SellData::getNameCat(8);
				$cM = 0;
				$tM = 0;
				foreach ($Cat as $c) {

					$det = SellData::getdetMedD($fecha);
					foreach ($det as $k) {
						$cM += $k->q;
						$tM += $k->total;
					}
					echo "
				<tr class='success'>
				<td>" . $c->name . "</td>
				";




					echo "
				<td>" . $cM . "</td>
				<td>" . number_format($tM, 2) . "</td>
                <td>
                ";
					foreach ($det as $k) {
						$pro = SellData::getProducts($k->product_id);
						echo "$pro->name <label>Can: </label> $k->q <label> Precio: </label> " . number_format($k->price, 2) . "<label> Total: </label> " . number_format($k->total, 2) . "<br>";
					}

					echo "</td></tr>";
				}

				echo "<tr><td><label>Total:</label></td><td></td><td class='success'><label>" . number_format($tot + $tM, 2) . "</label></td></tr>";
				?>

				<input type="hidden" id="ingre" name="ingre" value="<?php echo $tot + $tM ?>">
			</table>

			<h3>Entradas</h3><br>
			<table class="table table-bordered" id='datosexcel' style="width:350px;">
				<thead>
					<th>Forma de pago</th>
					<th>Total</th>
					<th>Facturado</th>
					<th>Banco Santander</th>
					<th>Banco Banorte</th>
				</thead>

				<?php


				$typ = SellData::getTypepayment();
				$totGen = 0;
				$totFac = 0;
				$totSan = 0;
				$totBan = 0;

				foreach ($typ as $t) {

					$ing = SellData::getIngresos($t->id, $fecha);


					echo "
				<tr class='success'>
				<td>$t->name</td>";
					$toti = 0;
					$totf = 0;
					$tots = 0;
					$totb = 0;
					foreach ($ing as $i) {
						$totGen += $i->total;
						$toti += $i->total;
						/****** FACTURADO *******/
						if ($i->fac == 1) {
							$totf += $i->total;
							$totFac += $i->total;
						}
						/****** BANCO *******/
						if ($i->banco == 1) {
							$totb += $i->total;
							$totBan += $i->total;
						} else {
							$tots += $i->total;
							$totSan += $i->total;
						}
					}
					echo "<td>" . number_format($toti, 2) . "</td>
				      <td>" . number_format($totf, 2) . "</td>
				      <td>" . number_format($tots, 2) . "</td>
				      <td>" . number_format($totb, 2) . "</td>";

					echo "</tr>";
				}

				echo "<tr><td><label>Total:</label></td><td class='success'><label>" . number_format($totGen, 2) . "</label></td><td class='success'><label>" . number_format($totFac, 2) . "</label></td><td class='success'><label>" . number_format($totSan, 2) . "</label></td><td class='success'><label>" . number_format($totBan, 2) . "</label></td></tr>";
				?>


			</table>


			<h3>Gastos</h3><br>
			<table class="table table-bordered table-hover" id='datosexcel' style="width:750px;">
				<thead>
					<th>Conceptos</th>
					<th>Cantidad</th>
					<th>Total</th>

				</thead>

				<?php

				$totE = 0;

				foreach ($ConEgre as $conE) {
					$totE += $conE->price * $conE->q;
					$pro = SellData::getProducts($conE->product_id);
					//$res = SellData::getAllBuyDateR($con->id,$fecha);
					echo "
				<tr class='danger'>
				<td>$pro->name</td>
                <td>$conE->q</td>
                <td>" . number_format($conE->price * $conE->q, 2) . "</td>
				";


					echo "</tr>";
				}

				echo "<tr ><td><label>Total:</label></td><td></td><td class='danger'><label>" . number_format($totE, 2) . "</label></td></tr>";
				?>


			</table>


			<h3>Salidas</h3><br>
			<table class="table table-bordered" id='datosexcel' style="width:350px;">
				<thead>
					<th>Forma de pago</th>
					<th>Total</th>
					<th>Facturado</th>
					<th>Banco Santander</th>
					<th>Banco Banorte</th>
				</thead>

				<?php


				$typE = SellData::getTypepaymentE();
				$totGenE = 0;
				$totFacE = 0;
				$totSanE = 0;
				$totBanE = 0;

				foreach ($typE as $t) {

					$eg = SellData::getEgresosE($t->id, $fecha);

					echo "
				<tr class='success'>
				<td>$t->name</td>";
					$totiE = 0;
					$totfE = 0;
					$totsE = 0;
					$totbE = 0;
					foreach ($eg as $e) {
						$totGenE += $e->total;
						$totiE += $e->total;
						/****** FACTURADO *******/
						if ($e->fac == 1) {
							$totfE += $e->total;
							$totFacE += $e->total;
						}
						/****** BANCO *******/
						if ($e->banco == 1) {
							$totbE += $e->total;
							$totBanE += $e->total;
						} else {
							$totsE += $e->total;
							$totSanE += $e->total;
						}
					}
					echo "<td>" . number_format($totiE, 2) . "</td>
				      <td>" . number_format($totfE, 2) . "</td>
				      <td>" . number_format($totsE, 2) . "</td>
				      <td>" . number_format($totbE, 2) . "</td>";

					echo "</tr>";
				}

				echo "<tr><td><label>Total:</label></td><td class='success'><label>" . number_format($totGenE, 2) . "</label></td><td class='success'><label>" . number_format($totFacE, 2) . "</label></td><td class='success'><label>" . number_format($totSanE, 2) . "</label></td><td class='success'><label>" . number_format($totBanE, 2) . "</label></td></tr>";
				?>

				<input type="hidden" id="egre" name="egre" value="<?php echo $totGenE ?>">
			</table>

			<br><br><br><br><br><br><br><br><br><br>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {



			var tot = (parseFloat($('#ingre').val()) - parseFloat($('#egre').val()));
			var tot2 = tot.toFixed(2);
			//alert(tot);

			$('#uti').html("Utilidad: " + tot2);
		});
	</script>