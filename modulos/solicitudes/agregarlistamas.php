<?php 
require_once("../../clases/conexionocdb.php");


$cant = $_POST['cant'];
$sku = $_POST['sku'];

$idsol = $_POST['idsol'];



$sql="
	UPDATE  [RP_VICENCIO].[dbo].[sisap_soldetalle]
	SET     cant_solicitada = cant_solicitada+ ".$cant.",cant_aceptada =cant_aceptada+ ".$cant."
	WHERE   solicitud_id = ".$idsol." AND codigo = '".$sku."' ";

$rs = odbc_exec( $conn, $sql );
							
			if ( !$rs )
			{
				exit( "Error en la consulta SQL" );
			}

odbc_close( $conn );

?>