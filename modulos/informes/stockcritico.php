<?php 
echo'  <script src="graficos/amcharts/amcharts.js" type="text/javascript"></script>   ';



include("graficos/stockcritico.php");
include("graficos/costoinversion.php");
include("graficos/historialStock.php");

$db = new MySQL();
$consulta = $db->consulta("SELECT productos.productos_id, productos_descripcion.productos_nombre, productos.productos_codigo, productos_atributos.productos_atributos_nombre, atributos_valores.productos_atributos_valores_nombre, productos.productos_stock, productos.producto_stock_ideal, productos.producto_stock_critico, precio_compra, precio_venta, precio_distribuidor, precio_oferta, precio_descuento FROM productos 
INNER JOIN productos_descripcion ON productos_descripcion.productos_productos_id = productos.productos_id 

INNER JOIN productos_atributos ON productos_atributos.productos_atributos_id = productos.productos_atributos_id 
INNER JOIN atributos_valores ON atributos_valores.productos_atributos_valores_id = productos.productos_atributos_valores_id
INNER JOIN lista_precio ON lista_precio.productos_productos_id = productos.productos_id WHERE productos.productos_id LIKE '$idproducto' ");
if($db->num_rows($registro)>0){
		$resultado = $db->fetch_array($consulta);      
	}


?>


<form id="horizontalForm">
			
			<fieldset>
				<legend>Indicador de Stock</legend>
                <div id="stockcritico" style="width:100%; height:180px; margin-bottom:15px; "></div>
         		 	
                 <fieldset style=" float:left; width:43%; height:207px;">
                 <legend>Producto</legend>
					<div id="costoinversion" style="width:100%; height:200px;"></div>
				</fieldset>
               <fieldset style=" float:right; width:50%; height:207px;">
                 <legend>Historial Recarga Stock</legend>
					 <div id="historialStock" style="width:100%; height:200px;"></div>
				</fieldset> 
           		
		
			</fieldset>
           
		</form>
        


 
