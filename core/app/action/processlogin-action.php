<?php
	// define('LBROOT',getcwd()); // LegoBox Root ... the server root
	// include("core/controller/Database.php");

	if(!isset($_SESSION["user_id"])) {
		$user = $_POST['username'];
		$pass = sha1(md5($_POST['password']));

		$base = new Database();
		$con = $base->connect();
		$sql = "SELECT * FROM users WHERE is_active = 1 AND (email= \"".$user."\" or username= \"".$user."\") and password= \"".$pass."\"";
	
		$query = $con->query($sql);
		$found = false;
		$userId = null; 
		$username = null;
		$type = null;
		$branchOfficeId = null;

		while($r = $query->fetch_array()){
			$found = true ;
			$userId = $r['id'];
			$username = $r['username'];
			$type = $r['user_type'];
			$branchOfficeId = $r['branch_office_id'];
		}
		$_SESSION['typeUser'] = $type;
		$_SESSION['branchOfficeId'] = $branchOfficeId;

		if($found==true) {
			$_SESSION['user_id'] = $userId ;
			print "Cargando ... $user";
			print "<script>window.location='index.php?view=home';</script>";
		}else {
			print "<script>
				alert('Verifica tus datos'); 
				window.location='index.php?view=login';
			</script>";
		}

	}else{
		print "<script>
			alert('Verifica tus datos');
			window.location='index.php?view=login';
		</script>";
	}
?>