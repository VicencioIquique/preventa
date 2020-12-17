<?php
 require('../../clases/fpdf/fpdf.php');
require_once("../../clases/conexionocdb.php");
require_once("../../clases/funciones.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes



(INT)$modulo = $_GET['modulo'];
$finicio = $_GET['inicio'];
$ffin =  $_GET['fin'];

 function cambiarFecha($fecha) 
 					{
					  return implode("-", array_reverse(explode("-", $fecha)));
					}
					$finicio2 = cambiarFecha($finicio);
					$ffin2 = cambiarFecha($ffin);
	 
					

					$total =0;
					$cantotal =0;
					
				$sql= "SELECT     TOP (100) PERCENT SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS Sumaventa, dbo.RP_ReceiptsDet_SAP.Bodega, dbo.RP_ReceiptsDet_SAP.TipoDocto, 
                      dbo.RP_ReceiptsDet_SAP.NumeroDocto AS NRODOCTO, CONVERT(varchar, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) AS Fecha, DATENAME(weekday, 
                      dbo.RP_ReceiptsCab_SAP.FechaDocto) AS DIA, dbo.RP_ReceiptsCab_SAP.RetencionDL18219 AS RETEN
FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID
WHERE     (CONVERT(datetime, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) >= '".$finicio2." 00:00:00.000') AND (CONVERT(datetime, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) 
                      <= '".$ffin2." 23:59:59.000') AND (CONVERT(INT, dbo.RP_ReceiptsDet_SAP.Bodega) = '".$modulo."')
GROUP BY dbo.RP_ReceiptsDet_SAP.Bodega, dbo.RP_ReceiptsDet_SAP.TipoDocto, dbo.RP_ReceiptsDet_SAP.NumeroDocto, dbo.RP_ReceiptsCab_SAP.FechaDocto, 
                      dbo.RP_ReceiptsCab_SAP.RetencionDL18219
ORDER BY NRODOCTO
";


					
							//echo $sql;
							//echo " Aqui hay un error que se genera porque son muchos registros que se solicitan, y el tipo de variable solo permite ciertos byte mañana se analizará";	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}
							
							$maxlun=$maxlun2=$maxmart=$maxmart2=$maxmier=$maxmier2=$maxjuev=$maxjuev2=$maxvier=$maxvier2=$maxsab=$maxsab2=$maxdom=$maxdom2 = -9999999;
							$minlun=$minlun2=$minmart=$minmart2=$minmier=$minmier2=$minjuev=$minjuev2=$minvier=$minvier2=$minsab=$minsab2=$mindom=$mindom2 =9999999;
							//$maxlun2 = -9999999;
							//$minlun2 =999999999999999;
							$acumlun=0;$acumlun2=$acumlun3 =0;$acummart=$acummart2=$acummart3 =0;$acummier=$acummier2=$acummier3 =0;$acumjuev=$acumjuev2=$acumjuev3 =0;$acumvier=$acumvier2=$acumvier3 =0;$acumsab=$acumsab2=$acumsab3 =0;$acumdom=$acumdom2=$acumdom3 =0;
							
							$acumreten1=$acumreten2=$acumreten3=0; // acumulador de ley de retencion 18219
							

							  while($resultado = odbc_fetch_array($rs)){ 
							   /*echo '<tr>
									<td >'.$resultado["Fecha"].'</td>
							         <td >'.utf8_encode($resultado["Bodega"]).'</td>
								     <td >'.utf8_encode($resultado["DIA"]).'</td>
									<td >'.$resultado["NRODOCTO"].'</td>
									<td >'.$resultado["TipoDocto"].'</td>
									<td >'.$resultado["RETEN"].'</td>
									<td >'.number_format($resultado["Sumaventa"], 0, '', '.').'</td> ' ;*/
									$total = $total + $resultado["Sumaventa"];
									$cantotal = $cantotal + $resultado["Cantidad"];
									$numerodocto =  intval($resultado["NRODOCTO"]);
									$nombredia = utf8_encode($resultado["DIA"]);
									
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
		 
								
								if($nombredia == 'Monday')
								{
										
										if($resultado["TipoDocto"] == 1 || $resultado["TipoDocto"] == 4) // para encontrar el mayor y menor numero de documento
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
								if($nombredia == 'Tuesday')
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
								if($nombredia == 'Wednesday')
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
					 // echo '</tr>';
								} // fin while
							//Calculo el total de todos los medios por día
							$acumlunestotal = $acumlun + $acumlun2;
							$acummartestotal = $acummart + $acummart2;
							$acummiertotal = $acummier + $acummier2;
							$acumjuevtotal = $acumjuev + $acumjuev2;
							$acumviertotal = $acumvier + $acumvier2;
							$acumsabtotal = $acumsab + $acumsab2;
							$acumdomtotal = $acumdom + $acumdom2;
							
							$TOTALBOLETA =  $acumlun + $acummart +$acummier+ $acumjuev +$acumvier +$acumsab +$acumdom;
							$TOTALFACT =  $acumlun2 + $acummart2 +$acummier2+ $acumjuev2 +$acumvier2 +$acumsab2 +$acumdom2;
							$TOTAL = $acumlunestotal +$acummartestotal+ $acummiertotal+ $acumjuevtotal+ $acumviertotal +$acumsabtotal +$acumdomtotal;
							//echo $maxlun." menor ".$minlun." ".$acumlun." ".$acummart." ".$acummierc." ".$acumjuev." ".$acumvier." ".$acumsab." ".$acumdom;					
							if($maxlun == -9999999)$maxlun=0; if($minlun == 9999999)$minlun=0;
							if($maxmart == -9999999)$maxmart=0; if($minmart == 9999999)$minmart=0;
							if($maxmier == -9999999)$maxmier=0; if($minmier == 9999999)$minmier=0;
							if($maxjuev == -9999999)$maxjuev=0; if($minjuev == 9999999)$minjuev=0;
							if($maxvier == -9999999)$maxvier=0; if($minvier == 9999999)$minvier=0;
							if($maxsab == -9999999)$maxsab=0; if($minsab == 9999999)$minsab=0;
							if($maxdom == -9999999)$maxdom=0; if($mindom == 9999999)$mindom=0;
							//max min valores 2 facturas
							if($maxlun2 == -9999999)$maxlun2=0; if($minlun2 == 9999999)$minlun2=0;
							if($maxmart2 == -9999999)$maxmart2=0; if($minmart2 == 9999999)$minmart2=0;
							if($maxmier2 == -9999999)$maxmier2=0; if($minmier2 == 9999999)$minmier2=0;
							if($maxjuev2 == -9999999)$maxjuev2=0; if($minjuev2 == 9999999)$minjuev2=0;
							if($maxvier2 == -9999999)$maxvier2=0; if($minvier2 == 9999999)$minvier2=0;
							if($maxsab2 == -9999999)$maxsab2=0; if($minsab2 == 9999999)$minsab2=0;
							if($maxdom2 == -9999999)$maxdom2=0; if($mindom2 == 9999999)$mindom2=0;
							
							
							
						//echo $max2." menor ".$min2;





$pdf=new FPDF();
$pdf->AddPage();

class PDF extends FPDF
{


//Page footer
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
}
}

//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P','Letter');  
$pdf->SetFont('Times','',12);
/*for($i=1;$i<=40;$i++)
    $pdf->Cell(0,10,'Printing line number '.$i,0,1);*/
$pdf->Image('logoSISAP.jpg',12,258,9,9);
//$pdf->Image('vicencio.jpg',155,228,25,10);

/********** ZOFRI S.A. **************/
	$pdf->SetFont('Helvetica','',15); // tamaño fuente header
	$pdf->SetXY(9, 20);//posicion 
    $pdf->Cell(0,5,'ZOFRI S.A.',0,1);// Titulo
/********** Fin Zofri del header **************/
/********** Titulo S.A. **************/
	$pdf->SetFont('Helvetica','b',10); // tamaño fuente header
	$pdf->SetXY(90, 24);//posicion 
    $pdf->Cell(0,5,'Informe de Ventas',0,1);// Titulo
/********** Fin Zofri del header **************/
	
$caby = 42;
/********** solo Modulo numero **************/

    $pdf->SetFont('Helvetica','',9); // tamaño fuente header
	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,ucwords( getmodulo($modulo)),0,1);// modulo
	$pdf->SetXY(150, $caby);//posicion dia
    $pdf->Cell(0,5,'Rut: 96.526.630-5',0,1);// dia
	
/********** Fin fecha del header **************/

$caby = $caby+4;
/********** solo fecha del header **************/
	$fecha1 = "26/08/2013";
	$fecha2= "31/08/2013";

	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'Razon Social:   Importadora Eximben S.A.',0,1);// dia


/********** Fin fecha del header **************/


$caby = $caby+4;
/********** solo fecha del header **************/

	$pdf->SetXY(9, $caby);//posicion dia
    $pdf->Cell(0,5,'Semana del   '.$finicio,0,1);// dia
	// al
	$pdf->SetXY(45, $caby);//posicion mes
	$pdf->Cell(0,5,'  Al  '.$ffin,0,1);// mes

/********** Fin fecha del header **************/

//Fields Name position
$Y_Fields_Name_position = 60;
//Table position, under Fields Name
$Y_Table_Position = 66;

//First create each Field Name
//Gray color filling each Field Name box

$pdf->SetFillColor(232,232,232);

//Bold Font for Field Name
$pdf->SetFont('Arial','B',8);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(10);
$pdf->Cell(17,6,'Fecha',1,0,'C',1);
$pdf->SetX(27);
$pdf->Cell(50,6,'Boleta',1,0,'C',1);
$pdf->SetX(77);
$pdf->Cell(50,6,'Factura',1,0,'C',1);
$pdf->SetX(127);
$pdf->Cell(40,6,'SRF',1,0,'C',1);
$pdf->SetX(167);
$pdf->Cell(33,6,'Total',1,0,'C',1);
$pdf->Ln();
//Now primera fila
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,' ',1,'false','C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,'Total',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6,'Correlativo',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,'Total',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,'Correlativo',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'Total',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'Correlativo',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,'',1,'R');

// segunda fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Lunes ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($acumlun, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6,$minlun.' - '.$maxlun,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($acumlun2, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,$minlun2.' - '.$maxlun2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format($acumlunestotal, 0, '', '.'),1,'R');

// tercera fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Martes ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($acummart, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6, $minmart.' - '. $maxmart,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($acummart2, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,$minmart2.' - '.$maxmart2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format($acummartestotal, 0, '', '.'),1,'R');


// cuarta fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Miercoles ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($acummier, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6, $minmier.' - '. $maxmier,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($acummier2, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,$minmier2.' - '.$maxmier2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format($acummiertotal, 0, '', '.'),1,'R');


// quinta fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Jueves ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($acumjuev, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6, $minjuev.' - '. $maxjuev,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($acumjuev2, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,$minjuev2.' - '.$maxjuev2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format($acumjuevtotal, 0, '', '.'),1,'R');


// sexta fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Viernes ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($acumvier, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6, $minvier.' - '. $maxvier,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($acumvier2, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,$minvier2.' - '.$maxvier2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format($acumviertotal, 0, '', '.'),1,'R');

// septima fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Sabado ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($acumsab, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6, $minsab.' - '. $maxsab,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($acumsab2, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,$minsab2.' - '.$maxsab2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format($acumsabtotal, 0, '', '.'),1,'R');

// octava fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Domingo ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($acumdom, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6, $mindom.' - '. $maxdom,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($acumdom2, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,$mindom2.' - '.$maxdom2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format($acumdomtotal, 0, '', '.'),1,'R');

// Total venta fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Total Venta',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($TOTALBOLETA, 0, '', '.'),1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6,'',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($TOTALFACT, 0, '', '.'),1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,'',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format($TOTAL, 0, '', '.'),1,'R','false');

// menos impuesto fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Menos Imp ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($acumreten1-$acumreten3, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($acumreten2, 0, '', '.'),1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format(($acumreten2+$acumreten1)-$acumreten3, 0, '', '.'),1,'R');

// iva fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'I.V.A. 18% ',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,'',1,'R');



// total neto fila

$Y_Table_Position = $Y_Table_Position+6;
$pdf->SetFont('Arial','',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(17,6,'Total Neto',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(27);
$pdf->MultiCell(22,6,number_format($TOTALBOLETA-($acumreten1-$acumreten3), 0, '', '.'),1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(49);
$pdf->MultiCell(28,6,'',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(77);
$pdf->MultiCell(22,6,number_format($TOTALFACT-($acumreten2), 0, '', '.'),1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(99);
$pdf->MultiCell(28,6,'',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(127);
$pdf->MultiCell(20,6,'',1,'C','false');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(147);
$pdf->MultiCell(20,6,'',1,'C','false');
$pdf->SetFont('Arial','B',8);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(167);
$pdf->MultiCell(33,6,number_format(($TOTALBOLETA-($acumreten1-$acumreten3)+($TOTALFACT-($acumreten2))), 0, '', '.'),1,'R','false');

$firmay = 190;
/********** Titulo S.A. **************/
	$pdf->SetFont('Arial','',10); // tamaño fuente header
	$pdf->SetXY(37, 185);//posicion 
    $pdf->Cell(0,5,'________________',0,1);// Titulo
	$pdf->SetXY(40, $firmay);//posicion 
    $pdf->Cell(0,5,'Recepcion Zofri',0,1);// Titulo
	$pdf->SetXY(136.5, 185);//posicion 
    $pdf->Cell(0,5,'________________',0,1);// Titulo
	$pdf->SetXY(140, $firmay);//posicion 
    $pdf->Cell(0,5,'Jefe de Modulo',0,1);// Titulo
/********** Fin Zofri del header **************/

$firmay = 225;
/********** Titulo S.A. **************/
	$pdf->SetFont('Arial','',9); // tamaño fuente header
	$pdf->SetXY(12, $firmay);//posicion 
    $pdf->Cell(0,5,'Fecha:   __________________',0,1);// Titulo
	$pdf->SetXY(12, $firmay+6);//posicion 
    $pdf->Cell(0,5,'Hora:     __________________',0,1);// Titulo
/********** Fin Zofri del header **************/


$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','',8);
$pdf->SetY(225);
$pdf->SetX(100);
$pdf->MultiCell(100,4,' " El Usuario Persona Natural / Jefe de Modulo / Representante del Usuario Persona Natural o Juridica, que suscribe el presente documento,declara expresamente que la informacion proporcionada en este informe de ventas es fidedigna y que se responsansibiliza"',0,'R','false');


$pdf->Output();

?>