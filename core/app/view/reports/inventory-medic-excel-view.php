<?php ob_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$month = ["00" => " ", "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

//require_once __DIR__ . '/../Bootstrap.php';
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

// Add some data
$spreadsheet->setActiveSheetIndex(0)
    //Códigos de colores
    ->setCellValue('A1', '')
    ->setCellValue('B1', 'POR CADUCAR 3 MESES')
    ->setCellValue('A2', '')
    ->setCellValue('B2', 'PROXIMOS A CADUCAR 5 Y 4 MESES')
    ->setCellValue('A3', '')
    ->setCellValue('B3', 'MEDICAMENTOS SIN MOVIMIENTO EN EL MES(VENTA MENOR A 5 PRODUCTOS)')
    ->setCellValue('A4', '')
    ->setCellValue('B4', 'CADUCIDAD SUPERIOR A 6 MESES')
    ->setCellValue('E1', date("d/m/y"))
    ->setCellValue('A5', 'PRECIO')
    ->setCellValue('B5', 'MARCA')
    ->setCellValue('C5', 'NOMBRE')
    ->setCellValue('D5', 'PRESENTACIÓN')
    ->setCellValue('E5', 'CADUCIDAD')
    ->setCellValue('F5', 'PIEZAS')
    ->setCellValue('G5', 'VENTAS MES')
    ->setCellValue('H5', 'OBSERVACIONES');

    $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');
    $sheet->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    $sheet->getStyle('A3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ED7D31');
    $sheet->getStyle('A4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('70AD47');

    $sheet->getStyle('E1')->applyFromArray([
        'font' => [
            'bold'  =>  true,
            'size'  =>  21,
            'name'  =>  'Arial'
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
    ]);

    $styleArrayTitle = [
        'font' => [
            'bold'  =>  true,
            'size'  =>  14,
            'name'  =>  'Arial',
            'color' => array('rgb' => 'FFFFFF'),
        ]
    ];
$sheet->getStyle('A5:H5')->applyFromArray($styleArrayTitle);
$sheet->getStyle('A5:H5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('2E75B5');
//Llenar datos de productos
$products = ProductData::getInventoryProducts();

//PRIORIDADES DE COLORES
//(1) #FF0000 POR CADUCAR 3 MESES
//(2) #FFFF00 PRÓXIMOS A CADUCAR 4 Y 5 MESES
//(3) #70AD47 CADUCIDAD SUPERIOR A 6 MESES
$expirationArrayColors = [1 => "FF0000", 2 => "FFFF00", 3 => "70AD47"];

$indexRowProduct = 6;

foreach ($products as $product) {
    $stock = OperationData::getStockByProduct($product->id);
    $expirationDates = OperationData::getAllExpirationDatesByProduct($product->id);
    $totalSaleData = OperationData::getTotalSalesByProduct($product->id);
    $totalSale = ($totalSaleData) ? $totalSaleData->q : 0;

    //Obtiene las ventas realizadas del producto en el último mes
    $totalSalesLMData = OperationData::getTotalSalesByProductDates($product->id, (date("Y-m-d", strtotime("-1 months"))), date("Y-m-d"));
    $totalSalesLM = ($totalSalesLMData) ? $totalSalesLMData->total : 0;
    if ($totalSalesLM <= 4) { //Hubo ventas mínimas
        $colorTdSales = "ED7D31";
    } else { //Sí hubo ventas
        $colorTdSales = "";
    }

    //FECHAS DE CADUCIDAD DE PRODUCTOS
    $stringExpirationDates = "";
    $expirationPriorityColor = "";
    $expirationKeysProduct = []; //Array de las prioridades de fecha de caducidad del producto
    $sumq = 0;
    $sumac = 0;
    $res = 0;
    $can = 0;

    //Ciclar fechas de caducidad de productos
    foreach ($expirationDates as $expirationDate) {
        $dateNow = date('Ym');
        $sumq = $sumq + $expirationDate->q; //Sumatoria de Cantidad del prodcuto en todas las fechas 

        //Sumatoria de cantidad es menor o igual a cantidad de la fecha ciclada
        if ($sumq <= $expirationDate->q) {
            //Calcular inventario actual, entradas - salidas
            $can = $expirationDate->q - $totalSale;

            if ($can > 0) {
                //Hay inventario
                $spanColor = "#000000";
                if ($expirationDate->difM >= 6) {
                    $expirationKeysProduct[] = 3; //Prioridad
                } else if ($expirationDate->difM  == 4 || $expirationDate->difM == 5) {
                    $expirationKeysProduct[] = 2; //Prioridad
                } else if ($expirationDate->difM  <= 3) { //Caducado o casi
                    //$spanColor = "#C14600";
                    $expirationKeysProduct[] = 1; //Prioridad
                }
                $stringExpirationDates .= $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  " . $expirationDate->difM . " Meses \n";
            } else {
                //No hay en inventario
                $res = $res + $expirationDate->q;
                $r = $res - $totalSale;
            }
        } else if ($sumq >= $expirationDate->q) {
            //Sumatoria de cantidad es mayor o igual a cantidad de la fecha
            $sumac = $sumac + $expirationDate->q;
            $canT = $sumq - $totalSale;

            $tot = $sumq - $sumac;
            if ($canT <= $expirationDate->q) {
                if ($canT > 0) {
                    //Hay inventario
                    $spanColor = "#000000";
                    if ($expirationDate->difM >= 6) {
                        $expirationKeysProduct[] = 3; //Prioridad
                    } else if ($expirationDate->difM  == 4 || $expirationDate->difM == 5) {
                        $expirationKeysProduct[] = 2; //Prioridad
                    } else if ($expirationDate->difM  <= 3) { //Caducado o casi
                        $expirationKeysProduct[] = 1; //Prioridad
                    }
                    $stringExpirationDates .= $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  " . $expirationDate->difM . " Meses \n";
                }
            } else {
                $spanColor = "#000000";
                if ($expirationDate->difM >= 6) {
                    $expirationKeysProduct[] = 3; //Prioridad
                } else if ($expirationDate->difM  == 4 || $expirationDate->difM == 5) {
                    $expirationKeysProduct[] = 2; //Prioridad
                } else if ($expirationDate->difM  <= 3) { //Caducado o casi
                    $expirationKeysProduct[] = 1; //Prioridad
                }
                $stringExpirationDates .= $expirationDate->exp . "-  " . $month[$expirationDate->mes] . "  " . $expirationDate->difM . " Meses \n";
            }
        }
    }

    //Definir el color de la celda de fechas de expiración, se selecciona el de mayor prioridad
    if ($expirationKeysProduct) {
        $expirationPriorityColor = $expirationArrayColors[min($expirationKeysProduct)];
    } else {
        $expirationPriorityColor = "FFFFFF";
    }

    if($colorTdSales){
        $spreadsheet->getActiveSheet()->getStyle('B'.$indexRowProduct.':G'.$indexRowProduct)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorTdSales);
    }  
    $spreadsheet->getActiveSheet()->getStyle('E'.$indexRowProduct)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($expirationPriorityColor);
    
    //Especificar datos en la fila
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A' . $indexRowProduct, '$' . number_format($product->price_out, 2))
    ->setCellValue('B' . $indexRowProduct, $product->brand)
    ->setCellValue('C' . $indexRowProduct, $product->name)
    ->setCellValue('D' . $indexRowProduct, $product->presentation)
    ->setCellValue('E' . $indexRowProduct, $stringExpirationDates)
    ->setCellValue('F' . $indexRowProduct, $stock)
    ->setCellValue('G' . $indexRowProduct, $totalSalesLM)
    ->setCellValue('H' . $indexRowProduct, "");

    $sheet->getStyle('E'.$indexRowProduct)->getAlignment()->setWrapText(true);
    $indexRowProduct++;
}


// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('INVENTARIO');

//COMBINAR CELDAS
//Códigos de colores
$sheet->mergeCells('B1:D1');
$sheet->mergeCells('B2:D2');
$sheet->mergeCells('B3:D3');
$sheet->mergeCells('B4:D4');
//Fecha
$sheet->mergeCells('E1:H4');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

foreach(range('A','H') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

ob_end_clean();
ob_clean();
// Redirect output to a client’s web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Reporte Inventario Doctoras '.date("d-m-Y").'.xls"');
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