<?php
$medic = MedicData::getById($_GET["id"]);
$branchOffices = BranchOfficeData::getAllByStatus(1);
$educationLevels = EducationLevelData::getAll();
$categories = CategoryMedicData::getAll();
$users = UserData::getUnassigned();
if ($medic->getUser()) {
  array_push($users, $medic->getUser());
}
?>
<div class="row">
  <div class="col-md-12">
    <h1>Editar Psicólogo</h1>
    <br>
    <div class="box box-primary">
      <div class="box-body">
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="index.php?action=medics/update" role="form">
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Área*</label>
            <div class="col-md-6">
              <select name="category_id" class="form-control">
                <?php foreach ($categories as $category) : ?>
                  <option value="<?php echo $category->id; ?>" <?php echo ($medic->category_id == $category->id) ? "selected" : "" ?>>
                    <?php echo $category->name; ?>
                  </option>
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
                  <option value="<?php echo $branchOffice->id; ?>" <?php echo ($medic->branch_office_id == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Grado de Estudios*</label>
            <div class="col-md-6">
              <select name="educationLevel" class="form-control" required>
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($educationLevels as $educationLevel) : ?>
                  <option value="<?php echo $educationLevel->id; ?>" <?php echo ($medic->education_level_id == $educationLevel->id) ? "selected" : "" ?>><?php echo $educationLevel->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
            <div class="col-md-6">
              <input type="text" name="name" value="<?php echo $medic->name; ?>" class="form-control" id="name" placeholder="Nombre">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Cédula Profesional*</label>
            <div class="col-md-6">
              <input type="text" name="professional_license" class="form-control" id="professional_license" value="<?php echo $medic->professional_license ?>" placeholder="Cédula Profesional">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Centro de Estudios*</label>
            <div class="col-md-6">
              <input type="text" name="study_center" class="form-control" id="study_center" value="<?php echo $medic->study_center ?>" placeholder="Centro de Estudios">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Correo Electrónico</label>
            <div class="col-md-6">
              <input type="text" name="email" class="form-control" id="email" value="<?php echo $medic->email ?>" placeholder="Correo Electrónico">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Teléfono</label>
            <div class="col-md-6">
              <input type="text" name="phone" class="form-control" id="phone" value="<?php echo $medic->phone ?>" placeholder="Teléfono">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Dirección</label>
            <div class="col-md-6">
              <input type="text" name="address" class="form-control" id="address" value="<?php echo $medic->address ?>" placeholder="Dirección">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Usuario</label>
            <div class="col-md-6">
              <select name="user_id" class="form-control">
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($users as $user) : ?>
                  <option value="<?php echo $user->id; ?>" <?php echo ($medic->user_id == $user->id) ? "selected" : "" ?>>
                    <?php echo $user->name; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Color*</label>
            <div class="col-md-6">
              <input id="color" name="calendar_color" type="color" value="<?php echo $medic->calendar_color ?>" required>
            </div>
          </div>

          <!--<div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Está activo</label>
            <div class="col-md-6">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="is_active" <?php echo ($medic->is_active) ? "checked" : "" ?>>
                </label>
              </div>
            </div>
          </div>-->

          <div class="form-group">
            <div class="col-lg-6 pull-right">
              <input type="hidden" name="id" value="<?php echo $medic->id; ?>">
              <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>