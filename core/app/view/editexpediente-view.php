    <?php 
$resu = ReservationData::get_resumen_expediente($_GET["id"]);
foreach ($resu as $key) {
 $fecha=$key->fecha;
 $res=$key->resumen;
}
    ?>
<div class="form-group">
 <div class="col-lg-12">
<form class="" role="form" method="POST" action="./?action=upd_resumen">


    <label for="inputEmail1" class="col-lg-1 control-label">Fecha: <?php echo $fecha ?></label>
    <textarea class="form-control" name="note"  rows="10" placeholder="Nota"><?php echo $res ?></textarea>
    <button type="submit" class="btn btn-info btn-xs">Actualizar</button>
    <input type="hidden" name="id_reser" value="<?php echo  $_GET["id"] ?>">
   
 </form>
 </div>
 </div>

