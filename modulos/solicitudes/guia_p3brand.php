<?php 
require_once("clases/conexion.php");


$id = $_GET['guianumero'];




?>
	<form id="horizontalForm" name="form1" method="post" action="index.php?opc=p3">
    <div id="datosguiad">
     <div class="paso" >1 Paso</div><div class="paso" >2 Paso</div><div class="paso_selected"  >3 Paso</div> <br /><br /><br /> </div><!-- fin div datosguiad -->
    <fieldset>
    
    <div id="print">


    
    <a class="col_print" target="_blank" href="modulos/impresiones/pdf_guiaEmitida.php?id=<?php echo $id;?>"><img src="images/imprimiruno.png" width="128" height="128" /></a>
    <a class="col_print" target="_blank" href="modulos/impresiones/pdf_guianueva2.php?id=<?php echo $id;?>"><img src="images/imprimirdos.png" width="128" height="128" /></a>
    <a class="col_print" target="_blank" href="modulos/impresiones/pdf_descarga.php?id=<?php echo $id;?>"><img src="images/descargar.png" width="128" height="128" /></a>
    <a class="col_print" target="_blank" href="modulos/impresiones/pdf_descarga2.php?id=<?php echo $id;?>"><img src="images/descargar2.png" width="128" height="128" /></a>
    
    </div><!-- fin   print-->
    
    <input type="submit" value="Finalizar"  class="submit" style="float:right; margin-top:-30px;"/>
    </fieldset>
    </form>
    

