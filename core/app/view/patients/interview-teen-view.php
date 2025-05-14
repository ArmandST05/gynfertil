<?php
function validateIsChecked($index,$array){
  $arrayValues = explode(",",$array);
  foreach($arrayValues as $arrayValue){
    if($arrayValue != null && (substr($arrayValue, 0, 1) == $index)){
      return "checked";
    }
  }
}
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
            <label class="col-lg-2 control-label">Nombre:</label>
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
            <label for="inputEmail1" class="col-lg-2 control-label">Grado de Estudios:*</label>
            <div class="col-md-6">
              <select name="educationLevel" class="form-control">
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
            <label class="col-lg-2 control-label">Ocupación:</label>
            <div class="col-md-6">
              <input type="text" name="occupation" value="<?php echo $patient->occupation; ?>" class="form-control" id="occupation" placeholder="Ocupación">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Nombre del padre o tutor:</label>
            <div class="col-md-6">
              <input type="text" name="relative_name" value="<?php echo $patient->relative_name; ?>" class="form-control" id="name" placeholder="Nombre del padre o tutor">
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
            <label class="col-lg-2 control-label">Ha llevado tratamiento anteriormente:</label>
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
            <label for="" class="col-lg-2 control-label">Motivo de tratamiento anterior:</label>
            <div class="col-lg-8">
              <input type="text" id="dt-3" name="details[3]" value="<?php echo $details['3'] ?>" class="form-control" placeholder="Motivo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Motivo de consulta:</label>
            <div class="col-md-6">
              <input type="text" id="dt-4" name="details[4]" value="<?php echo $details['4'] ?>" class="form-control" placeholder="Motivo de consulta">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[5][0]" value="0-1" <?php echo validateIsChecked(0,$details['5']) ?>>
                  <b>Ansiedad</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[34][0]" value="0-1" <?php echo validateIsChecked(0,$details['34']) ?>>
                  Mareos
                </label>
              </div>
              <div class="checkbox" name="details[35][0]" value="0-1" <?php echo validateIsChecked(0,$details['35']) ?>>
                <label>
                  <input type="checkbox">
                  Náuseas
                </label>
              </div>
              <div class="checkbox" name="details[36][0]" value="0-1" <?php echo validateIsChecked(0,$details['36']) ?>>
                <label>
                  <input type="checkbox">
                  Falta de aire
                </label>
              </div>
              <div class="checkbox" name="details[9][0]" value="0-1" <?php echo validateIsChecked(0,$details['9']) ?>>
                <label>
                  <input type="checkbox">
                  Angustia constante
                </label>
              </div>
              <div class="checkbox" name="details[37][0]" value="0-1" <?php echo validateIsChecked(0,$details['37']) ?>>
                <label>
                  <input type="checkbox">
                  Pánico o miedo intenso
                </label>
              </div>
              <div class="checkbox" name="details[38][0]" value="0-1" <?php echo validateIsChecked(0,$details['38']) ?>>
                <label>
                  <input type="checkbox">
                  Insomnio
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[11][0]" value="0-1" <?php echo validateIsChecked(0,$details['11']) ?>>
                  <b>Depresión</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[39][0]" value="0-1" <?php echo validateIsChecked(0,$details['39']) ?>>
                  Falta o exceso de sueño
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[40][0]" value="0-1" <?php echo validateIsChecked(0,$details['40']) ?>>
                  Sentimientos de culpa
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[41][0]" value="0-1" <?php echo validateIsChecked(0,$details['41']) ?>>
                  Aislamiento social
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[42][0]" value="0-1" <?php echo validateIsChecked(0,$details['42']) ?>>
                  Ideación suicida
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[43][0]" value="0-1" <?php echo validateIsChecked(0,$details['43']) ?>>
                  Irritabilidad
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[17][0]" value="0-1" <?php echo validateIsChecked(0,$details['17']) ?>>
                  Desmotivado
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[18][0]" value="0-1" <?php echo validateIsChecked(0,$details['18']) ?>>
                  <b>Conductas</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[44][0]" value="0-1" <?php echo validateIsChecked(0,$details['44']) ?>>
                  Confronta figuras de autoridad
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[45][0]" value="0-1" <?php echo validateIsChecked(0,$details['45']) ?>>
                  Dificultades con su higiene
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[46][0]" value="0-1" <?php echo validateIsChecked(0,$details['46']) ?>>
                  Dificultades para realizar actividades en casa
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[22][0]" value="0-1" <?php echo validateIsChecked(0,$details['22']) ?>>
                  No respeta límites
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[47][0]" value="0-1" <?php echo validateIsChecked(0,$details['47']) ?>>
                  Rebeldía
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[48][0]" value="0-1" <?php echo validateIsChecked(0,$details['48']) ?>>
                  Adicciones
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[23][0]" value="0-1" <?php echo validateIsChecked(0,$details['23']) ?>>
                  <b>Emociones</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[50][0]" value="0-1" <?php echo validateIsChecked(0,$details['50']) ?>>
                  Cuesta comprender emociones
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[51][0]" value="0-1" <?php echo validateIsChecked(0,$details['51']) ?>>
                  No percibe redes de apoyo
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[52][0]" value="0-1" <?php echo validateIsChecked(0,$details['52']) ?>>
                  Siente que no pertenece a algún lugar
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[53][0]" value="0-1" <?php echo validateIsChecked(0,$details['53']) ?>>
                  Percepción negativa del entorno
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[54][0]" value="0-1" <?php echo validateIsChecked(0,$details['54']) ?>>
                  Ausencia de metas
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <label>Información del paciente</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[5][1]" value="1-1" <?php echo validateIsChecked(1,$details['5']) ?>>
                  <b>Ansiedad</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[34][1]" value="1-1" <?php echo validateIsChecked(1,$details['34']) ?>>
                  Mareos
                </label>
              </div>
              <div class="checkbox" name="details[35][1]" value="1-1" <?php echo validateIsChecked(1,$details['35']) ?>>
                <label>
                  <input type="checkbox">
                  Náuseas
                </label>
              </div>
              <div class="checkbox" name="details[36][1]" value="1-1" <?php echo validateIsChecked(1,$details['36']) ?>>
                <label>
                  <input type="checkbox">
                  Falta de aire
                </label>
              </div>
              <div class="checkbox" name="details[9][1]" value="1-1" <?php echo validateIsChecked(1,$details['9']) ?>>
                <label>
                  <input type="checkbox">
                  Angustia constante
                </label>
              </div>
              <div class="checkbox" name="details[37][1]" value="1-1" <?php echo validateIsChecked(1,$details['37']) ?>>
                <label>
                  <input type="checkbox">
                  Pánico o miedo intenso
                </label>
              </div>
              <div class="checkbox" name="details[38][1]" value="1-1" <?php echo validateIsChecked(1,$details['38']) ?>>
                <label>
                  <input type="checkbox">
                  Insomnio
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[11][1]" value="1-1" <?php echo validateIsChecked(1,$details['11']) ?>>
                  <b>Depresión</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[39][1]" value="1-1" <?php echo validateIsChecked(1,$details['39']) ?>>
                  Falta o exceso de sueño
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[40][1]" value="1-1" <?php echo validateIsChecked(1,$details['40']) ?>>
                  Sentimientos de culpa
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[41][1]" value="1-1" <?php echo validateIsChecked(1,$details['41']) ?>>
                  Aislamiento social
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[42][1]" value="1-1" <?php echo validateIsChecked(1,$details['42']) ?>>
                  Ideación suicida
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[43][1]" value="1-1" <?php echo validateIsChecked(1,$details['43']) ?>>
                  Irritabilidad
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[17][1]" value="1-1" <?php echo validateIsChecked(1,$details['17']) ?>>
                  Desmotivado
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[18][1]" value="1-1" <?php echo validateIsChecked(1,$details['18']) ?>>
                  <b>Conductas</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[44][1]" value="1-1" <?php echo validateIsChecked(1,$details['44']) ?>>
                  Confronta figuras de autoridad
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[45][1]" value="1-1" <?php echo validateIsChecked(1,$details['45']) ?>>
                  Dificultades con su higiene
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[46][1]" value="1-1" <?php echo validateIsChecked(1,$details['46']) ?>>
                  Dificultades para realizar actividades en casa
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[22][1]" value="1-1" <?php echo validateIsChecked(1,$details['22']) ?>>
                  No respeta límites
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[47][1]" value="1-1" <?php echo validateIsChecked(1,$details['47']) ?>>
                  Rebeldía
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[48][1]" value="1-1" <?php echo validateIsChecked(1,$details['48']) ?>>
                  Adicciones
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[23][1]" value="1-1" <?php echo validateIsChecked(1,$details['23']) ?>>
                  <b>Emociones</b>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[50][1]" value="1-1" <?php echo validateIsChecked(1,$details['50']) ?>>
                  Cuesta comprender emociones
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[51][1]" value="1-1" <?php echo validateIsChecked(1,$details['51']) ?>>
                  No percibe redes de apoyo
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[52][1]" value="1-1" <?php echo validateIsChecked(1,$details['52']) ?>>
                  Siente que no pertenece a algún lugar
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[53][1]" value="1-1" <?php echo validateIsChecked(1,$details['53']) ?>>
                  Percepción negativa del entorno
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[54][1]" value="1-1" <?php echo validateIsChecked(1,$details['54']) ?>>
                  Ausencia de metas
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Quién cuida al paciente:</label>
            <div class="col-md-6">
              <input type="text" id="dt-29" name="details[29]" value="<?php echo $details['29'] ?>" class="form-control" placeholder="Quién cuida al paciente">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Desde hace cuánto cuida al paciente:</label>
            <div class="col-md-6">
              <input type="text" id="dt-30" name="details[30]" value="<?php echo $details['30'] ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Situación actual de padres:</label>
            <div class="col-md-6">
              <input type="text" id="dt-31" name="details[31]" value="<?php echo $details['31'] ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Familiograma:</label>
            <div class="col-md-6">
              <textarea class="form-control" id="dt-32" name="details[32]"><?php echo $details['32'] ?></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Quién vive en casa:</label>
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