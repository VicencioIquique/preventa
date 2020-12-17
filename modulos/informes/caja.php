<?php 
echo'  <script src="graficos/amcharts/amcharts.js" type="text/javascript"></script>   ';




include("graficos/ventanual.php");
include("graficos/ventadia.php");
include("graficos/ventamensual.php");
include("graficos/stockcritico.php");

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
<script type="text/javascript" src="js/prototype.lite.js"></script>
<script type="text/javascript" src="js/moo.fx.js"></script>
<script type="text/javascript" src="js/moo.fx.pack.js"></script>
<script type="text/javascript">
function init(){
	var stretchers = document.getElementsByClassName('box');
	var toggles = document.getElementsByClassName('tab');
	var myAccordion = new fx.Accordion(
		toggles, stretchers, {opacity: false, height: true, duration: 600}
	);
	//hash functions
	var found = false;
	toggles.each(function(h3, i){
		var div = Element.find(h3, 'nextSibling');
			if (window.location.href.indexOf(h3.title) > 0) {
				myAccordion.showThisHideOpen(div);
				found = true;
			}
		});
		if (!found) myAccordion.showThisHideOpen(stretchers[0]);
}
</script>

<form id="horizontalForm">
			
			<fieldset>
				<legend>Indicador de Stock</legend>
              	<div id="wrapper" style="  height:420px;">
                            <div id="content">
                            <h3 class="tab" title="third"><div class="tabtxt"><a href="#">Hoy</a></div></h3>
                            <div class="tab"><h3 class="tabtxt" title="second"><a href="#">Mes</a></h3></div>
                              <h3 class="tab" title="first"><div class="tabtxt"><a href="#">A&ntilde;o</a></div></h3>
                           
                            <div class="boxholder">
                            	<div class="box">
                                 	<div id="ventadia" style="width:100%; height:400px;"></div>
                                </div>
                                <div class="box">
 	                                  <div id="ventamensual" style="width:100%; height:400px;"></div>
                                </div>
                                <div class="box">
                                 	<div id="ventanual" style="width:100%; height:400px;"></div>
                                </div>
                               
							</div>
						</div>
				</div>
         		 	
             
<script type="text/javascript">
	Element.cleanWhitespace('content');
	init();
</script>
   				
                
		
			</fieldset>
           
		</form>
        


 
