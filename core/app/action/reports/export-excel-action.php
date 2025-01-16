<?php
require_once 'vendor/autoload.php';

use Box\Spout\Writer\XLSX\Writer;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

// Verifica que las fechas estén disponibles
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];

    // Validar fechas
    if (!$start_date || !$end_date) {
        die('Fechas no válidas');
    }

    // Obtener los datos usando tu consulta o modelo
    // Supón que la función ReportData::getExportData obtiene los datos de la base de datos
    $data = ReportData::getExportData($start_date, $end_date);

    // Si no se encontraron datos, terminar la ejecución
    if (empty($data)) {
        die('No se encontraron datos para el rango de fechas proporcionado.');
    }

    // Crear el escritor para un archivo Excel
    $writer = new Writer();
    $writer->openToBrowser("datos_filtrados_{$start_date}_a_{$end_date}.xlsx");

    // Escribir encabezados
    $headerRow = WriterEntityFactory::createRowFromArray(['Nombre', 'Teléfono', 'Categoría']);
    $writer->addRow($headerRow);

    // Escribir datos
    foreach ($data as $row) {
        $values = [
            isset($row->name) ? $row->name : 'Sin nombre',
            isset($row->tel) ? $row->tel : 'Sin teléfono',
            isset($row->category_name) ? $row->category_name : 'Sin categoría',
        ];
        $dataRow = WriterEntityFactory::createRowFromArray($values);
        $writer->addRow($dataRow);
    }

    // Cerrar el escritor
    $writer->close();
    exit;
} else {
    // Si no se reciben las fechas, mostrar un mensaje de error
    die('No se recibieron las fechas de inicio y fin.');
}
?>
