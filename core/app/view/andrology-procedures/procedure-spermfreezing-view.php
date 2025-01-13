<?php
/*-------------SPERMFREEEZING (2)----------*/
$lens40x = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 3);
$lens100x = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 4);

//Obtener el procedimiento primario si lo tiene.
$parentProcedure = AndrologyProcedureData::getPatientProcedureById($andrologyProcedure->primary_procedure_id);
?>

<!--COLOR-PICKER -->
<!--<link href="plugins/bootstrap/css/bootstrap-colorpicker.min.css" rel="stylesheet" />-->
<script src="plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!--COLOR-PICKER -->

<div class="box box-primary">
  <div class="box-header with-border">
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <?php if($parentProcedure):?>
    <div class="callout callout-default">
      <p><i class="fas fa-info-circle"></i> Este SPERMFREEZING es un subcódigo de <a href="index.php?view=andrology-procedures/details&procedureId=<?php echo $parentProcedure->id; ?>" target="_blank" rel="tooltip" title="Visualizar" class="btn btn-primary btn-sm"><?php echo $parentProcedure->procedure_code ?></a></p>
    </div>
    <?php endif;?>
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
            <input type="text" id="dt-213" name="details[213]" class="form-control" placeholder="" value="<?php echo $procedureDetails['213'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Recolección:</label>
            <input type="text" id="dt-214" name="details[214]" class="form-control" placeholder="" value="<?php echo $procedureDetails['214'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Procesamiento:</label>
            <input type="text" id="dt-215" name="details[215]" class="form-control" placeholder="" value="<?php echo $procedureDetails['215'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Donante:</label>
            <input type="text" id="dt-216" name="details[216]" class="form-control" placeholder="" value="<?php echo $procedureDetails['216'] ?>">
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
            <input type="text" id="dt-217" name="details[217]" class="form-control" placeholder="" value="<?php echo $procedureDetails['217'] ?>">
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
            <input type="text" id="dt-218" name="details[218]" class="form-control" placeholder="" value="<?php echo $procedureDetails['218'] ?>">
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
            <input type="text" id="dt-219" name="details[219]" class="form-control" placeholder="" value="<?php echo $procedureDetails['219'] ?>">
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
            <input type="text" id="dt-220" name="details[220]" class="form-control" placeholder="" value="<?php echo $procedureDetails['220'] ?>">
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
            <input type="text" id="dt-221" name="details[221]" class="form-control" placeholder="" value="<?php echo $procedureDetails['221'] ?>">
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
            <input type="text" id="dt-222" name="details[222]" class="form-control" placeholder="" value="<?php echo $procedureDetails['222'] ?>">
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
        <div class="col-md-3">
          <label>VALORES DE REFERENCIA OMS 2021</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Concentración (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-223" name="details[223]" class="form-control" placeholder="" value="<?php echo $procedureDetails['222'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Concentración (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-224" name="details[224]" class="form-control" placeholder="" value="<?php echo $procedureDetails['224'] ?>">
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
            <input type="text" id="dt-225" name="details[225]" class="form-control" placeholder="" value="<?php echo $procedureDetails['225'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-226" name="details[226]" class="form-control" placeholder="" value="<?php echo $procedureDetails['226'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
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
            <input type="text" id="dt-227" name="details[227]" class="form-control" placeholder="" value="<?php echo $procedureDetails['227'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Células redondas (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-228" name="details[228]" class="form-control" placeholder="" value="<?php echo $procedureDetails['228'] ?>">
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
            <input type="text" id="dt-229" name="details[229]" class="form-control" placeholder="" value="<?php echo $procedureDetails['229'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Leucocitos:</label>
          <div class="input-group">
            <input type="text" id="dt-230" name="details[230]" class="form-control" placeholder="" value="<?php echo $procedureDetails['230'] ?>">
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
            <input type="text" id="dt-231" name="details[231]" class="form-control" placeholder="" value="<?php echo $procedureDetails['231'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Aglutinación:</label>
          <div class="input-group">
            <input type="text" id="dt-232" name="details[232]" class="form-control" placeholder="" value="<?php echo $procedureDetails['232'] ?>">
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
            <input type="text" id="dt-233" name="details[233]" class="form-control" placeholder="" value="<?php echo $procedureDetails['233'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Otros:</label>
            <input type="text" id="dt-234" name="details[234]" class="form-control" placeholder="" value="<?php echo $procedureDetails['234'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label></label>
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
        <div class="col-md-3">
          <label>VALORES DE REFERENCIA OMS 2021</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad Progresiva (PR):</label>
          <div class="input-group">
            <input type="text" id="dt-235" name="details[235]" class="form-control" placeholder="" value="<?php echo $procedureDetails['235'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad Progresiva (PR):</label>
          <div class="input-group">
            <input type="text" id="dt-236" name="details[236]" class="form-control" placeholder="" value="<?php echo $procedureDetails['236'] ?>">
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
            <input type="text" id="dt-237" name="details[237]" class="form-control" placeholder="" value="<?php echo $procedureDetails['237'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad No Progresiva (NP):</label>
          <div class="input-group">
            <input type="text" id="dt-238" name="details[238]" class="form-control" placeholder="" value="<?php echo $procedureDetails['238'] ?>">
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
            <input type="text" id="dt-239" name="details[239]" class="form-control" placeholder="" value="<?php echo $procedureDetails['239'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Inmóviles (IM):</label>
          <div class="input-group">
            <input type="text" id="dt-240" name="details[240]" class="form-control" placeholder="" value="<?php echo $procedureDetails['240'] ?>">
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
            <input type="text" id="dt-241" name="details[241]" class="form-control" placeholder="" value="<?php echo $procedureDetails['241'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides PR Totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-242" name="details[242]" class="form-control" placeholder="" value="<?php echo $procedureDetails['242'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>(PR+NP) ≥ 42%</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Vitalidad:</label>
          <div class="input-group">
            <input type="text" id="dt-243" name="details[243]" class="form-control" placeholder="" value="<?php echo $procedureDetails['243'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <!--
          <label for="inputEmail1" class="control-label">Vitalidad:</label>
          <div class="input-group">
            <input type="text" id="dt-244" name="details[244]" class="form-control" placeholder="" value="<?php echo $procedureDetails['244'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
    -->
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
            <input type="text" id="dt-245" name="details[245]" class="form-control" placeholder="" value="<?php echo $procedureDetails['245'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <!--<div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides No Vitales:</label>
          <div class="input-group">
            <input type="text" id="dt-246" name="details[246]" class="form-control" placeholder="" value="<?php echo $procedureDetails['246'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>-->
        <div class="col-md-3">
          <br>
          <label></label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Total espermatozoides vitales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-247" name="details[247]" class="form-control" placeholder="" value="<?php echo $procedureDetails['247'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <!--<div class="col-md-3">
          <label for="inputEmail1" class="control-label">Total espermatozoides vitales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-248" name="details[248]" class="form-control" placeholder="" value="<?php echo $procedureDetails['248'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>-->
        <div class="col-md-3">
          <br>
          <label></label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">REM:<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Recuento de Espermatozoides Móviles"></i></label>
          <div class="input-group">
            <input type="text" id="dt-249" name="details[249]" class="form-control" placeholder="" value="<?php echo $procedureDetails['249'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <!--<div class="col-md-3">
          <label for="inputEmail1" class="control-label">REM*:</label>
          <div class="input-group">
            <input type="text" id="dt-250" name="details[250]" class="form-control" placeholder="" value="<?php echo $procedureDetails['250'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
    -->
        <div class="col-md-3">
          <br>
          <label></label>
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
    </div>-->
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
          <textarea name="observations" class="form-control" rows="3"><?php echo $andrologyProcedure->observations ?></textarea>
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

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Ubicación muestra congelada</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=andrology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cesta:</label>
            <input type="text" id="dt-251" name="details[251]" class="form-control" placeholder="" value="<?php echo $procedureDetails['251'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Tanque:</label>
            <input type="text" id="dt-252" name="details[252]" class="form-control" placeholder="" value="<?php echo $procedureDetails['252'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Varilla:</label>
            <input type="text" id="dt-253" name="details[253]" class="form-control" placeholder="" value="<?php echo $procedureDetails['253'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Color de varilla:</label>
            <input type="color" id="dt-254" name="details[254]" class="form-control" placeholder="" value="<?php echo $procedureDetails['254'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cantidad de dispositivos:</label>
            <input type="number" id="dt-255" name="details[255]" class="form-control" placeholder="" value="<?php echo $procedureDetails['255'] ?>">
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
<script>
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
</script>