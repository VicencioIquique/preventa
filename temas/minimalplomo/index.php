<?php 
require_once("clases/menu.php");
require_once("clases/modulos.php");
require_once("clases/funciones.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>.: witRetail :.</title>
<link rel="stylesheet" type="text/css" href="temas/minimalplomo/minimalplomo.css"><!-- estilos geneales-->
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"><!-- estilos geneales-->
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js' type='text/javascript'/></script>
<script type="text/javascript" language="javascript" src="js/jquery-ui.js"></script>

<script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
<script language="javascript" type="text/javascript" src="js/script.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.idTabs.js"></script>

<script language="javascript" type="text/javascript" src="js/jquery.Rut.js" ></script>
<script language="javascript" type="text/javascript" src="js/jquery.Rut.min.js" ></script>
<script type="text/javascript" language="javascript" src="js/jquery-1.3.2.js"></script>
<script type="text/javascript" language="javascript" src="js/modal-window.min.js"></script>









</head>

<body>


<div id="contenedor">
	<div id="header">
		<h1><?php echo $config['nombreSistema'];?></h1>
	<div id="navegacion">
		<?php  
			 $menu = new menu();
			 $menu->mostrar(); ?>
			
	</div><!-- fin navegacion  -->
	<div id="cuerpo">
			<?php  
			 $modulo = new modulos();
			 $modulo->mostrar(); ?>
	</div><!-- fin   cuerpo-->
    </div>
</div><!-- fin contenedor-->

</body>


</html>
