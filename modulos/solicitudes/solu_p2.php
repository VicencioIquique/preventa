<?php 
require_once("clases/conexionocdb.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

 $idsol = $_GET['idsol'];
 $bmarca = $_GET['marca'];
  $costo = $_GET['costo'];
 
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
 <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css" /> 
   <link rel="stylesheet" href="css/jquery-ui.css" />
  <script src="js/jquery-ui.js"></script>
  
 <script type="text/javascript">
  $(document).ready(function() {
  
	fn_anade_lista();	//eliminar de lista
	
	$("#loadscreen").hide();
	
	
  });

  $(function() {
    var name = $( "#name" ),
      email = $( "#email" ),
      password = $( "#password" ),
      allFields = $( [] ).add( name ).add( email ).add( password ),
      tips = $( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "Length of " + n + " must be between " +
          min + " and " + max + "." );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 600,
      width: 1000,
      modal: true,
      buttons: {
        "Aceptar": function() {
            
            $( this ).dialog( "close" );
			location.href = 'index.php?opc=pasodos&idsol='+ $("#idsol").val();
        },
       "cerrar": function() {
          $( this ).dialog( "close" );
		  location.href = 'index.php?opc=pasodos&idsol='+ $("#idsol").val();
        }
      },
      close: function() {
        allFields.val( "" ).removeClass( "ui-state-error" );
		//location.href = 'index.php?opc=pasodos&idsol='+ $("#idsol").val();
      }
    });
 
  
	  
	 
	  
	 <?php // cargar emergente si se ha elegido una marca
	 if($bmarca)
     echo' 
	 
	  $("#loadscreen").show();
	 $(window).load(function() {
		 $( "#dialog-form" ).dialog( "open" );
		 
		  $("#loadscreen").hide();
		});
		
	  //$("#dialog-form" ).html("<img id=\"loader\" src=\"images/loader.gif\">");
	
	 
	 ';
      
      ?>
	 
  }); // fin script para popup
 
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
		source: "modulos/solicitudes/bmarcas.php",
		minLength: 1,
		
	});
	

});



</script>
<style>
  #loadscreen{
                 background:url(images/bg.png)repeat;
                 height:1050px;
                 width:100%;
                 left:0;
                 top:0;
                 position:fixed;
                 text-align:center;
                 z-index:2000;
 }
  #loadscreen img{
position: absolute;
top: 40%;
left: 46%;
margin: -50px 0px 0px -50px;
}


    input.text { margin-bottom:0x; width:20%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }

  </style>


  <div id="loadscreen">
                     <img title="Maxcode" alt="Maxcode" src="images/loader.gif" >
     </div>
  
   <div id="dialog-form" title="Agregar a la Lista de Solicitud">
             <?php
			
			$sqlcont="SELECT     COUNT(*) as cuenta
FROM         dbo.oITM_From_SBO LEFT OUTER JOIN
                      dbo.View_OMAR ON dbo.View_OMAR.Code = dbo.oITM_From_SBO.U_VK_Marca
WHERE     (dbo.View_OMAR.Name = '".$bmarca."'  AND (dbo.oITM_From_SBO.ItmsGrpCod <> 103) )";
					  
					  $rscont = odbc_exec( $conn, $sqlcont );
							if ( !$rscont )
							{
							exit( "Error en la consulta SQL" );
							}
							$arr = odbc_fetch_array($rscont);
							//echo $arr['counter'];
							
							$cuenta = $arr['cuenta'];
							IF ($costo==0)
							{
								$Wcosto = "  AND (dbo.oITM_From_SBO.U_ZF_TIPITM = 'M')";
							}
							
				 	$sql="SELECT     dbo.oITM_From_SBO.ItemCode, dbo.oITM_From_SBO.ItemName, dbo.View_OMAR.Name, TABLA.Cantidad
FROM         (SELECT     Alu, Cantidad
                       FROM          dbo.VerStockTiendas
                       WHERE      (Bodega = ".$_SESSION["usuario_modulo"].")) AS TABLA FULL OUTER JOIN
                      dbo.oITM_From_SBO ON dbo.oITM_From_SBO.ItemCode = TABLA.Alu COLLATE SQL_Latin1_General_CP850_CI_AS LEFT OUTER JOIN
                      dbo.View_OMAR ON dbo.View_OMAR.Code = dbo.oITM_From_SBO.U_VK_Marca
WHERE     (dbo.View_OMAR.Name = '".$bmarca."')  AND (dbo.oITM_From_SBO.ItmsGrpCod <> 103) ".$Wcosto."
ORDER BY dbo.oITM_From_SBO.ItemName";

					//echo $sql;
					

							//echo $sql;	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}
if($cuenta>0)
{	
							
?>
 
  <form>
  <fieldset>  
<div id="contenido">  
   <table  id="table-6" class="lista">
              <thead>
                    <tr>
                        <th>código</th>
                        <th>descripción</th>
                        <th>stock tienda</th>
                        <th>reposición</th>
              			<th>&nbsp;</th>
                        
                    </tr>
                </thead>
                <tbody id="tablebody">
        <?php
							  while($resultados = odbc_fetch_array($rs)){ 
							 echo '<tr >
										<td ><strong>'.$resultados["ItemCode"].'</strong></td>
										<td >'.utf8_encode($resultados["ItemName"]).'</td>
										<td >'.(int)$resultados["Cantidad"].'</td>
										<td ><input type="text" name="name" id="cantsol" class="text ui-widget-content ui-corner-all" maxlength="3" value="0" /></td>
										<td><a class="anade_lista" id="'.$resultados["ItemCode"].'"><img src="images/add.png" /></a></td>
									</tr>';  
						
								}
							 ?>
                </tbody>
                 </table>
				  </div>
<?php  } //fin if cuando hay registros <0 
else echo ' <p class="validateTips">No se encontraron Registros</p>';?>
         </fieldset>
  </form>

</div> <!-- fin emergente-->
 

<form id="horizontalForm" name="form" method="get" action="">
   <div id="datosguiad">
     <div  class="paso"><a href="index.php?opc=nuevaSolicitud">1 Paso</a></div><div  class="paso_selected" >2 Paso</div><div class="paso" >3 Paso</div><br /><br /><br />	

     <fieldset>
      <legend>Productos para Solicitud: <?php echo $idsol;?></legend>
	
      
      <input name="opc" type="hidden" id="opc" size="40" class="required" value="pasodos" />
      <input name="idsol" type="hidden" id="idsol" size="40" class="required" value='<?php echo $idsol;?>' />
       <input name="idarticulo" type="hidden" id="idarticulo" size="40" class="required" value='<?php echo $idarticulo;?>' />
       <input name="unidadarticulo" type="hidden" id="unidadarticulo" size="40" class="required" value='<?php echo $unidadarticulo;?>' />
      <table  id="tablaarticulo" border="0">
          
            <tbody>

								  <tr>
								   
                                    <td> <label for="fecha1">
					            Buscar Marca<input type='text' name='articulo' id="articulo"  value='' class='auto' ></label></td>	
									 
									 <td><label  style="display:inline;" for="fecha1">  Todos los Articulos
									<input style="display:inline;" type="checkbox" name="costo" id="costo" /> </label> </td>
                                     <td> <input type="button" value="Buscar"  class="submit" id="enviarmarca" onclick="javacript: fn_enviar_marca();" /></td>				
									
								</tr>							
					
            </tbody>
          </table>   
      
  
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
										<td >'.utf8_encode($resultados["descripcion"]).'</td>
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
							Todos los pedidos deben ser ingresados antes de las 14:00 horas del día Sábado.
                            
						</fieldset> 
						
						

        
 <?php odbc_close( $conn );?>




	
    

