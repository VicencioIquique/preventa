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
$modulo = $_GET['modulo'];
$finicio = $_GET['inicio'];
$ffin =  $_GET['fin'];

if(!$finicio)
{
	 $finicio = date("m/d/Y");
	 $ffin= date("m/d/Y");
}


 function cambiarFecha($fecha) // funcion para cambiar el formato de la fecha y orden
 {
	 return implode("-", array_reverse(explode("-", $fecha)));
 }					
	$date = strtotime($finicio);
	$finicio2 = cambiarFecha($finicio);
	$ffin2 = cambiarFecha($ffin);
	 
    $total=$total1=$total2=$total3=$total4=$total5=$total6=$total7 =0;// acumulador
	$cantotal=$cantotal1=$cantotal2=$cantotal3=$cantotal4=$cantotal5=$cantotal6=$cantotal7 =0;//contador
					
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

ORDER BY dbo.RP_ReceiptsCab_SAP.Bodega,dbo.RP_ReceiptsPagos_SAP.FechaDoc, dbo.RP_ReceiptsPagos_SAP.TipoDocto, dbo.RP_ReceiptsPagos_SAP.TipoPago DESC" ;
	
	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							 while($resultado = odbc_fetch_array($rs)){ 
							 
								//	echo (int)$resultado["Bodega"];
									if((int)$resultado["Bodega"] == 1)
									{
										$total1 = $total1 + $resultado["TOTAL"];
										$cantotal1++;
										continue;
									}
									else if((int)$resultado["Bodega"] == 2)
									{
										$total2 = $total2 + $resultado["TOTAL"];
										$cantotal2++;
										continue;
									}
									else if((int)$resultado["Bodega"] == 3)
									{
										$total3 = $total3 + $resultado["TOTAL"];
										$cantotal3++;
										continue;
									}
									else if((int)$resultado["Bodega"] == 4)
									{
										$total4 = $total4 + $resultado["TOTAL"];
										$cantotal4++;
										continue;
									}
									else if((int)$resultado["Bodega"] == 5)
									{
										$total5 = $total5 + $resultado["TOTAL"];
										$cantotal5++;
										continue;
									}
									else if((int)$resultado["Bodega"] == 6)
									{
										$total6 = $total6 + $resultado["TOTAL"];
										$cantotal6++;
										continue;
									}
									else if((int)$resultado["Bodega"] == 7)
									{
										$total7 = $total7 + $resultado["TOTAL"];
										$cantotal7++;
										continue;
									}
									
								}//fin  while	
								$total = $total1+$total2+$total3+$total4+$total5+$total6+$total7;
								$cantotal=$cantotal1+$cantotal2+$cantotal3+$cantotal4+$cantotal5+$cantotal6+$cantotal7;
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
$pdf->Cell(0,10,' EXIMBEN SAC'.strtoupper(getmodulo($modulo)).'                                                                                                                     '.date("d").'/'.date("m").'/'.date("Y"),0,0,'L','true');
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
$pdf->Cell(0,1,'',0,0,'L','true');
$pdf->SetFillColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
/********** Fin Zofri del header **************/
$pdf->SetFont('Helvetica','B',12); // tamaño fuente header
$caby = $caby+7;
/********** solo fecha del header **************/
	$fecha1 = "26/08/2013";
	$fecha2= "31/08/2013";

	$pdf->SetXY(78, $caby);//posicion dia
    $pdf->Cell(0,5,'Informe de Cheques al Día',0,1);// dia
	$pdf->SetXY(75, 27);//posicion 
    $pdf->Cell(0,5,'_________________________',0,1);// Titulo


/********** Fin fecha del header **************/

$pdf->SetFont('Helvetica','',9);

	$caby = $caby+30.5;
	$pdf->SetXY(9, $caby);
    $pdf->Cell(0,5,'Importadora Eximben S.A.',0,1);
	
	$caby = $caby+5;
	$pdf->SetXY(9, $caby);
    $pdf->Cell(0,5,'RUT: 96.526.630-5',0,1);
	
	/*$caby = $caby;
	$pdf->SetXY(170, $caby);
    $pdf->Cell(0,5,'Módulo: 1010',0,1);*/


$caby = $caby+10;

/********** ZOFRI S.A. **************/
$pdf->SetFillColor(238,238,238);
$pdf->SetDrawColor(0,0,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(9, $caby);//posicion 
$pdf->SetFont('Helvetica','B',9); // tamaño fuente header
$pdf->Cell(0,5,' Totales por Módulo',0,0,'L','true');
$pdf->SetFillColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
/********** Fin Zofri del header **************/
$caby = $caby+10;
$pdf->SetFillColor(232,232,232);
				$pdf->SetFont('Arial','B',8);
				$pdf->SetY($caby);
				$pdf->SetX(30);
				$pdf->Cell(90,6,'Retail',1,0,'C',1);
				$pdf->SetX(120);
				$pdf->Cell(30,6,'Cantidad Cheques',1,0,'C',1);
				$pdf->SetX(150);
				$pdf->Cell(32,6,'Total',1,0,'C',1);
				$pdf->Ln();
				
				$caby =$caby+ 6; //Fila1
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(30);
					$pdf->Cell(90,6,'Módulo 1010',1,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal1,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total1, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
				$caby =$caby+ 6; //Fila2
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(30);
					$pdf->Cell(90,6,'Módulo 1132',1,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal2,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total2, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
				$caby =$caby+ 6; //Fila3
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(30);
					$pdf->Cell(90,6,'Módulo 181',1,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal3,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total3, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
				$caby =$caby+ 6; //Fila4
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(30);
					$pdf->Cell(90,6,'Módulo 184',1,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal4,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total5, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
				$caby =$caby+ 6; //Fila5
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(30);
					$pdf->Cell(90,6,'Módulo 2002',1,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal5,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total5, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
				$caby =$caby+ 6; //Fila6
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(30);
					$pdf->Cell(90,6,'Módulo 6115',1,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal6,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total6, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
				$caby =$caby+ 6; //Fila7
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(30);
					$pdf->Cell(90,6,'Módulo 6130',1,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal7,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total7, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
				$caby =$caby+ 6; //Fila7
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(100);
					$pdf->Cell(20,6,'Total:',0,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
				/*$caby =$caby+ 6; //Fila8
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($caby);
					$pdf->SetX(30);
					$pdf->Cell(90,6,'Módulo 1010',1,0,'C',0);
					$pdf->SetX(120);
					$pdf->Cell(30,6,$cantotal1,1,0,'C',0);
			     	$pdf->SetX(150);
					$pdf->Cell(32,6,number_format($total1, 0, '', '.'),1,0,'C',0);
					$pdf->Ln();*/

$caby = $caby+10;

$firmay = 190;
/********** Titulo S.A. **************/
	$pdf->SetFont('Arial','',10); // tamaño fuente header
	
	$pdf->SetXY(85.5, 185);//posicion 
    $pdf->Cell(0,5,'________________',0,1);// Titulo
	$pdf->SetXY(86, $firmay);//posicion 
    $pdf->Cell(0,5,'Firma Responsable',0,1);// Titulo
/********** Fin Zofri del header **************/


$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','',8);
$pdf->SetY(225);
$pdf->SetX(100);
$pdf->MultiCell(100,4,' " El Usuario Persona Natural / Representante del Usuario Persona Natural o Juridica, que suscribe el presente documento,declara expresamente que la informacion proporcionada en este informe de Cheques a Fecha es fidedigna."',0,'R','false');



	//Cabecera
		
/**************************************************************************************** SEGUNDA HOJA******************************************************/



$total =0;
$cantotal =0;
					
$pdf->AddPage('P','Letter');  
					
			
		//echo $sql;	
		$rs3 = odbc_exec( $conn, $sql);
		if ( !$rs3 )
		{
		exit( "Error en la consulta SQL" );
		}
		
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
			

				$Y_Fields_Name_position =  27;

				$Y_Table_Position = 27;
				
				
				$pdf->SetFillColor(232,232,232);
				
				//Cabecera
				$pdf->SetFont('Arial','B',8);
				$pdf->SetY($Y_Fields_Name_position);
				$pdf->SetX(9);
				$pdf->Cell(30,6,'Retail',1,0,'C',1);
				$pdf->SetX(39);
				$pdf->Cell(30,6,'Fecha Compra',1,0,'C',1);
				$pdf->SetX(69);
				$pdf->Cell(30,6,'Fecha Pago',1,0,'C',1);
				$pdf->SetX(99);
				$pdf->Cell(30,6,'Días',1,0,'C',1);
				$pdf->SetX(129);
				$pdf->Cell(25,6,'Número',1,0,'C',1);
				$pdf->SetX(154);
				$pdf->Cell(20,6,'Quedan',1,0,'C',1);
				$pdf->SetX(174);
				$pdf->Cell(32,6,'Monto',1,0,'C',1);
				$pdf->Ln();
				
				$saltador =0;
				while($row = odbc_fetch_array($rs3)){ 
					
					if($saltador==35){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}	
					if($saltador==70){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==105){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==140){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==175){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==210){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==245){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==280){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==315){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==350){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==385){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==420){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==455){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==490){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==525){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==560){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==595){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==630){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==665){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==700){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==735){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==770){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==805){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==840){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==875){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==910){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==945){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==980){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1015){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1050){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1085){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1120){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1155){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1190){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1225){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1260){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1295){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
					if($saltador==1330){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 15;}
				
				
					
				
					
					
					$Y_Table_Position =$Y_Table_Position+ 6;
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(9);
					$pdf->Cell(30,6,getModulo((int)$row["Bodega"]),1,0,'L',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(39);
					$pdf->Cell(30,6,$row["fcompra"],1,0,'C',0);
					
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(69);
					$pdf->Cell(30,6,$row["fpago"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(99);
					$pdf->Cell(30,6,$row["Dias"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(129);
					$pdf->Cell(25,6,$row["NumeroDoc"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(154);
					$pdf->Cell(20,6,$row["QUEDAN"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(174);
					$pdf->Cell(32,6,number_format($row["TOTAL"], 0, '', '.'),1,0,'C',0);
					$pdf->Ln();
					$saltador++;
				 }//fin while de la tabla detalles cheques



$pdf->Output();

?>