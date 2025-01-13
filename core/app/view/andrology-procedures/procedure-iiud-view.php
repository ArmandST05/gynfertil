<?php
/*-------------INSEMINACIÓN INTRAUTERINA DE DONANTE(8)----------*/
$lens40x = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 3);
$lens100x = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 4);

//Obtener los datos de los procedimientos de donde se obtuvo el SEMEN
$originAndrologyProcedures = AndrologyProcedureData::getOriginSemenProceduresByProcedureId($andrologyProcedureId);
?>

<!--COLOR-PICKER -->
<!--<link href="plugins/bootstrap/css/bootstrap-colorpicker.min.css" rel="stylesheet" />-->
<script src="plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!--COLOR-PICKER -->

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"></h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=andrology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Doctor:</label>
            <input type="text" id="dt-medicName" name="details[medicName]" class="form-control" placeholder="" value="<?php echo $andrologyProcedure->medic_name ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Días de abstinencia:</label>
            <input type="text" id="dt-358" name="details[358]" class="form-control" placeholder="" value="<?php echo $procedureDetails['358'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Recolección:</label>
            <input type="text" id="dt-359" name="details[359]" class="form-control" placeholder="" value="<?php echo $procedureDetails['359'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Procesamiento:</label>
            <input type="text" id="dt-360" name="details[360]" class="form-control" placeholder="" value="<?php echo $procedureDetails['360'] ?>">
          </div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Procedencia:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[361]" value="1" <?php echo ($procedureDetails['361'] == 1) ? "checked" : "" ?>>Fresco
              </label>
              <label>
                <input type="radio" name="details[361]" value="0" <?php echo ($procedureDetails['361'] == 0) ? "checked" : "" ?>>Descongelado
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Descongelado - Código Congelación:</label>
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
      </div>
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" id="patientAndrologyProcedureId" name="patientAndrologyProcedureId" value="<?php echo $andrologyProcedureId ?>" required>
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
  </div>
  <!-- /.box-body -->
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Análisis macroscópico</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=andrology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <label>RESULTADO</label>
        </div>
        <div class="col-md-3">
          <label>VALORES DE REFERENCIA OMS 2021</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Volumen (ml):</label>
          <div class="input-group">
            <input type="text" id="dt-364" name="details[364]" class="form-control" placeholder="" value="<?php echo $procedureDetails['364'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≥ 1.4 mL</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Licuefacción:</label>
          <div class="input-group">
            <input type="text" id="dt-365" name="details[365]" class="form-control" placeholder="" value="<?php echo $procedureDetails['365'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>Completa</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Viscosidad:</label>
          <div class="input-group">
            <input type="text" id="dt-366" name="details[366]" class="form-control" placeholder="" value="<?php echo $procedureDetails['366'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>Normal</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Aspecto:</label>
          <div class="input-group">
            <input type="text" id="dt-367" name="details[367]" class="form-control" placeholder="" value="<?php echo $procedureDetails['367'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>Perla/ gris opalescente</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">pH:</label>
          <div class="input-group">
            <input type="text" id="dt-368" name="details[368]" class="form-control" placeholder="" value="<?php echo $procedureDetails['368'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≥ 7.2</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Cuerpos Gelatinosos:</label>
          <div class="input-group">
            <input type="text" id="dt-369" name="details[369]" class="form-control" placeholder="" value="<?php echo $procedureDetails['369'] ?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" name="patientAndrologyProcedureId" value="<?php echo $andrologyProcedureId ?>" required>
        </div>
      </div>
    </form>
  </div>
  <!-- /.box-body -->
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Análisis microscópico</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=andrology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <label>RESULTADO MUESTRA INICIAL</label>
        </div>
        <div class="col-md-3">
          <label>RESULTADO MUESTRA CAPACITADA</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Concentración (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-370" name="details[370]" class="form-control" placeholder="" value="<?php echo $procedureDetails['370'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Concentración (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-371" name="details[371]" class="form-control" placeholder="" value="<?php echo $procedureDetails['371'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≥ 16 x 10⁶/ mL</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-372" name="details[372]" class="form-control" placeholder="" value="<?php echo $procedureDetails['372'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-373" name="details[373]" class="form-control" placeholder="" value="<?php echo $procedureDetails['373'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≥ 39 x 10⁶</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Células redondas (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-374" name="details[374]" class="form-control" placeholder="" value="<?php echo $procedureDetails['374'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Células redondas (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-375" name="details[375]" class="form-control" placeholder="" value="<?php echo $procedureDetails['375'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≤ 1 x 10⁶/ mL</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Leucocitos:</label>
          <div class="input-group">
            <input type="text" id="dt-376" name="details[376]" class="form-control" placeholder="" value="<?php echo $procedureDetails['376'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Leucocitos:</label>
          <div class="input-group">
            <input type="text" id="dt-377" name="details[377]" class="form-control" placeholder="" value="<?php echo $procedureDetails['377'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≤ 1 x 10⁶/ mL</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Aglutinación:</label>
          <div class="input-group">
            <input type="text" id="dt-378" name="details[378]" class="form-control" placeholder="" value="<?php echo $procedureDetails['378'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Aglutinación:</label>
          <div class="input-group">
            <input type="text" id="dt-379" name="details[379]" class="form-control" placeholder="" value="<?php echo $procedureDetails['379'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>Negativa</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Otros:</label>
            <input type="text" id="dt-380" name="details[380]" class="form-control" placeholder="" value="<?php echo $procedureDetails['380'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Otros:</label>
            <input type="text" id="dt-381" name="details[381]" class="form-control" placeholder="" value="<?php echo $procedureDetails['381'] ?>">
          </div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <label>RESULTADO MUESTRA INICIAL</label>
        </div>
        <div class="col-md-3">
          <label>RESULTADO MUESTRA CAPACITADA</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad Progresiva (PR):</label>
          <div class="input-group">
            <input type="text" id="dt-382" name="details[382]" class="form-control" placeholder="" value="<?php echo $procedureDetails['382'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad Progresiva (PR):</label>
          <div class="input-group">
            <input type="text" id="dt-383" name="details[383]" class="form-control" placeholder="" value="<?php echo $procedureDetails['383'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≥ 30 % </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad No Progresiva (NP):</label>
          <div class="input-group">
            <input type="text" id="dt-384" name="details[384]" class="form-control" placeholder="" value="<?php echo $procedureDetails['384'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad No Progresiva (NP):</label>
          <div class="input-group">
            <input type="text" id="dt-385" name="details[385]" class="form-control" placeholder="" value="<?php echo $procedureDetails['385'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>(NP) ≥ 1</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Inmóviles (IM):</label>
          <div class="input-group">
            <input type="text" id="dt-386" name="details[386]" class="form-control" placeholder="" value="<?php echo $procedureDetails['386'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Inmóviles (IM):</label>
          <div class="input-group">
            <input type="text" id="dt-387" name="details[387]" class="form-control" placeholder="" value="<?php echo $procedureDetails['387'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>(IM) ≤ 20% </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides PR Totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-388" name="details[388]" class="form-control" placeholder="" value="<?php echo $procedureDetails['388'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides PR Totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-389" name="details[389]" class="form-control" placeholder="" value="<?php echo $procedureDetails['389'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label> (PR+NP) ≥ 42%</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Vitalidad:</label>
          <div class="input-group">
            <input type="text" id="dt-390" name="details[390]" class="form-control" placeholder="" value="<?php echo $procedureDetails['390'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Vitalidad:</label>
          <div class="input-group">
            <input type="text" id="dt-391" name="details[391]" class="form-control" placeholder="" value="<?php echo $procedureDetails['391'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≥ 54 % </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides No Vitales:</label>
          <div class="input-group">
            <input type="text" id="dt-392" name="details[392]" class="form-control" placeholder="" value="<?php echo $procedureDetails['392'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <!--
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides No Vitales:</label>
          <div class="input-group">
            <input type="text" id="dt-393" name="details[393]" class="form-control" placeholder="" value="<?php echo $procedureDetails['393'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
              -->
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Total espermatozoides vitales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-394" name="details[394]" class="form-control" placeholder="" value="<?php echo $procedureDetails['394'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <!--
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Total espermatozoides vitales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-395" name="details[395]" class="form-control" placeholder="" value="<?php echo $procedureDetails['395'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
              -->
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">REM:<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Recuento de Espermatozoides Móviles"></i></label>
          <div class="input-group">
            <input type="text" id="dt-396" name="details[396]" class="form-control" placeholder="" value="<?php echo $procedureDetails['396'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <!--
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">REM:</label>
          <div class="input-group">
            <input type="text" id="dt-397" name="details[397]" class="form-control" placeholder="" value="<?php echo $procedureDetails['397'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
              -->

      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Volumen a transferir:</label>
          <div class="input-group">
            <input type="text" id="dt-527" name="details[527]" class="form-control" placeholder="" value="<?php echo $procedureDetails['527'] ?>">
            <div class="input-group-addon">
              <span></span>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Técnica de capacitación:</label>
          <div class="input-group">
            <input type="text" id="dt-528" name="details[528]" class="form-control" placeholder="" value="<?php echo $procedureDetails['528'] ?>">
            <div class="input-group-addon">
              <span></span>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" name="patientAndrologyProcedureId" value="<?php echo $andrologyProcedureId ?>" required>
        </div>
      </div>
    </form>
    <hr>
    <!--
    <div class="row">
      <div class="col-md-3" id="body-lens40x">
        <button type="button" class="btn btn-default btn-sm" id="btnUpload-lens40x" onclick="uploadLensFile(3)" <?php echo ($lens40x) ? "style='display: none';" : ""; ?>>Subir imagen objetivo 40x <i class="fas fa-upload"></i></button>
        <?php if (isset($lens40x)) : ?>
          <a href="<?php echo $lens40x->path; ?>" target="_blank" rel="tooltip" title="Visualizar" class="btn btn-sm btn-primary lens40x-file">Imagen Objetivo 40x <i class="fas fa-eye"></i></a><button class="btn btn-sm btn-danger lens40x-file" onclick="deleteLensFile(3)"><i class="fas fa-trash"></i></button>
        <?php endif; ?>
      </div>
      <div class="col-md-3" id="body-lens100x">
        <button type="button" class="btn btn-default btn-sm" id="btnUpload-lens100x" onclick="uploadLensFile(4)" <?php echo ($lens100x) ? "style='display: none';" : ""; ?>>Subir imagen objetivo 100x <i class="fas fa-upload"></i></button>
        <?php if (isset($lens100x)) : ?>
          <a href="<?php echo $lens100x->path; ?>" target="_blank" rel="tooltip" title="Visualizar" class="btn btn-sm btn-primary lens100x-file">Imagen Objetivo 100x <i class="fas fa-eye"></i></a><button class="btn btn-sm btn-danger lens100x-file" onclick="deleteLensFile(4)"><i class="fas fa-trash"></i></button>
        <?php endif; ?>
      </div>
    </div>
        -->
  </div>
  <!-- /.box-body -->
</div>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Observaciones</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=andrology-procedures/update-procedure-observations" role="form">
      <div class="col-md-12">
        <div class="row">
          <textarea name="observations" class="form-control" rows="2"><?php echo $andrologyProcedure->observations ?></textarea>
        </div>
        <div class="row">
          <div class="col-lg-1 pull-right">
            <br>
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            <input type="hidden" name="patientAndrologyProcedureId" value="<?php echo $andrologyProcedureId ?>" required>
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
  });

  //Guardar archivo de resultados
  function uploadLensFile(sectionId) {
    Swal.fire({
      title: 'Archivo',
      input: 'file',
      inputAttributes: {
        'accept': 'image/*',
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
        var nameFile = "lens";
        if (sectionId == 3) {
          nameFile = "lens40x";
          objectiveName = "40x";
        } else if (sectionId == 4) {
          nameFile = "lens100x";
          objectiveName = "100x";
        }
        var formData = new FormData();
        formData.append('andrologyProcedureId', "<?php echo $andrologyProcedureId ?>");
        formData.append('nameFile', nameFile);
        formData.append('imageSectionId', sectionId);

        var file = $('.swal2-file')[0].files[0];
        formData.append("file", file);

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "POST",
          url: "./?action=andrology-procedures/add-file",
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
            let htmlFile = '<a href = "' + data + '" target = "_blank" rel = "tooltip" title = "Visualizar" class = "btn btn-sm btn-primary ' + nameFile + '-file"> Imagen Objetivo ' + objectiveName + '<i class="fas fa-eye"> </i></a><button class="btn btn-sm btn-danger ' + nameFile + '-file" onclick="deleteLensFile(' + sectionId + ')"><i class="fas fa-trash"></i></button>';
            $("." + nameFile + "-file").remove();
            $("#body-" + nameFile).append(htmlFile);
            $("#btnUpload-" + nameFile).hide();
          }
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    });
  }

  //Eliminar archivo de resultados
  function deleteLensFile(sectionId) {
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
        var nameFile = "lens";
        if (sectionId == 3) {
          nameFile = "lens40x";
        } else if (sectionId == 4) {
          nameFile = "lens100x";
        }

        $.ajax({
          type: "POST",
          url: "./?action=andrology-procedures/delete-file",
          data: {
            andrologyProcedureId: "<?php echo $andrologyProcedureId ?>",
            imageSectionId: sectionId
          },
          error: function() {
            Swal.fire(
              '¡Oops!',
              'No se ha podido eliminar el archivo.',
              'error'
            )
          },
          success: function(data) {
            $("." + nameFile + "-file").remove();
            $("#btnUpload-" + nameFile).show();
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
          treatmentId: null,
          andrologyProcedureId: andrologyProcedureId,
          destinationAndrologyProcedureId: <?php echo $andrologyProcedureId ?>,
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