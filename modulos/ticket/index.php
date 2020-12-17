<?php
	require_once("../../clases/conexionocdb.php");
	session_start();
	session_destroy();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="formulario/js/jquery-1.11.0.min.js"></script>
<!-- INICIO Bootstrap -->
<!-- CSS -->
	<!-- BOOSTRAP -->
<link href="formulario/css/bootstrap.css" rel="stylesheet">
<style>
	body {
		background-image: url("images/background.jpg");
	}
</style>
<!-- JAVASCRIPT -->
	<!-- BOOSTRAP -->
<script src="formulario/js/bootstrap.min.js"></script>

<!-- FIN Boostrap -->
<title>INICIO PREVENTAS</title>

<script>
	$(document).ready(function(){
		$("#credencial").focus();
		$("#consultarPrecio").click(function(){
			location.href='consultaPrecio/index.php';
		});
	});
</script> 
</head>

<body>
	<div class="row" style="margin-left:10px; margin-right:10px; margin-top:15px;"><!-- FIN DEL ROW -->
		<div class="col-md-4 col-md-offset-4" style="margin-top:250px;">
			<center><img src="vicencio.png" width="300"/></center>
			<form action="script/login.php" method="post">
				<!--<label for="exampleInputPassword1" style="margin-top:40px; color:white;">Credencial</label>--><br><br>
				<input type="password" class="form-control" name="credencial" id="credencial" placeholder="Ubique su credencial en el lector">
			</form>
		</div>
		<div class="col-md-4 col-md-offset-4" style="margin-top:100px;">
			<center><button class="btn btn-info" style="padding:50px 100px 50px 100px; font-size:18px;" id="consultarPrecio">Consultar precio</button></center>			
		</div>
    </div>
	
</body>
</html>
