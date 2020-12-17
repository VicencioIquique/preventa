<?php 
		
class menu{
	
		function mostrar()
		{	
		
			if($_SESSION["usuario_rol"] == 1) // Menu para ROOT
   		    { 
		 	echo "	
				<div class='menu-secondary-wrap'>
						<ul class='menus menu-secondary'>
							
							<li><a href='index.php?opc=ticket'>Ticket</a>
							 <!-- <ul class='children'>
								<li><a href='index.php?opc=logout'>Salir del Sistema</a></li>
							  </ul>-->
							</li>
							<li><a href='http://eximben.cl/'>Config</a></li>
						</ul>	
							
					</div> <!-- fin menu -->";
			} // fin menu para ROOT
			  	 
	}
	
}// Fin class Menu	

?>