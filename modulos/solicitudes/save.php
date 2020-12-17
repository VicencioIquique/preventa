<?php
require_once("../../clases/conexionocdb.php");
 
$data  = explode("-",$_POST['id']);
 
$idsol = $data[0]; // id de la solicitud
$codigo    = $data[1]; // codigo del producto
$value = $_POST['value']; // valor por el cual reemplazar
 



  
 $sql="
	UPDATE   [RP_VICENCIO].[dbo].[sisap_soldetalle]
	SET     cant_aceptada = ".$value."
	WHERE   solicitud_id = ".$idsol." AND codigo ='".$codigo."' ";

$rs = odbc_exec( $conn, $sql );
							
			if ( !$rs )
			{
				exit( "Error en la consulta SQL" );
			}

 
//odbc_execute($rs);

echo $value;

odbc_close( $conn );

?>