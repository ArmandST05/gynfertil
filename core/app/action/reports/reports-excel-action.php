<?php
if(count($_POST) > 0) {
    $start_date = $_POST['start_date']; // Recibir el start_date
    $end_date = $_POST['end_date']; // Recibir el end_date
    
    $reportData = new ReportData();
    $data = $reportData->getExportData($start_date, $end_date); // Pasar los parámetros al método
    
    // Devuelve los datos en formato JSON
    echo json_encode($data);
}
