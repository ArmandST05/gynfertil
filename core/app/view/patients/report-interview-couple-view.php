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

    function validateIsChecked($index, $array)
    {
        $arrayValues = explode(",", $array);
        foreach ($arrayValues as $arrayValue) {
            if ($arrayValue != null && (substr($arrayValue, 0, 1) == $index)) {
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
    .border-left{
        border-left:1px solid black;
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
            <td><b>Entrevista Pareja</b></td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td><b>Nombre Px1:</b></td>
            <td class="patient-data" width="200px">' . $details['77']  . '</td>
            <td>Edad:</td>
            <td class="patient-data">' . $patient->getAgeByBirthdayDate($details['78'])  . '</td>
            <td>Sexo:</td>
            <td class="patient-data">' . ((SexData::getById($details['79'])) ? SexData::getById($details['79'])->name : "") . '</td>
            <td>Fecha nacimiento:</td>
            <td class="patient-data">' . $patient->getDateFormat($details['78']) . '</td>
        </tr>
    </table>
    <table>
    <tr>
        <td>Grado escolar:</td>
        <td class="patient-data" width="100px">' . ((EducationLevelData::getById($details['80'])) ? EducationLevelData::getById($details['80'])->name : '') . '</td>
        <td>Ocupación:</td>
        <td class="patient-data" width="100px">' . $details['81'] . '</td>
        <td>Teléfono:</td>
        <td class="patient-data">X</td>
    </tr>
</table>
<br>
    <table width="100%">
        <tr>
            <td><b>Nombre Px2:</b></td>
            <td class="patient-data" width="200px">' . $details['100']  . '</td>
            <td>Edad:</td>
            <td class="patient-data">' . $patient->getAgeByBirthdayDate($details['101'])  . '</td>
            <td>Sexo:</td>
            <td class="patient-data">' . ((SexData::getById($details['102'])) ? SexData::getById($details['102'])->name : "") . '</td>
            <td>Fecha nacimiento:</td>
            <td class="patient-data">' . $patient->getDateFormat($details['101']) . '</td>
        </tr>
    </table>
    <table>
    <tr>
        <td>Grado escolar:</td>
        <td class="patient-data" width="100px">' . ((EducationLevelData::getById($details['103'])) ? EducationLevelData::getById($details['103'])->name : '') . '</td>
        <td>Ocupación:</td>
        <td class="patient-data" width="100px">' . $details['104'] . '</td>
        <td>Teléfono:</td>
        <td class="patient-data">X</td>
    </tr>
</table>
<br>
<table width="100%">
<tr>
    <td>Edo. civil:</td>
    <td class="patient-data" width="100px">' . $details['55'] . '</td>
    <td>Tiempo:</td>
    <td class="patient-data">' . $details['56'] . '</td>
    <td>Hijos de relación:</td>
    <td class="patient-data">' . $details['76'] . '</td>
</tr>
</table>
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
        <td>Tratamiento psicológico anteriormente en pareja:</td>
        <td class="patient-data">' . $details['1'] . '</td>
    </tr>
</table>

<table width="100%">
    <tr>
        <th>Px1:</th>
        <th>Px2:</th>
    </tr>
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td>Tratamiento psicológico individual anteriormente:</td>
                    <td class="patient-data">' . $details['123'] . '</td>
                    <td>Tiempo:</td>
                    <td class="patient-data">' . $details['124'] . '</td>
                </tr>
                <tr>
                    <td colspan="4">Motivo:</td>
                </tr>
                <tr>
                    <td colspan="4" class="patient-data">' . $details['125'] . '</td>
                </tr>
                <tr>
                    <td colspan="2">No. de relación actual:</td>
                    <td colspan="2" class="patient-data" >' . $details['83'] . '</td>
                </tr>
                <tr>
                    <td colspan="2">No. de hijos de relación anterior:</td>
                    <td colspan="2" class="patient-data">' . $details['84'] . '</td>
                </tr>
                <tr>
                    <td class="patient-data">' . (($details['85'] == 1) ? "X" : "") . '</td>
                    <td>Padres juntos</td>
                    <td class="patient-data">' . (($details['85'] == 2) ? "X" : "") . '</td>
                    <td>Padres separados:</td>
                </tr>
                <tr>
                    <td colspan="4">Presenció situaciones:</td>
                </tr>
            </table>
            <table width="100%" class="symptoms">
                <tr>
                    <th width="10px">' . validateIsChecked(0, $details['87']) . '</th>
                    <td>Alcoholismo</td>
                    <th width="10px">' . validateIsChecked(0, $details['88']) . '</th>
                    <td>Violencia física/verbal</td>
                </tr>
                <tr>
                    <th width="10px">' . validateIsChecked(0, $details['89']) . '</th>
                    <td>Infidelidades</td>
                    <th width="10px">' . validateIsChecked(0, $details['90']) . '</th>
                    <td>Celos</td>
                </tr>
            </table>
        </td>
        <td class="border-left">
            <table width="100%">
                <tr>
                    <td>Tratamiento psicológico individual anteriormente:</td>
                    <td class="patient-data">' . $details['126'] . '</td>
                    <td>Tiempo:</td>
                    <td class="patient-data">' . $details['127'] . '</td>
                </tr>
                <tr>
                <td colspan="4">Motivo:</td>
                    </tr>
                <tr>
                    <td colspan="4" class="patient-data">' . $details['128'] . '</td>
                </tr>
                <tr>
                    <td colspan="2">No. de relación actual:</td>
                    <td colspan="2" class="patient-data">' . $details['106'] . '</td>
                </tr>
                <tr>
                    <td colspan="2">No. de hijos de relación anterior:</td>
                    <td colspan="2" class="patient-data">' . $details['107'] . '</td>
                </tr>
                <tr>
                    <td class="patient-data">' . (($details['108'] == 1) ? "X" : "") . '</td>
                    <td>Padres juntos</td>
                    <td class="patient-data">' . (($details['108'] == 2) ? "X" : "") . '</td>
                    <td>Padres separados:</td>
                </tr>
                <tr>
                    <td colspan="4">Presenció situaciones:</td>
                </tr>
            </table>
            <table width="100%" class="symptoms">
                <tr>
                    <th width="10px">' . validateIsChecked(1, $details['87']) . '</th>
                    <td>Alcoholismo</td>
                    <th width="10px">' . validateIsChecked(1, $details['88']) . '</th>
                    <td>Violencia física/verbal</td>
                </tr>
                <tr>
                    <th width="10px">' . validateIsChecked(1, $details['89']) . '</th>
                    <td>Infidelidades</td>
                    <th width="10px">' . validateIsChecked(1, $details['90']) . '</th>
                    <td>Celos</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="100%">
<tr>
    <td><b>Motivo actual de consulta:</b></td>
</tr>
</table>
<table width="100%" class="symptoms">
<tr>
    <th width="20px">Px1</th>
    <th width="20px">Px2</th>
    <th></th>
    <th width="20px">Px1</th>
    <th width="20px">Px2</th>
    <th></th>
    <th width="20px">Px1</th>
    <th width="20px">Px2</th>
    <th></th>
    <th width="20px">Px1</th>
    <th width="20px">Px2</th>
    <th></th>
</tr>
<tr>
    <td width="20px">' . validateIsChecked(0, $details['92']) . '</td>
    <td width="20px">' . validateIsChecked(1, $details['92']) . '</td>
    <td>Falta de afecto</td>
    <td width="20px">' . validateIsChecked(0, $details['93'])  . '</td>
    <td width="20px">' . validateIsChecked(1, $details['93'])  . '</td>
    <td>Deberes del hogar</td>
    <td width="20px">' . validateIsChecked(0, $details['94'])  . '</td>
    <td width="20px">' . validateIsChecked(1, $details['94'])  . '</td>
    <td>Finanzas</td>
    <td width="20px">' . validateIsChecked(0, $details['95'])  . '</td>
    <td width="20px">' . validateIsChecked(1, $details['95'])  . '</td>
    <td>Violencia</td>
</tr>
<tr>
    <td width="20px">' . validateIsChecked(0, $details['96']) . '</td>
    <td width="20px">' . validateIsChecked(1, $details['96']) . '</td>
    <td>Temas de recreación</td>
    <td width="20px">' . validateIsChecked(0, $details['65'])  . '</td>
    <td width="20px">' . validateIsChecked(1, $details['65'])  . '</td>
    <td>Sexualidad</td>
    <td width="20px">' . validateIsChecked(0, $details['97'])  . '</td>
    <td width="20px">' . validateIsChecked(1, $details['97'])  . '</td>
    <td>Amistades</td>
    <td width="20px">' . validateIsChecked(0, $details['98'])  . '</td>
    <td width="20px">' . validateIsChecked(1, $details['98'])  . '</td>
    <td>Familia origen</td>
</tr>
<tr>
    <td width="20px">' . validateIsChecked(0, $details['99']) . '</td>
    <td width="20px">' . validateIsChecked(1, $details['99']) . '</td>
    <td>Filosofía, valores, creencias</td>
    <td width="20px"></td>
    <td width="20px"></td>
    <td>Otros</td>
    <td width="20px"></td>
    <td width="20px"></td>
    <td>Otros</td>
    <td width="20px"></td>
    <td width="20px"></td>
    <td></td>
</tr>
</table>
<br>
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
