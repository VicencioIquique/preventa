<?php
	require_once("../../../../clases/conexionocdb.php");
	session_start();
	$datos = $_POST['formulario'];
	$sql="SELECT [Nombres]
      ,[ApellidoPaterno]
      ,[ApellidoMaterno]
      ,[eMail]
      ,[Empresa] FROM RP_VICENCIO.dbo.RP_Descuento_Empresa WHERE RUT = '".$datos['rut']."'";
	//echo $sql;

	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$nombres=$resultado['Nombres'];
		$apellidoPaterno = $resultado['ApellidoPaterno'];
		$apellidoMaterno = $resultado['ApellidoMaterno'];
		$empresa = $resultado['Empresa'];
	}
	
	$respuesta = array(
		'nombres'=>$nombres,
		'apellidoPaterno'=>$apellidoPaterno,
		'apellidoMaterno'=>$apellidoMaterno,
		'empresa'=>$empresa
	);
	echo json_encode($respuesta);
	odbc_close( $conn );
?>