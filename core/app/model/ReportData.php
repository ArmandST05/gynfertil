<?php
class ReportData {
    public static function getExportData($start_date, $end_date) {
        $sql = "SELECT pacient.name AS name, 
                pacient.tel AS tel, 
                COALESCE(patient_categories.name, 'NO CLASIFICADO') AS category_name 
                FROM pacient 
                LEFT JOIN patient_category_treatments ON pacient.id = patient_category_treatments.patient_id 
                LEFT JOIN patient_categories ON patient_category_treatments.patient_category_id = patient_categories.id
                WHERE pacient.created_at BETWEEN '$start_date' AND '$end_date'";
        
        $query = Executor::doit($sql);
		return Model::many($query[0],new ReportData());
    }
    
    
}
