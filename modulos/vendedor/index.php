
<?php 
require_once("clases/conexionocdb.php");

//$criterio = ""; 
if (isset($_GET['criterio'])){ 
   	$txt_criterio = $_GET["criterio"]; 
   	$criterio = " AND (SlpName like '%" . $txt_criterio . "%')"; 
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
      </ul>
      <div class="items">
        <div id="one">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="horizontalFormB">
            <fieldset>
				<legend>Buscar Vendedor</legend>

                      
                             <label for="pais1">
					            Buscar
                             <input name="opc" type="hidden" id="opc" size="40" class="required" value="vendedores" />
                            <input name="criterio" type="text" id="busqueda" size="40"  value="<?php echo $txt_criterio;?>" />
                            </label>

			</fieldset>
            </form></div><!-- fin one-->
     
      </div> <!-- fin items -->
    </div> <!-- fin idTabs -->
        
        
           
         

            <table  id="ssptable" class="lista">
              <thead>
                    <tr>
                        <th>ID</th>
						<th>Nombre</th>
                       <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
				
                 <?php
	
							$sql= "SELECT     SlpCode, SlpName, Memo, Commission, GroupCode, Locked, DataSource, UserSign, EmpID, Active
										FROM       SBO_Import_Eximben_SAC.dbo.OSLP WHERE SlpCode > 0 ".$criterio;
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}
							
							
							
							  while($resultado = odbc_fetch_array($rs)){ 
							   echo '<tr>
									<td >'.$resultado["SlpCode"].'</td> 
									<td >'.utf8_encode($resultado["SlpName"]).'</td> 
									<td><a  href="index.php?opc=ventest&id='.$resultado["SlpCode"].'" ><img src="images/bar.png" width="16px" height="16px" /></a></td> ';
									
							?>
							<?php echo		'</tr>';
								}
							

				?>
                </tbody>
                <tfoot>
                	<tr>
                    	<td colspan="5"><strong>Cantidad:</strong> <span id="span_cantidad">4</span> Vendedores.</td>
                    </tr>
                </tfoot>
            </table>
           


	<?php odbc_close( $conn );?>



            


   