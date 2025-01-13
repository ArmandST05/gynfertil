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

    $patient = $embryologyProcedure->getPatient();
    $patientOfficialData = $patient->getPatientOfficialData(); //Cargar dato oficial del paciente (rfc,curp,pasaporte).

    $patientBirthday = ($patient->birthday_format != "00/00/0000") ? $patient->birthday_format : "No especificada";
    //Obtener los datos de la pareja asignada en ese procedimiento
    $partner = $embryologyProcedure->getPartnerData();

    //Imágenes para el paciente
    $selectedImagesPath = [];
    /*$selectedOvulesImages = explode(",", $procedureDetails['522']);
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

    $GLOBALS['procedureDetails'] = $procedureDetails;
    function calculateSectionDate($dayNumber, $index)
    {
        $procedureDetails = $GLOBALS['procedureDetails'];
        global $procedureDetails;
        if (isset($procedureDetails[$index]) && $procedureDetails[$index] != "0000-00-00" && $dayNumber != "" &&  $dayNumber >= 0) {
            return (date("d/m/Y", strtotime($procedureDetails[$index] . " +" . $dayNumber . " days")));
        } else "";
    }

    $html = '
    <style>
    table,tr,td {
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
    .subtitle{
        color:#F256CA;
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
            <th class="title-document" align="center">CICLO MIXTO</th>
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
        <th class="title" colspan="4">ASPIRACIÓN FOLICULAR <span class="date-title">(' . ConfigurationData::getDateFormat($procedureDetails['148']) . ' - ' . ConfigurationData::getDateFormat($procedureDetails['166']) . ')</span></th>
        <td rowspan="5">';
    if (isset($selectedImagesPath[0])) {
        $html .= '<img width="150px" height="150px" src="' . $selectedImagesPath[0] . '" />';
    }
    $html .= '</td></tr> 
        <tr>
            <td colspan="2" class="subtitle">RESULTADO PACIENTE</td>
            <td colspan="2" class="subtitle">RESULTADO DONANTE</td>
        </tr>
        <tr>
            <td width="150px" class="content-title">Óvulos recuperados:</td>
            <td width="100px" class="underlined center">' . $procedureDetails['155'] . '</td>
            <td width="150px" class="content-title">Óvulos recuperados:</td>
            <td width="100px" class="underlined center">' . $procedureDetails['172'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Óvulos en MI:</td>
            <td class="underlined center">' . $procedureDetails['156'] . '</td>
            <td class="content-title">Óvulos en MI:</td>
            <td class="underlined center">' . $procedureDetails['173'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Óvulos en MII:</td>
            <td class="underlined center">' . $procedureDetails['157'] . '</td>
            <td class="content-title">Óvulos en MII:</td>
            <td class="underlined center">' . $procedureDetails['174'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Óvulos en VG:</td>
            <td class="underlined center">' . $procedureDetails['158'] . '</td>
            <td class="content-title">Óvulos en VG:</td>
            <td class="underlined center">' . $procedureDetails['175'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Degenerados:</td>
            <td class="underlined center">' . $procedureDetails['159'] . '</td>
            <td class="content-title">Degenerados:</td>
            <td class="underlined center">' . $procedureDetails['176'] . '</td>
        </tr>
    <tr>
    <th class="title" colspan="4">FERTILIZACIÓN <span class="date-title">(' . calculateSectionDate(1, 148) . ' - ' . calculateSectionDate(1, 166) . ')</span></th>
    <td rowspan="7">';
    if (isset($selectedImagesPath[1])) {
        $html .= '<img width="150px" height="150px" src="' . $selectedImagesPath[1] . '" />';
    }
    $html .= '</td></tr> 
    <tr>
    <td colspan="2" class="subtitle">RESULTADO PACIENTE</td>
    <td colspan="2" class="subtitle">RESULTADO DONANTE</td>
    </tr>
    <tr>
        <td width="150px" class="content-title">Óvulos inseminados:</td>
        <td  width="100px" class="underlined center">' . $procedureDetails['160'] . '</td>
        <td width="150px" class="content-title">Óvulos inseminados:</td>
        <td  width="100px" class="underlined center">' . $procedureDetails['177'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Óvulos fertilizados:</td>
        <td class="underlined center">' . $procedureDetails['161'] . '</td>
        <td class="content-title">Óvulos fertilizados:</td>
        <td class="underlined center">' . $procedureDetails['178'] . '</td>
    </tr>
<tr>
    <th class="title" colspan="3">EVALUACIÓN DÍA 3</th>
</tr>
    <tr>
    <td colspan="2" class="subtitle">RESULTADO PACIENTE</td>
    <td colspan="2" class="subtitle">RESULTADO DONANTE</td>
    </tr>
    <tr>
        <td width="150px" class="content-title">Embriones calidad A:</td>
        <td  width="100px" class="underlined center">' . $procedureDetails['498'] . '</td>
        <td width="150px" class="content-title">Embriones calidad A:</td>
        <td  width="100px" class="underlined center">' . $procedureDetails['510'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Embriones calidad B:</td>
        <td class="underlined center">' . $procedureDetails['499'] . '</td>
        <td class="content-title">Embriones calidad B:</td>
        <td class="underlined center">' . $procedureDetails['511'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Embriones calidad C:</td>
        <td class="underlined center">' . $procedureDetails['500'] . '</td>
        <td class="content-title">Embriones calidad C:</td>
        <td class="underlined center">' . $procedureDetails['512'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Embriones calidad D:</td>
        <td class="underlined center">' . $procedureDetails['501'] . '</td>
        <td class="content-title">Embriones calidad D:</td>
        <td class="underlined center">' . $procedureDetails['513'] . '</td>
    </tr>
</table>
<table width="100%">
<tr>
    <th class="title" colspan="4">EVALUACIÓN DÍA 5 <span class="date-title">(' . calculateSectionDate(5, 148) . ' - ' . calculateSectionDate(5, 166) . ')</span></th>
    <td rowspan="9">';
    if (isset($selectedImagesPath[2])) {
        $html .= '<img width="150px" height="150px" src="' . $selectedImagesPath[2] . '" />';
    }
    $html .= '</td></tr> 
    <tr>
    <td colspan="2" class="subtitle">RESULTADO PACIENTE</td>
    <td colspan="2" class="subtitle">RESULTADO DONANTE</td>
    </tr>
    <tr>
        <td width="150px" class="content-title">Blastocistos:</td>
        <td width="100px" class="underlined center">' . $procedureDetails['502'] . '</td>
        <td width="150px" class="content-title">Blastocistos:</td>
        <td width="100px" class="underlined center">' . $procedureDetails['514'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Calidad embrionaria:</td>
        <td class="underlined center">' . $procedureDetails['503'] . '</td>
        <td class="content-title">Calidad embrionaria:</td>
        <td class="underlined center">' . $procedureDetails['515'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Mórulas:</td>
        <td class="underlined center">' . $procedureDetails['504'] . '</td>
        <td class="content-title">Mórulas:</td>
        <td class="underlined center">' . $procedureDetails['516'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Detenidos:</td>
        <td class="underlined center">' . $procedureDetails['505'] . '</td>
        <td class="content-title">Detenidos:</td>
        <td class="underlined center">' . $procedureDetails['517'] . '</td>
    </tr>
    <tr>
        <th class="title" colspan="4">EVALUACIÓN DÍA 6 Y 7 <span class="date-title">(' . calculateSectionDate(6, 148) . ' - ' . calculateSectionDate(7, 148) . ')(' . calculateSectionDate(6, 166) . ' - ' . calculateSectionDate(7, 166) . ')</span></th>
    </tr>
    <tr>
    <td colspan="2" class="subtitle">RESULTADO PACIENTE</td>
    <td colspan="2" class="subtitle">RESULTADO DONANTE</td>
    </tr>
    <tr>
        <td width="150px" class="content-title">Blastocistos:</td>
        <td width="100px" class="underlined center">' . $procedureDetails['506'] . '</td>
        <td width="150px" class="content-title">Blastocistos:</td>
        <td width="100px" class="underlined center">' . $procedureDetails['518'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Calidad embrionaria:</td>
        <td class="underlined center">' . $procedureDetails['507'] . '</td>
        <td class="content-title">Calidad embrionaria:</td>
        <td class="underlined center">' . $procedureDetails['519'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Mórulas:</td>
        <td class="underlined center">' . $procedureDetails['508'] . '</td>
        <td class="content-title">Mórulas:</td>
        <td class="underlined center">' . $procedureDetails['520'] . '</td>
    </tr>
    <tr>
        <td class="content-title">Detenidos:</td>
        <td class="underlined center">' . $procedureDetails['509'] . '</td>
        <td class="content-title">Detenidos:</td>
        <td class="underlined center">' . $procedureDetails['521'] . '</td>
    </tr>
</table>
<br>
<table width="100%">
<tr>
    <th class="title">OBSERVACIONES:</th>
    <td width="600px" class="underlined">' . $procedureDetails['523'] . '</td>
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

            $html .= '<th width="250px" class="title-image">' . (($ovuleImage->procedure_section_id == 1) ? "PACIENTE #":"DONANTE #").$ovuleImage->procedure_code . '</th>';
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
