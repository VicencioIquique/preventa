<?php
 	require_once("../../../../clases/conexionocdb.php");
	session_start();
	$formulario = $_POST['codPack'];
	
	$sqlPack_Arcitulos = "SELECT ALU FROM RP_VICENCIO.dbo.RP_Pack_Articulos WHERE codPack = '".$formulario[0]."'";
	$rsPA = odbc_exec($conn, $sqlPack_Arcitulos);
	if(!$rsPA){
		exit("Error en la consulta SQL Pack_Articulos");
	}
	$res = array();
	while($resultado = odbc_fetch_array($rsPA)){
		$res[] = $resultado['ALU'];
	}
	odbc_close( $conn );
	
	$sqlIP = "SELECT bodega FROM [RP_VICENCIO].dbo.RP_IP_BODEGAS WHERE ip = '".$_SERVER['REMOTE_ADDR']."'";
	$rsIP = odbc_exec( $conn, $sqlIP );
	if ( !$rsIP ){
		exit( "Error en la consulta SQL IP" );
	}
	$resultadoBodega = odbc_fetch_array($rsIP);
	odbc_close( $conn );
	
	$acum = 0;
	for($i=0;$i<count($res);$i++){
		$sqlLotes ="SELECT SUM(Cantidad) as Cantidad FROM [RP_VICENCIO].dbo.LotesDisponibles WHERE ItemCode='".$res[$i]."' AND bodega='".$resultadoBodega['bodega']."'";
		$rsLotes = odbc_exec( $conn, $sqlLotes );
		if ( !$rsLotes ){
			exit( "Error en la consulta SQL LOTES" );
		}
		$resultadoLotes = odbc_fetch_array($rsLotes);
		if($resultadoLotes['Cantidad'] > 0){
			$acum=$acum+1;
		}
		odbc_close( $conn );
	}
	
	if(($acum == count($res))&&($acum != 0)){
		$sqlPack = "SELECT codPack, descripcion, total, articulo FROM RP_VICENCIO.dbo.RP_Pack WHERE codPack = '".$formulario[0]."'";
		$rs = odbc_exec($conn, $sqlPack);
		if ( !$rs ){
			exit( "Error en la consulta SQL IP" );
		}
		$resultado = odbc_fetch_array($rs);
		$respuesta = array(
			'codPack'=>$resultado['codPack'],
			'descripcion'=>$resultado['descripcion'],
			'total'=>$resultado['total'],
			'articulo'=>$resultado['articulo']
		);
		echo json_encode($respuesta);
	}else if($acum < count($res)){
		$respuesta = array(
			'codPack'=>'',
			'descripcion'=>'1',
			'total'=>''
		);
		echo json_encode($respuesta);
	}else if((count($res) == 0)&&($acum == 0)){
		$respuesta = array(
			'codPack'=>'',
			'descripcion'=>'2',
			'total'=>''
		);
		echo json_encode($respuesta);
	}else if($acum == 0){
		$respuesta = array(
			'codPack'=>'',
			'descripcion'=>'0',
			'total'=>''
		);
		echo json_encode($respuesta);
	}
	
		odbc_close( $conn );
?>