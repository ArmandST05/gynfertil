<div class="row">
	<div class="col-md-12">
		<div class="btn-group  pull-right">
			<a href="index.php?view=expense-concepts/new" class="btn btn-default"><i class="fas fa-plus"></i> Agregar Concepto</a>
		</div>
		<h1>Lista de Conceptos</h1>
		<div class="clearfix"></div>
		<hr>
		<table id="dataTable" class="table table-bordered table-hover">
			<thead bgcolor="#eeeeee" align="center">
				<tr>
					<th>Nombre</th>
					<th>Categoría</th>
					<th style="width:80px;">Acciones</th>
				</tr>
			</thead>
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

				url: "./?action=expense-concepts/get-all", // json datasource
				type: "post", // method  , by default get
				error: function() { // error handling
					$(".lookup-error").html("");
					//$("#dataTable").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se ha encontrado ningún dato</th></tr></tbody>');
					$("#lookup_processing").css("display", "none");

				}
			}
		});
	});

	function confirmar() {
		var flag = confirm("¿Seguro que deseas eliminar el concepto?");
		if (flag == true) {
			return true;
		} else {
			return false;
		}
	}
</script>