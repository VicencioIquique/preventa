<?php
	//include("script/autodestroy.php");
	require_once("../../../clases/conexionocdb.php");
	session_start();
	
	$sql="SELECT * FROM [RP_VICENCIO].[dbo].[RP_IP_BODEGAS] WHERE ip = '".$_SESSION['bodega']."'";
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$ip = $resultado['ip'];
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="../formulario/js/jquery-1.11.0.min.js"></script>
<!-- INICIO Bootstrap -->
<!-- CSS -->
	<!-- BOOSTRAP -->
<link href="../formulario/css/bootstrap.css" rel="stylesheet">


<!-- JAVASCRIPT -->
	<!-- BOOSTRAP -->
<script src="../formulario/js/bootstrap.min.js"></script>

<!-- FIN Boostrap -->
<title>PREVENTA - Consulta de precios</title>

<style>
	/*body {
		background-image: url("images/background.jpg");
	}*/
</style>
<script>
	
	/*ACTUALIZAR PARA CERRAR SESIÓN DESPUÉS DE 10 SEGUNDOS DE INACTIVIDAD*/
	var tim = 0;
	function reload () {
		tim = setTimeout("location.href='../index.php';",30000);   // 3 minutes
	}
	function canceltimer() {
		window.clearTimeout(tim);  // cancel the timer on each mousemove/click
		reload();  // and restart it
	}
	/*FIN ACTUALIZAR PARA CERRAR SESIÓN*/
	$(document).ready(function() {
		$('#codigoBusq').focus();
		$("#codigoBusq").keypress(function(e){
			var code = e.keyCode || e.which;
			if(code == 13) { 
				window.codBusq = $("#codigoBusq").val();
				if(codBusq == ""){
					alert("Por favor ingrese un código de barra");
				}else{
					
					var codigoCantidad = Array();
					codigoCantidad.push(codBusq);
					$.post('../formulario/script/obtenerProductoCant.php', {codCantidad : codigoCantidad}, function(resPHP){
						var respuesta = $.parseJSON(resPHP);
						if(respuesta['cantidad'] == null){
							respuesta['cantidad']= '0';
						}
						
						var codigo = Array();
						codigo.push(codBusq);
						$.post('../formulario/script/obtenerProductoCod.php',{cod : codigo},function(resPHP){
							var result = $.parseJSON(resPHP);
							if(result['upc'] == null){
								alert("Artículo o producto no encontrado");
								$("#codigoBusq").val("");
							}else{
								$('#example tr:last').after("<tr><td class='info art'>"+result['articulo']+"</td><td class='info upc'>"+result['upc']+"</td><td class='info descripcion'>"+result['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+respuesta['cantidad']+"'/></td></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td></td></tr>");
							}
						});
					});
					
					$('#codigoBusq').val("");
					$('#codigoBusq').focus();
				}
			}
		});
		$("#volver").click(function(){
			location.href='../index.php';
		});
	});
</script>
<!-- AUTO REFRESH PARA AUTODESLOGEO DE SESION-->
<script type = "text/javascript">

</script>
<!-- FIN AUTO REFRESH -->
</head>

<body onmousemove = "canceltimer()" onclick = "canceltimer()">
<br>
	<div class="row" style="margin-left:10px; margin-right:10px; margin-top:20px;">
        <div class="col-lg-10 col-lg-offset-1">
        	<div class="form-group">
                <label for="exampleInputEmail1">Código a buscar</label>
                <input type="text" class="form-control" id="codigoBusq" placeholder="Código" style="width:300px;" onmousemove = "canceltimer()" onclick = "canceltimer()" onkeypress = "canceltimer()"><br />
				<!-- -->
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Art#</th>
                            <th>UPC</th>
                            <th>Descripción</th>
                            <th>Cantidad en stock</th>
                            <th>Valor unidad</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <center><button type="submit" id ="volver" class="btn btn-info" style="width:160px; height:100px; margin-right:100px;">Volver</button></center>
        	</div>
    	</div><!-- FIN DEL ROW -->
    </div>
</body>
</html>
