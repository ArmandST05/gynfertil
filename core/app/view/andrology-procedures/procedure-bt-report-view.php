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
    </style>
    
    <table width="100%">
        <tr>
            <th class="title-document" align="center">BIOPSIA TESTICULAR</th>
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
            <td class="patient-subtitle">Recolección:</td>
            <td class="patient-data" width="70px">' . $procedureDetails['256'] . '</td>
            <td class="patient-subtitle">Procesamiento:</td>
            <td class="patient-data" width="70px">' . $procedureDetails['257'] . '</td>
            <td class="patient-subtitle">Procedencia:</td>
            <td class="patient-data" width="70px">' . $procedureDetails['258'] . '</td>
        </tr>
        <tr>
            <td class="patient-subtitle">Código congelación:</td>
            <td class="patient-data" width="70px">' . $procedureDetails['259'] . '</td>
            <td class="patient-subtitle">Cantidad de dispositivos:</td>
            <td class="patient-data" width="70px">' . $procedureDetails['260'] . '</td>
        </tr>
    </table>
<br>
    <table width="100%">
    <tr>
        <th class="title" colspan="4">ASPECTO GENERAL</th>
    </tr>
        <tr>
            <td width="160px"></td>
            <td class="bold" width="100px">RESULTADO</td>
        </tr> 
        <tr>
            <td class="content-title">Normal y turgente:</td>
            <td class="underlined center">' . $procedureDetails['261'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Vasectomía:</td>
            <td class="underlined center">' . $procedureDetails['262'] . '</td>
        </tr>
        <tr>
            <td class="content-title">Diagnóstico previo:</td>
            <td class="underlined center">' . $procedureDetails['263'] . '</td>
        </tr>
</table>


<table width="100%">
<tr>
    <th class="title" colspan="7">ANÁLISIS MICROSCÓPICO</th>
</tr>
    <tr>
        <td width="160px"></td>
        <td class="bold" width="90px">TESTÍCULO DERECHO</td>
        <td class="bold" width="60px"></td>
        <td class="bold" width="90px">TESTÍCULO IZQUIERDO</td>
        <td class="bold" width="60px"></td>
        <td class="bold" colspan="2">VALORES DE REFERENCIA OMS 2021</td>
        <td></td>
    </tr> 
    <tr>
        <td class="content-title">Resultado:</td>
        <td class="underlined center">' . $procedureDetails['264'] . '</td>
        <td class="underlined center"></td>
        <td class="underlined center">' . $procedureDetails['265'] . '</td>
        <td class="underlined center"></td>
        <td class="bold">Positivo</td>
        <td rowspan="5"><img width="150px" height="150px" src="' . $lens40xPath . '" /></td>
    </tr>
    <tr>
        <td class="content-title">Espermatozoides por campo:</td>
        <td class="underlined center">' . $procedureDetails['266'] . '</td>
        <td class="underlined center"></td>
        <td class="underlined center">' . $procedureDetails['267'] . '</td>
        <td class="underlined center"></td>
        <td class="bold">≥ 39 x 10⁶</td>
    </tr>
    <tr>
        <td class="content-title">Concentración (mill/mL):</td>
        <td class="underlined center">' . $procedureDetails['268'] . '</td>
        <td class="underlined center">x 10⁶/mL</td>
        <td class="underlined center">' . $procedureDetails['269'] . '</td>
        <td class="underlined center">x 10⁶/mL</td>
        <td class="bold">≤ 1 x 10⁶/ mL</td>
    </tr>
    <tr>
        <td class="content-title">Movilidad Progresiva (PR):</td>
        <td class="underlined center">' . $procedureDetails['270'] . '</td>
        <td class="underlined center">%</td>
        <td class="underlined center">' . $procedureDetails['271'] . '</td>
        <td class="underlined center">%</td>
        <td class="bold">≥ 30 % </td>
    </tr>
    <tr>
        <td class="content-title">Movilidad No Progresiva (NP):</td>
        <td class="underlined center">' . $procedureDetails['272'] . '</td>
        <td class="underlined center">%</td>
        <td class="underlined center">' . $procedureDetails['273'] . '</td>
        <td class="underlined center">%</td>
        <td class="bold">(NP) ≥ 1</td>
    </tr>
    <tr>
        <td class="content-title">Inmóviles (IM):</td>
        <td class="underlined center">' . $procedureDetails['274'] . '</td>
        <td class="underlined center">%</td>
        <td class="underlined center">' . $procedureDetails['275'] . '</td>
        <td class="underlined center">%</td>
        <td class="bold">(IM) ≤ 20% </td>
    </tr>
    <tr> 
        <td colspan="6"></td>
        <td rowspan="5"><img width="150px" height="150px" src="' . $lens100xPath . '" /></td>
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
