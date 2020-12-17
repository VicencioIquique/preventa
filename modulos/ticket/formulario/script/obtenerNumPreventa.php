<?php
	require_once("../../../clases/conexionocdb.php");
	$sql="SELECT Nombres FROM dbo.RP_Clientes WHERE RUT = '".$datos['rut']."'";
?>