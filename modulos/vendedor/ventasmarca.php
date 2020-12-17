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
	$conModulo = "  AND (dbo.RP_ReceiptsDet_SAP.Bodega LIKE '".$modulo."') ";
	$conModuloGroup = " , dbo.RP_ReceiptsDet_SAP.Bodega ";
}

/************************************************************ PARA LOS VENDEDORES ********************************************************************************/

$sql= "SELECT     SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS SumaMarca, SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca   ,SUM(dbo.RP_ReceiptsDet_SAP.Cantidad) AS SumCantidad, dbo.View_OMAR.Name

FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID LEFT OUTER JOIN
                      SBO_Import_Eximben_SAC.dbo.OITM ON 
                      dbo.RP_ReceiptsDet_SAP.Sku COLLATE SQL_Latin1_General_CP850_CI_AS = SBO_Import_Eximben_SAC.dbo.OITM.ItemCode  LEFT OUTER JOIN
                      dbo.View_OMAR ON SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca = dbo.View_OMAR.Code

WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000')  AND  (dbo.RP_ReceiptsDet_SAP.TipoDocto <> 3)
			".$conModulo." 
GROUP BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca, dbo.View_OMAR.Name".$conModuloGroup." 
ORDER BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca";

//echo $sql;			
	echo'  <script src="graficos/amcharts/amcharts.js" type="text/javascript"></script> ';//incluyo la librería para generar graficos	
	include("graficos/marcas.php");// grafico que mustra las ventas por marcas en peso 
	include("graficos/unidadPorMarca.php");// grafico que muestra las cantidades en unidad por marca					
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
		<?php // pregunta si ha ingresado una fecha para que se muestre el imagen link de generar Excel
		if($finicio2){?>
		  <li><a href="../SISAP/modulos/vendedor/topventamarcaex.php?modulo=<?php echo $modulo; ?>&inicio=<?php echo $finicio2; ?>&fin=<?php echo $ffin2; ?>"><img src="images/excel.png" width="30px" height="30px" /></a></li>
		<?php }?>
	 </ul>
      <div class="items">
        <div id="one"> 
         <form action="" method="GET" id="horizontalForm">
            <fieldset>
				<legend>Ingresar Fechas</legend>
					<input name="opc" type="hidden" id="opc" size="40" class="required" value="ventmarca" />
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
                        Inicio
                    	<input name="inicio" type="text" id="inicio" size="40" class="required" value="<?php echo $finicio;?>"  />
                    </label>
                    <label for="fecha2">
                        Fin
                    	<input name="fin" type="text" id="fin" size="40" class="required"  value="<?php echo $ffin;?>" />
                    </label>
                        <input name="agregar" type="submit" id="agregar" class="submit" value="Consultar" />
				</fieldset>
            </form>
         </div> <!-- fin div two-->
      </div> <!-- fin items -->
    </div> <!-- fin idTabs -->
      
      
<div id="usual1" class="usual" > 
  <ul> 
    <li ><a id="tabdua" href="#tab1" class="selected">Gráfico Ventas</a></li> 
    <li ><a id="tabdua" href="#tab3">Detalle</a></li> 
  </ul> 
  <div id="tab1"><?php 
		//echo' <div id="ventanual" style="width:100%; height:200px;"></div>';
		echo'<div id="unidadCantidad" style="width:100%; height: 400px;"></div>';
		echo'<div id="ingresos" style="width:100%; height: 400px;"></div>';
	?>
  </div> <!-- fin de grafico de marcas -->
  <div id="tab3"> 
  	<table  id="ssptable" class="lista">
      <thead>
            <tr>
                <th>Marca</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
      </thead>
      <tbody>
   <?php
	$total =0;
	$cantotal =0;
     //echo $sql;	
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs )
	{
		exit( "Error en la consulta SQL" );
	}
	
		  while($resultado = odbc_fetch_array($rs)){ 
		   echo '<tr>
				<td >'.utf8_encode($resultado["Name"]).'</td>
				<td ><strong>'.number_format($resultado["SumCantidad"], 0, '', '.').'</strong></td>
				<td ><strong>'.number_format($resultado["SumaMarca"], 0, '', '.').'</strong></td> ' ;
				$total = $total + $resultado["SumaMarca"];
				//$cantotal = $cantotal + $resultado["Cantidad"];
				
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