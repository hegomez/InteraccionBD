<?php
	include("functions.php");
	include("cnn.php");
	//Validacion de Usuarios
	$res=$bd->query("SELECT * FROM `usuarios`");
	if($res->num_rows<1)
	{
		//Crear Usuarios
		$UserData=array();
		$UserData[]=array("HEINER GOMEZ","he.gomez@hotmail.com","12345","1989-03-23");
		$UserData[]=array("LORAINE GUTIERREZ","lorigut@hotmail.com","54321","1997-10-04");
		foreach ($UserData as $value)
		{
			$PWD=sha1($UserData[2]);
			$bd->query("INSERT INTO usuarios (`nombre`,`email`,`contrasena`,`f_nacimiento`)
				VALUES ('".$value[0]."','".$value[1]."','".$PWD."','".$value[3]."')");
		}
	}
	if(isset($_POST['user']) && isset($_POST['pass']))
	{
		$email="'".$_POST["user"]."'";
		$contrasena="'".sha1($_POST["pass"])."'";
		$res=$bd->query("SELECT * FROM `usuarios` WHERE `email`=$email AND `contrasena`=$contrasena");
		if($res->num_rows<1)
		{
			$R=$res->fetch_assoc();
			$_SESSION['nombre']=$R['nombre'];
			$_SESSION['email']=$R['email'];
			$_SESSION['contrasena']=$R['contrasena'];
			$_SESSION['f_nacimiento']=$R['f_nacimiento'];
			GoTo("../client/main.php")
		}
	}
?>