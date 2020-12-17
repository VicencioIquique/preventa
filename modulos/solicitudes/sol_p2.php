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
if($estado != 0)
{
	echo '<meta http-equiv="Refresh" content="0;url=index.php?opc=nuevaSolicitud">';
}

?>
 <script src='js/jquery.min.js'></script>
 <script src="js/addboton2.js"></script>
 <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
 <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
 <script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>	
 <script type="text/javascript">
 
 $(document).keydown(function(tecla){ // para presionar botones de teclado
    if (tecla.keyCode == 38) {  //presiona arriba
			   // alert('Tecla arriba presionada');
				var oldValue =$("#cant").val();
			    var newVal = parseFloat(oldValue) + 1;
			    $("#cant").val(newVal);
				var valorUnitario = $("#precio").val();// para obtener el precio del producto
			    //$button.parent().parent().parent().find("#total").val();// para obtener el total del producto
			    var total = valorUnitario*newVal;
			   //alert(valorUnitario*newVal);
			 // $("#total").val(total.toFixed(0));// para darle el total del producto
    }
	if (tecla.keyCode == 40) { //presiona abajo
        
				var oldValue =$("#cant").val();
				if (oldValue > 0) 
				{
				var newVal = parseFloat(oldValue) - 1;
				}  
				else 
				{
				newVal = 0;
			    } 
				$("#cant").val(newVal);
			   var valorUnitario = $("#precio").val();// para obtener el precio del producto
			    //$button.parent().parent().parent().find("#total").val();// para obtener el total del producto
			    var total = valorUnitario*newVal;
			   //alert(valorUnitario*newVal);
			 //  $("#total").val(total.toFixed(0));// para darle el total del producto
			   
    }//fin tecla abajo
	if (tecla.keyCode == 16) { //presiona shift envia formulario
        
			  // $('#horizontalForm').submit();
    }//fin tecla shift
	
/*	if (tecla.keyCode == 17) { //presiona enter agrega producto
        
			 fn_agregar_lista();
    }//fin tecla enter*/
	
});
 
$(document).ready(function() {
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
	
	
	
	/*$(".auto").autocomplete({
    source: "modulos/solicitudes/barticulos.php",
    select: function( event, ui ) { alert("Item selected! Let's not trigger blur!"); }
})	*/

		

});

</script>
 

<form id="horizontalForm" name="form" method="get" action="">
   <div id="datosguiad">
     <div  class="paso">1 Paso</div><div  class="paso_selected" >2 Paso</div><div class="paso" >3 Paso</div><br /><br /><br />	

     <fieldset>
      <legend>Productos para Solicitud: <?php echo $idsol;?></legend>
	
      
      <input name="opc" type="hidden" id="opc" size="40" class="required" value="paso2" />
      <input name="idsol" type="hidden" id="idsol" size="40" class="required" value='<?php echo $idsol;?>' />
       <input name="idarticulo" type="hidden" id="idarticulo" size="40" class="required" value='<?php echo $idarticulo;?>' />
       <input name="unidadarticulo" type="hidden" id="unidadarticulo" size="40" class="required" value='<?php echo $unidadarticulo;?>' />
      <table  id="tablaarticulo" border="0">
          
            <tbody>

								  <tr>
                                    <td> <label for="fecha1">
					            Busqueda de producto<input type='text' name='articulo' id="articulo"  value='' class='auto' onBlur="javascript: fn_buscar_stock();"></label></td>						
                                      <td><label for="fecha1">Stock<input type='text' name='' id="stock"  value=''  ></label></td>						
                                  
								 
                                  <td id="cajacant"><div class="numbers-row">
        								<input type="text" id="cant" maxlength="6"  class="mini" value="0" >
      								   </div>
								  </td>
                                   

								</tr>							
					
            </tbody>
          </table>   
      
      <input type="button" value="Agregar"  class="submit" onclick="javascript: fn_agregar_lista();" />
     </fieldset>

    </div>
    </form >
    
 
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
WHERE     (dbo.sisap_solicitudes.solicitud_id = ".$idsol.")";

					
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
										<td >'.$resultados["cant_aceptada"].'</td>
										<td><a class="elimina_lista" id="'.$resultados["codigo"].'"><img src="images/delete.png" /></a></td>
									</tr>';  
																
						
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
				if($estado ==1)
				{
				echo ' <input type="button" value="Validar"  class="submit" style=" float:left; margin-top:0px;" onclick="javascript: fn_finalizar_guia();" />';	
				}
				?>
    			</div><!-- fin   -->
                     	
                                        
            </form>
            
            
            <fieldset class="caja" style="width:860px; float:left; margin-left:10px; padding-top:10px;" >
            			 <label style="margin-right:20px;" >Mauricio H.<input id="mauricio"  type="checkbox" /> </label>
                            <label style="margin-right:20px;">Marianela V.<input id="marianela"  type="checkbox" /> </label>
                            <label style="margin-right:20px;">Rosa Z.<input id="rosa"  type="checkbox" /> </label>
                            <label style="margin-right:20px;">Marieliza M.<input id="marieliza"  type="checkbox" /> </label>
                            
						</fieldset> 
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

   
    




	
    

