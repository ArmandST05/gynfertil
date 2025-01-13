<?php

// define('LBROOT',getcwd()); // LegoBox Root ... the server root
// include("core/controller/Database.php");

if(!isset($_SESSION["user_id"])) {
$user = $_POST['username'];
$pass = sha1(md5($_POST['password']));

$base = new Database();
$con = $base->connect();
$sql = "select * from user where (email= \"".$user."\" or username= \"".$user."\") and password= \"".$pass."\"";

$query = $con->query($sql);
$found = false;
$userid = null; $type = null;
while($r = $query->fetch_array()){
	$found = true ;
	$userid = $r['id'];
	$type = $r['tipo_usuario'];
}
$_SESSION['typeUser']=$type;
if($found==true) {
//	session_start();
	$_SESSION['user_id']=$userid;
	$_SESSION["alert_payment"] = 0;
//	setcookie('userid',$userid);
	print "Cargando ... $user";
	print "<script>window.location='index.php?view=home';</script>";
}else {
	print "<script>window.location='index.php?view=login';</script>";
}

}else{
	print "<script>
    alert('Verifica tus datos');
	window.location='index.php?view=login';</script>";
	
}
?>