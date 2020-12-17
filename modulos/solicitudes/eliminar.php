<?php 
require_once("../../clases/conexionocdb.php");

$id =$_POST['id_sol'];

$sql="DELETE FROM  dbo.sisap_solicitudes WHERE solicitud_id = ".$id."";

$rs = odbc_exec( $conn, $sql );
							
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

odbc_execute($rs);
odbc_close( $conn );

?>