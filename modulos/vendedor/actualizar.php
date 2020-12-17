<link rel="stylesheet" type="text/css" href="../../temas/minimalplomo/minimalplomo.css"><!-- estilos geneales-->
<script type="text/javascript" language="javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" language="javascript" src="../../js/modal-window.min.js"></script>
<script type="text/javascript">
/*  $(document).ready(function(){

               $(this).modal({width:833, height:453}).close(); return false;

            });
  
  


  */
 
</script>

<?php 
require_once("../../clases/conexion.php"); 

$nombrepais=$_POST['nombrePais'];
$id=$_POST['idpais'];
echo "<div id='mensajeactualizado'>Datos Actualizados</div>";
$db = new MySQL();
$update = $db->update("UPDATE  pais
						SET nombre = '$nombrepais'
						WHERE   pais_id = $id ") or die (mysql_error());





?>


