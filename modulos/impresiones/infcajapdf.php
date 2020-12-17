<?php
//#####################################//
//INFORME DESARROLLADO POR JULIO CORTES//
//#####PARA EXIMBEN SAC 26/09/20013####//
//#####################################//
require('../../clases/fpdf/fpdf.php'); // libreria para generar pdf
require_once("../../clases/conexionocdb.php"); // conexion con obcd driver
require_once("../../clases/funciones.php"); // funciones propias
ini_set('max_execution_time', 300); // Forzar más tiempo de ejecucion

//Recibo los parametros desde la url
(INT)$modulo = $_GET['modulo'];
$finicio = $_GET['inicio'];
$ffin =  $_GET['fin'];

 function cambiarFecha($fecha) // funcion para cambiar el formato de la fecha y orden
 {
	 return implode("-", array_reverse(explode("-", $fecha)));
 }					
	$date = strtotime($finicio);
	$finicio2 = cambiarFecha($finicio);
	$ffin2 = cambiarFecha($ffin);
	 
    $total =0;// acumulador
	$cantotal =0;//contador
					
		$sql ="SELECT  TOP (100) PERCENT SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS Sumaventa, dbo.RP_ReceiptsDet_SAP.Bodega, 	
			  dbo.RP_ReceiptsDet_SAP.TipoDocto,dbo.RP_ReceiptsDet_SAP.NumeroDocto AS NRODOCTO, CONVERT(varchar, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) AS Fecha,
			  DATENAME(weekday,dbo.RP_ReceiptsCab_SAP.FechaDocto) AS DIA, dbo.RP_ReceiptsCab_SAP.RetencionDL18219 AS RETEN, dbo.RP_ReceiptsCab_SAP.Workstation	
			  FROM         
			  dbo.RP_ReceiptsDet_SAP INNER JOIN
              dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID
			  WHERE      (CONVERT(datetime, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) >= '".$finicio2." 00:00:00.000') AND 
			             (CONVERT(datetime, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) <= '".$finicio2." 23:59:59.000') AND 
						 (CONVERT(INT, dbo.RP_ReceiptsDet_SAP.Bodega) = '".$modulo."')
			  GROUP BY dbo.RP_ReceiptsDet_SAP.Bodega, dbo.RP_ReceiptsDet_SAP.TipoDocto, dbo.RP_ReceiptsDet_SAP.NumeroDocto, dbo.RP_ReceiptsCab_SAP.FechaDocto, 
                      dbo.RP_ReceiptsCab_SAP.RetencionDL18219, dbo.RP_ReceiptsCab_SAP.Workstation
			  ORDER BY NRODOCTO"; // para ver las boletas, facturas y srf!

		$sql2="SELECT  SUM(dbo.RP_ReceiptsPagos_SAP.Monto) AS TOTAL, dbo.RP_ReceiptsPagos_SAP.FechaDoc, dbo.RP_ReceiptsPagos_SAP.TipoPago, 
					   dbo.RP_ReceiptsPagos_SAP.WorkStation, dbo.RP_ReceiptsCab_SAP.FechaDocto, DATEDIFF(day, dbo.RP_ReceiptsCab_SAP.FechaDocto, 
					   dbo.RP_ReceiptsPagos_SAP.FechaDoc) AS Dias, dbo.RP_ReceiptsCab_SAP.RutCliente
		       FROM    dbo.RP_ReceiptsPagos_SAP INNER JOIN
					   dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsPagos_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID
			   WHERE   (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND 
			   		   (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$finicio2." 23:59:59.000') AND 
					   (dbo.RP_ReceiptsPagos_SAP.Bodega LIKE '".$modulo."') AND 
					   (dbo.RP_ReceiptsPagos_SAP.TipoDocto NOT LIKE 3)
			   GROUP BY dbo.RP_ReceiptsPagos_SAP.TipoDocto, dbo.RP_ReceiptsPagos_SAP.FechaDoc, dbo.RP_ReceiptsPagos_SAP.TipoPago, 
			   			dbo.RP_ReceiptsPagos_SAP.WorkStation,dbo.RP_ReceiptsCab_SAP.FechaDocto, dbo.RP_ReceiptsCab_SAP.RutCliente
			   ORDER BY dbo.RP_ReceiptsPagos_SAP.TipoDocto, dbo.RP_ReceiptsPagos_SAP.TipoPago";


$sql3="SELECT     TOP (100) PERCENT Monto,  CONVERT(varchar, FechaDoc, 103) AS Fecha, TipoPago, WorkStation, NumeroDoc, CdCuenta
FROM         dbo.RP_ReceiptsPagos_SAP
WHERE     (FechaDoc >= '".$finicio2." 00:00:00.000') AND (FechaDoc <= '".$finicio2." 23:59:59.000') AND (Bodega LIKE '".$modulo."') AND (TipoDocto NOT LIKE 3) AND 
                      (TipoPago LIKE 'Payments')
ORDER BY TipoDocto, TipoPago";
				 
					
							//echo $sql;
							
/************************************************************************* Inicio Ventas por factura Boleta SF **********************************/
					$total =0;
					$cantotal =0;
					
	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
								exit( "Error en la consulta SQL" );
							}
							
							$maxlun=$maxlun2=$maxlun4=$maxmart=$maxmart2=$maxmart4=$maxmier=$maxmier2=$maxjuev=$maxjuev2=$maxvier=$maxvier2=$maxsab=$maxsab2=$maxdom=$maxdom2=$maxstd=$maxstd2 = -9999999;
							$minlun=$minlun2=$minlun4=$minmart=$minmart2=$minmart4=$minmier=$minmier2=$minjuev=$minjuev2=$minvier=$minvier2=$minsab=$minsab2=$mindom=$mindom2=$minstd=$minstd2 =9999999;
							//$maxlun2 = -9999999;
							//$minlun2 =999999999999999;
							$acumlun=0;$acumlun2=$acumlun4=$acumlun3 =0;$acummart=$acummart2=$acummart3 =0;$acummier=$acummier2=$acummier3 =0;$acumjuev=$acumjuev2=$acumjuev3 =0;$acumvier=$acumvier2=$acumvier3 =0;$acumsab=$acumsab2=$acumsab3 =0;$acumdom=$acumdom2=$acumdom3 =0;$acumstd=$acumstd2=$acumstd3 =0;
							
							$acumreten1=$acumreten2=$acumreten3=0; // acumulador de ley de retencion 18219
							

							  while($resultado = odbc_fetch_array($rs))
							  { 
							 
									
									
									$total = $total + $resultado["Sumaventa"];
									$cantotal = $cantotal + $resultado["Cantidad"];
									$numerodocto =  intval($resultado["NRODOCTO"]);
									$nombredia = utf8_encode($resultado["DIA"]);
									$caja = $resultado["Workstation"];
									
									if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4)
									{
										$acumreten1 = $acumreten1 + $resultado["RETEN"];
									}
									if($resultado["TipoDocto"] == 2)
									{
										$acumreten2 = $acumreten2 + $resultado["RETEN"];
									}
									if($resultado["TipoDocto"] == 3)
									{
										$acumreten3 = $acumreten3 + $resultado["RETEN"];
									}
		 
		 
		 					if($nombredia)
								{
										
										if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor numero de documento
										{
										$acumstd = $acumstd + $resultado["Sumaventa"];
										if($numerodocto>$maxstd && $resultado["TipoDocto"] == 1  )
										{
											$maxstd = $numerodocto;
										}
										if($numerodocto<$minstd && $resultado["TipoDocto"] == 1)
										{
											 $minstd = $numerodocto;
											
										}
										
									}//fin if docto 1 -4
									
									 if($resultado["TipoDocto"] == 2) // para encontrar el mayor y menor numero de documento
									{
										$acumstd2 = $acumstd2 + $resultado["Sumaventa"];
										if($numerodocto>$maxstd2)
										{
											$maxstd2 = $numerodocto;
										}
									    if($numerodocto<$minstd2)
										{
											 $minstd2 = $numerodocto;
											
										}
										
									} //fin if docto2
									
									if($resultado["TipoDocto"] == 3) // para encontrar el mayor y menor numero de documento
									{
										$acumstd3 = $acumstd3 + $resultado["Sumaventa"];
										
									} //fin if docto 3
								} // fin lunes
		 
	
								
								if($caja == 1)
								{
										
										if($resultado["TipoDocto"] == 1 ) // para encontrar el mayor y menor numero de documento
										{
										$acumlun = $acumlun + $resultado["Sumaventa"];
										if($numerodocto>$maxlun && $resultado["TipoDocto"] == 1  )
										{
											$maxlun = $numerodocto;
										}
										if($numerodocto<$minlun && $resultado["TipoDocto"] == 1)
										{
											 $minlun = $numerodocto;
											
										}
										
										
									}//fin if docto 1 -4
									
									if( $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor boleta manual
										{
											$acumlun4 = $acumlun4 + $resultado["Sumaventa"];
											if($numerodocto>$maxlun4 && $resultado["TipoDocto"] == 4  )
											{
												$maxlun4 = $numerodocto;
											}
											if($numerodocto<$minlun4 && $resultado["TipoDocto"] == 4)
											{
												 $minlun4 = $numerodocto;
												
											}
										}
									
									 if($resultado["TipoDocto"] == 2) // para encontrar el mayor y menor numero de documento
									{
										$acumlun2 = $acumlun2 + $resultado["Sumaventa"];
										if($numerodocto>$maxlun2)
										{
											$maxlun2 = $numerodocto;
										}
									    if($numerodocto<$minlun2)
										{
											 $minlun2 = $numerodocto;
											
										}
										
									} //fin if docto2
									
									if($resultado["TipoDocto"] == 3) // para encontrar el mayor y menor numero de documento
									{
										$acumlun3 = $acumlun3 + $resultado["Sumaventa"];
										
									} //fin if docto 3
								} // fin lunes
								if($caja == 2)
								{
										
									if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor numero de documento
									{
										$acummart = $acummart + $resultado["Sumaventa"];
										if($numerodocto>$maxmart && $resultado["TipoDocto"] == 1)
										{
											$maxmart = $numerodocto;
										}
										if($numerodocto<$minmart && $resultado["TipoDocto"] == 1)
										{
											 $minmart = $numerodocto;
											
										}
										
									}
									
									if($resultado["TipoDocto"] == 2 ) // para encontrar el mayor y menor numero de documento
									{
										$acummart2 = $acummart2 + $resultado["Sumaventa"];
										if($numerodocto>$maxmart2)
										{
											$maxmart2 = $numerodocto;
										}
									    if($numerodocto<$minmart2)
										{
											 $minmart2 = $numerodocto;
											
										}
										
									}
									if($resultado["TipoDocto"] == 3) // para encontrar el mayor y menor numero de documento
									{
										$acummart3 = $acummart3 + $resultado["Sumaventa"];
										
									} //fin if docto 3
								} //fin martes
								if($caja == 8)
								{
									if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor numero de documento
									{
										$acummier = $acummier + $resultado["Sumaventa"];
										if($numerodocto>$maxmier && $resultado["TipoDocto"] == 1)
										{
											$maxmier = $numerodocto;
										}
										if($numerodocto<$minmier && $resultado["TipoDocto"] == 1)
										{
											 $minmier = $numerodocto;
											
										}
										
									}
									
									if($resultado["TipoDocto"] == 2) // para encontrar el mayor y menor numero de documento
									{
										$acummier2 = $acummier2 + $resultado["Sumaventa"];
										if($numerodocto>$maxmier2)
										{
											$maxmier2 = $numerodocto;
										}
									    if($numerodocto<$minmier2)
										{
											 $minmier2 = $numerodocto;
											
										}
										
									}
									if($resultado["TipoDocto"] == 3) // para encontrar el mayor y menor numero de documento
									{
										$acummier3 = $acummier3 + $resultado["Sumaventa"];
										
									} //fin if docto 3
								} // fin miercoles
								if($nombredia == 'Thursday')
								{
										
									if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor numero de documento
									{
										$acumjuev = $acumjuev + $resultado["Sumaventa"];
										if($numerodocto>$maxjuev && $resultado["TipoDocto"] == 1)
										{
											$maxjuev = $numerodocto;
										}
										if($numerodocto<$minjuev && $resultado["TipoDocto"] == 1)
										{
											 $minjuev = $numerodocto;
											
										}
										
									}
									
									if($resultado["TipoDocto"] == 2) // para encontrar el mayor y menor numero de documento
									{
										$acumjuev2 = $acumjuev2 + $resultado["Sumaventa"];
										if($numerodocto>$maxjuev2)
										{
											$maxjuev2 = $numerodocto;
										}
									    if($numerodocto<$minjuev2)
										{
											 $minjuev2 = $numerodocto;
											
										}
										
									}
									if($resultado["TipoDocto"] == 3) // para encontrar el mayor y menor numero de documento
									{
										$acumjuev3 = $acumjuev3 + $resultado["Sumaventa"];
										
									} //fin if docto 3
								} // fin jueves
								if($nombredia == 'Friday')
								{
										
									if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor numero de documento
									{
										$acumvier = $acumvier + $resultado["Sumaventa"];
										if($numerodocto>$maxvier && $resultado["TipoDocto"] == 1)
										{
											$maxvier = $numerodocto;
										}
										if($numerodocto<$minvier && $resultado["TipoDocto"] == 1)
										{
											 $minvier = $numerodocto;
											
										}
										
									}
									
									if($resultado["TipoDocto"] == 2) // para encontrar el mayor y menor numero de documento
									{
										$acumvier2 = $acumvier2 + $resultado["Sumaventa"];
										if($numerodocto>$maxvier2)
										{
											$maxvier2 = $numerodocto;
										}
									    if($numerodocto<$minvier2)
										{
											 $minvier2 = $numerodocto;
											
										}
										
									}
									if($resultado["TipoDocto"] == 3) // para encontrar el mayor y menor numero de documento
									{
										$acumvier3 = $acumvier3 + $resultado["Sumaventa"];
										
									} //fin if docto 3
								} // viernes
								if($nombredia == 'Saturday')
								{
										
									if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor numero de documento
									{
										$acumsab = $acumsab + $resultado["Sumaventa"];
										if($numerodocto>$maxsab && $resultado["TipoDocto"] == 1)
										{
											$maxsab = $numerodocto;
										}
										if($numerodocto<$minsab && $resultado["TipoDocto"] == 1)
										{
											 $minsab = $numerodocto;
											
										}
										
									}
									
									if($resultado["TipoDocto"] == 2) // para encontrar el mayor y menor numero de documento
									{
										$acumsab2 = $acumsab2 + $resultado["Sumaventa"];
										if($numerodocto>$maxsab2)
										{
											$maxsab2 = $numerodocto;
										}
									    if($numerodocto<$minsab2)
										{
											 $minsab2 = $numerodocto;
											
										}
										
									}
									if($resultado["TipoDocto"] == 3) // para encontrar el mayor y menor numero de documento
									{
										$acumsab3 = $acumsab3 + $resultado["Sumaventa"];
										
									} //fin if docto 3
								} // fin Sabado
								if($nombredia == 'Sunday')
								{
										
									if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor numero de documento
									{
										$acumdom = $acumdom + $resultado["Sumaventa"];
										if($numerodocto>$maxdom && $resultado["TipoDocto"] == 1)
										{
											$maxdom = $numerodocto;
										}
										if($numerodocto<$mindom && $resultado["TipoDocto"] == 1)
										{
											 $mindom = $numerodocto;
											
										}
										
									}
									
									if($resultado["TipoDocto"] == 2) // para encontrar el mayor y menor numero de documento
									{
										$acumdom2 = $acumdom2 + $resultado["Sumaventa"];
										if($numerodocto>$maxdom2)
										{
											$maxdom2 = $numerodocto;
										}
									    if($numerodocto<$mindom2)
										{
											 $mindom2 = $numerodocto;
											
										}
										
									}
									if($resultado["TipoDocto"] == 3) // para encontrar el mayor y menor numero de documento
									{
										$acumdom3 = $acumdom3 + $resultado["Sumaventa"];
										
									} //fin if docto 3
								} // Total por Dias de la semana
					
								} // fin while
							//Calculo el total de todos los medios por día
							$acumlunestotal = $acumlun + $acumlun2;
							$acummartestotal = $acummart + $acummart2;
							$acummiertotal = $acummier + $acummier2;
							$acumjuevtotal = $acumjuev + $acumjuev2;
							$acumviertotal = $acumvier + $acumvier2;
							$acumsabtotal = $acumsab + $acumsab2;
							$acumdomtotal = $acumdom + $acumdom2;
							$acumstdtotal = $acumstd + $acumstd2;
							
							$TOTALBOLETA = $acumstd;
							$TOTALFACT =  $acumstd2;
							$TOTAL = $acumstdtotal;
							//echo $maxlun." menor ".$minlun." ".$acumlun." ".$acummart." ".$acummierc." ".$acumjuev." ".$acumvier." ".$acumsab." ".$acumdom;					
							if($maxlun == -9999999)$maxlun=0; if($minlun == 9999999)$minlun=0;
							if($maxmart == -9999999)$maxmart=0; if($minmart == 9999999)$minmart=0;
							if($maxmier == -9999999)$maxmier=0; if($minmier == 9999999)$minmier=0;
							if($maxjuev == -9999999)$maxjuev=0; if($minjuev == 9999999)$minjuev=0;
							if($maxvier == -9999999)$maxvier=0; if($minvier == 9999999)$minvier=0;
							if($maxsab == -9999999)$maxsab=0; if($minsab == 9999999)$minsab=0;
							if($maxdom == -9999999)$maxdom=0; if($mindom == 9999999)$mindom=0;
							if($maxstd == -9999999)$maxstd=0; if($minstd == 9999999)$minstd=0;
							//max min valores 2 facturas
							if($maxlun2 == -9999999)$maxlun2=0; if($minlun2 == 9999999)$minlun2=0;
							if($maxlun4 == -9999999)$maxlun4=0; if($minlun4 == 9999999)$minlun4=0;
							if($maxmart4 == -9999999)$maxmart4=0; if($minmart4 == 9999999)$minmart4=0;
							if($maxmart2 == -9999999)$maxmart2=0; if($minmart2 == 9999999)$minmart2=0;
							if($maxmier2 == -9999999)$maxmier2=0; if($minmier2 == 9999999)$minmier2=0;
							if($maxjuev2 == -9999999)$maxjuev2=0; if($minjuev2 == 9999999)$minjuev2=0;
							if($maxvier2 == -9999999)$maxvier2=0; if($minvier2 == 9999999)$minvier2=0;
							if($maxsab2 == -9999999)$maxsab2=0; if($minsab2 == 9999999)$minsab2=0;
							if($maxdom2 == -9999999)$maxdom2=0; if($mindom2 == 9999999)$mindom2=0;
							if($maxstd2 == -9999999)$maxstd2=0; if($minstd2 == 9999999)$minstd2=0;
							
							  // tabla que mustra el detalle de tipo de medio de pago
					
				$rs2 = odbc_exec( $conn, $sql2 );
							if ( !$rs2 )
							{
								exit( "Error en la consulta SQL" );
							}
							 $total1 =0;
							 $cash=$tCredito=$tDebito=$chequealdia=$chequeafecha = 0;
							  while($resultado2 = odbc_fetch_array($rs2)){ 
							  		
									if($resultado2["TipoPago"]=="Cash")
									{
										$cash =  $cash + $resultado2["TOTAL"];
									}
									
									if($resultado2["TipoPago"]=="CreditCard")
									{
										$tCredito = $tCredito + $resultado2["TOTAL"];
									}
									
														
									if($resultado2["TipoPago"]=="DebitCard")
									{
										$tDebito = $tDebito + $resultado2["TOTAL"];
									}
									 if($resultado2["TipoPago"]=="Payments")
									{
											
											
											if( $resultado2["Dias"]>0)
											{
																				
												$chequeafecha = $chequeafecha + $resultado2["TOTAL"];
											
											}	
											
											if( $resultado2["Dias"]==0)
											{
																				
												$chequealdia = $chequealdia + $resultado2["TOTAL"];
											
											}
						
									}
				
									 //$total1 = $total1 + $resultado2["TOTAL"];
								} // fin while
									
								$depositoDia = $cash+$chequealdia;
								$depositoPendiente= $tCredito+$tDebito+$chequeafecha;


$pdf=new FPDF();
$pdf->AddPage();

class PDF extends FPDF
{


//pie de pagina
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        $this->setX(10);
         $this->Cell(0,10,'Reportes',0,0,'L');
		 $this->Image('logoSISAP.jpg',12,258,9,9);
}
}

//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P','Letter');  
$pdf->SetFont('Times','',12);

/********** ZOFRI S.A. **************/
$pdf->SetFillColor(68,68,68);
$pdf->SetDrawColor(0,0,255);
$pdf->SetTextColor(255,255,255);
$pdf->SetXY(9, 8);//posicion 
$pdf->SetFont('Helvetica','B',12); // tamaño fuente header
$pdf->Cell(0,10,' INFORME DE CIERRE DE CAJA '.strtoupper(getmodulo($modulo)).'                                                             '.date("d", $date).'/'.date("m", $date).'/'.date("Y", $date),0,0,'L','true');
$pdf->SetFillColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
/********** Fin Zofri del header **************/

	
$caby = 20;


/********** ZOFRI S.A. **************/
$pdf->SetFillColor(238,238,238);
$pdf->SetDrawColor(0,0,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(9, $caby);//posicion 
$pdf->SetFont('Helvetica','B',9); // tamaño fuente header
$pdf->Cell(0,5,'1.- VENTA',0,0,'L','true');
$pdf->SetFillColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
/********** Fin Zofri del header **************/
$pdf->SetFont('Helvetica','',9); // tamaño fuente header
$caby = $caby+10;
/********** solo fecha del header **************/
	$fecha1 = "26/08/2013";
	$fecha2= "31/08/2013";

	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'BOLETAS',0,1);// dia


/********** Fin fecha del header **************/


$caby = $caby+30.5;
/********** solo fecha del header **************/

	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'FACTURAS',0,1);// dia

/********** Fin fecha del header **************/

$caby = $caby+12;
/********** solo fecha del header **************/

	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'SRF-MODULO',0,1);// dia

/********** Fin fecha del header **************/

$caby = $caby+20;
/********** solo fecha del header **************/

	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'RETENC I.V.A. N°',0,1);// dia

/********** Fin fecha del header **************/


$caby = $caby+22;

/********** ZOFRI S.A. **************/
$pdf->SetFillColor(238,238,238);
$pdf->SetDrawColor(0,0,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(9, $caby);//posicion 
$pdf->SetFont('Helvetica','B',9); // tamaño fuente header
$pdf->Cell(0,5,'2.- DEPOSITO POR VENTA DEL DIA',0,0,'L','true');
$pdf->SetFillColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
/********** Fin Zofri del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'EFECTIVO',0,1);// dia

/********** Fin fecha del header **************/
$caby = $caby+7;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'EFECTIVO US$',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'CHEQUES AL DIA',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'CHEQUE BANCO',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'CHEQUES OTROS BANCOS',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'OTROS',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+16;

/********** ZOFRI S.A. **************/
$pdf->SetFillColor(238,238,238);
$pdf->SetDrawColor(0,0,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(9, $caby);//posicion 
$pdf->SetFont('Helvetica','B',9); // tamaño fuente header
$pdf->Cell(0,5,'3.- DEPOSITOS PENDIENTES POR CREDITOS',0,0,'L','true');
$pdf->SetFillColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
/********** Fin Zofri del header **************/


$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'TARJETAS DE CREDITO',0,1);// dia

/********** Fin fecha del header **************/
$caby = $caby+7;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'TARJETAS DE DEBITO',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'CHEQUES A FECHA',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'VALE-OTROS',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+13;  // item tres 3
/********** solo fecha del header *******************************************************************************************************************/
	 $pdf->SetFont('Helvetica','b',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'DETALLE CREDITO',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+5;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'A) TARJETAS DE CREDITO Y DEBITO',0,1);// dia

/********** Fin fecha del header **************/
$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(60, $caby);//posicion dia
    $pdf->Cell(0,5,'SALDO ARRASTRE CAJA ANTERIOR',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(60, $caby);//posicion dia
    $pdf->Cell(0,5,'CREDITO HOY',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(60, $caby);//posicion dia
    $pdf->Cell(0,5,'(*) REBAJA POR DEPOSITO TRANSBANK',0,1);// dia
/********** Fin fecha del header **************/
$caby = $caby+7;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(60, $caby);//posicion dia
    $pdf->Cell(0,5,'SALDO ARRASTRE PROX. CAJA',0,1);// dia
/********** Fin fecha del header **************/

$caby = $caby+5;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'B) CHEQUES A FECHA',0,1);// dia

/********** Fin fecha del header **************/
$caby = $caby+6;
/********** solo fecha del header **************/
	 $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(60, $caby);//posicion dia
    $pdf->Cell(0,5,'SALDO ARRASTRE CAJA ANTERIOR',0,1);// dia
/********** Fin fecha del header **************/


//Fields Name position
$Y_Fields_Name_position = 30;
//Table position, under Fields Name
$Y_Table_Position =36;

$pdf->SetFillColor(232,232,232);

//Boleta primera fila
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(50);
$pdf->Cell(30,6,$minlun,1,0,'C',0);
$pdf->SetX(100);
$pdf->Cell(30,6,$maxlun,1,0,'C',0);
$pdf->SetX(150);
$pdf->Cell(30,6,number_format($acumlun, 0, '', '.'),1,0,'R',0);

$pdf->Ln();


//Boleta Segunda fila (caja2)
$Y_Table_Position = $Y_Table_Position;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,$minlun4,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,$maxlun4,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($acumlun4, 0, '', '.'),1,'R');

//Boleta Segunda fila (caja2)
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,$minmart,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,$maxmart,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($acummart, 0, '', '.'),1,'R');

//Boleta tercera fila (caja 3)
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,$minmart4,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,$maxmart4,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($acummart4, 0, '', '.'),1,'R');

//Boleta tercera fila (caja 3)
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,$minmier,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,$maxmier,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($acummier, 0, '', '.'),1,'R');



//Factura cuarta fila 
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,$minlun2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,$maxlun2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'',0,'R');

// Factura quinta fila
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,$minmart2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,$maxmart2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($acumlun2+$acummart2, 0, '', '.'),1,'R');

// SRF sexta fila
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,'0',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,'0',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'0',1,'R');

// Total septima fila
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,'',0,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(30,6,'TOTAL VENTAS',0,'C');
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format(($TOTALBOLETA+$TOTALFACT), 0, '', '.'),1,'R',1);

$Y_Table_Position = $Y_Table_Position+14;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'',1,'R');

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'',1,'R');

// novena fila
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,'',0,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(30,6,'TOTAL INGRESOS',0,'C');
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format(($TOTALBOLETA+$TOTALFACT), 0, '', '.'),1,'R',1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(180);
$pdf->MultiCell(25,6,number_format(($TOTALBOLETA+$TOTALFACT), 0, '', '.'),1,'R',1);

// decima fila
$Y_Table_Position = $Y_Table_Position+17;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($cash, 0, '', '.'),1,'R');

// fila once
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(30,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->MultiCell(30,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'',1,'R');




$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($chequealdia, 0, '', '.'),1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'',1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'',1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'',1,'R');

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(30,6,'TOTAL DEPOSITOS',0,'C');
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($depositoDia, 0, '', '.'),1,'R',1);


// // TERCER ITEM TARJETAS DE CREDITOS
$Y_Table_Position = $Y_Table_Position+17;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($tCredito, 0, '', '.'),1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($tDebito, 0, '', '.'),1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($chequeafecha, 0, '', '.'),1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,'',1,'R');

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(100);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(30,6,'TOTAL CREDITOS',0,'C');
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,number_format($depositoPendiente, 0, '', '.'),1,'R',1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(180);
$pdf->MultiCell(25,6,number_format($depositoDia+$depositoPendiente, 0, '', '.'),1,'R',1);

// // TERCER ITEM TARJETAS DE DETALLE
$Y_Table_Position = $Y_Table_Position+19;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(55,6,'',1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(55,6,number_format($tCredito+$tDebito, 0, '', '.'),1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(55,6,'',1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(55,6,'',1,'R');
$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(55,6,'',0,'R');

$Y_Table_Position = $Y_Table_Position+6;

$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(55,6,'',1,'R',1);


/**************************************************************************************** SEGUNDA HOJA******************************************************/

$sql3="SELECT     TOP (100) PERCENT SUM(dbo.RP_ReceiptsPagos_SAP.Monto) AS TOTAL, CONVERT(varchar, dbo.RP_ReceiptsPagos_SAP.FechaDoc, 103) AS fpago, 
                      dbo.RP_ReceiptsPagos_SAP.TipoPago, dbo.RP_ReceiptsPagos_SAP.WorkStation, CONVERT(varchar, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) AS fcompra, DATEDIFF(day, dbo.RP_ReceiptsCab_SAP.FechaDocto, dbo.RP_ReceiptsPagos_SAP.FechaDoc) AS Dias, dbo.RP_ReceiptsCab_SAP.RutCliente, 
                      dbo.RP_ReceiptsPagos_SAP.NumeroDoc
FROM         dbo.RP_ReceiptsPagos_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsPagos_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID
WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$finicio2." 23:59:59.000') AND 
                      (dbo.RP_ReceiptsPagos_SAP.Bodega LIKE '".$modulo."') AND (dbo.RP_ReceiptsPagos_SAP.TipoDocto NOT LIKE 3)AND 
                      (dbo.RP_ReceiptsPagos_SAP.TipoPago LIKE 'Payments')
GROUP BY dbo.RP_ReceiptsPagos_SAP.TipoDocto, dbo.RP_ReceiptsPagos_SAP.FechaDoc, dbo.RP_ReceiptsPagos_SAP.TipoPago, dbo.RP_ReceiptsPagos_SAP.WorkStation, 
                      dbo.RP_ReceiptsCab_SAP.FechaDocto, dbo.RP_ReceiptsCab_SAP.RutCliente, dbo.RP_ReceiptsPagos_SAP.NumeroDoc

ORDER BY dbo.RP_ReceiptsPagos_SAP.TipoDocto, dbo.RP_ReceiptsPagos_SAP.TipoPago,Dias";

$total =0;
$cantotal =0;
					
		
			
		//echo $sql;	
		$rs3 = odbc_exec( $conn, $sql3 );
		if ( !$rs3 )
		{
		exit( "Error en la consulta SQL" );
		}
		if(($chequeafecha>0) || ($chequealdia>0))
		{					  

			/********** ZOFRI S.A. **************/
			$Y_Table_Position = $Y_Table_Position+16;
			$pdf->SetFillColor(68,68,68);
			$pdf->SetDrawColor(0,0,255);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY(9, $Y_Table_Position);//posicion 
			$pdf->SetFont('Helvetica','B',12); // tamaño fuente header
			$pdf->Cell(0,10,'DETALLE CHEQUES',0,0,'L','true');
			$pdf->SetFillColor(0,0,0);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetTextColor(0,0,0);
			/********** Fin Zofri del header **************/
			

				$Y_Fields_Name_position =  25;

				$Y_Table_Position = 25;
				
				
				$pdf->SetFillColor(232,232,232);
				
				//Cabecera
				$pdf->SetFont('Arial','B',8);
				$pdf->SetY($Y_Fields_Name_position);
				$pdf->SetX(9);
				$pdf->Cell(30,6,'Fecha Emision',1,0,'C',1);
				$pdf->SetX(39);
				$pdf->Cell(30,6,'Tipo Cheque',1,0,'C',1);
				$pdf->SetX(69);
				$pdf->Cell(30,6,'Numero',1,0,'C',1);
				$pdf->SetX(99);
				$pdf->Cell(30,6,'Rut CLiente',1,0,'C',1);
				$pdf->SetX(129);
				$pdf->Cell(25,6,'Fecha Pago',1,0,'C',1);
				$pdf->SetX(154);
				$pdf->Cell(20,6,'Dias',1,0,'C',1);
				$pdf->SetX(174);
				$pdf->Cell(32,6,'Total',1,0,'C',1);
				$pdf->Ln();
				
				while($row = odbc_fetch_array($rs3)){ 
					//Cu
					$Y_Table_Position =$Y_Table_Position+ 6;
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(9);
					$pdf->Cell(30,6,$row["fcompra"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(39);
					if($row["Dias"] ==0)$pdf->Cell(30,6,'Cheque al Dia',1,0,'C',0);
					else $pdf->Cell(30,6,'Cheque a Fecha',1,0,'C',0);
					
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(69);
					$pdf->Cell(30,6,$row["NumeroDoc"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(99);
					$pdf->Cell(30,6,$row["RutCliente"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(129);
					$pdf->Cell(25,6,$row["fpago"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(154);
					$pdf->Cell(20,6,$row["Dias"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(174);
					$pdf->Cell(32,6,number_format($row["TOTAL"], 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
					
				 }//fin while de la tabla detalles cheques


		}// fin crea celdas si existen cheques

$pdf->Output();

?>