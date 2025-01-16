<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar y Exportar Datos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
        <h1 class="text-center mb-4">Visualizar y Exportar Datos</h1>

          <!-- Formulario para filtrar datos -->
          <form id="filterForm" class="mb-4">
    <div class="row mb-3 d-flex align-items-center">
        <div class="col-md-3">
            <label for="start_date" class="form-label">Fecha de inicio:</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>
        
        <div class="col-md-3">
            <label for="end_date" class="form-label">Fecha de fin:</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
        </div>
        
        <div class="col-md-3 justify-content-between">
            <!-- Botón Filtrar -->
            <button type="button" id="filterButton" class="btn btn-primary" style="margin-top: 23px;">Filtrar</button>
        </div>
        
        <div class="col-md-3 justify-content-between">

            <div class="col-md-3 justify-content-between">

            <button type="button" id="exportButton" class="btn btn-success" style="margin-top: 23px;">Exportar a Excel</button>
        </div>
        </div>
    </div>
</form>



        <!-- Tabla para mostrar datos -->
        <h2>Datos filtrados:</h2>
        <table id="dataTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las filas se llenarán con los datos obtenidos -->
            </tbody>
        </table>
    </div>


    <script>
$(document).ready(function() {
    $('#exportButton').on('click', function() {
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();

        // Validar que las fechas sean proporcionadas
        if (!startDate || !endDate) {
            alert('Por favor selecciona ambas fechas.');
            return;
        }

        // Realizar la solicitud AJAX para exportar a Excel
        $.ajax({
            url: './?action=reports/export-excel', // Ruta que manejará la exportación
            method: 'GET',
            data: { start_date: startDate, end_date: endDate },
            success: function(response) {
                // Crear un enlace temporal para descargar el archivo Excel
                const blob = new Blob([response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `datos_filtrados_${startDate}_a_${endDate}.xlsx`;  // Nombre del archivo
                link.click();
            },
            error: function(xhr, status, error) {
                console.error('Error al exportar los datos:', status, error);
                alert('Ocurrió un error al intentar exportar los datos.');
            }
        });
    });
});


    $(document).ready(function() {
        $('#filterButton').on('click', function() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            // Validar que las fechas sean proporcionadas
            if (!startDate || !endDate) {
                alert('Por favor selecciona ambas fechas.');
                return;
            }

            // Realizar la solicitud AJAX para obtener datos
            $.ajax({
                url: './?action=reports/reports-excel', // Asegúrate de que esta sea la ruta correcta
                method: 'POST',
                data: { start_date: startDate, end_date: endDate },
                dataType: 'json', // Asegura que la respuesta se trate como JSON

                success: function(response) {
                    const tbody = $('#dataTable tbody');
                    tbody.empty(); // Limpiar la tabla antes de llenarla

                    // Validar si hubo algún error en la respuesta
                    if (response.error) {
                        alert('Error: ' + response.error);
                        return;
                    }

                    // Llenar la tabla con los datos obtenidos
                    if (response && response.length > 0) {  // Cambié 'response.data' por 'response'
                        response.forEach(row => {
                            tbody.append(`
                                <tr>
                                    <td>${row.name || 'Sin nombre'}</td>
                                    <td>${row.tel || 'Sin teléfono'}</td>
                                    <td>${row.category_name || 'Sin categoría'}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append('<tr><td colspan="3">No se encontraron datos</td></tr>');
                    }

                    // Actualizar los campos ocultos del formulario de exportación
                    $('#export_start_date').val(startDate);
                    $('#export_end_date').val(endDate);
                },

                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', status, error);
                    console.error('Respuesta completa del servidor:', xhr.responseText); // Esto mostrará lo que devuelve el servidor
                    alert('Ocurrió un error al obtener los datos. Revisa la consola para más detalles.');
                }
            });
        });
    });
</script>

</body>
</html>
