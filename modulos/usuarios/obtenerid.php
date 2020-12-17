<?php
require_once("../../clases/conexionocdb.php");

$sql="SELECT     MAX(usuario_id) AS ID
FROM         dbo.sisap_usuarios";

$rs = odbc_exec( $conn, $sql );
							
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultado = odbc_fetch_array($rs)){ 
							 	 $id=$resultado["ID"];
							 
								}	
				 echo (json_encode((int)$id));		
				 
odbc_close( $conn );
						
?>	
                