<?php
	session_start();
	//ConexionBD
	$bd = new MySQLi("localhost", "root", "", "nextu_bd");
	if (mysqli_connect_errno()) { printf("Connect failed: %s\n", mysqli_connect_error()); exit(); }
?>