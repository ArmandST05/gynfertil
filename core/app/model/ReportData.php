<?php
class ReportData {
    public static function getExportData($start_date, $end_date) {
        // Ajustar la consulta para filtrar por el rango de fechas
        $sql = "SELECT name, fecha_na, created_at FROM pacient WHERE created_at BETWEEN '$start_date' AND '$end_date'";
        return Executor::doit($sql);
    }
}
