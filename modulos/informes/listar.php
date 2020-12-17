<script type='text/javascript' src='js/jquery.autocomplete.pack.js'></script>
<script type="text/javascript">
$().ready(function() {
	
	$("#descripcion").autocomplete("modulos/productos/mysql_editar_nombre.php", {
		width: 550,
		selectFirst: true
	});
	
	
});
</script>
	
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<?php
require_once("clases/conexion.php");




if (isset ($_GET['listar'])) {
	
	
	echo'  <script src="graficos/amcharts/amcharts.js" type="text/javascript"></script>   ';
	include("graficos/stockcritico.php");	
	echo'  <div id="stockcritico" style="width: 100%; height: 180px;"></div>';
	exit();
}
?>

<form id="horizontalForm" action="index.php" method="GET">
<input type="hidden" name="opc" value="stockcritico" />
  <fieldset>
    <legend>Buscar Producto
	  </legend>
	  <label for="descripcion">
		  Nombre:		    
            <input name="descripcion" type="text" class="text" id="descripcion" />
    </label>

    	  <input class="submit" type="submit" value="Buscar" name="listar"/>
	</fieldset>
</form>

<table id="erptabla">
			<thead>
			<tr>
             <th >Opciones</th>
            <th >Presentación</th>
            <th >Nombre</th>            
			</tr>
			</thead>

<?php
$_pagi_cuantos = 10;

$db = new MySQL(); 

$_pagi_sql="SELECT productos.productos_id, productos_descripcion.productos_nombre, productos.productos_codigo, productos_atributos.productos_atributos_nombre, atributos_valores.productos_atributos_valores_nombre, productos.productos_stock, productos.producto_stock_ideal, productos.producto_stock_critico, precio_compra, precio_venta, precio_distribuidor, precio_oferta, precio_descuento FROM productos 
INNER JOIN productos_descripcion ON productos_descripcion.productos_productos_id = productos.productos_id 
INNER JOIN productos_atributos ON productos_atributos.productos_atributos_id = productos.productos_atributos_id 
INNER JOIN atributos_valores ON atributos_valores.productos_atributos_valores_id = productos.productos_atributos_valores_id
INNER JOIN lista_precio ON lista_precio.productos_productos_id = productos.productos_id ";
include("clases/paginacion.php");

if($db->num_rows($_pagi_result)>0){
  while($resultados = $db->fetch_array($_pagi_result)){ 
   echo '<tr>
   		<td align="center"><a href="index.php?opc=stockcritico&id='.$resultados["productos_id"].'"><img src="images/bar.png" width="16px" height="16px" > </a></td>
   		<td align="center">'.$resultados["productos_atributos_valores_nombre"].' '.$resultados["productos_atributos_nombre"].'</td>  
		<td>'.$resultados["productos_nombre"].'</td>
		   		
		
		</tr>'; 
	}
}
echo '</table>';

?>
<div id="paginacion">
	<?php echo "$_pagi_navegacion";?>
</div><!-- fin  paginacion -->