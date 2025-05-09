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
                <div class="col-md-3">
                    <button type="button" id="filterButton" class="btn btn-primary" style="margin-top: 23px;">Filtrar</button>
                </div>
                <div class="col-md-3">
                    <button type="button" id="btnExport" class="btn btn-success" style="margin-top: 23px;">Exportar a Excel</button>
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
                    <th>Fecha de cita</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las filas se llenarán con los datos obtenidos -->
            </tbody>
        </table>
    </div>

    <script>
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
                    url: './?action=reports/reports-excel', // Ajusta esta ruta según tu backend
                    method: 'POST',
                    data: { start_date: startDate, end_date: endDate },
                    dataType: 'json',
                    success: function(response) {
                        const tbody = $('#dataTable tbody');
                        tbody.empty();

                        if (response && response.length > 0) {
                            response.forEach(row => {
                                tbody.append(`
                                    <tr>
                                        <td>${row.name || 'Sin nombre'}</td>
                                        <td>${row.tel || 'Sin teléfono'}</td>
                                        <td>${row.category_name || 'Sin categoría'}</td>
                                        <td>${row.date_at || 'Sin fecha'}</td>
                                        <td>${row.note}</td>
                                        
                                    </tr>
                                `);
                            });
                        } else {
                            tbody.append('<tr><td colspan="4">No se encontraron datos</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
        alert('Error al obtener los datos');
    }
                });
            });

            $('#btnExport').on('click', function() {
                const table = document.getElementById('dataTable'); // ID de tu tabla
                const tableHTML = `
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            table {
                                font-family: Arial, sans-serif;
                                border-collapse: collapse;
                                width: 100%;
                            }
                            th, td {
                                border: 1px solid #ddd;
                                padding: 8px;
                            }
                            th {
                                background-color: #f2f2f2;
                                text-align: left;
                            }
                        </style>
                    </head>
                    <body>
                        ${table.outerHTML}
                    </body>
                    </html>
                `;

                const filename = `datos_filtrados_${$('#start_date').val()}_a_${$('#end_date').val()}.xls`;

                const a = document.createElement('a');
                a.href = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(tableHTML);
                a.download = filename;
                a.click();
            });
        });
    </script>
</body>
</html>
