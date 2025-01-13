<?php
/*-------------PGTA (5)----------*/
$pgtaResultFile = EmbryologyProcedureData::getFileByTreatmentOvuleSectionId($_GET["treatmentId"], 0, 2);
?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Datos del Procedimiento</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=embryology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Ciclo:</label>
            <input type="text" class="form-control" placeholder="Ciclo" value="<?php echo $actualCycle ?>" readonly>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha de estimulación:</label>
            <input type="text" id="dt-80" name="details[80]" class="form-control" placeholder="Fecha de estimulación" value="<?php echo $embryologyProcedure->getDateMonthFormat($embryologyProcedure->start_date) ?>" readonly>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha de aspiración folicular:</label>
            <input type="date" id="dt-81" name="details[81]" class="form-control" placeholder="Fecha de aspiración folicular" required value="<?php echo $procedureDetails['81'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Aspiración - Estradiol:</label>
            <input type="text" id="dt-82" name="details[82]" class="form-control" placeholder="Aspiración - Estradiol" value="<?php echo $procedureDetails['82'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Progesterona:</label>
            <input type="text" id="dt-83" name="details[83]" class="form-control" placeholder="Progesterona" value="<?php echo $procedureDetails['83'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Beta Hcg:</label>
            <input type="text" id="dt-84" name="details[84]" class="form-control" placeholder="Beta Hcg" value="<?php echo $procedureDetails['84'] ?>">
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
                <input type="radio" name="details[87]" value="1" <?php echo ($procedureDetails['87'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[87]" value="0" <?php echo ($procedureDetails['87'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento masculino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[88]" value="1" <?php echo ($procedureDetails['88'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[88]" value="0" <?php echo ($procedureDetails['88'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento PGTA:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[89]" value="1" <?php echo ($procedureDetails['89'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[89]" value="0" <?php echo ($procedureDetails['89'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
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
        <!--
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Id donante semen:</label>
            <input type="text" id="dt-86" name="details[86]" class="form-control" placeholder="Id donante semen" value="<?php echo $procedureDetails['86'] ?>" readonly>
          </div>
        </div>-->
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cantidad de folículos:</label>
            <input type="number" min="0" id="dt-90" name="details[90]" class="form-control" placeholder="Cantidad de foliculos" value="<?php echo $procedureDetails['90'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Óvulos recuperados:</label>
            <input type="number" min="0" id="dt-91" name="details[91]" class="form-control" placeholder="Óvulos recuperados" value="<?php echo $procedureDetails['91'] ?>" required <?php echo ($procedureDetails['91'] != "" && $procedureDetails['91'] != 0) ? "readonly" : "" ?>>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MI:</label>
            <input type="number" min="0" id="dt-92" name="details[92]" class="form-control" placeholder="MI" value="<?php echo $procedureDetails['92'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MII:</label>
            <input type="number" min="0" id="dt-93" name="details[93]" class="form-control" placeholder="MII" value="<?php echo $procedureDetails['93'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Vesícula germinal:</label>
            <input type="number" min="0" id="dt-94" name="details[94]" class="form-control" placeholder="Vesícula germinal" value="<?php echo $procedureDetails['94'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Degenerado:</label>
            <input type="number" min="0" id="dt-95" name="details[95]" class="form-control" placeholder="Degenerado" value="<?php echo $procedureDetails['95'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecundación-Ovocitos inseminados:</label>
            <input type="number" min="0" id="dt-96" name="details[96]" class="form-control" placeholder="Fecundación-Ovocitos inseminado" value="<?php echo $procedureDetails['96'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecundación - Fertilizados:</label>
            <input type="number" min="0" id="dt-97" name="details[97]" class="form-control" placeholder="Fecundación - Fertilizados" value="<?php echo $procedureDetails['97'] ?>">
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
        <table id="ovulesDataTable" class="cell-border compact" style="width:100%">
          <thead>
            <tr>
              <th rowspan="2">#</th>
              <?php foreach ($sections as $section) :
                $sectionDate = "";
                if (isset($procedureDetails['81']) && $procedureDetails['81'] != "0000-00-00" && $section->day_number != "" &&  $section->day_number >= 0) {
                  $sectionDate = date("d/m/Y", strtotime($procedureDetails['81'] . " +" . $section->day_number . " days"));
                }
              ?>
                <th colspan="<?php echo $section->total_section_details ?>"><?php echo $section->name . "<br>" . $sectionDate ?></th>
              <?php endforeach; ?>
              <th colspan="2">DESTINO</th>
              <th rowspan="2">MUESTRA SEMEN</th>
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sectionDetails as $sectionDetail) : ?>
                <th><?php echo $sectionDetail->name ?></th>
              <?php endforeach; ?>
              <th>C</th>
              <th>NV</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($procedureOvules as $ovule) :
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
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th rowspan="2">#</th>
              <?php foreach ($sectionDetails as $sectionDetail) : ?>
                <th><?php echo $sectionDetail->name ?></th>
              <?php endforeach; ?>
              <th>C</th>
              <th>NV</th>
              <th rowspan="2">MUESTRA SEMEN</th>
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sections as $section) : ?>
                <th colspan="<?php echo $section->total_section_details ?>"><?php echo $section->name ?></th>
              <?php endforeach; ?>
              <th colspan="2">DESTINO</th>
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
          <input type="text" id="dt-98" name="details[98]" class="form-control" placeholder="No viables" value="0" readonly>
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
            <input type="text" id="dt-440" name="details[440]" class="form-control" value="<?php echo $procedureDetails['440'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad B:</label>
            <input type="text" id="dt-441" name="details[441]" class="form-control" value="<?php echo $procedureDetails['441'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad C:</label>
            <input type="text" id="dt-442" name="details[442]" class="form-control" value="<?php echo $procedureDetails['442'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriones calidad D:</label>
            <input type="text" id="dt-443" name="details[443]" class="form-control" value="<?php echo $procedureDetails['443'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Blastocistos:</label>
            <input type="text" id="dt-444" name="details[444]" class="form-control" value="<?php echo $procedureDetails['444'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Calidad embrionaria:</label>
            <input type="text" id="dt-445" name="details[445]" class="form-control" value="<?php echo $procedureDetails['445'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Mórulas:</label>
            <input type="text" id="dt-446" name="details[446]" class="form-control" value="<?php echo $procedureDetails['446'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 5 - Detenidos:</label>
            <input type="text" id="dt-447" name="details[447]" class="form-control" value="<?php echo $procedureDetails['447'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Blastocistos:</label>
            <input type="text" id="dt-448" name="details[448]" class="form-control" value="<?php echo $procedureDetails['448'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Calidad embrionaria:</label>
            <input type="text" id="dt-449" name="details[449]" class="form-control" value="<?php echo $procedureDetails['449'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7 - Mórulas:</label>
            <input type="text" id="dt-450" name="details[450]" class="form-control" value="<?php echo $procedureDetails['450'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Día 6 y 7- Detenidos:</label>
            <input type="text" id="dt-451" name="details[451]" class="form-control" value="<?php echo $procedureDetails['451'] ?>">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Observaciones:</label>
            <input type="text" id="dt-453" name="details[453]" class="form-control" value="<?php echo $procedureDetails['453'] ?>">
          </div>
        </div>
        <div class="col-md-12">
          <div class="checkbox" id="imagesList">
            <?php
            $arraySelectedImages = explode(",", $procedureDetails['452']);
            foreach ($ovuleImages as $ovuleImage) :
            ?>
              <label id="lblImageList<?php echo $ovuleImage->procedure_ovule_id ?>">
                <input name="details[452][]" id="details[452]" type="checkbox" value="<?php echo $ovuleImage->procedure_ovule_id ?>" <?php echo (in_array($ovuleImage->procedure_ovule_id, $arraySelectedImages)) ? "checked" : "" ?>>
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
              <th rowspan="2"># EMBRIÓN</th>
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
              <th rowspan="2"># EMBRIÓN</th>
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
      dropdownParent: $('#modalSearchAndrologyProcedure'),
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
                    let htmlImageList = '<label id="lblImageList' + procedureOvuleId + '"><input name="details[452][]" id="details[452]" type="checkbox" value="' + procedureOvuleId + '"><a href="' + data + '" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-image"></i>' + procedureOvuleCode + '</a></label>';
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

      } else if (sectionDetailId == "256") {
        //Marcar como PGTA
        var phaseId = trParent.attr("data-section-phase-id");
        let options = {};
        options['X'] = "PGTA";
        options[''] = "No PGTA";
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
                  'No se pudo cancelar el tratamiento..',
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

    //Datatable detalle de embriones vitirficados
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
    //Al cargar la página mostrar totales
    calculateTotalOvulesData();
    //Al cargar la página mostrar detalle de embriones congelados/vitrificados
    fillEmbryoVitrificationDataTable();
  });

  function updateProcedureDetails() {
    let totalOvulesRecovered = $("#dt-91").val();
    if ("<?php echo $procedureDetails['91'] ?>" == '' || "<?php echo $procedureDetails['91'] ?>" == 0) {
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
      })
    } else {
      $("#formUpdateProcedureDetails").submit();
    }
  }

  function calculateTotalOvulesData() {
    var totalNonValidate = 0;
    $(".d4").each(function(td) {
      if ($(this).text() == "X") {
        totalNonValidate++;
      }
    });
    $("#dt-98").val(totalNonValidate);

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
    var selectProcedureData = $('#selectAndrologyProcedure').select2('data');
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