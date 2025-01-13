<?php
/*-------------CICLO MIXTO (8)----------
Se combinaron datos de FIVTE + REC*/

/*FIVTE- Obtener el código de vitrificación si se realizó ese subtratamiento de fertilidad*/
$subTreatment = PatientCategoryData::getSubTreatmentById($embryologyProcedureId);
$subTreatmentId = ($subTreatment) ? $subTreatment->id : 0;

/*REC - Pgta Resultado */
$pgtaResultFile = EmbryologyProcedureData::getFileByTreatmentOvuleSectionId($_GET["treatmentId"], 0, 2);

//OBTENER DATOS DEL FORMATO/TABLA SECCIONES Y CONTENIDO DE TABLA DE ÓVULOS DE PROCEDIMIENTO REC
$sectionsRec = PatientOvuleData::getAllSectionsByTreatment($embryologyProcedure->patient_treatment_id, 2);
$sectionDetailsRec = PatientOvuleData::getAllSectionDetailsByTreatment($embryologyProcedure->patient_treatment_id, 2);
$procedureOvulesRec = PatientOvuleData::getOvulesByProcedureSectionId($embryologyProcedureId, 2);
?>

<!--COLOR-PICKER -->
<!--<link href="plugins/bootstrap/css/bootstrap-colorpicker.min.css" rel="stylesheet" />-->
<script src="plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!--COLOR-PICKER -->
<input type="hidden" id="subTreatmentId" class="form-control" value="<?php echo $subTreatmentId ?>">


<!-- ----------------------------------------FIVTE------------------------------------------------ -->

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Datos del Procedimiento FIVTE de la paciente</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=embryology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Ciclo:
              <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Descripción del ciclo"></i>
            </label>
            <input type="text" class="form-control" placeholder="Ciclo" value="<?php echo $actualCycle ?>" readonly>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha de estimulación:</label>
            <input type="text" class="form-control" placeholder="Fecha de estimulación" value="<?php echo $embryologyProcedure->getDateMonthFormat($embryologyProcedure->start_date) ?>" readonly>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha de aspiración folicular:</label>
            <input type="date" id="dt-148" name="details[148]" class="form-control" placeholder="Fecha de aspiración folicular" value="<?php echo $procedureDetails['148'] ?>" required>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Aspiración - Estradiol:</label>
            <input type="text" id="dt-149" name="details[149]" class="form-control" placeholder="Aspiración - Estradiol" value="<?php echo $procedureDetails['149'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Progesterona:</label>
            <input type="text" id="dt-164" name="details[164]" class="form-control" placeholder="Progesterona" value="<?php echo $procedureDetails['164'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Beta Hcg:</label>
            <input type="text" id="dt-150" name="details[150]" class="form-control" placeholder="Beta Hcg" value="<?php echo $procedureDetails['150'] ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1">Diagnóstico:</label>
            <input type="text" class="form-control" value="<?php echo $treatmentDiagnostics ?>" readonly>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento femenino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[153]" value="1" <?php echo ($procedureDetails['153'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[153]" value="0" <?php echo ($procedureDetails['153'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento masculino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[154]" value="1" <?php echo ($procedureDetails['154'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[154]" value="0" <?php echo ($procedureDetails['154'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cantidad de folículos:</label>
            <input type="number" min="0" id="dt-165" name="details[165]" class="form-control" placeholder="Cantidad de folículos" min="0" value="<?php echo $procedureDetails['165'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Óvulos recuperados:</label>
            <input type="number" min="0" id="dt-155" name="details[155]" class="form-control" placeholder="Óvulos recuperados" min="0" value="<?php echo $procedureDetails['155'] ?>" required <?php echo ($procedureDetails['155'] != "" && $procedureDetails['155'] != 0) ? "readonly" : "" ?>>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MI:</label>
            <input type="number" min="0" id="dt-156" name="details[156]" class="form-control" placeholder="MI" value="<?php echo $procedureDetails['156'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MII:</label>
            <input type="number" min="0" id="dt-157" name="details[157]" class="form-control" placeholder="MII" value="<?php echo $procedureDetails['157'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Vesícula germinal:</label>
            <input type="number" min="0" id="dt-158" name="details[158]" class="form-control" placeholder="Vesícula germinal" value="<?php echo $procedureDetails['158'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Degenerado:</label>
            <input type="number" min="0" id="dt-159" name="details[159]" class="form-control" placeholder="Degenerado" value="<?php echo $procedureDetails['159'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecundación-Ovocitos inseminados:</label>
            <input type="number" min="0" id="dt-160" name="details[160]" class="form-control" placeholder="Fecundación-Ovocitos inseminado" value="<?php echo $procedureDetails['160'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecundación - Fertilizados:</label>
            <input type="number" min="0" id="dt-161" name="details[161]" class="form-control" placeholder="Fecundación - Fertilizados" value="<?php echo $procedureDetails['161'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="button" onclick="updateProcedureDetails()" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" id="patientCategoryTreatmentId" name="patientCategoryTreatmentId" value="<?php echo $_GET["treatmentId"] ?>" required>
        </div>
      </div>
    </form>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="inputEmail1" class="control-label">Semen:</label>
          <br>
          <button type="button" data-toggle="modal" data-target="#modalSearchAndrologyProcedure" class="btn btn-sm btn-default"><i class="fas fa-search"></i> Buscar semen</button>
          <div class="col-lg-12" id="divSelectedAndrologyProcedures">
            <?php foreach ($originAndrologyProcedures as $andrologyProcedure) :
              $donorDetail = ($andrologyProcedure->patient_donor_id != '') ? ' (' . $andrologyProcedure->patient_donor_id . ')' : '';
            ?>
              <label id="semen<?php echo $andrologyProcedure->patient_procedure_id ?>" class="btn btn-simple btn-sm btn-default"><?php echo $andrologyProcedure->procedure_code . ' - ' . $andrologyProcedure->patient_name . ' ' . $donorDetail . ' -' . $andrologyProcedure->quantity . ' dispositivos' ?></label>
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
            <input type="text" id="dt-152" name="details[152]" class="form-control" placeholder="Id donante semen" value="<?php echo $procedureDetails['152'] ?>" readonly>
          </div>
        </div>-->
    </div>

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
      <div class="col-md-12">
        <table id="ovulesDataTableFivte" class="cell-border compact" style="width:100%">
          <thead>
            <tr>
              <th rowspan="2">#</th>
              <?php foreach ($sections as $section) :
                $sectionDate = "";
                if (isset($procedureDetails['148']) && $procedureDetails['148'] != "0000-00-00" && $section->day_number != "" &&  $section->day_number >= 0) {
                  $sectionDate = date("d/m/Y", strtotime($procedureDetails['148'] . " +" . $section->day_number . " days"));
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
              <tr data-section-phase-id="<?php echo ($ovule->end_ovule_phase_id) ? $ovule->end_ovule_phase_id : $ovule->initial_ovule_phase_id ?>" data-procedure-ovule-code="<?php echo $ovule->procedure_code ?>">
                <td><?php echo $ovule->procedure_code ?></td>
                <?php foreach ($ovuleSectionDetailValues as $ovuleValue) : ?>
                  <td data-section-detail-id="<?php echo $ovuleValue->ovule_section_detail_id ?>" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-' . $ovuleValue->ovule_section_detail_id ?>">
                    <?php echo $ovuleValue->value ?>
                  </td>
                <?php endforeach; ?>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d3" data-section-detail-id="d3" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d3' ?>"><?php echo ($ovule->end_ovule_status_id == 3) ? "X" : "" ?></td>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d2" data-section-detail-id="d2" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d2' ?>"><?php echo ($ovule->end_ovule_status_id == 2) ? "X" : "" ?></td>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> fivte-d4" data-section-detail-id="d4" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d4' ?>"><?php echo ($ovule->end_ovule_status_id == 4) ? "X" : "" ?></td>
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
                <td class="imageFivte-<?php echo $ovule->id ?>" data-section-detail-id="image" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-fivte-image' ?>">
                  <?php if ($image) : ?>
                    <a href='<?php echo $image->path ?>' target='_blank' class="btn btn-default btn-sm"><i class='fas fa-image'></i></a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php
            endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
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
          <input type="text" id="dt-162" name="details[162]" class="form-control" placeholder="No viables" value="0" readonly>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Reporte del Paciente - FIVTE de la paciente</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=embryology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad A:</label>
            <input type="text" id="dt-498" name="details[498]" class="form-control" value="<?php echo $procedureDetails['498'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad B:</label>
            <input type="text" id="dt-499" name="details[499]" class="form-control" value="<?php echo $procedureDetails['499'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad C:</label>
            <input type="text" id="dt-500" name="details[500]" class="form-control" value="<?php echo $procedureDetails['500'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad D:</label>
            <input type="text" id="dt-501" name="details[501]" class="form-control" value="<?php echo $procedureDetails['501'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Blastocistos:</label>
            <input type="text" id="dt-502" name="details[502]" class="form-control" value="<?php echo $procedureDetails['502'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Calidad embrionaria:</label>
            <input type="text" id="dt-503" name="details[503]" class="form-control" value="<?php echo $procedureDetails['503'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Mórulas:</label>
            <input type="text" id="dt-504" name="details[504]" class="form-control" value="<?php echo $procedureDetails['504'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Detenidos:</label>
            <input type="text" id="dt-505" name="details[505]" class="form-control" value="<?php echo $procedureDetails['505'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Blastocistos:</label>
            <input type="text" id="dt-506" name="details[506]" class="form-control" value="<?php echo $procedureDetails['506'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Calidad embrionaria:</label>
            <input type="text" id="dt-507" name="details[507]" class="form-control" value="<?php echo $procedureDetails['507'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Mórulas:</label>
            <input type="text" id="dt-508" name="details[508]" class="form-control" value="<?php echo $procedureDetails['508'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7- Detenidos:</label>
            <input type="text" id="dt-509" name="details[509]" class="form-control" value="<?php echo $procedureDetails['509'] ?>">
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
<!-- --------------------------INFORMACIÓN REC---------------------------------------------- -->
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Datos del Procedimiento REC - DONOVO</h3>
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
            <label for="inputEmail1" class="control-label">Fecha de aspiración folicular:</label>
            <input type="date" id="dt-166" name="details[166]" class="form-control" placeholder="Fecha de aspiración folicular" value="<?php echo $procedureDetails['166'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Aspiración - Estradiol:</label>
            <input type="text" id="dt-167" name="details[167]" class="form-control" placeholder="Aspiración - Estradiol" value="<?php echo $procedureDetails['167'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento femenino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[170]" value="1" <?php echo ($procedureDetails['170'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[170]" value="0" <?php echo ($procedureDetails['170'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento masculino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[171]" value="1" <?php echo ($procedureDetails['171'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[171]" value="0" <?php echo ($procedureDetails['171'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Código de ciclo de donación de óvulos:</label>
            <input type="text" id="dt-180" name="details[180]" class="form-control" placeholder="Código de ciclo de donación de óvulos" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Código de donadora de óvulos:</label>
            <input type="text" id="dt-181" name="details[181]" class="form-control" placeholder="Código de ciclo de donación de óvulos" readonly>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Diagnóstico:</label>
            <input type="text" class="form-control" value="<?php echo $treatmentDiagnostics ?>" readonly>
          </div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Óvulos recuperados:</label>
            <input type="number" min="0" id="dt-172" name="details[172]" class="form-control" placeholder="Óvulos recuperados" value="<?php echo $procedureDetails['172'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MI:</label>
            <input type="number" min="0" id="dt-173" name="details[173]" class="form-control" placeholder="MI" value="<?php echo $procedureDetails['173'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MII:</label>
            <input type="number" min="0" id="dt-174" name="details[174]" class="form-control" placeholder="MII" value="<?php echo $procedureDetails['174'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Vesícula germinal:</label>
            <input type="number" min="0" id="dt-175" name="details[175]" class="form-control" placeholder="Vesícula germinal" value="<?php echo $procedureDetails['175'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Degenerado:</label>
            <input type="number" min="0" id="dt-176" name="details[176]" class="form-control" placeholder="Degenerado" value="<?php echo $procedureDetails['176'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecundación-Ovocitos inseminados:</label>
            <input type="number" min="0" id="dt-177" name="details[177]" class="form-control" placeholder="Fecundación-Ovocitos inseminado" value="<?php echo $procedureDetails['177'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecundación - Fertilizados:</label>
            <input type="number" min="0" id="dt-178" name="details[178]" class="form-control" placeholder="Fecundación - Fertilizados" value="<?php echo $procedureDetails['178'] ?>">
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
    <hr>
    <div class="row">
      <div class="col-md-12">
        <table id="ovulesDataTableRec" class="cell-border compact" style="width:100%">
          <thead>
            <tr>
              <th rowspan="2"></th>
              <th rowspan="2">CÓDIGO DEL PROCEDIMIENTO</th>
              <th rowspan="2">#</th>
              <?php foreach ($sectionsRec as $section) :
                $sectionDate = "";
                if (isset($procedureDetails['166']) && $procedureDetails['166'] != "0000-00-00" && $section->day_number != "" &&  $section->day_number >= 0) {
                  $sectionDate = date("d/m/Y", strtotime($procedureDetails['166'] . " +" . $section->day_number . " days"));
                }
              ?>
                <th colspan="<?php echo $section->total_section_details ?>"><?php echo $section->name . "<br>" . $sectionDate ?></th>
              <?php endforeach; ?>
              <th colspan="3">DESTINO</th>
              <th rowspan="2">MUESTRA SEMEN</th>
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sectionDetailsRec as $sectionDetail) : ?>
                <th><?php echo $sectionDetail->name ?></th>
              <?php endforeach; ?>
              <th>TE</th>
              <th>C</th>
              <th>NV</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($procedureOvulesRec as $ovule) :
              $ovuleSectionDetailValues = PatientOvuleData::getSectionDetailsByOvuleId($embryologyProcedure->patient_treatment_id, 2, $ovule->id);
              $image = $ovule->getImage();
            ?>
              <tr class="origin-treatment" data-origin-treatment-code="<?php echo $ovule->getOriginPatientTreatment()->treatment_code; ?>" data-origin-donor-code="<?php echo $ovule->getDonor()->donor_id; ?>" data-origin-treatment-patient-name="<?php echo $ovule->getOriginPatientTreatment()->getPatient()->name; ?>" data-procedure-ovule-id="<?php echo $ovule->id ?>" data-section-phase-id="<?php echo ($ovule->end_ovule_phase_id) ? $ovule->end_ovule_phase_id : $ovule->initial_ovule_phase_id ?>" data-procedure-ovule-code="<?php echo $ovule->procedure_code ?>">
                <td>
                  <!--<button class="btn btn-sm btn-danger delete-ovule"><i class="fas fa-trash"></i></button>-->
                </td>
                <td><?php echo $ovule->getOriginPatientTreatment()->treatment_code; ?></td>
                <td><?php echo $ovule->getDonor()->donor_id . "-" . $ovule->procedure_code ?></td>
                <?php foreach ($ovuleSectionDetailValues as $ovuleValue) : ?>
                  <td data-section-detail-id="<?php echo $ovuleValue->ovule_section_detail_id ?>" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-' . $ovuleValue->ovule_section_detail_id ?>">
                    <?php echo $ovuleValue->value ?>
                  </td>
                <?php endforeach; ?>
                <td class="destination-<?php echo $ovule->id ?> d3" data-section-detail-id="d3" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d3' ?>"><?php echo ($ovule->end_ovule_status_id == 3) ? "X" : "" ?></td>
                <td class="destination-<?php echo $ovule->id ?> d2" data-section-detail-id="d2" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d2' ?>"><?php echo ($ovule->end_ovule_status_id == 2) ? "X" : "" ?></td>
                <td class="destination-<?php echo $ovule->id ?> rec-d4" data-section-detail-id="d4" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d4' ?>"><?php echo ($ovule->end_ovule_status_id == 4) ? "X" : "" ?></td>
                <td class="semen-used-<?php echo $ovule->id ?> fivte-semen-used" data-section-detail-id="semen-used" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-semen-used' ?>">
                  <?php
                  $semen = "";
                  if ($ovule->patient_andrology_procedure_id) {
                    $andrologyProcedureData = $ovule->getProcedureOvuleSemen();
                    $donorDetail = ($andrologyProcedureData->patient_donor_id != '') ? '(' . $andrologyProcedureData->patient_donor_id . ')' : '';
                    $semenType = ($andrologyProcedureData->patient_id == $partner->id) ? "PAREJA" : "DONANTE " . $donorDetail;
                    $semen = "<b>" . $semenType . "</b><br>" . $andrologyProcedureData->procedure_code;
                  }
                  echo $semen ?>
                </td>
                <td class="imageRec-<?php echo $ovule->id ?>" data-section-detail-id="image" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-rec-image' ?>">
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
              <th rowspan="2">CÓDIGO DEL PROCEDIMIENTO</th>
              <th rowspan="2">#</th>
              <?php foreach ($sectionDetailsRec as $sectionDetail) : ?>
                <th><?php echo $sectionDetail->name ?></th>
              <?php endforeach; ?>
              <th>TE</th>
              <th>C</th>
              <th>NV</th>
              <th rowspan="2">MUESTRA SEMEN</th>
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sectionsRec as $section) : ?>
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
          <input type="text" id="dt-179" name="details[179]" class="form-control" placeholder="No viables" value="0" readonly>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>

<!--MODAL ELEGIR FIVTE O TRATAMIENTOS Y ÓVULOS A UTILIZAR-->

<!-- --------------------------INFORMACIÓN FIVTE - REC---------------------------------------------- -->
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Reporte del Paciente REC - DONOVO</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=embryology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad A:</label>
            <input type="text" id="dt-510" name="details[510]" class="form-control" value="<?php echo $procedureDetails['510'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad B:</label>
            <input type="text" id="dt-511" name="details[511]" class="form-control" value="<?php echo $procedureDetails['511'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad C:</label>
            <input type="text" id="dt-512" name="details[512]" class="form-control" value="<?php echo $procedureDetails['512'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad D:</label>
            <input type="text" id="dt-513" name="details[513]" class="form-control" value="<?php echo $procedureDetails['513'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Blastocistos:</label>
            <input type="text" id="dt-514" name="details[514]" class="form-control" value="<?php echo $procedureDetails['514'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Calidad embrionaria:</label>
            <input type="text" id="dt-515" name="details[515]" class="form-control" value="<?php echo $procedureDetails['515'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Mórulas:</label>
            <input type="text" id="dt-516" name="details[516]" class="form-control" value="<?php echo $procedureDetails['516'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Detenidos:</label>
            <input type="text" id="dt-517" name="details[517]" class="form-control" value="<?php echo $procedureDetails['517'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Blastocistos:</label>
            <input type="text" id="dt-518" name="details[518]" class="form-control" value="<?php echo $procedureDetails['518'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Calidad embrionaria:</label>
            <input type="text" id="dt-519" name="details[519]" class="form-control" value="<?php echo $procedureDetails['519'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Mórulas:</label>
            <input type="text" id="dt-520" name="details[520]" class="form-control" value="<?php echo $procedureDetails['520'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7- Detenidos:</label>
            <input type="text" id="dt-521" name="details[521]" class="form-control" value="<?php echo $procedureDetails['521'] ?>">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Observaciones generales:</label>
            <input type="text" id="dt-523" name="details[523]" class="form-control" value="<?php echo $procedureDetails['523'] ?>">
          </div>
        </div>
        <div class="col-md-12">
          <div class="checkbox" id="imagesList">
            <?php
            $arraySelectedImages = explode(",", $procedureDetails['522']);
            foreach ($ovuleImages as $ovuleImage) :
              $ovuleData = $ovuleImage->getProcedureOvule();
              //Se define a que sección pertenece 1-FIVTE(Paciente) o 2-REC(Donadora) para no confundir los óvulos de la imagen
              $sectionName = ($ovuleData->section_id == 1) ? "Paciente" : "Donante";
            ?>
              <label id="lblImageList<?php echo $ovuleImage->procedure_ovule_id ?>">
                <input name="details[522][]" id="details[522]" type="checkbox" value="<?php echo $ovuleImage->procedure_ovule_id ?>" <?php echo (in_array($ovuleImage->procedure_ovule_id, $arraySelectedImages)) ? "checked" : "" ?>>
                <a href='<?php echo $ovuleImage->path ?>' target='_blank' class="btn btn-default btn-sm"><i class='fas fa-image'></i> <?php echo $sectionName . "- " . $ovuleData->procedure_code ?></a>
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
              <?php if ($embryoVitrificationDetail->code == "") : ?>
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default" id="btnAddEmbryoVitrificacionCode" onclick="addEmbryoVitrificationCode()"><i class="fas fa-plus"></i></button>
                </div>
              <?php endif; ?>
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

<div class="box box-primary" id="boxOvuleVitrificationFivte">
  <div class="box-header with-border">
    <h3 class="box-title">Información vitrificación y ubicación de óvulos</h3>
    <div class="box-tools pull-right"><label id="totalOvuleVitrification">Total: 0</label></div>
  </div>
  <!-- /.box-header -->
  <div class="box-body" id="bodyOvuleVitrification">
    <div class="row">
      <div class="col-lg-12" id="divSaveSubTreatment">
        <?php if (!$subTreatment) : ?>
          <button type="submit" class="btn btn-primary btn-sm" id="btnSaveSubTreatment" onclick="saveSubTreatment()">Generar código de vitrificación</button>
        <?php else : ?>
          <label class="btn btn-simple btn-primary"><?php echo $subTreatment->treatment_code ?></label><a href="index.php?view=embryology-procedures/details&treatmentId=<?php echo $subTreatment->id; ?>" target="_blank" rel="tooltip" title="Editar" class="btn btn-simple btn-default"><i class="fas fa-pencil-alt"></i></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
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


    //DATATABLE DETALLE DE ÓVULOS/EMBRIONES FIVTE
    var ovulesDataTableFivte = $('#ovulesDataTableFivte').DataTable({
      "ordering": false,
      "searching": false,
      "paging": false,
      "info": false,
      "scrollX": true,
      "scrollY": "550px",
      "scrollCollapse": true,
      "fixedColumns": {
        left: 1
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
    $('#ovulesDataTableFivte').on('click', 'tbody td:not(:first-child)', function(e) {
      var sectionDetailId = $(this).attr("data-section-detail-id");
      var procedureOvuleId = $(this).attr("data-procedure-ovule-id");
      var trParent = $(this).closest('tr');
      var treatmentId = "<?php echo $embryologyProcedureId ?>";

      if (sectionDetailId == "image") {
        let filePath = $(this).find('a').prop("href");
        let procedureOvuleCode = trParent.attr("data-procedure-ovule-code");
        Swal.fire({
          title: '#'+procedureOvuleCode,
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
                    $(".imageFivte-" + procedureOvuleId).text("");
                    let htmlImage = "<a href='" + data + "' target='_blank' class='btn btn-default btn-sm'><i class='fas fa-image'></i></a>";
                    $("#" + procedureOvuleId + "-fivte-" + sectionDetailId).html(htmlImage);

                    //Mostrar imagen en la lista de imágenes para seleccionar y que se muestren en el reporte del paciente
                    $("#lblImageList" + procedureOvuleId).remove();
                    let htmlImageList = '<label id="lblImageList' + procedureOvuleId + '"><input name="details[522][]" id="details[522]" type="checkbox" value="' + procedureOvuleId + '"><a href="' + data + '" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-image"></i>' + procedureOvuleCode + '</a></label>';
                    $("#imagesList").append(htmlImageList);
                    ovulesDataTableFivte.columns.adjust();
                  }
                });
              },
              allowOutsideClick: () => !Swal.isLoading()
            });
          } else if (result.isDenied) {
            Swal.fire({
              title: '¿Deseas eliminar esta imagen #'+procedureOvuleCode+'?',
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
                    $(".imageFivte-" + procedureOvuleId).text("");
                    $("#" + procedureOvuleId + "-fivte-" + sectionDetailId).html("");

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

      } else if (sectionDetailId == "262") {
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
      } else if (sectionDetailId == "d2" || sectionDetailId == "d3" || sectionDetailId == "d4") {
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
        }
        //DESTINO (TE-3, C-2, NV-4)
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
                ovulesDataTableFivte.columns.adjust();

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
                ovulesDataTableFivte.columns.adjust();
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
                ovulesDataTableFivte.columns.adjust();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      }
    });


    //Datatable detalle de óvulos/embriones REC
    var ovulesDataTableRec = $('#ovulesDataTableRec').DataTable({
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
    $('#ovulesDataTableRec').on('click', 'tbody td:not(:first-child):not(:nth-child(2)):not(:nth-child(3))', function(e) {
      var sectionDetailId = $(this).attr("data-section-detail-id");
      var procedureOvuleId = $(this).attr("data-procedure-ovule-id");
      var trParent = $(this).closest('tr');
      var treatmentId = "<?php echo $embryologyProcedureId ?>";

      if (sectionDetailId == "image") {
        let filePath = $(this).find('a').prop("href");
        let procedureOvuleCode = trParent.attr("data-procedure-ovule-code");
        Swal.fire({
          title: '#'+procedureOvuleCode,
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
                    $(".imageRec-" + procedureOvuleId).text("");
                    let htmlImage = "<a href='" + data + "' target='_blank' class='btn btn-default btn-sm'><i class='fas fa-image'></i></a>";
                    $("#" + procedureOvuleId + "-rec-" + sectionDetailId).html(htmlImage);

                    //Mostrar imagen en la lista de imágenes para seleccionar y que se muestren en el reporte del paciente
                    $("#lblImageList" + procedureOvuleId).remove();
                    let htmlImageList = '<label id="lblImageList' + procedureOvuleId + '"><input name="details[522][]" id="details[522]" type="checkbox" value="' + procedureOvuleId + '"><a href="' + data + '" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-image"></i>' + procedureOvuleCode + '</a></label>';
                    $("#imagesList").append(htmlImageList);
                    ovulesDataTableFivte.columns.adjust();
                  }
                });
              },
              allowOutsideClick: () => !Swal.isLoading()
            });
          } else if (result.isDenied) {
            Swal.fire({
              title: '¿Deseas eliminar esta imagen #'+procedureOvuleCode+'?',
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
                    $(".imageRec-" + procedureOvuleId).text("");
                    $("#" + procedureOvuleId + "-rec-" + sectionDetailId).html("");

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

      }else if (sectionDetailId == "260") {
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
      } else if (sectionDetailId == "d2" || sectionDetailId == "d3" || sectionDetailId == "d4") {
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
                ovulesDataTableRec.columns.adjust();

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
                ovulesDataTableRec.columns.adjust();
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
                ovulesDataTableRec.columns.adjust();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      }
    });

    //Eliminar filas de detalle de óvulos
    $('#ovulesDataTableRec tbody').on('click', '.delete-ovule', function() {
      var procedureOvuleId = $(this).parents('tr').attr('data-procedure-ovule-id');
      var row = ovulesDataTableRec.row($(this).parents('tr'));

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

  //Actualizar datos generales del procedimiento, validar los óvulos recuperados, ya que no se podrá editar.
  function updateProcedureDetails() {
    let totalOvulesRecovered = $("#dt-155").val();
    if ("<?php echo $procedureDetails['155'] ?>" == '' || "<?php echo $procedureDetails['155'] ?>" == 0) {
      Swal.fire({
        title: '¿Deseas registrar ' + totalOvulesRecovered + ' óvulos como recuperados?',
        text: "Esta acción no se podrá revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value == true) {
          $("#formUpdateProcedureDetails").submit();
        }
      });
    } else {
      $("#formUpdateProcedureDetails").submit();
    }
  }

  //Calcular el total de óvulos por cada destino.
  function calculateTotalOvulesData() {
    //FIVTE - No válidos
    var totalNonValidate = 0;
    $(".fivte-d4").each(function(td) {
      if ($(this).text() == "X") {
        totalNonValidate++;
      }
    });
    $("#dt-162").val(totalNonValidate);

    //REC - No válidos
    var totalNonValidate = 0;
    $(".rec-d4").each(function(td) {
      if ($(this).text() == "X") {
        totalNonValidate++;
      }
    });
    $("#dt-179").val(totalNonValidate);

    //FIVTE y REC- Transferencia de embriones
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

    //FIVTE - REC Vitrificación de embriones
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

    //FIVTE - Vitrificación de óvulos
    var totalOvuleVitrificationFivte = 0;
    $(".fivte-d2").each(function(td) {
      var trParent = $(this).closest('tr');
      if ($(this).text() == "X" && trParent.attr("data-section-phase-id") == 1) {
        totalOvuleVitrificationFivte++;
      }
    });
    $("#totalOvuleVitrificationFivte").text("Total: " + totalOvuleVitrificationFivte);
    if (totalOvuleVitrificationFivte > 0) {
      $("#boxOvuleVitrificationFivte").show();
      //En FIVTE y DONOVO, si hay óvulos vitrificados y no se ha creado código de vitrificación mostrar el botón de crear
      if ($("#subTreatmentId").val() == 0) {
        $("#btnSaveSubTreatment").show();
      }
    } else {
      //En FIVTE y DONOVO, si no se ha creado un código de vitrificación y no hay óvulos vitrificados ocultar el botón de crear
      if ($("#subTreatmentId").val() == 0) {
        $("#boxOvuleVitrificationFivte").hide();
        $("#btnSaveSubTreatment").hide();
      }
    }
  }

  //Mostrar el resumen de los códigos de procedencia de embriones
  function showOvuleOriginTreatments() {
    var arrayTreatments = [];
    var arrayDonors = [];
    $(".origin-treatment").each(function(td) {
      arrayTreatments.push($(this).attr("data-origin-treatment-code"));
      arrayDonors.push($(this).attr("data-origin-donor-code"));
    });
    //Sólo claves de tratamientos únicos
    var treatments = arrayTreatments.filter(function(item, i, arrayTreatments) {
      return i == arrayTreatments.indexOf(item);
    });

    //Sólo donantes únicos
    var donors = arrayDonors.filter(function(item, i, arrayDonors) {
      return i == arrayDonors.indexOf(item);
    });

    //Mostrar en input ordenados
    treatments.sort();
    donors.sort();

    $("#dt-180").val(treatments.toString()); //Cambiar
    $("#dt-181").val(donors.toString()); //Cambiar
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

  //Crear subcódigo de vitrificación VITOVULO
  function saveSubTreatment() {
    Swal.fire({
      title: '¿Deseas generar un código VITOVULO asociado a este FIVTE? Hazlo sólo cuando tengas especificados TODOS los óvulos vitrificados en la tabla de detalles',
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
          url: "./?action=patientcategories/add-patient-subtreatment",
          type: "POST",
          data: {
            patientTreatmentId: 10, //Tratamiento VITOVULO
            primaryTreatmentId: $("#patientCategoryTreatmentId").val()
          },
          success: function(data) {
            var treatmentData = JSON.parse(data);
            $("#subTreatmentId").val(treatmentData["id"]);
            $("#btnSaveSubTreatment").hide();
            var subTreatmentLink = '<label class="btn btn-simple btn-primary">' + treatmentData["treatment_code"] + '</label><a href="index.php?view=embryology-procedures/details&treatmentId=' + treatmentData["id"] + '" target="_blank" rel="tooltip" title="Editar" class="btn btn-simple btn-default"><i class="fas fa-pencil-alt"></i></a>';
            $("#divSaveSubTreatment").append(subTreatmentLink);
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Ha ocurrido un error al registrar el tratamiento, recarga la página.'
            });
          }
        });
      }
    })
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