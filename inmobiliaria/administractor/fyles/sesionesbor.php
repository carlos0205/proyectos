<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	// fucion validar permisos de acceso a usuario
	require 'general/permisos.php';
	$prog = 'sesiones.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
			
		function array_recibe($url_codusu) { 
			$tmp = stripslashes($url_codusu); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codusu=$_GET['codusu']; 	
	
		$codusu=array_recibe($codusu); 
		
		$qryusuario = "DELETE FROM sesiones WHERE codusu IN (".implode(',',$codusu).") AND invitado='1'";
		$resusuario =mysql_query($qryusuario,$enlace);		
		}

?>
	<script language = JavaScript type="text/javascript">
	location = "sesiones.php";
	</script><title>Admin-Web</title>';

