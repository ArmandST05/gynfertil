<?php
function validateIsChecked($index, $array)
{
  $arrayValues = explode(",", $array);
  foreach ($arrayValues as $arrayValue) {
    if ($arrayValue != null && (substr($arrayValue, 0, 1) == $index)) {
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
          <div class="col-md-6">
            <label>INFORMACIÓN PACIENTE 1:</label>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Nombre Px1:</label>
            <div class="col-md-6">
              <input type="text" name="details[77]" value="<?php echo $details['77'] ?>" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Sexo:*</label>
            <div class="col-md-6">
              <select name="details[79]" class="form-control">
                <option value="1" <?php echo ($details['79'] == 1) ? "selected" : "" ?>>Masculino</option>
                <option value="2" <?php echo ($details['79'] == 2) ? "selected" : "" ?>>Femenino</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Fecha nacimiento:</label>
            <div class="col-lg-6">
              <input type="date" name="details[78]" value="<?php echo $details['78']; ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Grado de Estudios:*</label>
            <div class="col-md-6">
              <select name="details[80]" class="form-control">
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($educationLevels as $educationLevel) : ?>
                  <option value="<?php echo $educationLevel->id; ?>" <?php echo ($details['80'] == $educationLevel->id) ? "selected" : "" ?>><?php echo $educationLevel->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Ocupación:</label>
            <div class="col-md-6">
              <input type="text" name="details[81]" value="<?php echo $details['81'] ?>" class="form-control" placeholder="Ocupación">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Teléfono:</label>
            <div class="col-lg-2">
              <input type="text" name="details[82]" value="<?php echo $details['82'] ?>" class="form-control" value="<?php echo $patient->cellphone; ?>" placeholder="Celular" required maxlength="10" pattern="\d{10}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Tratamiento psicológico individual anteriormente:</label>
            <div class="col-md-4">
              <input type="text" id="dt-123" name="details[123]" value="<?php echo $details['123'] ?>" class="form-control" placeholder="Tratamiento anterior">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Tiempo:</label>
            <div class="col-md-2">
              <input type="text" id="dt-124" name="details[124]" value="<?php echo $details['124'] ?>" class="form-control" placeholder="Tiempo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Motivo de tratamiento anterior:</label>
            <div class="col-lg-8">
              <input type="text" id="dt-125" name="details[125]" value="<?php echo $details['125'] ?>" class="form-control" placeholder="Motivo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">No. de relación actual:</label>
            <div class="col-md-4">
              <input type="text" id="dt-83" name="details[83]" value="<?php echo $details['83'] ?>" class="form-control" placeholder="Tratamiento anterior">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">No. de hijos de relación anterior:</label>
            <div class="col-md-4">
              <input type="text" id="dt-124" name="details[84]" value="<?php echo $details['84'] ?>" class="form-control" placeholder="No. de hijos relación anterio">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="radio">
                <label>
                  <input type="radio" name="details[85]" value="1" <?php echo ($details['85'] == 1) ? "checked" : "" ?>>
                  Padres juntos
                </label>
                <label>
                  <input type="radio" name="details[85]" value="2" <?php echo ($details['85'] == 2) ? "checked" : "" ?>>
                  Padres separados
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Presenció situaciones Px1:</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[87][0]" value="0-1" <?php echo validateIsChecked(0, $details['87']) ?>>
                  <b>Alcoholismo</b>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[88][0]" value="0-1" <?php echo validateIsChecked(0, $details['88']) ?>>
                  <b>Violencia física/verbal</b>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[89][0]" value="0-1" <?php echo validateIsChecked(0, $details['89']) ?>>
                  <b>Infidelidades</b>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[90][0]" value="0-1" <?php echo validateIsChecked(0, $details['90']) ?>>
                  <b>Celos</b>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Motivo actual de consulta Px1:</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[92][0]" value="0-1" <?php echo validateIsChecked(0, $details['92']) ?>>
                  Falta de afecto
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[96][0]" value="0-1" <?php echo validateIsChecked(0, $details['96']) ?>>
                  Temas de recreación
                </label>
              </div>
              <div class="checkbox" name="details[99][0]" value="0-1" <?php echo validateIsChecked(0, $details['99']) ?>>
                <label>
                  <input type="checkbox">
                  Filosofía, valores, creencias
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[93][0]" value="0-1" <?php echo validateIsChecked(0, $details['93']) ?>>
                  Deberes del hogar
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[65][0]" value="0-1" <?php echo validateIsChecked(0, $details['65']) ?>>
                  Sexualidad
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[68][0]" value="0-1" <?php echo validateIsChecked(0, $details['68']) ?>>
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
                  <input type="checkbox" name="details[94][0]" value="0-1" <?php echo validateIsChecked(0, $details['94']) ?>>
                  Finanzas
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[97][0]" value="0-1" <?php echo validateIsChecked(0, $details['97']) ?>>
                  Amistades
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[95][0]" value="0-1" <?php echo validateIsChecked(0, $details['95']) ?>>
                  Violencia
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[98][0]" value="0-1" <?php echo validateIsChecked(0, $details['98']) ?>>
                  Familia origen
                </label>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <label>INFORMACIÓN PACIENTE 2:</label>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Nombre Px2:</label>
            <div class="col-md-6">
              <input type="text" name="details[100]" value="<?php echo $details['100'] ?>" class="form-control" placeholder="Nombre">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Sexo:*</label>
            <div class="col-md-6">
              <select name="details[102]" class="form-control">
                <option value="1" <?php echo ($details['102'] == 1) ? "selected" : "" ?>>Masculino</option>
                <option value="2" <?php echo ($details['102'] == 2) ? "selected" : "" ?>>Femenino</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Fecha nacimiento:</label>
            <div class="col-lg-6">
              <input type="date" name="details[101]" value="<?php echo $details['101']; ?>" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="inputEmail1" class="col-lg-2 control-label">Grado de Estudios:*</label>
            <div class="col-md-6">
              <select name="details[103]" class="form-control">
                <option value="">-- SELECCIONE --</option>
                <?php foreach ($educationLevels as $educationLevel) : ?>
                  <option value="<?php echo $educationLevel->id; ?>" <?php echo ($details['103'] == $educationLevel->id) ? "selected" : "" ?>><?php echo $educationLevel->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Ocupación:</label>
            <div class="col-md-6">
              <input type="text" name="details[104]" value="<?php echo $details['104'] ?>" class="form-control" placeholder="Ocupación">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Teléfono:</label>
            <div class="col-lg-2">
              <input type="text" name="details[105]" value="<?php echo $details['105'] ?>" class="form-control" placeholder="Celular">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Tratamiento psicológico individual anteriormente:</label>
            <div class="col-md-4">
              <input type="text" id="dt-126" name="details[126]" value="<?php echo $details['126'] ?>" class="form-control" placeholder="Tratamiento anterior">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">Tiempo:</label>
            <div class="col-md-2">
              <input type="text" id="dt-127" name="details[127]" value="<?php echo $details['127'] ?>" class="form-control" placeholder="Tiempo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label for="" class="col-lg-2 control-label">Motivo de tratamiento anterior:</label>
            <div class="col-lg-8">
              <input type="text" id="dt-128" name="details[128]" value="<?php echo $details['128'] ?>" class="form-control" placeholder="Motivo">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">No. de relación actual:</label>
            <div class="col-md-4">
              <input type="text" id="dt-106" name="details[106]" value="<?php echo $details['106'] ?>" class="form-control" placeholder="Tratamiento anterior">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label">No. de hijos de relación anterior:</label>
            <div class="col-md-4">
              <input type="text" id="dt-107" name="details[107]" value="<?php echo $details['107'] ?>" class="form-control" placeholder="No. de hijos relación anterio">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="radio">
                <label>
                  <input type="radio" name="details[108]" value="1" <?php echo ($details['108'] == 1) ? "checked" : "" ?>>
                  Padres juntos
                </label>
                <label>
                  <input type="radio" name="details[108]" value="2" <?php echo ($details['108'] == 2) ? "checked" : "" ?>>
                  Padres separados
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Presenció situaciones Px2:</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[87][0]" value="1-1" <?php echo validateIsChecked(1, $details['87']) ?>>
                  <b>Alcoholismo</b>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[88][0]" value="1-1" <?php echo validateIsChecked(1, $details['88']) ?>>
                  <b>Violencia física/verbal</b>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[89][0]" value="1-1" <?php echo validateIsChecked(1, $details['89']) ?>>
                  <b>Infidelidades</b>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[90][0]" value="1-1" <?php echo validateIsChecked(1, $details['90']) ?>>
                  <b>Celos</b>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label>Motivo actual de consulta Px2:</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[92][0]" value="1-1" <?php echo validateIsChecked(1, $details['92']) ?>>
                  Falta de afecto
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[96][0]" value="1-1" <?php echo validateIsChecked(1, $details['96']) ?>>
                  Temas de recreación
                </label>
              </div>
              <div class="checkbox" name="details[99][0]" value="1-1" <?php echo validateIsChecked(1, $details['99']) ?>>
                <label>
                  <input type="checkbox">
                  Filosofía, valores, creencias
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[93][0]" value="1-1" <?php echo validateIsChecked(1, $details['93']) ?>>
                  Deberes del hogar
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[65][0]" value="1-1" <?php echo validateIsChecked(1, $details['65']) ?>>
                  Sexualidad
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[68][0]" value="1-1" <?php echo validateIsChecked(1, $details['68']) ?>>
                  Otros
                </label>
                <input type="text" id="dt-129" name="details[129]" value="<?php echo $details['129'] ?>" class="form-control">
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[94][0]" value="1-1" <?php echo validateIsChecked(1, $details['94']) ?>>
                  Finanzas
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[97][0]" value="1-1" <?php echo validateIsChecked(1, $details['97']) ?>>
                  Amistades
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[95][0]" value="1-1" <?php echo validateIsChecked(1, $details['95']) ?>>
                  Violencia
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="details[98][0]" value="1-1" <?php echo validateIsChecked(1, $details['98']) ?>>
                  Familia origen
                </label>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <label>Información general:</label>
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
            <label class="col-lg-2 control-label">Tiempo:</label>
            <div class="col-md-6">
              <input type="text" id="dt-56" name="details[56]" value="<?php echo $details['56'] ?>" class="form-control" placeholder="Tiempo de estado civil">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-lg-2 control-label">Hijos relación:</label>
            <div class="col-md-6">
              <input type="text" id="dt-76" name="details[76]" value="<?php echo $details['76'] ?>" class="form-control" placeholder="Hijos">
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
            <label class="col-lg-2 control-label">Tratamiento psicológico anteriormente en pareja:</label>
            <div class="col-md-4">
              <input type="text" id="dt-1" name="details[1]" value="<?php echo $details['1'] ?>" class="form-control" placeholder="Tratamiento anterior">
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

        <div class="row">
          <div class="form-group">
            <div class="col-lg-offset-10 col-lg-2">
              <input type="hidden" id="patientId" name="patientId" value="<?php echo $patient->id; ?>">
              <input type="hidden" name="patientTreatmentId" value="<?php echo $patientTreatmentId; ?>">
              <button type="submit" class="btn btn-primary" id="updatePatient">Actualizar Datos</button>
            </div>
          </div>
        </div>
    </form>
  </div>
</div>