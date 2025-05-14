<?php declare(strict_types=1);
$medic = MedicData::getByUserId(UserData::getLoggedIn()->id);
$categories = CategoryMedicData::getAll();
?>
<div class="row">
  <div class="col-md-12">
    <h1>Perfil</h1>
    <div class="box box-primary">
      <div class="box-body">
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="index.php?action=medics/update-profile" role="form">
          <div class="col-md-12">
            <div class="row">
              <div class="form-group">
                <div class="col-md-6">
                  <label for="inputEmail1" class="control-label">Área</label>
                  <select name="category_id" class="form-control" disabled>
                    <option value="">-- SELECCIONE --</option>
                    <?php foreach ($categories as $category) : ?>
                      <option value="<?php echo $category->id; ?>" <?php echo ($medic->category_id == $category->id) ? "selected" : "" ?>>
                        <?php echo $category->name; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputEmail1" class="control-label">Nombre</label>
                  <input type="text" name="name" value="<?php echo $medic->name; ?>" class="form-control" id="name" placeholder="Nombre" disabled>
                </div>
                <div class="col-md-6">
                  <label for="inputEmail1" class="control-label">Cédula Profesional*</label>
                  <input type="text" name="professionalLicense" class="form-control" id="professionalLicense" value="<?php echo $medic->professional_license ?>" placeholder="Cédula Profesional">
                </div>
                <div class="col-md-6">
                  <label for="inputEmail1" class="control-label">Centro de Estudios*</label>
                  <input type="text" name="studyCenter" class="form-control" id="studyCenter" value="<?php echo $medic->study_center ?>" placeholder="Centro de Estudios">
                </div>
                <div class="col-md-6">
                  <label for="inputEmail1" class="control-label">Correo Electrónico</label>
                  <input type="text" name="email" class="form-control" id="email" value="<?php echo $medic->email ?>" placeholder="Correo Electrónico">
                </div>
                <div class="col-md-6">
                  <label for="inputEmail1" class="control-label">Teléfono</label>
                  <input type="text" name="phone" class="form-control" id="phone" value="<?php echo $medic->phone ?>" placeholder="Teléfono">
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="form-group">
                <div class="col-lg-6">
                  <br>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="isDigitalSignature" name="isDigitalSignature" value="1" <?php echo ($medic->is_digital_signature == 1) ? "checked" : "" ?> onclick="selectedDigitalSignature()">
                      Utilizar firma digital.
                    </label>
                  </div>
                </div>
                <div class="col-md-6 divDigitalSignature" <?php echo ($medic->is_digital_signature == 1) ? '' : "style='display: none;'"; ?>>
                  <label for="inputEmail1" class="control-label">Firma Digital (.PNG sin fondo)</label>
                  <input type="file" name="digitalSignature" class="form-control" <?php echo $medic->digital_signature_path ?>>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="form-group">
                <div class="col-lg-6">
                  <br>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="isFielKey" name="isFielKey" value="1" <?php echo ($medic->is_fiel_key == 1) ? "checked" : "" ?> onclick="selectedFielKey()">
                      Utilizar firma digital (FIEL).
                    </label>
                  </div>
                </div>
                <div class="col-md-3 divFielKey" <?php echo ($medic->is_fiel_key == 1) ? '' : "style='display: none;'"; ?>>
                  <label for="inputEmail1" class="control-label">Firma Electrónica Digital (FIEL .key)</label>
                  <input type="file" name="fielKey" class="form-control">
                </div>
                <div class="col-md-3 divFielKey" <?php echo ($medic->is_fiel_key == 1) ? '' : "style='display: none;'"; ?>>
                  <label for="inputEmail1" class="control-label">Contraseña de la llave privada (FIEL .key)</label>
                  <input type="password" name="fielKeyPassword" class="form-control" placeholder="Contraseña FIEL">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2 pull-right">
                <input type="hidden" name="id" value="<?php echo $medic->id; ?>">
                <button type="submit" class="btn btn-primary">Actualizar Psicólogo</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<script type="text/javascript">
  $(document).ready(function() {});

  function selectedDigitalSignature() {
    if ($("#isDigitalSignature").is(':checked')) $(".divDigitalSignature").show();
    else $(".divDigitalSignature").hide();
  }

  function selectedFielKey() {
    if ($("#isFielKey").is(':checked')) $(".divFielKey").show();
    else $(".divFielKey").hide();
  }
</script>