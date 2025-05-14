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

    $configuration = ConfigurationData::getAll();
    $patientTreatmentId = $_GET["id"];
    $patientTreatment = TreatmentData::getPatientTreatmentById($patientTreatmentId);
    $details = TreatmentData::getDetailsByPatientTreatment($patientTreatmentId);
    $patient = $patientTreatment->getPatient();
    $medicName = ($patientTreatment->getMedic()) ? $patientTreatment->getMedic()->name:"";
    
    $firstAttendedDate = ReservationData::getFirstByDatesStatusPatientId($patient->id,2,$patientTreatment->start_date,$patientTreatment->end_date);
    if($firstAttendedDate){//Validar si hay primera cita donde asistió el paciente
        $dateInterview = $firstAttendedDate->date_format;
    }else{
        $firstNextDate = ReservationData::getFirstByDatesStatusPatientId($patient->id,1,$patientTreatment->start_date,$patientTreatment->end_date);
        if($firstNextDate){
            $dateInterview = $firstNextDate->date_format;
        }else{
            $dateInterview = $patientTreatment->start_date_format;
        }
    }

    function validateIsChecked($index,$array){ 
        $arrayValues = explode(",",$array);
        foreach($arrayValues as $arrayValue){
          if($arrayValue != null && (substr($arrayValue, 0, 1) == $index)){
            return "X";
          }
        }
      }

    $html = '
    <style>
    table.symptoms tr,
    table.symptoms th,
    table.symptoms td{
        border:.3px solid gray;
        border-left:.1px solid gray;
        border-right:.1px solid gray;
        font-family: "Helvetica";
        font-size:9pt;
        text-align:justify;
    }

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
        background-color:#F0F0F0;
    }
    .group-title{
        font-weight:bold !important;
    }
    .center-content{
        text-align: center;
    }
    .patient-data{
        border-bottom:.1px solid gray;
    }
    .underlined{
        border-bottom:.1px solid gray;
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
            <td rowspan="2"><img width="150px" height="90px" src="' . $logo . '" /></td>
            <th class="title-document" align="center" rowspan="2">' . $configuration['name']->value . '</th>
            <td>Fecha:</td>
            <td>' . $patientTreatment->start_date_format. '</td>
        </tr>
        <tr>
        <td>Terapeuta:</td>
        <td>' . $medicName . '</td>
        </tr>
    </table>
    <br>
    <table width="100%" style="text-align:center;">
        <tr>
            <td><b>Entrevista Adolescente</b></td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td>Nombre:</td>
            <td class="patient-data" width="200px">' . $patient->name . '</td>
            <td>Edad:</td>
            <td class="patient-data">' . $patient->getAge() . '</td>
            <td>Sexo:</td>
            <td class="patient-data">' . $patient->getSex()->name . '</td>
            <td>Fecha nacimiento:</td>
            <td class="patient-data">' . $patient->getBirthdayFormat() . '</td>
        </tr>
    </table>
    <br>
    <table>
    <tr>
        <td>Grado escolar:</td>
        <td class="patient-data" width="200px">' . (($patient->getEducationLevel()) ? $patient->getEducationLevel()->name:"") . '</td>
        <td>Ocupación:</td>
        <td class="patient-data" width="200px">' . $patient->occupation . '</td>
    </tr>
</table>
<br>
<table width="100%">
<tr>
    <td>Nombre del padre o tutor:</td>
    <td class="patient-data" width="200px">' . $patient->relative_name . '</td>
    <td>Teléfono:</td>
    <td class="patient-data">X</td>
</tr>
</table>
<br>
<table width="100%">
<tr>
    <td>Dirección:</td>
    <td class="patient-data" width="200px">' . $patient->street . ' ' . $patient->number . ' ' . $patient->colony . '</td>
    <td>Municipio:</td>
    <td class="patient-data">' . $patient->getCounty()->name . '</td>
</tr>
</table>
<table width="100%">
<tr>
    <td>Ha llevado tratamiento anteriormente:</td>
    <td class="patient-data">' . $details['1'] . '</td>
    <td>Tiempo:</td>
    <td class="patient-data">' . $details['2'] . '</td>
    <td>Motivo:</td>
    <td class="patient-data">' . $details['3'] . '</td>
</tr>
</table>
<table width="100%">
<tr>
    <td>Motivo de Consulta:</td>
</tr>
<tr>
<td class="patient-data">' . $details['4'] . '</td>
</tr>
</table>
<table width="100%" class="symptoms">
<tr>
    <th colspan="8">Información de parte de los padres</th>
</tr>
<tr>
    <th width="20px">' . validateIsChecked(0,$details['5']) . '</th>
    <th>Ansiedad</th>
    <th width="20px">' . validateIsChecked(0,$details['11'])  . '</th>
    <th>Depresión</th>
    <th width="20px">' . validateIsChecked(0,$details['18'])  . '</th>
    <th>Conductas</th>
    <th width="20px">' . validateIsChecked(0,$details['23'])  . '</th>
    <th>Emociones</th>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(0,$details['34']) . '</td>
    <td>Mareos</td>
    <td width="20px">' . validateIsChecked(0,$details['39'])  . '</td>
    <td>Falta o exceso de sueño</td>
    <td width="20px">' . validateIsChecked(0,$details['44'])  . '</td>
    <td>Confronta figuras de autoridad</td>
    <td width="20px">' . validateIsChecked(0,$details['50'])  . '</td>
    <td>Cuesta comprender emociones</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(0,$details['35']) . '</td>
    <td>Náuseas</td>
    <td width="20px">' . validateIsChecked(0,$details['40'])  . '</td>
    <td>Sentimientos de culpa</td>
    <td width="20px">' . validateIsChecked(0,$details['45'])  . '</td>
    <td>Dificultades con su higiene</td>
    <td width="20px">' . validateIsChecked(0,$details['51'])  . '</td>
    <td>No percibe redes de apoyo</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(0,$details['36']) . '</td>
    <td>Falta de aire</td>
    <td width="20px">' . validateIsChecked(0,$details['41'])  . '</td>
    <td>Aislamiento social</td>
    <td width="20px">' . validateIsChecked(0,$details['46'])  . '</td>
    <td>Dificultades para realizar actividades en casa</td>
    <td width="20px">' . validateIsChecked(0,$details['52'])  . '</td>
    <td>Siente que no pertenece a ningún lugar</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(0,$details['9'])  . '</td>
    <td>Angustia constante</td>
    <td width="20px">' . validateIsChecked(0,$details['42'])  . '</td>
    <td>Ideación suicida</td>
    <td width="20px">' . validateIsChecked(0,$details['22'])  . '</td>
    <td>No respeta límites</td>
    <td width="20px">' . validateIsChecked(0,$details['53'])  . '</td>
    <td>Percepción negativa del entorno</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(0,$details['37'])  . '</td>
    <td>Pánico o miedo intenso</td>
    <td width="20px">' . validateIsChecked(0,$details['43'])  . '</td>
    <td>Irritabilidad</td>
    <td width="20px">' . validateIsChecked(0,$details['47'])  . '</td>
    <td>Rebeldía</td>
    <td width="20px">' . validateIsChecked(0,$details['54'])  . '</td>
    <td>Ausencia de metas</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(0,$details['38'])  . '</td>
    <td>Insomnio</td>
    <td width="20px">' . validateIsChecked(0,$details['17'])  . '</td>
    <td>Desmotivado</td>
    <td width="20px">' . validateIsChecked(0,$details['48'])  . '</td>
    <td>Adicciones</td>
    <td width="20px"></td>
    <td></td>
</tr>
</table>
<br>
<table width="100%" class="symptoms">
<tr>
    <th colspan="8">Información del paciente</th>
</tr>
<tr>
    <th width="20px">' . validateIsChecked(1,$details['5']) . '</th>
    <th>Ansiedad</th>
    <th width="20px">' . validateIsChecked(1,$details['11'])  . '</th>
    <th>Depresión</th>
    <th width="20px">' . validateIsChecked(1,$details['18'])  . '</th>
    <th>Conductas</th>
    <th width="20px">' . validateIsChecked(1,$details['23'])  . '</th>
    <th>Emociones</th>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(1,$details['34']) . '</td>
    <td>Mareos</td>
    <td width="20px">' . validateIsChecked(1,$details['39'])  . '</td>
    <td>Falta o exceso de sueño</td>
    <td width="20px">' . validateIsChecked(1,$details['44'])  . '</td>
    <td>Confronta figuras de autoridad</td>
    <td width="20px">' . validateIsChecked(1,$details['50'])  . '</td>
    <td>Cuesta comprender emociones</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(1,$details['35']) . '</td>
    <td>Náuseas</td>
    <td width="20px">' . validateIsChecked(1,$details['40'])  . '</td>
    <td>Sentimientos de culpa</td>
    <td width="20px">' . validateIsChecked(1,$details['45'])  . '</td>
    <td>Dificultades con su higiene</td>
    <td width="20px">' . validateIsChecked(1,$details['51'])  . '</td>
    <td>No percibe redes de apoyo</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(1,$details['36']) . '</td>
    <td>Falta de aire</td>
    <td width="20px">' . validateIsChecked(1,$details['41'])  . '</td>
    <td>Aislamiento social</td>
    <td width="20px">' . validateIsChecked(1,$details['46'])  . '</td>
    <td>Dificultades para realizar actividades en casa</td>
    <td width="20px">' . validateIsChecked(1,$details['52'])  . '</td>
    <td>Siente que no pertenece a ningún lugar</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(1,$details['9'])  . '</td>
    <td>Angustia constante</td>
    <td width="20px">' . validateIsChecked(1,$details['42'])  . '</td>
    <td>Ideación suicida</td>
    <td width="20px">' . validateIsChecked(1,$details['22'])  . '</td>
    <td>No respeta límites</td>
    <td width="20px">' . validateIsChecked(1,$details['53'])  . '</td>
    <td>Percepción negativa del entorno</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(1,$details['37'])  . '</td>
    <td>Pánico o miedo intenso</td>
    <td width="20px">' . validateIsChecked(1,$details['43'])  . '</td>
    <td>Irritabilidad</td>
    <td width="20px">' . validateIsChecked(1,$details['47'])  . '</td>
    <td>Rebeldía</td>
    <td width="20px">' . validateIsChecked(1,$details['54'])  . '</td>
    <td>Ausencia de metas</td>
</tr>
<tr>    
    <td width="20px">' . validateIsChecked(1,$details['38'])  . '</td>
    <td>Insomnio</td>
    <td width="20px">' . validateIsChecked(1,$details['17'])  . '</td>
    <td>Desmotivado</td>
    <td width="20px">' . validateIsChecked(1,$details['48'])  . '</td>
    <td>Adicciones</td>
    <td width="20px"></td>
    <td></td>
</tr>
</table>
<br>
<table width="100%">
<tr>
    <td>Quién cuida al paciente:</td>
    <td class="patient-data" width="200px">' . $details['29'] . '</td>
    <td>Desde hace cuánto:</td>
    <td class="patient-data">' . $details['30'] . '</td>
</tr>
</table>
<table width="100%">
<tr>
    <td>Situación actual del paciente:</td>
    <td class="patient-data" width="200px">' . $details['31'] . '</td>
</tr>
</table>
<table width="100%">
<tr>
    <td>Familiograma:</td>
    <td>Quién vive en casa:</td>
</tr>
<tr>
    <td class="patient-data" width="200px">' . $details['32'] . '<br></td>
    <td class="patient-data">' . $details['33'] . '<br></td>
</tr>
</table>
<br>
<table width="100%" class="symptoms">
    <tr>
        <th width="200px">Parentesco</th>
        <th width="250px">Nombre</th>
        <th width="80px">Edad</th>
        <th width="200px">Tipo de relación</th>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
        <td><br></td>
        <td><br></td>
        <td><br></td>
    </tr>
</table>
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
