<?php
$user = UserData::getLoggedIn();
$userType = (isset($user)) ? $user->user_type : null;

if ($userType == "r" || $userType == "co") {
    $searchBranchOfficeId = $user->getBranchOffice()->id;
    $branchOffices = [$user->getBranchOffice()];
} else {
    $searchBranchOfficeId = (isset($_GET["searchBranchOfficeId"])) ? $_GET["searchBranchOfficeId"] : 0;
    $branchOffices = BranchOfficeData::getAllByStatus(1);
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="btn-group  pull-right">
            <?php if ($userType == "r" || ($userType == "su" && $searchBranchOfficeId != 0)) : ?>
                <a href="index.php?view=expenses/new<?php echo ($userType == "su")?'&branchOfficeId='.$searchBranchOfficeId:''?>" class="btn btn-default"><i class="fas fa-plus"></i> Agregar Gasto</a>
            <?php endif; ?>
        </div>
        <h1>Lista de Gastos</h1>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="GET" enctype="multipart/form-data" action="index.php" role="form">
                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-1 control-label">Sucursal</label>
                        <div class="col-md-3">
                            <select name="searchBranchOfficeId" class="form-control" required>
                                <option value="">-- SELECCIONE --</option>
                                <?php foreach ($branchOffices as $branchOffice) : ?>
                                    <option value="<?php echo $branchOffice->id; ?>" <?php echo ($searchBranchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="view" value="expenses/index">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <table id="dataTable" class="table table-bordered table-hover">
            <thead bgcolor="#eeeeee" align="center">
                <th></th>
                <th></th>
                <th>Folio</th>
                <th>Día</th>
                <th>Fecha</th>
                <th>Costo</th>
                <th>Comentarios</th>
                <th>Pagado</th>
                <th>Facturado</th>
                <th>No. de Factura</th>
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
</div>
<!--/.span9-->
</div>


<!--/.wrapper--><br />
<script>
    $(document).ready(function() {
        var dataTable = $('#dataTable').DataTable({

            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
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

                url: "./?action=expenses/get-all", // json datasource
                data: {
                    "searchBranchOfficeId": "<?php echo $searchBranchOfficeId ?>"
                },
                type: "post", // method  , by default get
                error: function() { // error handling
                    $(".lookup-error").html("");
                    $("#dataTable").append('<tbody class="employee-grid-error"><tr><th colspan="10">No se ha encontrado ningún dato.</th></tr></tbody>');
                    $("#lookup_processing").css("display", "none");

                }
            }
        });
    });

    function confirmar() {
        var flag = confirm("¿Seguro que deseas eliminar el gasto?");
        if (flag == true) {
            return true;
        } else {
            return false;
        }
    }
</script>