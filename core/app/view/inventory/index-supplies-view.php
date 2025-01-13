<link rel="stylesheet" href="datatables/dataTables.bootstrap.css" />
<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>

<div class="row">
	<div class="col-md-12">
		<!--div class="btn-group  pull-right">
<a href="index.php?view=re" class="btn btn-default">Entradas de Medicamentos e insumos</a>
</div-->
		<div class="btn-group  pull-right">
			<a href="index.php?view=supplies/new" class="btn btn-default">Agregar Insumo</a>
		</div>
		<h1>Inventario Insumos</h1>
		<div class="clearfix"></div>
		<hr>
		<table id="lookup1" class="table table-bordered table-hover">
			<thead bgcolor="#eeeeee" align="center">
				<tr>
					<th>Nombre</th>
					<th>Disponible</th>
					<th>Tipo</th>
					<th>Inventario</th>
					<th>Insumos</th>
				</tr>
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

<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<script src="datatables/jquery.dataTables.js"></script>
<script src="datatables/dataTables.bootstrap.js"></script>
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

				url: "./?action=inventory/get-all-supplies", // json datasource
				type: "post", // method  , by default get
				error: function() { // error handling
					$(".lookup-error").html("");
					$("#lookup1").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se han encontrado datos</th></tr></tbody>');
					$("#lookup_processing").css("display", "none");
				}
			}
		});
	});
</script>