<?php
$bankAccounts = BankAccountData::getAllByStatus(1);
$banks = BankData::getAll();
$companyAccounts = CompanyAccountData::getAll();

$startDate = (isset($_GET["startDate"])) ? $_GET["startDate"] : date("Y-m-d");
$endDate = (isset($_GET["endDate"])) ? $_GET["endDate"] : date("Y-m-d");

$companyAccountId = (isset($_GET["companyAccountId"])) ? $_GET["companyAccountId"] : 0;
$bankId = (isset($_GET["bankId"])) ? $_GET["bankId"] : 0;
$bankAccountId = (isset($_GET["bankAccountId"])) ? $_GET["bankAccountId"] : "0";
$paymentTypeId = (isset($_GET["paymentTypeId"])) ? $_GET["paymentTypeId"] : "0";

$isInvoice = (isset($_GET["isInvoice"])) ? $_GET["isInvoice"] : "all";

$subtitle = "";
if ($isInvoice == "1") {
    $subtitle = "SOLICITARON FACTURA";
} else if ($isInvoice == "0") {
    $subtitle = "NO FACTURAR (PÚBLICO EN GENERAL)";
}

$selectedBanks = [];
if ($bankAccountId == "0") { //Obtener las cuentas de cierta compañía
    $selectedBankAccounts = BankAccountData::getAllByCompanyAccount($companyAccountId);
    foreach ($selectedBankAccounts as $selectedBankAccount) {
        $selectedBanks[$selectedBankAccount->bank_id][] = $selectedBankAccount;
    }
} else { //Obtener datos de compañía en específico
    $selectedBankAccount = BankAccountData::getById($bankAccountId);
    $selectedBanks[$selectedBankAccount->bank_id][] = $selectedBankAccount;
}
$companyTotal = 0;
?>
<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        /*$("#btnExport").click(function(e) {
            $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel",
                datatype: $datatype.Table,
                filename: 'Reporte Transacciones'
            });
        });*/
    });
</script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1>Reporte Transacciones </h1>
            <h3><?php echo $subtitle ?></h3>
            <form>
                <input type="hidden" name="view" value="reports/transactions">
                <div class="row">
                    <div class="col-md-2">
                        <label>Fecha de inicio</label>
                        <input type="date" name="startDate" value="<?php echo $startDate ?>" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Fecha de fin</label>
                        <input type="date" name="endDate" value="<?php echo $endDate ?>" class="form-control" required>
                    </div>
                    <div class="col-lg-4">
                        <label>Factura</label>
                        <select name="isInvoice" class="form-control" required>
                            <option value="all" <?php echo ($isInvoice == "all") ? "selected" : "" ?>>--TODAS--</option>
                            <option value="1" <?php echo ($isInvoice == "1") ? "selected" : "" ?>>SÍ SOLICITÓ FACTURA</option>
                            <option value="0" <?php echo ($isInvoice == "0") ? "selected" : "" ?>>NO SOLICITÓ FACTURA (PÚBLICO GRAL)</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Forma de pago</label>
                        <select name="paymentTypeId" class="form-control" required>
                            <option value="0" <?php echo ($paymentTypeId == "0") ? "selected" : "" ?>>-- TODOS --</option>
                            <option value="cards" <?php echo ($paymentTypeId === "cards") ? "selected" : "" ?>>TARJETAS</option>
                            <option value="2" <?php echo ($paymentTypeId == 2) ? "selected" : "" ?>>TARJETA DE DÉBITO</option>
                            <option value="3" <?php echo ($paymentTypeId == 3) ? "selected" : "" ?>>TARJETA DE CRÉDITO</option>
                            <option value="10" <?php echo ($paymentTypeId == 10) ? "selected" : "" ?>>TRANSFERENCIAS</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>No. de cuenta</label>
                        <select name="bankAccountId" id="bankAccountId" class="form-control" required>
                            <option value="0">-- TODAS --</option>
                            <?php foreach ($bankAccounts as $bankAccount) : ?>
                                <option data-company-account-id="<?php echo $bankAccount->company_account_id ?>" value="<?php echo $bankAccount->id; ?>" <?php echo ($bankAccountId == $bankAccount->id) ? "selected" : "" ?>><?php echo $bankAccount->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!--<div class="col-lg-4">
                        <label>Banco</label>
                        <select name="bankId" class="form-control" required>
                            <option value="0">-- TODAS --</option>
                            <?php foreach ($banks as $bank) : ?>
                                <option value="<?php echo $bank->id; ?>" <?php echo ($bankId == $bank->id) ? "selected" : "" ?>><?php echo $bank->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>-->
                    <div class="col-lg-4">
                        <label>Empresa</label>
                        <select name="companyAccountId" id="companyAccountId" class="form-control" required>
                            <?php foreach ($companyAccounts as $companyAccount) : ?>
                                <option value="<?php echo $companyAccount->id; ?>" <?php echo ($companyAccountId == $companyAccount->id) ? "selected" : "" ?>><?php echo $companyAccount->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <input type="submit" class="btn btn-success btn-block" value="Procesar">
                    </div>
                    <div class="col-md-2">
                        <br>
                        <a target="_blank" href="index.php?view=reports/transactions-excel&startDate=<?php echo $startDate ?>&endDate=<?php echo $endDate ?>&companyAccountId=<?php echo $companyAccountId ?>&bankId=<?php echo $bankId?>&bankAccountId=<?php echo $bankAccountId?>&paymentTypeId=<?php echo $paymentTypeId?>&isInvoice=<?php echo $isInvoice?>" class="btn btn-primary btn-block"><i class="fas fa-file"></i> Exportar Excel</a>
                    </div>
                </div>

            </form>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach ($selectedBanks as $index => $selectedBank) :
                $bankData = BankData::getById($index);
            ?>
                <h3><?php echo $bankData->name ?></h3>
                <?php foreach ($selectedBank as $selectedBankAccount) :
                    echo "<h4>" . $selectedBankAccount->name . "</h4>";
                    $totalCards = 0;
                    $totalTransfers = 0;
                    $numberCards = 0;
                    $numberTransfers = 0;
                    //TABLA DE TARJETAS (Mostrar cuando se vayan a ver todos los tipos de pagos y cuando no se seleccionaran transferencias)
                    if ($paymentTypeId == "0" || $paymentTypeId != "10") :
                        $searchPaymentTypeId = ($paymentTypeId == 0) ? "cards" : $paymentTypeId;
                        $cardPayments = SellData::getAllPaymentsByDatesTypeBankAccountInvoice($startDate, $endDate, $searchPaymentTypeId, $selectedBankAccount->id, $isInvoice);
                        if (count($cardPayments) > 0) : ?>
                            <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                                <thead>
                                    <tr>
                                        <th colspan="7">TARJETAS</th>
                                    </tr>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Paciente</th>
                                        <th>Cuenta</th>
                                        <th>Forma Pago</th>
                                        <th>Total</th>
                                        <th>Factura</th>
                                        <th>Número Factura</th>
                                    </tr>
                                </thead>
                                <?php
                                foreach ($cardPayments as $payment) :
                                    $totalCards += $payment->total;
                                    $numberCards++;
                                    if ($payment->invoice_number != "") {
                                        $class = "success";
                                    } else {
                                        $class = "danger";
                                    }
                                ?>
                                    <tr id="r-<?php echo $payment->id ?>" class='<?php echo $class ?>'>
                                        <td><?php echo $payment->date_format ?></td>
                                        <td><?php echo $payment->hour_format ?></td>
                                        <td><?php echo $payment->patient_name ?></td>
                                        <td><?php echo $selectedBankAccount->name ?></td>
                                        <td><?php echo $payment->payment_type_name ?></td>
                                        <td>$<?php echo number_format($payment->total, 2) ?></td>
                                        <td><?php echo ($payment->is_invoice == 1) ? "SÍ SOLICITÓ" : "NO SOLICITÓ" ?></td>
                                        <td><input type="text" id="invoiceNumber-<?php echo $payment->id ?>" value="<?php echo $payment->invoice_number ?>" onblur="updateInvoiceNumber(<?php echo $payment->id ?>)"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <h5 style="color:#2A8AC4">Número de transacciones tarjeta: <?php echo $numberCards ?></h5>
                            <h5 style="color:#2A8AC4">Total de transacciones tarjeta: $<?php echo number_format($totalCards, 2) ?></h5>
                        <?php
                        else :
                            echo "<p class='alert alert-danger'>No se encontraron transacciones de tarjetas.</p>";
                        endif;
                        ?>
                    <?php
                        $companyTotal += $totalCards;
                    endif;
                    ?>
                    <?php //TABLA DE TRANSFERENCIAS (Mostrar cuando se vayan a ver todos los tipos de pagos y cuando se seleccionaran transferencias)
                    if ($paymentTypeId == "0" || $paymentTypeId == "10") :
                        $searchPaymentTypeId = 10; //Buscar y mostrar sólo transferencias
                        $transferPayments = SellData::getAllPaymentsByDatesTypeBankAccountInvoice($startDate, $endDate, $searchPaymentTypeId, $selectedBankAccount->id, $isInvoice);
                        if (count($transferPayments) > 0) : ?>
                            <table class="table table-bordered table-hover" id='datosexcel' border='1'>
                                <thead>
                                    <tr>
                                        <th colspan="7">TRANSFERENCIAS</th>
                                    </tr>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Paciente</th>
                                        <th>Cuenta</th>
                                        <th>Forma Pago</th>
                                        <th>Total</th>
                                        <th>Factura</th>
                                        <th>Número Factura</th>
                                    </tr>
                                </thead>
                                <?php
                                foreach ($transferPayments as $payment) :
                                    $totalTransfers += $payment->total;
                                    $numberTransfers++;
                                    if ($payment->invoice_number != "") {
                                        $class = "success";
                                    } else {
                                        $class = "danger";
                                    }
                                ?>
                                    <tr id="r-<?php echo $payment->id ?>" class='<?php echo $class ?>'>
                                        <td><?php echo $payment->date_format ?></td>
                                        <td><?php echo $payment->hour_format ?></td>
                                        <td><?php echo $payment->patient_name ?></td>
                                        <td><?php echo $selectedBankAccount->name ?></td>
                                        <td><?php echo $payment->payment_type_name ?></td>
                                        <td>$<?php echo number_format($payment->total, 2) ?></td>
                                        <td><?php echo ($payment->is_invoice == 1) ? "SÍ SOLICITÓ" : "NO SOLICITÓ" ?></td>
                                        <td> <input type="text" id="invoiceNumber-<?php echo $payment->id ?>" value="<?php echo $payment->invoice_number ?>" onblur="updateInvoiceNumber(<?php echo $payment->id ?>)"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <h5 style="color:#2A8AC4">Número de transferencias: <?php echo $numberTransfers ?></h5>
                            <h5 style="color:#2A8AC4">Total de transferencias: $<?php echo number_format($totalTransfers, 2) ?></h5>
                        <?php
                        else :
                            echo "<p class='alert alert-danger'>No se encontraron transferencias.</p>";
                        endif;
                        ?>
                    <?php
                        $companyTotal += $totalTransfers;
                    endif;
                    ?>
                    <h4 style="color:#2A8AC4">NÚMERO TRANSACCIONES CUENTA: <?php echo number_format($numberCards + $numberTransfers) ?></h4>
                    <h4 style="color:#2A8AC4">TOTAL TRANSACCIONES CUENTA: $<?php echo number_format($totalCards + $totalTransfers, 2) ?></h4>
                <?php
                endforeach;
                ?>
            <?php endforeach; ?>
            <br>
            <?php if ($companyAccountId != "0") : ?>
                <h3 style="color:#2A8AC4">TOTAL GENERAL EMPRESA: $<?php echo number_format($companyTotal, 2); ?></h3>
            <?php endif; ?>
        </div>
    </div>
</section>
<script>
    function updateInvoiceNumber(paymentId) {
        let invoiceNumber = $.trim($("#invoiceNumber-" + paymentId).val());

        $.ajax({
            type: "POST",
            url: "./?action=payments/update-invoice-number",
            data: {
                paymentId: paymentId,
                invoiceNumber: invoiceNumber
            },
            error: function() {
                Swal.fire(
                    '¡Oops!',
                    'No se ha podido actualizar el número de factura, vuelve a editar el dato.',
                    'error'
                );
            },
            success: function(data) {
                $("#invoiceNumberTd-" + paymentId).text(invoiceNumber);
                $("#r-" + paymentId).removeClass("success");
                $("#r-" + paymentId).removeClass("danger");
                if (invoiceNumber != "") {
                    $("#r-" + paymentId).addClass("success");
                } else {
                    $("#r-" + paymentId).addClass("danger");
                }
            }
        });
    }
    $("#bankAccountId").change(function() {
        $("#companyAccountId").val($(this).find(":selected").data("company-account-id"));
    });

    $("#companyAccountId").change(function() {
        $("#bankAccountId").val(0);
    });
</script>