<?php
$sexes = PatientData::getAllSexes();
$patients = PatientData::getAll();
?>
<script src="plugins/croppie/js/croppie.js"></script>
<link rel="stylesheet" href="plugins/croppie/css/croppie.css" />

<div class="row">
  <div class="col-md-12">
    <h1>Nuevo Paciente</h1>
    <br>
    <form class="form-horizontal" method="post" enctype="multipart/form-data" id="addpacient" action="index.php?view=addpacient" role="form">

      <div class="form-group">
        <label class="col-lg-2 control-label">Foto de perfil:</label>
        <div class="col-md-6">
          <div id="image_profile" style="width:300px; margin-top:10px"></div>
          <label for="insert_image">
            <a class="btn btn-success" style="margin-left:31px;">Seleccionar </a>
          </label>
          <button type="button" id="rotate_image" class="btn btn-primary rotate_image" data-deg="-90"><span class="glyphicon glyphicon-repeat"></span> Rotar</button>
          <button type="button" id="reset_image" class="btn btn-danger" data-deg="-90"><span class="glyphicon glyphicon-cancel"></span>Eliminar</button>
          <input id="insert_image" type="file" style='display: none;' name="image" accept="image/*" />
        </div>
      </div>

      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Nombre*:</label>
        <div class="col-md-6">
          <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre" autofocus>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Sexo*:</label>
        <div class="col-md-6">
          <select id="sexId" name="sexId" class="form-control">
            <?php foreach ($sexes as $sex) : ?>
              <option value="<?php echo $sex->id; ?>"><?php echo $sex->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <div class="checkbox">
            <label>
              <input name="isRelativeRegistered" id="isRelativeRegistered" type="checkbox" value="1"> Tiene pareja registrada
            </label>
          </div>
        </div>
      </div>
      <div class="form-group" id="divRelativeName">
        <label for="inputEmail1" class="col-lg-2 control-label">Nombre de la pareja:</label>
        <div class="col-md-6">
          <input type="text" name="relativeName" class="form-control" id="relativeName" placeholder="Nombre de la pareja" autofocus>
        </div>
      </div>
      <div class="form-group" id="divRelativeId">
        <label for="inputEmail1" class="col-lg-2 control-label">Pareja registrada:</label>
        <div class="col-lg-6">
          <select name="relativeId" id="relativeId" class="form-control" id="combobox">
            <option value="">-- SELECCIONE -- </option>
            <?php foreach ($patients as $patient) : ?>
              <option value="<?php echo $patient->id; ?>"><?php echo $patient->id . " - " . $patient->name ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="inputEmail1" class="col-lg-2 control-label">Teléfonos:</label>
        <div class="col-lg-2">
          <input type="text" name="tel" required class="form-control" id="tel" placeholder="Teléfono">
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="button" id="addPatient" class="btn btn-primary">Agregar Paciente</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#relativeId").select2({});
    $('#image_profile').hide();
    $('#rotate_image').hide();
    $('#reset_image').hide();

    $("#divRelativeId").hide();

    $image_crop = $('#image_profile').croppie({
      enableExif: true,
      enableOrientation: true,
      viewport: {
        width: 150,
        height: 150,
        type: 'square' //square
      },
      boundary: {
        width: 150,
        height: 150
      },

    });

    $('#insert_image').on('change', function() {
      $('#image_profile').show();
      $('#rotate_image').show();
      $('#reset_image').show();

      var reader = new FileReader();

      reader.onload = function(event) {
        $image_crop.croppie('bind', {
          url: event.target.result
        }).then(function() {});
      }

      reader.readAsDataURL(this.files[0]);
      $('#insertimageModal').modal('show');

    });

    $('#rotate_image').on('click', function(ev) {
      $image_crop.croppie('rotate', parseInt($(this).data('deg')));
    });

    $('#addPatient').click(function(event) {
      var name = $("#name").val();
      var sexId = $("#sexId").val();
      var isRelativeRegistered = $("#isRelativeRegistered").val();
      var relativeName = $("#relativeName").val();
      var relativeId = $("#relativeId").val();
      var tel = $("#tel").val();

      if (name && sexId) {
        if($('#isRelativeRegistered').prop('checked') == false || ($('#isRelativeRegistered').prop('checked') == true && relativeId != null && relativeId != "")){
          $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
          }).then(function(response) {
            var image = response;
            $.ajax({
              url: "./?action=patients/add",
              type: 'POST',
              data: {
                "name": name,
                "sexId": sexId,
                "isRelativeRegistered": $('#isRelativeRegistered').prop('checked'),
                "relativeId": relativeId,
                "relativeName": relativeName,
                "tel": tel,
                "image": image
              },
              success: function(data, textStatus, xhr) {
                window.location = "index.php?view=patients/index";
              },
              error: function() {
                Swal.fire(
                  '¡Oops!',
                  'Ha ocurrido un error al registrar el paciente, verifica que el nombre no esté repetido.',
                  'error'
                );
              }
            });
          });
        }
        else{
          Swal.fire(
            '¡Oops!',
            'Selecciona la pareja del paciente.',
            'error'
          );
        }
      } else {
        Swal.fire(
          '¡Oops!',
          'Ingresa los datos requeridos del paciente.',
          'error'
        );
      }
    });

    $("#reset_image").click(function() {
      $('#image_profile').hide();
      $('#rotate_image').hide();
      $('#reset_image').hide();

      $('#insert_image').val(''); // this will clear the input val.
      $image_crop.croppie('bind', {
        url: ''
      }).then(function() {

      });
    });
  });

  $("#isRelativeRegistered").change(function() {
    if (this.checked) {
      //Se se va a seleccionar una pareja que es paciente registrado 
      $("#divRelativeName").hide();
      $("#divRelativeId").show();
    } else {
      $("#divRelativeName").show();
      $("#divRelativeId").hide();
    }
  });
</script>