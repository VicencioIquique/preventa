<?php 
require_once("../../clases/conexionocdb.php");

//$total = $_POST['totalfinal'];
$idsol = $_POST['idsol'];
$usuario_id = $_POST['usuario_id'];



echo $sql="
	UPDATE  [RP_VICENCIO].[dbo].[sisap_solicitudes]
	SET     fecha_estado = GETDATE(),estado =2 , recepcion_id = ".$usuario_id."
	WHERE   solicitud_id = ".$idsol;

$rs = odbc_exec( $conn, $sql );
							
			if ( !$rs )
			{
				exit( "Error en la consulta SQL" );
			}


odbc_execute($rs);
odbc_close( $conn );

?>