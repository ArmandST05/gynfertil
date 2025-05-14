<?php
$user = UserData::getLoggedIn();
$userType = $user->user_type;

if ($userType == "su" || $userType == "co") {
  $branchOffices = BranchOfficeData::getAllByStatus(1);
} else {
  $branchOffices = [$user->getBranchOffice()];
}
$categories = PatientData::getAllCategories();
$counties = CountyData::getAll();
$companies = CompanyData::getAll();
$treatments = TreatmentData::getAll();
$educationLevels = EducationLevelData::getAll();
$medics = MedicData::getAll();
?>
<script src="plugins/croppie/js/croppie.js"></script>
<link rel="stylesheet" href="plugins/croppie/css/croppie.css" />

<div class="row">
  <div class="col-md-12">
    <h1>Nuevo Paciente</h1>
    <br>
    <div class="box box-primary">
      <!-- /.box-header -->
      <div class="box-body">
        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="addpatient" action="index.php?action=patients/add" role="form">

          <div class="form-group">
            <label class="col-lg-2 control-label">Foto de perfil:</label>
            <div class="col-md-6">
              <div id="image_profile" style="width:300px; margin-top:10px"></div>
              <label for="insert_image">
                <a class="btn btn-success" style="margin-left:31px;">Seleccionar foto</a>
              </label>
              <button type="button" id="rotate_image" class="btn btn-primary rotate_image" data-deg="-90"><span class="glyphicon glyphicon-repeat"></span> Rotar</button>
              <button type="button" id="reset_image" class="btn btn-danger" data-deg="-90"><span class="glyphicon glyphicon-cancel"></span>Eliminar</button>
              <input id="insert_image" type="file" style='display: none;' name="image" accept="image/*" />
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Sucursal*</label>
            <div class="col-md-6">
              <select id="branchOfficeId" name="branchOfficeId" class="form-control" required>
                <?php foreach ($branchOffices as $branchOffice) : ?>
                  <option value="<?php echo $branchOffice->id; ?>"><?php echo $branchOffice->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Empresa</label>
            <div class="col-md-6">
              <select id="companyId" name="companyId" class="form-control" required>
                <option value="0">PÚBLICO EN GENERAL</option>
                <?php foreach ($companies as $company) : ?>
                  <option value="<?php echo $company->id; ?>"><?php echo $company->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Nombre:*</label>
            <div class="col-md-8">
              <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre" autofocus>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Sexo:*</label>
            <div class="col-md-8">
              <select id="sex" name="sex" class="form-control">
                <option value="1">Masculino</option>
                <option value="2">Femenino</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">CURP:</label>
            <div class="col-md-8">
              <input type="text" name="curp" required class="form-control" id="curp" placeholder="CURP" autofocus>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Nombre de Familiar:</label>
            <div class="col-md-8">
              <input type="text" name="relative_name" class="form-control" id="relative_name" placeholder="Nombre del familiar" autofocus>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Dirección:</label>
            <div class="col-lg-3">
              <input type="text" id="street" name="street" class="form-control" placeholder="Calle">
            </div>
            <div class="col-lg-2">
              <input type="text" id="number" name="number" class="form-control" placeholder="No.">
            </div>
            <div class="col-lg-3">
              <input type="text" id="colony" name="colony" class="form-control" placeholder="Colonia">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-lg-2 control-label"></label>
            <div class="col-lg-3">
              <select id="countyId" name="countyId" class="form-control" required>
                <option value="0">--Seleccionar municipio --</option>
                <?php foreach ($counties as $county) : ?>
                  <option value="<?php echo $county->id; ?>"><?php echo $county->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Teléfonos:</label>
            <div class="col-lg-2">
              <input type="number" min="0" id="cellphone" name="cellphone" class="form-control" placeholder="Celular">
            </div>
            <div class="col-lg-2">
              <input type="number" min="0" id="homephone" name="homephone" class="form-control" placeholder="Teléfono Fijo">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Correo Electrónico:</label>
            <div class="col-md-8">
              <input type="text" id="email" name="email" class="form-control" placeholder="Correo Electrónico">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Fecha nacimiento:</label>
            <div class="col-lg-8">
              <input type="date" name="birthday" id="birthday" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Nivel Educativo</label>
            <div class="col-md-6">
              <select id="educationLevelId" name="educationLevelId" class="form-control" required>
                <?php foreach ($educationLevels as $educationLevel) : ?>
                  <option value="<?php echo $educationLevel->id; ?>"><?php echo $educationLevel->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Ocupación:</label>
            <div class="col-md-8">
              <input type="text" name="occupation" required class="form-control" id="occupation" placeholder="Ocupación" autofocus>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Referido por:</label>
            <div class="col-md-8">
              <input type="text" id="referred_by" name="referred_by" class="form-control" placeholder="Referido por">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Categoría:</label>
            <div class="col-md-8">
              <input type="text" name="category_id" value="ACTIVO" class="form-control" id="category_id" placeholder="Categoría" readonly>
            </div>
          </div>
          <!--<div class="form-group">
            <label for="" class="col-lg-2 control-label">Categoría*</label>
            <div class="col-md-8">
              <select id="category_id" name="category_id" class="form-control" required disabled>
                <option>-- SELECCIONE --</option>
                <?php foreach ($categories as $category) : ?>
                  <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>-->
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Observaciones:</label>
            <div class="col-md-8">
              <textarea name="observations" class="form-control" id="observations" rows="7"></textarea>
            </div>
          </div>

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo tratamiento</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputEmail1">Tratamiento:*</label>
                    <select class="form-control" id="treatmentIdNew" name="treatmentIdNew">
                      <?php foreach ($treatments as $treatment) : ?>
                        <option value="<?php echo $treatment->id ?>"><?php echo $treatment->name ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Psicólogo:*</label>
                    <select id="medicTreatmentNew" name="medicTreatmentNew" class="form-control" required>
                      <?php foreach ($medics as $medic) : ?>
                        <option value="<?php echo $medic->id; ?>"><?php echo $medic->name; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Precio predeterminado:*</label>
                    <input type="number" min="0" step=".01" id="defaultPriceTreatmentNew" name="defaultPriceTreatmentNew" value="0" class="form-control" placeholder="Precio">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Motivo:</label>
                    <textarea class="form-control" id="reasonTreatmentNew" name="reasonTreatmentNew"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <div class="form-group">
            <div class="col-lg-2 pull-right">
              <button type="button" id="addPatient" class="btn btn-primary">Agregar Paciente</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#medicId").select2({});
    $("#countyId").select2({});

    $('#image_profile').hide();
    $('#rotate_image').hide();
    $('#reset_image').hide();

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
      /*obligatorio sucursal, nombre, sexo y teléfonos a 10 dígitos.
       */
      let name = $("#name").val().trim();
      let sex = $("#sex").val().trim();
      let street = $("#street").val().trim();
      let number = $("#number").val().trim();
      let colony = $("#colony").val().trim();
      let cellphone = $("#cellphone").val().trim();
      let homephone = $("#homephone").val().trim();
      //let category_id = $("#category_id").val();
      let branchOfficeId = $("#branchOfficeId").val();

      let treatmentId = $("#treatmentIdNew").val().trim();
      let medicId = $("#medicTreatmentNew").val().trim();
      let defaultPrice = $("#defaultPriceTreatmentNew").val().trim();
      let reason = $("#reasonTreatmentNew").val().trim();

      if (!branchOfficeId || !name || !sex || !cellphone ||
        (cellphone && cellphone.length != 10) || (homephone && homephone.length != 10)
      ){
        Swal.fire(
          'Advertencia',
          'Captura los datos obligatorios: SUCURSAL,NOMBRE,SEXO Y TELÉFONOS (10 DÍGITOS)',
          'wanring'
        );
      }
      else if (!treatmentId || !medicId || !defaultPrice){
        Swal.fire(
          'Advertencia',
          'Captura los datos obligatorios del tratamiento: TRATAMIENTO, PSICÓLOGO,PRECIO',
          'wanring'
        );
      } else {
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
              "sex": sex,
              "curp": $("#curp").val(),
              "street": street,
              "number": number,
              "colony": colony,
              "cellphone": cellphone,
              "homephone": homephone,
              "email": $("#email").val(),
              "birthday": $("#birthday").val(),
              "referred_by": $("#referred_by").val(),
              //"category_id": category_id,
              "relative_name": $("#relative_name").val(),
              "branchOfficeId": branchOfficeId,
              "countyId": $("#countyId").val(),
              "companyId": $("#companyId").val(),
              "observations": $("#observations").val(),
              "educationLevelId": $("#educationLevelId").val(),
              "occupation": $("#occupation").val(),
              "image": image,
              //DATOS NUEVO TRATAMIENTO
              "treatmentId": treatmentId,
              "medicId": medicId,
              "defaultPrice": defaultPrice,
              "reason": reason,
            },
            success: function(data, textStatus, xhr) {
              let treatmentData = JSON.parse(data);
              window.open("index.php?view=patients/report-interview-"+treatmentData["code"]+"&id="+treatmentData["id"]);
              window.location = "index.php?view=patients/index";
           
            },
            error: function() {
              Swal.fire(
                'Error',
                'No se ha registrado el paciente. Captura los campos requeridos y verifica que el nombre del paciente no esté repetido.',
                'error'
              );
            }
          });

        });
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
</script>