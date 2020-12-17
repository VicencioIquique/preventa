<?php 
require_once("clases/conexionocdb.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

 $idsol = $_GET['idsol'];
 $sql="SELECT   estado
 	   FROM     dbo.sisap_solicitudes WHERE solicitud_id LIKE '".$idsol."'";

$rs = odbc_exec( $conn, $sql );
							
	if ( !$rs )
	{
	   exit( "Error en la consulta SQL" );
	}
    while($resultado = odbc_fetch_array($rs))
	{ 
		 $estado=$resultado["estado"];
	}	

echo $estado;
?>
 <script src='js/jquery.min.js'></script>
 <script src="js/addboton2.js"></script>
 <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css" /> 
   <link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-ui.js"></script>
 
<script type="text/javascript" language="javascript" src="js/jquery.jeditable.js"></script>
 <script type="text/javascript">
 
 
 
$(document).ready(function() {
	     $('.text').editable('modulos/solicitudes/save.php');
	fn_eliminar_articulolista();	//eliminar de lista
	


	$('#articulo').focus(); // darle focus al buscar producto
    var re;
    var valor = 0
   
   $('form').children().find('.preciototalu').each(function(){ // sumar todos los precios
        re = $(this).val();
		
        valor += parseFloat(re)
    });
	
    $('#TOTALFINAL').val(valor.toFixed(0));
});

 
$(function() {
	
	//autocomplete
	$(".auto").autocomplete({
		source: "modulos/solicitudes/barticulos.php",
		minLength: 1,
		
	});				

});

</script>
<input name="idsol" type="hidden" id="idsol" size="40" class="required" value="<?php echo $_GET['idsol'];?>" />
<div class="paso_selected" style="margin-bottom:10px;" >1 Paso</div><div style="margin-bottom:10px;" class="paso" >2 Paso</div><div style="margin-bottom:10px;" class="paso" >3 Paso</div>
    <form id="horizontalForm">
     <table  id="ssptable" class="lista">
              <thead>
                    <tr>
                        <th>código</th>
                        <th>descripción</th>
                        <th>stock tienda</th>
                        <th>cant. solicitada</th>
                        <th>cant. aceptada</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody id="tablebody">
                   <?php
                 
				 	$sql="SELECT     dbo.sisap_solicitudes.solicitud_id, dbo.sisap_soldetalle.solicitud_id AS detid, dbo.sisap_soldetalle.codigo, dbo.sisap_soldetalle.descripcion, 
                      dbo.sisap_soldetalle.marca, dbo.sisap_soldetalle.stock_modulo, dbo.sisap_soldetalle.cant_solicitada, dbo.sisap_soldetalle.cant_aceptada, 
                      dbo.sisap_soldetalle.detalle_id
FROM         dbo.sisap_solicitudes LEFT OUTER JOIN
                      dbo.sisap_soldetalle ON dbo.sisap_solicitudes.solicitud_id = dbo.sisap_soldetalle.solicitud_id
WHERE     (dbo.sisap_solicitudes.solicitud_id = ".$idsol.")
";

					
					$total =0;
					$cantotal =0;

							//echo $sql;	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}

							  while($resultados = odbc_fetch_array($rs)){ 
							 echo '<tr >
										<td ><stron>'.$resultados["codigo"].'</strong></td>
										<td >'.$resultados["descripcion"].'</td>
										<td >'.$resultados["stock_modulo"].'</td>
										<td >'.$resultados["cant_solicitada"].'</td>
										<td class="text" id="'.$idsol.'-'.$resultados["codigo"].'" > '.$resultados["cant_aceptada"].'</td>
										
									</tr>';  //<td><a class="elimina_lista" id="'.$resultados["codigo"].'"><img src="images/delete.png" /></a></td>
																
						
							 echo '</tr>';
								}
							

				             ?>
                
                   
                
             
                </tbody>

            </table>
            <div id="" style="float:right; font-size:30px; margin-right:20px; ">
            	   
				<!--Total: <input style=" font-size:30px; width:200px;" name="TOTALFINAL" type="text" readOnly="true" value="0" id="TOTALFINAL" class="totalfinal" />
                <input type="button" value="Calcular"  class="submit" style=" margin-left:-180px; float:left;" onclick="javascript: window.location.reload();" />-->
                <?php 
				if($estado ==0)
				{
				echo ' <input type="button" value="Enviar"  class="submit" style=" float:left; margin-top:0px;" onclick="javascript: fn_finalizar_guia();" />';	
				}
				if($estado ==2)
				{
				echo ' <input type="button" value="Validar"  class="submit" style=" float:left; margin-top:0px;" onclick="javascript: fn_validar_sol();" />';	
				}
				?>
    			</div><!-- fin   -->
            </form>
  	<?php odbc_close( $conn );?>           
 <script type='text/javascript'>
        // Cada vez que se pulse una tecla, controlamos que sea numerica
        $("#cant").keypress(function(event) {
            //obtenemos la tecla pulsada
            var valueKey=String.fromCharCode(event.which);
            //obtenemos el valor ascii de la tecla pulsada
            var keycode=event.which;
            
            // Si NO pulsamos un numero, un punto, la tecla suprimir
            // la tecla backspace o el simobolo "-" (45), cancelamos la pulsacion
            if(valueKey.search('[0-9|\.]')!=0 && keycode!=8 && keycode!=46 && keycode!=45)
            {
                // anulamos la pulsacion de la tecla
                return false;
            }
        });
        
        // evento que se ejecuta cada vez que se suelte la tecla en cualquiera de
        // los tres inputs
        $("#cant").keyup(function(event) {
            calcular();
        });
        
        // Calculamos la suma de los dos valores
        function calcular()
        {
            var valor1=validarNumero('#cant');
            var valor2=validarNumero('#precio');
			var total =valor1*valor2;
            
            $("#total").val(total.toFixed(0));
        }
        
        // Funcion para validar que el numero sea correcto, y para cambiar el color
        // del marco en caso de error
        function validarNumero(id)
        {
            if($.isNumeric($(id).val()))
            {
                $(id).css('border-color','#808080');
                return parseFloat($(id).val());
            }else if($(id).val()==""){
                $(id).css('border-color','#808080');
                return 0;
            }else{
                $(id).css('border-color','#f00');
                return 0;
            }
        }
    </script>

   
    




	
    

