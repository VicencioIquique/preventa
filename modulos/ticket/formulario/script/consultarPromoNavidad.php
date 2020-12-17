<?php
 	require_once("../../../../clases/conexionocdb.php");
	$formulario = $_POST['cod'];
	$sql="SELECT T1.upc, descuento, T2.DESC2, T2.DESC3, T2.PRICE01, T2.RecNumber FROM RP_VICENCIO.dbo.[RP_Descuento_Navidad2] as T1
			LEFT JOIN RP_VICENCIO.dbo.RP_Articulos as T2 ON T1.upc = T2.ALU
			WHERE T1.upc = '".$formulario[0]."'";

	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$descripcion = $resultado['DESC2']." ".$resultado['DESC3'];
		$descuento = number_format($resultado['PRICE01'],0,'','') - $resultado['descuento'];
		$upc = $resultado['upc'];
		$articulo = $resultado['RecNumber'];
		$precioOferta = $resultado['descuento'];
		$precio = number_format($resultado['PRICE01'],0,'','');
	}
	
	$respuesta = array(
		'descripcion'=>$descripcion,
		'descuento'=>$descuento,
		'upc'=>$upc,
		'articulo'=>$articulo,
		'precioOferta'=>$precioOferta,
		'precio'=>$precio
	);
	echo json_encode($respuesta);
	odbc_close( $conn );
?>