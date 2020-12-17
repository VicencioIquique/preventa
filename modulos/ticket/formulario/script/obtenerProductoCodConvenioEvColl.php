<?php
	//Por cambios para la boleta UPC = ALU en $respuesta 2016-07-06
 	require_once("../../../../clases/conexionocdb.php");
	$formulario = $_POST['cod'];
	$sql="SELECT DESC2, DESC3, PRICE01, DNAME, UPC, ALU, RecNumber FROM [dbo].[RP_Articulos] WHERE (ALU = '".$formulario[0]."' OR UPC='".$formulario[0]."') AND ALU NOT IN(SELECT upc FROM RP_VICENCIO.dbo.RP_Descuento_Navidad2)";

	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	$contDesc = 0;
	while($resultado = odbc_fetch_array($rs)){
		$descripcion = $resultado['DESC2']." ".$resultado['DESC3']." ".$resultado['DNAME'];
		$precio = number_format($resultado['PRICE01'],0,'','');
		$upc = $resultado['UPC'];
		$alu = $resultado['ALU'];
		$articulo = $resultado['RecNumber'];
		if($precio == 1 || $precio == 0){
			$descuento = 0;
		}else if(($resultado['DNAME'] == "PERFUMES") || ($resultado['DNAME'] == "PERFUMES ") || ($resultado['DNAME'] == "PERFUMES  ")){
			if($resultado['DESC3'] == "CHRISTIAN DIOR"){
				$descuento = 0;
			}else if($resultado['DESC3'] == "CHANEL"){
				$descuento = 0;
			}else{
				$descuento = ($precio * 10)/100;
				$contDesc++;
			}
		}else if($resultado['DNAME'] == "COSMETICOS"){
			if($resultado['DESC3'] == "PUPA"){
				$descuento = ($precio * 20)/100;
				$contDesc++;
			}else{
				$descuento = 0;
			}
		}else{
			$descuento = ($precio * 10)/100;
			$contDesc++;
		}
		if($contDesc == 1){
			$calc = $precio - $descuento;
			$total = substr($calc,0,-1).'0';
		}else{
			$total = $precio - $descuento;
		}
	}
	
	$respuesta = array(
		'descripcion'=>$descripcion,
		'precio'=>$precio,
		'upc'=>$alu,
		'articulo'=>$articulo,
		'descuento'=>$descuento,
		'total'=>$total
	);
	echo json_encode($respuesta);
	odbc_close( $conn );
?>