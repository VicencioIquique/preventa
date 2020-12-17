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
					
 
 function fechaMy($fecha) {
					  return implode("-", array_reverse(explode("/", $fecha)));
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
				$( "#inicio" ).datepicker( );
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
						
						
                            
							  <input name="opc" type="hidden" id="opc" size="40" class="required" value="ventasmodulodet" />
							
							
					       
							
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
        
        
        
           
						
            <table  id="ssptable2" class="lista">
              <thead>
                    <tr>
						<th>Módulo</th>
						<th>Un.</th>
						<th>Total Venta</th> 
						<th>USD</th> 
						<th>CIF</th> 
						<!--<th>Total CIF</th> -->
                    </tr>
                </thead>
                <tbody>
                 <?php
				 
					if ($marca)
					{
						$Wmarca = " AND (dbo.oITM_From_SBO.U_VK_Marca LIKE '".$marca."')  ";
					}
					
					if ($codbarra)
					{
						$Wcodbarra = " AND (dbo.RP_ReceiptsDet_SAP.Sku LIKE '".$codbarra."')  ";
					}
					
					if ($_GET['id'])
					{
						$Wvendedor = " AND (dbo.RP_ReceiptsDet_SAP.Vendedor LIKE '".$vendedor."')  ";
					}

					$total =0;
					$cantotal =0;
					$totalCIFEXT =0;
					$totalCIF=0;
					$totalUSD=0;
					
					/*$sql= "SELECT     TOP (100) PERCENT dbo.RP_ReceiptsDet_SAP.Bodega, CONVERT(varchar, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) AS Fecha, 
                      dbo.RP_ReceiptsCab_SAP.TipoDocto, dbo.RP_ReceiptsDet_SAP.NumeroDocto, dbo.RP_ReceiptsDet_SAP.Sku, dbo.RP_ReceiptsDet_SAP.Cantidad, 
                      dbo.RP_ReceiptsDet_SAP.PrecioExtendido, dbo.RP_ReceiptsDet_SAP.PrecioExtendido / dbo.RP_ReceiptsCab_SAP.TipoCambio AS USD, 
                      dbo.RP_ReceiptsDet_SAP.CIF, dbo.RP_ReceiptsDet_SAP.CIF * dbo.RP_ReceiptsDet_SAP.Cantidad AS CIFEXTENDIDO, dbo.oITM_From_SBO.ItemName, 
                      dbo.oITM_From_SBO.U_VK_Marca, dbo.RP_ReceiptsDet_SAP.Vendedor, dbo.View_OMAR.Name, dbo.RP_ReceiptsCab_SAP.TipoCambio
FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID INNER JOIN
                      dbo.oITM_From_SBO ON dbo.oITM_From_SBO.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS = dbo.RP_ReceiptsDet_SAP.Sku LEFT OUTER JOIN
                      dbo.View_OMAR ON dbo.View_OMAR.Code = dbo.oITM_From_SBO.U_VK_Marca
WHERE     (dbo.RP_ReceiptsDet_SAP.Bodega = '".$modulo."') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000') ".$Wvendedor." ".$Wmarca." ".$Wcodbarra." ORDER BY dbo.RP_ReceiptsCab_SAP.FechaDocto" ;
*/
					
$sql2= "SELECT   dbo.RP_ReceiptsDet_SAP.Bodega, SUM(dbo.RP_ReceiptsDet_SAP.Cantidad) AS Cantidad, SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) 
                      AS Pextendido, SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido / dbo.RP_ReceiptsCab_SAP.TipoCambio) AS USD, SUM(dbo.RP_ReceiptsDet_SAP.CIF) AS CIF, 
                      SUM(dbo.RP_ReceiptsDet_SAP.CIF * dbo.RP_ReceiptsDet_SAP.Cantidad) AS CIFEXTENDIDO
FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID INNER JOIN
                      dbo.oITM_From_SBO ON dbo.oITM_From_SBO.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS = dbo.RP_ReceiptsDet_SAP.Sku LEFT OUTER JOIN
                      dbo.View_OMAR ON dbo.View_OMAR.Code = dbo.oITM_From_SBO.U_VK_Marca
WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000') AND 
                      (dbo.RP_ReceiptsDet_SAP.TipoDocto = 3)
GROUP BY dbo.RP_ReceiptsDet_SAP.Bodega" ;
	
						$sql= "SELECT   dbo.RP_ReceiptsDet_SAP.Bodega, SUM(dbo.RP_ReceiptsDet_SAP.Cantidad) AS Cantidad, SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) 
                      AS Pextendido, SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido / dbo.RP_ReceiptsCab_SAP.TipoCambio) AS USD, SUM(dbo.RP_ReceiptsDet_SAP.CIF) AS CIF, 
                      SUM(dbo.RP_ReceiptsDet_SAP.CIF * dbo.RP_ReceiptsDet_SAP.Cantidad) AS CIFEXTENDIDO
FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID INNER JOIN
                      dbo.oITM_From_SBO ON dbo.oITM_From_SBO.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS = dbo.RP_ReceiptsDet_SAP.Sku LEFT OUTER JOIN
                      dbo.View_OMAR ON dbo.View_OMAR.Code = dbo.oITM_From_SBO.U_VK_Marca
WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000') AND 
                      (dbo.RP_ReceiptsDet_SAP.TipoDocto <> 3)
GROUP BY dbo.RP_ReceiptsDet_SAP.Bodega" ;


										
							//echo $sql2;	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}
							$rs2 = odbc_exec( $conn, $sql2 );
							if ( !$rs2 )
							{
							exit( "Error en la consulta SQL" );
							}
							$i=0;
							
							/******************************************** MYSQL ***********************************/
							require_once("clases/conexionMysql.php");
							//################# CONSULTA PARA OBTENER EL TRAMO DEL TRABAJADOR SEGÚN SUELDO IMPONIBLE#########
							list($anio1, $dia1,$mes1) = explode("-",fechaMy($finicio2)); 
							list($anio2, $dia2,$mes2) = explode("-",fechaMy($ffin2)); 
													$db = new MySQL();
													$consultaMy = $db->consulta("SELECT SUM(SUBTOTAL) AS TOTAL,SUM(TOTALCIF) AS TOTALCIF,SUM(SUBTOTAL/TIPOCAMBIO) AS TOTALUS
													FROM tpv2.mo_facturas WHERE FECHA >= '".$anio1.'-'.$mes1.'-'.$dia1."' AND FECHA <='".$anio2.'-'.$mes2.'-'.$dia2."' AND ESTADO = 'I'
							GROUP BY ID_CAJA;");
							
							/*echo "SELECT SUM(SUBTOTAL) AS TOTAL,SUM(TOTALCIF) AS TOTALCIF,SUM(SUBTOTAL/TIPOCAMBIO) AS TOTALUS
													FROM tpv2.mo_facturas WHERE FECHA >= '".$anio1.'-'.$mes1.'-'.$dia1."' AND FECHA <='".$anio2.'-'.$mes2.'-'.$dia2."' AND ESTADO = 'I'
							GROUP BY ID_CAJA;";*/
													if($db->num_rows($consultaMy)>0){
													  while($resultadosMy = $db->fetch_array($consultaMy))
													  { 
														
															$TOTAL2077 = $resultadosMy['TOTAL'];
															$TOTALCIF2077 = $resultadosMy['TOTALCIF'];
															$TOTALUS2077 = $resultadosMy['TOTALUS'];
													  }
													}
							/******************************************************************FIN MYSQL *******************************/
							
						while(($resultado = odbc_fetch_array($rs)) || ($i <= 6) ){ 
							  
							 /* while($resultado2 = odbc_fetch_array($rs2))
							  {  */
							  
								  if($resultado["Bodega"]==$resultado2["Bodega"])
								  {
							  
								   echo '<tr>
										<td ><a style="color:#000;font-weight:bold;" href="index.php?opc=ventasdet&modulo=00'.(int)$resultado["Bodega"].'&id=&inicio='.$finicio.'&fin='.$ffin.'">'.utf8_encode(getmodulo((int)$resultado["Bodega"])).'</a></td> 
										<td >'.number_format($resultado["Cantidad"]-$resultado2["Cantidad"], 0, '', '.').'</td> 
										<td ><strong>'.number_format($resultado["Pextendido"]-$resultado2["Pextendido"], 0, '', '.').'</strong></td>
										<td >'.number_format($resultado["USD"]-$resultado2["USD"], 2, ',', '.').'</td> 
										<td >'.number_format($resultado["CIF"]-$resultado2["CIF"], 2, ',', '.').'</td>  ' ;
										/*<td >'.number_format($resultado["CIFEXTENDIDO"], 2, ',', '.').'</td>*/
										$total = $total + ($resultado["Pextendido"]-$resultado2["Pextendido"]);
										$cantotal = $cantotal + ($resultado["Cantidad"]-$resultado2["Cantidad"]);
										$totalUSD = $totalUSD + ($resultado["USD"]-$resultado2["USD"]);
										$totalCIF = $totalCIF + ($resultado["CIF"]-$resultado2["CIF"]);
										$totalCIFEXT = $totalCIFEXT + $resultado["CIFEXTENDIDO"];
										
										$i++;
								}//fin if
								else
								{
									 echo '<tr>
										<td ><a style="color:#000;font-weight:bold;" href="index.php?opc=ventasdet&modulo=00'.(int)$resultado["Bodega"].'&id=&inicio='.$finicio.'&fin='.$ffin.'">'.utf8_encode(getmodulo((int)$resultado["Bodega"])).'</a></td> 
										<td >'.number_format($resultado["Cantidad"], 0, '', '.').'</td> 
										<td ><strong>'.number_format($resultado["Pextendido"], 0, '', '.').'</strong></td>
										<td >'.number_format($resultado["USD"], 2, ',', '.').'</td> 
										<td >'.number_format($resultado["CIF"], 2, ',', '.').'</td>  ' ;
										/*<td >'.number_format($resultado["CIFEXTENDIDO"], 2, ',', '.').'</td>*/
										$total = $total + ($resultado["Pextendido"]);
										$cantotal = $cantotal + ($resultado["Cantidad"]);
										$totalUSD = $totalUSD + ($resultado["USD"]);
										$totalCIF = $totalCIF + ($resultado["CIF"]);
										$totalCIFEXT = $totalCIFEXT + $resultado["CIFEXTENDIDO"];
										
										$i++;
								
								  }//fin else}
								  
								//}
							}//fin primer while
							  echo '<tr>
									<td ><a style="color:#000;font-weight:bold;" >Modulo 2077</a></td> 
									<td >'.number_format(0, 0, '', '.').'</td> 
									<td ><strong>'.number_format($TOTAL2077, 0, '', '.').'</strong></td>
									<td >'.number_format($TOTALUS2077, 2, ',', '.').'</td> 
									<td >'.number_format($TOTALCIF2077, 2, ',', '.').'</td>  ' ;
							echo '</tr>';
				?>
                </tbody>
                <tfoot>
                	<tr>
                    	<td colspan="11">Cantidad: <strong> <?php echo $cantotal; ?></strong> Productos. --- TOTAL de: <strong>$<?php echo number_format($total+$TOTAL2077, 0, '', '.'); ?></strong> --- USD: <strong><?php echo number_format($totalUSD+$TOTALUS2077, 2, ',', '.'); ?></strong> --- CIF: <strong><?php echo number_format($totalCIF+$TOTALCIF2077, 2, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
			
            


	<?php @odbc_close( $conn );?>