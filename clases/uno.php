<?php 
$dsn = "prueba"; 
//debe ser de sistema no de usuario
$usuario = "sa";
$clave="U4xyyBLk56";

//realizamos la conexion mediante odbc
$cid=odbc_connect($dsn, $usuario, $clave);

if (!$cid){
	exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}	
$id="11002177275417620130701";
echo $id;
// consulta SQL a nuestra tabla "usuarios" que se encuentra en la base de datos "db.mdb"
$sql="SELECT     TOP (100) PERCENT SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS SumaMarca, SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca, 
                      dbo.RP_ReceiptsDet_SAP.Vendedor
FROM         dbo.RP_ReceiptsDet_SAP LEFT OUTER JOIN
                      SBO_Import_Eximben_SAC.dbo.OITM ON 
                      dbo.RP_ReceiptsDet_SAP.Sku COLLATE SQL_Latin1_General_CP850_CI_AS = SBO_Import_Eximben_SAC.dbo.OITM.ItemCode
WHERE     (dbo.RP_ReceiptsDet_SAP.ID LIKE ".$id." )
GROUP BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca, dbo.RP_ReceiptsDet_SAP.Vendedor
ORDER BY SBO_Import_Eximben_SAC.dbo.OITM.U_VK_Marca";

// generamos la tabla mediante odbc_result_all(); utilizando borde 1
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
print odbc_result_all($result,"border=1");

?>