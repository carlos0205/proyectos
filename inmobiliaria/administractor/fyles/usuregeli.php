<?php
	session_start();
	include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'usuregeli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
			
		function array_recibe($url_codreg) { 
			$tmp = stripslashes($url_codreg); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codreg=$_GET['codreg']; 	
		$codreg=array_recibe($codreg); 
		
		$qryusu = "DELETE FROM usutercli WHERE codusucli IN (".implode(',',$codreg).") ";
		$resusu =mysql_query($qryusu,$enlace);	
	
		echo '<script language = JavaScript>
		location = "usureg.php";
		</script>';
	}
	
?>