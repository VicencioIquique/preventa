<?php 
require_once("clases/conexionocdb.php");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
?>
<!doctype html>
 
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Dialog - Modal form</title>
  <script language="javascript" type="text/javascript" src="js/script.js"></script>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  
  <link rel="stylesheet" type="text/css" href="temas/minimalplomo/minimalplomo.css"><!-- estilos geneales-->
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <style>
    body { font-size: 62.5%; }
    label, input { display:block; }
    input.text { margin-bottom:0x; width:20%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    div#users-contain { width: 350px; margin: 20px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
  </style>
  <script>
  $(document).ready(function() {
	fn_anade_lista();	//eliminar de lista
	
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
        "Create an account": function() {
          var bValid = true;
          allFields.removeClass( "ui-state-error" );
 
          bValid = bValid && checkLength( name, "username", 3, 16 );

          bValid = bValid && checkLength( password, "password", 5, 16 );
 
          bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
          
       
 
          if ( bValid ) {//cuando valida las condiciones de arriba inserta una fila en la lista de abajo, tambien se puede utilizar ajax para insertar en la bdx
            $( "#users tbody" ).append( "<tr>" +
              "<td>" + name.val() + "</td>" +
              "<td>" + email.val() + "</td>" +
              "<td>" + password.val() + "</td>" +
            "</tr>" );
            $( this ).dialog( "close" );
          }
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
        allFields.val( "" ).removeClass( "ui-state-error" );
      }
    });
 
    $( "#create-user" )
      .button()
      .click(function() {
        $( "#dialog-form" ).dialog( "open" );
      });
  });
  </script>
</head>
<body>
 
<div id="dialog-form" title="Agregar a la Lista de Solicitud">
  <p class="validateTips">Ingrese la cantidad y luego haga click! en el signo <img src="images/add.png" /></p>
 
  <form>
  <fieldset>
   <!-- <label for="name">Name</label>
    <input name="name" type="text" class="text ui-widget-content ui-corner-all" id="name"  />
    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
    <label for="password">Password</label>
    <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />-->

  
   <table  id="table-6" class="lista">
              <thead>
                    <tr>
                        <th>código</th>
                        <th>descripción</th>
                        <th>stock tienda</th>
                        <th>cant. solicitada</th>
              			<th>cant. solicitada</th>
                        
                    </tr>
                </thead>
                <tbody id="tablebody">
                   <?php
                 
				 	$sql="SELECT [Bodega]
						  ,[Alu]
						  ,[ItemName]
						  ,[Name]
						  ,[Cantidad]
					  FROM [RP_VICENCIO].[dbo].[StockTiendaMarcaBodega]
					  
					  WHERE [NAME] = 'AZZARO' AND [Bodega] = '003' ";

					
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
										<td ><stron>'.$resultados["Alu"].'</strong></td>
										<td >'.$resultados["ItemName"].'</td>
										<td >'.(int)$resultados["Cantidad"].'</td>
										<td ><input type="text" name="name" id="cantsol" class="text ui-widget-content ui-corner-all" maxlength="3" value="23" /></td>
										<td><a class="anade_lista" id="'.$resultados["Alu"].'"><img src="images/add.png" /></a></td>
									</tr>';  
							 echo '</tr>';
								}
							 ?>
                </tbody>
                 </table>
         </fieldset>
  </form>
 
</div> <!-- fin emergente-->
 
 
<div id="users-contain" class="ui-widget">
  <h1>Existing Users:</h1>
  <table id="users" class="ui-widget ui-widget-content">
    <thead>
      <tr class="ui-widget-header ">
        <th>Name</th>
        <th>Email</th>
        <th>Password</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John Doe</td>
        <td>john.doe@example.com</td>
        <td>johndoe1</td>
      </tr>
    </tbody>
  </table>
</div>
<button id="create-user">Create new user</button>
 
 
</body>
</html>