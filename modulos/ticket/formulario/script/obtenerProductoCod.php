<?php
	//Por cambios para la boleta UPC = ALU en $respuesta 2016-07-06
 	require_once("../../../../clases/conexionocdb.php");
	$formulario = $_POST['cod'];
	$sql="SELECT DESC2, DESC3, PRICE01, UPC, ALU, RecNumber FROM [RP_VICENCIO].[dbo].[RP_Articulos] WHERE ALU = '".$formulario[0]."' OR UPC='".$formulario[0]."'";

	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$descripcion = $resultado['DESC2']." ".$resultado['DESC3'];
		$precio = number_format($resultado['PRICE01'],0,'','');
		$upc = $resultado['UPC'];
		$alu = $resultado['ALU'];
		$articulo = $resultado['RecNumber'];
	}
	
	$respuesta = array(
		'descripcion'=>$descripcion,
		'precio'=>$precio,
		'upc'=>$alu,
		'articulo'=>$articulo
	);
	echo json_encode($respuesta);
	odbc_close( $conn );
?>