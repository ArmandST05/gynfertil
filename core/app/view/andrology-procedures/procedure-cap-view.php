<?php
/*-------------CAPACITACIÓN ESPERMÁTICA (6)----------*/
$lens40x = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 3);
$lens100x = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 4);
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
            <input type="text" id="dt-276" name="details[276]" class="form-control" placeholder="" value="<?php echo $procedureDetails['276'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Recolección:</label>
            <input type="text" id="dt-277" name="details[277]" class="form-control" placeholder="" value="<?php echo $procedureDetails['277'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Procesamiento:</label>
            <input type="text" id="dt-278" name="details[278]" class="form-control" placeholder="" value="<?php echo $procedureDetails['278'] ?>">
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
                <input type="radio" name="details[313]" value="1" <?php echo ($procedureDetails['313'] == 1) ? "checked" : "" ?>>Fresco
              </label>
              <label>
                <input type="radio" name="details[313]" value="0" <?php echo ($procedureDetails['313'] == 0) ? "checked" : "" ?>>Descongelado
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Código Congelación (SPERMFREEZING):</label>
            <input type="text" id="dt-314" name="details[314]" class="form-control" placeholder="" value="<?php echo $procedureDetails['314'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cantidad de dispositivos:</label>
            <input type="text" id="dt-315" name="details[315]" class="form-control" placeholder="" value="<?php echo $procedureDetails['315'] ?>">
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
            <input type="text" id="dt-279" name="details[279]" class="form-control" placeholder="" value="<?php echo $procedureDetails['279'] ?>">
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
            <input type="text" id="dt-280" name="details[280]" class="form-control" placeholder="" value="<?php echo $procedureDetails['280'] ?>">
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
            <input type="text" id="dt-281" name="details[281]" class="form-control" placeholder="" value="<?php echo $procedureDetails['281'] ?>">
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
            <input type="text" id="dt-282" name="details[282]" class="form-control" placeholder="" value="<?php echo $procedureDetails['282'] ?>">
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
            <input type="text" id="dt-283" name="details[283]" class="form-control" placeholder="" value="<?php echo $procedureDetails['283'] ?>">
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
            <input type="text" id="dt-284" name="details[284]" class="form-control" placeholder="" value="<?php echo $procedureDetails['284'] ?>">
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
            <input type="text" id="dt-285" name="details[285]" class="form-control" placeholder="" value="<?php echo $procedureDetails['285'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Concentración (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-286" name="details[286]" class="form-control" placeholder="" value="<?php echo $procedureDetails['286'] ?>">
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
            <input type="text" id="dt-287" name="details[287]" class="form-control" placeholder="" value="<?php echo $procedureDetails['287'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-288" name="details[288]" class="form-control" placeholder="" value="<?php echo $procedureDetails['288'] ?>">
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
            <input type="text" id="dt-289" name="details[289]" class="form-control" placeholder="" value="<?php echo $procedureDetails['289'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Células redondas (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-290" name="details[290]" class="form-control" placeholder="" value="<?php echo $procedureDetails['290'] ?>">
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
            <input type="text" id="dt-291" name="details[291]" class="form-control" placeholder="" value="<?php echo $procedureDetails['291'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Leucocitos:</label>
          <div class="input-group">
            <input type="text" id="dt-292" name="details[292]" class="form-control" placeholder="" value="<?php echo $procedureDetails['292'] ?>">
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
            <input type="text" id="dt-293" name="details[293]" class="form-control" placeholder="" value="<?php echo $procedureDetails['293'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Aglutinación:</label>
          <div class="input-group">
            <input type="text" id="dt-294" name="details[294]" class="form-control" placeholder="" value="<?php echo $procedureDetails['294'] ?>">
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
            <input type="text" id="dt-295" name="details[295]" class="form-control" placeholder="" value="<?php echo $procedureDetails['295'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Otros:</label>
            <input type="text" id="dt-296" name="details[296]" class="form-control" placeholder="" value="<?php echo $procedureDetails['296'] ?>">
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
            <input type="text" id="dt-297" name="details[297]" class="form-control" placeholder="" value="<?php echo $procedureDetails['297'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad Progresiva (PR):</label>
          <div class="input-group">
            <input type="text" id="dt-298" name="details[298]" class="form-control" placeholder="" value="<?php echo $procedureDetails['298'] ?>">
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
            <input type="text" id="dt-299" name="details[299]" class="form-control" placeholder="" value="<?php echo $procedureDetails['299'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad No Progresiva (NP):</label>
          <div class="input-group">
            <input type="text" id="dt-300" name="details[300]" class="form-control" placeholder="" value="<?php echo $procedureDetails['300'] ?>">
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
            <input type="text" id="dt-301" name="details[301]" class="form-control" placeholder="" value="<?php echo $procedureDetails['301'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Inmóviles (IM):</label>
          <div class="input-group">
            <input type="text" id="dt-302" name="details[302]" class="form-control" placeholder="" value="<?php echo $procedureDetails['302'] ?>">
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
            <input type="text" id="dt-303" name="details[303]" class="form-control" placeholder="" value="<?php echo $procedureDetails['303'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides PR Totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-304" name="details[304]" class="form-control" placeholder="" value="<?php echo $procedureDetails['304'] ?>">
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
            <input type="text" id="dt-305" name="details[305]" class="form-control" placeholder="" value="<?php echo $procedureDetails['305'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Vitalidad:</label>
          <div class="input-group">
            <input type="text" id="dt-306" name="details[306]" class="form-control" placeholder="" value="<?php echo $procedureDetails['306'] ?>">
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
            <input type="text" id="dt-307" name="details[307]" class="form-control" placeholder="" value="<?php echo $procedureDetails['307'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides No Vitales:</label>
          <div class="input-group">
            <input type="text" id="dt-308" name="details[308]" class="form-control" placeholder="" value="<?php echo $procedureDetails['308'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Total espermatozoides vitales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-309" name="details[309]" class="form-control" placeholder="" value="<?php echo $procedureDetails['309'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Total espermatozoides vitales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-310" name="details[310]" class="form-control" placeholder="" value="<?php echo $procedureDetails['310'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">REM:<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Recuento de Espermatozoides Móviles"></i></label>
          <div class="input-group">
            <input type="text" id="dt-311" name="details[311]" class="form-control" placeholder="" value="<?php echo $procedureDetails['311'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">REM:</label>
          <div class="input-group">
            <input type="text" id="dt-312" name="details[312]" class="form-control" placeholder="" value="<?php echo $procedureDetails['312'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <!--<div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Volumen a transferir:</label>
          <div class="input-group">
            <input type="text" id="dt-529" name="details[529]" class="form-control" placeholder="" value="<?php echo $procedureDetails['529'] ?>">
            <div class="input-group-addon">
              <span></span>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Técnica de capacitación:</label>
          <div class="input-group">
            <input type="text" id="dt-530" name="details[530]" class="form-control" placeholder="" value="<?php echo $procedureDetails['530'] ?>">
            <div class="input-group-addon">
              <span></span>
            </div>
          </div>
        </div>
      </div>-->
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" name="patientAndrologyProcedureId" value="<?php echo $andrologyProcedureId ?>" required>
        </div>
      </div>
    </form>
    <hr>
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

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Diagnóstico</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=andrology-procedures/update-procedure-diagnostic" role="form">
      <div class="col-md-12">
        <div class="row">
          <textarea name="diagnostic" class="form-control" rows="2"><?php echo $andrologyProcedure->diagnostic ?></textarea>
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