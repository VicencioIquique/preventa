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
		$descripcion = utf8_encode($resultado['DESC2'])." ".utf8_encode($resultado['DESC3']);
		$precio = number_format($resultado['PRICE01'],0,'','');
		$upc = $resultado['UPC'];
		$alu = $resultado['ALU'];
		$articulo = $resultado['RecNumber'];
	}
	
	$respuesta = array(
		'descripcion'=>sanear_string($descripcion),
		'precio'=>$precio,
		'upc'=>$alu,
		'articulo'=>$articulo
	);
	echo json_encode($respuesta);
	odbc_close( $conn );

	function sanear_string($string) {
		$string = trim($string);
	
		$string = str_replace(
			array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
			$string
		);
	
		$string = str_replace(
			array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
			$string
		);
	
		$string = str_replace(
			array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
			$string
		);
	
		$string = str_replace(
			array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
			$string
		);
	
		$string = str_replace(
			array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
			$string
		);
	
		$string = str_replace(
			array('ñ', 'Ñ', 'ç', 'Ç'),
			array('n', 'N', 'c', 'C',),
			$string
		);
	
		//Esta parte se encarga de eliminar cualquier caracter extraño
		$string = str_replace(
			array("\\", "¨", "º", "-", "~",
				 "#", "@", "|", "!", "\"",
				 "·", "$", "%", "&", "/",
				 "(", ")", "?", "'", "¡",
				 "¿", "[", "^", "`", "]",
				 "+", "}", "{", "¨", "´",
				 ">", "< ", ";", ",", ":",
				 ""),
			'',
			$string
		);
	
		return $string;
	}
?>