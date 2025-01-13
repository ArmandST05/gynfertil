<?php
/*-------------INSEMINACIÓN INTRAUTERINA (7)----------*/
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
            <input type="text" id="dt-318" name="details[318]" class="form-control" placeholder="" value="<?php echo $procedureDetails['318'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Recolección:</label>
            <input type="text" id="dt-319" name="details[319]" class="form-control" placeholder="" value="<?php echo $procedureDetails['319'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Procesamiento:</label>
            <input type="text" id="dt-320" name="details[320]" class="form-control" placeholder="" value="<?php echo $procedureDetails['320'] ?>">
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
                <input type="radio" name="details[321]" value="1" <?php echo ($procedureDetails['321'] == 1) ? "checked" : "" ?>>Fresco
              </label>
              <label>
                <input type="radio" name="details[321]" value="0" <?php echo ($procedureDetails['321'] == 0) ? "checked" : "" ?>>Descongelado
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Descongelado-Código Congelación (SPERMFREEZING):</label>
            <input type="text" id="dt-322" name="details[322]" class="form-control" placeholder="" value="<?php echo $procedureDetails['322'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Descongelado-Cantidad de dispositivos:</label>
            <input type="text" id="dt-323" name="details[323]" class="form-control" placeholder="" value="<?php echo $procedureDetails['323'] ?>">
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
            <input type="text" id="dt-324" name="details[324]" class="form-control" placeholder="" value="<?php echo $procedureDetails['324'] ?>">
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
            <input type="text" id="dt-325" name="details[325]" class="form-control" placeholder="" value="<?php echo $procedureDetails['325'] ?>">
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
            <input type="text" id="dt-326" name="details[326]" class="form-control" placeholder="" value="<?php echo $procedureDetails['326'] ?>">
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
            <input type="text" id="dt-327" name="details[327]" class="form-control" placeholder="" value="<?php echo $procedureDetails['327'] ?>">
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
            <input type="text" id="dt-328" name="details[328]" class="form-control" placeholder="" value="<?php echo $procedureDetails['328'] ?>">
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
            <input type="text" id="dt-329" name="details[329]" class="form-control" placeholder="" value="<?php echo $procedureDetails['329'] ?>">
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
            <input type="text" id="dt-330" name="details[330]" class="form-control" placeholder="" value="<?php echo $procedureDetails['330'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Concentración (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-331" name="details[331]" class="form-control" placeholder="" value="<?php echo $procedureDetails['331'] ?>">
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
            <input type="text" id="dt-332" name="details[332]" class="form-control" placeholder="" value="<?php echo $procedureDetails['332'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-333" name="details[333]" class="form-control" placeholder="" value="<?php echo $procedureDetails['333'] ?>">
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
            <input type="text" id="dt-334" name="details[334]" class="form-control" placeholder="" value="<?php echo $procedureDetails['334'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Células redondas (mill/mL):</label>
          <div class="input-group">
            <input type="text" id="dt-335" name="details[335]" class="form-control" placeholder="" value="<?php echo $procedureDetails['335'] ?>">
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
            <input type="text" id="dt-336" name="details[336]" class="form-control" placeholder="" value="<?php echo $procedureDetails['336'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Leucocitos:</label>
          <div class="input-group">
            <input type="text" id="dt-337" name="details[337]" class="form-control" placeholder="" value="<?php echo $procedureDetails['337'] ?>">
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
            <input type="text" id="dt-338" name="details[338]" class="form-control" placeholder="" value="<?php echo $procedureDetails['338'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Aglutinación:</label>
          <div class="input-group">
            <input type="text" id="dt-339" name="details[339]" class="form-control" placeholder="" value="<?php echo $procedureDetails['339'] ?>">
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
            <input type="text" id="dt-340" name="details[340]" class="form-control" placeholder="" value="<?php echo $procedureDetails['340'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Otros:</label>
            <input type="text" id="dt-341" name="details[341]" class="form-control" placeholder="" value="<?php echo $procedureDetails['341'] ?>">
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
            <input type="text" id="dt-342" name="details[342]" class="form-control" placeholder="" value="<?php echo $procedureDetails['342'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad Progresiva (PR):</label>
          <div class="input-group">
            <input type="text" id="dt-343" name="details[343]" class="form-control" placeholder="" value="<?php echo $procedureDetails['343'] ?>">
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
            <input type="text" id="dt-344" name="details[344]" class="form-control" placeholder="" value="<?php echo $procedureDetails['344'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad No Progresiva (NP):</label>
          <div class="input-group">
            <input type="text" id="dt-345" name="details[345]" class="form-control" placeholder="" value="<?php echo $procedureDetails['345'] ?>">
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
            <input type="text" id="dt-346" name="details[346]" class="form-control" placeholder="" value="<?php echo $procedureDetails['346'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Inmóviles (IM):</label>
          <div class="input-group">
            <input type="text" id="dt-347" name="details[347]" class="form-control" placeholder="" value="<?php echo $procedureDetails['347'] ?>">
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
            <input type="text" id="dt-348" name="details[348]" class="form-control" placeholder="" value="<?php echo $procedureDetails['348'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides PR Totales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-349" name="details[349]" class="form-control" placeholder="" value="<?php echo $procedureDetails['349'] ?>">
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
            <input type="text" id="dt-350" name="details[350]" class="form-control" placeholder="" value="<?php echo $procedureDetails['350'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <!--
          <label for="inputEmail1" class="control-label">Vitalidad:</label>
          <div class="input-group">
            <input type="text" id="dt-351" name="details[351]" class="form-control" placeholder="" value="<?php echo $procedureDetails['351'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>-->
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
            <input type="text" id="dt-352" name="details[352]" class="form-control" placeholder="" value="<?php echo $procedureDetails['352'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <!--
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides No Vitales:</label>
          <div class="input-group">
            <input type="text" id="dt-353" name="details[353]" class="form-control" placeholder="" value="<?php echo $procedureDetails['353'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>-->
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Total espermatozoides vitales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-354" name="details[354]" class="form-control" placeholder="" value="<?php echo $procedureDetails['354'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>
        <!--
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Total espermatozoides vitales (mill):</label>
          <div class="input-group">
            <input type="text" id="dt-355" name="details[355]" class="form-control" placeholder="" value="<?php echo $procedureDetails['355'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ </span>
            </div>
          </div>
        </div>-->
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">REM:<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Recuento de Espermatozoides Móviles"></i></label>
          <div class="input-group">
            <input type="text" id="dt-356" name="details[356]" class="form-control" placeholder="" value="<?php echo $procedureDetails['356'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <!--
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">REM:</label>
          <div class="input-group">
            <input type="text" id="dt-357" name="details[357]" class="form-control" placeholder="" value="<?php echo $procedureDetails['357'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>-->
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Volumen a transferir:</label>
          <div class="input-group">
            <input type="text" id="dt-525" name="details[525]" class="form-control" placeholder="" value="<?php echo $procedureDetails['525'] ?>">
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
            <input type="text" id="dt-526" name="details[526]" class="form-control" placeholder="" value="<?php echo $procedureDetails['526'] ?>">
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