<?php
// autoload.php
// 10 octubre del 2014
// esta función elimina el hecho de estar agregando los modelos manualmente

function autoloadClass($modelname){
	if(Model::exists($modelname)){
		include Model::getFullPath($modelname);
	} 

}
spl_autoload_register("autoloadClass");
?>