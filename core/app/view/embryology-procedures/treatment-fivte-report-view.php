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
    $ovuleImagesData = EmbryologyProcedureData::getFilesByTreatmentSectionId($embryologyProcedureId, 1);
    $arrayOvuleImages = array_chunk($ovuleImagesData, 4);

    $totalEmbryoTransfers = 0;
    $totalEmbryoVitrifications = 0;

    $transferDetail = EmbryologyProcedureTransferData::getByTreatmentId($embryologyProcedureId);
    if (!$transferDetail) $transferDetail = new EmbryologyProcedureTransferData();

    $embryoVitrificationDetail = EmbryologyProcedureVitrificationData::getByTreatmentId($embryologyProcedureId);
    if (!$embryoVitrificationDetail) $embryoVitrificationDetail = new EmbryologyProcedureVitrificationData();

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
        //background-color:#E8E8E8;
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
            <td class="title-document">FERTILIZACIÓN IN VITRO <br>' . $embryologyProcedure->treatment_code . '</td>
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
            <b>' . $patientOfficialData->name . ': </b>' . $patientOfficialData->value . '<br>
          <b>Edad: </b>' . $patient->getAge() . '<br>
        </td>
        <td><b>Nombre pareja: </b>' . $partner->name . '<br>
            <b>' . $partner->officialDocumentName . ': </b>' . $partner->officialDocumentValue . '<br>
            <b>Edad: </b>' . $partner->age . '<br>
        </td>
        </tr>
    </table>
    </br>
    <table width="100%" class="patient-details">
    <tr class="title">
        <th>Ciclo</th>
        <th>Fecha estimulación</th>
        <th>Fecha de aspiración folicular</th>
        <th>Aspiración - Estradiol</th>
        <th>Progesterona</th>
    </tr>
    <tr>
    <td>' . $actualCycle . '</td>
    <td>' . $embryologyProcedure->getDateMonthFormat($embryologyProcedure->start_date) . '</td>
    <td>' . $embryologyProcedure->getDateMonthFormat($procedureDetails['2']) . '</td>
    <td>' . $procedureDetails['3'] . '</td>
    <td>' . $procedureDetails['19'] . '</td>
</tr>
<tr class="title">
    <th>Beta Hcg.</th>
    <th>Consentimiento femenino</th>
    <th>Consentimiento masculino</th>
    <th colspan="2">Diagnóstico</th>
</tr>
<tr>
<td>' . $procedureDetails['4'] . '</td>';
    $femaleConsent = (($procedureDetails['7'] == 1) ? "Sí" : "No");
    $maleConsent = (($procedureDetails['8'] == 1) ? "Sí" : "No");
    $html .= '<td>' . $femaleConsent . '</td>
<td>' . $maleConsent . '</td>
<td>' . $treatmentDiagnostics . '</td>
</tr>
<tr class="title">
<th colspan="5">Semen</th>
</tr>
<tr>
<td colspan="5"></td>
</tr>
<tr class="title">
    <th>Cantidad de folículos</th>
    <th>Óvulos recuperados</th>
    <th>MI</th>
    <th>MII</th>
    <th>Vesícula germinal</th>
</tr>
<tr>
<td>' . $procedureDetails['20'] . '</td>
<td>' . $procedureDetails['10'] . '</td>
<td>' . $procedureDetails['11'] . '</td>
<td>' . $procedureDetails['12'] . '</td>
<td>' . $procedureDetails['13'] . '</td>
</tr>
<tr class="title">
    <th>Degenerado</th>
    <th>Fecundación-Ovocitos inseminados</th>
    <th>Fecundación-Fertilizados</th>
</tr>
<tr>
<td>' . $procedureDetails['14'] . '</td>
<td>' . $procedureDetails['15'] . '</td>
<td>' . $procedureDetails['16'] . '</td>
</tr>
</table>
    <br>
    <table cellspacing="0" class="ovule-details cell-border compact" style="width:100%">
    <thead>
      <tr class="table-title">
        <th rowspan="2">#</th>
    ';
    foreach ($sections as $section) {
        $html .= '<th colspan="' . $section->total_section_details . '">' . $section->name . '<br>' . calculateSectionDate($section->day_number, 2) . '</th>';
    }
    $html .= '<th colspan="3">DESTINO</th>
        <th rowspan="2">MUESTRA SEMEN</th>
      </tr>
      <tr  class="table-title">';
    foreach ($sectionDetails as $sectionDetail) {
        $html .= '<th>' . $sectionDetail->name . '</th>';
    }
    $html .= '<th>TE</th>
        <th>C</th>
        <th>NV</th>
      </tr>
    </thead>
    <tbody>';
    foreach ($procedureOvules as $ovule) {
        $ovuleSectionDetailValues = PatientOvuleData::getSectionDetailsByOvuleId($embryologyProcedure->patient_treatment_id, 1, $ovule->id);
        $html .= '<tr>
          <td>' . $ovule->procedure_code . '</td>';
        foreach ($ovuleSectionDetailValues as $ovuleValue) {
            $html .= '<td>' . $ovuleValue->value . '</td>';
        }
        $html .= '<td>' . (($ovule->end_ovule_status_id == 3) ? "X" : "") . '</td>
          <td>' . (($ovule->end_ovule_status_id == 2) ? "X" : "") . '</td>
          <td>' . (($ovule->end_ovule_status_id == 4) ? "X" : "") . '</td>';
          
        $semen = "";
        if ($ovule->patient_andrology_procedure_id) {
            $andrologyProcedureData = $ovule->getProcedureOvuleSemen();
            $donorDetail = ($andrologyProcedureData->patient_donor_id != '') ? '(' . $andrologyProcedureData->patient_donor_id . ')' : '';
            $semenType = ($andrologyProcedureData->patient_id == $partner->id) ? "PAREJA" : "DONANTE " . $donorDetail;
            $semen = "<b>" . $semenType . "</b><br>" . $andrologyProcedureData->procedure_code;
        }
        $html .= '<td>' . $semen . '</td></tr>';

        $endOvulePhaseId = (($ovule->end_ovule_phase_id) ? $ovule->end_ovule_phase_id : $ovule->initial_ovule_phase_id);
        if ($ovule->end_ovule_status_id == 3 && $endOvulePhaseId == 2) {
            $totalEmbryoTransfers++;
        } else if ($ovule->end_ovule_status_id == 2 && $endOvulePhaseId == 2) {
            $totalEmbryoVitrifications++;
        }
    }
    $html .= '</tbody>
    <tfoot>
      <tr class="table-title">
        <th rowspan="2">#</th>';
    foreach ($sectionDetails as $sectionDetail) {
        $html .= '<th>' . $sectionDetail->name . '</th>';
    }
    $html .= '<th>TE</th>
        <th>C</th>
        <th>NV</th>
        <th rowspan="2">MUESTRA SEMEN</th>
      </tr>
      <tr class="table-title">';
    foreach ($sections as $section) {
        $html .= '<th colspan="' . $section->total_section_details . '">' . $section->name . '</th>';
    }
    $html .= '<th colspan="3">DESTINO</th>
      </tr>
    </tfoot>
  </table>
  <div style="page-break-after: always"></div>';
    if ($totalEmbryoVitrifications > 0) {
        $html .= '<table width="100%">
    <tr>
        <td class="subtitle-document bold">INFORMACIÓN VITRIFICACIÓN Y UBICACIÓN DE EMBRIONES</td>
    </tr>
    </table>
    <br>
    <table width="100%" class="patient-details">
    <tr class="title">
        <th>Fecha vitrificación</th>
        <th>Código de vitrificación</th>
        <th>Varilla</th>
        <th>Color de varilla</th>
        <th>Número de dispositivo</th>
    </tr>';
        $vitrificationEmbryos = EmbryologyProcedureVitrificationData::getDetailsByTreatmentId($_GET["id"]);

        $arrayEmbryoVitrificationDates = [];
        foreach ($vitrificationEmbryos as $embryo) {
            if ($embryo->date) {
                $date = new DateTime($embryo->date);
                $dateFormat = $date->format("d/m/Y");
                $arrayEmbryoVitrificationDates[] = $dateFormat;
            }
        }
        $embryoVitrificationStringDates = implode(",", (array_unique($arrayEmbryoVitrificationDates)));
        $html .= '<tr>
    <td>' . $embryoVitrificationStringDates . '</td>
    <td>' . $embryoVitrificationDetail->code . '</td>
    <td>' . $embryoVitrificationDetail->rod . '</td>
    <td style="background-color:' . (($embryoVitrificationDetail->rod_color != "") ? $embryoVitrificationDetail->rod_color : "#FFFFFF") . '"></td>
    <td>' . $embryoVitrificationDetail->device_number . '</td>
    </tr>
    <tr>
    <th class="title">Color de dispositivo</th>
    <th class="title">Cesta</th>
    <th class="title">Tanque</th>
    </tr>
    <tr>
    <td style="background-color:' . (($embryoVitrificationDetail->device_color != "") ? $embryoVitrificationDetail->device_color : "#FFFFFF") . '"></td>
    <td>' . $embryoVitrificationDetail->basket . '</td>
    <td>' . $embryoVitrificationDetail->tank . '</td>
    </tr>
    </table>
    <br>
    <table cellspacing="0" class="ovule-details cell-border compact" style="width:100%">
    <thead>
    <tr>
        <th rowspan="2">#</th>
        <th colspan="8"></th>
    </tr>
    <tr>
        <th>FECHA</th>
        <th>ESTADÍO</th>
        <th>DESTINO</th>
        <th>NO. DISPOSITIVO</th>
        <th>COLOR DISPOSITIVO</th>
        <th>VARILLA</th>
        <th>COLOR VARILLA</th>
        <th>INCIDENCIAS</th>
    </tr>
    </thead>
    <tbody>';
        foreach ($vitrificationEmbryos as $embryo) {
            $rodColor = (($embryo->rod_color != "") ? '' . $embryo->rod_color : '#FFFFFF');
            $deviceColor = (($embryo->device_color != "") ? '' . $embryo->device_color : '#FFFFFF');
            $date = new DateTime($embryo->date);
            $dateFormat = $date->format("d/m/Y");

            $html .= '<tr><td>' . ($embryo->getPatientOvule()->procedure_code) . '</td>
            <td>' . $dateFormat . '</td>
            <td>' . $embryo->stage . '</td>
            <td>' . $embryo->destiny . '</td>
            <td>' . $embryo->rod . '</td>
            <td style="background-color:' . $rodColor . '"></td>
            <td>' . $embryo->device_number . '</td>
            <td style="background-color:' . $deviceColor . '"></td>
            <td>' . $embryo->incidents . '</td>
        </tr>';
        }
        $html .= '</tbody>
    </table>';
    }
    if ($totalEmbryoTransfers > 0) {
        $html .= '<br>
    <table width="100%">
    <tr>
        <td class="subtitle-document bold">INFORMACIÓN TRANSFERENCIA EMBRIONARIA</td>
    </tr>
    </table>
    <br>
    <table width="100%" class="patient-details">
    <tr class="title">
    <th>Fecha de transferencia</th>
    <th>Hora</th>
    <th>Id de embrión transferido</th>
    <th>Total transferidos</th>
    <th>Calidad</th>
    </tr>
    <tr>
    <td>' . $embryologyProcedure->getDateMonthFormat($transferDetail->date) . '</td>
    <td>' . $transferDetail->hour . '</td>
    <td>' . $transferDetail->embryo_id_details . '</td>
    <td>' . $transferDetail->total . '</td>
    <td>' . $transferDetail->quality . '</td>
    </tr>
    <tr class="title">
    <th colspan="2">Ginecólogo</th>
    <th>Ecografista</th>
    <th>Embriólogo</th>
    <th>Testigo</th>
    </tr>
    <tr>
        <td>' . ((MedicData::getById($transferDetail->gynecologist_id)) ? MedicData::getById($transferDetail->gynecologist_id)->name : "") . '</td>
        <td>' . ((MedicData::getById($transferDetail->gynecologist_id)) ? MedicData::getById($transferDetail->sonographer_id)->name : "") . '</td>
        <td>' . ((MedicData::getById($transferDetail->gynecologist_id)) ? MedicData::getById($transferDetail->embryologist_id)->name : "") . '</td>
        <td>' . ((MedicData::getById($transferDetail->gynecologist_id)) ? MedicData::getById($transferDetail->witness_id)->name : "") . '</td>
    </tr>
    <tr class="title"><
    <th colspan="2">Estradiol</th>
    <th>Cánula</th>
    <th>Lote</th>
    <th>Caducidad</th>
    </tr>
    <tr>
        <td>' . $transferDetail->estradiol . '</td>
        <td>' . $transferDetail->catheter . '</td>
        <td>' . $transferDetail->catheter_lot . '</td>
        <td>' . $transferDetail->catheter_expiration . '</td>
    </tr>
    <tr class="title">
    <th colspan="2">Progesterona</th>
    <th>Jeringa</th>
    <th>Lote</th>
    <th>Caducidad</th>
    </tr>
    <tr>
        <td>' . $transferDetail->progesterone . '</td>
        <td>' . $transferDetail->syringe . '</td>
        <td>' . $transferDetail->syringe_lot . '</td>
        <td>' . $transferDetail->syringe_expiration . '</td>
    </tr>
    <tr class="title">
    <th colspan="5">Observaciones</th>
    </tr>
    <tr>
        <td colspan="5">' . $transferDetail->observations . '</td>
    </tr>
    </table>';
    }
    $html .= '<br>
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
    foreach ($arrayOvuleImages as $ovuleImages) {
        $html .= '<tr>';
        foreach ($ovuleImages as $ovuleImage) {
            $html .= '<td width="250px"><img width="230px" height="230px" src="' . $ovuleImage->path . '" /></td>';
        }
        $html .= '</tr>
        <tr class="title">';
        foreach ($ovuleImages as $ovuleImage) {
            $html .= '<th width="250px">#' . $ovuleImage->getProcedureOvule()->procedure_code . '</th>';
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
