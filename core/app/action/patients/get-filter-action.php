<?php
require 'vendor/autoload.php'; // Asegúrate de ajustar la ruta si es necesario
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = Database::getCon();

$requestData = $_REQUEST;

$user = UserData::getLoggedIn();
$userId = $user->id;
$userType = $user->user_type;

$medicId = $_POST['medicId'];
$categoryId = $_POST['categoryId'];
$companyId = $_POST['companyId'];

if ($userType == "su" || $userType == "co") {
    $branchOfficeId = $_POST['branchOfficeId'];
} else {
    $branchOfficeId = $user->getBranchOffice()->id;
}

$sql = "SELECT p.*, pc.name AS patient_category_name, pc.color AS patient_category_color 
        FROM patients p 
        JOIN patient_categories pc ON p.category_id = pc.id 
        WHERE 1=1 ";

if ($branchOfficeId) $sql .= " AND p.branch_office_id = " . intval($branchOfficeId);
if ($categoryId != "all" && $categoryId != "active") {
    $sql .= " AND p.category_id = " . intval($categoryId);
} elseif ($categoryId == "active") {
    $sql .= " AND (p.category_id = 1 OR p.category_id = 4)";
}

if ($companyId != "all") {
    if ($companyId == "company") {
        $sql .= " AND (p.company_id IS NOT NULL AND p.company_id != 0)";
    } elseif ($companyId == "withoutCompany") {
        $sql .= " AND (p.company_id IS NULL OR p.company_id = 0)";
    } else {
        $sql .= " AND p.company_id = " . intval($companyId);
    }
}

if ($medicId) {
    $sql .= " AND (
        SELECT patient_treatments.medic_id
        FROM patient_treatments
        WHERE patient_treatments.patient_id = p.id
        ORDER BY start_date DESC
        LIMIT 1
    ) = " . intval($medicId);
}

$query = mysqli_query($conn, $sql) or die("Error en la consulta.");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$headers = ['ID', 'Nombre', 'Calle', 'Celular', 'Email', 'Familiar', 'Médico', 'Empresa', 'Categoría'];
$sheet->fromArray($headers, null, 'A1');

// Datos
$rowIndex = 2;
while ($row = mysqli_fetch_array($query)) {
    $patient = PatientData::getById($row["id"]);
    $medicName = ($patient->getLastTreatment()) ? $patient->getLastTreatment()->medic_name : "";
    $companyName = ($patient->getCompany()) ? $patient->getCompany()->name : "NO APLICA";

    $sheet->setCellValue("A$rowIndex", $row["id"]);
    $sheet->setCellValue("B$rowIndex", $row["name"]);
    $sheet->setCellValue("C$rowIndex", $row["street"]);
    $sheet->setCellValue("D$rowIndex", $row["cellphone"]);
    $sheet->setCellValue("E$rowIndex", $row["email"]);
    $sheet->setCellValue("F$rowIndex", $row["relative_name"]);
    $sheet->setCellValue("G$rowIndex", $medicName);
    $sheet->setCellValue("H$rowIndex", $companyName);
    $sheet->setCellValue("I$rowIndex", $row["patient_category_name"]);

    $rowIndex++;
}

// Salida del archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="pacientes_filtrados.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
