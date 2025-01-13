<?php

if(count($_POST)>0){

	$user = new UserData();
	$user->name = $_POST["name"];
	$user->username = $_POST["username"];
	$user->password = sha1(md5($_POST["password"]));
	$user->tipo_usuario = $_POST["tipo_usuario"];
	
	$user->add();

print "<script>window.location='index.php?view=users';</script>";


}


?>