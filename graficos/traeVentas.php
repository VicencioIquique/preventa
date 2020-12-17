<?php
require_once("../clases/conexionocdb.php");//incluimos archivo de conexión

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

$finicio = $_GET['inicio'];
$ffin = $_GET['fin'];

if(!$finicio)
{
	 $finicio = date("m/d/Y");
	 $ffin= date("m/d/Y");
}

function cambiarFecha($fecha) 
{
  return implode("-", array_reverse(explode("-", $fecha)));
}
$finicio2 = cambiarFecha($finicio);
$ffin2 = cambiarFecha($ffin);
if ($modulo)// si selecciono modulo, se genera la consulta
{
	$conModulo = "  AND (dbo.RP_ReceiptsDet_SAP.Bodega LIKE '".$modulo."') ";
	$conModuloGroup = " , dbo.RP_ReceiptsDet_SAP.Bodega ";
}

					
					/*$sql= "SELECT     SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS SumaMarca, SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca   ,SUM(dbo.RP_ReceiptsDet_SAP.Cantidad) AS SumCantidad, dbo.View_OMAR.Name

FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID LEFT OUTER JOIN
                      SBO_Import_Eximben_SAC.dbo.OITM ON 
                      dbo.RP_ReceiptsDet_SAP.Sku COLLATE SQL_Latin1_General_CP850_CI_AS = SBO_Import_Eximben_SAC.dbo.OITM.ItemCode  LEFT OUTER JOIN
                      dbo.View_OMAR ON SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca = dbo.View_OMAR.Code

WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000')  AND  (dbo.RP_ReceiptsDet_SAP.TipoDocto <> 3)
			".$conModulo." 
GROUP BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca, dbo.View_OMAR.Name".$conModuloGroup." 
ORDER BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca";*/

$sql ="SELECT     TOP (100) PERCENT SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS SumaMarca, SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca, 
                      SUM(dbo.RP_ReceiptsDet_SAP.Cantidad) AS SumCantidad, dbo.View_OMAR.Name
FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID LEFT OUTER JOIN
                      SBO_Import_Eximben_SAC.dbo.OITM ON 
                      dbo.RP_ReceiptsDet_SAP.Sku COLLATE SQL_Latin1_General_CP850_CI_AS = SBO_Import_Eximben_SAC.dbo.OITM.ItemCode LEFT OUTER JOIN
                      dbo.View_OMAR ON SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca = dbo.View_OMAR.Code
WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '12/22/2013 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '12/22/2013 23:59:59.000') AND 
                      (dbo.RP_ReceiptsDet_SAP.TipoDocto <> 3)
GROUP BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca, dbo.View_OMAR.Name
ORDER BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca";

//echo $sql;
					
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs )
	{
		exit( "Error en la consulta SQL" );
	}
	
		  while($resultados = odbc_fetch_array($rs)) 
						  { 
							$vector[] =   array ( 
												"country" => utf8_encode($resultados['Name']),
												"visits" => (int)$resultados['SumaMarca'],
												"color" => "#0489B1"
												
												);
						
						}
						  
				
						echo json_encode($vector);
					  
						
?>