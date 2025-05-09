<?php
class ReportData {
    public static function getExportData($start_date, $end_date) {
        $sql = "SELECT 
            pacient.name AS name, 
            pacient.tel AS tel, 
            COALESCE((
                SELECT GROUP_CONCAT(DISTINCT name SEPARATOR ', ') 
                FROM patient_category_treatments
                LEFT JOIN patient_categories 
                ON patient_category_treatments.patient_category_id = patient_categories.id
                WHERE patient_category_treatments.patient_id = pacient.id
            ), 'NO CLASIFICADO') AS category_name, 
            reservation.date_at AS date_at, 
            reservation.date_at_final AS date_at_final,
            COALESCE(reservation.note, 'N/A') as note
        FROM reservation 
        INNER JOIN pacient 
            ON reservation.pacient_id = pacient.id 
        WHERE reservation.date_at BETWEEN '$start_date' AND '$end_date'
        ORDER BY reservation.date_at;";
        
        $query = Executor::doit($sql);
        return Model::many($query[0], new ReportData());
    }
    
    
    
    
    
}