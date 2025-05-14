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
              <input type="text" name="name" value="<?php echo $patient->name; ?>" class="form-control" id="name" placeholder="Nombre"  required>
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
              <select name="educationLevel" class="form-control"  >
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($educationLevels as $educationLevel) : ?>
                  <option value="<?php echo $educationLevel->id; ?>"><?php echo $educationLevel->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Nombre del padre o tutor</label>
            <div class="col-md-6">
              <input type="text" name="relative_name" value="<?php echo $patient->relative_name; ?>" class="form-control" id="name" placeholder="Nombre del padre o tutor"  >
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
            <label class="col-lg-2 control-label">Ha llevado tratamiento anteriormente</label>
            <div class="col-md-4">
              <input type="text" id="dt-1" name="details[1]" value="<?php echo $details['1'] ?>" class="form-control" placeholder="Tratamiento anterior"  >
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Tiempo</label>
            <div class="col-md-2">
              <input type="text" id="dt-2" name="details[2]" value="<?php echo $details['2'] ?>" class="form-control" placeholder="Tiempo"  >
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Motivo de tratamiento anterior</label>
            <div class="col-lg-8">
              <input type="text" id="dt-3" name="details[3]" value="<?php echo $details['3'] ?>" class="form-control" placeholder="Motivo"  >
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Motivo de consulta</label>
            <div class="col-md-6">
              <input type="text" id="dt-4" name="details[4]" value="<?php echo $details['4'] ?>" class="form-control" placeholder="Motivo de consulta"  >
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[5]" value="1" <?php echo ($details['5'] == 1) ? "checked" : "" ?>>
                  <b>Ansiedad</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[6]" value="1" <?php echo ($details['6'] == 1) ? "checked" : "" ?>>
                  Se muerde uñas
                </label>
              </div>
              <div class="checkbox" name="details[7]" value="1" <?php echo ($details['7'] == 1) ? "checked" : "" ?>>
                <label>
                  <input type="checkbox">
                  No duerme bien
                </label>
              </div>
              <div class="checkbox" name="details[8]" value="1" <?php echo ($details['8'] == 1) ? "checked" : "" ?>>
                <label>
                  <input type="checkbox">
                  Temores nocturnos
                </label>
              </div>
              <div class="checkbox" name="details[9]" value="1" <?php echo ($details['9'] == 1) ? "checked" : "" ?>>
                <label>
                  <input type="checkbox">
                  Angustia constante
                </label>
              </div>
              <div class="checkbox" name="details[10]" value="1" <?php echo ($details['10'] == 1) ? "checked" : "" ?>>
                <label>
                  <input type="checkbox">
                  Se estresa con facilidad
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[11]" value="1" <?php echo ($details['11'] == 1) ? "checked" : "" ?>>
                  <b>Depresión</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[12]" value="1" <?php echo ($details['12'] == 1) ? "checked" : "" ?>>
                  Llanto constante
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[13]" value="1" <?php echo ($details['13'] == 1) ? "checked" : "" ?>>
                  Se aísla
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[14]" value="1" <?php echo ($details['14'] == 1) ? "checked" : "" ?>>
                  Temores no quiere convivir
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[15]" value="1" <?php echo ($details['15'] == 1) ? "checked" : "" ?>>
                  Se ve triste
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[16]" value="1" <?php echo ($details['16'] == 1) ? "checked" : "" ?>>
                  Habla cosas deprimentes
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[17]" value="1" <?php echo ($details['17'] == 1) ? "checked" : "" ?>>
                  Desmotivado
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[18]" value="1" <?php echo ($details['18'] == 1) ? "checked" : "" ?>>
                  <b>Conductas</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[19]" value="1" <?php echo ($details['19'] == 1) ? "checked" : "" ?>>
                  Golpea
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[20]" value="1" <?php echo ($details['20'] == 1) ? "checked" : "" ?>>
                  Hace rabietas
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[21]" value="1" <?php echo ($details['21'] == 1) ? "checked" : "" ?>>
                  Muerde
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[22]" value="1" <?php echo ($details['22'] == 1) ? "checked" : "" ?>>
                  No respeta límites
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[23]" value="1" <?php echo ($details['23'] == 1) ? "checked" : "" ?>>
                  <b>Emociones</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[24]" value="1" <?php echo ($details['24'] == 1) ? "checked" : "" ?>>
                  No se expresa
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[25]" value="1" <?php echo ($details['25'] == 1) ? "checked" : "" ?>>
                  Se enoja fácilmente
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[26]" value="1" <?php echo ($details['26'] == 1) ? "checked" : "" ?>>
                  Tiene temor
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[27]" value="1" <?php echo ($details['27'] == 1) ? "checked" : "" ?>>
                  No disfruta
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[28]" value="1" <?php echo ($details['28'] == 1) ? "checked" : "" ?>>
                  Percepción negativa
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Quién cuida al paciente</label>
            <div class="col-md-6">
              <input type="text" id="dt-29" name="details[29]" value="<?php echo $details['29'] ?>" class="form-control" placeholder="Quién cuida al paciente"  >
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Desde hace cuánto cuida al paciente</label>
            <div class="col-md-6">
              <input type="text" id="dt-30" name="details[30]" value="<?php echo $details['30'] ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Situación actual de padres</label>
            <div class="col-md-6">
              <input type="text" id="dt-31" name="details[31]" value="<?php echo $details['31'] ?>" class="form-control">
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