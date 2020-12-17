<?php 

require_once("clases/conexion.php");
$db = new MySQL();


if (isset ($_POST['aceptar'])) {
	$nombreSistema = $_POST["nombreSistema"];
	$temaid = $_POST["temaid"];
	if($preciou = $_POST["onoffswitch"])
	{ $preciou = "checked";}
	

		$update = $db->update("UPDATE config_sistema SET  preciounitario ='$preciou'") or die (mysql_error());
		
		echo '<meta http-equiv="refresh" content="0">';
	
}
$consulta = $db->consulta("SELECT tema.id as temaid,tema.nombre as temanombre FROM tema,config_sistema");
$consulta2 = $db->consulta("SELECT * FROM tema,config_sistema");
$resultado = $db->fetch_array($consulta2);    

echo'	
			

		

<form  action="index.php?opc=configuracion" method="POST" id="horizontalForm" >
			<fieldset>
				<legend>
					Configuraciones del Sistema
				</legend>	
					
					<table id="ssptable">
  <tr>
    <td  style=" font-size: 18px;">
					Precio unitario en Impresión:
				
				</td>
    <td><div class="onoffswitch">
				<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" '.$resultado['preciounitario'] .'>
				<label class="onoffswitch-label" for="myonoffswitch">
					<div class="onoffswitch-inner"></div>
					<div class="onoffswitch-switch"></div>
				</label>
			</div></td>
  </tr>
  
</table>
				
				
				
				
                <input class="submit" type="submit" value="Aceptar" name="aceptar" />
			</fieldset>		
		</form>';
?>