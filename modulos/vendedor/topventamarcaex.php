<?php 
require_once("../../clases/conexionocdb.php");




$modulo = $_GET['modulo'];
$finicio2 = $_GET['inicio'];
$ffin2 = $_GET['fin'];

if ($modulo) // pregunta si se consulta por modulo sino es asi, crea una consulta general
					{
						$conModulo = "  AND (dbo.RP_ReceiptsDet_SAP.Bodega LIKE '".$modulo."') ";
						$conModuloGroup = " , dbo.RP_ReceiptsDet_SAP.Bodega ";
					}


 
			
					$total =0;
					$cantotal =0;

	$sql= "SELECT     TOP (100) PERCENT SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS SumaMarca, SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca   ,SUM(dbo.RP_ReceiptsDet_SAP.Cantidad) AS SumCantidad

FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID LEFT OUTER JOIN
                      SBO_Import_Eximben_SAC.dbo.OITM ON 
                      dbo.RP_ReceiptsDet_SAP.Sku COLLATE SQL_Latin1_General_CP850_CI_AS = SBO_Import_Eximben_SAC.dbo.OITM.ItemCode
WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000')
			".$conModulo." 
GROUP BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca".$conModuloGroup." 
ORDER BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca";							
										
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}
							
							if($modulo)
							{
								$shtml = $shtml."MODULO: ".$modulo.";\n";
							}
									
							   $shtml = $shtml."LINEA ;".
							  				   " CANTIDAD; ".
											      "TOTAL \n";

							  while($reg = odbc_fetch_array($rs)){ 
							  
							  $shtml = $shtml.utf8_encode($reg["U_VK_Marca"]).";".
							  				   number_format($reg["SumCantidad"], 0, '', '.')."; ".
											  number_format($reg["SumaMarca"], 0, '', '.')."\n";
						
							  }
							  
							  //aqui le decimos al navegador que vamos a mandar un archivo del tipo CSV
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=TopVentaMarcas.csv");
print $shtml;
exit;

odbc_close( $conn );

?>