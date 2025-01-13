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
    if (file_exists($lens40xPath)) {
        $typeLens40x = pathinfo($lens40xPath, PATHINFO_EXTENSION);
        $dataLens40x = file_get_contents($lens40xPath);
        $lens40xImage = 'data:image/' . $typeLens40x . ';base64,' . base64_encode($dataLens40x);
    }

    $lens100xImage = "";
    $lens100xData = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 4);
    $lens100xPath = $lens100xData->path;
    if (file_exists($lens100xPath)) {
        $typeLens100x = pathinfo($lens100xPath, PATHINFO_EXTENSION);
        $dataLens100x = file_get_contents($lens100xPath);
        $lens100xImage = 'data:image/' . $typeLens100x . ';base64,' . base64_encode($dataLens100x);
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
    .footer {
        position:absolute;
        bottom:0;
        width:90%;
        height:100px;   /* Height of the footer */
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
    .bold{
        font-weight: bold;
    }    
    .center{
        text-align: center;
    }
    </style>
    
    <table width="100%">
        <tr>
            <th class="title-document" align="center">FRAGMENTACIÓN DEL DNA</th>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td rowspan="3"><img width="150px" height="90px" src="' . $logo . '" /></td>
            <td width="60px">Código:</td>
            <td class="patient-data">' . $andrologyProcedure->procedure_code . '</td>
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
            <td>Paciente:</td>
            <td class="patient-data" width="200px">' . $patient->name . '</td>
            <td>Fecha nacimiento:</td>
            <td class="patient-data">' . $patientBirthday . '</td>
            <td>Edad:</td>
            <td class="patient-data">' . $patient->getAge() . '</td>
        </tr>
        <tr>
            <td>Pareja:</td>
            <td class="patient-data" width="200px">' . $relativeName . '</td>
            <td>Fecha nacimiento:</td>
            <td class="patient-data">' . $relativeBirthday . '</td>
            <td>Edad:</td>
            <td class="patient-data">' . $relativeAge . '</td>
        </tr>
    </table>

    <br>
    <table width="40%">
        <tr>
            <td width="150px">Días de abstinencia:</td>
            <td class="patient-data" width="100px">' . $procedureDetails['316'] . '</td>
        </tr>
    </table>
<br>
    <table width="100%">
    <tr>
        <th class="title" colspan="3">VALORES DE REFERENCIA OMS 2021</th>
    </tr>
        <tr>
            <td width="200px"></td>
            <td class="bold" width="100px">RESULTADO</td>
            <td></td>
        </tr> 
        <tr>
            <td>DNA espermático de buena calidad:</td>
            <td class="underlined" width="100px"><15%</td>
            <td></td>
        </tr>
        <tr>
            <td>DNA espermático heterogéneo:</td>
            <td class="underlined" width="100px">16 - 30 %</td>
            <td></td>
        </tr>
        <tr>
            <td>DNA espermático dañado:</td>
            <td class="underlined" width="100px"> >31%</td>
            <td></td>
        </tr>
</table>


<table width="100%">
<tr>
    <th class="title" colspan="5">ANÁLISIS MICROSCÓPICO</th>
</tr>
    <tr>
        <td class="center bold" width="400px">RESULTADO</td>
        <td></td>
    </tr> 
    <tr>
        <td class="center" rowspan="1"><br>El análisis de  Fragmentación  de ADN en células espermáticas con muestra en fresco, presenta un porcentaje del<br><br>
            <div style="text-decoration:underline;" class="bold">' . $procedureDetails['317'] . ' %</div><br><br>
            El análisis de Fragmentación del DNA indirecta, se basa en la prueba de dispersión  de cromatina  espermática (SCD) (Fernández  et  al., J. Androl 24: 59-66, 2003;  Fertil Steril  84: 833-842, 2005).<br> 
            en las células espermáticas.
        </td>
        <td><img width="260px" height="150px" src="' . $lens40xPath . '" /></td>
    </tr>
    <tr>
        <td></td>
        <td><img width="260px" height="150px" src="' . $lens100xPath . '" /></td>
    </tr>
</table>
<br>
<table width="100%">
<tr>
    <th class="title">OBSERVACIONES:</th>
    <td width="600px" class="underlined-title">' . $andrologyProcedure->observations . '</td>
</tr>
</table>
<br>
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
