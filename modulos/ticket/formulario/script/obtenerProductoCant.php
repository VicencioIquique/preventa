<?php
	//SE CAMBIO LA BUSQUEDA EN LOTES DISPONIBLES PARA QUE SOLO BUSQUE POR ALU 2016-07-06
 	require_once("../../../../clases/conexionocdb.php");
	session_start();
	$formulario = $_POST['codCantidad'];
	/*$sql="SELECT SUM(PrecioRepo.Cantidad) as Cantidad FROM [dbo].[RP_Articulos] as ArtRepo
		LEFT JOIN [dbo].[LotesDisponibles] as PrecioRepo ON ArtRepo.UPC = PrecioRepo.ItemCode
		WHERE ArtRepo.UPC = '".$formulario[0]."' OR ArtRepo.ALU='".$formulario[0]."'";*/
	$bodegaIP = $_SERVER['REMOTE_ADDR'];
	
	$sqlIP = "SELECT bodega FROM [RP_VICENCIO].dbo.RP_IP_BODEGAS WHERE ip = '".$bodegaIP."'";
	$rs = odbc_exec( $conn, $sqlIP );
	if ( !$rs ){
		exit( "Error en la consulta SQL IP" );
	}
	$resultado = odbc_fetch_array($rs);
	
	$sqlALU = "SELECT ALU FROM [RP_VICENCIO].[dbo].[RP_Articulos] WHERE ALU = '".$formulario[0]."'";
	$rsALU = odbc_exec( $conn, $sqlALU );
	if ( !$rsALU ){
		exit( "Error en la consulta SQL ARTICULOS" );
	}
	$resultadoALU = odbc_fetch_array($rsALU);
	
	$sql ="SELECT Cantidad FROM [RP_VICENCIO].dbo.LotesDisponibles WHERE ItemCode='".$resultadoALU['ALU']."' AND bodega='".$resultado['bodega']."'";
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL LOTES" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$cantidad = number_format($resultado['Cantidad'],0,'','');
	}
	$respuesta = array(
		'cantidad'=>$cantidad
	);
	echo json_encode($respuesta);
	odbc_close( $conn );
?>