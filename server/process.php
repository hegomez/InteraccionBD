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
		$UserData[]=array("HEINER GOMEZ VILLARREAL","he.gomez@hotmail.com","12345","1989-03-23");
		$UserData[]=array("LORAINE GUTIERREZ AVENDANIO","lorigut@hotmail.com","54321","1997-10-04");
		$UserData[]=array("BENJAMIN GOMEZ GUTIERREZ","benjigg@hotmail.com","13579","20017-11-03");
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
		if(isset($_GET['ChargarEventos']) && !empty($_GET['ChargarEventos']))
		{
			$res=$bd->query("SELECT * FROM `eventos` WHERE user=".$_GET['ChargarEventos']);
			if($res->num_rows>=1)
			{
				while($Row_Eventos=$res->fetch_array())
				{
					$array[]=array(
						"title"=>$Row_Eventos[1],
						"start"=>$Row_Eventos[2],
						"end"=>$Row_Eventos[4]
					);
				}
			}
			//title: 'Long Event',
			//start: '2018-03-07',
			//end: '2018-03-10'
		}
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
		echo json_encode($array);
	}
?>