<?php 
require_once("../../clases/conexionocdb.php");

$sku = $_POST['sku'];
$id =$_POST['idsol'];




$sql="DELETE FROM  dbo.sisap_soldetalle WHERE codigo = '".$sku."' AND solicitud_id = ".$id." ";

$rs = odbc_exec( $conn, $sql );
							
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

odbc_execute($rs);
odbc_close( $conn );

?>