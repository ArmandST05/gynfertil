<?php
    $vitrificationEmbryos = EmbryologyProcedureVitrificationData::getDetailsByTreatmentId($_GET["treatmentId"]);
    foreach ($vitrificationEmbryos as $embryo){
        $rodColor = (($embryo->rod_color != "") ? ''.$embryo->rod_color : '#FFFFFF');
        $deviceColor = (($embryo->device_color != "") ? ''.$embryo->device_color : '#FFFFFF');
        if($embryo->date){
            $date = new DateTime($embryo->date);
            $dateFormat = $date->format("d/m/Y");
        }else $dateFormat = "";

        echo '<tr><td>'.$embryo->getPatientOvule()->procedure_code.'</td>';
        echo '<td data-section-detail-id="'.$embryo->id.'" data-column-name="date" data-patient-ovule-id="'.$embryo->patient_ovule_id.'" id="'.$embryo->patient_ovule_id . '-date" class="embryo-date">'.$dateFormat.'</td>';
        echo '<td data-section-detail-id="'.$embryo->id.'" data-column-name="stage" data-patient-ovule-id="'.$embryo->patient_ovule_id.'" id="'.$embryo->patient_ovule_id . '-stage">'.$embryo->stage.'</td>';
        echo '<td class="vitrification-destiny" data-section-detail-id="'.$embryo->id.'" data-column-name="destiny" data-patient-ovule-id="'.$embryo->patient_ovule_id.'" id="'.$embryo->patient_ovule_id . '-destiny">'.$embryo->destiny.'</td>';
        echo '<td data-section-detail-id="'.$embryo->id.'" data-column-name="rod" data-patient-ovule-id="'.$embryo->patient_ovule_id.'" id="'.$embryo->patient_ovule_id . '-rod">'.$embryo->rod.'</td>';
        echo '<td data-section-detail-id="'.$embryo->id.'" data-column-name="rod_color" data-patient-ovule-id="'.$embryo->patient_ovule_id.'" id="'.$embryo->patient_ovule_id . '-rod_color" style="background-color:'.$rodColor.'"></td>';
        echo '<td data-section-detail-id="'.$embryo->id.'" data-column-name="device_number" data-patient-ovule-id="'.$embryo->patient_ovule_id.'" id="'.$embryo->patient_ovule_id . '-device_number">'.$embryo->device_number.'</td>';
        echo '<td data-section-detail-id="'.$embryo->id.'" data-column-name="device_color" data-patient-ovule-id="'.$embryo->patient_ovule_id.'" id="'.$embryo->patient_ovule_id . '-device_color"  style="background-color:'.$deviceColor.'"></td>';
        echo '<td data-section-detail-id="'.$embryo->id.'" data-column-name="incidents" data-patient-ovule-id="'.$embryo->patient_ovule_id.'" id="'.$embryo->patient_ovule_id . '-incidents">'.$embryo->incidents.'</td>';
    echo '</tr>';
    }
    ?>
