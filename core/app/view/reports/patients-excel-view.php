<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();

// Propiedades del documento
$spreadsheet->getProperties()->setCreator('Tu Nombre')
    ->setLastModifiedBy('Tu Nombre')
    ->setTitle('Reporte de Pacientes')
    ->setSubject('Reporte')
    ->setDescription('Reporte de pacientes exportado a Excel.')
    ->setKeywords('pacientes excel exportacion')
    ->setCategory('Reporte');

// Encabezados de columnas
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'ID')
    ->setCellValue('B1', 'NOMBRE')
    ->setCellValue('C1', 'SUCURSAL')
    ->setCellValue('D1', 'TRATAMIENTO')
    ->setCellValue('E1', 'COSTO')
    ->setCellValue('F1', 'CALLE')
    ->setCellValue('G1', 'NÚMERO')
    ->setCellValue('H1', 'COLONIA')
    ->setCellValue('I1', 'MUNICIPIO')
    ->setCellValue('J1', 'FECHA DE NACIMIENTO')
    ->setCellValue('K1', 'EDAD')
    ->setCellValue('L1', 'SEXO')
    ->setCellValue('M1', 'TELÉFONOS')
    ->setCellValue('N1', 'ESTATUS')
    ->setCellValue('O1', 'INICIO SESIÓN #1')
    ->setCellValue('P1', 'PSICÓLOGO')
    ->setCellValue('Q1', 'MOTIVO DE CONSULTA')
    ->setCellValue('R1', 'ESCOLARIDAD')
    ->setCellValue('S1', 'OCUPACIÓN')
    ->setCellValue('T1', 'PSICÓLOGO ANTERIOR 1')
    ->setCellValue('U1', 'PSICÓLOGO ANTERIOR 2')
    ->setCellValue('V1', 'FECHA BAJA')
    ->setCellValue('W1', 'MOTIVO DE BAJA')
    ->setCellValue('X1', 'DURACIÓN')
    ->setCellValue('Y1', 'ÚLTIMA SESIÓN')
    ->setCellValue('Z1', 'ÚLTIMA ANOTACIÓN')
    ->setCellValue('AA1', 'REINGRESO')
    ->setCellValue('AB1', 'EMPRESA')
    ->setCellValue('AC1', 'PSIQUIATRA')
    ->setCellValue('AD1', 'OBSERVACIONES');

// Obtener filtros desde GET
$branch_id = $_GET['branch_office_id'] ?? null;
$psychologist_id = $_GET['psychologist_id'] ?? null;
$category_id = $_GET['category_id'] ?? null;
$company_id = $_GET['company_id'] ?? null;

// Obtener usuario logueado (asumiendo método UserData::getLoggedIn())
$user = UserData::getLoggedIn();
$userId = $user->id;
$userType = $user->user_type;

// Insertar datos de pacientes
$row = 2;
foreach ($patients as $patient) {
    // Obtener tratamientos (solo los primeros 3 para psicología, por ejemplo)
    $treatments = TreatmentData::getAllPatientTreatments($patient->id, 3);

    $treatmentName1 = $treatmentPrice1 = $treatmentStartDate1 = $treatmentEndDate1 = "";
    $treatmentMedicName1 = $treatmentReason1 = $treatmentNote1 = $treatmentTotalSessions1 = "";
    $treatmentDuration1 = $treatmentPsychiatrist1 = $treatmentLastNote1 = "";
    $treatmentMedicName2 = $treatmentMedicName3 = "";

    foreach ($treatments as $idx => $treatment) {
        if ($idx == 0) {
            $treatmentName1 = $treatment->treatment_name;
            $treatmentPrice1 = $treatment->default_price;
            $treatmentStartDate1 = $treatment->start_date_format;
            $treatmentEndDate1 = $treatment->end_date_format;
            $treatmentMedicName1 = $treatment->getMedic() ? $treatment->getMedic()->name : "";
            $treatmentReason1 = $treatment->reason;
            $treatmentNote1 = $treatment->cancellation_reason;
            $treatmentTotalSessions1 = $treatment->getTotalReservations()->total;
            $treatmentDuration1 = $treatment->getTreatmentDuration();
            $treatmentLastNote1 = $treatment->last_note;
            $treatmentPsychiatrist1 = $treatment->psychiatrist;
        } elseif ($idx == 1) {
            $treatmentMedicName2 = $treatment->getMedic() ? $treatment->getMedic()->name : "";
        } elseif ($idx == 2) {
            $treatmentMedicName3 = $treatment->getMedic() ? $treatment->getMedic()->name : "";
        }
    }

    // Rellenar fila en Excel
    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, $patient->id)
        ->setCellValue('B' . $row, $patient->name)
        ->setCellValue('C' . $row, $patient->getBranchOffice()->name)
        ->setCellValue('D' . $row, $treatmentName1)
        ->setCellValue('E' . $row, $treatmentPrice1)
        ->setCellValue('F' . $row, $patient->street)
        ->setCellValue('G' . $row, $patient->number)
        ->setCellValue('H' . $row, $patient->colony)
        ->setCellValue('I' . $row, $patient->getCounty() ? $patient->getCounty()->name : "")
        ->setCellValue('J' . $row, $patient->getBirthdayFormat())
        ->setCellValue('K' . $row, $patient->getAge())
        ->setCellValue('L' . $row, $patient->getSex()->name)
        ->setCellValue('M' . $row, $patient->cellphone)
        ->setCellValue('N' . $row, $patient->getCategory()->name)
        ->setCellValue('O' . $row, $treatmentStartDate1)
        ->setCellValue('P' . $row, $treatmentMedicName1)
        ->setCellValue('Q' . $row, $treatmentReason1)
        ->setCellValue('R' . $row, $patient->getEducationLevel() ? $patient->getEducationLevel()->name : "")
        ->setCellValue('S' . $row, $patient->occupation)
        ->setCellValue('T' . $row, $treatmentMedicName2)
        ->setCellValue('U' . $row, $treatmentMedicName3)
        ->setCellValue('V' . $row, $treatmentEndDate1)
        ->setCellValue('W' . $row, $treatmentNote1)
        ->setCellValue('X' . $row, $treatmentDuration1)
        ->setCellValue('Y' . $row, $treatmentTotalSessions1)
        ->setCellValue('Z' . $row, $treatmentLastNote1)
        ->setCellValue('AA' . $row, '') // REINGRESO vacío
        ->setCellValue('AB' . $row, $patient->getCompany() ? $patient->getCompany()->name : "")
        ->setCellValue('AC' . $row, $treatmentPsychiatrist1)
        ->setCellValue('AD' . $row, $patient->observations);

    $row++;
}

// Renombrar hoja
$spreadsheet->getActiveSheet()->setTitle('Pacientes');
$spreadsheet->setActiveSheetIndex(0);

// Headers para descarga del archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Pacientes.xlsx"');
header('Cache-Control: max-age=0');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
ob_end_clean();
$writer->save('php://output');
exit;
