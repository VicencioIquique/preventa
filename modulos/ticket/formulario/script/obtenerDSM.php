<?php
 	require_once("../../../../clases/conexionocdb.php");
	session_start();
	$formulario = $_POST['cod'];
	$bodegaIP = $_SERVER['REMOTE_ADDR'];
	
	$sqlIP = "SELECT bodega FROM [RP_VICENCIO].dbo.RP_IP_BODEGAS WHERE ip = '".$bodegaIP."'";
	$rs = odbc_exec( $conn, $sqlIP );
	if ( !$rs ){
		exit( "Error en la consulta SQL IP" );
	}
	$resultado = odbc_fetch_array($rs);
	
	if($resultado['bodega'] == '000'){
		$bodega = 'ZFI.2077';
	}else if($resultado['bodega'] == '001'){
		$bodega = 'ZFI.1010';
	}else if($resultado['bodega'] == '002'){
		$bodega = 'ZFI.1132';
	}else if($resultado['bodega'] == '003'){
		$bodega = 'ZFI.181';
	}else if($resultado['bodega'] == '004'){
		$bodega = 'ZFI.184';
	}else if($resultado['bodega'] == '005'){
		$bodega = 'ZFI.2020';
	}else if($resultado['bodega'] == '006'){
		$bodega = 'ZFI.6115';
	}else if($resultado['bodega'] == '007'){
		$bodega = 'ZFI.6130';
	}
	
	$sql ="SELECT NroDEM FROM [RP_VICENCIO].dbo.RP_DEM WHERE CodigoProducto = '".$formulario[0]."' AND Estado = 0 AND Bodega = '".$bodega."'";
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}else{
		$res = odbc_fetch_array($rs);
		echo $res['NroDEM'];
	}
	odbc_close( $conn );
?>