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

    $andrologySign = "";
    $pathSign = $_SERVER["DOCUMENT_ROOT"] . "/assets/andrology-sign.png";
    //Validar que se ha subido un logo de la clínica para mostrar
    if (file_exists($pathSign)) {
        $typeSign = pathinfo($pathSign, PATHINFO_EXTENSION);
        $dataSign = file_get_contents($pathSign);
        $andrologySign = 'data:image/' . $typeSign . ';base64,' . base64_encode($dataSign);
    }

    $andrologyProcedure = AndrologyProcedureData::getPatientProcedureById($_GET["id"]);
    $andrologyProcedureId = $_GET["id"];
    $procedureDetails = AndrologyProcedureData::getDetailsByProcedure($andrologyProcedure->andrology_procedure_id, $andrologyProcedureId);
    $medicProcedure = $andrologyProcedure->getMedic();
    
    $patient = $andrologyProcedure->getPatient();
    $patientOfficialData = $patient->getPatientOfficialData(); //Cargar dato oficial del paciente (rfc,curp,pasaporte).

    $patientBirthday = ($patient->birthday_format != "00/00/0000") ? $patient->birthday_format : "No especificada";
    //Si el paciente tiene una pareja registrada como paciente, obtener los datos de ahí, si no, los datos capturados.
    if ($patient->relative_id) {
        $relative = PatientData::getById($patient->relative_id);
        $relativeName = $relative->name;
        $relativeBirthday = ($relative->birthday_format != "00/00/0000") ? $relative->birthday_format : "No especificada";
        $relativeAge = $relative->getAge();
        $relativeOfficialData = $relative->getPatientOfficialData(); //Cargar dato oficial de pareja del paciente (rfc,curp,pasaporte).
    } else {
        $relativeName = $patient->relative_name;
        $relativeBirthday = ($patient->relative_birthday_format != "00/00/0000") ? $patient->relative_birthday_format : "No especificada";
        $relativeAge = $patient->getRelativeAge();
        $relativeOfficialData = $patient->getRelativeOfficialData(); //Cargar dato oficial de pareja del paciente (rfc,curp,pasaporte).
    }

    //Imágenes de objetivos
    $lens40xImage = "";
    $lens40xData = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 3);
    $lens40xPath = $lens40xData->path;
    /*if (file_exists($lens40xPath)) {
        $typeLens40x = pathinfo($lens40xPath, PATHINFO_EXTENSION);
        $dataLens40x = file_get_contents($lens40xPath);
        $lens40xImage = 'data:image/' . $typeLens40x . ';base64,' . base64_encode($dataLens40x);
    }*/

    $lens100xImage = "";
    $lens100xData = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 4);
    $lens100xPath = $lens100xData->path;
    /*if (file_exists($lens100xPath)) {
        $typeLens100x = pathinfo($lens100xPath, PATHINFO_EXTENSION);
        $dataLens100x = file_get_contents($lens100xPath);
        $lens100xImage = 'data:image/' . $typeLens100x . ';base64,' . base64_encode($dataLens100x);
    }*/

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
    .footer {
        position:absolute;
        bottom:0;
        width:90%;
        height:60px;   /* Height of the footer */
        font-style:normal;
        font-weight:normal;
        font-size:9pt;
        font-family: "Helvetica";
        text-align: center;
    }
    .title-document{
        font-size:13pt;
    }
    .title{
        border-bottom: 2px solid rgb(13,173,224);
        text-align: left;
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
    .underlined-title{
        border-bottom: 2px solid rgb(13,173,224);  
    }  
    .content-title{
        color: rgb(13,173,224);
        text-align:right;
    }
    .patient-subtitle{
        text-align:right;
    }
    .bold{
        font-weight: bold;
    }    
    .center{
        text-align: center;
    }
    .right{
        text-align: right;
    }
    .left{
        text-align: left;
    }
    .unit{
        text-align: left;
        border-bottom:.1px solid gray;
    }
    </style>
    
    <table width="100%">
        <tr>
            <th class="title-document" align="center">ESPERMATOBIOSCOPÍA DIRECTA</th>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td rowspan="3"><img width="150px" height="90px" src="' . $logo . '" /></td>
            <td width="60px">Código:</td>
            <td class="patient-data">' . $andrologyProcedure->procedure_code .$typeLens100x. '</td>
        </tr>
        <tr>
        <td>Fecha:</td>
        <td class="patient-data">' . $andrologyProcedure->date_format . '</td>
    </tr>
    <tr>
        <td>DR (A):</td>
        <td class="patient-data">' . $andrologyProcedure->medic_name . '</td>
    </tr>
    </table>
    
    <table width="100%">
        <tr>
            <td class="patient-subtitle">Paciente:</td>
            <td class="patient-data" width="200px">' . $patient->name . '</td>
            <td class="patient-subtitle">Fecha nacimiento:</td>
            <td class="patient-data">' . $patientBirthday . '</td>
            <td class="patient-subtitle">Edad:</td>
            <td class="patient-data">' . $patient->getAge() . '</td>
        </tr>
        <tr>
            <td class="patient-subtitle">Pareja:</td>
            <td class="patient-data" width="200px">' . $relativeName . '</td>
            <td class="patient-subtitle">Fecha nacimiento:</td>
            <td class="patient-data">' . $relativeBirthday . '</td>
            <td class="patient-subtitle">Edad:</td>
            <td class="patient-data">' . $relativeAge . '</td>
        </tr>
    </table>

    <br>
    <table width="100%">
        <tr>
            <td  class="patient-subtitle">Días de abstinencia:</td>
            <td class="patient-data" width="130px">' . $procedureDetails['183'] . '</td>
            <td  class="patient-subtitle" width="100px">Recolección:</td>
            <td class="patient-data">' . $procedureDetails['184'] . '</td>
            <td class="patient-subtitle" width="100px">Procesamiento:</td>
            <td class="patient-data">' . $procedureDetails['185'] . '</td>
            <td  class="patient-subtitle" width="100px">Congelación:</td>
            <td class="patient-data">' . $procedureDetails['186'] . '</td>
        </tr>
    </table>
<br>
    <table width="100%">
    <tr>
        <th class="title" colspan="4">ANÁLISIS MACROSCÓPICO</th>
    </tr>
        <tr>
            <td width="200px"></td>
            <td class="bold" width="100px">RESULTADO</td>
            <td class="bold">VALORES DE REFERENCIA OMS 2021</td>
        </tr> 
        <tr>
            <td class="content-title">Volumen (ml):</td>
            <td class="underlined center">' . $procedureDetails['187'] . '</td>
            <td class="bold">≥ 1.4 mL</td>
        </tr>
        <tr>
            <td class="content-title">Licuefacción:</td>
            <td class="underlined center">' . $procedureDetails['188'] . '</td>
            <td class="bold">Completa</td>
        </tr>
        <tr>
            <td class="content-title">Viscosidad:</td>
            <td class="underlined center">' . $procedureDetails['189'] . '</td>
            <td class="bold">Normal</td>
        </tr>
        <tr>
            <td class="content-title">Aspecto:</td>
            <td class="underlined center">' . $procedureDetails['190'] . '</td>
            <td class="bold">Perla/ gris opalescente</td>
        </tr>
        <tr>
            <td class="content-title">pH:</td>
            <td class="underlined center">' . $procedureDetails['191'] . '</td>
            <td class="bold">≥ 7.2</td>
        </tr>
        <tr>
            <td class="content-title">Cuerpos gelatinosos:</td>
            <td class="underlined center">' . $procedureDetails['192'] . '</td>
            <td class="bold"></td>
        </tr>
</table>


<table width="100%">
<tr>
    <th class="title" colspan="5">ANÁLISIS MICROSCÓPICO</th>
</tr>
    <tr>
        <td width="200px"></td>
        <td class="bold" width="100px">RESULTADO</td>
        <td class="bold" width="90px">UNIDAD</td>
        <td class="bold" colspan="2">VALORES DE REFERENCIA OMS 2021</td>
        <td></td>
    </tr> 
    <tr>
        <td class="content-title">Concentración (mill/mL):</td>
        <td class="underlined right">' . $procedureDetails['193'] . '</td>
        <td class="unit"> x 10⁶ /mL</td>
        <td class="bold">≥ 16 x 10⁶/ mL</td>
        <td rowspan="9"><img width="150px" height="150px" src="' . $lens40xPath . '" /></td>
    </tr>
    <tr>
        <td class="content-title">Espermatozoides totales (mill):</td>
        <td class="underlined right">' . $procedureDetails['194'] . '</td>
        <td class="unit"> x 10⁶ </td>
        <td class="bold">≥ 39 x 10⁶</td>
    </tr>
    <tr>
        <td class="content-title">Células redondas (mill/mL):</td>
        <td class="underlined right">' . $procedureDetails['195'] . '</td>
        <td class="unit">x 10⁶ /mL</td>
        <td class="bold">≤ 1 x 10⁶/ mL</td>
    </tr>
    <tr>
        <td class="content-title">Leucocitos:</td>
        <td class="underlined right">' . $procedureDetails['196'] . '</td>
        <td class="unit"> x 10⁶ /mL</td>
        <td class="bold">≤ 1 x 10⁶/ mL</td>
    </tr>
    <tr>
        <td class="content-title">Aglutinación:</td>
        <td class="underlined right">' . $procedureDetails['197'] . '</td>
        <td></td>
        <td class="bold">Negativa</td>
    </tr>
    <tr>
        <td class="content-title">Otros:</td>
        <td class="underlined right">' . $procedureDetails['198'] . '</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td class="content-title">Movilidad Progresiva (PR):</td>
        <td class="underlined right">' . $procedureDetails['199'] . '</td>
        <td class="unit">%</td>
        <td class="bold">≥ 30 % </td>
    </tr>
    <tr>
        <td class="content-title">Movilidad No Progresiva (NP):</td>
        <td class="underlined right">' . $procedureDetails['200'] . '</td>
        <td class="unit">%</td>
        <td class="bold">(NP) ≥ 1</td>
    </tr>
    <tr>
        <td class="content-title">Inmóviles (IM):</td>
        <td class="underlined right">' . $procedureDetails['201'] . '</td>
        <td class="unit">%</td>
        <td class="bold">(IM) ≤ 20% </td>
    </tr>
    <tr>
        <td class="content-title">Espermatozoides PR Totales (mill):</td>
        <td class="underlined right">' . $procedureDetails['202'] . '</td>
        <td class="unit"> x 10⁶ </td>
        <td class="bold">(PR+NP) ≥ 42%</td>
    </tr>
    <tr>
        <td class="content-title">Vitalidad:</td>
        <td class="underlined right">' . $procedureDetails['203'] . '</td>
        <td class="unit">%</td>
        <td class="bold">≥ 54 % </td>
        <td rowspan="9"><img width="150px" height="150px" src="' . $lens100xPath . '" /></td>
    </tr>
    <tr>
        <td class="content-title">Espermatozoides No Vitales:</td>
        <td class="underlined right">' . $procedureDetails['204'] . '</td>
        <td class="unit">%</td>
        <td></td>
    </tr>
    <tr class="content-title">
        <td class="content-title">Total espermatozoides vitales (mill):</td>
        <td class="underlined right">' . $procedureDetails['205'] . '</td>
        <td class="unit"> x 10⁶ </td>
        <td></td>
    </tr>
    <tr>
        <td class="content-title">REM*:</td>
        <td class="underlined right">' . $procedureDetails['206'] . '</td>
        <td class="unit"> x 10⁶ /mL</td>
        <td></td>
    </tr>
    <tr>
        <td class="content-title">Espermatozoides Normales (%):</td>
        <td class="underlined right">' . $procedureDetails['207'] . '</td>
        <td class="unit">%</td>
        <td class="bold">≥ 4% </td>
    </tr>
    <tr>
        <td class="content-title">Espermatozoides Anormales (%):</td>
        <td class="underlined right">' . $procedureDetails['208'] . '</td>
        <td class="unit">%</td>
        <td></td>
    </tr>
    <tr>
        <td class="content-title">Defectos de Cabeza (%):</td>
        <td class="underlined right">' . $procedureDetails['209'] . '</td>
        <td class="unit">%</td>
        <td></td>
    </tr>
    <tr>
        <td class="content-title">Defectos de Pieza Media (%):</td>
        <td class="underlined right">' . $procedureDetails['210'] . '</td>
        <td class="unit">%</td>
        <td></td>
    </tr>
    <tr>
        <td class="content-title">Defectos de Cola (%):</td>
        <td class="underlined right">' . $procedureDetails['211'] . '</td>
        <td class="unit">%</td>
        <td></td>
    </tr>
    <tr>
        <td class="content-title">Residuo Citoplasmático (%):</td>
        <td class="underlined right">' . $procedureDetails['212'] . '</td>
        <td class="unit">%</td>
        <td></td>
    </tr>
</table>
<br>
<table width="100%">
<tr>
    <th class="title">OBSERVACIONES:</th>
    <td width="600px" class="underlined-title">' . $andrologyProcedure->observations . '</td>
</tr>
<tr>
    <th class="title">DIAGNÓSTICO:</th>
    <td width="600px" class="underlined-title">' . $andrologyProcedure->diagnostic . '</td>
</tr>
</table>
<table>
<tr>
    <td>*R.E.M=Recuento de Espermatozoides Móviles</td>
</tr>
</table>
<table align="center" style="text-align:center;">
    <tr>
        <td><img width="150px" height="40px" src="storage_data/medics/' . $medicProcedure->id . '/' . $medicProcedure->digital_signature_path . '" /></td>
    </tr>
    <tr>
    <td>' . $medicProcedure->name . '</td>
    </tr>
</table>
<div class="footer">BLV. LUIS DONALDO COLOSIO MURRIETA 106, LOMAS DEL CAMPESTRE FRACC. 2ª SECC. C.P.  20119, AGUASCALIENTES, AGS. WHATSAPP (449) 3972633,  (449) 9968320, (449) 7101555.</div>
';
    $mpdf->WriteHTML($html);
    // Other code
    ob_get_clean();
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
    // Process the exception, log, print etc.
    echo $e->getMessage();
}
//$mpdf->Output("../../view/HistorialReportes/".$filename,"F");
