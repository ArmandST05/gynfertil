<?php
class ReportData {
    public static function getExportData($start_date, $end_date) {
        $sql = "SELECT pacient.name AS patient_name, 
                pacient.tel AS patient_telefono, 
                COALESCE(patient_categories.name, 'NO CLASIFICADO') AS category_name 
                FROM pacient 
                LEFT JOIN patient_category_treatments ON pacient.id = patient_category_treatments.patient_id 
                LEFT JOIN patient_categories ON patient_category_treatments.patient_category_id = patient_categories.id
                WHERE pacient.created_at BETWEEN '$start_date' AND '$end_date'";
    
        // Asegurarse de que la consulta se ejecute y obtener los datos
        $data = Executor::doit($sql);
    
        // Depuración: Ver si se obtienen datos
        var_dump($data);
        exit();  // Detener la ejecución para ver el resultado
    }
    
}
