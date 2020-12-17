<?php
	//Por cambios para la boleta UPC = $resultado['ALU'] en $respuesta 2016-07-06
 	require_once("../../../../clases/conexionocdb.php");
	$formulario = $_POST['codigo'];
	
	$sql="SELECT T2.DESC2, T2.DESC3, T2.PRICE01, T2.UPC, T2.ALU, T2.RecNumber FROM RP_VICENCIO.dbo.RP_Pack_Articulos AS T1
			LEFT JOIN RP_VICENCIO.dbo.RP_Pack_Articulos AS T3 ON T1.codPack = T3.codPack
			LEFT JOIN RP_VICENCIO.dbo.RP_Articulos AS T2 ON T2.ALU = T3.ALU
			WHERE T1.codPack = '".$formulario."'
			GROUP BY T2.DESC2, T2.DESC3, T2.PRICE01, T2.UPC, T2.ALU, T2.RecNumber";

	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$respuesta[] = array(
			'descripcion'=>$resultado['DESC2']." ".$resultado['DESC3'],
			'precio'=>number_format($resultado['PRICE01'],0,'',''),
			'upc'=>$resultado['ALU'],
			'articulo'=>$resultado['RecNumber']
		);
	}
	echo json_encode($respuesta);
	odbc_close( $conn );
?>