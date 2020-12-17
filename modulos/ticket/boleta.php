<?php
session_start();
require_once("../../clases/conexionocdb.php");

	//$sql= "SELECT top 1 [NumeroDocto], FechaCreacion FROM [dbo].[RP_ReceiptsCabPre_SAP] ORDER BY FechaCreacion DESC";
	//$sql = "SELECT top 1 [NumeroDocto] FROM [dbo].[RP_ReceiptsCabPre_SAP] WHERE Bodega = 002 ORDER BY FechaCreacion DESC";
	/*SQL DE BUSQUEDA DE NUMERO DE DOCUMENTO EN TABLA DE PRUEBA SOLO POR FIN DE SEMANA*/
	$sql ="SELECT top 1 NumeroDocto FROM [RP_VICENCIO].[dbo].[NumeroDoctoPrueba] ORDER BY NumeroDocto DESC";
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$numDoctoRes = $resultado['NumeroDocto'];
	}
	
	$datos = $_POST['formulario'];
	$prods = $_POST['arreglo'];
	$tam = count($prods); 
	$codigo = $prods[$tam-1][1];
	$tipoDocto = substr($codigo,0,1);
	$workstation = substr($codigo,1,1);
	$bodega = substr($codigo,2,3);
	$numeroDocto = (((int)$numDoctoRes)+1);
	$numeroDocto =$numeroDocto;
	$numeroDocto = str_pad($numeroDocto, 5, "0", STR_PAD_LEFT);
	if($numDoctoRes > 99999){
		$numeroDocto = (11111);
	}
	
	$fecha = substr($codigo,10,2).'16'.substr($codigo,14,4);
	$fechainsert = substr($codigo,10,4)."-".substr($codigo,14,2)."-".substr($codigo,16,2). "00:00:00";
	$timeFechaInsert = strtotime($fechainsert);
	$fechaInsertT = date('Y-m-d H:i:s',$timeFechaInsert);
	
	
	$idVenta = $tipoDocto.$workstation.$bodega.$numeroDocto.$fecha;
	
	//	echo $idVenta; 
	
	$fechaCreacion = date('Y-m-d H:i:s');
	$timeFechaCreacion = strtotime($fechaCreacion);
	$fechaCreacionT = date('Y-m-d H:i:s',$timeFechaCreacion);
	
	$sqlcabecera = "INSERT INTO [RP_VICENCIO].dbo.RP_ReceiptsCabPre_SAP VALUES('".$bodega."', '".$workstation."', '".$tipoDocto."', '".($numeroDocto)."', GETDATE(),".$prods[$tam-1][0].", 0, 0, ".$prods[$tam-1][0].", '".$prods[$tam-1][5]."', '".$prods[$tam-1][5]."', ".$prods[$tam-1][4].", 0, 0, 0, 0, ".$prods[$tam-1][4].", 0,' ',' ','".$idVenta."', GETDATE(), 4, GETDATE(), NULL)";
	//echo $sqlcabecera."<br><br>";
	
	$rs = odbc_exec( $conn, $sqlcabecera );
	if(!$rs){
		exit( "Error en la consulta SQL CABECERA" );
	}
	odbc_close( $conn );
	/*INSERCIÓN EN NUMERODOCTOPRUEBA SOLO POR FIN DE SEMANA PARA SEPARAR NUMERO DE DOCUMENTO DE RPRO*/
	$sqlPruebaAumento = "INSERT INTO [RP_VICENCIO].[dbo].[NumeroDoctoPrueba] VALUES ('".$numeroDocto."')";
	$ra = odbc_exec($conn, $sqlPruebaAumento);
	if(!$rs){
		exit("Error en la consulta aumento de prueba");
	}
	odbc_close($conn);
	
	/*FIN INSERCION NUMERODOCTOPRUEBA*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- BOOSTRAP -->
<link href="css/bootstrap.css" rel="stylesheet">
<script src="jquery.min.js"></script>
<script src="jQuery.print.js"></script>
<script src="barcode.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		/*GENERAR CODIGO DE BARRA*/
		var strValue = '<?php echo $idVenta;?>';
		var strBarcodeHTML = code128(strValue);
		$('#barcode').html(strBarcodeHTML);
		/*IMPRIMIR*/
		
		//$(".printer").bind("click",function()
		//{
			$('.print').print();
		//});
		
		$("#btnCerrar").click(function(){
			/*CERRAR SESIÓN PARA QUE EL EQUIPO QUEDE DISPONIBLE A OTROS USUARIO A LA PREVENTA*/
			var session_destoy = '<?php session_destroy();?>';
			window.opener.location.reload();
			window.close();
		});
		setInterval(function() {
			$("#btnCerrar").click();
        }, 2000);
		
	});
</script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="print">
		<center><img src="vicencio.png" width="200" height="70"/></center><br>
		<p style="font-size:14px; text-align:center;"><strong>***************M&oacute;dulo 
		<?php
			if($bodega == '000'){
				echo ' 2077';
			}else if($bodega == '001'){
				echo ' 1010';
			}else if($bodega == '002'){
				echo ' 1132';
			}else if($bodega == '003'){
				echo ' 181';
			}else if($bodega == '005'){
				echo ' 2002';
			}
		?>***************</strong><br>
		<?php
			if($prods[$tam-1][4] =='49'){
				echo '
					<p style="font-size:14px; text-align:center;"><strong>*************AUTOSERVICIO*************</strong>
				';
			}
		?>
		<!--Pre Venta N: <?php echo $idVenta;?><br>
		1-9<br>
		CLIENTE GENERICO LOCAL</p>-->
		<table>
			<thead>
				<tr>
					<th width="100px" style="text-align:left; font-size:12px; font-weight: normal;" >C&oacute;digo/</th>
				</tr>
			</thead>
			<thead> <!--se puede agregar mas thead !-->
				<tr>
					<th width="180px" style="text-align:left; font-size:12px; font-weight: normal;">Descripci&oacute;n</th>
					<th style="text-align:left; font-size:12px; font-weight: normal;">Cantidad</th>
					<th style="text-align:left; font-size:12px; font-weight: normal; padding-left:5px;">Precio</th>
				</tr>
			</thead>
			<tbody>
            <?php
			/*
				0 = RecNumer = Cod articulo
				1 = codigo
				2 = descripcion
				3 = cantidad
				4 = % descuento
				5 = valor total 
				6 = total descuento por unidad
			*/
				for($i=0;$i<count($prods)-1;$i++){
					
					echo '<tr>
							<td style="font-size:12px; margin-top:15px;">'.($i+1).') '.$prods[$i][1].'</td>
						</tr>
						<tr>
							<td style="font-size:12px;">'.$prods[$i][2].'</td>
							<td style="font-size:12px;">'.$prods[$i][3].'</td>
							<td style="font-size:12px;">'.$prods[$i][5].'</td>
						</tr>
						';
					$precioOriginal = -((($prods[$i][5] + $prods[$i][6])/$prods[$i][3])*0.19);
					//echo "Precio original: ".$precioOriginal."<br>";
					if($prods[$i][6] == ""){
						$descuento = '0';
					}else{
						$descuento = $prods[$i][6];
					}
					//echo "Descuento :".$descuento."<br>";
					$precioFinal = (($prods[$i][5] + $prods[$i][6])/$prods[$i][3]);
					//echo "Precio final: ".$precioFinal."<br>";
					$precioExtendido = (($precioFinal * $prods[$i][3] )-$descuento);
					//echo "Precio extendido: ".$precioExtendido."<br>";
					
					//OBTENER EL CostoExt PARA INSERTAR EN DETALLE DE PREVENTA OBTENIDO DESDE SAP
					/* SQL PARA BASE DE DATOS DE PRUEBA
					$sqlCostoExt = "SELECT AvgPrice FROM [SBO_Eximben_ZOFI].[dbo].[OITM] WHERE ItemCode = '".$prods[$i][1]."'";
					*/
					$sqlCostoExt = "SELECT AvgPrice FROM [SBO_Imp_Eximben_SAC].[dbo].[OITM] WHERE ItemCode = '".$prods[$i][1]."'";
					$rs = odbc_exec( $conn, $sqlCostoExt );
					if ( !$rs ){
						exit( "Error en la consulta SQL" );
					}
					
					while($resultado = odbc_fetch_array($rs)){ 
						$CostoExt = $resultado['AvgPrice'];
					}
					odbc_close( $conn );
					if($prods[$tam-1][4]<10){
						$vendedor = '00'.$prods[$tam-1][4];
					}else if($prods[$tam-1][4] >=10){
						$vendedor = '0'.$prods[$tam-1][4];
					}else if($prods[$tam-1][4] >=100){
						$vendedor = $prods[$tam-1][4];
					}
					
					if(substr($prods[$i][1],0,3) == 999){
						$sqldetalle="INSERT INTO [RP_VICENCIO].dbo.RP_ReceiptsDetPre_SAP VALUES('".$bodega."','".$tipoDocto."','".$numeroDocto."',".($i+1).", '".$prods[$i][1]."', ".$prods[$i][3].", ".$precioOriginal.", ".$descuento.", ".$precioFinal.", '".$vendedor."',0,0,".$precioExtendido.", 0, '".$workstation."', '".$idVenta."', ".floor($CostoExt).",1, ".$prods[$i][0].", '".$prods[$i][1]."', GETDATE(),'Package Item','0')";
						$rs = odbc_exec( $conn, $sqldetalle );
						if(!$rs){
							exit( "Error en la consulta SQL DETALLE" );
						}
						odbc_close( $conn );
					}else if((substr($prods[$i-1][1],0,3) == 999)||(substr($prods[$i-2][1],0,3) == 999)){
						$sqldetalle="INSERT INTO [RP_VICENCIO].dbo.RP_ReceiptsDetPre_SAP VALUES('".$bodega."','".$tipoDocto."','".$numeroDocto."',".($i+1).", '".$prods[$i][1]."', ".$prods[$i][3].", ".$precioOriginal.", ".$descuento.", ".$precioFinal.", '".$vendedor."',0,0,".$precioExtendido.", 0, '".$workstation."', '".$idVenta."', ".floor($CostoExt).",1, ".$prods[$i][0].", '".$prods[$i][1]."', GETDATE(),'','10')";
						$rs = odbc_exec( $conn, $sqldetalle );
						if(!$rs){
							exit( "Error en la consulta SQL DETALLE" );
						}
						odbc_close( $conn );
					}else{
						$sqldetalle="INSERT INTO [RP_VICENCIO].dbo.RP_ReceiptsDetPre_SAP VALUES('".$bodega."','".$tipoDocto."','".$numeroDocto."',".($i+1).", '".$prods[$i][1]."', ".$prods[$i][3].", ".$precioOriginal.", ".$descuento.", ".$precioFinal.", '".$vendedor."',0,0,".$precioExtendido.", 0, '".$workstation."', '".$idVenta."', ".floor($CostoExt).",1, ".$prods[$i][0].", '".$prods[$i][1]."', GETDATE(),'','')";					
						$rs = odbc_exec( $conn, $sqldetalle );
						if(!$rs){
							exit( "Error en la consulta SQL DETALLE" );
						}
						odbc_close( $conn );
					}
				}
			?>		
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td style="font-size:12px;">Total: </td>
					<td style="font-size:12px;"><?php $tam = count($prods); echo $prods[$tam-1][0]; ?></td>
				</tr>
			</tfoot>
		</table>
		<p style="font-size:14px; text-align:center;"><strong>************************************************</strong><br>
		<!--CODIGO DE BARRA-->
		<div class="barcode128h" id="barcode" style="overflow-y:hidden; font-size:60px;"></div>
		<center><p style="font-size:10px; margin-top:-1px;"><?php echo $idVenta; ?></p></center>
		
		<!-- TABLA VENDEDOR Y FECHA -->
		<table>
			<thead>
				<tr>
					<th width="145px" style="text-align:left; font-size:12px; font-weight: normal;" >Vendedor: <?php echo $prods[$tam-1][3];?></th>
					<th  style="text-align:left; font-size:12px; font-weight: normal;"><?php $date = date('m/d/Y h:i:s a', time()); echo $date;?></th>
				</tr>
			</thead>
		</table>
		</table>
		<center><p style="font-size:12px;"><?php
			if($bodega == '000'){
				echo 'Inversiones Servimex Limitada';
			}else if($bodega == '001'){
				echo 'Importaciones Eximben SAC';
			}else if($bodega == '002'){
				echo 'Importaciones Eximben SAC';
			}else if($bodega == '003'){
				echo 'Importaciones Eximben SAC';
			}else if($bodega == '005'){
				echo 'Importaciones Eximben SAC';
			}
		?></p></center>
		<!-- FIN CODIGO DE BARRA-->
	</div>
	<!--<p><input type="button" class="printer" value="Imprimir"></p>--><br />
    <center><button type="submit" id ="btnCerrar" class="btn btn-info" style="width:130px;; height:70px;;">Cerrar</button></center>
</body>
</html>
