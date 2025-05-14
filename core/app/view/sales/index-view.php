<?php
$user = UserData::getLoggedIn();
$userType = (isset($user)) ? $user->user_type : null;
$paymentTypes = PaymentTypeData::getAll();

if ($userType == "r") {
    $searchBranchOfficeId = $user->getBranchOffice()->id;
    $branchOffices = [$user->getBranchOffice()];
} else {
    if ($userType == "co") {
        $searchBranchOfficeId = (isset($_GET["searchBranchOfficeId"])) ? $_GET["searchBranchOfficeId"] : $user->getBranchOffice()->id;
    } else {
        $searchBranchOfficeId = (isset($_GET["searchBranchOfficeId"])) ? $_GET["searchBranchOfficeId"] : 0;
    }
    $branchOffices = BranchOfficeData::getAllByStatus(1);
}

$searchPaymentTypeId = (isset($_GET["searchPaymentTypeId"])) ? $_GET["searchPaymentTypeId"] : "all";
$searchStatusId = (isset($_GET["searchStatusId"])) ? $_GET["searchStatusId"] : "all";

?>
<h1>Lista de Ventas</h1>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" method="GET" enctype="multipart/form-data" action="index.php" role="form">
            <div class="form-group">
                <div class="col-md-3">
                    <label for="inputEmail1" class="col-lg-1 control-label">Sucursal</label>
                    <select name="searchBranchOfficeId" class="form-control" required>
                        <option value="">-- SELECCIONE --</option>
                        <?php foreach ($branchOffices as $branchOffice) : ?>
                            <option value="<?php echo $branchOffice->id; ?>" <?php echo ($searchBranchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="view" value="sales/index">
                </div>
                <div class="col-md-3">
                    <label>Tipo pago</label>
                    <select name="searchPaymentTypeId" class="form-control">
                        <option value="all">-- TODOS --</option>
                        <?php foreach ($paymentTypes as $paymentType) : ?>
                            <option value="<?php echo $paymentType->id; ?>" <?php echo ($searchPaymentTypeId == $paymentType->id) ? "selected" : "" ?>><?php echo $paymentType->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Estatus:</label>
                    <select name="searchStatusId" class="form-control" required>
                        <option value="all">-- TODAS --</option>
                        <option value="0" <?php echo ($searchStatusId == "0") ? "selected" : "" ?>>PENDIENTE</option>
                        <option value="1" <?php echo ($searchStatusId == "1") ? "selected" : "" ?>>PAGADO</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <br>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<hr>
<table id="lookup1" class="table table-bordered table-hover">
    <thead align="center">
        <th></th>
        <th></th>
        <th></th>
        <th>Folio</th>
        <th>Día</th>
        <th>Fecha</th>
        <th>Nombre del paciente</th>
        <th>Total</th>
        <th>Comentarios</th>
        <th>Pagado</th>
        <th>Facturado</th>
        <th>No de Factura</th>
        <th>Banco</th>
        <th>Estatus</th>
    </thead>
    <tbody>

    </tbody>
</table>

</div>
</div>

</div>
<!--/.content-->

<!--/.wrapper--><br />
<script>
    $(document).ready(function() {
        var dataTable = $('#lookup1').DataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "sSorting": false,

                "bSortable": false,
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },

                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },

            "ordering": false,

            "processing": true,
            "serverSide": true,
            "ajax": {

                url: "./?action=sales/get-all", // json datasource
                data: {
                    "searchBranchOfficeId": "<?php echo $searchBranchOfficeId ?>",
                    "searchPaymentTypeId": "<?php echo $searchPaymentTypeId ?>",
                    "searchStatusId": "<?php echo $searchStatusId ?>"
                },
                type: "post", // method  , by default get
                error: function() { // error handling
                    $(".lookup1-error").html("");
                    //$("#lookup1").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se ha encontrado ningún dato</th></tr></tbody>');
                    $("#lookup_processing").css("display", "none");
                }
            }
        });
    });

    function confirmDelete() {
        var flag = confirm("¿Seguro que deseas eliminar la venta?");
        if (flag == true) {
            return true;
        } else {
            return false;
        }
    }
</script>