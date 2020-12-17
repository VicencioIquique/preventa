<?php 
require_once("clases/conexionocdb.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

//$criterio = ""; 
if (isset($_GET['criterio'])){ 
   	$txt_criterio = $_GET["criterio"]; 
   	$criterio = " AND (A.usuario_nombre like '%" . $txt_criterio . "%')"; 
}
?>
<script type="text/javascript">
  $(document).ready(function(){

                fn_eliminar_pais();
				fn_editar_pais();
				$('#busqueda').focus();

            });
  
  


  
 
</script>

<?php
 
      $buscar = $_POST['b'];
       
    
       
?>

<div class="idTabs">
      <ul>
        <li><a href="#one"><img src="images/buscar.png" width="30px" height="30px" /></a></li>
        <li><a href="#two"><img src="images/agregar.png" width="30px" height="30px" /></a></li>
        <!-- <li><a href="#tree"><img src="images/editmod.png" width="30px" height="30px" /></a></li>-->
        <!-- Try adding this <br/> tag here
        <li><a href="#three">3</a></li>
        <li><a href="#four">4</a></li> -->
      </ul>
      <div class="items">
        <div id="one">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="horizontalFormB">
            <fieldset>
				<legend>Buscar Usuario</legend>

                      
                             <label for="pais1">
					            Buscar
                             <input name="opc" type="hidden" id="opc" size="40" class="required" value="usuarios" />
                            <input name="criterio" type="text" id="busqueda" size="40"  value="<?php echo $txt_criterio;?>" />
                            </label>

			</fieldset>
            </form></div><!-- fin one-->
        <div id="two"> <form action="javascript: fn_agregar_usuario();" method="post" id="horizontalForm">
            <fieldset>
				<legend>Agregar Usuario</legend>
                
        				    <label for="nombre1">
					            Nombre
                            <input name="nombre" type="text" id="nombre" size="40" class="required" />
                            </label>
                             <label for="nombre1">
					            Apodo Usuario
                            <input name="usuario" type="text" id="usuario" size="40" class="required" />
                            </label>
                            
                             <label for="nombre1">
					            Contraseña
                            <input name="pass" type="text" id="pass" size="40" class="required" />
                            </label>
                              <label class="first" for="title1">
                                        Retail
                                        <select id="modulo" name="modulo"   class="styled" > 
                                        <option value="001">Modulo 1010</option>
                                        <option value="002">Modulo 1132</option>
                                        <option value="003">Modulo 181</option>
                                        <option value="004">Modulo 184</option>
                                        <option value="005">Modulo 2002</option>
                                        <option value="006">Modulo 6115</option>
                                        <option value="007">Modulo 6130</option>
                                        </select>
                               </label>
                                <label class="first" for="title1">
                                        Rol
                                        <select id="rol" name="rol"   class="styled" > 
                                        <option value="2">Vendedor</option>
                                        <option value="3">Vizador</option>
                                       </select>
                               </label>
                              
                             <input name="agregar" type="submit" id="agregar" class="submit" value="Agregar" />

			</fieldset>
            </form>
         </div> <!-- fin dos -->
        
      </div> <!-- fin items -->
    </div> <!-- fin idTabs -->
        
        
           
         

            <table  id="ssptable" class="lista">
              <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Modulo</th>
                    </tr>
                </thead>
                <tbody>
                 <?php
				$filaPag =10; 
				
				if(isset($_GET['page']))
				{
					$page= $_GET['page'];
				}
				else
				{
					//SI NO DIGO Q ES LA PRIMERA PÁGINA
					$page=1;
				}		
					$sqlfilas="SELECT     COUNT(*) AS Filas
								FROM         dbo.sisap_usuarios
								WHERE     (usuario_rol NOT LIKE 1) ";
					$result = odbc_exec( $conn, $sqlfilas );
					$arr = odbc_fetch_array($result);	
					$filas = $arr['Filas']; //cantidad de registros de tabla
					
					$lastpage= ceil($filas/ $filaPag);
					
					$page=(int)$page;
 
					if($page > $lastpage)
					{
						$page= $lastpage;
					}
					 
					if($page < 1)
					{
						$page=1;
					}
  
					
				 	$sql="
							DECLARE @rowsperpage INT

							DECLARE @start INT

							SET @start = ".(($page-1)*10)."
							SET @rowsperpage = ".$filaPag." 

							SELECT * FROM
							(
							SELECT row_number() OVER (ORDER BY usuario_modulo) AS rownum, usuario_nombre,usuario_pass, usuario_rol, usuario_user, usuario_modulo
							FROM         dbo.sisap_usuarios
							WHERE usuario_rol NOT LIKE 1 ".$criterio."
							) AS A
							WHERE A.rownum 
							BETWEEN (@start) AND (@start + @rowsperpage) ";


					
					$total =0;
					$cantotal =0;

						//	echo $sql;	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
								exit( "Error en la consulta SQL" );
							}
							
							        odbc_next_result($rs);
								if (odbc_next_result($rs))
								{

									  while($resultado = odbc_fetch_array($rs))
									  { 
									   echo '<tr>
											<td ><strong>'.utf8_encode($resultado["usuario_user"]).'</strong></td>
											<td >'.utf8_encode($resultado["usuario_nombre"]).'</td>
											<td >'.utf8_encode($resultado["usuario_rol"]).'</td>
											<td >'.utf8_encode(getmodulo($resultado["usuario_modulo"])).'</td>
											</tr>' ;
									  }
								}	

				?>
                </tbody>
                <tfoot>
                	<tr>
                    	<td colspan="5"><strong>Cantidad:</strong> <span id="span_cantidad">4</span> Usuarios.</td>
                    </tr>
                </tfoot>
            </table>
			
			<?php
			//UNA VEZ Q MUESTRO LOS DATOS TENGO Q MOSTRAR EL BLOQUE DE PAGINACIÓN SIEMPRE Y CUANDO HAYA MÁS DE UNA PÁGINA
      
    if($filas != 0)
    {
       $nextpage= $page +1;
       $prevpage= $page -1;
     
       ?><ul id="pagination-clean"><?php
           //SI ES LA PRIMERA PÁGINA DESHABILITO EL BOTON DE PREVIOUS, MUESTRO EL 1 COMO ACTIVO Y MUESTRO EL RESTO DE PÁGINAS
           if ($page == 1) 
           {
            ?>
              <li class="previous-off">&laquo; Anterior</li>
              <li class="active">1</li> 
         <?php
              for($i= $page+1; $i<= $lastpage ; $i++)
              {?>
                <li><a href="?opc=usuarios&page=<?php echo $i;?>"><?php echo $i;?></a></li>
        <?php }
           
           //Y SI LA ULTIMA PÁGINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
            if($lastpage >$page )
            {?>      
                <li class="next"><a href="?opc=usuarios&page=<?php echo $nextpage;?>" >Siguiente &raquo;</a></li><?php
            }
            else
            {?>
                <li class="next-off">Next &raquo;</li>
        <?php
            }
        } 
        else
        {
     
            //EN CAMBIO SI NO ESTAMOS EN LA PÁGINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEMÁS
        ?>
            <li class="previous"><a href="?opc=usuarios&page=<?php echo $prevpage;?>">&laquo; Anterior</a></li><?php
             for($i= 1; $i<= $lastpage ; $i++)
             {
                           //COMPRUEBO SI ES LA PÁGINA ACTIVA O NO
                if($page == $i)
                {
            ?>       <li class="active"><?php echo $i;?></li><?php
                }
                else
                {
            ?>       <li><a href="?opc=usuarios&page=<?php echo $i;?>" ><?php echo $i;?></a></li><?php
                }
            }
             //Y SI NO ES LA ÚLTIMA PÁGINA ACTIVO EL BOTON NEXT     
            if($lastpage >$page )
            {   ?>   
                <li class="next"><a href="?opc=usuarios&page=<?php echo $nextpage;?>">Siguiente &raquo;</a></li><?php
            }
            else
            {
        ?>       <li class="next-off">Next &raquo;</li><?php
            }
        }     
    ?></ul></div><?php
    } 

?>	
	<?php odbc_close($conn);?>          


  