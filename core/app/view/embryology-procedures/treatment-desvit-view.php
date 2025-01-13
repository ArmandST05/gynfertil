<?php
/*-------------DESVIT (DESVITRIFICACIÓN DE ÓVULOS) (14)----------*/
$pgtaResultFile = EmbryologyProcedureData::getFileByTreatmentOvuleSectionId($_GET["treatmentId"], 0, 2);
?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Datos del Procedimiento</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=embryology-procedures/updateProcedure" role="form">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Ciclo:</label>
            <input type="text" class="form-control" placeholder="Ciclo" value="<?php echo $actualCycle ?>" readonly>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha de desvitrificación de óvulos:</label>
            <input type="date" id="dt-117" name="details[117]" class="form-control" placeholder="Fecha de desvitrificación de óvulos" value="<?php echo $procedureDetails['117'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Beta Hcg:</label>
            <input type="text" id="dt-118" name="details[118]" class="form-control" placeholder="Beta Hcg" value="<?php echo $procedureDetails['118'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Diagnóstico:</label>
            <input type="text" class="form-control" value="<?php echo $treatmentDiagnostics ?>" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Código de ciclo de vitrificación de óvulos:</label>
            <input type="text" id="dt-123" name="details[123]" class="form-control" placeholder="Código de ciclo de vitrificación de óvulos" value="<?php echo $procedureDetails['123'] ?>" readonly>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento femenino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[121]" value="1" <?php echo ($procedureDetails['121'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[121]" value="0" <?php echo ($procedureDetails['121'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento masculino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[122]" value="1" <?php echo ($procedureDetails['122'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[122]" value="0" <?php echo ($procedureDetails['122'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Semen:</label>
            <br>
            <button type="button" data-toggle="modal" data-target="#modalSearchAndrologyProcedure" class="btn btn-sm btn-default"><i class="fas fa-search"></i> Buscar semen</button>
            <div class="col-lg-12" id="divSelectedAndrologyProcedures">
              <?php foreach ($originAndrologyProcedures as $andrologyProcedure) :
                $patient = $andrologyProcedure->getPatient();
                $donorDetail = ($patient->donor_id != '') ? '( ' . $patient->donor_id . ')' : '';
              ?>
                <label id="semen<?php echo $andrologyProcedure->patient_procedure_id ?>" class="btn btn-simple btn-sm btn-default"><?php echo $andrologyProcedure->procedure_code . ' - ' . $patient->name . ' ' . $donorDetail . ' -' . $andrologyProcedure->quantity . ' dispositivos' ?></label>
                <a href="index.php?view=andrology-procedures/details&procedureId=<?php echo $andrologyProcedure->patient_procedure_id ?>" target="_blank" rel="tooltip" title="Visualizar" class="btn btn-simple btn-sm btn-default"><i class="fas fa-eye"></i></a>
                <button type="button" class="btn btn-sm btn-danger" onclick="deleteAndrologyProcedure(<?php echo $andrologyProcedure->id ?>)"><i class="fas fa-trash"></i></button>
                <br>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <!--<div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Id donante semen:</label>
            <input type="text" id="dt-120" name="details[120]" class="form-control" placeholder="Id donante semen" value="<?php echo $procedureDetails['120'] ?>" readonly>
          </div>
        </div>-->
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Óvulos descongelados:</label>
            <input type="number" min="0" id="dt-124" name="details[124]" class="form-control" placeholder="Óvulos descongelados" value="<?php echo $procedureDetails['124'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Degenerado:</label>
            <input type="number" min="0" id="dt-125" name="details[125]" class="form-control" placeholder="Degenerado" value="<?php echo $procedureDetails['125'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecundación-Ovocitos inseminados:</label>
            <input type="number" min="0" id="dt-126" name="details[126]" class="form-control" placeholder="Fecundación-Ovocitos inseminado" value="<?php echo $procedureDetails['126'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecundación - Fertilizados:</label>
            <input type="number" min="0" id="dt-127" name="details[127]" class="form-control" placeholder="Fecundación - Fertilizados" value="<?php echo $procedureDetails['127'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" id="patientCategoryTreatmentId" name="patientCategoryTreatmentId" value="<?php echo $_GET["treatmentId"] ?>" required>
        </div>
      </div>
    </form>
    <!--MODAL ELEGIR FIVTE O TRATAMIENTOS Y ÓVULOS A UTILIZAR-->
    <div class="modal fade" id="modalSearchTreatments">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Seleccionar óvulos/embriones</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label for="inputEmail1" class="control-label">Tratamientos:</label>
                <select id="selectTreatments" name="selectTreatments" class="form-control">
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label for="inputEmail1" class="control-label">Óvulos/Embriones:</label>
                <select id="selectOvules" name="selectOvules" class="form-control">
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="saveSelectedTreatments()">Agregar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--MODAL ELEGIR FIVTE O TRATAMIENTOS Y ÓVULOS A UTILIZAR-->

    <!--MODAL ELEGIR TRATAMIENTOS ANDROLOGÍA A UTILIZAR-->
    <div class="modal fade" id="modalSearchAndrologyProcedure">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Seleccionar procedimiento de andrología</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label for="inputEmail1" class="control-label">Procedimiento:</label>
                <select id="selectAndrologyProcedure" name="selectAndrologyProcedure" class="form-control">
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label for="inputEmail1" class="control-label">Cantidad de dispositivos:</label>
                <input type="number" min="0" id="quantityAndrologyProcedure" name="quantityAndrologyProcedure" class="form-control" placeholder="Cantidad de dispositivos">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="saveSelectedAndrologyProcedure()">Agregar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--MODAL ELEGIR TRATAMIENTOS ANDROLOGÍA A UTILIZAR-->
    <hr>
    <div class="row">
      <div class="col-lg-2 pull-right">
        <button type="button" data-toggle="modal" data-target="#modalSearchTreatments" class="btn btn-sm btn-default"><i class="fas fa-search"></i> Buscar óvulos/embriones</button>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table id="ovulesDataTable" class="cell-border compact" style="width:100%">
          <thead>
            <tr>
              <th rowspan="2"></th>
              <th rowspan="2">#</th>
              <?php foreach ($sections as $section) :
                $sectionDate = "";
                if (isset($procedureDetails['117']) && $procedureDetails['117'] != "0000-00-00" && $section->day_number != "" &&  $section->day_number >= 0) {
                  $sectionDate = date("d/m/Y", strtotime($procedureDetails['117'] . " +" . $section->day_number . " days"));
                }
              ?>
                <th colspan="<?php echo $section->total_section_details ?>"><?php echo $section->name . "<br>" . $sectionDate ?></th>
              <?php endforeach; ?>
              <th colspan="3">DESTINO</th>
              <th rowspan="2">MUESTRA SEMEN</th>
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sectionDetails as $sectionDetail) : ?>
                <th><?php echo $sectionDetail->name ?></th>
              <?php endforeach; ?>
              <th>TE</th>
              <th>C</th>
              <th>NV</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($procedureOvules as $ovule) :
              $ovuleSectionDetailValues = PatientOvuleData::getSectionDetailsByOvuleId($embryologyProcedure->patient_treatment_id, 1, $ovule->id);
              $image = $ovule->getImage();
              $destinationColor = ($ovule->end_ovule_status_id == "") ? "bg-danger" : ""; //Mostrar en rojo las celdas de destino si no se ha especificado ninguno
            ?>
              <tr class="origin-treatment" data-origin-treatment-code="<?php echo $ovule->getOriginPatientTreatment()->treatment_code; ?>" data-origin-treatment-patient-name="<?php echo $ovule->getOriginPatientTreatment()->getPatient()->name; ?>" data-procedure-ovule-id="<?php echo $ovule->id ?>" data-section-phase-id="<?php echo ($ovule->end_ovule_phase_id) ? $ovule->end_ovule_phase_id : $ovule->initial_ovule_phase_id ?>" data-procedure-ovule-code="<?php echo $ovule->procedure_code ?>">
                <td><button class="btn btn-sm btn-danger delete-ovule"><i class="fas fa-trash"></i></button></td>
                <td><?php echo $ovule->procedure_code ?></td>
                <?php foreach ($ovuleSectionDetailValues as $ovuleValue) : ?>
                  <td data-section-detail-id="<?php echo $ovuleValue->ovule_section_detail_id ?>" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-' . $ovuleValue->ovule_section_detail_id ?>">
                    <?php echo $ovuleValue->value ?>
                  </td>
                <?php endforeach; ?>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d3" data-section-detail-id="d3" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d3' ?>"><?php echo ($ovule->end_ovule_status_id == 3) ? "X" : "" ?></td>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d2" data-section-detail-id="d2" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d2' ?>"><?php echo ($ovule->end_ovule_status_id == 2) ? "X" : "" ?></td>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d4" data-section-detail-id="d4" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d4' ?>"><?php echo ($ovule->end_ovule_status_id == 4) ? "X" : "" ?></td>
                <td class="semen-used-<?php echo $ovule->id ?> fivte-semen-used" data-section-detail-id="semen-used" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-semen-used' ?>">
                  <?php
                  $semen = "";
                  if ($ovule->patient_andrology_procedure_id) {
                    $andrologyProcedureData = $ovule->getProcedureOvuleSemen();
                    $donorDetail = ($andrologyProcedureData->patient_donor_id != '') ? '( ' . $andrologyProcedureData->patient_donor_id . ')' : '';
                    $semenType = ($andrologyProcedureData->patient_id == $partner->id) ? "PAREJA" : "DONANTE " . $donorDetail;
                    $semen = "<b>" . $semenType . "</b><br>" . $andrologyProcedureData->procedure_code;
                  }
                  echo $semen ?>
                </td>
                <td class="image-<?php echo $ovule->id ?>" data-section-detail-id="image" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-image' ?>">
                  <?php if ($image) : ?>
                    <a href='<?php echo $image->path ?>' target='__blank' class="btn btn-default btn-sm"><i class='fas fa-image'></i></a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php
            endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th rowspan="2"></th>
              <th rowspan="2">#</th>
              <?php foreach ($sectionDetails as $sectionDetail) : ?>
                <th><?php echo $sectionDetail->name ?></th>
              <?php endforeach; ?>
              <th>TE</th>
              <th>C</th>
              <th>NV</th>
              <th rowspan="2">MUESTRA SEMEN</th>
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sections as $section) : ?>
                <th colspan="<?php echo $section->total_section_details ?>"><?php echo $section->name ?></th>
              <?php endforeach; ?>
              <th colspan="3">DESTINO</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="inputEmail1" class="control-label">No viables:</label>
          <input type="text" id="dt-128" name="details[128]" class="form-control" placeholder="No viables" value="0" readonly>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Reporte del Paciente</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=embryology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad A:</label>
            <input type="text" id="dt-412" name="details[412]" class="form-control" value="<?php echo $procedureDetails['412'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad B:</label>
            <input type="text" id="dt-413" name="details[413]" class="form-control" value="<?php echo $procedureDetails['413'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad C:</label>
            <input type="text" id="dt-414" name="details[414]" class="form-control" value="<?php echo $procedureDetails['414'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad D:</label>
            <input type="text" id="dt-415" name="details[415]" class="form-control" value="<?php echo $procedureDetails['415'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Blastocistos:</label>
            <input type="text" id="dt-416" name="details[416]" class="form-control" value="<?php echo $procedureDetails['416'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Calidad embrionaria:</label>
            <input type="text" id="dt-417" name="details[417]" class="form-control" value="<?php echo $procedureDetails['417'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Mórulas:</label>
            <input type="text" id="dt-418" name="details[418]" class="form-control" value="<?php echo $procedureDetails['418'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Detenidos:</label>
            <input type="text" id="dt-419" name="details[419]" class="form-control" value="<?php echo $procedureDetails['419'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Blastocistos:</label>
            <input type="text" id="dt-420" name="details[420]" class="form-control" value="<?php echo $procedureDetails['420'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Calidad embrionaria:</label>
            <input type="text" id="dt-421" name="details[421]" class="form-control" value="<?php echo $procedureDetails['421'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Mórulas:</label>
            <input type="text" id="dt-422" name="details[422]" class="form-control" value="<?php echo $procedureDetails['422'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7- Detenidos:</label>
            <input type="text" id="dt-423" name="details[423]" class="form-control" value="<?php echo $procedureDetails['423'] ?>">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Observaciones:</label>
            <input type="text" id="dt-425" name="details[425]" class="form-control" value="<?php echo $procedureDetails['425'] ?>">
          </div>
        </div>
        <div class="col-md-12">
          <div class="checkbox" id="imagesList">
            <?php
            $arraySelectedImages = explode(",", $procedureDetails['424']);
            foreach ($ovuleImages as $ovuleImage) :
            ?>
              <label id="lblImageList<?php echo $ovuleImage->procedure_ovule_id ?>">
                <input name="details[424][]" id="details[424]" type="checkbox" value="<?php echo $ovuleImage->procedure_ovule_id ?>" <?php echo (in_array($ovuleImage->procedure_ovule_id, $arraySelectedImages)) ? "checked" : "" ?>>
                <a href='<?php echo $ovuleImage->path ?>' target='_blank' class="btn btn-default btn-sm"><i class='fas fa-image'></i> <?php echo $ovuleImage->getProcedureOvule()->procedure_code ?></a>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" name="patientCategoryTreatmentId" value="<?php echo $_GET["treatmentId"] ?>" required>
        </div>
      </div>
    </form>
  </div>
  <!-- /.box-body -->
</div>
<div class="box box-primary" id="boxEmbryoTransfer">
  <div class="box-header with-border">
    <h3 class="box-title">Información Transferencia Embrionaria</h3>
    <div class="box-tools pull-right">
      <label id="totalEmbryoTransfer">Total: 0</label>
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body" id="bodyEmbryoTransfer">
    <div id="embryoTransferDetails">
      <form method="POST" action="index.php?action=embryology-procedures/updateTransferDetail" role="form">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Fecha de Transferencia:</label>
              <input type="date" id="embryoTransferDate" name="date" required class="form-control" placeholder="Fecha de Transferencia" value="<?php echo $transferDetail->date ?>">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Hora:</label>
              <div class="input-group">
                <input type="text" class="form-control timepicker" id="embryoTransferDateHour" name="hour" value="<?php echo $transferDetail->hour ?>">
                <div class="input-group-addon">
                  <i class="far fa-clock"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Id de Embrión Transferido:</label>
              <input type="text" id="embryoTransferIdDetails" name="embryoIdDetails" required class="form-control" placeholder="Id de Embrión Transferido" value="<?php echo $transferDetail->embryo_id_details ?>" autofocus>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Total Transferidos:</label>
              <input type="number" id="embryoTransferTotal" name="total" min="0" required class="form-control" placeholder="Total Transferidos" value="<?php echo $transferDetail->total ?>" autofocus>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Calidad:</label>
              <input type="text" id="embryoTransferQuality" name="quality" class="form-control" placeholder="Calidad" value="<?php echo $transferDetail->quality ?>" autofocus>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Ginecólogo:</label>
              <select id="embryoTransferGynecologist" name="gynecologist" class="form-control">
                <?php foreach ($medics as $medic) : ?>
                  <option value="<?php echo $medic->id; ?>" <?php echo ($transferDetail->gynecologist_id == $medic->id) ? "selected" : "" ?>><?php echo $medic->id . " - " . $medic->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Ecografista:</label>
              <select id="embryoTransferSonographer" name="sonographer" class="form-control">
                <?php foreach ($medics as $medic) : ?>
                  <option value="<?php echo $medic->id; ?>" <?php echo ($transferDetail->sonographer_id == $medic->id) ? "selected" : "" ?>><?php echo $medic->id . " - " . $medic->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Embriólogo:</label>
              <select id="embryoTransferEmbryologist" name="embryologist" class="form-control">
                <?php foreach ($medics as $medic) : ?>
                  <option value="<?php echo $medic->id; ?>" <?php echo ($transferDetail->embryologist_id == $medic->id) ? "selected" : "" ?>><?php echo $medic->id . " - " . $medic->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Testigo:</label>
              <select id="embryoTransferWitness" name="witness" id="witness" class="form-control">
                <?php foreach ($medics as $medic) : ?>
                  <option value="<?php echo $medic->id; ?>" <?php echo ($transferDetail->witness_id == $medic->id) ? "selected" : "" ?>><?php echo $medic->id . " - " . $medic->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Transferencia de E. - Estradiol:</label>
              <input type="text" id="embryoTransferEstradiol" name="estradiol" required class="form-control" placeholder="Estradiol" value="<?php echo $transferDetail->estradiol ?>" autofocus>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Cánula:</label>
              <input type="text" id="embryoTransferCatheter" name="catheter" required class="form-control" placeholder="Cánula" value="<?php echo $transferDetail->catheter ?>" autofocus>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Lote:</label>
              <input type="text" id="embryoTransferCatheterLot" name="catheterLot" required class="form-control" placeholder="Lote" value="<?php echo $transferDetail->catheter_lot ?>" autofocus>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Caducidad:</label>
              <input type="text" id="embryoTransferCatheterExpiration" name="catheterExpiration" required class="form-control" placeholder="Caducidad" value="<?php echo $transferDetail->catheter_expiration ?>" autofocus>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Transferencia de E. - Progesterona:</label>
              <input type="text" id="embryoTransferProgesterone" name="progesterone" required class="form-control" placeholder="Progesterona" value="<?php echo $transferDetail->progesterone ?>" autofocus>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Jeringa :</label>
              <input type="text" id="embryoTransferSyringe" name="syringe" required class="form-control" placeholder="Jeringa " value="<?php echo $transferDetail->syringe ?>" autofocus>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Lote:</label>
              <input type="text" id="embryoTransferSyringeLot" name="syringeLot" required class="form-control" placeholder="Lote" value="<?php echo $transferDetail->syringe_lot ?>" autofocus>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Caducidad:</label>
              <input type="text" id="embryoTransferSyringeExpiration" name="syringeExpiration" required class="form-control" placeholder="Caducidad" value="<?php echo $transferDetail->syringe_expiration ?>" autofocus>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="inputEmail1" class="control-label">Observaciones:</label>
              <textarea id="embryoTransferObservations" name="observations" class="form-control"><?php echo $transferDetail->observations ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-1 pull-right">
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            <input type="hidden" name="patientCategoryTreatmentId" value="<?php echo $_GET["treatmentId"] ?>" required>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<div class="box box-primary" id="boxEmbryoVitrification">
  <div class="box-header with-border">
    <h3 class="box-title">Información vitrificación y ubicación de embriones</h3>
    <div class="box-tools pull-right"><label id="totalEmbryoVitrification">Total: 0</label></div>
  </div>
  <!-- /.box-header -->
  <div class="box-body" id="bodyEmbryoVitrification">
    <form method="POST" action="index.php?action=embryology-procedures/updateVitrificationDetail" role="form">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha vitrificación:</label>
            <input type="text" id="embryoVitrificationDate" name="date" value="<?php echo $embryoVitrificationDetail->date ?>" class="form-control" placeholder="Fecha vitrificación" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Código vitrificación:</label>
            <div class="input-group">
              <input type="text" class="form-control" id="embryoVitrificationCode" name="code" value="<?php echo $embryoVitrificationDetail->code ?>" readonly>
              <div class="input-group-btn">
                <?php if ($embryoVitrificationDetail->code == "") : ?>
                  <button type="button" class="btn btn-default" id="btnAddEmbryoVitrificacionCode" onclick="addEmbryoVitrificationCode()"><i class="fas fa-plus"></i></button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Varilla:</label>
            <input type="text" id="embryoVitrificationRod" name="rod" value="<?php echo $embryoVitrificationDetail->rod ?>" required class="form-control" placeholder="Varilla" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Color de varilla:</label>
            <input type="color" id="embryoVitrificationRodColor" name="rodColor" value="<?php echo $embryoVitrificationDetail->rod_color ?>" required class="form-control" placeholder="Color de varilla" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Número de dispositivo:</label>
            <input type="text" id="embryoVitrificationDeviceNumber" name="deviceNumber" value="<?php echo $embryoVitrificationDetail->device_number ?>" required class="form-control" placeholder="Número de dispositivo" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Color del dispositivo:</label>
            <input type="color" id="embryoVitrificationDeviceColor" name="deviceColor" value="<?php echo $embryoVitrificationDetail->device_color ?>" required class="form-control" placeholder="Color del dispositivo" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cesta:</label>
            <input type="text" id="embryoVitrificationBasket" name="basket" value="<?php echo $embryoVitrificationDetail->basket ?>" required class="form-control" placeholder="Cesta" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Tanque:</label>
            <input type="text" id="embryoVitrificationTank" name="tank" value="<?php echo $embryoVitrificationDetail->tank ?>" required class="form-control" placeholder="Tanque" autofocus>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" name="patientCategoryTreatmentId" value="<?php echo $_GET["treatmentId"] ?>" required>
          <input type="hidden" name="vitrificationTypeId" value="2" required>
        </div>
      </div>
    </form>
    <div class="row">
      <div class="col-md-12">
        <table id="embryoVitrificationDataTable" class="cell-border compact" style="width:100%">
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
          <tbody>
            <!-- Los datos se cargan por una petición ajax-->
          </tbody>
          <tfoot>
            <tr>
              <th rowspan="2">#</th>
              <th>FECHA</th>
              <th>ESTADÍO</th>
              <th>DESTINO</th>
              <th>NO. DISPOSITIVO</th>
              <th>COLOR DISPOSITIVO</th>
              <th>VARILLA</th>
              <th>COLOR VARILLA</th>
              <th>INCIDENCIAS</th>
            </tr>
            <tr>
              <th colspan="8"></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Resultado de PGTA</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body" id="bodyPgtaResult">
    <button type="submit" class="btn btn-default btn-sm" id="btnUploadPgtaResult" onclick="uploadResultFile()" <?php echo ($pgtaResultFile) ? "style='display: none';" : ""; ?>>Subir archivo <i class="fas fa-upload"></i></button>
    <?php if (isset($pgtaResultFile)) : ?>
      <a href="<?php echo $pgtaResultFile->path; ?>" target="_blank" rel="tooltip" title="Visualizar" class="btn btn-sm btn-primary pgta-result">RESULTADO PGTA <i class="fas fa-eye"></i></a><button class="btn btn-sm btn-danger pgta-result" onclick="deleteResultFile()"><i class="fas fa-trash"></i></button>
    <?php endif; ?>
  </div>
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Observaciones</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=embryology-procedures/updateProcedureObservations" role="form">
      <div class="col-md-12">
        <div class="row">
          <textarea name="observations" class="form-control" rows="10"><?php echo $embryologyProcedure->embryology_procedure_observations ?></textarea>
        </div>
        <div class="row">
          <div class="col-lg-1 pull-right">
            <br>
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            <input type="hidden" id="patientCategoryTreatmentId" name="patientCategoryTreatmentId" value="<?php echo $_GET["treatmentId"] ?>" required>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- /.box-body -->
</div>

<script>
  $(document).ready(function() {
    //Selects detalles de transferencia embrionaria
    $("#embryoTransferGynecologist").select2({});
    $("#embryoTransferSonographer").select2({});
    $("#embryoTransferEmbryologist").select2({});
    $("#embryoTransferWitness").select2({});

    $('#modalSearchAndrologyProcedure').modal({
      keyboard: false,
      backdrop: false,
      show: true
    });

    //Select búsqueda de procedimientos andrología - Semen
    $('#selectAndrologyProcedure').select2({
      placeholder: "Escribe el código del procedimiento",
      minimumInputLength: 3,
      multiple: false,
      ajax: {
        url: "./?action=andrology-procedures/get-search-by-code-name", // json datasource
        type: 'GET',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: data
          }
        }
      }
    });

    $('#modalSearchAndrologyProcedure').modal("hide"); //Ocultar hasta este momento para que el select se muestre completo

    $('#modalSearchTreatments').modal({
      keyboard: false,
      backdrop: false,
      show: true
    });

    //Select búsqueda de tratamientos para seleccionar posteriormente los embriones
    $('#selectTreatments').select2({
      placeholder: "Escribe el código del tratamiento",
      minimumInputLength: 3,
      multiple: false,
      ajax: {
        url: "./?action=embryology-procedures/getSearchByCodeName", // json datasource
        type: 'GET',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: data
          }
        }
      }
    });

    //Select búsqueda de embriones para añadirlos a la tabla de detalles
    var selectOvules = $('#selectOvules').select2({
      placeholder: "Escribe el código del embrión",
      multiple: true,
      ajax: {
        url: "./?action=embryology-procedures/get-treatment-ovules", // json datasource
        type: 'GET',
        dataType: 'json',
        delay: 250,
        processResults: function(data) {
          return {
            results: data
          }
        }
      }
    });

    $('#modalSearchTreatments').modal("hide"); //Ocultar hasta este momento para que el select se muestre completo

    //Datatable detalle de óvulos/embriones
    var ovulesDataTable = $('#ovulesDataTable').DataTable({
      "ordering": false,
      "searching": false,
      "paging": false,
      "info": false,
      "scrollX": true,
      "scrollY": "550px",
      "scrollCollapse": true,
      "fixedColumns": {
        left: 2
      },
      "fixedHeader": {
        "header": true,
        "footer": true
      },
      language: {
        url: 'plugins/datatables/languages/es-mx.json'
      }
    });

    //Activar el modal de edición al hacer clic en una celda de detalle de óvulos general
    //:not(:nth-child(2))
    $('#ovulesDataTable').on('click', 'tbody td:not(:first-child)', function(e) {
      var sectionDetailId = $(this).attr("data-section-detail-id");
      var procedureOvuleId = $(this).attr("data-procedure-ovule-id");
      var trParent = $(this).closest('tr');
      var treatmentId = "<?php echo $embryologyProcedureId ?>";

      if (sectionDetailId == "image") {
        let filePath = $(this).find('a').prop("href");
        let procedureOvuleCode = trParent.attr("data-procedure-ovule-code");

        //Imagen
        Swal.fire({
          title: '#' + procedureOvuleCode,
          imageUrl: filePath,
          imageWidth: 400,
          imageHeight: 200,
          imageAlt: 'Imagen óvulo/embrión',
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: 'Actualizar Imagen',
          denyButtonText: 'Eliminar',
          cancelButtonText: `Cancelar`,
          confirmButtonColor: '#3085d6',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            //Subir Imagen
            Swal.fire({
              title: 'Imagen',
              input: 'file',
              inputAttributes: {
                'accept': 'image/*',
                'aria-label': 'Selecciona la imagen'
              },
              onBeforeOpen: () => {
                $(".swal2-file").change(function() {
                  var reader = new FileReader();
                  reader.readAsDataURL(this.files[0]);
                });
              },
              showCancelButton: true,
              confirmButtonText: 'Guardar',
              cancelButtonText: 'Cancelar',
              showLoaderOnConfirm: true,
              inputValidator: (value) => {
                if (!value) {
                  return '¡Selecciona un archivo!'
                }
              },
              preConfirm: (value) => {
                var formData = new FormData();
                formData.append('treatmentId', treatmentId);
                formData.append('procedureOvuleId', procedureOvuleId);
                formData.append('imageSectionId', 1);

                var file = $('.swal2-file')[0].files[0];
                formData.append("file", file);

                $.ajax({
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  type: "POST",
                  url: "./?action=embryology-procedures/addFile",
                  contentType: false,
                  cache: false,
                  processData: false,
                  data: formData,
                  error: function() {
                    Swal.fire(
                      '¡Oops!',
                      'La imagen no se ha podido guardar.',
                      'error'
                    )
                  },
                  success: function(data) {
                    $(".image-" + procedureOvuleId).text("");
                    let htmlImage = "<a href='" + data + "' target='_blank' class='btn btn-default btn-sm'><i class='fas fa-image'></i></a>";
                    $("#" + procedureOvuleId + "-" + sectionDetailId).html(htmlImage);

                    //Mostrar imagen en la lista de imágenes para seleccionar y que se muestren en el reporte del paciente
                    $("#lblImageList" + procedureOvuleId).remove();
                    let htmlImageList = '<label id="lblImageList' + procedureOvuleId + '"><input name="details[424][]" id="details[424]" type="checkbox" value="' + procedureOvuleId + '"><a href="' + data + '" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-image"></i>' + procedureOvuleCode + '</a></label>';
                    $("#imagesList").append(htmlImageList);
                  }
                });
              },
              allowOutsideClick: () => !Swal.isLoading()
            });
          } else if (result.isDenied) {
            Swal.fire({
              title: '¿Deseas eliminar esta imagen #' + procedureOvuleCode + '?',
              text: "Esta acción no se podrá revertir.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Aceptar',
              cancelButtonText: 'Cancelar'
            }).then((result) => {
              if (result.value == true) {
                $.ajax({
                  type: "POST",
                  url: "./?action=embryology-procedures/deleteFile",
                  data: {
                    treatmentId: treatmentId,
                    procedureOvuleId: procedureOvuleId,
                    imageSectionId: 1
                  },
                  success: function(data) {
                    $(".image-" + procedureOvuleId).text("");
                    $("#" + procedureOvuleId + "-" + sectionDetailId).html("");

                    //Mostrar imagen en la lista de imágenes para seleccionar y que se muestren en el reporte del paciente
                    $("#lblImageList" + procedureOvuleId).remove();
                  },
                  error: function() {
                    Swal.fire(
                      'Error',
                      'Ocurrió un error al eliminar..',
                      'error'
                    );
                  }
                });
              }
            });
          }
        });
      } else if (sectionDetailId == "265") {
        //Marcar como PGTA
        var phaseId = trParent.attr("data-section-phase-id");
        let options = {};
        options['X'] = "PGTA";
        options[''] = "No PGTA";
        Swal.fire({
          title: 'Destino',
          input: 'radio',
          inputValue: "",
          inputOptions: options,
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: false,
          preConfirm: (value) => {
            $.ajax({
              type: "POST",
              url: "./?action=embryology-procedures/updateSectionDetailOvule",
              data: {
                sectionDetailId: sectionDetailId,
                procedureOvuleId: procedureOvuleId,
                value: value.trim()
              },
              error: function() {
                Swal.fire(
                  '¡Oops!',
                  'No se ha podido guardar el destino.',
                  'error'
                )
              },
              success: function(data) {
                let id = procedureOvuleId + "-" + sectionDetailId;
                $("#" + id).text(value.trim());
                ovulesDataTable.columns.adjust();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      } else if (sectionDetailId == "d2" || sectionDetailId == "d3" || sectionDetailId == "d4" || sectionDetailId == "d5") {
        //Destinos
        var phaseId = trParent.attr("data-section-phase-id");
        let options = {};
        /*El segundo parámetro es la fase (1) Óvulo (2) Embriones */
        if (sectionDetailId == "d2") {
          //Vitrificación de Embriones u Óvulos
          options['2-1'] = "Óvulo - C (Vitrificación)";
          options['2-2'] = "Embrión - C (Vitrificación)";
        } else if (sectionDetailId == "d3") {
          options['3-2'] = "TE (Transferencia de embriones)";
        } else if (sectionDetailId == "d4") {
          options['4-1'] = "Óvulo - NV (No viable)";
          options['4-2'] = "Embrión - NV (No viable)";
        } else if (sectionDetailId == "d5") {
          options['5-2'] = "PGTA";
        }
        //DESTINO (TE-3, C-2, NV-4,PGTA->DESPUÉS CONGELADO (5))
        let defaultValue = sectionDetailId.substr(1, 2) + "-" + phaseId;
        Swal.fire({
          title: 'Destino',
          input: 'radio',
          inputValue: "",
          inputOptions: options,
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: false,
          onBeforeOpen: () => {
            $('input:radio[name="swal2-radio"]').filter('[value="' + defaultValue + '"]').attr('checked', true);
          },
          inputValidator: (value) => {
            if (!value) {
              return '¡Selecciona un destino!'
            }
          },
          preConfirm: (value) => {
            $.ajax({
              type: "POST",
              url: "./?action=embryology-procedures/updateStatusProcedureOvule",
              data: {
                treatmentId: "<?php echo $embryologyProcedureId ?>",
                procedureOvuleId: procedureOvuleId,
                status: value
              },
              error: function() {
                Swal.fire(
                  '¡Oops!',
                  'No se ha podido guardar el destino.',
                  'error'
                )
              },
              success: function(data) {
                let selectedStatus = value.substr(0, 1);
                let selectedPhase = value.substr(2, 1);

                $(".destination-" + procedureOvuleId).text("");
                $("#" + procedureOvuleId + "-d" + selectedStatus).text("X");
                trParent.attr("data-section-phase-id", selectedPhase);
                ovulesDataTable.columns.adjust();

                //Al actualizar el destino del óvulo cambiar el color de fondo de las celdas
                $(".destination-" + procedureOvuleId).removeClass("bg-danger");

                calculateTotalOvulesData();
                fillEmbryoVitrificationDataTable();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });

      } else if (sectionDetailId == "semen-used") { //Semen used
        //MUESTRA DE SEMEN UTILIZADA
        let options = {};
        let selectedValue = 0;
        let selectedIndex = 0;

        let originAndrologyProcedures = <?php echo json_encode($originAndrologyProcedures); ?>;
        $.each(originAndrologyProcedures, function(index, andrologyProcedure) {
          let donorDetail = (andrologyProcedure["patient_donor_id"]) ? '(' + andrologyProcedure["patient_donor_id"] + ')' : '';
          let semenType = (andrologyProcedure["patient_id"] == "<?php echo $partner->id ?>") ? "PAREJA" : "DONANTE " + donorDetail;
          let semen = "<b>" + semenType + "</b><br>" + andrologyProcedure["procedure_code"] + '<br>' + andrologyProcedure["patient_name"];
          options[index + "-" + andrologyProcedure["andrology_procedure_id"]] = semen;
        });
        options['0'] = "NINGUNO"; //Primero index del elemento-> después andrology_procedure_id que es lo que se guardará en la base de datos

        let defaultValue = "";
        Swal.fire({
          width: "50%",
          title: 'Muestra de semen',
          input: 'radio',
          inputValue: "",
          inputOptions: options,
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: false,
          onBeforeOpen: () => {
            $('input:radio[name="swal2-radio"]').filter('[value="' + defaultValue + '"]').attr('checked', true);
          },
          inputValidator: (value) => {
            if (!value) {
              return '¡Selecciona una muestra de semen!'
            }
          },
          preConfirm: (value) => {
            if (value != "0") { //Si no se seleccionó ninguno, buscar el índice seleccionado y el valor a guardar
              let selectedArray = value.split("-");
              selectedIndex = selectedArray[0];
              selectedValue = selectedArray[1];
            }
            sectionDetailId.substr(1, 2) + "-" + phaseId;
            $.ajax({
              type: "POST",
              url: "./?action=embryology-procedures/update-ovule-andrology-procedure",
              data: {
                andrologyProcedureId: selectedValue,
                procedureOvuleId: procedureOvuleId
              },
              error: function() {
                Swal.fire(
                  '¡Oops!',
                  'No se ha podido guardar la muestra de semen utilizada.',
                  'error'
                )
              },
              success: function(data) {
                //Actualizar la celda de la tabla con la información seleccionada
                let semen = "";
                if (value != "0") {
                  let selectedAndrologyProcedure = originAndrologyProcedures[selectedIndex];
                  let donorDetail = (selectedAndrologyProcedure["patient_donor_id"]) ? '(' + selectedAndrologyProcedure["patient_donor_id"] + ')' : '';
                  let semenType = (selectedAndrologyProcedure["patient_id"] == "<?php echo $partner->id ?>") ? "PAREJA" : "DONANTE " + donorDetail;
                  semen = "<b>" + semenType + "</b><br>" + selectedAndrologyProcedure["procedure_code"];
                }
                $("#" + procedureOvuleId + "-semen-used").html(semen);
                ovulesDataTable.columns.adjust();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });

      } else {
        Swal.fire({
          title: 'Editar',
          input: 'text',
          inputValue: $(this).text().trim(),
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
          inputValidator: (value) => {
            if (value.length > 200) {
              return '¡Tu texto no puede superar los 200 caracteres!'
            }
          },
          preConfirm: (value) => {
            $.ajax({
              type: "POST",
              url: "./?action=embryology-procedures/updateSectionDetailOvule",
              data: {
                sectionDetailId: sectionDetailId,
                procedureOvuleId: procedureOvuleId,
                value: value.trim()
              },
              error: function() {
                Swal.fire(
                  'Error',
                  'No se pudieron actualizar los datos..',
                  'error'
                )
              },
              success: function(data) {
                let id = procedureOvuleId + "-" + sectionDetailId;
                $("#" + id).text(value.trim());
                ovulesDataTable.columns.adjust();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      }
    });

    //Eliminar filas de detalle de óvulos
    $('#ovulesDataTable tbody').on('click', '.delete-ovule', function() {
      var procedureOvuleId = $(this).parents('tr').attr('data-procedure-ovule-id');
      var row = ovulesDataTable.row($(this).parents('tr'));

      Swal.fire({
        title: '¿Deseas eliminar este embrión del procedimiento?',
        text: "Esta acción no se podrá revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value == true) {
          $.ajax({
            type: "POST",
            url: "./?action=embryology-procedures/deleteOvuleByTreatment",
            data: {
              id: procedureOvuleId
            },
            success: function(data) {
              row.remove().draw();
              fillEmbryoVitrificationDataTable();
            },
            error: function() {
              Swal.fire(
                'Error',
                'Ocurrió un error al eliminar..',
                'error'
              );
            }
          });
        }
      });
    });

    //DATATABLE DETALLE DE EMBRIONES VITRIFICADOS
    var embryoVitrificationDataTable = $('#embryoVitrificationDataTable').DataTable({
      "ordering": false,
      "searching": false,
      "paging": false,
      "info": false,
      "language": {
        "url": 'plugins/datatables/languages/es-mx.json'
      }
    });

    //Activar el modal de edición al hacer clic en una celda de vitrificación de embriones
    //:not(:nth-child(2))
    $('#embryoVitrificationDataTable').on('click', 'tbody td:not(:first-child)', function(e) {
      var sectionDetailId = $(this).attr("data-section-detail-id");
      var columnName = $(this).attr("data-column-name");
      var patientOvuleId = $(this).attr("data-patient-ovule-id");
      if (columnName == "rod_color" || columnName == "device_color") {
        //Colores
        let actualColor = $("#" + patientOvuleId + "-" + columnName).css("background-color");
        actualColor = rgb2hex(actualColor).toUpperCase();
        Swal.fire({
          title: 'Editar',
          html: '<input type="color" class="form-control" id="vitColor-' + patientOvuleId + '-' + columnName + '" value="' + actualColor + '">',
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            let value = $("#vitColor-" + patientOvuleId + "-" + columnName).val().toUpperCase();
            $.ajax({
              type: "POST",
              url: "./?action=embryology-procedures/updateVitrificationDetailOvule",
              data: {
                sectionDetailId: sectionDetailId,
                columnName: columnName,
                value: value
              },
              error: function() {
                Swal.fire(
                  'Error',
                  'No se pudieron actualizar los datos..',
                  'error'
                )
              },
              success: function(data) {
                $("#" + patientOvuleId + "-" + columnName).css("background-color", value);
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      } else if (columnName == "date") {
        //Fecha
        let actualDate = $("#" + patientOvuleId + "-" + columnName).text();
        Swal.fire({
          title: 'Editar',
          html: '<input type="date" class="form-control" id="date-' + patientOvuleId + '-' + columnName + '" value="' + actualDate + '">',
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            let value = $("#date-" + patientOvuleId + "-" + columnName).val();
            $.ajax({
              type: "POST",
              url: "./?action=embryology-procedures/updateVitrificationDetailOvule",
              data: {
                sectionDetailId: sectionDetailId,
                columnName: columnName,
                value: value
              },
              error: function() {
                Swal.fire(
                  'Error',
                  'No se pudieron actualizar los datos..',
                  'error'
                )
              },
              success: function(data) {
                //Formatear fecha dd/mm/YYYY
                if (value != "") value = getFormattedDate(value);
                $("#" + patientOvuleId + "-" + columnName).text(value);
                showEmbryoVitrificationDates();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      } else {
        Swal.fire({
          title: 'Editar',
          input: 'text',
          inputValue: $(this).text().trim(),
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
          inputValidator: (value) => {
            if (value.length > 200) {
              return '¡Tu texto no puede superar los 200 caracteres!'
            }
          },
          preConfirm: (value) => {
            $.ajax({
              type: "POST",
              url: "./?action=embryology-procedures/updateVitrificationDetailOvule",
              data: {
                sectionDetailId: sectionDetailId,
                columnName: columnName,
                value: value.trim()
              },
              error: function() {
                Swal.fire(
                  'Error',
                  'No se pudieron actualizar los datos..',
                  'error'
                )
              },
              success: function(data) {
                let id = patientOvuleId + "-" + columnName;
                $("#" + id).text(value.trim());
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      }
    });

    $("#embryoTransferDateHour").timepicker({});

    //Al cargar la página mostrar totales
    calculateTotalOvulesData();
    //Al cargar la página mostrar detalle de embriones congelados/vitrificados
    fillEmbryoVitrificationDataTable();
    //Al cargar mostrar los tratamientos de donde se obtienen los embriones.
    showOvuleOriginTreatments();
  });

  //Calcular el total de óvulos por cada destino.
  function calculateTotalOvulesData() {
    //No viables
    var totalNonValidate = 0;
    $(".d4").each(function(td) {
      if ($(this).text() == "X") {
        totalNonValidate++;
      }
    });
    $("#dt-128").val(totalNonValidate);

    //Transferencia de embriones
    var totalEmbryoTransfer = 0;
    $(".d3").each(function(td) {
      var trParent = $(this).closest('tr');
      if ($(this).text() == "X" && trParent.attr("data-section-phase-id") == 2) {
        totalEmbryoTransfer++;
      }
    });
    $("#totalEmbryoTransfer").text("Total: " + totalEmbryoTransfer);
    if (totalEmbryoTransfer > 0) $("#boxEmbryoTransfer").show();
    else $("#boxEmbryoTransfer").hide();

    //Vitrificación de embriones
    var totalEmbryoVitrification = 0;
    $(".d2").each(function(td) {
      var trParent = $(this).closest('tr');
      if ($(this).text() == "X" && trParent.attr("data-section-phase-id") == 2) {
        totalEmbryoVitrification++;
      }
    });
    $("#totalEmbryoVitrification").text("Total: " + totalEmbryoVitrification);
    if (totalEmbryoVitrification > 0) $("#boxEmbryoVitrification").show();
    else $("#boxEmbryoVitrification").hide();
  }

  //Mostrar el resumen de los códigos de procedencia de embriones
  function showOvuleOriginTreatments() {
    var arrayTreatments = [];
    $(".origin-treatment").each(function(td) {
      arrayTreatments.push($(this).attr("data-origin-treatment-code"));
    });
    //Sólo claves de tratamientos únicos
    var treatments = arrayTreatments.filter(function(item, i, arrayTreatments) {
      return i == arrayTreatments.indexOf(item);
    });
    //Mostrar en input ordenados
    treatments.sort();
    $("#dt-123").val(treatments.toString()); //Cambiar
  }

  //Mostrar el resumen de las fechas de vitrificación de embriones colocadas en la tabla de los detalles
  function showEmbryoVitrificationDates() {
    var arrayDates = [];
    $(".embryo-date").each(function(td) {
      if ($(this).text() != "") {
        arrayDates.push($(this).text());
      }
    });
    //Sólo fechas únicas
    var dates = arrayDates.filter(function(item, i, arrayDates) {
      return i == arrayDates.indexOf(item);
    });
    //Mostrar en input ordenadas
    dates.sort();
    $("#embryoVitrificationDate").val(dates.toString());
  }

  //Crear código de vitrificación de embriones VITEMBRIO
  function addEmbryoVitrificationCode() {
    Swal.fire({
      title: '¿Deseas generar un código VITEMBRIO?',
      text: "Esta acción no se podrá revertir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value == true) {
        $.ajax({
          url: "./?action=embryology-procedures/addProcedureVitrificationCode",
          type: "POST",
          data: {
            patientCategoryTreatmentId: $("#patientCategoryTreatmentId").val(),
            vitrificationTypeId: 2
          },
          success: function(data) {
            var vitrificationCodeData = JSON.parse(data);
            $("#embryoVitrificationCode").val(vitrificationCodeData["code"]);
            $("#btnAddEmbryoVitrificacionCode").hide();
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Ha ocurrido un error al generar el código de vitrificación, recarga la página.'
            });
          }
        });
      }
    })
  }

  //Obtener datos de tabla de vitrificación de embriones
  function fillEmbryoVitrificationDataTable() {
    $.ajax({
      type: "GET",
      url: "./?action=embryology-procedures/getEmbryoVitrificationDetail",
      data: {
        treatmentId: "<?php echo $embryologyProcedureId ?>"
      },
      success: function(data) {
        $("#embryoVitrificationDataTable tbody tr").remove()
        $("#embryoVitrificationDataTable").append(data);
      },
      complete: function() {
        showEmbryoVitrificationDates(); //Cargar fecha de vitrificación en base a tabla de datalles
      }
    });
  }

  /*---------------SELECCIONAR EMBRIONES PARA TABLA PRINCIPAL DETALLES--------------------- */
  //Evento al seleccionar tratamiento en select de búsqueda
  $('#selectTreatments').on('select2:select', function(e) {
    getOvulesSelectTreatment();
    $("#selectOvules").select2();
  });

  $("#selectTreatments").on('select2:unselect', function(e) {
    $("#selectOvules").select2();
    getOvulesSelectTreatment();
  });

  //Obtiene los óvulos/embriones dependiendo del tratamiento seleccionado
  function getOvulesSelectTreatment() {
    $.ajax({
      type: "GET",
      url: "./?action=embryology-procedures/get-treatment-ovules",
      data: {
        treatmentId: $('#selectTreatments').val(),
        endPhaseId: "0" //Embriones y óvulos
      },
      success: function(data) {
        var ovules = JSON.parse(data);
        $.each(ovules, function(index, ovule) {
          var option = new Option(ovule.patient_procedure_code, ovule.id, false, false);
          selectOvules.append(option);
        });
      }
    });
  }
  //Guardar datos de embriones seleccionados en las tablas
  function saveSelectedTreatments() {
    var selectTreatmentsData = $('#selectTreatments').select2('data')
    var originTreatmentName = selectTreatmentsData[0].text;
    var ovules = $("#selectOvules").val();

    if (originTreatmentName != "" && ovules != null) {
      $.ajax({
        type: "POST",
        url: "./?action=embryology-procedures/addOvulesByTreatment",
        data: {
          treatmentId: "<?php echo $embryologyProcedureId ?>",
          originTreatmentName: originTreatmentName,
          ovules: ovules,
        },
        success: function(data) {
          window.location.reload();
          showOvuleOriginTreatments();
        },
        complete: function() {
          $('#modalSearchTreatments').modal('hide');
          $("#selectTreatments").select2();
          $("#selectOvules").select2();
        }
      });
    } else {
      Swal.fire(
        '¡Oops!',
        'Ingresa todos los datos o haz clic en Cancelar.',
        'error'
      );
    }
  }

  //Guardar archivo de resultados
  function uploadResultFile() {
    Swal.fire({
      title: 'Archivo',
      input: 'file',
      inputAttributes: {
        'accept': 'application/pdf',
        'aria-label': 'Selecciona el archivo'
      },
      onBeforeOpen: () => {
        $(".swal2-file").change(function() {
          var reader = new FileReader();
          reader.readAsDataURL(this.files[0]);
        });
      },

      showCancelButton: true,
      confirmButtonText: 'Guardar',
      cancelButtonText: 'Cancelar',
      showLoaderOnConfirm: true,
      inputValidator: (value) => {
        if (!value) {
          return '¡Selecciona un archivo!'
        }
      },
      preConfirm: (value) => {
        var formData = new FormData();
        formData.append('treatmentId', "<?php echo $embryologyProcedureId ?>");
        formData.append('procedureOvuleId', 0);
        formData.append('nameFile', 'pgta-result');
        formData.append('imageSectionId', 2);

        var file = $('.swal2-file')[0].files[0];
        formData.append("file", file);

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "POST",
          url: "./?action=embryology-procedures/addFile",
          contentType: false,
          cache: false,
          processData: false,
          data: formData,
          error: function() {
            Swal.fire(
              '¡Oops!',
              'La imagen no se ha podido guardar.',
              'error'
            )
          },
          success: function(data) {
            let htmlFile = '<a href = "' + data + '" target = "_blank" rel = "tooltip" title = "Visualizar" class = "btn btn-sm btn-primary pgta-result"> RESULTADO PGTA <i class="fas fa-eye"> </i></a><button class="btn btn-sm btn-danger pgta-result" onclick="deleteResultFile()"><i class="fas fa-trash"></i></button>';
            $(".pgta-result").remove();
            $("#bodyPgtaResult").append(htmlFile);
            $("#btnUploadPgtaResult").hide();
          }
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    });
  }

  //Eliminar archivo de resultados
  function deleteResultFile() {
    Swal.fire({
      title: '¿Deseas eliminar el archivo?',
      text: "Esta acción no se podrá revertir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value == true) {
        $.ajax({
          type: "POST",
          url: "./?action=embryology-procedures/deleteFile",
          data: {
            treatmentId: "<?php echo $embryologyProcedureId ?>",
            procedureOvuleId: 0,
            imageSectionId: 2
          },
          error: function() {
            Swal.fire(
              '¡Oops!',
              'No se ha podido eliminar el archivo.',
              'error'
            )
          },
          success: function(data) {
            $(".pgta-result").remove();
            $("#btnUploadPgtaResult").show();
          }
        });
      }
    });
  }

  //Guardar datos del semen, procedimiento de andrología de donde se obtuvo y cantidad de dispositivos.
  function saveSelectedAndrologyProcedure() {
    var selectProcedureData = $('#selectAndrologyProcedure').select2('data')
    var originProcedureName = selectProcedureData[0].text;
    var andrologyProcedureId = $("#selectAndrologyProcedure").val();
    var quantity = $("#quantityAndrologyProcedure").val(); //Cantidad de dispositivos

    if (andrologyProcedureId != "" && quantity != null) {
      $.ajax({
        type: "POST",
        url: "./?action=andrology-procedures/add-treatment-procedure-semen",
        data: {
          treatmentId: "<?php echo $embryologyProcedureId ?>",
          andrologyProcedureId: andrologyProcedureId,
          destinationAndrologyProcedureId: null,
          quantity: quantity,
        },
        success: function(data) {
          $("#semen" + andrologyProcedureId).remove();
          var semenProcedureDetail = '<label class="btn btn-simple btn-sm btn-default">' + originProcedureName + '</label><a href="index.php?view=andrology-procedures/details&procedureId=' + andrologyProcedureId + '" target="_blank" rel="tooltip" title="Visualizar" class="btn btn-simple btn-sm btn-default"><i class="fas fa-eye"></i></a><button type="button" class="btn btn-sm btn-danger" onclick="deleteAndrologyProcedure(' + data + ')"><i class="fas fa-trash"></i></button>';
          $("#divSelectedAndrologyProcedures").append(semenProcedureDetail);
        },
        complete: function() {
          $('#modalSearchAndrologyProcedure').modal('hide');
          $("#quantityAndrologyProcedure").val(0);
          window.location.reload();
        }
      });
    } else {
      Swal.fire(
        '¡Oops!',
        'Ingresa todos los datos o haz clic en Cancelar.',
        'error'
      );
    }
  }

  function deleteAndrologyProcedure(id) {
    Swal.fire({
      title: '¿Deseas eliminar este procedimiento de andrología?',
      text: "Esta acción no se podrá revertir.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.value == true) {
        $.ajax({
          type: "POST",
          url: "./?action=andrology-procedures/delete-treatment-procedure-semen",
          data: {
            id: id
          },
          success: function(data) {
            window.location.reload();
          },
          error: function() {
            Swal.fire(
              'Error',
              'Ocurrió un error al eliminar..',
              'error'
            );
          }
        });
      }
    });
  }
</script>