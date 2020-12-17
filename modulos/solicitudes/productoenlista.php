<?php
require_once("../../clases/conexionocdb.php");
$idarticulo = $_GET['sku'];
$numeroguia =$_GET['idsol'];

$sql="SELECT     codigo, solicitud_id

FROM         dbo.sisap_soldetalle WHERE codigo LIKE '".$idarticulo."' AND  solicitud_id = ".$numeroguia." ";

//echo $sql;

$rs = odbc_exec( $conn, $sql );
							
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultado = odbc_fetch_array($rs)){ 
							 	 $id=$resultado["codigo"];
							 
								}	
				if($id==$idarticulo){
						
						  echo (json_encode(1));	
						}
						else
						{
							echo (json_encode(0));	
						}		
						

odbc_close( $conn );
					
				 		
						
?>	
                