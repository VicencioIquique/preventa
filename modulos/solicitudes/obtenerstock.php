<?php
require_once("../../clases/conexionocdb.php");
$idarticulo = $_GET['sku'];
$numeroguia =$_GET['idsol'];

$sql="SELECT  [Bodega]
      ,[RecNum]
      ,[Alu]
      ,CONVERT(INT,[Cantidad]) as cant
  FROM [RP_VICENCIO].[dbo].[VerStockTiendas]
  
  WHERE [Alu] LIKE '".$idarticulo."' AND [Bodega] = 2";

//echo $sql;

$rs = odbc_exec( $conn, $sql );
							
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultado = odbc_fetch_array($rs)){ 
							 	 $cantidad=(INT)$resultado['cant'];
							 
								}	
								
								if($cantidad == null)
								{
									echo json_encode(0);
								}
								else 
								echo json_encode($cantidad);
	odbc_close( $conn );
?>