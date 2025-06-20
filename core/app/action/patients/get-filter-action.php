<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 0);



$con = Database::getCon();

$filters = [
    'branchOfficeId' => $_POST["branchOfficeId"] ?? null,
    'medicId'        => $_POST["medicId"] ?? null,
    'categoryId'     => $_POST["categoryId"] ?? null,
    'companyId'      => $_POST["companyId"] ?? null
];

$where = "WHERE 1=1";
if (!empty($filters['branchOfficeId'])) {
    $where .= " AND patients.branch_office_id = " . intval($filters['branchOfficeId']);
}
if (!empty($filters['medicId'])) {
    $where .= " AND patient_treatments.medic_id = " . intval($filters['medicId']);
}
if (!empty($filters['categoryId'])) {
    $where .= " AND patients.category_id = " . intval($filters['categoryId']);
}
if (!empty($filters['companyId'])) {
    $where .= " AND patients.company_id = " . intval($filters['companyId']);
}

$sql = "SELECT 
    patients.id AS patient_id,
    patients.name AS patient_name,
    patients.sex_id,
    patients.street,
    patients.number,
    patients.colony,
    branch_offices.name AS branch_office_name,
    patients.cellphone,
    patients.birthday AS raw_birthday,
    DATE_FORMAT(patients.birthday, '%d/%m/%Y') AS birthday,
    counties.name AS county_name,
    companies.name AS company_name,
    medics.name AS medic_name,

    (
        SELECT medics.name FROM patient_treatments pt2
        INNER JOIN medics ON medics.id = pt2.medic_id
        WHERE pt2.patient_id = patients.id AND pt2.id <> patient_treatments.id
        ORDER BY pt2.start_date DESC LIMIT 1 OFFSET 0
    ) AS previous_medic_1,

    (
        SELECT medics.name FROM patient_treatments pt3
        INNER JOIN medics ON medics.id = pt3.medic_id
        WHERE pt3.patient_id = patients.id AND pt3.id <> patient_treatments.id
        ORDER BY pt3.start_date DESC LIMIT 1 OFFSET 1
    ) AS previous_medic_2,

    treatments.name AS treatment_name,
    treatment_status.name AS status_name,
    patient_treatments.reason,
    patient_treatments.cancellation_reason,
    patient_treatments.last_note,
    education_levels.name AS level_education,
    DATE_FORMAT(patient_treatments.start_date, '%d/%m/%Y') AS start_date,
    DATE_FORMAT(patient_treatments.end_date, '%d/%m/%Y') AS end_date,
    patient_treatments.default_price AS price,

    (
        SELECT COUNT(id) FROM reservations
        WHERE reservations.patient_id = patients.id
        AND reservations.status_id = 2
        AND reservations.date_at >= CONCAT(patient_treatments.start_date, ' 00:00:00')
        AND reservations.date_at <= IF(
            patient_treatments.end_date IS NULL OR patient_treatments.end_date = '0000-00-00',
            CONCAT(CURDATE(), ' 23:59:59'),
            CONCAT(patient_treatments.end_date, ' 23:59:59')
        )
    ) AS total_sessions

FROM patient_treatments
LEFT JOIN patients ON patients.id = patient_treatments.patient_id
LEFT JOIN counties ON patients.county_id = counties.id
LEFT JOIN medics ON medics.id = patient_treatments.medic_id
LEFT JOIN branch_offices ON patients.branch_office_id = branch_offices.id
LEFT JOIN treatments ON treatments.id = patient_treatments.treatment_id
LEFT JOIN treatment_status ON treatment_status.id = patient_treatments.status_id
LEFT JOIN education_levels ON education_levels.id = patients.education_level_id
LEFT JOIN companies ON companies.id = patients.company_id
$where
ORDER BY patient_treatments.start_date DESC";

$query = $con->query($sql);
$data = [];

while ($p = $query->fetch_object()) {
    $data[] = [
        $p->patient_id,
        $p->patient_name,
        $p->branch_office_name,
        $p->treatment_name,
        $p->price,
        $p->street,
        $p->number,
        $p->colony,
        $p->county_name,
        $p->birthday,
        calcularEdad($p->raw_birthday),
        $p->sex_id == 1 ? "Hombre" : "Mujer",
        $p->cellphone,
        $p->status_name,
        $p->start_date,
        $p->medic_name,
        $p->reason,
        $p->level_education,
        $p->occupation,
        $p->previous_medic_1,
        $p->previous_medic_2,
        $p->end_date,
        $p->cancellation_reason,
        calcularDuracion($p->raw_birthday, $p->start_date),
        $p->total_sessions,
        $p->last_note,
        $p->company_name,
        $p->observations
    ];
}

echo json_encode(["data" => $data]);

function calcularEdad($fechaNacimiento) {
    if (!$fechaNacimiento || $fechaNacimiento == "0000-00-00") return "";
    $birthDate = new DateTime($fechaNacimiento);
    $today = new DateTime();
    return $birthDate->diff($today)->y;
}

function calcularDuracion($start, $end) {
    if (!$start || !$end) return "";
    try {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $diff = $startDate->diff($endDate);
        return $diff->format('%m meses %d d\ías');
    } catch (Exception $e) {
        return "";
    }
}
