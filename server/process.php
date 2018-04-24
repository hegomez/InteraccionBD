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
	if(isset($_GET['jsoncallback1']) && !empty($_GET['jsoncallback1']))
	{
		$data=array();
		if(isset($_GET['EditarEvento']) && !empty($_GET['EditarEvento']))
		{
			$tmp=explode("-", $_GET['IdEvento'][0]);
			$id=$tmp[1];
			$f_ini="'".$_GET['nFini']."'";
			empty($_GET['nFfin']) ? $f_fin="'".$_GET['nFini']."'" : $f_fin="'".$_GET['nFfin']."'";
			//die("UPDATE eventos set `f_ini`=$f_ini,`f_fin`=$f_fin where `id`=$id");
			$bd->query("UPDATE eventos set `f_ini`=$f_ini,`f_fin`=$f_fin where `id`=$id");
		}
		if(isset($_GET['AmacenarEvento']) && !empty($_GET['AmacenarEvento']))
		{
			$tiutlo=prepare($_GET['titulo'],'text',$bd);
			$f_ini=prepare($_GET['start_date'],'date',$bd);
			$h_ini=prepare($_GET['start_hour'],'date',$bd);
			$f_fin=prepare($_GET['end_date'],'date',$bd);
			$h_fin=prepare($_GET['end_hour'],'date',$bd);
			$user=prepare($_GET['IdUser'],'int',$bd);
			$bd->query("INSERT INTO eventos (`tiutlo`,`f_ini`,`h_ini`,`f_fin`,`h_fin`,`user`) VALUES ($tiutlo,$f_ini,$h_ini,$f_fin,$h_fin,$user)");
			$array['rta']='OK';
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
				
			}
			else
			{
				$array['rta']='ERR';
			}
		}
		echo $_GET['jsoncallback1'].'('.json_encode($data).')';
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
						"end"=>$Row_Eventos[4],
						"className"=>'id-'.$Row_Eventos[0]
					);
				}
			}
		}
		echo json_encode($array);
	}
?>