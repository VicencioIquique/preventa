<?php
require_once("../../clases/conexion.php");
						$db = new MySQL();
						$consulta = $db->consulta("SELECT MAX(pais_id) AS id FROM pais");
						if($db->num_rows($consulta)>0){
						  while($resultados = $db->fetch_array($consulta)){ 
						
						  $id = trim($resultados[0]);
						  }
						}
					
				 echo (json_encode((int)$id));		
		 odbc_close( $conn );		
?>	
                