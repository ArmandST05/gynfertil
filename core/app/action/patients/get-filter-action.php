<?php
$conn = Database::getCon();

$requestData = $_REQUEST;
$length = isset($requestData['length']) ? intval($requestData['length']) : 20;
$start = isset($requestData['start']) ? intval($requestData['start']) : 0;
$branchOffice = $requestData['branch_office'] ?? '';

// Obtener total general (sin filtros)
$resultTotal = $conn->query("
  SELECT COUNT(*) as total 
  FROM patient_treatments
  INNER JOIN patients ON patients.id = patient_treatments.patient_id
");
$totalRecords = ($resultTotal && $row = $resultTotal->fetch_assoc()) ? intval($row['total']) : 0;

// Construcción del SQL con filtro dinámico
$sql = "SELECT 
  patients.id AS patient_id,
  patients.name AS patient_name,
  patients.sex_id,
  patients.curp,
  patients.street,
  patients.number,
  patients.colony as colony,
  branch_offices.name AS branch_office_name,
  patients.cellphone,
  patients.homephone,
  patients.email,
  patients.birthday AS raw_birthday,
  DATE_FORMAT(patients.birthday, '%d/%m/%Y') AS birthday,
  counties.name as county_name,
  medics.name as medic_name,
  treatments.name AS treatment_name,
  treatments.code AS treatment_code,
  treatment_status.name AS status_name,
  DATE_FORMAT(patient_treatments.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(patient_treatments.end_date, '%d/%m/%Y') AS end_date,
  patient_treatments.default_price as price
FROM patient_treatments
INNER JOIN patients ON patients.id = patient_treatments.patient_id
INNER JOIN counties ON patients.county_id = counties.id
INNER JOIN medics ON medics.id = patient_treatments.medic_id
INNER JOIN branch_offices ON patients.branch_office_id = branch_offices.id
INNER JOIN treatments ON treatments.id = patient_treatments.treatment_id
INNER JOIN treatment_status ON treatment_status.id = patient_treatments.status_id
WHERE 1=1";

$params = [];
$types = '';

if (!empty($branchOffice)) {
    $sql .= " AND patients.branch_office_id = ?";
    $params[] = $branchOffice;
    $types .= 'i';
}

$sql .= " ORDER BY patient_treatments.start_date DESC LIMIT ?, ?";
$params[] = $start;
$params[] = $length;
$types .= 'ii';

// Preparar, bindear y ejecutar
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

// Procesar resultados
$data = [];
while ($row = $res->fetch_assoc()) {
    $edad = "No especificada.";
    if (!empty($row['raw_birthday']) && $row['raw_birthday'] != '0000-00-00') {
        $birthDate = new DateTime($row['raw_birthday']);
        $today = new DateTime();
        $ageDiff = $today->diff($birthDate);
        $edad = $ageDiff->y . ($ageDiff->y == 1 ? " Año" : " Años");
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
        htmlspecialchars($row['treatment_code']),
        $row['end_date'],
    ];
}

$stmt->close();

$response = [
    "draw" => intval($requestData['draw'] ?? 0),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data,
];

ob_clean();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit;
    