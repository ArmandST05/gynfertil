<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
try {
    $mpdf = new \Mpdf\Mpdf(['format' => 'Letter']);
    //Logo de clínica
    $logo = "";
    $path = $_SERVER["DOCUMENT_ROOT"] . "/assets/clinic-logo.png";
    //Validar que se ha subido un logo de la clínica para mostrar
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    //Datos del embriólogo
    $configurations = ConfigurationData::getAll();
    $embryologyMedic = MedicData::getById($configurations["chief_embryologist_id"]->value);

    $embryologySign = "";
    $pathSign = $_SERVER["DOCUMENT_ROOT"] . "/storage_data/medics/".$embryologyMedic->id."/".$embryologyMedic->digital_signature_path;

    //Validar que se ha subido un logo de la clínica para mostrar
    if (file_exists($pathSign)) {
        $typeSign = pathinfo($pathSign, PATHINFO_EXTENSION);
        $dataSign = file_get_contents($pathSign);
        $embryologySign = 'data:image/' . $typeSign . ';base64,' . base64_encode($dataSign);
    }

    $embryologyProcedure = PatientCategoryData::getById($_GET["id"]);
    $embryologyProcedureId = $_GET["id"];
    $patient = $embryologyProcedure->getPatient();
    $procedureDetails = EmbryologyProcedureData::getDetailsByProcedure($embryologyProcedure->patient_treatment_id, $embryologyProcedureId);
 
    //Obtener el código de vitrificación si se realizó ese subtratamiento de fertilidad
    $subEmbryologyProcedure = PatientCategoryData::getSubTreatmentById($embryologyProcedureId);
    $subprocedureDetails = EmbryologyProcedureData::getDetailsByProcedure($subEmbryologyProcedure->patient_treatment_id, $subEmbryologyProcedure->id);

    $ovuleReceivers = [];
    $procedureOvules = PatientOvuleData::getOvulesByProcedureSectionId($embryologyProcedureId, 1);
    foreach($procedureOvules as $ovule){
        $ovuleSectionDetailValue = PatientOvuleData::getSectionDetailValueByOvuleId($embryologyProcedure->patient_treatment_id, 1, $ovule->id,152);
        if($ovuleSectionDetailValue && $ovuleSectionDetailValue->value != ""){
          $receiverData = explode(" - ", $ovuleSectionDetailValue->value);
          $receiverCode = $receiverData[0];
          
          if(isset($ovuleReceivers[$receiverCode])){
              $ovuleReceivers[$receiverCode] = floatval($ovuleReceivers[$receiverCode])+1;
          }else{ 
            $ovuleReceivers[$receiverCode] = 1;
          }
        }
    }

    $patient = $embryologyProcedure->getPatient();
    $patientOfficialData = $patient->getPatientOfficialData(); //Cargar dato oficial del paciente (rfc,curp,pasaporte).

    $patientBirthday = ($patient->birthday_format != "00/00/0000") ? $patient->birthday_format : "No especificada";
    //Obtener los datos de la pareja asignada en ese procedimiento
    $partner = $embryologyProcedure->getPartnerData();

    //Imágenes para el paciente
    $selectedImagesPath = [];
    /*
    $selectedOvulesImages = explode(",", $procedureDetails['473']);
    foreach ($selectedOvulesImages as $imageOvuleId) {
        $ovuleImage = PatientOvuleData::getProcedureOvuleById($imageOvuleId);
        if ($ovuleImage && $ovuleImage->getImage()) {
            $imagePath = $ovuleImage->getImage()->path;
            if (file_exists($imagePath)) {
                array_push($selectedImagesPath, $imagePath);
            }
        }
    }*/

    //Imágenes de los óvulos (todas)
    $ovuleImagesData = EmbryologyProcedureData::getFilesByTreatmentSectionId($embryologyProcedureId, 1);
    $arrayOvuleImages = array_chunk($ovuleImagesData, 4);
    
    $html = '
    <style>
    table,tr,td {
       /* border-collapse: collapse;*/
        /*border:.3px solid black;*/
        border: none;
    }
    td {
        padding: 0px;
        font-style:normal;
        font-weight:normal;
        font-size:9pt;
        font-family: "Helvetica";
    }
    th{
        font-style:normal;
        font-weight:bold;
        font-size:10pt;
        font-family: "Helvetica";
    }
    .title-document{
        font-size:13pt;
    }
    .title{
        border-bottom: 2px solid rgb(13,173,224);
        text-align: left;
    }
    .title-image{
        background-color:#F7CFDF;
        font-style:normal;
        font-weight:bold;
        font-size:10pt;
    }   
    .group-title{
        font-weight:bold !important;
    }
    .center-content{
        text-align: center;
    }
    .patient-data{
        background-color:#F7CFDF;
    }
    .underlined{
        border-bottom:.1px solid gray;
    }
    .content-title{
        color: rgb(13,173,224);
        text-align:right;
    }
    .date-title{
        font-style:normal;
        font-weight:bold;
        font-size:9pt;
        font-family: "Helvetica";
    }
    .bold{
        font-weight: bold;
    }
    .center{
        text-align: center;
    }
    </style>
    
    <table width="100%">
        <tr>
            <th class="title-document" align="center">DONACIÓN DE ÓVULOS</th>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td rowspan="3"><img width="150px" height="90px" src="' . $logo . '" /></td>
            <td width="60px">Código:</td>
            <td class="patient-data">' . $embryologyProcedure->treatment_code . '</td>
        </tr>
        <tr>
        <td>Fecha:</td>
        <td class="patient-data">' . $embryologyProcedure->start_date_format . '</td>
    </tr>
    <tr>
        <td>DR (A):</td>
        <td class="patient-data">Heidi Trejo Castañeda</td>
    </tr>
    </table>
    <table width="100%">
        <tr>
            <td>Paciente:</td>
            <td class="patient-data" width="200px">' . $patient->name . '</td>
            <td>Fecha nacimiento:</td>
            <td class="patient-data">' . $patientBirthday . '</td>
            <td>Edad:</td>
            <td class="patient-data">' . $patient->getAge() . '</td>
        </tr>
        <tr>
            <td>Pareja:</td>
            <td class="patient-data" width="200px">' . $partner->name . '</td>
            <td>Fecha nacimiento:</td>
            <td class="patient-data">' . $partner->birthdayFormat . '</td>
            <td>Edad:</td>
            <td class="patient-data">' . $partner->age . '</td>
        </tr>
    </table>
    <br>
    <table width="100%">
    <tr>
        <th class="title" colspan="3">ASPIRACIÓN FOLICULAR <span class="date-title">(' . ConfigurationData::getDateFormat($procedureDetails['107']) . ')</span></th>
        <td rowspan="5">';
    if (isset($selectedImagesPath[0])) {
        $html .= '<img width="150px" height="150px" src="' . $selectedImagesPath[0] . '" />';
    }
    $html .= '</td></tr> 
        <tr>
            <td width="200px" class="content-title">Óvulos recuperados:</td>
            <td width="150px" class="underlined center">' . $procedureDetails['110'] . '</td>
            <td width="150px"></td>
        </tr>
        <tr>
            <td class="content-title">Óvulos en MI:</td>
            <td class="underlined center">' . $procedureDetails['111'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Óvulos en MII:</td>
            <td class="underlined center">' . $procedureDetails['112'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Óvulos en VG:</td>
            <td class="underlined center">' . $procedureDetails['113'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Degenerados:</td>
            <td class="underlined center">' . $procedureDetails['114'] . '</td>
        </tr>
    <tr>
    <th class="title" colspan="3">VITRIFICACIÓN DE ÓVULOS <span class="date-title">(' . ConfigurationData::getDateFormat($subprocedureDetails['40']) . ')</span></th>
    <td rowspan="5">';
    if (isset($selectedImagesPath[1])) {
        $html .= '<img width="150px" height="150px" src="' . $selectedImagesPath[1] . '" />';
    }
    $html .= '</td></tr> 
    <tr>
        <td width="200px" class="content-title">Óvulos vitrificados:</td>
        <td  width="150px" class="underlined center">' . $subprocedureDetails['48'] . '</td>
        <td width="150px"></td>
    </tr>
    <tr>
        <th class="title" colspan="3">UBICACIÓN</th>
    </tr>
    <tr>
        <td width="200px" class="content-title">Tanque:</td>
        <td class="underlined center">' . $subprocedureDetails['45'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Cesta:</td>
        <td class="underlined center">' . $subprocedureDetails['44'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Varilla:</td>
        <td class="underlined center"></td>
    </tr>
</table>
<table width="100%">
<tr>
    <th class="title" colspan="3">CÓDIGO DE VITRIFICACIÓN</th>
    <td rowspan="2">';
    if (isset($selectedImagesPath[2])) {
        $html .= '<img width="150px" height="150px" src="' . $selectedImagesPath[2] . '" />';
    }
    $html .= '</td></tr> 
    <tr>
        <td width="200px" class="content-title">Código:</td>
        <td width="150px" class="underlined center">' . $subEmbryologyProcedure->treatment_code . '</td>
        <td width="150px"></td>
    </tr>
    <tr>
        <td class="content-title">Número de dispositivos:</td>
        <td class="underlined center">' . $subprocedureDetails['43'] . '</td>
    </tr>
</table>
<table width="100%">
<tr>
    <th class="title" colspan="4">RECEPTORAS</th>
</tr>';
foreach($ovuleReceivers as $index=> $receiver){
    $html.= '<tr>
        <td width="100px" class="content-title">Código:</td>
        <td width="150px" class="underlined center">' . $index . '</td>
        <td width="200px" class="content-title">Número de óvulos donados:</td>
        <td width="150px" class="underlined center">' . $receiver . '</td>
    </tr>';
}
$html.= '
</table>
<br>
<table width="100%">
<tr>
    <th class="title">OBSERVACIONES:</th>
    <td width="600px" class="underlined">'  . $procedureDetails['474'] . '</td>
</tr>
</table>
<table width="100%" align="center">
<tr>
    <th><img width="150px" height="50px" src="' . $embryologySign . '" /></th>
</tr>
<tr>
<td class="center">' . $embryologyMedic->name . '</td>
</tr>
<tr>
<td class="center">' . $embryologyMedic->specialty_title . '</td>
</tr>
</table>
<br>
<table width="100%">
<tr>
<td class="center">BLV. LUIS DONALDO COLOSIO MURRIETA 106, LOMAS DEL CAMPESTRE FRACC. 2ª SECC. C.P.  20119, AGUASCALIENTES, AGS. WHATSAPP (449) 3972633,  (449) 9968320, (449) 7101555.</td>
</tr>
</table>
<div style="page-break-after: always"></div>
<table width="100%">
<tr>
    <td class="title bold">IMÁGENES</td>
</tr>
</table>
<table class="patient-details">';
    foreach ($arrayOvuleImages as $ovuleImages) {
        $html .= '<tr>';
        foreach ($ovuleImages as $ovuleImage) {
            $html .= '<td width="250px"><img width="230px" height="230px" src="' . $ovuleImage->path . '" /></td>';
        }
        $html .= '</tr>
        <tr>';
        foreach ($ovuleImages as $ovuleImage) {
            $html .= '<th width="250px" class="title-image">#' . $ovuleImage->getProcedureOvule()->procedure_code . '</th>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    $mpdf->WriteHTML($html);
    // Other code
    ob_get_clean();
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
    // Process the exception, log, print etc.
    echo $e->getMessage();
}
//$mpdf->Output("../../view/HistorialReportes/".$filename,"F");
