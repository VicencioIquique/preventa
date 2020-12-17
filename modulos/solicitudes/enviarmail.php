<?php 
ini_set("SMTP", "mail.eximben.cl"); 
require_once("../../clases/conexionocdb.php"); // conexion con obcd driver
require_once("../../clases/funciones.php"); // funciones propias


$idsol = (int)$_REQUEST['idsol'];

$correos = $_REQUEST['correos'];

$lista = substr($correos, 0, -1);


$sql="SELECT  [solicitud_id]
      ,[estado]
      , CONVERT(varchar, [fecha_crea], 103)as Fecha
      ,[fecha_estado]
      ,[cantidad_total]
      ,[modulo]
      ,[vendedor_id]
      ,[recepcion_id]
  FROM [RP_VICENCIO].[dbo].[sisap_solicitudes]
             
 WHERE [solicitud_id]  = ".$idsol;
 
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

$destinatario = $lista; 
$asunto = "Nueva Solicitud"; 
$cuerpo = ' 
<html> 
<head> 
   <title></title> 
</head> 
<body> 
<h1>HOLA!</h1> 
<p> 
<b>Buen día!, le ha llegado una nueva solicitud</b>. Para revisar haga click en el siguiente enlace: <a href="g6.eximben.cl/SISAP/index.php?opc=nuevaSolicitudbrand" >CLICK AQUI</a>
</p> 
<p> 
<b>Solicitud numero:</b> '.$idsol.'
</p> 
<p> 
<b>Solicitada por:</b> '.getusuario($vendedor).'
</p> 
<p> 
<b>Modulo:</b> '.getmodulo($modulo).'
</p> 
<p> 
<b>Fecha:</b> '.$fechacrea.'
</p> 
</body> 
</html> 
'; 

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//dirección del remitente 
$headers .= "From: brandmanager@eximben.cl\r\n"; 

//dirección de respuesta, si queremos que sea distinta que la del remitente 
$headers .= "Reply-To:  brandmanager@eximben.cl\r\n"; 


//direcciones que recibián copia 
$headers .= "Cc: brandmanager@eximben.cl,soporte@eximben.cl\r\n"; 

//direcciones que recibirán copia oculta 
//$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 

mail($destinatario,$asunto,$cuerpo,$headers) 
?>