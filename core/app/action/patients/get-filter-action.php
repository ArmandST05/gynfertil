<?php
require_once "core/controller/Database.php";
require_once "core/controller/PatientData.php";
require_once "core/controller/UserData.php";
require_once "core/controller/CompanyData.php";
require_once "core/controller/PatientTreatmentData.php";
require_once "vendor/autoload.php"; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = Database::getCon();

$user = UserData::getLoggedIn();
$userType = $user->user_type;
$branchOfficeId = ($userType == "su" || $userType == "co") ? $_POST['branchOfficeId'] : $user->getBranchOffice()->id;
$categoryId = $_POST['categoryId'];
$companyId = $_POST['companyId'];
$medicId = $_POST['medicId'];

$sql = "SELECT p.*, pc.name AS patient_category_name
        FROM patients p
        INNER JOIN patient_categories pc ON p.category_id = pc.id
        WHERE 1=1";

if ($branchOfficeId) {
    $sql .= " AND p.branch_office_id = " . intval($branchOfficeId);
}

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
        SELECT pt.medic_id
        FROM patient_treatments pt
        WHERE pt.patient_id = p.id
        ORDER BY pt.start_date DESC
        LIMIT 1
    ) = " . intval($medicId);
}

$sql .= " ORDER BY p.id DESC";

$query = mysqli_query($conn, $sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$headers = ['ID', 'Nombre', 'Dirección', 'Teléfono', 'Email', 'Familiar', 'Médico', 'Empresa', 'Categoría'];
$sheet->fromArray($headers, NULL, 'A1');

$rowIndex = 2;
while ($row = mysqli_fetch_assoc($query)) {
    $patient = PatientData::getById($row['id']);
    $medicName = ($patient->getLastTreatment()) ? $patient->getLastTreatment()->medic_name : "";
    $companyName = ($patient->getCompany()) ? $patient->getCompany()->name : "NO APLICA";

    $sheet->setCellValue("A$rowIndex", $row['id']);
    $sheet->setCellValue("B$rowIndex", $row['name']);
    $sheet->setCellValue("C$rowIndex", $row['street']);
    $sheet->setCellValue("D$rowIndex", $row['cellphone']);
    $sheet->setCellValue("E$rowIndex", $row['email']);
    $sheet->setCellValue("F$rowIndex", $row['relative_name']);
    $sheet->setCellValue("G$rowIndex", $medicName);
    $sheet->setCellValue("H$rowIndex", $companyName);
    $sheet->setCellValue("I$rowIndex", $row['patient_category_name']);
    $rowIndex++;
}

// Descargar el archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="pacientes-filtrados.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
