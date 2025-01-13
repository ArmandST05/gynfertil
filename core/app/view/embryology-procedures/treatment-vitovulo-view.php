<?php
/*-------------VITOVULO (VITRIFICACIÓN DE ÓVULOS) (10)----------*/
//Obtener el procedimiento primario si lo tiene.
$parentProcedure = PatientCategoryData::getById($embryologyProcedure->primary_treatment_id);
?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Datos del Procedimiento</h3>
    <div class="pull-right">
      <!--<a href='./?action=gynecology_procedures/reportProcedurePdf&id=<?php echo $embryologyProcedureId ?>' class='btn btn-default btn-xs'><i class="fas fa-eye"></i> Vista Previa</a>-->
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <?php if ($parentProcedure) : ?>
      <div class="callout callout-default">
        <p><i class="fas fa-info-circle"></i> Este VITOVULO es un subcódigo de <a href="index.php?view=embryology-procedures/details&treatmentId=<?php echo $parentProcedure->id; ?>" target="_blank" rel="tooltip" title="Visualizar" class="btn btn-primary btn-sm"><?php echo $parentProcedure->treatment_code ?></a></p>
      </div>
    <?php endif; ?>
    <form method="POST" action="index.php?action=embryology-procedures/updateProcedure" role="form" id="formUpdateProcedureDetails">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Ciclo:</label>
            <input type="text" id="dt-29" name="details[29]" class="form-control" placeholder="Ciclo" value="<?php echo $actualCycle ?>" readonly>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha de estimulación:</label>
            <input type="text" id="dt-30" name="details[30]" class="form-control" placeholder="Fecha de estimulación" value="<?php echo $embryologyProcedure->getDateMonthFormat($embryologyProcedure->start_date) ?>" readonly>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha de aspiración folicular:</label>
            <input type="date" id="dt-31" name="details[31]" class="form-control" placeholder="Fecha de aspiración folicular" value="<?php echo $procedureDetails['31'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Aspiración - Estradiol:</label>
            <input type="text" id="dt-32" name="details[32]" class="form-control" placeholder="Aspiración - Estradiol" value="<?php echo $procedureDetails['32'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento femenino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[49]" value="1" <?php echo ($procedureDetails['49'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[49]" value="0" <?php echo ($procedureDetails['49'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>

        <div class="col-md-9">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Diagnóstico:</label>
            <input type="text" id="dt-33" name="details[33]" class="form-control" placeholder="Diagnóstico" value="<?php echo $treatmentDiagnostics ?>" readonly>
          </div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Óvulos recuperados:</label>
            <input type="number" min="0" id="dt-34" name="details[34]" class="form-control" placeholder="Óvulos recuperados" value="<?php echo $procedureDetails['34'] ?>">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cantidad de folículos:</label>
            <input type="number" min="0" id="dt-35" name="details[35]" class="form-control" placeholder="Cantidad de foliculos" value="<?php echo $procedureDetails['35'] ?>">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MI:</label>
            <input type="number" min="0" id="dt-36" name="details[36]" class="form-control" placeholder="MI" value="<?php echo $procedureDetails['36'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MII:</label>
            <input type="number" min="0" id="dt-37" name="details[37]" class="form-control" placeholder="MII" value="<?php echo $procedureDetails['37'] ?>" required <?php echo (!isset($parentProcedure) && $procedureDetails['37'] != "" && $procedureDetails['37'] != 0) ? "readonly" : "" ?>>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Vesícula germinal:</label>
            <input type="number" min="0" id="dt-38" name="details[38]" class="form-control" placeholder="Vesícula germinal" value="<?php echo $procedureDetails['38'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Degenerado:</label>
            <input type="number" min="0" id="dt-39" name="details[39]" class="form-control" placeholder="Degenerado" value="<?php echo $procedureDetails['39'] ?>">
          </div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha vitrificación:</label>
            <input type="date" id="dt-40" name="details[40]" required class="form-control" placeholder="Fecha vitrificación" value="<?php echo $procedureDetails['40'] ?>" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Número de dispositivo:</label>
            <input type="text" id="dt-43" name="details[43]" required class="form-control" placeholder="Número de dispositivo" value="<?php echo $procedureDetails['43'] ?>" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cesta:</label>
            <input type="text" id="dt-44" name="details[44]" required class="form-control" placeholder="Cesta" value="<?php echo $procedureDetails['44'] ?>" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Tanque:</label>
            <input type="text" id="dt-45" name="details[45]" required class="form-control" placeholder="Tanque" value="<?php echo $procedureDetails['45'] ?>" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Varilla:</label>
            <input type="text" id="dt-497" name="details[497]" required class="form-control" placeholder="Tanque" value="<?php echo $procedureDetails['497'] ?>" autofocus>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Hora:</label>
            <div class="input-group">
              <input type="text" class="form-control timepicker" id="dt-46" name="details[46]" value="<?php echo $procedureDetails['46'] ?>">
              <div class="input-group-addon">
                <i class="far fa-clock"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Embriólogo:</label>
            <select id="embryoTransferEmbryologist" name="embryologist" class="form-control" id="dt-47" name="details[47]">
              <?php foreach ($medics as $medic) : ?>
                <option value="<?php echo $medic->id; ?>" <?php echo ($procedureDetails['47'] == $medic->id) ? "selected" : "" ?>><?php echo $medic->id . " - " . $medic->name; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Cantidad de óvulos vitrificados:</label>
            <input type="text" id="dt-48" name="details[48]" required class="form-control" placeholder="Cantidad de óvulos vitrificados" value="<?php echo $procedureDetails['48'] ?>" autofocus>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-1 pull-right">
          <br>
          <button type="button" onclick="updateProcedureDetails()" class="btn btn-primary btn-sm">Guardar</button>
          <input type="hidden" id="patientCategoryTreatmentId" name="patientCategoryTreatmentId" value="<?php echo $_GET["treatmentId"] ?>" required>
          <input type="hidden" id="isParentProcedure" name="isParentProcedure" value="<?php echo (isset($parentProcedure)) ? "1" : "0" ?>" required>
          <input type="hidden" name="typeId" value="2" required>
        </div>
      </div>
    </form>

    <div class="row">
      <div class="col-md-12">
        <table id="ovuleVitrificationDataTable" class="cell-border compact" style="width:100%">
          <thead>
            <tr>
              <th rowspan="2">#</th>
              <?php foreach ($sections as $sectionVitrification) : ?>
                <th colspan="<?php echo $sectionVitrification->total_section_details ?>"><?php echo $sectionVitrification->name ?></th>
              <?php endforeach; ?>
              <th colspan="2">DESTINO</th>
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sectionDetails as $sectionVitrificationDetail) : ?>
                <th><?php echo $sectionVitrificationDetail->name ?></th>
              <?php endforeach; ?>
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
                  <td data-section-detail-id="<?php echo $ovuleValue->ovule_section_detail_id ?>" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-' . $ovuleValue->ovule_section_detail_id ?>" style="background-color:<?php echo (($ovuleValue->ovule_section_detail_id == "58" || $ovuleValue->ovule_section_detail_id == "60") && $ovuleValue->value != "") ? $ovuleValue->value : '#FFFFFF' ?>">
                    <?php if ($ovuleValue->ovule_section_detail_id != "58" && $ovuleValue->ovule_section_detail_id != "60") : ?>
                      <?php echo $ovuleValue->value ?>
                    <?php endif;
                    ?>
                  </td>
                <?php endforeach; ?>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d2" data-section-detail-id="d2" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d2' ?>"><?php echo ($ovule->end_ovule_status_id == 2) ? "X" : "" ?></td>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d4" data-section-detail-id="d4" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d4' ?>"><?php echo ($ovule->end_ovule_status_id == 4) ? "X" : "" ?></td>
                <td class="image-<?php echo $ovule->id ?>" data-section-detail-id="image" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-image' ?>">
                  <?php if ($image) : ?>
                    <a href='<?php echo $image->path ?>' target='_blank' class="btn btn-default btn-sm"><i class='fas fa-image'></i></a>
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
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sections as $sectionVitrification) : ?>
                <th colspan="<?php echo $sectionVitrification->total_section_details ?>"><?php echo $sectionVitrification->name ?></th>
              <?php endforeach; ?>
              <th colspan="2">DESTINO</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    </form>
    <hr>
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
        <div class="col-md-12">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Observaciones:</label>
            <input type="text" id="dt-524" name="details[524]" class="form-control" value="<?php echo $procedureDetails['524'] ?>">
          </div>
        </div>
        <div class="col-md-12">
          <div class="checkbox" id="imagesList">
            <?php
            $arraySelectedImages = explode(",", $procedureDetails['461']);
            foreach ($ovuleImages as $ovuleImage) :
            ?>
              <label id="lblImageList<?php echo $ovuleImage->procedure_ovule_id ?>">
                <input name="details[461][]" id="details[461]" type="checkbox" value="<?php echo $ovuleImage->procedure_ovule_id ?>" <?php echo (in_array($ovuleImage->procedure_ovule_id, $arraySelectedImages)) ? "checked" : "" ?>>
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
  function format(d) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
      '<tr>' +
      '<td>Imágenes agregadas:</td>' +
      '<td>' + d.name + '</td>' +
      '</tr>' +
      '</table>';
  }

  $(document).ready(function() {
    //Datatable detalle de óvulos vitirficados
    var ovuleVitrificationDataTable = $('#ovuleVitrificationDataTable').DataTable({
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

    //Activar el modal de edición al hacer clic en una celda de vitrificación de embriones
    //:not(:nth-child(2))
    $('#ovuleVitrificationDataTable').on('click', 'tbody td:not(:first-child)', function(e) {
      var sectionDetailId = $(this).attr("data-section-detail-id");
      var procedureOvuleId = $(this).attr("data-procedure-ovule-id");
      var treatmentId = "<?php echo $embryologyProcedureId ?>";
      var trParent = $(this).closest('tr');
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
                    let htmlImageList = '<label id="lblImageList' + procedureOvuleId + '"><input name="details[461][]" id="details[461]" type="checkbox" value="' + procedureOvuleId + '"><a href="' + data + '" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-image"></i>' + procedureOvuleCode + '</a></label>';
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
      } else if (sectionDetailId == "58" || sectionDetailId == "60") {
        let actualColor = $("#" + procedureOvuleId + "-" + sectionDetailId).css("background-color");
        actualColor = rgb2hex(actualColor).toUpperCase();
        Swal.fire({
          title: 'Editar',
          html: '<input type="color" class="form-control" id="vitColor-' + sectionDetailId + '-' + procedureOvuleId + '" value="' + actualColor + '">',
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: true,
          preConfirm: () => {
            let value = $("#vitColor-" + sectionDetailId + "-" + procedureOvuleId).val().toUpperCase();
            $.ajax({
              type: "POST",
              url: "./?action=embryology-procedures/updateSectionDetailOvule",
              data: {
                sectionDetailId: sectionDetailId,
                procedureOvuleId: procedureOvuleId,
                value: value
              },
              error: function() {
                Swal.fire(
                  'Error',
                  'No se pudo cancelar el tratamiento..',
                  'error'
                )
              },
              success: function(data) {
                $("#" + procedureOvuleId + "-" + sectionDetailId).css("background-color", value);
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      } else if (sectionDetailId == "d2" || sectionDetailId == "d4") {
        //Destinos
        var phaseId = trParent.attr("data-section-phase-id");
        let options = {};
        /*El segundo parámetro es la fase (1) Óvulo (2) Embriones */
        if (sectionDetailId == "d2") {
          //Vitrificación de Embriones u Óvulos
          options['2-1'] = "Óvulo - C (Vitrificación)";
        } else if (sectionDetailId == "d4") {
          options['4-1'] = "Óvulo - NV (No viable)";
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

                //Al actualizar el destino del óvulo cambiar el color de fondo de las celdas
                $(".destination-" + procedureOvuleId).removeClass("bg-danger");
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
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      }
    });


    $("#dt-46").timepicker({});
  });

  //Actualizar datos generales del procedimiento, validar los óvulos recuperados, ya que no se podrá editar.}
  function updateProcedureDetails() {
    let totalOvulesMii = $("#dt-37").val();
    if ("<?php echo isset($parentProcedure->id) ?>" == false && ("<?php echo $procedureDetails['37'] ?>" == '' || "<?php echo $procedureDetails['37'] ?>" == 0)) {
      Swal.fire({
        title: '¿Deseas registrar ' + totalOvulesMii + ' óvulos como MII?',
        text: "Esta acción no se podrá revertir y se cargarán los óvulos a la tabla de detalles.",
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
</script>