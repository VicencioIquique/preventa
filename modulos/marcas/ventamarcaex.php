<?php 
require_once("../../clases/conexionocdb.php");

$vendedor = $_GET['id'];
$marca = $_GET['marca']; // Pregunta si realmente busco por marca -> crea la consulta WHERE
if ($marca)
{
	$Wmarca = " AND (dbo.oITM_From_SBO.U_VK_Marca LIKE '".$marca."') ";
}


$vendedor = str_pad($vendedor, 3, '0', 'STR_PAD_LEFT');
$modulo = $_GET['modulo'];
$finicio2 = $_GET['inicio'];
$ffin2 = $_GET['fin'];

 
			
					$total =0;
					$cantotal =0;

	$sql= "SELECT     TOP (100) PERCENT dbo.RP_ReceiptsDet_SAP.Vendedor, dbo.RP_ReceiptsDet_SAP.PrecioExtendido, dbo.RP_ReceiptsDet_SAP.NumeroDocto, 
                      dbo.RP_ReceiptsDet_SAP.Sku, dbo.RP_ReceiptsDet_SAP.Bodega, CONVERT(varchar, dbo.RP_ReceiptsCab_SAP.FechaDocto, 103) AS Fecha, 
                      dbo.RP_ReceiptsDet_SAP.ID, dbo.RP_ReceiptsDet_SAP.Cantidad, dbo.OSLP.SlpName, dbo.oITM_From_SBO.ItemName, dbo.oITM_From_SBO.ItemName, dbo.oITM_From_SBO.U_VK_Marca

FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID INNER JOIN
                      dbo.OSLP ON dbo.OSLP.SlpCode = dbo.RP_ReceiptsDet_SAP.Vendedor INNER JOIN
                      dbo.oITM_From_SBO ON dbo.oITM_From_SBO.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS = dbo.RP_ReceiptsDet_SAP.Sku
WHERE     (dbo.RP_ReceiptsDet_SAP.Bodega = '".$modulo."') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000') AND 
                      (dbo.RP_ReceiptsDet_SAP.Vendedor LIKE '".$vendedor."') ".$Wmarca;							
										
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($reg = odbc_fetch_array($rs)){ 
							  
							  $shtml = $shtml.$reg["SlpName"].";".
											  $reg["NumeroDocto"].";".
											  $reg["Fecha"]."; ".
											  number_format($reg["Cantidad"], 0, '', '.').";Sku: ".
											  $reg["Sku"].";".
											  $reg["U_VK_Marca"].";".
											  $reg["ItemName"].";".
											  number_format($reg["PrecioExtendido"], 0, '', '.')."\n";
						
							  }
							  
							  //aqui le decimos al navegador que vamos a mandar un archivo del tipo CSV
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=Ventas.csv");
print $shtml;
exit;



?>