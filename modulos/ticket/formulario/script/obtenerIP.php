<?php
 	require_once("../../../../clases/conexionocdb.php");
	$formulario = $_POST['form'];
	$sql="SELECT * FROM [RP_VICENCIO].dbo.RP_IP_BODEGAS WHERE ip = '".$formulario['ip']."'";
	
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$ip = $resultado['ip'];
		$bodega = $resultado['bodega'];
	}
	$respuesta = array(
		'ip' => $ip,
		'bodega' => $bodega
	);
	echo json_encode($respuesta);
	odbc_close( $conn );
?>