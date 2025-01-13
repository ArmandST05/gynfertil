    <?php
    $categories = CategoryMedicData::getAll();
    $medicTypes = MedicTypeData::getAll();
    $users = UserData::getAll();
    ?>
    <div class="row">
      <div class="col-md-12">
        <h1>Agregar Personal Médico</h1>
        <br>
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="index.php?view=medics/add" role="form">
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Especialidad*</label>
            <div class="col-md-6">
              <select name="categoryId" class="form-control" required>
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($categories as $category) : ?>
                  <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Tipo*</label>
            <div class="col-md-6">
              <select name="typeId" class="form-control" required>
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($medicTypes as $type) : ?>
                  <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
            <div class="col-md-6">
              <input type="text" name="name" class="form-control" id="name" placeholder="Nombre" required>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Título de especialidad (aparecerá en los reportes)</label>
            <div class="col-md-6">
              <input type="text" name="specialty_title" class="form-control" id="specialty_title" placeholder="Título de especialidad">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Usuario</label>
            <div class="col-md-6">
              <select name="userId" class="form-control">
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($users as $user) : ?>
                  <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label"></label>
            <div class="col-lg-6">
              <div class="checkbox">
                <label>
                  <input type="checkbox" id="isDigitalSignature" name="isDigitalSignature" value="1" onclick="selectedDigitalSignature()">
                  Utilizar firma digital.
                </label>
              </div>
            </div>
          </div>
          <div class="form-group divDigitalSignature" style="display: none;">
            <label for="inputEmail1" class="col-lg-2 control-label">Firma Digital (.PNG sin fondo)</label>
            <div class="col-md-6">
              <input type="file" name="digitalSignature" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <button type="submit" class="btn btn-primary">Agregar Medico</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    </div>
    </div>
    <script>
      function selectedDigitalSignature() {
        if ($("#isDigitalSignature").is(':checked')) $(".divDigitalSignature").show();
        else $(".divDigitalSignature").hide();
      }
    </script>