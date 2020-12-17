<?php
include("clases/verificar.php"); 
		
	class modulos{
	
		public $opcion;
	
		function mostrar()
		{
			 $id=$_GET['opc']; //se captura la opcion elegida
			 					
			
			if($_SESSION["usuario_rol"] == 1) // Opciones para el ROOT
		  	{ 
		 		switch ($id) 
						{	
							case 'login':
								 include("modulos/sesion/index.php");
							   break;
							case 'valida':
								 include("modulos/sesion/doLogin.php");
							   break;
							case 'acerca':
								 include("modulos/acerca/index.php");
							   break;
							case 'logout':
								session_unset();
								session_destroy();
								echo '<script languaje="javascript"> window.location="index.php"; </script>';	
							   break;
							case 'ticket':
								 include("modulos/ticket/index.php");
							   break;
							
							default:
								 include("modulos/ticket/index.php");
							   break;
						 }  
		  } // fin opciones de ROOT
			  
		
			  
		else 
		{ 
			 switch ($id) 
			{
							case 'login':
								 include("modulos/sesion/index.php");
							   break;
							case 'valida':
								 include("modulos/sesion/doLogin.php");
							   break;
							case 'acerca':
								 include("modulos/acerca/index.php");
							   break;
							case 'logout':
								session_unset();
								session_destroy();
								echo '<script languaje="javascript"> window.location="index.php"; </script>';	
							   break;
							default:
								 include("modulos/sesion/index.php");
							   break;
						 }  
			 
		      } // fin else de acceso
		}
	
}//fin class	
	
	
	
	


?>