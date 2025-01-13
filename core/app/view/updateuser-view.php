<?php

if(count($_POST)>0){
	$user = UserData::getById($_POST["user_id"]);
	$user->name = $_POST["name"];
	$user->username = $_POST["username"];
	$user->tipo_usuario = $_POST["tipo_usuario"];
	$user->update();

	if($_POST["password"]!=""){
		$user->password = sha1(md5($_POST["password"]));
		$user->update_passwd();
		print "<script>alert('Se ha actualizado la contraseña');</script>";

	}

print "<script>window.location='index.php?view=users';</script>";


}


?>