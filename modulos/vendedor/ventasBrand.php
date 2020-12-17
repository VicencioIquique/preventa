<?php 
require_once("clases/conexionocdb.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
$vendedor = $_GET['id'];
$vendedorSelect = $_GET['id'];

$vendedor = str_pad($vendedor, 3, '0', 'STR_PAD_LEFT');
$finicio = $_GET['inicio'];
$ffin = $_GET['fin'];
$codbarra = $_GET['codbarra'];

if(!$finicio)
{
	 $finicio = date("m/d/Y");
	 $ffin= date("m/d/Y");
}



/********************** para que solo busque por modulos segun pertenesca ******************************************/
if($_SESSION["usuario_modulo"] !=0)
{
	$modulo = $_SESSION["usuario_modulo"];
	$modulo =str_pad($modulo, 3, '0', 'STR_PAD_LEFT');
}

else
{
	$modulo = $_GET['modulo'];
}
/************************************** fin privilegio de modulo *****************************/




 function cambiarFecha($fecha) {
					  return implode("-", array_reverse(explode("-", $fecha)));
					}
					$finicio2 = cambiarFecha($finicio);
					$ffin2 = cambiarFecha($ffin);


$marca = $_GET['marca']; // Pregunta si realmente busco por marca -> crea la consulta WHERE

// Consulta para llamar las marcas de los productos
$sql2= "SELECT  [Code]
      ,[Name]
  FROM [RP_VICENCIO].[dbo].[View_OMAR]";
							$rs2 = odbc_exec( $conn, $sql2 );
							if ( !$rs2 )
							{
							exit( "Error en la consulta SQL" );
							}
/************************************************************ PARA LOS VENDEDORES **************************************/

$sql3= "SELECT     SlpCode, SlpName
		FROM       SBO_Import_Eximben_SAC.dbo.OSLP WHERE SlpCode > 0";
							$rs3 = odbc_exec( $conn, $sql3 );
							if ( !$rs3 )
							{
							exit( "Error en la consulta SQL" );
							}


							
							
?>
<script type="text/javascript">
  $(document).ready(function(){
				//comienza focus en modulo
				$('#moduloid').focus();
				// calendarios en text de fecha inicio fin
				$( "#inicio" ).datepicker({ 
			   firstDay: 1, // comenzar el lunes
			  } );
				//formatos de las fechas
			   // $('#inicio').datepicker('option', {dateFormat: 'dd-mm-yy'});
				$( "#fin" ).datepicker(  );
				//$('#fin').datepicker('option', {dateFormat: 'dd-mm-yy'});

            });

</script>


<div class="idTabs">
      <ul>
        <li><a href="#one"><img src="images/agregar.png" width="30px" height="30px" /></a></li>
		<?php // pregunta si ha ingresado una fecha para que se muestre el imagen link de generar Excel
		
		if($finicio2){
		
		?>
		     <!-- <li><a href="../SISAP/modulos/vendedor/ventasproexcel.php?id=<?php //echo $vendedor; ?>&modulo=<?php // echo $modulo; ?>&inicio=<?php //echo $finicio2; ?>&fin=<?php // echo $ffin2; ?>&marca=<?php //echo $marca; ?>&codbarra=<?php //echo $codbarra; ?>"><img src="images/excel.png" width="30px" height="30px" /></a></li>-->
		<?php }?>
	 </ul>
      <div class="items">
        <div id="one"> <form action="" method="GET" id="horizontalForm">
        
            <fieldset>
				<legend>Ingresar Fechas</legend>
						
						
                            
							  <input name="opc" type="hidden" id="opc" size="40" class="required" value="ventasBM" />
							
							
					       
							
                             <label for="fecha1">
					            Inicio
                            <input name="inicio" type="text" id="inicio" size="40" class="required" value="<?php echo $finicio;?>"  />
                            </label>
							 <label for="fecha2">
					            Fin
                            <input name="fin" type="text" id="fin" size="40" class="required"  value="<?php echo $ffin;?>" />
                            </label>
                            
                              </label>
							
							
                             <input name="agregar" type="submit" id="agregar" class="submit" value="Consultar" />

			</fieldset>
            </form>
         </div> <!-- fin div two-->
      </div> <!-- fin items -->
    </div> <!-- fin idTabs -->
        
        
        
                 <?php
				 
					
					$total =0;
					$cantotal =0;
					$totalCIFEXT =0;
					$totalCIF=0;
					$totalUSD=0;
					
					
					
				  $acumMauri = 0; $cantMauri = 0; $ncMauri = 0;$cantncMauri = 0;
				  $acumMarquez = 0; $cantMarquez = 0; $ncMarquez = 0;$cantncMarquez = 0;
				  $acumVidal = 0; $cantVidal = 0; $ncVidal = 0;$cantncVidal = 0;
				  $acumRosa = 0; $cantRosa = 0; $ncRosa = 0;$cantncRosa = 0;
				  $acumOtros = 0; $cantOtros = 0; $ncOtros = 0;$cantncOtros = 0;
				 
	$sql= "SELECT     SUM(dbo.RP_ReceiptsDet_SAP.Cantidad) AS Cantidad, SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS Pextendido, 
                      SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido / dbo.RP_ReceiptsCab_SAP.TipoCambio) AS USD, SUM(dbo.RP_ReceiptsDet_SAP.CIF) AS CIF, 
                      SUM(dbo.RP_ReceiptsDet_SAP.CIF * dbo.RP_ReceiptsDet_SAP.Cantidad) AS CIFEXTENDIDO, dbo.oITM_From_SBO.SWW, dbo.RP_ReceiptsDet_SAP.Sku, 
                      dbo.RP_ReceiptsDet_SAP.Bodega, dbo.RP_ReceiptsDet_SAP.TipoDocto
FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID INNER JOIN
                      dbo.oITM_From_SBO ON dbo.oITM_From_SBO.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS = dbo.RP_ReceiptsDet_SAP.Sku LEFT OUTER JOIN
                      dbo.View_OMAR ON dbo.View_OMAR.Code = dbo.oITM_From_SBO.U_VK_Marca
WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000') 
                      
GROUP BY dbo.oITM_From_SBO.SWW, dbo.RP_ReceiptsDet_SAP.Sku, dbo.RP_ReceiptsDet_SAP.Bodega, dbo.RP_ReceiptsDet_SAP.TipoDocto" ;


										
							//echo $sql;	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}
						
							
								while($resultado = odbc_fetch_array($rs) )
								{ 
								 //echo "hola".$resultado['TipoDocto'];
								 
										if($resultado['SWW']=='Mauricio Huerta')
										{
											if($resultado['TipoDocto']==3)
											{
												$ncMauri =  $ncMauri +$resultado['Pextendido'];
												$cantncMauri =  $cantncMauri +$resultado['Cantidad'];
											}
											else
											{
												$acumMauri =  $acumMauri +$resultado['Pextendido'];
												$cantMauri =  $cantMauri +$resultado['Cantidad'];
											}
										}//fin Mauricio Huerta
										
										if($resultado['SWW']=='M Marquez')
										{
											if($resultado['TipoDocto']==3)
											{
												$ncMarquez =  $ncMarquez +$resultado['Pextendido'];
												$cantncMarquez =  $cantncMarquez +$resultado['Cantidad'];
											}
											else
											{
												$acumMarquez =  $acumMarquez +$resultado['Pextendido'];
												$cantMarquez =  $cantMarquez +$resultado['Cantidad'];
											}
										}//fin M Marquez
										
										if($resultado['SWW']=='Marianela Vidal')
										{
											if($resultado['TipoDocto']==3)
											{
												$ncVidal =  $ncVidal +$resultado['Pextendido'];
												$cantncVidal =  $cantncVidal +$resultado['Cantidad'];
											}
											else
											{
												$acumVidal =  $acumVidal +$resultado['Pextendido'];
												$cantVidal =  $cantVidal +$resultado['Cantidad'];
											}
										}//fin M Vidal
										
										if($resultado['SWW']=='Rosa Zamora')
										{
											if($resultado['TipoDocto']==3)
											{
												$ncRosa =  $ncRosa +$resultado['Pextendido'];
												$cantncRosa =  $cantncRosa +$resultado['Cantidad'];
											}
											else
											{
												$acumRosa =  $acumRosa +$resultado['Pextendido'];
												$cantRosa =  $cantRosa +$resultado['Cantidad'];
											}
										}//fin M Rosa
										
										if(($resultado['SWW']=='CAMPO ADICIONAL') || ($resultado['SWW']==NULL) ||  ($resultado['SWW']=='Sin Asignar'))
										{
											if($resultado['TipoDocto']==3)
											{
												$ncOtros =  $ncOtros +$resultado['Pextendido'];
												$cantncOtros =  $cantncOtros +$resultado['Cantidad'];
											}
											else
											{
												$acumOtros =  $acumOtros +$resultado['Pextendido'];
												$cantOtros =  $cantOtros +$resultado['Cantidad'];
											}
										}//fin M Rosa
							  
								}
							$cantotal = ($cantMauri-$cantncMauri)+($cantMarquez-$cantncMarquez)+($cantVidal-$cantncVidal)+
								     ($cantRosa-$cantncRosa)+($cantOtros-$cantncOtros);
							$total = ($acumMauri-$ncMauri)+($acumMarquez-$ncMarquez)+($acumVidal-$ncVidal)+
								     ($acumRosa-$ncRosa)+($acumOtros-$ncOtros);
				?>
	<div style="float:right;margin-right:10px;margin-top:20px;">
		  <table  id="ssptable2" class="lista" style="width:500px;margin: 0 0 0 0;  " >
              <thead>
                    <tr>
						<th>Nombre</th>
						<th>Un.</th>
						<th>Total Venta</th> 
						<!--<th>Cant N. Credito</th> 
						<th>Total N. Credito</th> 
						<th>Total CIF</th> -->
                    </tr>
                </thead>
                <tbody>
							<tr>
									<td >Mauricio Huerta</td> 
									<td ><?php echo number_format($cantMauri-$cantncMauri, 0, '', '.') ; ?></td> 
									<td ><strong><?php echo number_format($acumMauri-$ncMauri, 0, '', '.') ; ?></strong></td>
									<!--<td ><?php //echo number_format($cantncMauri, 0, '', '.') ; ?></td> 
									<td ><?php //echo number_format($ncMauri, 0, '', '.') ; ?></td> -->
							</tr>
							
							<tr>
									<td >M Marquez</td> 
									<td ><?php echo number_format($cantMarquez-$cantncMarquez, 0, '', '.') ; ?></td> 
									<td ><strong><?php echo number_format($acumMarquez-$ncMarquez, 0, '', '.') ; ?></strong></td>
									<!--<td ><?php //echo number_format($cantncMarquez, 0, '', '.') ; ?></td> 
									<td ><?php //echo number_format($ncMarquez, 0, '', '.') ; ?></td> -->
							</tr>
							
							<tr>
									<td >Marianela Vidal</td> 
									<td ><?php echo number_format($cantVidal-$cantncVidal, 0, '', '.') ; ?></td> 
									<td ><strong><?php echo number_format($acumVidal-$ncVidal, 0, '', '.') ; ?></strong></td>
									<!--<td ><?php //echo number_format($cantncVidal, 0, '', '.') ; ?></td> 
									<td ><?php //echo number_format($ncVidal, 0, '', '.') ; ?></td> -->
							</tr>
							<tr>
									<td >Rosa Zamora</td> 
									<td ><?php echo number_format($cantRosa-$cantncRosa, 0, '', '.') ; ?></td> 
									<td ><strong><?php echo number_format($acumRosa-$ncRosa, 0, '', '.') ; ?></strong></td>
									<!--<td ><?php //echo number_format($cantncRosa, 0, '', '.') ; ?></td> 
									<td ><?php //echo number_format($ncRosa, 0, '', '.') ; ?></td> -->
							</tr>
							
							<tr>
									<td >No Definido</td> 
									<td ><?php echo number_format($cantOtros-$cantncOtros, 0, '', '.') ; ?></td> 
									<td ><strong><?php echo number_format($acumOtros-$ncOtros, 0, '', '.') ; ?></strong></td>
									<!--<td ><?php //echo number_format($cantncOtros, 0, '', '.') ; ?></td> 
									<td ><?php //echo number_format($ncOtros, 0, '', '.') ; ?></td> -->
							</tr>
                </tbody>
                <tfoot>
                	<tr>
                    	<td colspan="11">Cantidad: <strong> <?php echo $cantotal; ?></strong> Productos. --- TOTAL de: <strong>$<?php echo number_format($total, 0, '', '.'); ?></strong> </td>
                    </tr>
                </tfoot>
            </table>
		</div>	
		
		<div style=" width:560px; height:300px; float:left; margin-left:10px;-moz-border-radius:5px 5px 5px;-webkit-border-radius: 5px 5px 5px;
	border-radius: 5px 5px 5px 5px;
	
	border: 1px solid #dedede;">
		<script src="graficos/amcharts/amcharts.js" type="text/javascript"></script>
			     <script type="text/javascript">
            var chart;

            var chartData = [{
                country: "Mauricio Huerta",
                  visits: <?php echo $acumMauri-$ncMauri ?>
            }, {
                country: "Marieliza Marquez",
                visits: <?php echo $acumMarquez-$ncMarquez ?>
            }, {
                country: "Rosa Zamora",
                visits: <?php echo $acumRosa-$ncRosa ?>
            }, {
                country: "Marianela Vidal",
                visits: <?php echo $acumVidal-$ncVidal ?>
            }, {
                country: "No Definido",
                visits: <?php echo $acumOtros-$ncOtros ?>
            },];


            AmCharts.ready(function () {
                // PIE CHART
                chart = new AmCharts.AmPieChart();
				

                // title of the chart
              chart.addTitle("Ventas Brand Manager", 16);

                chart.dataProvider = chartData;
                chart.titleField = "country";
                chart.valueField = "visits";
                chart.sequencedAnimation = true;
                chart.startEffect = "elastic";
                chart.innerRadius = "30%";
                chart.startDuration = 2;
                chart.labelRadius = 15;
				

				chart.autoMargins = false;
				chart.marginTop = 0;
				chart.marginBottom = 0;
				chart.marginLeft = 0;
				chart.marginRight = 0;
				chart.colors = ["#B0DE09", "#04D215", "#0D8ECF", "#0D52D1", "#ff0000"];
			

                // the following two lines makes the chart 3D
                chart.depth3D = 10;
                chart.angle = 15;

                // WRITE                                 
                chart.write("chartdiv");
            });
        </script>
    </head>
    
    <body>
        <div id="chartdiv" style="width:600px; height:350px; padding-top:-20px;"></div>
    </body>
		</div>
            


	<?php odbc_close( $conn );?>