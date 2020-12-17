<?php

	/*$is_ajax = $_REQUEST['is_ajax'];
	if(isset($is_ajax) && $is_ajax)
	{
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		
		if($username == 'caca' && $password == 'caca')
		{
			echo "success";	
		}
	}*/
	
	
$login = $_REQUEST[username];
$pass = $_REQUEST[password];



 $login;
 $pass;

if (!$login || !$pass )
{
		if(!$login) { echo'El campo Login es obligatorio<br>'; }
		if(!$pass) { echo'El campo Password es obligatorio<br>'; }
		exit();
}
else {
	
require_once("clases/conexionocdb.php");

	$sql= "SELECT     Count(*) AS counter
FROM         dbo.sisap_usuarios
WHERE     (usuario_user = '".$login."') ";

$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}
	$arr = odbc_fetch_array($rs);
    //echo $arr['counter'];
	
	$cuenta = $arr['counter'];
							


//$registro = $db->consulta("SELECT * FROM usuario WHERE usuario = '$login' ");
 	
	

//$cuenta = $db->num_rows($registro);
	

if($cuenta == 0)
{
		echo "No existe el login introducido";
		echo '<script languaje="javascript"> window.location="index.php"; </script>';
}

else
{
	$sql2= "SELECT     *
FROM         dbo.sisap_usuarios
WHERE     (usuario_user = '".$login."')  ";



	$rs2 = odbc_exec( $conn, $sql2 );
						if ( !$rs2 )
						{
								exit( "Error en la consulta SQL" );
						}
						
						$sql3= "SELECT     Count(*) AS counter
FROM         dbo.sisap_usuarios
WHERE     (usuario_user = '".$login."')   ";



	$rs3 = odbc_exec( $conn, $sql3 );
						if ( !$rs3 )
						{
								exit( "Error en la consulta SQL" );
						}
					
	$arr2 = odbc_fetch_array($rs3);

	
	$registro = $arr2['counter'];
							
				
	
	 if($registro>0){
		 
			$resultado = odbc_fetch_array($rs2);    
			// echo "estoy aqui";
          }

				if($resultado["usuario_pass"]==$pass)//crypt($pass,"semilla") )
				{
					 
					 $_SESSION["usuario_nombre"] = $resultado["usuario_nombre"]; 
					 $_SESSION["usuario_rol"] = $resultado["usuario_rol"];
					 $_SESSION["usuario_user"] = $resultado["usuario_user"];  
					 $_SESSION["usuario_modulo"] = $resultado["usuario_modulo"]; 
					 $_SESSION["usuario_id"] = $resultado["usuario_id"];  
				  
							echo '<script languaje="javascript"> window.location="index.php?opc=#"; </script>';
				}

				else { 
				echo "Password incorrecto!<br>"; 
				echo '<script languaje="javascript"> window.location="index.php"; </script>';
				
				
				}
}

}

	
?>