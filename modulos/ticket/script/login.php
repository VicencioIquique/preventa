<?php
session_start();
	require_once("../../../clases/conexionocdb.php");
	$_SESSION['username'] = $_POST['credencial'];	
	if(empty($_SESSION['username'])) { // Recuerda usar corchetes.
		header('Location: ../index.php');
	}else{
		$credencial = substr($_POST['credencial'], 16, 3);
		//$credencial = $_POST['credencial'];
		$sql = "SELECT SlpName, SlpCode 
					FROM [SBO_Imp_Eximben_SAC].[dbo].[OSLP]
					WHERE SlpCode = '".$credencial."'";
		/* SQL EN BASE DE DATOS DE PRUEBA
		$sql="SELECT SlpName, SlpCode 
				FROM SBO_Eximben_ZOFI.[dbo].[OSLP]
				WHERE SlpCode = '".$credencial."'";
		 */ 
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ){
			exit( "Error en la consulta SQL" );
		}
		while($resultado = odbc_fetch_array($rs)){ 
			$credencialQR = $resultado['SlpCode'];
			$nombreVendedor = $resultado['SlpName'];
		}
		$_SESSION['vendedor'] = $nombreVendedor;
		$_SESSION['slp'] = $credencialQR;
		
		//OBTENER IP DEL EQUIPO
		$_SESSION['bodega'] = $_SERVER['REMOTE_ADDR'];
		if($credencialQR){
			echo "<script>location.href='../formulario/form.php';</script>";
		}else{
			echo "<script>location.href='../index.php';</script>";
		}
	}
	
		
?>