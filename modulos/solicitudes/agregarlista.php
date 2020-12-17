<?php 
 session_start();
require_once("../../clases/conexionocdb.php");

$idsol = $_POST['idsol'];
$sku = $_POST['sku'];
$stock = $_POST['stock'];
$cant = $_POST['cant'];
$descrip = $_POST['descrip'];


echo $sql2="SELECT  [Bodega]
      ,[RecNum]
      ,[Alu]
      ,[Cantidad]
  FROM [RP_VICENCIO].[dbo].[VerStockTiendas]
  
  WHERE [Alu] LIKE '".$sku."' AND [Bodega] = ".$_SESSION["usuario_modulo"]." ";
  
  $rs2 = odbc_exec( $conn, $sql2 );
				if ( !$rs2 )
							{
							exit( "Error en la consulta SQL" );
				}

				 while($resultado = odbc_fetch_array($rs2)){ 
							 	 $stock=$resultado["Cantidad"];
							 
								}	





$sql="INSERT INTO [RP_VICENCIO].[dbo].[sisap_soldetalle]

       (codigo, descripcion, marca, stock_modulo, cant_solicitada, cant_aceptada, solicitud_id)
VALUES ( '".$sku."','".$descrip."', NULL, ".$stock.", ".$cant.",".$cant.",".$idsol.")";

$rs = odbc_exec( $conn, $sql );
							
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}


odbc_execute($rs);

odbc_close( $conn );


?>