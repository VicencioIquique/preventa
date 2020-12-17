<?php 
require_once("../../clases/conexionocdb.php");

$id = $_POST['usuario_id'];
$modulo = (int)$_POST['modulo'];
$estado = 0;


$sql="INSERT INTO [RP_VICENCIO].[dbo].[sisap_solicitudes]

       ( estado, fecha_crea, fecha_estado, cantidad_total, modulo, vendedor_id, recepcion_id)
VALUES ( ".$estado.", GETDATE(), NULL, '', ".$modulo.", ".$id.",'')";

$rs = odbc_exec( $conn, $sql );
							
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}


odbc_execute($rs);

odbc_close( $conn );

?>