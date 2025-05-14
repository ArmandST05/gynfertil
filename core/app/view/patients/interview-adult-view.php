<?php
?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Entrevista del paciente</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="index.php?action=patients/update-treatment-interview" role="form">
      <div class="col-md-12">
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Nombre</label>
            <div class="col-md-6">
              <input type="text" name="name" value="<?php echo $patient->name; ?>" class="form-control" id="name" placeholder="Nombre" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Fecha nacimiento:</label>
            <div class="col-lg-6">
              <input type="date" name="birthday" id="birthday" value="<?php echo $patient->birthday; ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Grado de Estudios*</label>
            <div class="col-md-6">
              <select name="educationLevel" class="form-control">
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($educationLevels as $educationLevel) : ?>
                  <option value="<?php echo $educationLevel->id; ?>" <?php echo ($educationLevel->id == $patient->education_level_id) ? "selected" : "" ?>><?php echo $educationLevel->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Estado civil:</label>
            <div class="col-md-6">
              <input type="text" id="dt-55" name="details[55]" value="<?php echo $details['55'] ?>" class="form-control" placeholder="Estado civil">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Tiempo estado civil:</label>
            <div class="col-md-6">
              <input type="text" id="dt-56" name="details[56]" value="<?php echo $details['56'] ?>" class="form-control" placeholder="Estado civil">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Hijos (Cantidad y edades):</label>
            <div class="col-md-6">
              <input type="text" id="dt-76" name="details[76]" value="<?php echo $details['76'] ?>" class="form-control" placeholder="Hijos">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Ocupación:</label>
            <div class="col-md-6">
              <input type="text" name="occupation" value="<?php echo $patient->occupation; ?>" class="form-control" id="occupation" placeholder="Ocupación">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Teléfonos:</label>
            <div class="col-lg-2">
              <input type="text" id="cellphone" name="cellphone" class="form-control" value="<?php echo $patient->cellphone; ?>" placeholder="Celular" required maxlength="10" pattern="\d{10}">
            </div>
            <div class="col-lg-2">
              <input type="text" id="homephone" name="homephone" class="form-control" value="<?php echo $patient->homephone; ?>" placeholder="Teléfono Fijo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Dirección:</label>
            <div class="col-lg-3">
              <input type="text" id="street" name="street" value="<?php echo $patient->street; ?>" class="form-control" placeholder="Calle">
            </div>
            <div class="col-lg-1">
              <input type="text" id="number" name="number" value="<?php echo $patient->number; ?>" class="form-control" placeholder="Número">
            </div>
            <div class="col-lg-2">
              <input type="text" id="colony" name="colony" value="<?php echo $patient->colony; ?>" class="form-control" placeholder="Colonia">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label"></label>
            <div class="col-lg-3">
              <select id="countyId" name="countyId" class="form-control">
                <option value="0">--Seleccionar municipio --</option>
                <?php foreach ($counties as $county) : ?>
                  <option value="<?php echo $county->id; ?>" <?php echo ($county->id == $patient->county_id) ? "selected" : "" ?>><?php echo $county->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Tratamiento psicológico anterior:</label>
            <div class="col-md-4">
              <input type="text" id="dt-1" name="details[1]" value="<?php echo $details['1'] ?>" class="form-control" placeholder="Tratamiento anterior">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Tiempo:</label>
            <div class="col-md-2">
              <input type="text" id="dt-2" name="details[2]" value="<?php echo $details['2'] ?>" class="form-control" placeholder="Tiempo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Motivo de tratamiento psicológico anterior:</label>
            <div class="col-lg-8">
              <input type="text" id="dt-3" name="details[3]" value="<?php echo $details['3'] ?>" class="form-control" placeholder="Motivo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Tratamiento psicológico anterior 2:</label>
            <div class="col-md-4">
              <input type="text" id="dt-60" name="details[60]" value="<?php echo $details['60'] ?>" class="form-control" placeholder="Tratamiento anterior">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Tiempo:</label>
            <div class="col-md-2">
              <input type="text" id="dt-61" name="details[61]" value="<?php echo $details['61'] ?>" class="form-control" placeholder="Tiempo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Motivo de tratamiento anterior 2:</label>
            <div class="col-lg-8">
              <input type="text" id="dt-62" name="details[62]" value="<?php echo $details['62'] ?>" class="form-control" placeholder="Motivo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <label>Motivo actual de consulta:</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[5]" value="1" <?php echo ($details['5'] == 1) ? "checked" : "" ?>>
                  Ansiedad
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[64]" value="1" <?php echo ($details['64'] == 1) ? "checked" : "" ?>>
                  Problemas de pareja
                </label>
              </div>
              <div class="checkbox" name="details[67]" value="1" <?php echo ($details['67'] == 1) ? "checked" : "" ?>>
                <label>
                  <input type="checkbox">
                  Autoestima
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[11]" value="1" <?php echo ($details['11'] == 1) ? "checked" : "" ?>>
                  Depresión
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[63]" value="1" <?php echo ($details['63'] == 1) ? "checked" : "" ?>>
                  Duelo
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[68]" value="1" <?php echo ($details['68'] == 1) ? "checked" : "" ?>>
                  Otros
                </label>
                <input type="text" id="dt-69" name="details[69]" value="<?php echo $details['69'] ?>" class="form-control">
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[18]" value="1" <?php echo ($details['18'] == 1) ? "checked" : "" ?>>
                  Conductual
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[65]" value="1" <?php echo ($details['65'] == 1) ? "checked" : "" ?>>
                  Sexual
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[23]" value="1" <?php echo ($details['23'] == 1) ? "checked" : "" ?>>
                  Emocional
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[66]" value="1" <?php echo ($details['66'] == 1) ? "checked" : "" ?>>
                  Superar infancia
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Malestares físicos:</label>
            <div class="col-md-6">
              <input type="text" id="dt-70" name="details[70]" value="<?php echo $details['70'] ?>" class="form-control" placeholder="Malestares físicos">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Inicio del malestar:</label>
            <div class="col-md-4">
              <input type="text" id="dt-71" name="details[71]" value="<?php echo $details['71'] ?>" class="form-control" placeholder="Inicio del malestar">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Frecuencia:</label>
            <div class="col-md-2">
              <input type="text" id="dt-72" name="details[72]" value="<?php echo $details['72'] ?>" class="form-control" placeholder="Tiempo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Consumo de medicamentos y/o sustancias:</label>
            <div class="col-md-6">
              <input type="text" id="dt-73" name="details[73]" value="<?php echo $details['73'] ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Familiares diagnosticados psicológicamente:</label>
            <div class="col-md-6">
              <input type="text" id="dt-74" name="details[74]" value="<?php echo $details['74'] ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Cuenta con algún diagnostico medico físico:</label>
            <div class="col-md-6">
              <input type="text" id="dt-75" name="details[75]" value="<?php echo $details['75'] ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Familiograma</label>
            <div class="col-md-6">
              <textarea class="form-control" id="dt-32" name="details[32]"><?php echo $details['32'] ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Quién vive en casa</label>
            <div class="col-md-6">
              <textarea class="form-control" id="dt-33" name="details[33]"><?php echo $details['33'] ?></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-offset-10 col-lg-2">
          <input type="hidden" id="patientId" name="patientId" value="<?php echo $patient->id; ?>">
          <input type="hidden" name="patientTreatmentId" value="<?php echo $patientTreatmentId; ?>">
          <button type="submit" class="btn btn-primary" id="updatePatient">Actualizar Datos</button>
        </div>
      </div>
    </form>
  </div>
</div>