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
				fn_editar_pais();
				$('#busqueda').focus();

            });

</script>

<?php
 
      $buscar = $_POST['b'];
    
?>
<div class="paso_selected" >1 Paso</div><div class="paso" >2 Paso</div><div class="paso" >3 Paso</div><br /><br /><br />	 
<div class="idTabs">
      <ul>
        <li><a href="#one"><img src="images/agregar.png" width="30px" height="30px" /></a></li>
         <!--<li><a href="#two"><img src="images/agregar.png" width="30px" height="30px" /></a></li>
        <li><a href="#tree"><img src="images/editmod.png" width="30px" height="30px" /></a></li>-->
        <!-- Try adding this <br/> tag here
        <li><a href="#three">3</a></li>
        <li><a href="#four">4</a></li> -->
      </ul>
      <div class="items">
        <div id="two">
     </div><!-- fin two-->
        <div id="one">
         <form action="javascript: fn_agregar_nuevaSolicitud();" method="post" id="horizontalForm">
          
            <fieldset>
				<legend>Nueva Solicitud <?php echo getmodulo($_SESSION['usuario_modulo']);?> </legend>
                    <label for="pais1">
					          <?php //echo $_SESSION['usuario_nombre'];?>
        				    <input name="usuario_id" type="hidden" id="usuario_id" size="40" class="required" value="<?php echo $_SESSION['usuario_id'];?>" />
                             <input name="modulo" type="hidden" id="modulo" size="40" class="required" value="<?php echo $_SESSION['usuario_modulo'];?>" />
                             </label>
                            <input name="agregar" type="submit" id="agregar" class="submit" value="Comenzar" />
				</fieldset>
            </form>
         </div> <!-- fin one -->
 	  </div> <!-- fin items -->
    </div> <!-- fin idTabs -->
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
                      dbo.sisap_usuarios ON dbo.sisap_solicitudes.vendedor_id = dbo.sisap_usuarios.usuario_id WHERE  ".$_SESSION["usuario_modulo"]." LIKE  dbo.sisap_solicitudes.modulo
					  ";

					
					$total =0;
					$cantotal =0;

							//echo $sql;	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultado = odbc_fetch_array($rs)){ 
							  
							//  echo $resultado["estado"];
							  
								if($resultado["estado"]>0)
								{
									  if($resultado["estado"]==3)
								  {
							   echo '<tr>
									<td ><strong><a target="_blank"  href="modulos/impresiones/listasolicitudpdf.php?idsol='.$resultado["solicitud_id"].' ">'.utf8_encode($resultado["solicitud_id"]).'</a></strong></td>';
								  }
								  else
								  {
							   echo '<tr>
									<td ><strong><a href="#">'.utf8_encode($resultado["solicitud_id"]).'</a></strong></td>';
								  }
									echo '
								    <td ><strong>'.utf8_encode(getusuario($resultado["vendedor_id"])).'</strong></td>
									<td >'.getestado($resultado["estado"]).'</td>
									<td >'.utf8_encode($resultado["Fecha"]).'</td>
									<td >'.utf8_encode(getmodulo($resultado["modulo"])).'</td>
									
									<td >'.utf8_encode(getusuario($resultado["recepcion_id"])).'</td>
									<td ></td>' ;
																
								}
								else if((int)$resultado["estado"]==0)
								{
							   echo '<tr>
									<td ><strong><a href="index.php?opc=pasodos&idsol='.$resultado["solicitud_id"].'">'.utf8_encode($resultado["solicitud_id"]).'</a></strong></td>
									<td ><strong>'.utf8_encode(getusuario($resultado["vendedor_id"])).'</strong></td>
									<td >'.getestado($resultado["estado"]).'</td>
									<td >'.utf8_encode($resultado["Fecha"]).'</td>
									<td >'.utf8_encode(getmodulo($resultado["modulo"])).'</td>
									
									<td >'.utf8_encode(getusuario($resultado["recepcion_id"])).'</td>
									<td ><a class="elimina_solicitud" id="'.$resultado["solicitud_id"].'"><img src="images/delete.png" /></a></td>' ;
								}
						
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
            


   