<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar y Exportar Datos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Visualizar y Exportar Datos</h1>

    <form id="filterForm">
        <label for="start_date">Fecha de inicio:</label>
        <input type="date" name="start_date" id="start_date" required>

        <label for="end_date">Fecha de fin:</label>
        <input type="date" name="end_date" id="end_date" required>

        <button type="button" id="filterButton">Filtrar</button>
    </form>

    <h2>Datos filtrados:</h2>
    <table id="dataTable">
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

    <form action="index.php?action=reports/reports-excel" method="GET" id="exportForm">
        <input type="hidden" name="start_date" id="export_start_date">
        <input type="hidden" name="end_date" id="export_end_date">
        <button type="submit">Exportar a Excel</button>
    </form>
    <script>
$(document).ready(function() {
    $('#filterButton').on('click', function() {
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();

        console.log('Fechas enviadas - Inicio:', startDate, 'Fin:', endDate);  // Depuración de las fechas

        if (!startDate || !endDate) {
            alert('Por favor selecciona ambas fechas.');
            return;
        }

        $.ajax({
            url: './?action=reports/reports-excel', // Ruta correcta
            method: 'POST',
            data: { start_date: startDate, end_date: endDate },
            dataType: 'json', // Asegura que la respuesta se trate como JSON

            success: function(response) {
                console.log('Respuesta del servidor:', response);  // Depuración de la respuesta del servidor

                // Comprobamos si hay algún error en la respuesta
                if (response.error) {
                    console.error('Error desde el servidor:', response.error);  // Ver error específico
                    alert('Error: ' + response.error);
                    return;
                }

                // Procesamos los datos
                const tbody = $('#dataTable tbody');
                tbody.empty();
                if (response.length > 0) {
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
            },

            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', status, error);
                console.error('Respuesta completa del servidor:', xhr.responseText);  // Depuración de la respuesta
                alert('Ocurrió un error al obtener los datos.');
            }
        });
    });
});


</script>

</body>
</html>
