<?php
/*-------------FRAG (5)----------*/
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
        <div class="col-md-12">
          <label>El análisis de Fragmentación del DNA indirecta, se basa en la prueba de dispersión de cromatina espermática (SCD) (Fernández et al., J. Androl 24: 59-66, 2003; Fertil Steril 84: 833-842, 2005). </label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Doctor:</label>
            <input type="text" id="dt-medicName" name="details[medicName]" class="form-control" placeholder="" value="<?php echo $andrologyProcedure->medic_name ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Días de abstinencia:</label>
          <div class="form-group">
            <input type="text" id="dt-316" name="details[316]" class="form-control" placeholder="" value="<?php echo $procedureDetails['316'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Porcentaje en células espermáticas:</label>
          <div class="input-group">
            <input type="text" id="dt-317" name="details[317]" class="form-control" placeholder="" value="<?php echo $procedureDetails['317'] ?>">
            <div class="input-group-addon">
              <span>%</span>
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