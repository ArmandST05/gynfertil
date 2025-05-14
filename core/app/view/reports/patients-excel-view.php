<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);
    return;
}

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');

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

// Filtros desde GET
$branch_id = isset($_GET['branch_office_id']) ? $_GET['branch_office_id'] : null;
$psychologist_id = isset($_GET['psychologist_id']) ? $_GET['psychologist_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$company_id = isset($_GET['company_id']) ? $_GET['company_id'] : null;

// Usuario
$user = UserData::getLoggedIn();
$userId = $user->id;
$userType = $user->user_type;

// Obtener pacientes con filtros
if ($userType == "su" || $userType == "co") {
    $patients = PatientData::getFiltered($branch_id, $psychologist_id, $category_id, $company_id);
} else {
    $patients = PatientData::getFiltered($user->branch_office_id, $psychologist_id, $category_id, $company_id);
}

$index = 2;
foreach ($patients as $patient) {
    $treatments = TreatmentData::getAllPatientTreatments($patient->id, 3);

    $treatmentName1 = "";
    $treatmentPrice1 = "";
    $treatmentStartDate1 = "";
    $treatmentEndDate1 = "";
    $treatmentMedicName1 = "";
    $treatmentReason1 = "";
    $treatmentNote1 = "";
    $treatmentTotalSesions1 = "";
    $treatmentDuration1 = "";
    $treatmentPsychiatrist1 = "";
    $treatmentLastNote1 = "";
    $treatmentMedicName2 = "";
    $treatmentMedicName3 = "";

    foreach ($treatments as $indexTreatment => $treatment) {
        if ($indexTreatment == 0) {
            $treatmentName1 = $treatment->treatment_name;
            $treatmentPrice1 = $treatment->default_price;
            $treatmentStartDate1 = $treatment->start_date_format;
            $treatmentEndDate1 = $treatment->end_date_format;
            $treatmentMedicName1 = (($treatment->getMedic()) ? $treatment->getMedic()->name : "");
            $treatmentReason1 = $treatment->reason;
            $treatmentNote1 = $treatment->cancellation_reason;
            $treatmentTotalSesions1 = $treatment->getTotalReservations()->total;
            $treatmentDuration1 = $treatment->getTreatmentDuration();
            $treatmentLastNote1 = $treatment->last_note;
            $treatmentPsychiatrist1 = $treatment->psychiatrist;
        } else if ($indexTreatment == 1) {
            $treatmentMedicName2 = (($treatment->getMedic()) ? $treatment->getMedic()->name : "");
        } else if ($indexTreatment == 2) {
            $treatmentMedicName3 = (($treatment->getMedic()) ? $treatment->getMedic()->name : "");
        }
    }

    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $index, $patient->id)
        ->setCellValue('B' . $index, $patient->name)
        ->setCellValue('C' . $index, $patient->getBranchOffice()->name)
        ->setCellValue('D' . $index, $treatmentName1)
        ->setCellValue('E' . $index, $treatmentPrice1)
        ->setCellValue('F' . $index, $patient->street)
        ->setCellValue('G' . $index, $patient->number)
        ->setCellValue('H' . $index, $patient->colony)
        ->setCellValue('I' . $index, (($patient->getCounty()) ? $patient->getCounty()->name : ""))
        ->setCellValue('J' . $index, $patient->getBirthdayFormat())
        ->setCellValue('K' . $index, $patient->getAge())
        ->setCellValue('L' . $index, $patient->getSex()->name)
        ->setCellValue('M' . $index, $patient->cellphone)
        ->setCellValue('N' . $index, $patient->getCategory()->name)
        ->setCellValue('O' . $index, $treatmentStartDate1)
        ->setCellValue('P' . $index, $treatmentMedicName1)
        ->setCellValue('Q' . $index, $treatmentReason1)
        ->setCellValue('R' . $index, (($patient->getEducationLevel()) ? $patient->getEducationLevel()->name : ""))
        ->setCellValue('S' . $index, $patient->occupation)
        ->setCellValue('T' . $index, $treatmentMedicName2)
        ->setCellValue('U' . $index, $treatmentMedicName3)
        ->setCellValue('V' . $index, $treatmentEndDate1)
        ->setCellValue('W' . $index, $treatmentNote1)
        ->setCellValue('X' . $index, $treatmentDuration1)
        ->setCellValue('Y' . $index, $treatmentTotalSesions1)
        ->setCellValue('Z' . $index, $treatmentLastNote1)
        ->setCellValue('AA' . $index, '')
        ->setCellValue('AB' . $index, (($patient->getCompany()) ? $patient->getCompany()->name : ""))
        ->setCellValue('AC' . $index, $treatmentPsychiatrist1)
        ->setCellValue('AD' . $index, $patient->observations);

    $index++;
}

$spreadsheet->getActiveSheet()->setTitle('Pacientes');
$spreadsheet->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Pacientes.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
ob_end_clean();
$writer->save('php://output');
exit;
