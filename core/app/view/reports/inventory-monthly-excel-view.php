<?php ob_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//INICIO OBTENER DATOS GENERALES PRODUCTOS
$months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

$actualDate = date('Y-m-d');
$searchMonth = (isset($_GET["searchMonth"])) ? $_GET["searchMonth"] : date('m');
$searchYear = (isset($_GET["searchYear"])) ? $_GET["searchYear"] : date('Y');

$startDate = date($searchYear . "-" . $searchMonth . "-01");
$endDate = date("Y-m-t", strtotime($startDate));

$previousStartDate = date_create($startDate)->modify('-1 day')->format('Y-m-d');

$interval = new DateInterval('P1D');
$dateRange = new DatePeriod(date_create($startDate), $interval, date_create($endDate)->modify('+1 day'));

$products = ProductData::getInventoryProducts();
$totalProducts = count($products);

$maxLotNumber = 0;//Número máximo de fechas o lotes que tienen los productos, al determinar el mayor número es la cantidad de filas que se crearán
//OBTENER DATOS DE LOS PRODUCTOS (FECHAS DE CADUCIDAD) EN EL MES QUE SE CONSULTA EL REPORTE
foreach ($products as $product) {
  $productMaxLotNumber = 0;

  //Calcular stock de los productos y fechas de caducidad
  $stockActualMonth = OperationData::getStockByProductDate($product->id,$endDate);//Stock actual al final del mes
  $stockInitialMonth = OperationData::getStockByProductDate($product->id,$previousStartDate);//Stock con el que se inició el mes
  $totalInputsMonthData = OperationData::getTotalInputsByProductDates($product->id,$startDate,$endDate);//Stock añadido en el transcurso del mes
  $totalInputsMonth = ($totalInputsMonthData) ? $totalInputsMonthData->total : 0;

  $expirationDates = OperationData::getExpirationDatesByProductMaxDate($product->id,$endDate);
  $totalSaleData = OperationData::getTotalSalesByProductMaxDate($product->id,$endDate);
  $totalSale = ($totalSaleData) ? $totalSaleData->q : 0;

  //Variables a utilizar en la tabla
  $product->stock = $stockActualMonth;
  $product->initial_stock = $stockInitialMonth;
  $product->added_month_stock = $totalInputsMonth;
  $product->total_sale = $totalSale;
  $product->lots = [];
  $product->week_sales = 0;
  $product->month_sales = 0;
  $product->week_inputs = 0;
  $product->month_inputs = 0;

  //FECHAS DE CADUCIDAD DE PRODUCTOS
  $sumq = 0;
  $sumac = 0;
  $res = 0;
  $can = 0;

  //Ciclar fechas de caducidad de productos
  foreach ($expirationDates as $expirationDate) {
    $dateNow = date('Ym');
    $sumq = $sumq + $expirationDate->q; //Sumatoria de Cantidad del producto en todas las fechas 

    //Sumatoria de cantidad es menor o igual a cantidad de la fecha ciclada
    if ($sumq <= $expirationDate->q) {
      //Calcular inventario actual, entradas - salidas
      $can = $expirationDate->q - $totalSale;

      if ($can > 0) {
        //Hay inventario
        $product->lots[] = array("lot" => $expirationDate->lot,"date"=>$expirationDate->dateExpiry,"quantity"=>$can);
        $productMaxLotNumber++;
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
          $product->lots[] = array("lot" => $expirationDate->lot,"date"=>$expirationDate->dateExpiry,"quantity"=>$canT);
          $productMaxLotNumber++;
        }
      } else {
        $product->lots[] = array("lot" => $expirationDate->lot,"date"=>$expirationDate->dateExpiry,"quantity"=>$expirationDate->q);
        $productMaxLotNumber++;
      }
    }

  }

  //Validar número de fechas/lotes
  if($productMaxLotNumber > $maxLotNumber){
    $maxLotNumber = $productMaxLotNumber;
  }

}
//FIN OBTENER DATOS GENERALES PRODUCTOS


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

$spreadsheet->getProperties()->setCreator('Techno Consuting')
    ->setLastModifiedBy('Techno Consulting')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');


//FECHAS Y LOTES DE CADUCIDAD
$colTitleLot= 'C';
for ($i = 1; $i <= $totalProducts; $i++) {
    $sheet->setCellValue(($colTitleLot."1"), "PIEZAS");
    $sheet->getStyle($colTitleLot.'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C55A11');
    $colTitleLot++;
    $sheet->setCellValue(($colTitleLot."1"), "DETALLES");
    $sheet->getStyle($colTitleLot.'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C55A11');
    $colTitleLot++;
}
$lotRow = 2;
for($i = 0; $i < $maxLotNumber;$i++){
  $colLotQuantity= 'C';
  foreach ($products as $product){
    $productLot = "LOTE";
    $productLotDate = "FECHA";
    $productLotQuantity = "";

    $lotsArray = $product->lots;
    if(isset($lotsArray[$i])){
      $productLot = $lotsArray[$i]['lot'];
      $productLotDate = $lotsArray[$i]['date'];
      $productLotQuantity = $lotsArray[$i]['quantity'];
    }

    $sheet->setCellValue(($colLotQuantity.($lotRow)), $productLotQuantity);
    $sheet->getStyle($colLotQuantity.$lotRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9EAD3');
    
    $sheet->mergeCells($colLotQuantity.($lotRow).":".$colLotQuantity.($lotRow+1));
    $colLotQuantity++;
    $sheet->setCellValue(($colLotQuantity.($lotRow)), $productLotDate);
    $sheet->getStyle($colLotQuantity.$lotRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('93C47D');
    
    $sheet->setCellValue(($colLotQuantity.($lotRow+1)), $productLot);
    $sheet->getStyle($colLotQuantity.($lotRow+1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('93C47D');
    $colLotQuantity++;
  }
  $lotRow = $lotRow+2;
}

//FIN FECHAS Y LOTES DE CADUCIDAD
$sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('70AD47');
$sheet->mergeCells('A1:B'.(($maxLotNumber*2)+2));
$initProductDataRow = (($maxLotNumber*2)+2);//Especificar productos

$colNameProduct= 'C';
foreach ($products as $product) {
    $sheet->setCellValue(($colNameProduct.$initProductDataRow), $product->name);
    $sheet->getStyle($colNameProduct.$initMonthDataRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C55A11');
    
    $startColumn = $colNameProduct;
    $colNameProduct++;
    $sheet->mergeCells($startColumn.$initProductDataRow.':'.($colNameProduct).$initProductDataRow);
}

$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A'.$initProductDataRow+1, 'FECHA')
    ->setCellValue('B'.$initProductDataRow+1, 'INICIO DEL MES')
    ->setCellValue('B'.$initProductDataRow+2, 'TOTAL INGRESO')
    ->setCellValue('B'.$initProductDataRow+3, 'EXISTENCIAS')
    ->setCellValue('B'.$initProductDataRow+4, '');

    $sheet->mergeCells('A'.($initProductDataRow+1).':A'.($initProductDataRow+4));
    $sheet->getStyle('B'.$initProductDataRow+1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('93C47D');
    $sheet->getStyle('B'.$initProductDataRow+2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');
    $sheet->getStyle('B'.$initProductDataRow+3)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE598');

//Valor inicio del mes
$col= 'C';
foreach ($products as $product) {
    $row = $initProductDataRow+1;
    $sheet->setCellValue(($col.$row), $product->initial_stock);
    $sheet->getStyle($col.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('93C47D');
    $col++;
    $sheet->setCellValue(($col.$row), "VENTA");
    $sheet->getStyle($col.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9EAD3');
    $sheet->mergeCells($col.$row.':'.$col.($row+3));
    $col++;
}
//Valor total ingreso
$col= 'C';
foreach ($products as $product) {
    $row = $initProductDataRow+2;
    $sheet->setCellValue(($col.$row), $product->added_month_stock);
    $sheet->getStyle($col.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF9900');
    $col++;
    $col++;
}
//Valor Existencias
$col= 'C';
foreach ($products as $product) {
    $row = $initProductDataRow+3;
    $sheet->setCellValue(($col.$row), $product->stock);
    $sheet->getStyle($col.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE598');
    $col++;
    $col++;
}
//Título ingreso
$col= 'C';
foreach ($products as $product) {
    $row = $initProductDataRow+4;
    $sheet->setCellValue(($col.$row), "INGRESOS");
    $sheet->getStyle($col.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('9CC2E5');
    $col++;
    $col++;
}

$initMonthDataRow = $initProductDataRow+5;
$actualMonthRow = $initMonthDataRow;
//LENAR DATOS DE MESES
foreach ($dateRange as $date){
    $dayNumber = $date->format('N');
    $dateFormat = $date->format('Y-m-d');

    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A'.$actualMonthRow, $date->format('d-m')." ")
    ->setCellValue('B'.$actualMonthRow, "");

    $col= 'C';
    foreach ($products as $product) {
        $firstCol = $col;
        $secondCol = $col++;
        //Obtener venta del día
        $daySalesData = OperationData::getTotalSalesByProductDates($product->id, $dateFormat,  $dateFormat);
        $totalDaySales = ($daySalesData) ? $daySalesData->total : 0;

        if ($dayNumber == 1) { //Si es lunes, reiniciar subtotal semanal
          $product->week_sales = 0;
        }
        //Agregar subtotal a objeto de productos comenzar al inicio de una semana y mostrar total al final de la semana
        $product->week_sales += $totalDaySales;
        $product->month_sales += $totalDaySales;

        //Obtener entrada de producto por día
        $totalInputsDayData = OperationData::getTotalInputsByProductDates($product->id,$dateFormat,$dateFormat);//Stock añadido en el transcurso del mes
        $totalInputsDay = ($totalInputsDayData) ? $totalInputsDayData->total : 0;

        $product->week_inputs += $totalInputsDay;
        $product->month_inputs += $totalDaySales;

        $sheet->setCellValue(($firstCol.$actualMonthRow), $totalInputsDay);
        $sheet->setCellValue(($secondCol.$actualMonthRow), $totalDaySales);

    }
    $actualMonthRow++;
    if ($dayNumber == 7 || $dateFormat == $endDate){
        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A'.$actualMonthRow, "")
        ->setCellValue('B'.$actualMonthRow, "");
        foreach ($products as $product){
            $sheet->setCellValue(($firstCol.$actualMonthRow), "");
            $sheet->setCellValue(($secondCol.$actualMonthRow), $product->week_sales);
        }
      $actualMonthRow++;
    }
    $col++;
}

$col= 'C';
foreach ($products as $product) {
    $firstCol = $col;
    $secondCol = $col++;
    $sheet->setCellValue(($firstCol.$actualMonthRow), "");
    $sheet->setCellValue(($secondCol.$actualMonthRow), $product->month_sales);
    $col++;
}

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle($startDate);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

foreach(range('A','G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

ob_end_clean();
ob_clean();
// Redirect output to a client’s web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Reporte Inventario Mensual '.date("d-m-Y").'.xls"');
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