<?php
/*-------------BIOPSIA TESTICULAR(4)----------*/
$lens40x = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 3);
$lens100x = AndrologyProcedureData::getFileByProcedureSectionId($andrologyProcedureId, 4);

/*Obtener el código de vitrificación si se realizó ese subtratamiento de fertilidad*/
$subProcedure = AndrologyProcedureData::getSubProcedureById($andrologyProcedureId);
$subProcedureId = ($subProcedure) ? $subProcedure->id : 0;
?>
<!--COLOR-PICKER -->
<!--<link href="plugins/bootstrap/css/bootstrap-colorpicker.min.css" rel="stylesheet" />-->
<script src="plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!--COLOR-PICKER -->
<input type="hidden" id="subProcedureId" class="form-control" value="<?php echo $subProcedureId ?>">
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
            <label for="inputEmail1" class="control-label">Recolección:</label>
            <input type="text" id="dt-256" name="details[256]" class="form-control" placeholder="" value="<?php echo $procedureDetails['256'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Procesamiento:</label>
            <input type="text" id="dt-257" name="details[257]" class="form-control" placeholder="" value="<?php echo $procedureDetails['257'] ?>">
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
                <input type="radio" name="details[258]" value="1" <?php echo ($procedureDetails['258'] == 1) ? "checked" : "" ?>>Fresco
              </label>
              <label>
                <input type="radio" name="details[258]" value="0" <?php echo ($procedureDetails['258'] == 0) ? "checked" : "" ?>>Congelado
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Código Congelación (SPERMFREEZING):</label>
            <input type="text" id="dt-259" name="details[259]" class="form-control" placeholder="" value="<?php echo $procedureDetails['259'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cantidad de dispositivos:</label>
            <input type="text" id="dt-260" name="details[260]" class="form-control" placeholder="" value="<?php echo $procedureDetails['260'] ?>">
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
    <h3 class="box-title">Aspecto General</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=andrology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Normal y turgente:</label>
            <input type="text" id="dt-261" name="details[261]" class="form-control" placeholder="" value="<?php echo $procedureDetails['261'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Vasectomía:</label>
            <input type="text" id="dt-262" name="details[262]" class="form-control" placeholder="" value="<?php echo $procedureDetails['262'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Diagnótico previo:</label>
            <input type="text" id="dt-263" name="details[263]" class="form-control" placeholder="" value="<?php echo $procedureDetails['263'] ?>">
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
          <label>TESTÍCULO DERECHO</label>
        </div>
        <div class="col-md-3">
          <label>TESTÍCULO IZQUIERDO</label>
        </div>
        <div class="col-md-3">
          <label>VALORES DE REFERENCIA OMS 2021</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Resultado:</label>
          <div class="input-group">
            <input type="text" id="dt-264" name="details[264]" class="form-control" placeholder="" value="<?php echo $procedureDetails['264'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Resultado:</label>
          <div class="input-group">
            <input type="text" id="dt-265" name="details[265]" class="form-control" placeholder="" value="<?php echo $procedureDetails['265'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>Positivo</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides por campo:</label>
          <div class="input-group">
            <input type="text" id="dt-266" name="details[266]" class="form-control" placeholder="" value="<?php echo $procedureDetails['266'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Espermatozoides por campo:</label>
          <div class="input-group">
            <input type="text" id="dt-267" name="details[267]" class="form-control" placeholder="" value="<?php echo $procedureDetails['267'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <br>
          <label>≥ 39 x 10⁶</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Concentración (mill/ml):</label>
          <div class="input-group">
            <input type="text" id="dt-268" name="details[268]" class="form-control" placeholder="" value="<?php echo $procedureDetails['268'] ?>">
            <div class="input-group-addon">
              <span> x 10⁶ /mL</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Concentración (mill/ml):</label>
          <div class="input-group">
            <input type="text" id="dt-269" name="details[269]" class="form-control" placeholder="" value="<?php echo $procedureDetails['269'] ?>">
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
      <hr>
      <div class="row">
        <div class="col-md-3">
          <label>TESTÍCULO DERECHO</label>
        </div>
        <div class="col-md-3">
          <label>TESTÍCULO IZQUIERDO</label>
        </div>
        <div class="col-md-3">
          <label>VALORES DE REFERENCIA OMS 2021</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad Progresiva (PR):</label>
          <div class="input-group">
            <input type="text" id="dt-270" name="details[270]" class="form-control" placeholder="" value="<?php echo $procedureDetails['270'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad Progresiva (PR):</label>
          <div class="input-group">
            <input type="text" id="dt-271" name="details[271]" class="form-control" placeholder="" value="<?php echo $procedureDetails['271'] ?>">
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
            <input type="text" id="dt-272" name="details[272]" class="form-control" placeholder="" value="<?php echo $procedureDetails['272'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Movilidad No Progresiva (NP):</label>
          <div class="input-group">
            <input type="text" id="dt-273" name="details[273]" class="form-control" placeholder="" value="<?php echo $procedureDetails['273'] ?>">
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
            <input type="text" id="dt-274" name="details[274]" class="form-control" placeholder="" value="<?php echo $procedureDetails['274'] ?>">
            <div class="input-group-addon">
              <span>%</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Inmóviles (IM):</label>
          <div class="input-group">
            <input type="text" id="dt-275" name="details[275]" class="form-control" placeholder="" value="<?php echo $procedureDetails['275'] ?>">
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
    <h3 class="box-title">Ubicación muestra congelada</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-lg-12" id="divSaveSubProcedure">
        <?php if (!$subProcedure) : ?>
          <button type="submit" class="btn btn-primary btn-sm" id="btnSaveSubProcedure" onclick="saveSubProcedure()">Generar código de congelación</button>
        <?php else : ?>
          <label class="btn btn-simple btn-primary"><?php echo $subProcedure->procedure_code ?></label><a href="index.php?view=andrology-procedures/details&procedureId=<?php echo $subProcedure->id; ?>" target="_blank" rel="tooltip" title="Editar" class="btn btn-simple btn-default"><i class="fas fa-pencil-alt"></i></a>
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

  //Crear subcódigo de vitrificación VITOVULO
  function saveSubProcedure() {
    Swal.fire({
      title: '¿Deseas generar un código SPERMFREEZING asociado a esta Biopsia Testicular?',
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
          url: "./?action=andrology-procedures/add-sub-procedure",
          type: "POST",
          data: {
            andrologyProcedureId: 2, //Tratamiento SPERMFREEZING
            primaryProcedureId: $("#patientAndrologyProcedureId").val()
          },
          success: function(data) {
            var procedureData = JSON.parse(data);
            $("#subProcedureId").val(procedureData["id"]);
            $("#btnSaveSubProcedure").hide();
            var subProcedureLink = '<label class="btn btn-simple btn-primary">' + procedureData["procedureCode"] + '</label><a href="index.php?view=andrology-procedures/details&procedureId=' + procedureData["id"] + '" target="_blank" rel="tooltip" title="Editar" class="btn btn-simple btn-default"><i class="fas fa-pencil-alt"></i></a>';
            $("#divSaveSubProcedure").append(subProcedureLink);
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Ha ocurrido un error al registrar el procedimiento, recarga la página.'
            });
          }
        });
      }
    })
  }
</script>