<?php 
require_once("clases/conexionocdb.php");//incluimos archivo de conexión

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

$vendedor = $_GET['id'];
$vendedor = str_pad($vendedor, 3, '0', 'STR_PAD_LEFT');
$modulo = $_GET['modulo'];
$finicio = $_GET['inicio'];
$ffin = $_GET['fin'];

if(!$finicio)
{
	 $finicio = date("m/d/Y");
	 $ffin= date("m/d/Y");
}

function cambiarFecha($fecha) 
{
  return implode("-", array_reverse(explode("-", $fecha)));
}
$finicio2 = cambiarFecha($finicio);
$ffin2 = cambiarFecha($ffin);

if ($modulo)// si selecciono modulo, se genera la consulta
{
	$conModulo = "  AND (Bodega LIKE '".$modulo."') ";
	$conModuloGroup = " , Bodega ";
}

/************************************************************ PARA LAS HORAS PUNTA ********************************************************************************/

$sql2="SELECT     SUM(TotNeto) AS total, CONVERT(char(13), FechaDocto, 120) AS HORA
FROM         dbo.RP_ReceiptsCab_SAP
WHERE     (FechaDocto >= '".$finicio2." 00:00:00.000') AND (FechaDocto <= '".$finicio2." 23:59:59.000')".$conModulo." 
GROUP BY CONVERT(char(13), FechaDocto, 120)".$conModuloGroup." ";

$sql="SELECT     COUNT(*) AS cantidad, CONVERT(char(13), FechaDocto, 120) AS HORA
FROM         dbo.RP_ReceiptsCab_SAP
WHERE     (FechaDocto >= '".$finicio2." 00:00:00.000') AND (FechaDocto <= '".$finicio2." 23:59:59.000')".$conModulo." 
GROUP BY CONVERT(char(13), FechaDocto, 120)".$conModuloGroup." ";

//echo $sql;			
	echo'  <script src="graficos/amcharts/amcharts.js" type="text/javascript"></script> ';//incluyo la librería para generar graficos	
	include("graficos/horaPunta.php");// grafico que mustra las ventas por marcas en peso 
	include("graficos/ventaClpPunta.php");// grafico que mustra las ventas por marcas en peso 
						
						
?>
<script type="text/javascript">
  $(document).ready(function(){
				//comienza focus en modulo
				$('#moduloid').focus();
				// calendarios en text de fecha inicio fin
				$( "#inicio" ).datepicker( );
				//formatos de las fechas
			   // $('#inicio').datepicker('option', {dateFormat: 'dd-mm-yy'});
				$( "#fin" ).datepicker(  );
				//$('#fin').datepicker('option', {dateFormat: 'dd-mm-yy'});
				
				
			});//fin funciotn principal
</script>
<div class="idTabs">
      <ul>
          <li><a href="#one"><img src="images/agregar.png" width="30px" height="30px" /></a></li>
		
	 </ul>
      <div class="items">
        <div id="one"> 
         <form action="" method="GET" id="horizontalForm">
            <fieldset>
				<legend>Ingresar Fechas</legend>
					<input name="opc" type="hidden" id="opc" size="40" class="required" value="horaPunta" />
					    <?php
							 	if($_SESSION["usuario_modulo"] == 0)
								{
									
									echo '<label class="first" for="title1">
									Retail
									<select id="moduloid" name="modulo"    class="styled" >
									<option ></option>';
									if($modulo)
									{
										echo'<option value="'.$modulo.'" selected>'.getmodulo($modulo).'</option>';
									}
									echo'<option value="001">Modulo 1010</option>
									<option value="002">Modulo 1132</option>
									<option value="003">Modulo 181</option>
									<option value="004">Modulo 184</option>
									<option value="005">Modulo 2002</option>
									<option value="006">Modulo 6115</option>
									<option value="007">Modulo 6130</option>
									</select>
				            </label>';
								}
					        ?>
                    <label for="fecha1">
                        Fecha
                    	<input name="inicio" type="text" id="inicio" size="40" class="required" value="<?php echo $finicio;?>"  />
                    </label>
                    
                        <input name="agregar" type="submit" id="agregar" class="submit" value="Consultar" />
				</fieldset>
            </form>
         </div> <!-- fin div two-->
      </div> <!-- fin items -->
    </div> <!-- fin idTabs -->
      
      
<div id="usual1" class="usual" > 
  <ul> 
    <li ><a id="tabdua" href="#tab1" class="selected">Gráfico hora punta de ventas</a></li> 
    <li ><a id="tabdua" href="#tab3">Detalle</a></li> 
  </ul> 
  <div id="tab1"><?php 
		//echo' <div id="ventanual" style="width:100%; height:200px;"></div>';
		
		echo'<div id="horaPunta" style="width:100%; height: 400px;"></div>';
		echo'<div id="horaPuntaClp" style="width:100%; height: 400px;"></div>';
	?>
  </div> <!-- fin de grafico de marcas -->
  <div id="tab3"> 
  	<table  id="ssptable" class="lista">
      <thead>
            <tr>
                <th>Hora</th>
                <th>Cantidad Ventas</th>
				
            </tr>
      </thead>
      <tbody>
   <?php
	$total =0;
	$cantotal =0;
     //echo $sql;	
	$rs = odbc_exec( $conn, $sql );
	$rs2 = odbc_exec( $conn, $sql );
	if ( !$rs ||  !$rs2)
	{
		exit( "Error en la consulta SQL" );
	}
		while($resultado2 = odbc_fetch_array($rs2)){ 
			$total = $total + $resultado2["cantidad"];
		}
	
		  while($resultado = odbc_fetch_array($rs)){ 
		   echo '<tr>
				<td >'.substr($resultado["HORA"], 11, 2).':00 Hrs.</td>
				<td ><strong>'.number_format($resultado["cantidad"], 0, '', '.').'</strong></td>
				
				' ;
				
				//$cantotal = $cantotal + $resultado["Cantidad"];<td ><strong>'.number_format($resultado["cantidad"]/$total, 2, ',', '.').'</strong></td>
				
			echo '</tr>';
			}?>
	  </tbody>
	  <tfoot>
			<tr>
				<td colspan="5">TOTAL de: <strong><?php echo number_format($total, 0, '', '.'); ?></strong></td>
			</tr>
	  </tfoot>
	</table>
 </div>  <!-- fin de tabla de vendedores -->
<script type="text/javascript"> 
  $("#usual1 ul").idTabs(); 
</script>
 <?php odbc_close( $conn );?>