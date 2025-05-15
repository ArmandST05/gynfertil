<!DOCTYPE html>
<?php
$user = UserData::getLoggedIn();
$userType = null;
$patient = null;
$userName = null;
$medic = null;
if($user){
  $userType = $user->user_type;
  $userName = $user->username;
  $medic = MedicData::getByUserId($user->id);
  if ($_SESSION['typeUser'] == "pa") {
    $patient = PatientData::getByUserId($user->id);
  }
}
$date = date("Y-m-d");
date_default_timezone_set('America/Mexico_City');

require_once 'vendor/autoload.php';
?>
<html>

<head>
  <meta charset="UTF-8">
  <title>POWER DR</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link rel="icon" href="assets/powerdr-icon.png">

  <!-- Jquery -->
  <script src="assets/jquery-2.1.1.min.js" type="text/javascript"></script>

  <!-- Bootstrap 3.3.4 -->
  <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Font Awesome Icons -->
  <link href="plugins/font-awesome/css/all.min.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="plugins/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link href="plugins/dist/css/skins/skin-blue-light.min.css" rel="stylesheet" type="text/css" />
  <!--link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css"-->
  <link href="plugins/colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css">


  <!-- SweetAlert -->
  <link href="plugins/sweetalert/min.css" rel="stylesheet" type="text/css" />
  <script src="plugins/sweetalert/min.js"></script>

  <script type='text/javascript' src='plugins/nicedit/nicEdit.js'></script>
  <!--<?php if ($userType == "su" || $userType == "do") : ?>
    <script type='text/javascript' src='plugins/nicedit/nicEdit.js'></script>
    <script type='text/javascript'>
      bkLib.onDomLoaded(function() {
        nicEditors.allTextAreas()
      });
    </script>
  <?php endif; ?>-->

  <script src="plugins/jquery/jquery-2.1.4.min.js"></script>
  <script src="plugins/morris/raphael-min.js"></script>
  <script src="plugins/morris/morris.js"></script>
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <link rel="stylesheet" href="plugins/morris/example.css">
  <script src="plugins/jspdf/jspdf.min.js"></script>
  <script src="plugins/jspdf/jspdf.plugin.autotable.js"></script>

  <?php if (isset($_GET["view"]) && $_GET["view"] == "sales") : ?>
    <script type="text/javascript" src="plugins/jsqrcode/llqrcode.js"></script>
    <script type="text/javascript" src="plugins/jsqrcode/webqr.js"></script>
  <?php endif; ?>

  <!-- Sweet Alert -->
  <script src="plugins/sweetalert/min.js"></script>
  <!--  Sweet Alert-->
  <!-- ColorPicker -->
  <script src="plugins/colorpicker/dist/js/bootstrap-colorpicker.js"></script>
  <!-- ColorPicker -->
  <!-- Select2 -->
  <link href="plugins/select2/select2.min.css" rel="stylesheet" />
  <script src="plugins/select2/select2.min.js"></script>
  <!-- Select2 -->
  <!-- CALENDAR-->
  <script src='assets/js/moment.min.js'></script>
  <script src='assets/js/jquery-ui.min.js'></script>
  <link href='node_modules/fullcalendar/main.min.css' rel='stylesheet' />
  <script src='node_modules/fullcalendar/main.min.js'></script>
  <script src='node_modules/fullcalendar/locales/es.js'></script>
  <!-- CALENDAR -->

  <!-- Charts -->
  <script src="plugins/chart/chart.min.js"></script>
  <!-- Charts -->
</head>

<body onload='' class="<?php if (isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])) : ?>  skin-blue-light sidebar-mini <?php else : ?>login-page<?php endif; ?>">
  <div class="background">
    <div class="wrapper">
      <!-- Main Header -->
      <?php if (isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])) : ?>
        <header class="main-header">
          <!-- Logo -->
          <a href="./" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>P</b>D</span>
            <!-- logo for regular state and mobile devices -->
            <img src="" width="150">
          </a>

          <!-- Header Navbar -->
          <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"><i class="fas fa-bars"></i>
              <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
              <ul class="nav navbar-nav">

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                  <!-- Menu Toggle Button -->
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!-- The user image in the navbar-->
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    <span class=""><i class="fas fa-user"></i> <?php echo $userName ?> </span>
                  </a>
                </li>
                <li class="dropdown user user-menu">
                  <!-- Menu Toggle Button -->
                  <a href="./logout.php" class="dropdown-toggle">Salir <i class="fas fa-sign-out-alt"></i></a>
                </li>
                <!-- Control Sidebar Toggle Button -->
              </ul>
            </div>
          </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

          <!-- sidebar: style can be found in sidebar.less -->
          <section class="sidebar">

            <!-- Sidebar Menu  ADMINISTRADOR-->
            <ul class="sidebar-menu">

              <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "su")) : ?>
                <meta charset="UTF-8">
                <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
                <!--li><a href="./?view=sell"><i class='fa fa-usd'></i> <span>Vender</span></a></li-->
                <li><a href="./?view=patients/index"><i class='fas fa-user-alt'></i> <span>Pacientes</span></a></li>
                 <li><a href="./?view=patients/export"><i class='fas fa-user-alt'></i> <span>Respaldo</span></a></li>

                <li><a href="./?view=medics/index"><i class='fa fa-user-md'></i> <span>Psicólogos</span></a></li>

                <li class="treeview">
                  <a href="#"><i class='fa fa-th-list'></i> <span>Catálogos</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <!--<li><a href="./?view=diagnostics/index">Diagnósticos</a></li>-->
                    <!--<li><a href="./?view=medicines/index">Medicamentos</a></li>-->
                    <li><a href="./?view=medic-categories/index">Especialidades Psicólogo</span></a></li>
                    <li><a href="./?view=companies/index">Empresas</span></a></li>
                  </ul>
                </li>

                <li class="treeview">
                  <a href="#"><i class="fas fa-pills"></i> <span>Productos e insumos</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="./?view=products/index">Productos</a></li>
                    <li><a href="./?view=supplies/index">Insumos</a></li>
                  </ul>
                </li>

                <li class="treeview">
                  <a href="#"><i class='fas fa-money-bill-alt'></i> <span>Gastos</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="./?view=expense-categories/index">Categoría gastos</a></li>
                    <li><a href="./?view=expense-concepts/index">Conceptos gastos</a></li>
                    <li><a href="./?view=expenses/index&limit">Gastos</a></li>
                  </ul>
                </li>

                <li class="treeview">
                  <a href="#"><i class='fa fa-database'></i> <span>Ingresos</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="./?view=income-concepts/index">Conceptos</a></li>
                    <li><a href="./?view=sales/index">Ingresos</a></li>
                    <!--<li><a href="./?view=sales/receivable-accounts">Cuentas por cobrar</a></li>-->
                    <li><a href="./?view=cashier-balance/index">Cortes</a></li>
                    <li><a href="./?view=cashier-balance/index-personal">Cortes Personal</a></li>
                  </ul>
                </li>

                <li class="treeview">
                  <a href="#"><i class='fas fa-boxes'></i> <span>Inventario</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="./?view=inventory/index-products">Inventario Productos</a></li>
                    <li><a href="./?view=inventory/index-supplies">Inventario Insumos</a></li>
                    <li><a href="./?view=inventory/index-outputs">Salidas</a></li>
                  </ul>
                </li>

                <li class="treeview">
                  <a href="#"><i class='fas fa-file-alt'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="./?view=reports/logs">Acciones del sistema</a></li>
                    <li><a href="./?view=reports/status-patients">Entrevistas y bajas</a></li>
                    <li><a href="./?view=reports/scheduled-patients">Pacientes agendados</a></li>
                    <li><a href="./?view=reports/reservation-notifications">Recordatorios citas</a></li>
                    <li><a href="./?view=reports/not-scheduled-patients-notifications">Recordatorios no agendados</a></li>
                    <li><a href="./?view=reports/sales">Ventas</a></li>
                    <!--
                    <li><a href="./?view=reports/incomeexpenses">Reporte Ingresos y Egresos</a></li>
                    <li><a href="./?view=reportsSellCir">Cirugías por cobrar y cobradas</a></li>
                    <li><a href="./?view=reportsSell">Cuentas por cobrar y cobradas</a></li>
                    <li><a href="./?view=reportsExp">Cuentas por pagar y pagadas</a></li>
                    <li><a href="./?view=utilidad">Margen de utilidad</a></li>
                    <li><a href="./?view=reports/invoices">Facturado</a></li>
                    <li><a href="./?view=reports/noinvoice">No facturado</a></li>-->
                  </ul>
                </li>

                <li><a href="./?view=emails/new"><i class='fas fa-envelope'></i> <span>Felicitación</span></a></li>
                <li class="treeview">
                  <a href="#"><i class='fas fa-cog'></i> <span>Configuración</span> <i class="fas fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="./?view=configuration/edit-clinic-profile">Perfil Clínica</a></li>
                    <?php if (isset($medic)) : ?>
                      <li><a href="./?view=configuration/edit-medic-profile">Perfil Psicólogo</a></li>
                    <?php endif; ?>
                    <li><a href="./?view=users/index">Usuarios</a></li>
                    <li><a href="./?view=branch-offices/index">Sucursales</a></li>
                  </ul>
                </li>
            </ul>
          <?php endif; ?>

          <!-- MÉDICO GENERAL ASISTENTE (SUB-ADMINISTRADOR)-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "mg")) : ?>
              <meta charset="UTF-8">
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <li><a href="./?view=patients/index"><i class='fas fa-user-alt'></i> <span>Pacientes</span></a></li>
          </ul>
        <?php endif; ?>

        <!-- Doctor-->
        <ul class="sidebar-menu">
          <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "pa")) : ?>
            <li><a href="./index.php?view=patients/medical-record"><i class='fa fa-user-alt'></i> <span>Expediente</span></a></li>
          <?php endif; ?>

          <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "do")) : ?>
            <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
            <!--li><a href="./?view=sell"><i class='fa fa-usd'></i> <span>Vender</span></a></li-->
            <!--<li><a href="./?view=patients/index"><i class='fas fa-user-alt'></i> <span>Pacientes</span></a></li>-->
            <li><a href="./?view=configuration/edit-medic-profile"><i class='fas fa-cog'></i> <span>Perfil</span></a></li>
          <?php endif; ?>

          <!-- Recepcionista-->
          <ul class="sidebar-menu">

            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "r" || $userType == "co")) : ?>
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <!--li><a href="./?view=sell"><i class='fa fa-usd'></i> <span>Vender</span></a></li-->
              <li><a href="./?view=patients/index"><i class='fas fa-user-alt'></i> <span>Pacientes</span></a></li>

              <li class="treeview">
                <a href="#"><i class='fas fa-money-bill-alt'></i> <span>Gastos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">

                  <li><a href="./?view=expenses/new">Agregar gasto</a></li>
                  <li><a href="./?view=expenses/index&limit">Ver gastos</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#"><i class='fa fa-database'></i> <span>Ingresos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=sales/index">Ingresos</a></li>
                  <li><a href="./?view=cashier-balance/index-personal">Cortes Personal</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#"><i class='fas fa-file-alt'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=reports/status-patients">Entrevistas y bajas</a></li>
                  <li><a href="./?view=reports/scheduled-patients">Pacientes agendados</a></li>
                  <li><a href="./?view=reports/reservation-notifications">Recordatorios citas</a></li>
                  <li><a href="./?view=reports/not-scheduled-patients-notifications">Recordatorios no agendados</a></li>
                  <li><a href="./?view=reports/sales">Ventas</a></li>
                </ul>
              </li>
            <?php endif; ?>

            <!-- Enfermera-->
            <ul class="sidebar-menu">

              <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "a")) : ?>
                <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
                <li><a href="./?view=patients/index"><i class='fas fa-user-alt'></i> <span>Pacientes</span></a></li>

                <li class="treeview">
                  <a href="#"><i class='fas fa-boxes'></i> <span>Inventario</span> <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                    <li><a href="./?view=inventory/index-products">Inventario Productos</a></li>
                    <li><a href="./?view=reports/inventory&ed=<?php echo $date ?>">Reporte inventario</a></li>
                  </ul>
                </li>

              <?php endif; ?>

              <!-- Enfermera-->
              <ul class="sidebar-menu">
                <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "au")) : ?>
                  <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
                  <li><a href="./index.php?view=reports/invoices"><i class='fa fa-file-text-o'></i> <span>Ventas y Compras</span></a></li>
                <?php endif; ?>
          </section>
        </aside>
      <?php endif; ?>


      <!-- Content Wrapper. Contains page content -->
      <?php if (isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])) : ?>
        <div class="content-wrapper">
          <div class="content">
            <?php View::load("index"); ?>
          </div>
        </div><!-- /.content-wrapper -->

        <footer class="main-footer">
          <div class="pull-right hidden-xs">
          </div>
          Copyright © <a href="https://www.v2technoconsulting.com" target="_blank">Techno Consulting</a> <!-- Credit: www.templatemo.com -->
        </footer>
      <?php else : ?>
        <style>
          body::after {
            content: "";
            background-image: url("assets/background.png") !important;
            background-size: cover !important;
            opacity: 0.2;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            position: absolute;
            z-index: -1;
          }
        </style>
        <div class="login-box">
          <div class="login-box-body">
            <form action="./?action=processLogin" method="post">
              <div class="form-group">
                <img src="assets/powerdr-logo.png" width="300px;">
              </div>
              <div class="form-group has-feedback">
                <input type="text" name="username" required class="form-control" placeholder="Usuario" />
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" name="password" required class="form-control" placeholder="Contraseña" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Acceder</button>
                </div><!-- /.col -->
              </div>
            </form>
          </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
      <?php endif; ?>

    </div><!-- ./wrapper -->
  </div>
  <!-- REQUIRED JS SCRIPTS -->

  <!-- jQuery 2.1.4 -->
  <!-- Bootstrap 3.3.2 JS -->
  <script src="plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="plugins/dist/js/app.min.js" type="text/javascript"></script>

  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.js"></script>

  <!-- Locales for moment.js-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.4/locale/es.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.10.15/sorting/datetime-moment.js"></script>

  <!--script src="plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="plugins/datatables/dataTables.bootstrap.min.js"></script-->
  <!-- Optionally, you can add Slimscroll and FastClick plugins.
            Both of these plugins are recommended to enhance the
            user experience. Slimscroll is required when using the
            fixed layout. -->

  <script type="text/javascript">
    var Toast = Swal.mixin({
      toast: true,
      position: 'bottom-end',
      showConfirmButton: false,
      timer: 3000
    });

    $(document).ready(function() {
      /*
        //Sweet Alert
              Swal.fire({
                  title: '¡Atención!',
                  text: 'Estimado usuario, tu sistema presenta un saldo vencido. Te invitamos a regularizarlo a la brevedad posible.',
                  icon: 'error',
                  confirmButtonText: 'Aceptar'
              });*/
    });

    function addLog(rowId, moduleId, actionTypeId, description) {
      $.ajax({
        url: "./?action=logs/add",
        type: 'POST',
        data: {
          "rowId": rowId,
          "moduleId": moduleId,
          "actionTypeId": actionTypeId,
          "description": description
        },
        success: function(data, textStatus, xhr) {

        },
        error: function() {

        }
      });
    }

    function getActualDateYmd() {
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1;
      var yyyy = today.getFullYear();
      if (dd < 10) {
        dd = '0' + dd;
      }
      if (mm < 10) {
        mm = '0' + mm;
      }
      today = yyyy + '-' + mm + '-' + dd;
      return today;
    }
  </script>
</body>

</html>