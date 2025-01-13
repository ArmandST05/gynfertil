<?php
$patient = PatientData::getById($_GET["id"]);

$birthdayDay = substr($patient->fecha_na, 8, 2);
$birthdayMonth = substr($patient->fecha_na, 5, 2);
$birthdayYear = substr($patient->fecha_na, 0, 4);

$relativeBirthdayDay = substr($patient->relative_birthday, 8, 2);
$relativeBirthdayMonth = substr($patient->relative_birthday, 5, 2);
$relativeBirthdayYear = substr($patient->relative_birthday, 0, 4);

$est = PatientData::get_estatus();

$date2 = date('Y-m-d');
$diff = abs(strtotime($date2) - strtotime($patient->fecha_na));

$patients = PatientData::getAll();
$sexes = PatientData::getAllSexes();
$officialDocuments = OfficialDocumentData::getAll();
?>
<script src="plugins/croppie/js/croppie.js"></script>
<link rel="stylesheet" href="plugins/croppie/css/croppie.css" />

<div class="row">
  <div class="col-md-12">
    <h1>Modificar Paciente</h1>
    <br>
    <form class="form-horizontal" method="post" enctype="multipart/form-data" role="form">
      <div class="form-group">
        <div class="col-lg-12">
          <div class="callout callout-warning">
            <p>Si el paciente realizará un tratamiento de fertilidad, registra correctamente su FECHA DE NACIMIENTO y un DATO OFICIAL, además de TODOS los datos de su pareja, formará parte de su histórico.</p>
          </div>
        </div>
      </div>
      <hr>
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
        <label for="" class="col-lg-2 control-label">Nombre*:</label>
        <div class="col-md-6">
          <input type="text" id="name" name="name" value="<?php echo $patient->name; ?>" class="form-control" id="name" placeholder="Nombre" required>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Sexo*:</label>
        <div class="col-md-6">
          <select id="sexId" name="sexId" class="form-control">
            <?php foreach ($sexes as $sex) : ?>
              <option value="<?php echo $sex->id; ?>" <?php echo ($patient->sex_id == $sex->id) ? "selected" : "" ?>><?php echo $sex->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Dato oficial del paciente:</label>
        <div class="col-lg-2">
          <select name="officialDocumentId" id="officialDocumentId" class="form-control">
            <?php foreach ($officialDocuments as $officialDocument) : ?>
              <option value="<?php echo $officialDocument->id ?>" <?php echo ($officialDocument->id == $patient->official_document_id) ? "selected" : "" ?>><?php echo $officialDocument->name ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <input type="text" id="officialDocumentValue" name="officialDocumentValue" value="<?php echo $patient->official_document_value; ?>" class="form-control" placeholder="Dato oficial">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Dirección:</label>
        <div class="col-lg-3">
          <input type="text" id="calle" name="calle" value="<?php echo $patient->calle; ?>" class="form-control" id="calle" placeholder="Calle">
        </div>
        <div class="col-lg-1">
          <input type="text" id="num" name="num" value="<?php echo $patient->num; ?>" class="form-control" id="num" placeholder="No.">
        </div>
        <div class="col-lg-2">
          <input type="text" id="col" name="col" value="<?php echo $patient->col; ?>" class="form-control" id="col" placeholder="Colonia">
        </div>
      </div>

      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Teléfonos:</label>
        <div class="col-lg-2">
          <input type="text" id="tel" name="tel" value="<?php echo $patient->tel; ?>" class="form-control" id="tel" placeholder="Teléfono">
        </div>
        <div class="col-lg-2">
          <input type="text" id="tel2" name="tel2" value="<?php echo $patient->tel2; ?>" class="form-control" id="tel" placeholder="Tel alternativo">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Email:</label>
        <div class="col-md-6">
          <input type="text" id="email" name="email" value="<?php echo $patient->email; ?>" class="form-control" id="email1" placeholder="Email">
        </div>
      </div>

      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Fecha nacimiento</label>
        <div class="col-lg-1">
          <select name="birthday_day" id="birthday_day" class="form-control" onchange="calculatePatientBirthday();">
            <?php for ($i = 01; $i <= 31; $i++) : ?>
              <option value="<?php echo $i; ?>" <?php echo ($birthdayDay == $i) ? "selected" : "" ?>><?php echo $i ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-lg-2">
          <select name="birthday_month" id="birthday_month" class="form-control" onchange="calculatePatientBirthday();">
            <?php
            $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
            foreach ($months as $index => $month) : ?>
              <option value="<?php echo $index; ?>" <?php echo ($birthdayMonth == $index) ? "selected" : "" ?>><?php echo $month ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-lg-2">
          <select name="birthday_year" id="birthday_year" class="form-control" onchange="calculatePatientBirthday();">
            <?php for ($k = date("Y"); $k >= 1930; $k--) : ?>
              <option value="<?php echo $k; ?>" <?php echo ($birthdayYear == $k) ? "selected" : "" ?>><?php echo $k ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <input type="hidden" name="birthday" id="birthday">
      </div>
      <hr>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <div class="checkbox">
            <label>
              <input name="isRelativeRegistered" id="isRelativeRegistered" type="checkbox" value="1" <?php echo ($patient->relative_id != "" && $patient->relative_id != 0) ? "checked" : "" ?>> Tiene pareja registrada
            </label>
          </div>
        </div>
      </div>
      <div class="form-group" id="divRelativeId">
        <label for="inputEmail1" class="col-lg-2 control-label">Pareja registrada:</label>
        <div class="col-lg-6">
          <select name="relativeId" id="relativeId" class="form-control" id="combobox">
            <option value="">-- SELECCIONE -- </option>
            <?php foreach ($patients as $relativePatient) : ?>
              <option value="<?php echo $relativePatient->id; ?>" <?php echo ($relativePatient->id == $patient->relative_id) ? "selected" : "" ?>><?php echo $relativePatient->id . " - " . $relativePatient->name ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div id="divRelativeName">
        <div class="form-group">
          <label for="inputEmail1" class="col-lg-2 control-label">Nombre de la pareja:</label>
          <div class="col-md-6">
            <input type="text" name="relativeName" value="<?php echo $patient->relative_name; ?>" class="form-control" id="relativeName" placeholder="Nombre de la pareja" autofocus>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-lg-2 control-label">Dato oficial de la pareja:</label>
          <div class="col-lg-2">
            <select name="relativeOfficialDocumentId" id="relativeOfficialDocumentId" class="form-control">
              <?php foreach ($officialDocuments as $officialDocument) : ?>
                <option value="<?php echo $officialDocument->id ?>" <?php echo ($officialDocument->id == $patient->relative_official_document_id) ? "selected" : "" ?>><?php echo $officialDocument->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" id="relativeOfficialDocumentValue" name="relativeOfficialDocumentValue" value="<?php echo $patient->relative_official_document_value; ?>" class="form-control" placeholder="Dato oficial">
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-lg-2 control-label">Fecha nacimiento pareja:</label>
          <div class="col-lg-1">
            <select name="birthday_day" id="relativeBirthdayDay" class="form-control" onchange="calculateRelativeBirthday();">
              <?php for ($i = 01; $i <= 31; $i++) : ?>
                <option value="<?php echo $i; ?>" <?php echo ($relativeBirthdayDay == $i) ? "selected" : "" ?>><?php echo $i ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-lg-2">
            <select name="birthday_month" id="relativeBirthdayMonth" class="form-control" onchange="calculateRelativeBirthday();">
              <?php
              $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
              foreach ($months as $index => $month) : ?>
                <option value="<?php echo $index; ?>" <?php echo ($relativeBirthdayMonth == $index) ? "selected" : "" ?>><?php echo $month ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-2">
            <select name="birthday_year" id="relativeBirthdayYear" class="form-control" onchange="calculateRelativeBirthday();">
              <?php for ($k = date("Y"); $k >= 1930; $k--) : ?>
                <option value="<?php echo $k; ?>" <?php echo ($relativeBirthdayYear == $k) ? "selected" : "" ?>><?php echo $k ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <input type="hidden" name="relativeBirthday" id="relativeBirthday">
        </div>
      </div>
      <hr>
      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Referida por:*</label>
        <div class="col-md-6">
          <input type="text" id="ref" name="ref" value="<?php echo $patient->ref; ?>" class="form-control" id="email1" placeholder="Referida por">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-lg-2 control-label">Estatus</label>
        <div class="col-md-6">
          <select id="estatus" name="estatus" class="form-control">
            <option value="">-- SELECCIONE --</option>
            <?php foreach ($est as $use) : ?>
              <option value="<?php echo $use->id; ?>" <?php if ($patient->status == $use->id) {
                                                        echo "selected";
                                                      } ?>><?php echo $use->nombre; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <input type="hidden" id="patientId" name="patientId" value="<?php echo $patient->id; ?>">
          <button type="button" class="btn btn-primary" id="updatePatient">Actualizar Paciente</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
</div>

<script type="text/javascript">
  function agregarfecha() {
    var d = new Date();
    var n = d.toISOString().slice(0, 10).split("-").join("/");
    var day = n.slice(8, 10);
    var month = n.slice(5, 7);
    var year = n.slice(0, 4);
    calculatePatientBirthday();
  }

  function calculatePatientBirthday() {
    let day = $('#birthday_day').val();
    let month = $('#birthday_month').val();
    let year = $('#birthday_year').val();

    $('#birthday').val(year + "/" + month + "/" + day);
  }

  function calculateRelativeBirthday() {
    let day = $('#relativeBirthdayDay').val();
    let month = $('#relativeBirthdayMonth').val();
    let year = $('#relativeBirthdayYear').val();

    $('#relativeBirthday').val(year + "/" + month + "/" + day);
  }

  $(document).ready(function() {
    $("#relativeId").select2({});

    if ($('#isRelativeRegistered').prop('checked') == true) {
      $("#divRelativeName").hide();
      $("#divRelativeId").show();
    } else {
      $("#divRelativeName").show();
      $("#divRelativeId").hide();
    }

    $("#isRelative").prop("checked", true);

    var image = "<?php echo $patient->image ?>";
    var url_image = 'storage_data/patients/' + image;
    if (image == '') {
      $('#image_profile').hide();
      $('#rotate_image').hide();
      $('#reset_image').hide();
    }

    $image_crop = $('#image_profile').croppie({
      enableExif: true,
      enableOrientation: true,
      viewport: {
        width: 150,
        height: 150,
        type: 'square'
      },
      boundary: {
        width: 150,
        height: 150
      }
    });

    $("#image_profile").croppie('bind', {
      url: url_image,
      points: [100, 100, 100, 100]
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
    });

    $('#rotate_image').on('click', function(ev) {
      $image_crop.croppie('rotate', parseInt($(this).data('deg')));
    });

    $('#updatePatient').click(function(event) {
      calculateRelativeBirthday();
      calculatePatientBirthday();
      var patientId = $("#patientId").val();
      var name = $("#name").val();
      var sexId = $("#sexId").val();
      var officialDocumentId = $("#officialDocumentId").val();
      var officialDocumentValue = $("#officialDocumentValue").val();
      var relativeId = $("#relativeId").val();
      var relativeName = $("#relativeName").val();
      var relativeOfficialDocumentId = $("#relativeOfficialDocumentId").val();
      var relativeOfficialDocumentValue = $("#relativeOfficialDocumentValue").val();
      var calle = $("#calle").val();
      var num = $("#num").val();
      var col = $("#col").val();
      var tel = $("#tel").val();
      var tel2 = $("#tel2").val();
      var email = $("#email").val();
      var ref = $("#ref").val();
      var estatus = $("#estatus").val();
      var birthday = $('#birthday').val();
      var relativeBirthday = $('#relativeBirthday').val();

      if (name && sexId && patientId != relativeId) {
        if ($('#isRelativeRegistered').prop('checked') == false || ($('#isRelativeRegistered').prop('checked') == true && relativeId != null && relativeId != "")) {
          $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
          }).then(function(response) {
            var image = response;
            $.ajax({
              url: "./?action=patients/update",
              type: 'POST',
              data: {
                "patientId": patientId,
                "name": name,
                "sexId": sexId,
                "officialDocumentId": officialDocumentId,
                "officialDocumentValue": officialDocumentValue,
                "isRelativeRegistered": $('#isRelativeRegistered').prop('checked'),
                "relativeId": relativeId,
                "relativeName": relativeName,
                "relativeOfficialDocumentId": relativeOfficialDocumentId,
                "relativeOfficialDocumentValue": relativeOfficialDocumentValue,
                "calle": calle,
                "num": num,
                "col": col,
                "tel": tel,
                "tel2": tel2,
                "email": email,
                "birthday": birthday,
                "relativeBirthday": relativeBirthday,
                "ref": ref,
                "estatus": estatus,
                "image": image
              },
              success: function(data, textStatus, xhr) {
                window.location = "index.php?view=patients/index";
              },
              error: function() {
                alert("Ha ocurrido un error al almacenar los datos");
              }
            });
          });
        } else {
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