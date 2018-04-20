<?php
	//ConexionBD
	$bd = new MySQLi("localhost", "root", "", "nextu_bd");
	if (mysqli_connect_errno()) { printf("Connect failed: %s\n", mysqli_connect_error()); exit(); }
	//Funciones
	function prepare($theValue, $theType,$bd) 
	{
		if (PHP_VERSION < 6)
		{
			$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	  	}
	 	$theValue = $bd->real_escape_string($theValue);
	  	switch ($theType)
	  	{
			case "text":
		  		$theValue = ($theValue != "") ? "'" . $theValue . "'" : "''";
		  	break;    
			case "long":
			case "int":
		  		$theValue = ($theValue != "") ? intval($theValue) : "0";
		  	break;
			case "double":
		  		$theValue = ($theValue != "") ? doubleval($theValue) : "0";
		  	break;
			case "date":
		  		$theValue = ($theValue != "") ? "'" . $theValue . "'" : " ";
		  	break;
	  	}
	  	return $theValue;
	}

	//Creacion de Usuarios
	$res=$bd->query("SELECT * FROM `usuarios`");
	if($res->num_rows<1)
	{
		//Crear Usuarios
		$UserData=array();
		$UserData[]=array("HEINER GOMEZ","he.gomez@hotmail.com","12345","1989-03-23");
		$UserData[]=array("LORAINE GUTIERREZ","lorigut@hotmail.com","54321","1997-10-04");
		foreach ($UserData as $value)
		{
			$PWD=sha1($value[2]);
			$bd->query("INSERT INTO usuarios (`nombre`,`email`,`contrasena`,`f_nacimiento`)
				VALUES ('".$value[0]."','".$value[1]."','".$PWD."','".$value[3]."')");
		}
	}

	//Carga de Infomracion del Usuario
	if(isset($_GET['jsoncallback']) && !empty($_GET['jsoncallback']))
	{
		$array=array();
		if(isset($_GET['user']) && isset($_GET['pass']))
		{
			$email="'".$_GET["user"]."'";
			$contrasena="'".sha1($_GET["pass"])."'";
			//die("SELECT `id` FROM `usuarios` WHERE `email`=$email AND `contrasena`=$contrasena");
			$res=$bd->query("SELECT `id` FROM `usuarios` WHERE `email`=$email AND `contrasena`=$contrasena");
			if($res->num_rows>=1)
			{
				$R=$res->fetch_assoc();
				$array['id']=$R['id'];
				$array['rta']='OK';
			}
			else
			{
				$array['rta']='ERR';
			}
		}
		echo $_GET['jsoncallback'].'('.json_encode($array).')';
	}
?>