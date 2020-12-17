<?php 
require_once("../../clases/conexion.php"); 

$id=$_GET['id'];

$db = new MySQL();
$sql= $db->consulta("SELECT * FROM pais WHERE pais_id =$id");
$resultados = $db->fetch_array($sql);

?>
<link rel="stylesheet" type="text/css" href="../../temas/minimalplomo/minimalplomo.css"><!-- estilos geneales-->
<form action="../pais/actualizar.php" method="post" id="horizontalForm">
            <fieldset>
				<legend>Editar País</legend>
                             <input name="idpais" type="hidden" id="idpais" size="40"  value="<?php echo $id ;?>" />
                             <label for="name1">
					            Nombre País
                             
                             <input name="nombrePais" type="text" id="nombrePais" size="40" class="required" value="<?php echo $resultados['nombre'];?>" />
                            </label>
                           <input name="agregar" type="button" id="agregar" class="submit" value="Cerrar" onclick=" parent.$.modal().close();"/>
                           <input name="agregar" type="submit" id="agregar" class="submit" value="Editar" />
                    		
                	
	</fieldset>
</form>