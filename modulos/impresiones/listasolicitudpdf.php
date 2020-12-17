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
$idsol =  $_GET['idsol'];

 function cambiarFecha($fecha) // funcion para cambiar el formato de la fecha y orden
 {
	 return implode("-", array_reverse(explode("-", $fecha)));
 }					
	$date = strtotime($finicio);
	$finicio2 = cambiarFecha($finicio);
	$ffin2 = cambiarFecha($ffin);
	
	
	
	 
$sql="SELECT  [solicitud_id]
      ,[estado]
      , CONVERT(varchar, [fecha_crea], 103)as Fecha
      ,[fecha_estado]
      ,[cantidad_total]
      ,[modulo]
      ,[vendedor_id]
      ,[recepcion_id]
  FROM [RP_VICENCIO].[dbo].[sisap_solicitudes]
             
 WHERE [solicitud_id]  =	".$idsol."	 ";
 
 	$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultado = odbc_fetch_array($rs)){ 
							  
							  	$fechacrea = $resultado["Fecha"];
								$modulo	 = $resultado["modulo"];
								$vendedor = $resultado["vendedor_id"];
								$recepcion = $resultado["recepcion_id"];
							  
							  }

	 

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
$pdf->Cell(0,10,' SOLICITUD DE PRODUCTOS '.strtoupper(getmodulo($modulo)).'                                                             '.$fechacrea,0,0,'L','true');
$pdf->SetFillColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetTextColor(0,0,0);
/********** Fin Zofri del header **************/
$pdf->SetFont('Helvetica','B',9);
$pdf->SetXY(9, 19);//posicion dia
    $pdf->Cell(0,5,utf8_decode('Número Solicitud: '.$idsol),0,1);// dia
	
$pdf->SetXY(65, 19);//posicion dia
$pdf->Cell(0,5,utf8_decode('Solicitante: '.getusuario($vendedor)),0,1);// dia


$pdf->SetXY(145, 19);//posicion dia
$pdf->Cell(0,5,utf8_decode('Válida: '.getusuario($recepcion)),0,1);// dia
	
$caby = 20;

//Fields Name position
$Y_Fields_Name_position = 30;
//Table position, under Fields Name
$Y_Table_Position =36;




/**************************************************************************************** SEGUNDA HOJA******************************************************/

$sql3="SELECT TOP 1000 [detalle_id]
      ,[codigo]
      ,[descripcion]
      ,[marca]
      ,[stock_modulo]
      ,[cant_solicitada]
      ,[cant_aceptada]
      ,[solicitud_id]
  FROM [RP_VICENCIO].[dbo].[sisap_soldetalle]
  
  WHERE [solicitud_id] = ".$idsol." ";

		
			
		//echo $sql;	
		$rs3 = odbc_exec( $conn, $sql3 );
		if ( !$rs3 )
		{
		exit( "Error en la consulta SQL" );
		}
						  
				$Y_Fields_Name_position =  27;

				$Y_Table_Position = 27;
				
				
				$pdf->SetFillColor(232,232,232);
				
				//Cabecera
				$pdf->SetFont('Arial','B',8);
				$pdf->SetY($Y_Fields_Name_position);
				$pdf->SetX(9);
				$pdf->Cell(30,6,utf8_decode('Código'),1,0,'C',1);
				$pdf->SetX(39);
				$pdf->Cell(150,6,utf8_decode('Descripción'),1,0,'C',1);
				$pdf->SetX(189);
				$pdf->Cell(17,6,'Cantidad',1,0,'C',1);
				
				$pdf->Ln();
				$saltador=0;
				while($row = odbc_fetch_array($rs3)){ 
					
					if($saltador==35){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}	
					if($saltador==75){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					if($saltador==110){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					if($saltador==145){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					if($saltador==195){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					if($saltador==215){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					if($saltador==250){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					if($saltador==285){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					if($saltador==320){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					if($saltador==355){
					$pdf->AddPage('P','Letter');
					$Y_Table_Position = 3;}
					
					
					$Y_Table_Position =$Y_Table_Position+ 6;
					$pdf->SetFont('Arial','',8);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(9);
					$pdf->Cell(30,6,$row["codigo"],1,0,'C',0);
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(39);
					
					$pdf->Cell(150,6,utf8_decode($row["descripcion"]),1,0,'L',0);
					
					$pdf->SetY($Y_Table_Position);
					$pdf->SetX(189);
					$pdf->Cell(17,6,$row["cant_aceptada"],1,0,'C',0);
				
					$pdf->Ln();
					$saltador++;
				 }//fin while de la tabla detalles cheques


$pdf->Output();

?>