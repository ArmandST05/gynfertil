<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/PatientData.php";

require_once '../PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$corteA=SellData::getAllCorteAll($_GET["id"]);
$ConEgre=SellData::getAllBuyDate($_GET["id"]);
$tot=0; $typeP=0; $med=0; $ef=0; $tc=0; $td=0; $ch=0;$sta=""; $totE=0; $concep="";


$section1 = $word->AddSection();
$section1->addImage(
    '../assets/gyn.PNG',
    array(
        'width' => 160,
        'height' => 75,
        'wrappingStyle' => 'behind'
    )
);


$styleTable = array('borderSize' => 2, 'borderColor' => '888888', 'cellMargin' => 40,"size"=>9);
$styleFirstRow = array('borderBottomColor' => '0000FF',"size"=>9);

$table1 = $section1->addTable("table1");
$section1->addText(("CORTE: ".$_GET["id"].""),array("size"=>10,"color"=>"000000", "bold" => true));


$section1->addText(("INGRESOS"),array("size"=>10,"color"=>"404040", "bold"  => true));;
$table2 = $section1->addTable("table3");
$table2->addRow();
$table2->addCell(3000)->addText(("MÉDICO"),array("size"=>10,"color"=>"000000", "bold"  => true));
$table2->addCell(3000)->addText(("PACIENTE"),array("size"=>10,"color"=>"000000", "bold"  => true));
$table2->addCell(1500)->addText(("CONCEPTOS"),array("size"=>10,"color"=>"000000", "bold"  => true));
$table2->addCell(1500)->addText(("F.PAGO"),array("size"=>10,"color"=>"000000", "bold"  => true));
$table2->addCell(1500)->addText(("TOTAL"),array("size"=>10,"color"=>"000000", "bold"  => true));


foreach($corteA as $cor){
  $table2->addRow();
	$tot +=$cor->total;
	$Med = SellData::getAll_docCor($cor->idMedic);

$table2->addCell()->addText($Med->name);
$table2->addCell()->addText($cor->name);

$typeC = OperationData::getAllByConcepts($cor->id);

$ConAll = OperationData::getConceptsId($cor->id);

$table2->addCell()->addText($ConAll->con);

    foreach ($typeC as $key2) {
 $P = OperationData::getnamePro($key2->product_id);

 

 $concep +=$cor->total;
    if($P->type== "MEDICAMENTO"){
    	//echo "entre";
    	$med += $P->price_out * $key2->q;
    }
    }

    $PayAll = OperationData::getAllBySellPayCon($cor->id);
    $table2->addCell()->addText($PayAll->tpay);
    

    $typeP = OperationData::getAllBySellPay($cor->id);
    foreach ($typeP as $key) {
      
       	if($key->tname=="EFECTIVO"){
              $ef += $key->cash;
          	}
          	else if($key->tname=="T. DEBITO"){
          	  $td += $key->cash;
          	}
          	else if($key->tname=="T. CREDITO"){
          	  $tc += $key->cash;
          	}
          	else if($key->tname=="CHEQUES"){
          	  $ch += $key->cash;
          	}
         }
    
$table2->addCell()->addText("$".number_format($cor->total,2,".",","));


}
$section1->addText(("TOTAL GENERAL: ".number_format($tot,2)),array("size"=>10,"color"=>"000000", "bold"  => true));


$section1->addText(("GASTOS"),array("size"=>10,"color"=>"404040", "bold"  => true));;
$table3 = $section1->addTable("table3");
$table3->addRow();
$table3->addCell(2000)->addText(("CONCEPTOS"),array("size"=>10,"color"=>"000000", "bold" => true));;
$table3->addCell(1500)->addText(("PRECIO"),array("size"=>10,"color"=>"000000", "bold" => true));;
$table3->addCell(1500)->addText(("CANTIDAD"),array("size"=>10,"color"=>"000000", "bold" => true));;
$table3->addCell(1500)->addText(("TOTAL"),array("size"=>10,"color"=>"000000", "bold" => true));;

foreach($ConEgre as $conE){
	  $totE +=$conE->price * $conE->q;
	  $pro = SellData::getProducts($conE->product_id);
$table3->addRow();
$table3->addCell()->addText($pro->name);
$table3->addCell()->addText($conE->price);
$table3->addCell()->addText($conE->q);
$table3->addCell()->addText(number_format($conE->price * $conE->q,2));

}
$section1->addText(("TOTAL: ".number_format($totE,2)),array("size"=>10,"color"=>"000000", "bold"  => true));


$section1->addText(("EFECTIVO: ".number_format($ef,2)."    "."SALIDAS: ".number_format($totE,2)."    "."ENTREGAS: ".number_format($ef-$totE,2)."    "."MEDICAMENTO: ".number_format($med,2)."    "."TARJETA: ".number_format($tc+$td,2)),array("size"=>10,"color"=>"404040", "bold"  => true));;


$word->addTableStyle('table1', $styleTable,$styleFirstRow);
$word->addTableStyle('table3', $styleTable,$styleFirstRow);
$word->addTableStyle('table2', $styleTable,$styleFirstRow);
$word->addTableStyle('table4', $styleTable);



/// datos bancarios

$filename = "CORTE ".$_GET["id"].".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>