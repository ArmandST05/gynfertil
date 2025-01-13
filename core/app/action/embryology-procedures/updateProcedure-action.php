<?php
if (count($_POST) > 0) {
    foreach ($_POST["details"] as $index => $detail) {
        $procedureDetail = EmbryologyProcedureData::getDetail($_POST["patientCategoryTreatmentId"], $index);

        //Actualizar registro existente
        if ($procedureDetail) {
            //Guardar imágenes a mostrar en reporte del paciente
            //En FIVTE(410),TEC(456),PGTA(452),REC(438),DESVIT(424),VITOVULO(461),EMBRIODON(481),ICSIBT(495),DONOVO(473)
            if ($index == 410 || $index == 456 || $index == 452 || $index == 438 || $index == 424 || $index == 461 || $index == 481 || $index == 495 || $index == 473) {
                $procedureDetail->value = generateStringImages($detail);
            } else {
                $procedureDetail->value = $detail;
            }
            //Actualizar existente
            $procedureDetail->updateDetail();

            if ($index == 2) {
                //Actualizar la fecha de donación de los óvulos recuperados en caso de que se actualice la fecha de aspiración folicular.
                PatientOvuleData::updateDonationDateByTreatmentId($_POST["patientCategoryTreatmentId"], $detail);
            }
        } else {
            //Crear nuevo registro
            $procedureDetail = new EmbryologyProcedureData();
            $procedureDetail->patient_category_treatment_id = $_POST["patientCategoryTreatmentId"];
            $procedureDetail->procedure_section_detail_id = $index;

            //Guardar imágenes a mostrar en reporte del paciente
            if ($index == 410 || $index == 456 || $index == 452 || $index == 438 || $index == 424 || $index == 461 || $index == 481 || $index == 495 || $index == 473) {
                $procedureDetail->value = generateStringImages($detail);
            } else {
                $procedureDetail->value = $detail;
            }
            //Actualizar existente
            $procedureDetail->addDetail();

            //En FIVTE (detail-10)la cantidad de óvulos recuperados colocada, es la cantidad de óvulos a utilizar.
            //En VITOVULO (detail-37)la cantidad de óvulos MII colocada, es la cantidad de óvulos a utilizar, sólo si no es un subprocedimiento (en los subprocedimientos se obtienen del procedimiento padra).
            //En PGTA (detail-91)la cantidad de óvulos recuperados colocada, es la cantidad de óvulos a utilizar.
            //En DONOVO (detail-110)la cantidad de óvulos recuperados colocada, es la cantidad de óvulos a utilizar.
            //En ICSIBT (detail-137)la cantidad de óvulos recuperados colocada, es la cantidad de óvulos a utilizar.
            //En MIXTO (detail-155)la cantidad de óvulos recuperados colocada, es la cantidad de óvulos a utilizar.

            //Se registran todos los óvulos recuperados y se asignan a la paciente para utilizarlos en el procedimiento.

            if ($index == 10 || ($index == 37 && $_POST["isParentProcedure"] == 0) || $index == 91 || $index == 110 || $index == 137 || $index == 155) {
                //Se obtiene el tratamiento para saber a qué paciente se le realiza
                $treatment = PatientCategoryData::getById($_POST["patientCategoryTreatmentId"]);

                $aspirationDetail = EmbryologyProcedureData::getDetail($_POST["patientCategoryTreatmentId"], 2);
                $aspirationDate = (isset($aspirationDetail)) ? $aspirationDetail->value : date("Y-m-d");

                //Cuando se asignan al procedimiento se coloca un número consecutivo de los óvulos utilizados en ese tipo de procedimiento de la paciente.
                $ovuleNumberData = PatientOvuleData::getTotalProcedureOvulesByPatient($_POST["patientCategoryTreatmentId"], $treatment->patient_id);
                $ovuleNumber = ($ovuleNumberData) ? floatval($ovuleNumberData->total) + 1 : 1;

                for ($i = 0; $i < $detail; $i++) {
                    $totalOvules = PatientOvuleData::getTotalByDonorId($treatment->patient_id);

                    $ovule = new PatientOvuleData();
                    $ovule->procedure_code = $ovuleNumber; //Es el número de óvulo que se muestra en la tabla de detalles.
                    $ovule->origin_patient_category_treatment_id = $_POST["patientCategoryTreatmentId"];
                    $ovule->donor_id = $treatment->patient_id;
                    $ovule->recipient_id = $treatment->patient_id;
                    $ovule->donation_date = $aspirationDate;
                    $ovule->ovule_status_id = 1;
                    $newOvule = $ovule->add();

                    $ovuleProcedure = new PatientOvuleData();
                    $ovuleProcedure->ovule_status_id = 1;
                    $ovuleProcedure->patient_category_treatment_id = $_POST["patientCategoryTreatmentId"];
                    $ovuleProcedure->patient_ovule_id = $newOvule[1];
                    $ovuleProcedure->section_id = 1;
                    $ovuleProcedure->addByProcedure();
                    $ovuleNumber++;
                }
            }
        }
    }
}

//Convierte el array de las imágenes a mostrar en reporte de paciente en string para guardarlo en BD
function generateStringImages($detail)
{
    $stringImages = implode(",",$detail);
    return $stringImages;
}
echo '<script>window.location="index.php?view=embryology-procedures/details&treatmentId=' . $_POST["patientCategoryTreatmentId"] . '"</script>';
