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
        <li><a href="#one"><img src="images/buscar.png" width="30px" height="30px" /></a></li>
		<?php // pregunta si ha ingresado una fecha para que se muestre el imagen link de generar Excel
		
		if($finicio2){
		
		?>
		     <li><a href="../SISAP/modulos/impresiones/inf_cheque.php?id=<?php //echo $vendedor; ?>&modulo=<?php // echo $modulo; ?>&inicio=<?php //echo $finicio2; ?>&fin=<?php // echo $ffin2; ?>&marca=<?php //echo $marca; ?>&codbarra=<?php //echo $codbarra; ?>"><img src="images/reports.png" width="30px" height="30px" /></a></li>
		<?php }?>
	 </ul>
      <div class="items">
        <div id="one"> <form action="" method="GET" id="horizontalForm">
        
            <fieldset>
				<legend>Ingresar Fechas</legend>
						
						
                            
							  <input name="opc" type="hidden" id="opc" size="40" class="required" value="avisos" />
							
							
                              <?php
							 	if($_SESSION["usuario_modulo"] == 0)
								{
									
									echo '<label class="first" for="title1">
									Retail
									<select id="moduloid" name="modulo"    class="styled" >
									<option value="" selected></option>';
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
							<!-- <label for="fecha2">
					            Fin
                            <input name="fin" type="text" id="fin" size="40" class="required"  value="<?php echo $ffin;?>" />
                            </label>-->
                             <label for="ndocto">
					            Nro. Cheque
                            <input name="ndocto" type="text" id="ndocto" size="40"  value="<?php echo $_GET['ndocto'];?>" />
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
						<th>Modulo</th>
						<th>Fecha Compra</th>
						<th>Fecha de Pago</th>
						<th>Dias</th> 
						<th>Nro doc</th> 
						<th>Monto</th> 
						<th>Quedan</th> 
						<th></th> 
						
                    </tr>
                </thead>
                <tbody>
                 <?php
				 
				
					if ($_GET['modulo'])
					{
						$Wmodulo = " AND ( dbo.RP_ReceiptsCab_SAP.Bodega = '".$_GET['modulo']."')  ";
					}
					if ($_GET['ndocto'])
					{
						$Wndocto = " AND ( dbo.RP_ReceiptsPagos_SAP.NumeroDoc = '".$_GET['ndocto']."')  ";
					}


					$total =0;
					$cantotal =0;
					$totalCIFEXT =0;
					$totalCIF=0;
					$totalUSD=0;
					
					$sql= "SELECT     TOP (100) PERCENT SUM(dbo.RP_ReceiptsPagos_SAP.Monto) AS TOTAL, CONVERT(varchar, dbo.RP_ReceiptsPagos_SAP.FechaDoc, 103) AS fpago, 
                      dbo.RP_ReceiptsPagos_SAP.TipoPago, dbo.RP_ReceiptsPagos_SAP.WorkStation, CONVERT(varchar, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) AS fcompra, 
                      DATEDIFF(day, dbo.RP_ReceiptsCab_SAP.FechaDocto, dbo.RP_ReceiptsPagos_SAP.FechaDoc) AS Dias, dbo.RP_ReceiptsCab_SAP.RutCliente, 
                      dbo.RP_ReceiptsPagos_SAP.NumeroDoc, DATEDIFF(day,'".$finicio2."', dbo.RP_ReceiptsPagos_SAP.FechaDoc) AS QUEDAN, dbo.RP_ReceiptsCab_SAP.Bodega

FROM         dbo.RP_ReceiptsPagos_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsPagos_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID
WHERE     (dbo.RP_ReceiptsPagos_SAP.TipoDocto NOT LIKE 3) AND (dbo.RP_ReceiptsPagos_SAP.TipoPago LIKE 'Payments') AND 
                      (dbo.RP_ReceiptsPagos_SAP.FechaDoc >= '".$finicio2."') ".$Wmodulo." ".$Wndocto."
GROUP BY dbo.RP_ReceiptsPagos_SAP.TipoDocto, dbo.RP_ReceiptsPagos_SAP.FechaDoc, dbo.RP_ReceiptsPagos_SAP.TipoPago, dbo.RP_ReceiptsPagos_SAP.WorkStation, 
                      dbo.RP_ReceiptsCab_SAP.FechaDocto, dbo.RP_ReceiptsCab_SAP.RutCliente, dbo.RP_ReceiptsPagos_SAP.NumeroDoc, dbo.RP_ReceiptsCab_SAP.Bodega

ORDER BY dbo.RP_ReceiptsPagos_SAP.FechaDoc, dbo.RP_ReceiptsPagos_SAP.TipoDocto, dbo.RP_ReceiptsPagos_SAP.TipoPago DESC" ;

					
										
					//echo $sql;	
						
					
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultado = odbc_fetch_array($rs)){ 
							  
							 
							   echo '<tr>
									<td >'.getModulo((int)$resultado["Bodega"]).'</td> 
									<td >'.$resultado["fcompra"].'</td> 
									<td >'.$resultado["fpago"].'</td> 
									<td >'.number_format($resultado["Dias"], 0, '', '.').'</td> 
									<td ><strong>'.(int)$resultado["NumeroDoc"].'</strong></td>
									<td >'.number_format($resultado["TOTAL"], 0, '', '.').'</td>
									<td style="font-size:14px;color: #323133;
 font-family: Helvetica Neue, Arial, Helvetica, sans-serif;
letter-spacing: -1px;
text-decoration: none; 
text-shadow: 1px 1px #fff, 0 0 #0e0e0e, 2px 3px 1px #e3e3e3; 
text-transform: none; 
word-spacing: -2px;" >'.number_format($resultado["QUEDAN"], 0, ',', '.').'</td> ';
									if($resultado["QUEDAN"] <= 2)
									echo'<td > <img src="images/alert.png" width="16px" height="16px" /></td> ' ;
									if($resultado["QUEDAN"] > 2 && $resultado["QUEDAN"] <= 5)
									echo'<td > <img src="images/alert2.png" width="16px" height="16px" /></td> ' ;
									if($resultado["QUEDAN"] > 5 && $resultado["QUEDAN"] <= 7)
									echo'<td > <img src="images/alert3.png" width="16px" height="16px" /></td> ' ;
									if($resultado["QUEDAN"] > 7 )
									echo'<td > <img src="images/alert4.png" width="16px" height="16px" /></td> ' ;

									$total = $total + $resultado["TOTAL"];
									$cantotal++;
								
									
									
							?>
							<?php echo '</tr>';
								}
							

				?>
                </tbody>
                <tfoot>
                	<tr>
						<td colspan="11">Cantidad: <strong> <?php echo $cantotal; ?></strong> Cheques. --- TOTAL de: <strong>$<?php echo number_format($total, 0, '', '.'); ?></strong></td>
					</tr>
                </tfoot>
            </table>
			
            


	<?php odbc_close( $conn );?>