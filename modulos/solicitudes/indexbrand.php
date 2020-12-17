<?php 
require_once("clases/conexionocdb.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

//$criterio = ""; 
if (isset($_GET['criterio'])){ 
   	$txt_criterio = $_GET["criterio"]; 
   	$criterio = " AND (usuario_nombre like '%" . $txt_criterio . "%')"; 
}
?>
<script type="text/javascript">
  $(document).ready(function(){

                fn_eliminar_solicitud();
				fn_revisar_solicitud();
				fn_editar_pais();
				$('#busqueda').focus();
				
				

            });// funcion principal jquery
			
			

</script>

<?php
 
      $buscar = $_POST['b'];
    
?>
<div  style="margin-bottom:10px;" class="paso"  >1 Paso</div><div style="margin-bottom:10px;" class="paso" >2 Paso</div><div style="margin-bottom:10px;" class="paso_selected"  >3 Paso</div>

        <input name="usuario_id" type="hidden" id="usuario_id" size="40" class="required" value="<?php echo $_SESSION['usuario_id'];?>" />
        
           
         

            <table  id="ssptable" class="lista">
              <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre Solicita</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th>Modulo</th>
                        
                        <th>Revisión</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                 <?php
                 
				 	$sql="SELECT     dbo.sisap_solicitudes.solicitud_id, dbo.sisap_solicitudes.estado, CONVERT(varchar, dbo.sisap_solicitudes.fecha_crea, 103) AS Fecha, dbo.sisap_solicitudes.fecha_estado, 
                      dbo.sisap_solicitudes.cantidad_total, dbo.sisap_solicitudes.modulo, dbo.sisap_solicitudes.vendedor_id, dbo.sisap_solicitudes.recepcion_id, 
                      dbo.sisap_usuarios.usuario_nombre
FROM         dbo.sisap_solicitudes LEFT OUTER JOIN
                      dbo.sisap_usuarios ON dbo.sisap_solicitudes.vendedor_id = dbo.sisap_usuarios.usuario_id
					  WHERE dbo.sisap_solicitudes.estado NOT LIKE 0
					  ORDER BY dbo.sisap_solicitudes.solicitud_id DESC";

					
					$total =0;
					$cantotal =0;

							
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultado = odbc_fetch_array($rs)){ 
							  //echo $resultado["estado"];
							  
							  if($resultado["estado"]==1)
							  {
							   echo '<tr>
									<td ><strong><a class="revisar_solicitud" id="'.$resultado["solicitud_id"].'">'.utf8_encode($resultado["solicitud_id"]).'</a></strong></td>
									<td ><strong>'.utf8_encode(getusuario($resultado["vendedor_id"])).'</strong></td>
									<td >'.getestado($resultado["estado"]).'</td>
									<td >'.utf8_encode($resultado["Fecha"]).'</td>
									<td >'.utf8_encode(getmodulo($resultado["modulo"])).'</td>
									
										<td >'.utf8_encode(getusuario($resultado["recepcion_id"])).'</td>
									<td ></td>' ;
							  } //fin if 
							  
							    if($resultado["estado"]>1)
							  {
								  if($resultado["estado"]==2)
								  {
							   echo '<tr>
									<td ><strong><a  href="index.php?opc=paso2brand&idsol='.$resultado["solicitud_id"].'">'.utf8_encode($resultado["solicitud_id"]).'</a></strong></td>';
								  }
								  if($resultado["estado"]==3)
								  {
							   echo '<tr>
									<td ><strong><a target="_blank" href="modulos/impresiones/listasolicitudpdf.php?idsol='.$resultado["solicitud_id"].'">'.utf8_encode($resultado["solicitud_id"]).'</a></strong></td>';
								  }
									echo '<td ><strong>'.utf8_encode(getusuario($resultado["vendedor_id"])).'</strong></td>
									<td >'.getestado($resultado["estado"]).'</td>
									<td >'.utf8_encode($resultado["Fecha"]).'</td>
									<td >'.utf8_encode(getmodulo($resultado["modulo"])).'</td>
									
										<td >'.utf8_encode(getusuario($resultado["recepcion_id"])).'</td>
									<td ></td>' ;
							  } //fin if 
						
							?>
						<?php echo '</tr>';
								}
							

				             ?>
                </tbody>
                <tfoot>
                	<tr>
                    	<td colspan="5"><strong>Cantidad:</strong> <span id="span_cantidad">4</span> Solicitudes.</td>
                    </tr>
                </tfoot>
            </table>
	<?php odbc_close( $conn );?>
            


   