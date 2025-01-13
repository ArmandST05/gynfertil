<!DOCTYPE html>
<?php
date_default_timezone_set('America/Mexico_City');
require_once 'vendor/autoload.php';
$actualDate = date("Y-m-d");

$user = UserData::getLoggedIn();
//Si es un usuario doctor obtener datos para visualizar la categoría
if (isset($_SESSION["user_id"]) && $_SESSION['typeUser'] == "do") {
  $medic = MedicData::getByUserId($user->id);
}

$dateNextWeek = date("Y-m-d", strtotime('+7 days'));

//PAPANICOLAU
$futurePaps = ReservationData::getFuturePapsTestsNotificationsLimit($dateNextWeek, 5);
$totalFuturePaps = ReservationData::getTotalFuturePapsTests($dateNextWeek);
//PREGNANCY TEST
$futurePregnancyTest = PatientCategoryData::getFuturePregnancyTestsNotificationsLimit(5, date("Y-m-d"));
$totalFuturePregnancyTest = PatientCategoryData::getTotalFuturePregnancyTests(date("Y-m-d"));

//Código utilizado la primera vez que se implementó la alerta con sesión iniciada.
if (!isset($_SESSION["alert_payment"])) {
  $_SESSION["alert_payment"] = 0;
}
?>
<html>
<style type="text/css">
  div.image {
    max-width: 10px;
    max-height: 10px;
    background-image: url(data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDQwIDYwIiB3aWR0aD0iNTEyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnIGlkPSJQYWdlLTEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+PGcgaWQ9IjAwNC0tLVByZWduYW50LVdvbWFuIiBmaWxsPSJyZ2IoMCwwLDApIiBmaWxsLXJ1bGU9Im5vbnplcm8iIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xKSI+PHBhdGggaWQ9IlNoYXBlIiBkPSJtOC42ODYgNTEuMDMzYy0xLjA1MTk5NDQ4IDIuNTI3ODg1Ny0xLjYyMzc1MjE3IDUuMjI5NjYxMi0xLjY4NiA3Ljk2NyAwIC41NTIyODQ3LjQ0NzcxNTI1IDEgMSAxaDMwYy4yOTQwMDc2LS4wMDAwODYzLjU3MzA4MDMtLjEyOTU1MDIuNzYzMDE4Mi0uMzUzOTY5Ni4xODk5Mzc4LS4yMjQ0MTk0LjI3MTQ5MzEtLjUyMTA1MjYuMjIyOTgxOC0uODExMDMwNC0uMDgyLS41LTIuMDctMTIuMjQtNi4wOTEtMjAuMjgxLS45MTk1OTM5LTIuMDM4MDgzMS0xLjM4Njk2OTMtNC4yNTExMjI2LTEuMzctNi40ODcgMi4xMTU2MTYxLjQyMTYyNTMgNC4yNTE4ODA5LjczMjA1MTMgNi40LjkzbC4wNzUuMDAzYy40NTg2Nzk0LS4wMDAwMTIxLjg1ODU0MDktLjMxMjA2ODkuOTctLjc1NyAxLjYxMS02LjQ0MSAxLjMxOS0xNS4zNTkgMS4xMjYtMjEuMjYtLjA1MS0xLjU3LS4xLTIuOTMyLS4xLTMuOTYtLjAwMjc5MTMtMS45NzE0NjMxNy0uNzcxNTU5Mi0zLjg2NDY5NzUyLTIuMTQ0LTUuMjgtMS4wMDYyNzU3LTEuMDkxOTc0My0yLjQxNzIyMTQtMS43MjIyMzUyOC0zLjkwMi0xLjc0M2gtMTEuNzgzYy0xLjA1MTcxODUtLjAwMzAyODA3LTIuMDI3MjA3LjU0ODM2ODAyLTIuNTY3IDEuNDUxLTEuMzI3Mjg0MiAyLjI3MjgxMzg0LTMuMTMwNTIyIDQuMjMxNjA5MS01LjI4NiA1Ljc0Mi0uNTI5NjY5Ny4zNTAwMzMxMi0xLjA5Mzk3OTkuNjQ0NTg2OTQtMS42ODQuODc5LTIuMzc4OTQ1LjkyNzcxODAxLTMuODkzMDYzNTEgMy4yNzgzNTI3LTMuNzU0IDUuODI4LjA2Nzc4MjMyIDEuODkwMDM3Ni44OTI2NDI5MyAzLjY3MzQ1MTggMi4yODkgNC45NDktLjIwOTA5NTMuMDc0NTMxMy0uNDEzNDcxNC4xNjE2OTE3LS42MTIuMjYxLTEuMjI2NzgxMjkuNTkyMDA2MS0yLjEyMDI3NDgzIDEuNzA2NzU5Ny0yLjQzMSAzLjAzMy00LjYzNTQ4NzAxIDMuMDU0Mjg2NC03LjMzNjY4MzY1IDguMzA5OTAwMi03LjEyMiAxMy44NTcgMCA4LjMgNC4wMDUgMTIuNzIxIDcuNjg2IDE1LjAzM3ptMTIuNjI0LTQ4LjU0N2MuMTgwNTEwMi0uMzAxMjQ1MDguNTA1ODEyOC0uNDg1NzIyNDMuODU3LS40ODZoNC43NjZjLS4yMDggMS45MzctMS4xIDYtNC45MzMgNi0xLjYzMDIxNTgtLjAwMDc5NzI0LTMuMjU2NzMyNi0uMTU1MTQ1NTktNC44NTgtLjQ2MSAxLjYzMTU0MTktMS40Njg5NzYxNiAzLjAzNjE2MDktMy4xNzE4NDA3OCA0LjE2OC01LjA1M3ptLTEwLjQzNSAxMS4zNDZjLS4xMTM5NzY2LTEuNzA1Mzk4Ni44OTU3NzM1LTMuMjg2MzAwMSAyLjQ5MS0zLjkuNTY4MjA2Mi0uMjMxOTU4NTkgMS4xMTYzODUxLS41MTAyMjg3NyAxLjYzOS0uODMyIDIuMjg1MzUzNS41OTc1NTM4OSA0LjYzNzgxNjQuOTAwMDEzNCA3IC45IDQuOTkxIDAgNi42NjctNC43NzcgNi45NS04aDEuNzkxYy0xLjU5Nzk5ODggNi41OTUwMDgxNi0xLjkyMTc5ODkgMTMuNDM0NTIwNC0uOTU0IDIwLjE1MS0xLjU0Nzc0MTYtLjI5ODYyNTYtMy4xMTQwMzg1LS40OTE0MDU4LTQuNjg4LS41NzctMS4yNzYxMzczLS4wMzc4LTIuNTQ0NDc0LS4yMTMxNDc3LTMuNzgzLS41MjMtLjg0LS4yOC0xLjctLjYzMy0yLjUyNC0uOTc0LTEuMjk5ODQxMS0uNTg2MDIxNS0yLjY1MTMxNTItMS4wNTAwMTU0LTQuMDM3LTEuMzg2LTIuMTkwNTc3NC0uNjM3NjA5Mi0zLjc0NTA1ODMtMi41ODE4MTA3LTMuODg1LTQuODU5em0uNTcyIDcuMDYzYy44NjI3NzAyLS4zOTgyNTYgMS44MzM5NjU3LS40OTY0NjY3IDIuNzU5LS4yNzkuMDgxOTQ2NC4wMjcyMjIzLjE2NjkwNjkuMDQ0MzQ4Ny4yNTMuMDUxIDEuMjIzMjUzMi4zMTg2Mzg1IDIuNDE3NDc4Ny43Mzk3OTUzIDMuNTcgMS4yNTkuODYuMzU0IDEuNzQ5LjcyMSAyLjY1NSAxLjAyMyAxLjM5MzY3NDQuMzY4NjcwOCAyLjgyNTAxODMuNTc2MzYgNC4yNjYuNjE5IDIuODQ5LjIxMyA2LjA4LjQ1NCA4LjQ1IDIuMjMyLjI4NTgxMjUuMjE0MzU5NC42NjQyNzM0LjI1OTk1NjQuOTkyODIwMy4xMTk2MTUyLjMyODU0NjktLjE0MDM0MTEuNTU3MjY1Ni0uNDQ1Mjk5NC42LS44LjA0MjczNDQtLjM1NDcwMDUtLjEwNzAwNzgtLjcwNTI1NTgtLjM5MjgyMDMtLjkxOTYxNTItLjgxODUxMTYtLjYwMDA2MjUtMS43MTkwNjE3LTEuMDc5MzAwNy0yLjY3NC0xLjQyMy0xLjE1MzcxOTEtNi45MTA5NzgxLS44NTgxMzIzLTEzLjk4NjMzMzMzLjg2OC0yMC43NzdoMS4xNTZjLjk0NTYyNTIuMDMwMDUwODggMS44Mzg4MDQyLjQ0MTY0OTEzIDIuNDc2IDEuMTQxLjk5Mjk3ODkgMS4wNDE1Mjk4IDEuNTU1MjY1OSAyLjQyMDA5NzQ4IDEuNTc0IDMuODU5IDAgMS4wNjguMDQ1IDIuNDUzLjEgNC4wNDguMTgxIDUuNTMyLjQ1IDEzLjc2NS0uODcxIDE5Ljg2OS0zLjAyOS0uMzM5LTEzLjU2OC0xLjgxNy0yMi42MzktNi43MzQtMS4yMjk0NDEtMS4wODUwNTExLTIuNzM2NzM1Ni0xLjgwNjMyMjUtNC4zNTMtMi4wODMuMjU1MzE0OS0uNTI1ODMyNC42ODIwNjctLjk0OTA1NzUgMS4yMS0xLjJ6bS0yLjQ2MiAzLjEwNWguMDE1YzEuNTg3ODU1NC4wNDMzMzE1IDMuMTA4OTM4OS42NDgxNTA5IDQuMjkzIDEuNzA3LjA2NzcxNzMuMDY3MjgwMi4xNDQ0NTAyLjEyNDgyOTkuMjI4LjE3MSA1LjAzNjE1OTUgMi42NDczODM3IDEwLjQxMzA0NTcgNC41ODgyOTg2IDE1Ljk3OSA1Ljc2OC0uMDY0OTAwNiAyLjY4NzY3NjkuNDgyMjI2OCA1LjM1NDkyMjkgMS42IDcuOCAzLjE3MyA2LjMzNiA1LjA4OSAxNS4zNjkgNS43IDE4LjU1NGgtMjcuNzQ5Yy4xNTM4NjE5OS0yLjA2MDMyMTEuNjI2MTM0NTUtNC4wODQzNDY0IDEuNC02IDMuMDk4MDQzIDEuNDQwNjUzNCA2LjQ3NzQ2MjcgMi4xNzUwMTI4IDkuODk0IDIuMTUuOTI1NDc0LjAwMDA0NzQgMS44NTAyMzQ3LS4wNTEzNjUzIDIuNzctLjE1NC4zNTUxMjItLjAzOTY1NjUuNjYyMTExOC0uMjY1NzU1OS44MDUzMjkzLS41OTMxMjg4cy4xMDA5MDQ1LS43MDYyODM2LS4xMTEtLjk5NGMtLjIxMTkwNDUtLjI4NzcxNjUtLjU2MTIwNzMtLjQ0MDUyNzctLjkxNjMyOTMtLjQwMDg3MTItNC4yNzE4MjQxLjUyNzQzMTUtOC42MDMxNjAyLS4yNTMyNjg5LTEyLjQyMi0yLjIzOS0zLjQxLTEuOS03LjQ3MS01LjgyOC03LjQ3MS0xMy43NjkgMC01LjIwNyAyLjAyOS05LjIzMSA1Ljk4NS0xMnoiLz48cGF0aCBpZD0iU2hhcGUiIGQ9Im0xMS41NDIgNDQuMjM2Yy41NjguNDc0IDEuMTcyLjk3OSAxLjgwNyAxLjUyMy4zNzQyMjQuMzIwMDg3Ny45MjU3NzYuMzIwMDg3NyAxLjMgMCAuNjM1LS41NDQgMS4yMzktMS4wNDkgMS44MDctMS41MjMgMy4zNDQtMi43ODYgNS41NDQtNC42MjYgNS41NDQtNy40NzguMDQ2NjgwMi0xLjIzMTA5MS0uNDA0MDMzNC0yLjQyOTE3MjMtMS4yNTA1NjY0LTMuMzI0MjM5OC0uODQ2NTMzLS44OTUwNjc2LTIuMDE3NjUzNi0xLjQxMTgwNTYtMy4yNDk0MzM2LTEuNDMzNzYwMi0xLjMyNDE1Ny0uMDAxODA5LTIuNTkwNDE3Mi41NDI2ODI5LTMuNSAxLjUwNS0uOTA5NTgyOC0uOTYyMzE3MS0yLjE3NTg0My0xLjUwNjgwOS0zLjUtMS41MDUtMS4yMzE3ODAwMS4wMjE5NTQ2LTIuNDAyOTAwNTkuNTM4NjkyNi0zLjI0OTQzMzU4IDEuNDMzNzYwMi0uODQ2NTMyOTguODk1MDY3NS0xLjI5NzI0NjYzIDIuMDkzMTQ4OC0xLjI1MDU2NjQyIDMuMzI0MjM5OCAwIDIuODUyIDIuMiA0LjY5MiA1LjU0MiA3LjQ3OHptLTEuMDQyLTEwLjIzNmMxLjA5MzI4NjEuMDM4MTQzMyAyLjA4NTA0MDQuNjUxMTQ0OSAyLjYwOCAxLjYxMi4xNzAxNzQzLjMzMzA2MTYuNTExOTg1OS41NDMzMTgyLjg4Ni41NDVoLjAwNmMuMzc1MDY3NC0uMDAxNTUxMi43MTgxMjY5LS4yMTE2MjY5Ljg5LS41NDUuNTIzMTY2Ny0uOTYxNTk5NiAxLjUxNTkyOTctMS41NzQ3NTQzIDIuNjEtMS42MTIuNzAyMTU1OS4wMjAzNzEgMS4zNjU2NzY0LjMyNjA1NDIgMS44Mzc0NDQ0Ljg0NjUwODdzLjcxMTAyMzggMS4yMTA3MTQuNjYyNTU1NiAxLjkxMTQ5MTNjMCAxLjkxNi0xLjgxNSAzLjQzMi00LjgyMyA1Ljk0My0uMzc3LjMxNS0uNzcuNjQyLTEuMTc3Ljk4Ni0uNDA3LS4zNDQtLjgtLjY3MS0xLjE3Ny0uOTg2LTMuMDA4LTIuNTExLTQuODIzLTQuMDI3LTQuODIzLTUuOTQzLS4wNDg0NjgyLS43MDA3NzczLjE5MDc4NzU3LTEuMzkxMDM2OC42NjI1NTU2LTEuOTExNDkxMy40NzE3NjgwMi0uNTIwNDU0NSAxLjEzNTI4ODQ3LS44MjYxMzc3IDEuODM3NDQ0NC0uODQ2NTA4N3oiLz48L2c+PC9nPjwvc3ZnPg==)
  }
</style>

<head>
  <meta charset="UTF-8">
  <title>POWERDR</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link rel="icon" href="assets/powerdr-icon.png">

  <!-- Bootstrap 3.3.4 -->
  <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Font Awesome Icons -->
  <link href="plugins/font-awesome/css/all.min.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="plugins/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link href="plugins/dist/css/skins/skin-blue-light.min.css" rel="stylesheet" type="text/css" />

  <?php if (isset($_SESSION['user_id']) && ($_SESSION['typeUser'] == "su" || $_SESSION['typeUser'] == "sub" || $_SESSION['typeUser'] == "do")) {
    echo "<script type='text/javascript' src='assets/nicEdit.js'></script>
    <script type='text/javascript'>
    bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
    </script>";
  }
  ?>

  <script src="plugins/jquery/jquery-2.1.4.min.js"></script>
  <script src="plugins/morris/raphael-min.js"></script>
  <script src="plugins/morris/morris.js"></script>
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <link rel="stylesheet" href="plugins/morris/example.css">
  <script src="plugins/jspdf/jspdf.min.js"></script>
  <script src="plugins/jspdf/jspdf.plugin.autotable.js"></script>
  <?php if (isset($_GET["view"]) && $_GET["view"] == "sell") : ?>
    <script type="text/javascript" src="plugins/jsqrcode/llqrcode.js"></script>
    <script type="text/javascript" src="plugins/jsqrcode/webqr.js"></script>
  <?php endif; ?>

  <!-- Sweet Alert -->
  <script src="plugins/sweetalert/min.js"></script>
  <link rel="stylesheet" href="plugins/sweetalert/min.css" />
  <!--  Sweet Alert-->

  <!-- DATA TABLES PLUGIN-->

  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css" />
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.js"></script>
  <script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>

  <!--Revisar estilos de tabla para embriología. Marcar todas las celdas
    <link href="plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />-->

  <script src="plugins/datatables/extensions/FixedColumns/dataTables.fixedColumns.min.js"></script>
  <!--<link href="plugins/datatables/extensions/FixedColumns/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />-->
  <script src="plugins/datatables/extensions/FixedHeader/dataTables.fixedHeader.min.js"></script>
  <link href="plugins/datatables/extensions/FixedHeader/dataTables.fixedHeader.min.css" rel="stylesheet" type="text/css" />
  <!-- DATA TABLES PLUGIN-->

  <!--SELECT2 -->
  <link href="assets/select2.min.css" rel="stylesheet" />
  <script src="assets/select2.min.js"></script>
  <!--SELECT2 -->
  <style>
    .select2-container--open {
      z-index: 99999999999999 !important;
    }
  </style>
</head>

<body onload='' class="<?php if (isset($_SESSION["user_id"]) || isset($_SESSION["client_id"])) : ?>  skin-blue-light sidebar-mini <?php else : ?>login-page<?php endif; ?>">
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
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="fas fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
              <li class="dropdown notifications-menu <?php echo ($totalFuturePregnancyTest->total > 0) ? 'label-danger' : '' ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img width="80%" src="assets/icon-pregnancy.png" />
                  <span class="label <?php echo ($totalFuturePregnancyTest->total > 0) ? 'label-danger' : '' ?>"><?php echo $totalFuturePregnancyTest->total ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Tienes <?php echo $totalFuturePregnancyTest->total ?> pruebas de embarazo pendientes</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <?php foreach ($futurePregnancyTest as $pregnancyTest) : ?>
                        <li>
                          <a><b><?php echo $pregnancyTest->pregnancy_test_date_format . "|" . $pregnancyTest->patient_name ?></b><br>
                            <?php echo "Teléfono:" . $pregnancyTest->patient_tel ?><br>
                            <?php echo "Teléfono alternativo: " . $pregnancyTest->patient_tel2 ?>
                          </a>
                          <?php if ($pregnancyTest->total_notifications == 0) : ?>
                            <button class='btn btn-default btn-xs' onclick="notify('<?php echo $pregnancyTest->patient_id ?>','<?php echo $pregnancyTest->patient_tel ?>','<?php echo $pregnancyTest->patient_tel2 ?>','<?php echo $pregnancyTest->end_date ?>','2')"><i class="glyphicon glyphicon-ok"></i> Avisar</button>
                          <?php else : ?>
                            <button class='btn btn-primary btn-xs'><i class="glyphicon glyphicon-ok"></i> Avisado</button>
                          <?php endif; ?>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </li>
                  <li class="footer"><a href="./?view=notifications/pregnancyTest">Ver todas las notificaciones</a></li>
                </ul>
              </li>
              <li class="dropdown notifications-menu <?php echo ($totalFuturePaps->total > 0) ? 'label-danger' : '' ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img width="80%" src="assets/icon-uterus.png" />
                  <span class=" label <?php echo ($totalFuturePaps->total > 0) ? 'label-danger' : '' ?>"><?php echo $totalFuturePaps->total ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Tienes <?php echo $totalFuturePaps->total ?> papanicolaou pendientes.</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <?php foreach ($futurePaps as $papanicolaou) : ?>
                        <li>
                          <a><b><?php echo $papanicolaou->date_format ?> | <?php echo $papanicolaou->patient_name ?></b><br>
                            <?php echo "Teléfono:" . $papanicolaou->patient_tel ?><br>
                            <?php echo "Teléfono alternativo: " . $papanicolaou->patient_tel2 ?>
                          </a>
                          <button onclick="notify('<?php echo $papanicolaou->patient_id ?>','<?php echo $papanicolaou->patient_tel ?>','<?php echo $papanicolaou->patient_tel2 ?>','<?php echo $papanicolaou->date ?>','1')" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-ok"></i> Avisar</button>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </li>
                  <li class="footer"><a href="./?view=notifications/papanicolauTest">Ver todas las notificaciones</a></li>
                </ul>
              </li>
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class=""><?php if (isset($_SESSION["user_id"])) {
                                    echo UserData::getById($_SESSION["user_id"])->name;
                                  } ?> <b class="caret"></b> </span>

                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="./logout.php" class="btn btn-default btn-flat">Salir</a>
                    </div>
                  </li>
                </ul>
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


          <!-- ADMINISTRADOR-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "su")) : ?>
              <meta charset="UTF-8">
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

              <li><a href="./?view=patients/index"><i class='fa fa-male'></i> <span>Pacientes</span></a></li>
              <li><a href="./?view=donants/index"><i class='fas fa-hand-holding-heart'></i> <span>Donantes</span></a></li>
              <li><a href="./?view=medics/index"><i class="fa fa-user-md"></i> <span>Personal médico</span></a></li>
              <li><a href="./?view=catmedic"><i class='fa fa-th-list'></i> <span>Especialidades</span></a></li>

              <li class="treeview">
                <a href="#"><i class="fa fa-microscope"></i> <span>Laboratorio</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=embryology-procedures/index">Embriología</a></li>
                  <li><a href="./?view=andrology-procedures/index">Andrología</a></li>
                  <li><a href="./?view=reports/vitrifications">Banco de Gametos</a></li>
                </ul>
              </li>

              <!--<li class="treeview">
                <a href="#"><i class="fas fa-pills"></i> <span>Medicamentos e insumos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=products/index">Productos</a></li>
                  <li><a href="./?view=supplies/index">Insumos</a></li>
                </ul>
              </li>-->

              <li class="treeview">
                <a href="#"><i class="fas fa-money-bill-alt"></i> <span>Gastos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=catspend">Categoría gastos</a></li>
                  <li><a href="./?view=conceptspend">Conceptos gastos</a></li>
                  <li><a href="./?view=expenses&limit">Gastos</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#"><i class='fa fa-database'></i> <span>Ingresos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=concepts">Conceptos</a></li>
                  <li><a href="./?view=sales/index">Ingresos</a></li>
                  <li><a href="./?view=getSellC">Cuentas por cobrar</a></li>
                  <li><a href="./?view=sellcorte">Cortes</a></li>
                  <li><a href="./?view=sellcorteAsis">Cortes Asistente</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#"><i class='fas fa-boxes'></i> <span>Inventario</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=inventory/index-medicines">Inventario Medicamento</a></li>
                  <li><a href="./?view=inventory/index-supplies">Inventario Insumos</a></li>
                  <li><a href="./?view=res">Salidas</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fas fa-file-alt'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=reports/inventory&ed=<?php echo $actualDate ?>">Reporte inventario</a></li>
                  <li><a href="./?view=reports/inventory-medic">Reporte inventario doctoras</a></li>
                  <li><a href="./?view=reports/inventory-monthly">Reporte inventario mensual</a></li>
                  <li><a href="./?view=reportsIE">Reporte ingresos y egresos</a></li>
                  <li><a href="./?view=reportsSellCir">Cirug&iacuteas por cobrar y cobradas</a></li>
                  <li><a href="./?view=reportsSell">Cuentas por cobrar y cobradas</a></li>
                  <li><a href="./?view=reportsExp">Cuentas por pagar y pagadas</a></li>
                  <li><a href="./?view=utilidad">Margen de utilidad</a></li>
                  <!--<li><a href="./?view=reportsF">Facturado</a></li>
                  <li><a href="./?view=reportsSF">No facturado</a></li>-->
                  <li><a href="./?view=reportsAssisReser">Reporte asistencia citas</a></li>
                  <li><a href="./?view=reportsPaps">Reporte Papanicolaou</a></li>
                  <li><a href="./?view=reports/treatments">Reporte Tratamientos</a></li>
                  <li><a href="./?view=reportsPregnancies">Reporte Embarazos</a></li>
                  <li><a href="./?view=reports/transactions">Reporte Transacciones</a></li>
                </ul>
              </li>

              <li><a href="./?view=users"><i class='fa fa-cog'></i> <span>Usuarios</span></a></li>
              <li><a href="./?view=correo"><i class='fa fa-envelope'></i> <span>Felicitaci&oacuten</span></a></li>
            <?php endif; ?>
          </ul>
          <!-- Fin ADMINISTRADOR-->

          <!-- SUB-ADMINISTRADOR-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "sub")) : ?>
              <meta charset="UTF-8">
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

              <li><a href="./?view=patients/index"><i class='fa fa-male'></i> <span>Pacientes</span></a></li>
              <li><a href="./?view=medics/index"><i class="fa fa-user-md"></i> <span>Personal médico</span></a></li>
              <li><a href="./?view=catmedic"><i class='fa fa-th-list'></i> <span>Especialidades</span></a></li>

              <li class="treeview">
                <a href="#"><i class="fa fa-microscope"></i> <span>Laboratorio</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=embryology-procedures/index">Embriología</a></li>
                  <li><a href="./?view=andrology-procedures/index">Andrología</a></li>
                  <li><a href="./?view=reports/vitrifications">Banco de Gametos</a></li>
                </ul>
              </li>
              <!--<li class="treeview">
                <a href="#"><i class="fas fa-pills"></i> <span>Medicamentos e insumos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=products/index">Productos</a></li>
                  <li><a href="./?view=supplies/index">Insumos</a></li>
                </ul>
              </li>-->
              <li class="treeview">
                <a href="#"><i class="fas fa-money-bill-alt"></i> <span>Gastos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=catspend">Categoría gastos</a></li>
                  <li><a href="./?view=conceptspend">Conceptos gastos</a></li>
                  <li><a href="./?view=expenses&limit">Gastos</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fa fa-database'></i> <span>Ingresos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=concepts">Conceptos</a></li>
                  <li><a href="./?view=sales/index">Ingresos</a></li>
                  <li><a href="./?view=getSellC">Cuentas por cobrar</a></li>
                  <li><a href="./?view=sellcorte">Cortes</a></li>
                  <li><a href="./?view=sellcorteAsis">Cortes Asistente</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fas fa-boxes'></i> <span>Inventario</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=inventory/index-medicines">Inventario Medicamento</a></li>
                  <li><a href="./?view=inventory/index-supplies">Inventario Insumos</a></li>
                  <li><a href="./?view=res">Salidas</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fas fa-file-alt'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=reports/inventory&ed=<?php echo $actualDate ?>">Reporte inventario</a></li>
                  <li><a href="./?view=reports/inventory-medic">Reporte inventario doctoras</a></li>
                  <li><a href="./?view=reports/inventory-monthly">Reporte inventario mensual</a></li>
                  <li><a href="./?view=reportsIE">Reporte ingresos y egresos</a></li>
                  <li><a href="./?view=reportsSellCir">Cirug&iacuteas por cobrar y cobradas</a></li>
                  <li><a href="./?view=reportsSell">Cuentas por cobrar y cobradas</a></li>
                  <li><a href="./?view=reportsExp">Cuentas por pagar y pagadas</a></li>
                  <li><a href="./?view=utilidad">Margen de utilidad</a></li>
                  <!--<li><a href="./?view=reportsF">Facturado</a></li>
                  <li><a href="./?view=reportsSF">No facturado</a></li>-->
                  <li><a href="./?view=reportsAssisReser">Reporte asistencia citas</a></li>
                  <li><a href="./?view=reportsPaps">Reporte Papanicolaou</a></li>
                  <li><a href="./?view=reports/treatments">Reporte Tratamientos</a></li>
                  <li><a href="./?view=reportsPregnancies">Reporte Embarazos</a></li>
                  <li><a href="./?view=reports/transactions">Reporte Transacciones</a></li>
                </ul>
              </li>
              <li><a href="./?view=users"><i class='fa fa-cog'></i> <span>Usuarios</span></a></li>
              <li><a href="./?view=correo"><i class='fa fa-envelope'></i> <span>Felicitaci&oacuten</span></a></li>
            <?php endif; ?>
          </ul>
          <!-- Fin SUB-ADMINISTRADOR-->

          <!-- Doctor/Personal médico que no es de embriología-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "do") && $medic->category_id != 8) : ?>
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <li><a href="./?view=patients/index"><i class='fa fa-male'></i> <span>Pacientes</span></a></li>
            <?php endif; ?>
          </ul>
          <!-- Fin Doctor/Personal médico que no es de embriología-->

          <!-- Doctor - Embriología-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "do") && $medic->category_id == 8) : ?>
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <li><a href="./?view=patients/index"><i class='fa fa-male'></i> <span>Pacientes</span></a></li>
              <li><a href="./?view=donants/index"><i class='fas fa-hand-holding-heart'></i> <span>Donantes</span></a></li>
              <li class="treeview">
                <a href="#"><i class="fa fa-microscope"></i> <span>Laboratorio</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=embryology-procedures/index">Embriología</a></li>
                  <li><a href="./?view=andrology-procedures/index">Andrología</a></li>
                  <li><a href="./?view=reports/vitrifications">Banco de gametos</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fas fa-file-alt'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=reports/treatments">Reporte Tratamientos</a></li>
                  <li><a href="./?view=reportsPregnancies">Reporte Embarazos</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
          <!-- Fin Doctor - Embriología-->

          <!-- Personal médico - Andrología -->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "an")) : ?>
              <li><a href="./?view=patients/index"><i class='fa fa-male'></i> <span>Pacientes</span></a></li>
              <li><a href="./?view=donants/index"><i class='fas fa-hand-holding-heart'></i> <span>Donantes</span></a></li>
              <li class="treeview">
                <a href="#"><i class="fa fa-microscope"></i> <span>Laboratorio</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=andrology-procedures/index">Andrología</a></li>
                  <li><a href="./?view=reports/vitrifications">Banco de Gametos</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
          <!-- Fin Personal médico - Andrología -->

          <!-- Recepcionista-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "r")) : ?>
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <li><a href="./?view=patients/index"><i class='fa fa-male'></i> <span>Pacientes</span></a></li>
              <li class="treeview">
                <a href="#"><i class="fas fa-money-bill-alt"></i> <span>Gastos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">

                  <li><a href="./?view=buy">Agregar gasto</a></li>
                  <li><a href="./?view=expenses&limit">Ver gastos</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fa fa-database'></i> <span>Ingresos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=sales/index">Ingresos</a></li>
                  <li><a href="./?view=sellcorteAsis">Cortes Asistente</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fas fa-file-alt'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=reports/transactions">Reporte Transacciones</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
          <!-- Fin Recepcionista-->

          <!-- Ventas-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "ve")) : ?>
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <li><a href="./?view=patients/index"><i class='fa fa-male'></i> <span>Pacientes</span></a></li>
              <li class="treeview">
                <a href="#"><i class="fas fa-money-bill-alt"></i> <span>Gastos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">

                  <li><a href="./?view=buy">Agregar gasto</a></li>
                  <li><a href="./?view=expenses&limit">Ver gastos</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fa fa-database'></i> <span>Ingresos</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=sales/index">Ingresos</a></li>
                  <li><a href="./?view=sellcorteAsis">Cortes Asistente</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fas fa-file-alt'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=reports/transactions">Reporte Transacciones</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
          <!-- Fin Ventas-->

          <!-- Enfermera-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "a")) : ?>
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <li><a href="./?view=patients/index"><i class='fa fa-male'></i> <span>Pacientes</span></a></li>
              <li class="treeview">
                <a href="#"><i class='fas fa-boxes'></i> <span>Inventario</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=inventory/index-medicines">Inventario Medicamento</a></li>
                  <li><a href="./?view=inventory/index-supplies">Inventario Insumos</a></li>
                  <li><a href="./?view=res">Salidas</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#"><i class='fas fa-file-alt'></i> <span>Reportes</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                  <li><a href="./?view=reports/inventory&ed=<?php echo $actualDate ?>">Reporte inventario</a></li>
                  <li><a href="./?view=reports/inventory-medic">Reporte inventario doctoras</a></li>
                  <li><a href="./?view=reports/inventory-monthly">Reporte inventario mensual</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>
          <!-- Fin Enfermera-->

          <!-- /.sidebar-menu -->
          <!-- Auditor-->
          <ul class="sidebar-menu">
            <?php if ((isset($_SESSION["user_id"])) && ($_SESSION['typeUser'] == "au")) : ?>
              <li><a href="./index.php?view=home"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
              <li><a href="./index.php?view=reportsF"><i class='fa fa-file-text-o'></i> <span>Ventas y Compras</span></a></li>
            <?php endif; ?>
          </ul>
          <!-- Fin Auditor-->
          <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
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
      <div class="login-box">
        <div class="login-box-body">
          <div class="login-logo">
            <img src="assets/power.png" width="300">
          </div><!-- /.login-logo -->
          <form action="./?action=processlogin" method="post">
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

  <!-- REQUIRED JS SCRIPTS -->

  <!-- jQuery 2.1.4 -->
  <!-- Bootstrap 3.3.2 JS -->
  <script src="plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="plugins/dist/js/app.min.js" type="text/javascript"></script>

  <!-- MOMENT-->
  <!--<script type="text/javascript" src="plugins/moment/moment-with-locales.min.js"></script>
  <script type="text/javascript" src="plugins/moment/locale/es-mx.js"></script>-->
  <!-- MOMENT-->

  <!-- DATETIMEPICKER -->
  <link href="plugins/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
  <script src="plugins/bootstrap/js/bootstrap-datepicker.js"></script>

  <link href="plugins/bootstrap/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
  <script src="plugins/bootstrap/js/bootstrap-timepicker.min.js"></script>
  <!--  DATETIMEPICKER-->


  <script type="text/javascript">
    $(document).ready(function() {
      if ("<?php echo $_SESSION["alert_payment"] ?>" == 0) {

        //Sweet Alert
        /*Swal.fire({
          title: '¡Atención!',
          text: 'Estimado usuario, tu sistema presenta un saldo vencido. Te invitamos a regularizarlo a la brevedad posible.',
          icon: 'error',
          confirmButtonText: 'Aceptar'
        });
        let alert = "<?php echo $_SESSION["alert_payment"] = 1 ?>"
        */
      }
    });

    function notify(patient_id, patient_tel, patient_tel2, next_date, notification_module_id) {
      Swal.fire({
        title: '¿Deseas marcar como avisado al paciente?',
        html: "Confirma sólo si estás seguro, no podrás revertirlo.<br>Teléfono: " + patient_tel + "<br>Teléfono alternativo: " + patient_tel2,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Confirmar Aviso!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value == true) {
          $.ajax({
            type: "POST",
            url: "./?action=addNotification",
            data: {
              patient_id: patient_id,
              next_date: next_date,
              notification_module_id: notification_module_id
            },
            error: function() {
              Swal.fire(
                'Error',
                'No se pudo marcar como notificado.',
                'error'
              )
            },
            success: function(data) {
              location.reload();
            }
          });
        }
      })
    }

    //EMBRYOLOGY TREATMENTS
    const rgb2hex = (rgb) => `#${rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/).slice(1).map(n => parseInt(n, 10).toString(16).padStart(2, '0')).join('')}`;

    function getFormattedDate(date) {
      var date = new Date((date + "T00:00:00").replace(/-/g, '\/').replace(/T.+/, ''));
      var year = date.getFullYear();

      var month = (1 + date.getMonth()).toString();
      month = month.length > 1 ? month : '0' + month;

      var day = date.getDate().toString();
      day = day.length > 1 ? day : '0' + day;

      return day + '/' + month + '/' + year;
    }
  </script>
</body>

</html>