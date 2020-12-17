<?php

$q = strtolower( $_GET["q"] );
if (!$q) return;

$dbhost = "localhost";		// Database Host
$dbuser = "info_erp";			// User
$dbpass = "rejuchris";			// Password
$dbname = "info_erp";			// Name of Database

mysql_connect( $dbhost, $dbuser, $dbpass ) or die( mysql_error() );
mysql_select_db( $dbname ) or die( mysql_error() );

// Replace "TABLE_NAME" below with the table you'd like to extract data from
$data = mysql_query( "SELECT productos_descripcion.productos_nombre, productos_descripcion.productos_productos_id, atributos_valores.productos_atributos_valores_nombre, productos_atributos.productos_atributos_nombre, productos.productos_atributos_id, productos.productos_atributos_valores_id FROM productos_descripcion
INNER JOIN productos ON productos.productos_id 	= productos_descripcion.productos_productos_id
INNER JOIN productos_atributos ON productos_atributos.productos_atributos_id = productos.productos_atributos_id
INNER JOIN atributos_valores ON atributos_valores.productos_atributos_valores_id = productos.productos_atributos_valores_id" )
or die( mysql_error() );

// Replace "COLUMN_ONE" below with the column you'd like to search through
// In between the if/then statement, you may present a string of text
// you'd like to appear in the textbox.
while( $row = mysql_fetch_array( $data )){
	if ( strpos( strtolower( $row['productos_nombre'] ), $q ) !== false) {
		echo "Cod. " . $row['productos_productos_id'] . " ";
		echo $row['productos_nombre'];
		echo "<b> (" . $row['productos_atributos_valores_nombre'] ." ". $row['productos_atributos_nombre']. ")</b>\n";
	}
}
?>