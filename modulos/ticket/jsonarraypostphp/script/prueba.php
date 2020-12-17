<?php
require_once("../../../clases/conexionocdb.php");
$id = $_POST['prueba'];
	
		$sql = "SELECT TOP 1000 [idDetalleFondoFijo]
				  ,[FK_idFondoFijo]
				  ,[rinDate]
				  ,[business]
				  ,[numDoc]
				  ,[title]
				  ,[description]
				  ,[cost]
				  ,[FK_idConcepto]
			  FROM [SISAP].[dbo].[SI_DetalleFondoFijo]
			  WHERE FK_idFondoFijo = '".$id['id']."'";
			  
		$rs = odbc_exec( $conn, $sql );
		if ( !$rs ){
			exit( "Error en la consulta SQL" );
		}
		
			while($resultado = odbc_fetch_array($rs)){ 
				$res[] = array ( 
								'idDetalleFondoFijo' => $resultado['idDetalleFondoFijo'],
								'idFondoFijo' => $resultado['FK_idFondoFijo'],
								'fecha' => $resultado['rinDate'],
								'negocio' => $resultado['business'],
								'documento' => $resultado['numDoc'],
								'titulo' => $resultado['title'],
								'descripcion' => $resultado['description'],
								'costo' => $resultado['cost'],
								'FK_idConcepto' => $resultado['FK_idConcepto'],
							);
			}
		
		echo json_encode($res);
		
?>