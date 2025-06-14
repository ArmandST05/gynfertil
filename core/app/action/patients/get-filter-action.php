<?php
$conn = Database::getCon();
$requestData = $_REQUEST;
$branchOfficeId = $_POST['branchOfficeId'] ?? '';
$medicId = $_POST['medicId'] ?? '';
$categoryId = $_POST['categoryId'] ?? '';
$companyId = $_POST['companyId'] ?? '';
$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;

$whereClauses = [];
$params = [];
$paramTypes = "";

// Aplicar filtros si existen
if (!empty($branchOfficeId)) {
    $whereClauses[] = "patients.branch_office_id = ?";
    $params[] = $branchOfficeId;
    $paramTypes .= "i";
}
if (!empty($medicId)) {
    $whereClauses[] = "patient_treatments.medic_id = ?";
    $params[] = $medicId;
    $paramTypes .= "i";
}
if (!empty($categoryId)) {
    $whereClauses[] = "patients.category_id = ?";
    $params[] = $categoryId;
    $paramTypes .= "i";
}
if (!empty($companyId)) {
    $whereClauses[] = "patients.company_id = ?";
    $params[] = $companyId;
    $paramTypes .= "i";
}

$whereSQL = count($whereClauses) > 0 ? " WHERE " . implode(" AND ", $whereClauses) : "";

// Total sin filtros
$resultTotal = $conn->query("SELECT COUNT(*) as total FROM patients");
$totalRecords = $resultTotal->fetch_assoc()['total'] ?? 0;

// Total con filtros
$sqlFiltered = "SELECT COUNT(DISTINCT patients.id) as total FROM patient_treatments 
    LEFT JOIN patients ON patients.id = patient_treatments.patient_id $whereSQL";
$stmtFiltered = $conn->prepare($sqlFiltered);
if ($stmtFiltered && !empty($paramTypes)) {
    $stmtFiltered->bind_param($paramTypes, ...$params);
}
$stmtFiltered->execute();
$resultFiltered = $stmtFiltered->get_result();
$totalFiltered = $resultFiltered->fetch_assoc()['total'] ?? 0;
$stmtFiltered->close();

// Agregar limit y offset
$paramsWithLimit = [...$params, intval($start), intval($length)];
$paramTypesWithLimit = $paramTypes . "ii";

// Consulta de datos
$sqlData = "
    SELECT 
      patients.id AS patient_id,
      patients.name AS patient_name,
      patients.sex_id,
      patients.curp,
      patients.street,
      patients.number,
      patients.colony,
      branch_offices.name AS branch_office_name,
      patients.cellphone,
      patients.homephone,
      patients.email,
      patients.birthday AS raw_birthday,
      patients.occupation,
      patients.observations,
      DATE_FORMAT(patients.birthday, '%d/%m/%Y') AS birthday,
      counties.name AS county_name,
      companies.name as company_name,
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
      treatments.code AS treatment_code,
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
    $whereSQL
    ORDER BY patient_treatments.start_date DESC
    LIMIT ?, ?
";

$stmtData = $conn->prepare($sqlData);
$stmtData->bind_param($paramTypesWithLimit, ...$paramsWithLimit);
$stmtData->execute();
$resultData = $stmtData->get_result();

$data = [];
while ($row = $resultData->fetch_assoc()) {
    $edad = "No especificada.";
    if (!empty($row['raw_birthday']) && $row['raw_birthday'] != '0000-00-00') {
        $birthDate = new DateTime($row['raw_birthday']);
        $today = new DateTime();
        $edad = $today->diff($birthDate)->y . " Años";
    }

    $duracion = "Fechas inválidas";
    if ($row['start_date'] !== "0000-00-00" && $row['end_date'] !== "0000-00-00") {
        $startDate = DateTime::createFromFormat('d/m/Y', $row['start_date']);
        $endDate = ($row['end_date']) ? DateTime::createFromFormat('d/m/Y', $row['end_date']) : new DateTime();
        if ($startDate && $endDate) {
            $interval = $startDate->diff($endDate);
            $duracion = floor($interval->days / 7) . ' semanas con ' . ($interval->days % 7) . ' días';
        }
    }

    $data[] = [
        $row['patient_id'],
        htmlspecialchars($row['patient_name']),
        htmlspecialchars($row['branch_office_name']),
        htmlspecialchars($row['treatment_name']),
        $row['price'],
        htmlspecialchars($row['street']),
        htmlspecialchars($row['number']),
        htmlspecialchars($row['colony']),
        htmlspecialchars($row['county_name']),
        $row['birthday'],
        $edad,
        $row['sex_id'] == 1 ? 'Hombre' : 'Mujer',
        htmlspecialchars($row['cellphone']),
        htmlspecialchars($row['status_name']),
        $row['start_date'],
        htmlspecialchars($row['medic_name']),
        htmlspecialchars($row['reason']),
        htmlspecialchars($row['level_education']),
        htmlspecialchars($row['occupation']),
        htmlspecialchars($row['previous_medic_1']),
        htmlspecialchars($row['previous_medic_2']),
        $row['end_date'],
        htmlspecialchars($row['cancellation_reason']),
        $duracion,
        $row['total_sessions'],
        htmlspecialchars($row['last_note']),
        htmlspecialchars($row['company_name']),
        htmlspecialchars($row['observations'])
    ];
}
$stmtData->close();

$response = [
    "draw" => intval($requestData['draw'] ?? 0),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
];

ob_clean();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit;
