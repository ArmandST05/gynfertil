  <?php
 $pac = PatientData::estatus_paciente();
 
 

  require("class.phpmailer.php");
 $msg = ""; $action=isset($_POST['action'])  ? $_POST['action'] : null ;
 if ($action== "send") {
    $varname = $_FILES['archivo']['name'];
    $vartemp = $_FILES['archivo']['tmp_name'];
  
  $mail = new PHPMailer();
  $mail->Host = "Technoconsulting";
  $mail->From = "contacto.gynfertil@ gmail.com";
  $mail->FromName = "Gynfertil";
  $mail->Subject = $_POST['asunto'];
  $mail->AddAddress($_POST['destino']);
  if ($varname != "") {
    $mail->AddAttachment($vartemp, $varname);
  }
  $body = "<img src='http://powererpintera.com.mx/bookmedik-master/assets/felicitacion.jpg' rows='20'>";
  //$body.= "<i>Enviado por Gynfertil"."<br>";
  //$mail->AddEmbeddedImage('assets/gyn.png', $type = "assets/gyn.png");
  $mail->Body = $body;
  //$mail->AddAttachment("assets/gyn.gif");
  $mail->IsHTML(true);
  $mail->Send();
  $msg = "Mensaje enviado correctamente";
}




?>

<div class="row" >
	<div class="col-md-12">
<div class="btn-group pull-right">
<script src="assets/jquery.searchable-1.0.0.min.js"></script>
<!--script language="JavaScript" type="text/javascript" src="assets/ajax.js"></script-->
<script type="text/javascript">

$(document).ready(function() {
  // Instrucciones a ejecutar al terminar la carga



     var valor=($("#fecha").val());

    MostrarConsulta('assets/consulta.php?valor='+valor);
  
  function objetoAjax(){
  var xmlhttp=false;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
      }
  }

  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

function MostrarConsulta(datos){
  //alert(datos);
  divResultado = document.getElementById('destinoo');
  ajax=objetoAjax();
  ajax.open("GET", datos);
  ajax.onreadystatechange=function() {
    if (ajax.readyState==4) {
      divResultado.innerHTML = ajax.responseText

    
var clon = $("#destinoo").text(); 
 $("#destino").val(clon);
    }
  }
  ajax.send()

}
  
});

function mensa(){
  alert('Enviado Correctamente');
}
 

</script>
</div>
<d<div class="row">
  <div class="col-md-12">
      <h4 class="title">Felicitación</h4>

<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="view" value="correo">
      
  <div class="form-group">
    <div class="col-lg-2">
		<div class="input-group">
		  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
		   <input type="submit" name="btsend" class="btn btn-warning btn-sm" onclick="mensa();" value="Enviar">
  	</div>
    </div>
    <div class="col-lg-8">
    <input type="text" name="destino" id="destino" value=""  class="form-control" placeholder="Para" autofocus autocomplete="off">
        <input type="text" name="asunto" id="asunto" class="form-control" placeholder="Asunto" value="GYNFERTIL" autofocus autocomplete="off">

    </div>
     <div class="col-lg-2">
  <span class="btn btn-warning btn-sm"><i class="fa fa-paperclip"></i></span>


    <input type="file" name="archivo" >
    <input type="hidden" name="action"  value="send" />
    </div>
    </div>
    <div class="form-group">
       <div class="col-lg-1">
    </div>
    <div class="col-lg-4">
 <input name="fecha" id="fecha"  value="<?php echo date("m-d") ?>" class="form-control" required type="hidden">
   

    </div>
 </div>
   <ul style="display:none" id="destinoo" name="destin0o"></ul>

    <div class="login-logo ">
      <img src="assets/felicitacion.jpg" name="mensaje" id="mensaje"  ></img>
    </div>
  </div> 
</form>
</div>
</div>