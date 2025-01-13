<?php ob_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

$month = ["00" => " ", "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);
    return;
}

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties

$spreadsheet->getProperties()->setCreator('Techno Consulting')
    ->setLastModifiedBy('Techno Consulting')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');

$styleArrayTitle = [
    'font' => [
        'bold'  =>  true,
        'size'  =>  14,
        'name'  =>  'Arial',
        'color' => array('rgb' => '000000'),
    ]
];

$styleArraySubtitle = [
    'font' => [
        'bold'  =>  true,
        'size'  =>  13,
        'name'  =>  'Arial',
        'color' => array('rgb' => '000000'),
    ]
];

$styleArrayTitleTable = [
    'font' => [
        'bold'  =>  true,
        'size'  =>  11,
        'name'  =>  'Arial',
        'color' => array('rgb' => '000000'),
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
];

$styleArraySubtotalTable = [
    'font' => [
        'bold'  =>  true,
        'size'  =>  10,
        'name'  =>  'Arial',
        'color' => array('rgb' => '2A8AC4'),
    ]
];

$styleArraySubtotalGeneral = [
    'font' => [
        'bold'  =>  true,
        'size'  =>  12,
        'name'  =>  'Arial',
        'color' => array('rgb' => '2A8AC4'),
    ]
];

$styleArrayTotal = [
    'font' => [
        'bold'  =>  true,
        'size'  =>  14,
        'name'  =>  'Arial',
        'color' => array('rgb' => '2A8AC4'),
    ]
];

$styleArrayTableBorders = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]/*,
        'bottom' => [
            'borderStyle' => Border::BORDER_THIN
        ],
        'top' => [
            'borderStyle' => Border::BORDER_THIN
        ],
        'left' => [
            'borderStyle' => Border::BORDER_THIN
        ],
        'rigth' => [
            'borderStyle' => Border::BORDER_THIN
        ]*/
    ]
];

$bankAccounts = BankAccountData::getAllByStatus(1);
$banks = BankData::getAll();
$companyAccounts = CompanyAccountData::getAll();

$startDate = (isset($_GET["startDate"])) ? $_GET["startDate"] : date("Y-m-d");
$endDate = (isset($_GET["endDate"])) ? $_GET["endDate"] : date("Y-m-d");

$companyAccountId = (isset($_GET["companyAccountId"])) ? $_GET["companyAccountId"] : 0;
$bankId = (isset($_GET["bankId"])) ? $_GET["bankId"] : 0;
$bankAccountId = (isset($_GET["bankAccountId"])) ? $_GET["bankAccountId"] : "0";
$paymentTypeId = (isset($_GET["paymentTypeId"])) ? $_GET["paymentTypeId"] : "0";

$isInvoice = (isset($_GET["isInvoice"])) ? $_GET["isInvoice"] : "all";

$subtitle = "";
if ($isInvoice == "1") {
    $subtitle = "SOLICITARON FACTURA";
} else if ($isInvoice == "0") {
    $subtitle = "NO FACTURAR (PÚBLICO EN GENERAL)";
}

$selectedBanks = [];
if ($bankAccountId == "0") { //Obtener las cuentas de cierta compañía
    $selectedBankAccounts = BankAccountData::getAllByCompanyAccount($companyAccountId);
    foreach ($selectedBankAccounts as $selectedBankAccount) {
        $selectedBanks[$selectedBankAccount->bank_id][] = $selectedBankAccount;
    }
} else { //Obtener datos de compañía en específico
    $selectedBankAccount = BankAccountData::getById($bankAccountId);
    $selectedBanks[$selectedBankAccount->bank_id][] = $selectedBankAccount;
}
$companyTotal = 0;

$indexRow = 1;

foreach ($selectedBanks as $index => $selectedBank) {
    $bankData = BankData::getById($index);

    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, $bankData->name);
    $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArrayTitle);
    $indexRow++;

    foreach ($selectedBank as $selectedBankAccount){
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, $selectedBankAccount->name);
        $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArraySubtitle);
        $indexRow++;

        $totalCards = 0;
        $totalTransfers = 0;
        $numberCards = 0;
        $numberTransfers = 0;

        //TABLA DE TARJETAS (Mostrar cuando se vayan a ver todos los tipos de pagos y cuando no se seleccionaran transferencias)
        if ($paymentTypeId == "0" || $paymentTypeId != "10"){
            $searchPaymentTypeId = ($paymentTypeId == 0) ? "cards" : $paymentTypeId;
            $cardPayments = SellData::getAllPaymentsByDatesTypeBankAccountInvoice($startDate, $endDate, $searchPaymentTypeId, $selectedBankAccount->id, $isInvoice);
            
            if (count($cardPayments) > 0){
                $sheet->getStyle('A'.$indexRow.':H'.($indexRow+count($cardPayments)+1))->applyFromArray($styleArrayTableBorders);

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "TARJETAS");
                $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArrayTitleTable);
                $sheet->mergeCells('A'.$indexRow.':H'.$indexRow);
                $indexRow++;

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $indexRow, "Fecha")
                    ->setCellValue('B' . $indexRow, "Hora")
                    ->setCellValue('C' . $indexRow, "Paciente")
                    ->setCellValue('D' . $indexRow, "Cuenta")
                    ->setCellValue('E' . $indexRow, "Forma Pago")
                    ->setCellValue('F' . $indexRow, "Total")
                    ->setCellValue('G' . $indexRow, "Factura")
                    ->setCellValue('H' . $indexRow, "Número Factura");
                $sheet->getStyle('A'.$indexRow.':H'.$indexRow)->applyFromArray($styleArrayTitleTable);
                $indexRow++;

                foreach ($cardPayments as $payment){
                    $totalCards += $payment->total;
                    $numberCards++;
                    if ($payment->invoice_number != "") {
                        $class = "success";
                    } else {
                        $class = "danger";
                    }
                
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $indexRow, $payment->date_format)
                        ->setCellValue('B' . $indexRow, $payment->hour_format)
                        ->setCellValue('C' . $indexRow, $payment->patient_name)
                        ->setCellValue('D' . $indexRow, $selectedBankAccount->name)
                        ->setCellValue('E' . $indexRow, $payment->payment_type_name)
                        ->setCellValue('F' . $indexRow, number_format($payment->total, 2))
                        ->setCellValue('G' . $indexRow, (($payment->is_invoice == 1) ? "SÍ SOLICITÓ" : "NO SOLICITÓ"))
                        ->setCellValue('H' . $indexRow, $payment->invoice_number);
                    $indexRow++;
                }
                $indexRow++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "Número de transacciones tarjeta:".$numberCards);
                $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArraySubtotalTable);
                $indexRow++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "Total de transacciones tarjeta: $".number_format($totalCards, 2));
                $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArraySubtotalTable);
                $indexRow++;
            }else{
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "No se encontraron transacciones de tarjetas.");
                $indexRow++;
            }
            $companyTotal += $totalCards;
        }

        //TABLA DE TRANSFERENCIAS (Mostrar cuando se vayan a ver todos los tipos de pagos y cuando se seleccionaran transferencias)
        if ($paymentTypeId == "0" || $paymentTypeId == "10"){
            $searchPaymentTypeId = 10; //Buscar y mostrar sólo transferencias
            $transferPayments = SellData::getAllPaymentsByDatesTypeBankAccountInvoice($startDate, $endDate, $searchPaymentTypeId, $selectedBankAccount->id, $isInvoice);
            $indexRow++;
            if (count($transferPayments) > 0){
                $sheet->getStyle('A'.$indexRow.':H'.($indexRow+count($transferPayments))+1)->applyFromArray($styleArrayTableBorders);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "TRANSFERENCIAS");
                $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArrayTitleTable);
                $sheet->mergeCells('A'.$indexRow.':H'.$indexRow);
                $indexRow++;

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $indexRow, "Fecha")
                    ->setCellValue('B' . $indexRow, "Hora")
                    ->setCellValue('C' . $indexRow, "Paciente")
                    ->setCellValue('D' . $indexRow, "Cuenta")
                    ->setCellValue('E' . $indexRow, "Forma Pago")
                    ->setCellValue('F' . $indexRow, "Total")
                    ->setCellValue('G' . $indexRow, "Factura")
                    ->setCellValue('H' . $indexRow, "Número Factura");
                $sheet->getStyle('A'.$indexRow.':H'.$indexRow)->applyFromArray($styleArrayTitleTable);
                $indexRow++;

                foreach ($transferPayments as $payment){
                    $totalTransfers += $payment->total;
                    $numberTransfers++;
                    if ($payment->invoice_number != "") {
                        $class = "success";
                    } else {
                        $class = "danger";
                    }

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $indexRow, $payment->date_format)
                        ->setCellValue('B' . $indexRow, $payment->hour_format)
                        ->setCellValue('C' . $indexRow, $payment->patient_name)
                        ->setCellValue('D' . $indexRow, $selectedBankAccount->name)
                        ->setCellValue('E' . $indexRow, $payment->payment_type_name)
                        ->setCellValue('F' . $indexRow, number_format($payment->total, 2))
                        ->setCellValue('G' . $indexRow, (($payment->is_invoice == 1) ? "SÍ SOLICITÓ" : "NO SOLICITÓ"))
                        ->setCellValue('H' . $indexRow, $payment->invoice_number);
                    $indexRow++;
                }
                $indexRow++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "Número de transferencias:".$numberTransfers);
                $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArraySubtotalTable);
                $indexRow++;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "Total de transferencias: $".number_format($totalTransfers, 2));
                $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArraySubtotalTable);
                $indexRow++;
            }else{
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "No se encontraron transferencias.");
                $indexRow++;
            }
            $companyTotal += $totalTransfers;
        }

        $indexRow++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "NÚMERO TRANSACCIONES CUENTA: ".number_format($numberCards + $numberTransfers));
        $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArraySubtotalGeneral);
        $indexRow++;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "TOTAL TRANSACCIONES CUENTA: $".number_format($totalCards + $totalTransfers, 2));
        $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArraySubtotalGeneral);
        $indexRow++;
    }
}
if ($companyAccountId != "0"){
    $indexRow++;
    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $indexRow, "TOTAL GENERAL EMPRESA: $".number_format($companyTotal, 2));
    $sheet->getStyle('A'.$indexRow)->applyFromArray($styleArrayTotal);
}

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('TRANSACCIONES');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

foreach(range('B','H') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

ob_end_clean();
ob_clean();
// Redirect output to a client’s web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Reporte Transacciones '.$startDate.'-'.$endDate.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');
exit();