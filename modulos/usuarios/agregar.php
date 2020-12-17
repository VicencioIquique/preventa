<?php 
require_once("../../clases/conexionocdb.php");

$id = $_POST['ide'];
$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$rol = $_POST['rol'];
$modulo = $_POST['modulo'];
$pass = $_POST['pass'];

$sql="
  
INSERT INTO [RP_VICENCIO].[dbo].[sisap_usuarios]

       (  usuario_id, usuario_nombre, usuario_pass, usuario_rol, usuario_user, usuario_modulo)
VALUES ( ".$id.", '".$nombre."', '".$pass."', ".$rol.", '".$usuario."', ".$modulo.")";

$rs = odbc_exec( $conn, $sql );
							
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}


odbc_execute($rs);

odbc_close( $conn );

?>