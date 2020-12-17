<?php
require_once("../../clases/conexionocdb.php");
//################# CONSULTA PARA OBTENER EL PRODUCTO SEGUN CODIGO DE BARRA#########
$renta = $_GET['codBarra'];
$monto=0;

$sql= "SELECT [RecNumber]
								  ,[ALU]
								  ,[DESC1]
								,[PRICE01]
								  
							  FROM [RP_VICENCIO].[dbo].[RP_Articulos]
							WHERE [ALU] = '".$codbarra."' ";
					 
					// $stock = explode("-",stockModulos($codbarra));
							//echo $sql;	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultado = odbc_fetch_array($rs)){ 
						
							
								$articulo = array ( 
												"codBarra" => $resultado['ALU'],
												"descrip" => $resultado['DESC1'],
												"precio" => $resultado['PRICE01'],
											);
							 
 
						  }
						
					
				 echo (json_encode($articulo));		
						
?>	
                