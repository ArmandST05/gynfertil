<?php
header('Content-Type: application/json');  // Asegura que la respuesta sea JSON

if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    // Obtener las fechas enviadas desde el frontend
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Depuración: Verifica que las fechas se estén recibiendo correctamente
    if (!$start_date || !$end_date) {
        echo json_encode(['error' => 'Fechas inválidas.']);
        exit();
    }

    // Llamar al método para obtener los datos
    $data = ReportData::getExportData($start_date, $end_date);

    // Procesar los datos para devolverlos como JSON
    $result = [];
    if (!empty($data)) {
        foreach ($data as $row) {
            $result[] = [
                'name' => $row->name,  // Agregar un valor por defecto en caso de nulo
                'tel' => $row->tel,  // Agregar un valor por defecto en caso de nulo
                'category_name' => $row->category_name,
            ];
        }
    } else {
        $result = ['message' => 'No se encontraron datos para el rango de fechas proporcionado.'];
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($result);
    exit();
} else {
    // Si no se reciben datos, devolver un error
    echo json_encode(['error' => 'No data received']);
    exit();
}
?>
