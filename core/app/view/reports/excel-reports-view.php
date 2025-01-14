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
    <table border="1" id="dataTable">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>fecha_na</th>
                <th>Fecha de Ingreso</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los datos se cargarán aquí dinámicamente -->
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

        // Depuración: Verificar si las fechas son correctas
        console.log('Fecha de inicio: ', startDate);
        console.log('Fecha de fin: ', endDate);

        if (!startDate || !endDate) {
            alert('Por favor selecciona ambas fechas.');
            return;
        }

        $.ajax({
            url: './?action=reports/reports-excel', // Asegúrate de que esta acción devuelva JSON
            method: 'POST',
            data: { start_date: startDate, end_date: endDate },

            // Depuración: Ver qué datos se están enviando en la solicitud
            beforeSend: function() {
                console.log('Enviando datos al servidor...');
                console.log('Datos enviados:', { start_date: startDate, end_date: endDate });
            },

            success: function(response) {
                try {
                    // Depuración: Ver la respuesta cruda del servidor
                    console.log('Respuesta del servidor:', response);

                    // Intentar parsear la respuesta
                    const data = JSON.parse(response);
                    const tbody = $('#dataTable tbody');
                    tbody.empty();

                    if (data.length > 0) {
                        data.forEach(row => {
                            tbody.append(`
                                <tr>
                                    <td>${row.name}</td>
                                    <td>${row.fecha_na}</td>
                                    <td>${row.created_at}</td>
                                </tr>
                            `);
                        });

                        $('#export_start_date').val(startDate);
                        $('#export_end_date').val(endDate);
                    } else {
                        tbody.append('<tr><td colspan="3">No se encontraron datos</td></tr>');
                    }
                } catch (error) {
                    // Depuración: Si ocurre un error al parsear la respuesta
                    console.error('Error al procesar la respuesta JSON:', error);
                    alert('Error al procesar los datos. Asegúrate de que el servidor esté enviando datos en formato JSON.');
                    console.log('Respuesta cruda:', response);
                }
            },

            error: function(xhr, status, error) {
                // Depuración: Mostrar detalles de cualquier error en la solicitud AJAX
                console.error('Error en la solicitud AJAX:', status, error);
                alert('Ocurrió un error al obtener los datos.');
            }
        });
    });
});

    </script>
</body>
</html>
