<?php
$categories = CategoryMedicData::getAll();
$users = UserData::getUnassigned();
$branchOffices = BranchOfficeData::getAllByStatus(1);
$educationLevels = EducationLevelData::getAll();
?>
<div class="row">
  <div class="col-md-12">
    <h1>Agregar Psicólogo</h1>
    <br>
    <div class="box box-primary">
      <div class="box-body">
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="index.php?action=medics/add" role="form">
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Área*</label>
            <div class="col-md-6">
              <select name="category_id" class="form-control" required>
                <?php foreach ($categories as $category) : ?>
                  <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Sucursal*</label>
            <div class="col-md-6">
              <select name="branch_office_id" class="form-control" required>
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($branchOffices as $branchOffice) : ?>
                  <option value="<?php echo $branchOffice->id; ?>"><?php echo $branchOffice->name; ?></option>
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
            <label for="inputEmail1" class="col-lg-2 control-label">Grado de Estudios*</label>
            <div class="col-md-6">
              <select name="educationLevel" class="form-control" required>
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($educationLevels as $educationLevel) : ?>
                  <option value="<?php echo $educationLevel->id; ?>"><?php echo $educationLevel->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Cédula Profesional*</label>
            <div class="col-md-6">
              <input type="text" name="professional_license" class="form-control" id="professional_license" placeholder="Cédula Profesional">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Centro de Estudios*</label>
            <div class="col-md-6">
              <input type="text" name="study_center" class="form-control" id="study_center" placeholder="Centro de Estudios">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Correo Electrónico</label>
            <div class="col-md-6">
              <input type="text" name="email" class="form-control" id="email" placeholder="Correo Electrónico">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Teléfono</label>
            <div class="col-md-6">
              <input type="text" name="phone" class="form-control" id="phone" placeholder="Teléfono">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Dirección</label>
            <div class="col-md-6">
              <input type="text" name="address" class="form-control" id="address" placeholder="Dirección">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Usuario</label>
            <div class="col-md-6">
              <select name="user_id" class="form-control">
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($users as $user) : ?>
                  <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Color en Calendario*</label>
            <div class="col-md-6">
              <input id="color" name="calendar_color" type="color" value="#ff0000" required>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-6 pull-right">
              <button type="submit" class="btn btn-primary">Agregar Psicólogo</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>