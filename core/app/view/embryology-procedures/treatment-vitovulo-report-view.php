<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
try {
    $mpdf = new \Mpdf\Mpdf(['orientation' => 'L', 'format' => 'Letter',]);

    //Logo de clínica
    $logo = "";
    $path = $_SERVER["DOCUMENT_ROOT"] . "/assets/clinic-logo.png";
    //Validar que se ha subido un logo de la clínica para mostrar
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    $embryologyProcedure = PatientCategoryData::getById($_GET["id"]);
    $embryologyProcedureId = $_GET["id"];
    $patient = $embryologyProcedure->getPatient();
    $procedureDetails = EmbryologyProcedureData::getDetailsByProcedure($embryologyProcedure->patient_treatment_id, $embryologyProcedureId);

    //DATOS OFICIALES DEL PACIENTE Y SU PAREJA
    $patientOfficialData = $patient->getPatientOfficialData(); //Cargar dato oficial del paciente (rfc,curp,pasaporte).
    $patientBirthday = ($patient->birthday_format != "00/00/0000") ? $patient->birthday_format : "No especificada";
    //Obtener los datos de la pareja asignada en ese procedimiento
    $partner = $embryologyProcedure->getPartnerData();

    //Número de ciclos por tratamiento que ha realizado
    $actualCycleData = PatientCategoryData::getTotalPatientTreatmentsByType($embryologyProcedure->patient_id, $embryologyProcedure->patient_treatment_id, $embryologyProcedure->id);
    $actualCycle = $actualCycleData->total;

    //OBTENER DATOS DEL FORMATO/TABLA SECCIONES Y CONTENIDO DE TABLA DE ÓVULOS
    $sections = PatientOvuleData::getAllSectionsByTreatment($embryologyProcedure->patient_treatment_id, 1);
    $sectionDetails = PatientOvuleData::getAllSectionDetailsByTreatment($embryologyProcedure->patient_treatment_id, 1);
    $procedureOvules = PatientOvuleData::getOvulesByProcedureSectionId($embryologyProcedureId, 1);

    $user = UserData::getLoggedIn();
    $userType = $user->tipo_usuario;

    $treatmentDiagnostics = TreatmentDiagnosticData::getByTreatmentString($_GET["id"]);
    //Imágenes de los óvulos
    $ovuleImagesData = EmbryologyProcedureData::getFilesByTreatmentSectionId($embryologyProcedureId,1);
    $arrayOvuleImages = array_chunk($ovuleImagesData,4);

    $html = '
    <style>
    table.patient-details tr,
    table.patient-details th,
    table.patient-details td{
        border: none;
        font-family: "Helvetica";
        font-size:9pt;
        text-align:center;
    }

    table.ovule-details tr,
    table.ovule-details th,
    table.ovule-details td{
        border:.3px solid gray;
        border-left:.1px solid gray;
        border-right:.1px solid gray;
        font-family: "Helvetica";
        font-size:9pt;
        text-align:center;
    }
    td {
        padding: 0px;
        font-style:normal;
        font-weight:normal;
        font-family: "Helvetica";
    }
    .title-document{
        font-size:13pt;
        font-weight:bold;
        font-family: "Helvetica";
        text-align:center;
    }
    .title{
        background-color:#F7CFDF;
        font-style:normal;
        font-weight:bold;
        font-size:10pt;
    }
    .subtitle-document{
        border-bottom: 2px solid rgb(13,173,224);
        text-align: left;
        font-size:11pt;
        font-weight:bold;
        font-family: "Helvetica";
    }
    .table-title{
        //background-color:#ACE7FB;
        color:white;
        font-style:normal;
        font-weight:bold;
        font-size:10pt;
    }

    .group-title{
        font-weight:bold !important;
    }
    .center{
        text-align: center;
    }
    </style>
    <table width="100%">
        <tr>
            <td width="150px"><img width="150px" height="90px" src="' . $logo . '" /></td>
            <td class="title-document">VITRIFICACIÓN DE ÓVULOS <br>' . $embryologyProcedure->treatment_code . '</td>
        </tr>
    </table>
    <br>
    <table width="100%">
    <tr>
        <td class="subtitle-document bold">DATOS DEL PROCEDIMIENTO</td>
    </tr>
    </table>
    <br>
    <table width="100%" class="patient-details">
        <tr class="title">
            <th>PACIENTE</th>
            <th>PAREJA</th>
        </tr>
        <tr>
        <td><b>Nombre: </b>' . $patient->name . '<br>
            <b>' . $patientOfficialData->name. ': </b>' . $patientOfficialData->value . '<br>
          <b>Edad: </b>' . $patient->getAge() . '<br>
        </td>
        <td><b>Nombre pareja: </b>' . $partner->name . '<br>
            <b>' . $partner->officialDocumentName . ': </b>' . $partner->officialDocumentValue . '<br>
            <b>Edad: </b>' . $partner->age . '<br>
        </td>
        </tr>
    </table>
    <table width="100%" class="patient-details">
    <tr class="title">
        <th>Ciclo</th>
        <th>Fecha estimulación</th>
        <th>Fecha de aspiración folicular</th>
        <th>Aspiración - Estradiol</th>
        <th>Consentimiento femenino</th>
    </tr>
    <tr>';
    $femaleConsent = (($procedureDetails['49'] == 1) ? "Sí" : "No");
    $html .= '
    <td>' . $actualCycle . '</td>
    <td>' . $embryologyProcedure->getDateMonthFormat($embryologyProcedure->start_date) . '</td>
    <td>' . $embryologyProcedure->getDateMonthFormat($procedureDetails['30']) . '</td>
    <td>' . $procedureDetails['32'] . '</td>
    <td>' . $femaleConsent . '</td>
</tr>
<tr class="title">
    <th colspan="2">Diagnóstico</th>
    <th>Óvulos recuperados</th>
    <th>Cantidad de folículos</th>
    <th>MI</th>
</tr>
<tr>
<td>' . $treatmentDiagnostics . '</td>
<td>' . $procedureDetails['34'] . '</td>
<td>' . $procedureDetails['35'] . '</td>
<td>' . $procedureDetails['36'] . '</td>
</tr>
<tr>
    <th class="title">MII</th>
    <th class="title">Vesícula germinal</th>
    <th class="title">Degenerado</th>
</tr>
<tr>
<td>' . $procedureDetails['37'] . '</td>
<td>' . $procedureDetails['38'] . '</td>
<td>' . $procedureDetails['39'] . '</td>
</tr>
<tr class="title">
    <th>Fecha vitrificación</th>
    <th>Número de dispositivo</th>
    <th>Cesta</th>
    <th>Tanque</th>
    <th>Varilla</th>
</tr>
<tr>
<td>' . $procedureDetails['40']  . '</td>
<td>' . $procedureDetails['43'] . '</td>
<td>' . $procedureDetails['44'] . '</td>
<td>' . $procedureDetails['45'] . '</td>
<td>' . $procedureDetails['497'] . '</td>
</tr>
<tr>
    <th  class="title">Hora</th>
    <th class="title">Embriólogo</th>
    <th class="title">Cantidad de óvulos vitrificados</th>
</tr>
<tr>';
$embryologist = ((isset($procedureDetails['47'])) ? MedicData::getById($procedureDetails['47'])->name:"");
$html .= '
<td>' . $procedureDetails['46']  . '</td>
<td>' . $embryologist . '</td>
<td>' . $procedureDetails['48'] . '</td>
</tr>
</table>
    <br>
    <table id="ovulesDataTable" cellspacing="0" class="ovule-details cell-border compact" style="width:100%">
    <thead>
      <tr class="table-title">
        <th rowspan="2">#</th>
    ';
    foreach ($sections as $section) {
        $html .= '<th colspan="' . $section->total_section_details . '">' . $section->name . '</th>';
    }
    $html .= '<th colspan="2">DESTINO</th>
      </tr>
      <tr  class="table-title">';
    foreach ($sectionDetails as $sectionDetail) {
        $html .= '<th>' . $sectionDetail->name . '</th>';
    }
    $html .= '<th>C</th>
        <th>NV</th>
      </tr>
    </thead>
    <tbody>';
    foreach ($procedureOvules as $ovule) {
        $ovuleSectionDetailValues = PatientOvuleData::getSectionDetailsByOvuleId($embryologyProcedure->patient_treatment_id, 1, $ovule->id);
        $html .= '<tr>
          <td>' . $ovule->procedure_code . '</td>';
        foreach ($ovuleSectionDetailValues as $ovuleValue) {
            $html .= '<td style="background-color:'.((($ovuleValue->ovule_section_detail_id == "58" || $ovuleValue->ovule_section_detail_id == "60") && $ovuleValue->value != "") ? $ovuleValue->value : "#FFFFFF").'">';
            $html .= (($ovuleValue->ovule_section_detail_id != "58" && $ovuleValue->ovule_section_detail_id != "60") ? $ovuleValue->value:"");
            $html .= '</td>';
        }
        $html .= '<td>' . (($ovule->end_ovule_status_id == 2) ? "X" : "") . '</td>
          <td>' . (($ovule->end_ovule_status_id == 4) ? "X" : "") . '</td>
        </tr>';
    }
    $html .= '</tbody>
    <tfoot>
      <tr class="table-title">
        <th rowspan="2">#</th>';
    foreach ($sectionDetails as $sectionDetail) {
        $html .= '<th>' . $sectionDetail->name . '</th>';
    }
    $html .= '<th>C</th>
        <th>NV</th>
      </tr>
      <tr class="table-title">';
    foreach ($sections as $section) {
        $html .= '<th colspan="' . $section->total_section_details . '">' . $section->name . '</th>';
    }
    $html .= '<th colspan="2">DESTINO</th>
      </tr>
    </tfoot>
  </table>
  <div style="page-break-after: always"></div>
<table width="100%">
<tr>
    <td class="subtitle-document bold">OBSERVACIONES</td>
</tr>
<tr>
    <td>' . $embryologyProcedure->embryology_procedure_observations . '</td>
</tr>
</table>
<div style="page-break-after: always"></div>
<table width="100%">
<tr>
    <td class="subtitle-document bold">IMÁGENES</td>
</tr>
</table>
<table class="patient-details">';
    foreach($arrayOvuleImages as $ovuleImages){
        $html.='<tr>';
        foreach($ovuleImages as $ovuleImage){
            $html.='<td width="250px"><img width="230px" height="230px" src="' . $ovuleImage->path . '" /></td>';
        }
        $html.='</tr>
        <tr class="title">';
        foreach($ovuleImages as $ovuleImage){
            $html.='<th width="250px">#' . $ovuleImage->getProcedureOvule()->procedure_code . '</th>';
        }        
        $html.='</tr>';
    }
$html.='</table>';
    $mpdf->WriteHTML($html);

    // Other code
    ob_get_clean();
    $mpdf->Output();
} catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
    // Process the exception, log, print etc.
    echo $e->getMessage();
}
