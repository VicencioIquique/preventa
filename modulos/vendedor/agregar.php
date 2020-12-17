<?php 
require_once("../../clases/conexion.php");

$id = $_POST['ide_usu'];
$nombre = $_POST['nom_usu'];

$db = new MySQL();
$insert = $db->insert("INSERT INTO pais (pais_id, nombre) VALUES( $id,  lower('$nombre')  )") or die (mysql_error());


?>