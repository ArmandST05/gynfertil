<?php
header('Content-Type: application/json');  // Asegura que la respuesta sea JSON

if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    // Obtener las fechas enviadas desde el frontend
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validar que las fechas sean correctas
    if (!$start_date || !$end_date) {
        echo json_encode(['error' => 'Fechas inválidas.']);
        exit();
    }

    // Obtener los datos usando el modelo
    $data = ReportData::getExportData($start_date, $end_date);

    // Verificar si se obtuvieron datos
    if (empty($data)) {
        echo json_encode(['message' => 'No se encontraron datos para el rango de fechas proporcionado.']);
    } else {
        // Convertir los datos en un formato adecuado para el JSON
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'name' => $row->name,
                'tel' => $row->tel,
                'category_name' => $row->category_name,
                'date_at' => $row->date_at,
                'note' => $row->note
                
                
            ];
        }

        // Devolver los datos en formato JSON
        echo json_encode($result);
    }

    exit();
} else {
    // Si no se reciben datos, devolver un error
    echo json_encode(['error' => 'No data received']);
    exit();
}
