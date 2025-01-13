<?php
/*-------------DONOVO (DONADORA DE EMBRIONES)(12)----------*/
/*Obtener el código de vitrificación si se realizó ese subtratamiento de fertilidad*/
$subTreatment = PatientCategoryData::getSubTreatmentById($embryologyProcedureId);
$subTreatmentId = ($subTreatment) ? $subTreatment->id : 0;
?>
<input type="hidden" id="subTreatmentId" class="form-control" value="<?php echo $subTreatmentId ?>">
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
            <label for="inputEmail1" class="control-label">Código Donadora:</label>
            <input type="text" class="form-control" placeholder="Código Donadora" value="<?php echo $patient->donor_id ?>" readonly>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Fecha de aspiración folicular:</label>
            <input type="date" id="dt-107" name="details[107]" class="form-control" placeholder="Fecha de aspiración folicular" value="<?php echo $procedureDetails['107'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Aspiración - Estradiol:</label>
            <input type="text" id="dt-108" name="details[108]" class="form-control" placeholder="Aspiración - Estradiol" value="<?php echo $procedureDetails['108'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label for="inputEmail1" class="control-label">Consentimiento femenino:</label>
          <div class="form-group">
            <div class="radio">
              <label>
                <input type="radio" name="details[109]" value="1" <?php echo ($procedureDetails['109'] == 1) ? "checked" : "" ?>>Sí
              </label>
              <label>
                <input type="radio" name="details[109]" value="0" <?php echo ($procedureDetails['109'] == 0) ? "checked" : "" ?>>No
              </label>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Diagnóstico:</label>
            <input type="text" class="form-control" placeholder="Diagnóstico" value="<?php echo $treatmentDiagnostics ?>" readonly>
          </div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Óvulos recuperados:</label>
            <input type="number" min="0" id="dt-110" name="details[110]" class="form-control" placeholder="Óvulos recuperados" value="<?php echo $procedureDetails['110'] ?>" required <?php echo ($procedureDetails['110'] != "" && $procedureDetails['110'] != 0) ? "readonly" : "" ?>>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MI:</label>
            <input type="number" min="0" id="dt-111" name="details[111]" class="form-control" placeholder="MI" value="<?php echo $procedureDetails['111'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">MII:</label>
            <input type="number" min="0" id="dt-112" name="details[112]" class="form-control" placeholder="MII" value="<?php echo $procedureDetails['112'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Vesícula germinal:</label>
            <input type="number" min="0" id="dt-113" name="details[113]" class="form-control" placeholder="Vesícula germinal" value="<?php echo $procedureDetails['113'] ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Degenerado:</label>
            <input type="number" min="0" id="dt-114" name="details[114]" class="form-control" placeholder="Degenerado" value="<?php echo $procedureDetails['114'] ?>">
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
      <div class="col-md-12">
        <table id="ovulesDataTable" class="cell-border compact" style="width:100%">
          <thead>
            <tr>
              <th rowspan="2">#</th>
              <?php foreach ($sections as $section) : ?>
                <th colspan="<?php echo $section->total_section_details ?>"><?php echo $section->name ?></th>
              <?php endforeach; ?>
              <th colspan="3">DESTINO</th>
              <th rowspan="2">IMAGEN</th>
            </tr>
            <tr>
              <?php foreach ($sectionDetails as $sectionDetail) : ?>
                <th><?php echo $sectionDetail->name ?></th>
              <?php endforeach; ?>
              <th>CULTIVO</th>
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
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d6" data-section-detail-id="d6" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d6' ?>"><?php echo ($ovule->end_ovule_status_id == 6) ? "X" : "" ?></td>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d2" data-section-detail-id="d2" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d2' ?>"><?php echo ($ovule->end_ovule_status_id == 2) ? "X" : "" ?></td>
                <td class="<?php echo $destinationColor ?> destination-<?php echo $ovule->id ?> d4" data-section-detail-id="d4" data-procedure-ovule-id="<?php echo $ovule->id ?>" id="<?php echo $ovule->id . '-d4' ?>"><?php echo ($ovule->end_ovule_status_id == 4) ? "X" : "" ?></td>
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
              <th rowspan="2">#</th>
              <?php foreach ($sectionDetails as $sectionDetail) : ?>
                <th><?php echo $sectionDetail->name ?></th>
              <?php endforeach; ?>
              <th>CULTIVO</th>
              <th>C</th>
              <th>NV</th>
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
          <input type="text" id="dt-115" name="details[115]" class="form-control" placeholder="No viables" value="0" readonly>
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
        <div class="col-md-12">
          <div class="form-group">
            <label for="inputEmail1" class="control-label">Observaciones:</label>
            <input type="text" id="dt-474" name="details[474]" class="form-control" value="<?php echo $procedureDetails['474'] ?>">
          </div>
        </div>
        <div class="col-md-12">
          <div class="checkbox" id="imagesList">
            <?php
            $arraySelectedImages = explode(",", $procedureDetails['473']);
            foreach ($ovuleImages as $ovuleImage) :
            ?>
              <label id="lblImageList<?php echo $ovuleImage->procedure_ovule_id ?>">
                <input name="details[473][]" id="details[473]" type="checkbox" value="<?php echo $ovuleImage->procedure_ovule_id ?>" <?php echo (in_array($ovuleImage->procedure_ovule_id, $arraySelectedImages)) ? "checked" : "" ?>>
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
<div class="box box-primary" id="boxOvuleVitrification">
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
                    let htmlImageList = '<label id="lblImageList' + procedureOvuleId + '"><input name="details[473][]" id="details[473]" type="checkbox" value="' + procedureOvuleId + '"><a href="' + data + '" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-image"></i>' + procedureOvuleCode + '</a></label>';
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
      } else if (sectionDetailId == "d2" || sectionDetailId == "d4" || sectionDetailId == "d6") {
        //Destinos
        var phaseId = trParent.attr("data-section-phase-id");
        let options = {};
        /*El segundo parámetro es la fase (1) Óvulo (2) Embriones */
        if (sectionDetailId == "d2") {
          //Vitrificación de Embriones u Óvulos
          options['2-1'] = "Óvulo - C (Vitrificación)";
        } else if (sectionDetailId == "d4") {
          options['4-1'] = "Óvulo - NV (No viable)";
        } else if (sectionDetailId == "d6") {
          options['6-1'] = "Óvulo - Cultivo ";
        }
        //DESTINO (C-2, NV-4, CULTIVO 6)
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

                calculateTotalOvulesData();
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });

      } else if (sectionDetailId == "152") {
        //Definir receptoras
        Swal.fire({
          title: 'Receptora',
          html: '<select id="selectReceptor-' + sectionDetailId + '-' + procedureOvuleId + '" class="form-control selectReceptor"></select>',
          showCancelButton: true,
          confirmButtonText: 'Guardar',
          cancelButtonText: 'Cancelar',
          showLoaderOnConfirm: false,
          inputValidator: (value) => {
            if (!value) {
              return '¡Selecciona un receptora!'
            }
          },
          didOpen: function() {
            $('.selectReceptor').select2({
              width: '100%',
              placeholder: "Escribe el código del tratamiento",
              minimumInputLength: 0,
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
          },
          preConfirm: () => {
            $('.selectReceptor').select2({
              width: '100%',
              placeholder: "Escribe el código del tratamiento",
              minimumInputLength: 0,
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
            let value = $("#selectReceptor-" + sectionDetailId + "-" + procedureOvuleId).select2('data');
            value = (value[0]) ? value[0].text : "";
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
                $("#" + procedureOvuleId + "-" + sectionDetailId).text(value);
              }
            });
          }
          /*,
                    allowOutsideClick: () => !Swal.isLoading()*/
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
              }
            });
          },
          allowOutsideClick: () => !Swal.isLoading()
        });
      }
    });

    //Al cargar la página mostrar totales
    calculateTotalOvulesData();
  });

  //Actualizar datos generales del procedimiento, validar los óvulos recuperados, ya que no se podrá editar.
  function updateProcedureDetails() {
    let totalOvulesRecovered = $("#dt-110").val();
    if ("<?php echo $procedureDetails['110'] ?>" == '' || "<?php echo $procedureDetails['110'] ?>" == 0) {
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
    //No válidos
    var totalNonValidate = 0;
    $(".d4").each(function(td) {
      if ($(this).text() == "X") {
        totalNonValidate++;
      }
    });
    $("#dt-115").val(totalNonValidate);
    //Vitrificación de óvulos
    var totalOvuleVitrification = 0;
    $(".d2").each(function(td) {
      var trParent = $(this).closest('tr');
      if ($(this).text() == "X" && trParent.attr("data-section-phase-id") == 1) {
        totalOvuleVitrification++;
      }
    });
    $("#totalOvuleVitrification").text("Total: " + totalOvuleVitrification);
    if (totalOvuleVitrification > 0) {
      $("#boxOvuleVitrification").show();
      //En FIVTE y DONOVO, si hay óvulos vitrificados y no se ha creado código de vitrificación mostrar el botón de crear
      if ($("#subTreatmentId").val() == 0) {
        $("#btnSaveSubTreatment").show();
      }
    } else {
      //En FIVTE y DONOVO, si no se ha creado un código de vitrificación y no hay óvulos vitrificados ocultar el botón de crear
      if ($("#subTreatmentId").val() == 0) {
        $("#boxOvuleVitrification").hide();
        $("#btnSaveSubTreatment").hide();
      }
    }
  }

  //Crear subcódigo de vitrificación VITOVULO
  function saveSubTreatment() {
    Swal.fire({
      title: '¿Deseas generar un código VITOVULO asociado a este DONOVO? Hazlo sólo cuando tengas especificados TODOS los óvulos vitrificados en la tabla de detalles',
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
</script>