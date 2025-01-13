<?php
$bdd = Database::getConPdo();
$ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
$ti_usua = UserData::get_tipo_usuario($ti_user);
$medicData = MedicData::getByUserId($ti_user);

foreach ($ti_usua as $key) {
  $tipo2 = $key->tipo_usuario;
}
$fecha = isset($_GET["fecha"])  ? $_GET['fecha'] :  date('Y-m-d');
$fecha_chi = isset($_GET["fecha"])  ? $_GET['fecha'] :  date('Y-m-d');
$originalDate = $fecha_chi;
$newDate = date("Y-m-d", strtotime($originalDate));

$fecha = isset($_GET["fecha"])  ? $_GET['fecha'] :  date('Y-m-d');

$fecha1 = $_GET["fecha1"] . " 00:00:01";

$fecha2 = $_GET["fecha2"] . " 23:59:59";

if ($tipo2 == "do") {
  $medicId = $medicData->id;
  if ($medicData->category_id == 8) {
    $sql = "select  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,r.time_at_final,date_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,
    r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas, 
    TRIM(p.name)patient_name,TRIM(p.tel) patient_tel
    FROM reservation r
    LEFT JOIN pacient p ON r.pacient_id = p.id
    WHERE (medic_id='$medicId' OR id_col=6 OR id_col=7 OR id_col = 10)
    AND date_at >= '$fecha1' AND date_at <= '$fecha2' 
    order by date_at ASC";
  } else {
    $sql = "select  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,r.time_at_final,date_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,
      r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas, 
      TRIM(p.name)patient_name,TRIM(p.tel) patient_tel
      FROM reservation r
      LEFT JOIN pacient p ON r.pacient_id = p.id
      WHERE medic_id='$medicId' 
      AND date_at >= '$fecha1' AND date_at <= '$fecha2' 
      order by date_at ASC";
  }
} else if ($tipo2 == "au") {
  $sql = "select  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,r.time_at_final,date_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,
      r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas, 
      TRIM(p.name)patient_name,TRIM(p.tel) patient_tel
      FROM reservation r
      LEFT JOIN pacient p ON r.pacient_id = p.id
      WHERE negritas='1' AND date_at >= '$fecha1' AND date_at <= '$fecha2' 
      order by date_at ASC";
} else {
  $sql = "select  r.clave,r.id,r.pacient_id,r.medic_id,r.date_at,date_at_final,r.time_at_final,DATE_FORMAT(r.time_at,'%h:%i %p') time_at,
    r.user_id,r.note,r.status_reser,r.color,r.color_letra,negritas,  
    TRIM(p.name)patient_name,TRIM(p.tel) patient_tel
    FROM reservation r
    LEFT JOIN pacient p ON r.pacient_id = p.id
    WHERE date_at >= '$fecha1' AND date_at <= '$fecha2' 
    order by date_at ASC";
}
$req = $bdd->prepare($sql);
$req->execute();
$events = $req->fetchAll();

$tipo = ReservationData::get_tipo_cal();
?>
<?php

?>
<style>
  #delay1 {

    font-size: 24px;
    color: red;
  }
</style>
<script src="plugins/jquery/jquery-2.1.4.min.js"></script>
<link href='assets/css/fullcalendar.css' rel='stylesheet' />
<link href='assets/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='assets/js/moment.min.js'></script>
<script src='assets/js/jquery-ui.min.js'></script>
<script src='assets/js/fullcalendar.min.js'></script>

<?php if ($tipo2 != "ve") : ?>
<a href="./index.php?view=newreservation" class="btn btn-info btn-xs"><i class='fas fa-user-alt'></i> Nueva cita paciente </a>
<a href="./index.php?view=newreservationdoc" class="btn btn-default btn-xs"><i class='fa fa-user-md'></i> Nueva cita doctor</a>
<?php endif; ?>
<a href="./index.php?view=hometodo" class="btn btn-default btn-xs"><i class="far fa-calendar-alt"></i> Mostrar todo el calendario</a>

<?php if ($tipo2 == "su" || $tipo2 == "sub" || $tipo2 == "r" || $tipo2 == "ve") { ?>
  <a href="./index.php?view=sales/new" class="btn btn-success btn-xs"><i class='fa fa-dollar-sign'></i> Realizar venta </a>
<?php }; ?>
<form class="form-horizontal" role="form">
  <input type="hidden" name="view" value="reservations_historial">
  <div class="form-group">
    <div class="col-lg-6">
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-search"></i></span>
        <input type="text" name="bus" id="search" class="form-control" placeholder="Buscar paciente" autofocus autocomplete="off">


      </div>
    </div>
  </div>


</form>
<div class="col-lg-3">
  <input id="fecha" type="date" class="form-control" value="<?php echo $newDate ?>" name="fecha" onChange="prueba();">




</div>

<script type="text/javascript">
  function prueba() {
    var fecha = document.getElementById('fecha').value;

    $('#calendar').fullCalendar('gotoDate', fecha);
    //alert(fecha);
  }
</script>

<?php if ($tipo2 == "su" || $tipo2 == "sub" || $tipo2 == "do") { ?>

  <style>
    p {
      color: #000;
    }
  </style>
  <script>
    $(document).ready(function() {


      $('#calendar').fullCalendar({
        //theme: true,

        header: {

          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: '<?php echo $fecha ?>',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        height: 2750,
        navLinks: true,
        defaultView: 'agendaDay',


        select: function(start, end) {


          var hora = (moment(start).format('YYYY-MM-DD HH:mm:ss'));
          //$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
          //$('#ModalAdd').modal('show');
          document.location.href = "./?view=newreservation&start=" + hora + "";

        },


        eventRender: function(event, element) {
          element.bind('click', function() {
            var id = (event.id);
            var id_pac = (event.id_pac);
            var fecha = (event.fecha);
            var url = "./?view=reservations/details&id=" + id + "&id_paciente=" + id_pac + "&fecha=" + fecha + "";

            window.open(url, "_blank");
            //window.open = "./?view=reservations/details&id=" + id + "&id_paciente=" + id_pac + "&fecha=" + fecha + ", '_blank')";


          });


        },

        eventAfterRender: function(event, element, view) {

          $(document.createElement("input")).attr({
            id: event.id,
            name: 'est',
            value: event.id,
            type: 'checkbox',
            checked: true
          });


          var negritas = (event.negritas);
          if (negritas == 1) {
            //$(element).css("font-weight","bold");
            //$(element).css("font-family","Arial Black");
          } else {

          }
        },

        events: [


          <?php

          $paciente = "";
          $tel = "";
          $ti_us = "";
          $nota = "";
          foreach ($events as $event) :
            $nota = addcslashes($event['note'], "\n\r");
            $hora = $event['time_at'];
            $nota = addcslashes($event['note'], "\n\r");
            $start = explode(" ", $event['date_at']);
            if ($start[1] == '00:00:00') {
              $start = $start[0];
            } else {
              $start = $event['date_at'];
            }
            $end = $event['date_at_final'];

            $paciente = $event['patient_name'];
            $tel =  $event['patient_tel'];

            foreach ($tipo as $key1) {
              if ($event["status_reser"] == $key1->id) {
                $ti_us = $key1->descripcion;
              }
            }

          ?>

            {
              id: '<?php echo $event['id']; ?>',
              title: '<?php echo $paciente . ' ' . $ti_us . '\n' . $tel . '\n' . $nota ?>',
              start: '<?php echo $start; ?>',
              end: '<?php echo $end; ?>',
              color: '<?php echo $event['color']; ?>',
              borderColor: "#9FE1E7",
              textColor: '#000000',
              id_pac: '<?php echo $event['pacient_id']; ?>',
              negritas: '<?php echo $event['negritas']; ?>',

            },

          <?php endforeach; ?>


        ]


      });

      //$( "div .fc-content").css("font-weight","bold");
    });
  </script>
<?php  } ?>


<?php if ($tipo2 == "r") { ?>

  <style>
    p {
      color: #000;
    }
  </style>
  <script>
    $(document).ready(function() {
      $('#calendar').fullCalendar({
        //theme: true,

        header: {

          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: '<?php echo $fecha ?>',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        height: 2750,
        navLinks: true,
        defaultView: 'agendaDay',

        select: function(start, end) {


          var hora = (moment(start).format('YYYY-MM-DD HH:mm:ss'));
          //$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
          //$('#ModalAdd').modal('show');
          document.location.href = "./?view=newreservation&start=" + hora + "";

        },



        eventRender: function(event, element) {
          element.bind('click', function() {
            var id = (event.id);
            var id_pac = (event.id_pac);
            var url = "./?view=editreservation&id=" + id + "&id_paciente=" + id_pac + "";
            //var url="./?view=sales/new-details&id_paciente=" + id_pac + "";

            window.open(url, "_blank");

          });

        },


        eventAfterRender: function(event, element, view) {
          /*var negritas=(event.negritas);
          if(negritas==1){
          //$(element).css("font-weight","bold");
          //$(element).css("font-family","Arial Black");
         }else{

       }*/
        },
        events: [

          <?php

          $paciente = "";
          $tel = "";
          $ti_us = "";
          $nota = "";


          foreach ($events as $event) :

            //$pacient  = $event->getPacient();
            $hora = $event['time_at'];
            $nota = addcslashes($event['note'], "\n\r");
            $start = explode(" ", $event['date_at']);
            if ($start[1] == '00:00:00') {
              $start = $start[0];
            } else {
              $start = $event['date_at'];
            }
            $end = $event['date_at_final'];

            $paciente = $event['patient_name'];
            $tel =  $event['patient_tel'];

            foreach ($tipo as $key1) {
              if ($event["status_reser"] == $key1->id) {
                $ti_us = $key1->descripcion;
              }
            }

          ?>

            {
              id: '<?php echo $event['id']; ?>',
              title: '<?php echo $paciente . ' ' . $ti_us . '\n' . $tel . '\n' . $nota ?>',
              start: '<?php echo $start; ?>',
              end: '<?php echo $end; ?>',
              color: '<?php echo $event['color']; ?>',
              borderColor: "#9FE1E7",
              textColor: '#000000',
              id_pac: '<?php echo $event['pacient_id']; ?>',
              negritas: '<?php echo $event['negritas']; ?>',



            },
          <?php endforeach; ?>

        ]


      });

    });
  </script>
<?php  } ?>


<?php if ($tipo2 == "ve") { ?>
  <style>
    p {
      color: #000;
    }
  </style>
  <script>
    $(document).ready(function() {
      $('#calendar').fullCalendar({

        header: {

          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: '<?php echo $fecha ?>',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        height: 2750,
        navLinks: true,
        defaultView: 'agendaDay',

        select: function(start, end) {
          /*var hora = (moment(start).format('YYYY-MM-DD HH:mm:ss'));
          document.location.href = "./?view=newreservation&start=" + hora + "";
          */
        },

        eventRender: function(event, element) {
          element.bind('click', function() {
            /*var id = (event.id);
            var id_pac = (event.id_pac);
            var url = "./?view=editreservation&id=" + id + "&id_paciente=" + id_pac + "";
            window.open(url, "_blank");*/
          });

        },


        eventAfterRender: function(event, element, view) {
        },
        events: [

          <?php
          $paciente = "";
          $tel = "";
          $ti_us = "";
          $nota = "";


          foreach ($events as $event) :

            $hora = $event['time_at'];
            $nota = addcslashes($event['note'], "\n\r");
            $start = explode(" ", $event['date_at']);
            if ($start[1] == '00:00:00') {
              $start = $start[0];
            } else {
              $start = $event['date_at'];
            }
            $end = $event['date_at_final'];

            $paciente = $event['patient_name'];
            $tel =  $event['patient_tel'];

            foreach ($tipo as $key1) {
              if ($event["status_reser"] == $key1->id) {
                $ti_us = $key1->descripcion;
              }
            }

          ?>

            {
              id: '<?php echo $event['id']; ?>',
              title: '<?php echo $paciente . ' ' . $ti_us . '\n' . $tel . '\n' . $nota ?>',
              start: '<?php echo $start; ?>',
              end: '<?php echo $end; ?>',
              color: '<?php echo $event['color']; ?>',
              borderColor: "#9FE1E7",
              textColor: '#000000',
              id_pac: '<?php echo $event['pacient_id']; ?>',
              negritas: '<?php echo $event['negritas']; ?>',

            },
          <?php endforeach; ?>

        ]

      });

    });
  </script>
<?php  } ?>


<?php if ($tipo2 == "a" || $tipo2 == "au") { ?>

  <style>
    p {
      color: #000;
    }
  </style>
  <script>
    $(document).ready(function() {

      $('#calendar').fullCalendar({
        // theme: true,
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: '<?php echo date('Y-m-d'); ?>',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        height: 2750,

        select: function(start, end) {


          var hora = (moment(start).format('YYYY-MM-DD HH:mm:ss'));
          //$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
          //$('#ModalAdd').modal('show');
          document.location.href = "./?view=newreservation&start=" + hora + "";

        },


        eventRender: function(event, element) {
          element.bind('click', function() {
            var id = (event.id);
            var id_pac = (event.id_pac);
            var url = "./?view=editreservationenfermera&id=" + id + "&id_paciente=" + id_pac + "";
            window.open(url, "_blank");


          });

        },

        eventAfterRender: function(event, element, view) {
          var negritas = (event.negritas);
          if (negritas == 1) {
            //sss$(element).css("font-weight","bold");
            //$(element).css("font-family","Arial Black");
          } else {

          }
        },
        events: [

          <?php


          $paciente = "";
          $tel = "";
          $ti_us = "";
          $nota = "";
          foreach ($events as $event) :

            //$pacient  = $event->getPacient();
            $hora = $event['time_at'];
            $nota = addcslashes($event['note'], "\n\r");

            $start = explode(" ", $event['date_at']);
            if ($start[1] == '00:00:00') {
              $start = $start[0];
            } else {
              $start = $event['date_at'];
            }
            $end = $event['date_at_final'];

            $paciente = $event['patient_name'];
            $tel =  $event['patient_tel'];

            foreach ($tipo as $key1) {
              if ($event["status_reser"] == $key1->id) {
                $ti_us = $key1->descripcion;
              }
            }

          ?>

            {
              id: '<?php echo $event['id']; ?>',
              title: '<?php echo $paciente . ' ' . $ti_us . '\n' . $tel . '\n' . $nota ?>',
              start: '<?php echo $start; ?>',
              end: '<?php echo $end; ?>',
              color: '<?php echo $event['color']; ?>',
              borderColor: "#9FE1E7",
              textColor: '#000000',
              id_pac: '<?php echo $event['pacient_id']; ?>',
              negritas: '<?php echo $event['negritas']; ?>',



            },
          <?php endforeach; ?>

        ]


      });

    });
  </script>
<?php  } ?>

<body>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header" data-background-color="blue">
        </div>
        <div class="card-content table-responsive">
          <div id="calendar"></div>
          <input id="tipoo" type="hidden" value="<?php echo $tipo2 ?>" name="tipoo">

        </div>
      </div>
    </div>
  </div>